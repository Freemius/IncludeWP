<?php
    require_once dirname(__DIR__) . '/includes/config.php';
    require_once dirname(__DIR__) . '/includes/functions.php';

    /* Frameworks update
 -------------------------------------------------------------------------------------*/
    $framework_files        = glob(dirname(__DIR__) . '/src/frameworks/*.php');
    $framework_files_output = dirname(__DIR__) . '/src/frameworks/compiled/';
    $index_path             = $framework_files_output . '_index.php';
    $updates_index_path     = $framework_files_output . '_updates.php';

    $frameworks_index = array();
    $frameworks_updates = array();

    if (file_exists($updates_index_path))
        require_once $updates_index_path;

    foreach ($framework_files as $key => $file)
    {
        $file = canonize_file_path($file);

        $slug = substr($file, strrpos($file, '/') + 1, - 4);
        $framework_path = $framework_files_output . substr($file, strrpos($file, '/') + 1);

        if (isset($frameworks_updates[$slug]) && $frameworks_updates[$slug] >= (time() - WEEK_IN_SEC) && file_exists($framework_path))
        {
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
        }

        $frameworks_index[$slug] = $framework['github']['stars'];
    }

    // Sort frameworks by stars.
    arsort($frameworks_index);

    // Dump index.
    dump_var_to_php_file($frameworks_index, '$frameworks_index', $index_path);

    // Dump framework update timestamp.
    dump_var_to_php_file($frameworks_updates, '$frameworks_updates', $updates_index_path);

    /* Plugins update
     -------------------------------------------------------------------------------------*/
    $plugin_files        = glob(dirname(__DIR__) . '/src/plugins/*.php');
    $plugin_files_output = dirname(__DIR__) . '/src/plugins/compiled/';

    foreach ($plugin_files as $key => $file)
    {
        $framework_plugins = array();

        $file = canonize_file_path($file);

        $slug = substr($file, strrpos($file, '/') + 1, - 4);

        $plugin_path = $plugin_files_output . substr($file, strrpos($file, '/') + 1);

        // Get plugins index.
        require_once $file;

        $framework_plugins_data = array();

        if (file_exists($plugin_path))
        {
            require_once $plugin_path;
        }

        foreach ($plugins_index as $plugin_slug)
        {
            if (isset($framework_plugins_data[$plugin_slug]) && $framework_plugins_data[$plugin_slug]['last_update'] >= (time() - WEEK_IN_SEC) && $plugin_path)
            {
                // Plugin data already pulled during the last week.
                continue;
            }

            $framework_plugins_data[$plugin_slug] = array('wp_slug' => $plugin_slug);

            enrich_with_wp($framework_plugins_data[$plugin_slug]);

            // @todo handle case when plugin doesn't have a banner image.
//            if ( ! isset($framework_plugins_data[$plugin_slug]['banner']))
//                enrich_banner($framework_plugins_data[$plugin_slug]);

            // Add banner protocol if missing before running via CDN.
            if ('/' === $framework_plugins_data[$plugin_slug]['banner'][0])
                $framework_plugins_data[$plugin_slug]['banner'] = 'http:' . $framework_plugins_data[$plugin_slug]['banner'];

            // Add images CDN proxy
            $framework_plugins_data[$plugin_slug]['banner'] = 'https://res.cloudinary.com/freemius/image/fetch/' . $framework_plugins_data[$plugin_slug]['banner'];

            $framework_plugins_data[$plugin_slug]['last_update'] = time();
        }

        dump_var_to_php_file($framework_plugins_data, '$plugins', $plugin_path);
    }

    // Sort frameworks by stars.
//    arsort($plugins_index);

    // Dump index.
//    dump_var_to_php_file($plugins_index, '$plugins_index', $index_path);

    // Dump framework update timestamp.
//    dump_var_to_php_file($plugins_updates, '$plugins_updates', $updates_index_path);