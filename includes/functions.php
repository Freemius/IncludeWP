<?php
    define('SCRIPT_START', time());
    global $LOG_COUNTER;
    $LOG_COUNTER = 0;

    function console_log($msg)
    {
        global $LOG_COUNTER;
        echo '[' . number_format(time() - SCRIPT_START) . 's] [' . ($LOG_COUNTER ++) . "] $msg\n";
    }

    /**
     * Execute simple GET request to a given URL.
     *
     * @param string $url
     *
     * @return string
     */
    function get_content_from_github($url)
    {
        $ch = curl_init();

        $options = array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CONNECTTIMEOUT => 1,
            CURLOPT_USERAGENT      => 'Chrome',
        );

        curl_setopt_array($ch, $options);

        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }

    /**
     * Pull framework stats from GitHub and enrich the $framework object.
     *
     * @param array $framework
     */
    function enrich_with_github(&$framework)
    {
        console_log($framework['slug'] . ' - Fetching details from GitHub API.');

        // Fetch details from GitHub API.
        $github_repo_json = get_content_from_github('https://api.github.com/repos/' . $framework['github_repo'] . '?access_token=' . GITHUB_ACCESS_TOKEN);
        $github_repo      = json_decode($github_repo_json, true);

        $framework['github'] = array(
            'stars'  => intval($github_repo['watchers_count']),
            'forks'  => intval($github_repo['forks_count']),
            'issues' => intval($github_repo['open_issues']),
        );
    }

    /**
     * Pull plugin's info from WordPress.org plugins' API and enrich the $framework object.
     *
     * @param array $framework
     */
    function enrich_with_wp(&$framework)
    {
        console_log($framework['wp_slug'] . ' - Fetching details from WordPress.org Plugins API.');

        $wp_repo = null;
        $retries = 3;

        while ($retries > 0 && ( ! is_array($wp_repo) || empty($wp_repo['name'])))
        {
            // Fetch details from WordPress.org plugins API.
            $wp_repo_json = file_get_contents('http://api.wordpress.org/plugins/info/1.0/' . $framework['wp_slug'] . '.json?fields=active_installs,icons,banners');
            $wp_repo      = json_decode($wp_repo_json, true);

            if ( ! is_array($wp_repo) || empty($wp_repo['name']))
            {
                // Probably blocked by .org.
                $sleep = rand(10, 30);
                console_log($framework['slug'] . " - Request failed, going to sleep for {$sleep} sec (retry " . (4 - $retries) . ")");
                sleep($sleep);
                $retries --;
            }
        }

        $framework['wordpress'] = array(
            'name'              => $wp_repo['name'],
            'short_description' => $wp_repo['short_description'],
            'homepage'          => $wp_repo['homepage'],
            'downloads'         => intval($wp_repo['downloaded']),
            'active'            => intval($wp_repo['active_installs']),
            'avg_rate'          => number_format(5 * (floatval($wp_repo['rating']) / 100), 2),
            'votes'             => intval($wp_repo['num_ratings']),
        );

        if (isset($wp_repo['banners']))
        {
            // Try to fetch banner from API.
            if (isset($wp_repo['banners']['low']))
                $framework['banner'] = $wp_repo['banners']['low'];
            else if (isset($wp_repo['banners']['high']))
                $framework['banner'] = $wp_repo['banners']['high'];
        }
    }

    /**
     * Pull plugin's info from WordPress.org plugins' API and enrich the $framework object.
     *
     * @param array $theme
     */
    function enrich_with_wp_theme(&$theme)
    {
        console_log($theme['wp_slug'] . ' - Fetching details from WordPress.org Themes API.');

        $api_endpoint = 'http://api.wordpress.org/themes/info/1.0/';

        $ch      = curl_init();
        $options = array(
            CURLOPT_URL            => $api_endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query(array(
                'action'  => 'theme_information',
                'request' => serialize((object) array(
                    'slug'   => $theme['wp_slug'],
                    'fields' => array(
                        'homepage'        => true,
                        'theme_url'       => true,
                        'description'     => true,
                        'sections'        => false,
                        'screenshot_url'  => true,
                        'active_installs' => true,
                    ),
                ))
            )),
        );

        curl_setopt_array($ch, $options);

        $result = curl_exec($ch);

        $wp_repo = get_object_vars(unserialize($result));

        $theme['wordpress'] = array(
            'name'              => $wp_repo['name'],
            'short_description' => $wp_repo['description'],
            'homepage'          => $wp_repo['theme_url'],
            'downloads'         => intval($wp_repo['downloaded']),
            'active'            => intval($wp_repo['active_installs']),
            'avg_rate'          => number_format(5 * (floatval($wp_repo['rating']) / 100), 2),
            'votes'             => intval($wp_repo['num_ratings']),
        );

        if (isset($wp_repo['screenshot_url']))
            $theme['banner'] = $wp_repo['screenshot_url'];
    }

    /**
     * Uses page2images API to snap a screenshot of the framework's homepage and enrich the $framework object.
     *
     * @param array $framework
     */
    function enrich_banner(&$framework)
    {
        if (isset($framework['homepage']))
        {
            console_log($framework['slug'] . ' - Enriching banner with homepage screenshot.');

            $http_homepage = urlencode(str_replace('https://', 'http://', $framework['homepage']));

            $screenshot_fetch_url = 'http://api.page2images.com/restfullink?p2i_url=' . $http_homepage . '&p2i_size=772x250&p2i_key=' . PAGE_2_IMAGES_REST_KEY;

            $screenshot_url = 'http://api.page2images.com/directlink?p2i_url=' . $http_homepage . '&p2i_size=772x250&p2i_key=' . PAGE_2_IMAGES_KEY;

            do
            {
                console_log($framework['slug'] . ' - Fetching homepage screenshot.');

                // Fetch screenshot.
                $result = file_get_contents($screenshot_fetch_url);

                $result = json_decode($result, true);

                if ('processing' === $result['status'])
                {
                    console_log($framework['slug'] . ' - Still processing, going to sleep for ' . $result['estimated_need_time'] . 's.');

                    // Wait till screenshot generated.
                    sleep($result['estimated_need_time']);
                }
            } while ('processing' === $result['status']);

            if ('finished' === $result['status'])
            {
                $screenshot_url = $result['image_url'];
            }

            $framework['banner'] = $screenshot_url;
        }
        else
        {
            console_log($framework['slug'] . ' - Enriching banner with random tech image.');

            // Use generic placeholder.
            $framework['banner'] = 'https://placeimg.com/518/168/tech';
        }
    }

    /**
     * Dump variable to a PHP file based on a given destination.
     *
     * @param array  $variable
     * @param string $name Variable name.
     * @param string $dest
     * @param bool   $strip_keys
     */
    function dump_var_to_php_file(&$variable, $name, $dest, $strip_keys = false)
    {
        if (file_exists($dest))
            unlink($dest);

        $export = var_export($variable, true);

        if ($strip_keys)
        {
            // Strip array numeric keys.
            $export = preg_replace('(\d+\s=>)', "", $export);
        }

        file_put_contents($dest, '<?php ' . $name . ' = ' . $export . ';');
    }

    /**
     * @param string $path
     *
     * @return string
     */
    function canonize_file_path($path)
    {
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('|/+|', '/', $path);

        return $path;
    }

    /**
     * @param string $slug
     *
     * @return array
     */
    function get_framework_items($slug)
    {
        console_log($slug . ' - Fetching framework items from Addendio.');

        $result = file_get_contents("https://includewp.firebaseio.com/{$slug}.json");

        return json_decode($result, true);
    }

    /**
     * Fetch framework plugins and themes, and merge them with the existing
     * item indexes.
     *
     * @param string $framework
     * @param string $plugins_dir
     * @param string $themes_dir
     */
    function dump_framework_items($framework, $plugins_dir, $themes_dir)
    {
        $slug = $framework['slug'];

        $framework_plugins_and_themes = get_framework_items($slug);

        if ($framework['is_for_plugins'])
        {
            $plugins_index = array();
            $plugins_count = - 1;
            if (file_exists($plugins_dir . '/' . $slug . '.php'))
            {
                require_once $plugins_dir . '/' . $slug . '.php';
                $plugins_count = count($plugins_index);
            }

            if (isset($framework_plugins_and_themes['plugins']) &&
                is_array($framework_plugins_and_themes['plugins'])
            )
            {
                // Union arrays.
                $plugins_index = array_unique(array_merge($plugins_index, $framework_plugins_and_themes['plugins']['slugs']));

                // Exclude the framework itself.
                $key = array_search($slug, $plugins_index);
                if (false !== $key)
                    array_splice($plugins_index, $key, 1);
            }

            if ($plugins_count !== count($plugins_index))
            {
                // Create index file only if not existed or if there are
                // new items added to the array.
                dump_var_to_php_file(
                    $plugins_index,
                    '$plugins_index',
                    $plugins_dir . '/' . $slug . '.php',
                    true
                );
            }
        }

        if ($framework['is_for_themes'])
        {
            $themes_index = array();
            $themes_count = - 1;
            if (file_exists($themes_dir . '/' . $slug . '.php'))
            {
                require_once $themes_dir . '/' . $slug . '.php';
                $themes_count = count($themes_index);
            }

            if (isset($framework_plugins_and_themes['themes']) &&
                is_array($framework_plugins_and_themes['themes'])
            )
            {
                // Union arrays.
                $themes_index = array_unique(array_merge($themes_index, $framework_plugins_and_themes['themes']['slugs']));
            }

            if ($themes_count !== count($themes_index))
            {
                // Create index file only if not existed or if there are
                // new items added to the array.
                dump_var_to_php_file(
                    $themes_index,
                    '$themes_index',
                    $themes_dir . '/' . $slug . '.php',
                    true
                );
            }
        }
    }