<?php
    $framework = array(
        'name'           => 'Toolset Types',
        'description'    => 'Custom post types, custom taxonomies and custom fields.',
        'github_repo'    => 'crowdfavorite-mirrors/wp-types',
        'wp_slug'        => 'types',
        'homepage'       => 'https://wp-types.com/',
        'is_for_plugins' => true,
        'is_for_themes'  => true,
        'thumbprint'     => array(
            'file'  => 'wpcf.php',
            'token' => 'function wpcf_init()',
        ),
    );