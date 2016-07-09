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

    if (file_exists($index_path))
        unlink($index_path);

    file_put_contents($index_path, '<?php $frameworks_index = ' . var_export($frameworks_index, true) . ';');