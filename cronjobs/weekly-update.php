<?php
    require_once dirname(__DIR__) . '/includes/config.php';
    require_once dirname(__DIR__) . '/includes/functions.php';

    $framework_files        = glob(dirname(__DIR__) . '/src/frameworks/*.php');
    $framework_files_output = dirname(__DIR__) . '/src/frameworks/compiled/';

    $frameworks_index = array();

    foreach ($framework_files as $key => $file)
    {
        $file = str_replace('\\', '/', $file);
        $file = preg_replace('|/+|', '/', $file);

        // Get framework data.
        require_once $file;

        // Get slug from filename.
        $framework['slug'] = substr($file, strrpos($file, '/') + 1, - 4);

        // Enrich with GitHub info.
        if (isset($framework['github_repo']))
            enrich_with_github($framework);

        // Enrich with WordPress.org info.
        if (isset($framework['wp_slug']))
            enrich_with_wp($framework);

        if ( ! isset($framework['banner']))
            enrich_banner($framework);

        // Add images CDN proxy.
        $framework['banner'] = 'https://res.cloudinary.com/freemius/image/fetch/' . $framework['banner'];

        $framework_path = $framework_files_output . substr($file, strrpos($file, '/') + 1);

        dump_framework_to_php_file($framework, $framework_files_output . $framework_path);

        $frameworks_index[$framework['slug']] = $framework['github']['stars'];
    }

    arsort($frameworks_index);

    $index_path = $framework_files_output . 'index.php';

    if (file_exists($index_path))
        unlink($index_path);

    file_put_contents($index_path, '<?php $frameworks_index = ' . var_export($frameworks_index, true) . ';');