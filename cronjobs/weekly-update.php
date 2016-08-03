<?php
    require_once dirname(__DIR__) . '/includes/config.php';
    require_once dirname(__DIR__) . '/includes/functions.php';

    $framework_files = glob(dirname(__DIR__) . '/src/frameworks/*.php');

    if (true)
    {
        #region Frameworks update -----------------------------------------------------------

        $framework_files_output = dirname(__DIR__) . '/src/frameworks/compiled/';
        $plugins_dir            = dirname(__DIR__) . '/src/plugins/';
        $themes_dir             = dirname(__DIR__) . '/src/themes/';
        $index_path             = $framework_files_output . '_index.php';
        $updates_index_path     = $framework_files_output . '_updates.php';

        $frameworks_index   = array();
        $frameworks_updates = array();

        if (file_exists($updates_index_path))
            require_once $updates_index_path;

        $is_updated = false;

        foreach ($framework_files as $key => $file)
        {
            $file = canonize_file_path($file);

            $slug           = substr($file, strrpos($file, '/') + 1, - 4);
            $framework_path = $framework_files_output . substr($file, strrpos($file, '/') + 1);

            console_log($slug . ' - Starting to handle framework.');

            // Fetch data only if last cached more than a week ago.
            if (isset($frameworks_updates[$slug]) &&
                $frameworks_updates[$slug] >= (time() - WEEK_IN_SEC) &&
                file_exists($framework_path)
            )
            {
                console_log($slug . ' - Framework cache file is still valid (weekly refresh).');

                // Get framework cached data.
                require_once $framework_path;
            }
            else
            {
                // Get framework data.
                require_once $file;

                // Get slug from filename.
                $framework['slug'] = $slug;

                // Enrich with GitHub info.
                if (isset($framework['github_repo']))
                    enrich_with_github($framework);

                // Enrich with WordPress.org info.
                if (isset($framework['wp_slug']))
                    enrich_with_wp($framework);

                if ( ! isset($framework['banner']))
                    enrich_banner($framework);

                // Add banner protocol if missing before running via CDN.
                if ('/' === $framework['banner'][0])
                    $framework['banner'] = 'http:' . $framework['banner'];

                // Add images CDN proxy.
                $framework['banner'] = 'https://res.cloudinary.com/freemius/image/fetch/' . $framework['banner'];

                dump_var_to_php_file($framework, '$framework', $framework_path);

                $frameworks_updates[$slug] = time();

                // Fetch framework's themes and plugin slugs.
                dump_framework_items($framework, $plugins_dir, $themes_dir);

                $is_updated = true;
            }

            $frameworks_index[$slug] = $framework['github']['stars'];
        }

        if ($is_updated)
        {
        // Sort frameworks by stars.
        arsort($frameworks_index);

        // Dump index.
        dump_var_to_php_file($frameworks_index, '$frameworks_index', $index_path);

        // Dump framework update timestamp.
        dump_var_to_php_file($frameworks_updates, '$frameworks_updates', $updates_index_path);
        }

        #endregion Frameworks update -----------------------------------------------------------
    }

    if (true)
    {
        #region Plugins update -----------------------------------------------------------

        $plugin_files        = glob(dirname(__DIR__) . '/src/plugins/*.php');
        $plugin_files_output = dirname(__DIR__) . '/src/plugins/compiled/';

        foreach ($plugin_files as $key => $file)
        {
            $framework_plugins = array();

            $file = canonize_file_path($file);

            $slug = substr($file, strrpos($file, '/') + 1, - 4);

            $plugin_path = $plugin_files_output . substr($file, strrpos($file, '/') + 1);

            console_log($slug . ' - Starting to handle framework plugins.');

            // Get plugins index.
            require_once $file;

            $plugins = array();

            if (file_exists($plugin_path))
            {
                require_once $plugin_path;
            }

            $is_updated = false;

            foreach ($plugins_index as $plugin_slug)
            {
                console_log($slug . ' - ' . $plugin_slug . ' - Starting to handle plugin.');

                if (isset($plugins[$plugin_slug]) && $plugins[$plugin_slug]['last_update'] >= (time() - WEEK_IN_SEC) && $plugin_path)
                {
                    console_log($slug . ' - ' . $plugin_slug . ' - plugin cache file is still valid (weekly refresh).');

                    // Plugin data already pulled during the last week.
                    continue;
                }

                $plugins[$plugin_slug] = array('wp_slug' => $plugin_slug);

                enrich_with_wp($plugins[$plugin_slug]);

                // Handle case when plugin doesn't have a banner image.
                if ( ! isset($plugins[$plugin_slug]['banner']))
                {
                    if (isset($plugins[$plugin_slug]['wordpress']['homepage']))
                        $plugins[$plugin_slug]['homepage'] = $plugins[$plugin_slug]['wordpress']['homepage'];

                    enrich_banner($plugins[$plugin_slug]);

                    unset($plugins[$plugin_slug]['homepage']);
                }

                // Add banner protocol if missing before running via CDN.
                if ('/' === $plugins[$plugin_slug]['banner'][0])
                    $plugins[$plugin_slug]['banner'] = 'http:' . $plugins[$plugin_slug]['banner'];

                // Add images CDN proxy
                $plugins[$plugin_slug]['banner'] = 'https://res.cloudinary.com/freemius/image/fetch/' . $plugins[$plugin_slug]['banner'];

                $plugins[$plugin_slug]['last_update'] = time();

                $is_updated = true;
            }

            if ($is_updated)
                dump_var_to_php_file($plugins, '$plugins', $plugin_path);
        }

        #endregion Plugins update -----------------------------------------------------------
    }

    if (true)
    {
        #region Themes update -----------------------------------------------------------

        $theme_files        = glob(dirname(__DIR__) . '/src/themes/*.php');
        $theme_files_output = dirname(__DIR__) . '/src/themes/compiled/';

        foreach ($theme_files as $key => $file)
        {
            $framework_themes = array();

            $file = canonize_file_path($file);

            $slug = substr($file, strrpos($file, '/') + 1, - 4);

            $theme_path = $theme_files_output . substr($file, strrpos($file, '/') + 1);

            console_log($slug . ' - Starting to handle framework themes.');

            // Get themes index.
            require_once $file;

            $themes = array();

            if (file_exists($theme_path))
            {
                require_once $theme_path;
            }

            $is_updated = false;

            foreach ($themes_index as $theme_slug)
            {
                console_log($slug . ' - ' . $theme_slug . ' - Starting to handle theme.');

                if (isset($themes[$theme_slug]) && $themes[$theme_slug]['last_update'] >= (time() - WEEK_IN_SEC) && $theme_path)
                {
                    console_log($slug . ' - ' . $theme_slug . ' - theme cache file is still valid (weekly refresh).');

                    // Theme data already pulled during the last week.
                    continue;
                }

                $themes[$theme_slug] = array('wp_slug' => $theme_slug);

                enrich_with_wp_theme($themes[$theme_slug]);

                // Handle case when theme doesn't have a banner image.
                if ( ! isset($themes[$theme_slug]['banner']))
                {
                    if (isset($themes[$theme_slug]['wordpress']['homepage']))
                        $themes[$theme_slug]['homepage'] = $themes[$theme_slug]['wordpress']['homepage'];

                    enrich_banner($themes[$theme_slug]);

                    unset($themes[$theme_slug]['homepage']);
                }

                // Add banner protocol if missing before running via CDN.
                if ('/' === $themes[$theme_slug]['banner'][0])
                    $themes[$theme_slug]['banner'] = 'http:' . $themes[$theme_slug]['banner'];

                // Add images CDN proxy
                $themes[$theme_slug]['banner'] = 'https://res.cloudinary.com/freemius/image/fetch/' . $themes[$theme_slug]['banner'];

                $themes[$theme_slug]['last_update'] = time();

                $is_updated = true;
            }

            if ($is_updated)
                dump_var_to_php_file($themes, '$themes', $theme_path);
        }

        #endregion Themes update -----------------------------------------------------------
    }