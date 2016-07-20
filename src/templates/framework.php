<?php
    $framework_plugins = dirname(dirname(__DIR__)) . "/src/plugins/compiled/{$framework_slug}.php";
    $framework_themes  = dirname(dirname(__DIR__)) . "/src/themes/compiled/{$framework_slug}.php";
    $framework_url     = "https://includewp.com/{$framework_slug}/";

    if (file_exists($framework_plugins))
        require_once $framework_plugins;
    if (file_exists($framework_themes))
        require_once $framework_themes;

    $plugins_count   = isset($plugins) ? count($plugins) : 0;
    $themes_count    = isset($themes) ? count($themes) : 0;
    $total_downloads = 0;
    $total_active    = 0;
    if ($plugins_count > 0)
    {
        foreach ($plugins as $slug => $plugin)
        {
            $total_downloads += $plugin['wordpress']['downloads'];
            $total_active += $plugin['wordpress']['active'];
        }
    }

    if ($themes_count > 0)
    {
        foreach ($themes as $slug => $theme)
        {
            $total_downloads += $theme['wordpress']['downloads'];
            $total_active += $theme['wordpress']['active'];
        }
    }

    $counter = ($total_active > 100000) ?
        number_format($total_active) . '+ active installs' :
        number_format($total_downloads) . ' downloads';


    //    #Freemius framework is used by 38 #plugins on #WordPress.org & 54 #themes with 2,000,000 downloads.
    $tweet = $framework['name'] . ' framework is used by ' .
             ($plugins_count > 0 ? number_format($plugins_count) . ' #plugins' : '') .
             ($themes_count > 0 ? ($plugins_count > 0 ? ' & ' : '') . number_format($themes_count) . ' #themes' : '') .
             ' on #WordPress.org with ' . $counter . '.';

    $is_empty_result = (0 === ($plugins_count + $themes_count));
?>
<section id="framework" class="section">
    <div id="focus"></div>
    <div class="container row" style="margin-bottom:0!important;">
        <div class="col s12 m8 l8">
            <h1><?php echo $framework_name ?></h1>

            <p><?php echo $framework['description'] ?></p>

            <div class="row">
                <?php if ($has_wp_repo) : ?>
                    <div class="col s4">
                        <nobr><a class="wordpress"
                                 href="https://wordpress.org/plugins/<?php echo trim($framework['wp_slug'], '/') ?>/"
                                 target="_blank" title="WordPress.org"><i class="fa fa-wordpress"></i> WP.org</a></nobr>
                    </div>
                <?php endif ?>
                <div class="col">
                    <nobr><a class="github" href="https://github.com/<?php echo trim($framework['github_repo'], '/') ?>/"
                             target="_blank" title="GitHub"><i class="fa fa-github"></i>
                            https://github.com/<?php echo trim($framework['github_repo'], '/') ?>/</a></nobr>
                </div>
                <div class="col">
                    <nobr><a class="homepage" href="<?php echo trim($framework['homepage'], '/') ?>/" target="_blank"
                             title="Homepage"><i class="fa fa-home"></i> <?php echo trim($framework['homepage'], '/') ?>
                            /</a></nobr>
                </div>
            </div>
        </div>
        <div class="col s12 m4 l4">
        
        <div class="white includewp-tweet"> 
          <div class="row">
            <div class="col s12">
            <?php if ($is_empty_result) : ?>
                <?php $item_label = ! $framework['is_for_themes'] ?
                    'plugins' :
                    (! $framework['is_for_plugins'] ? 'themes' : 'plugins or themes') ?>
                <p class="black-text">
                    <?php if ($framework['is_for_themes'] && ! $framework['is_for_plugins']) : ?>
                        Oops... We couldn't find any theme using the framework on WordPress.org. If your theme is listed on WordPress.org and you can't find it here - please
                        <a href="https://github.com/Freemius/IncludeWP/issues/new" target="_blank">open an issue on our
                            GitHub
                            repo</a>. In the ticket, provide the theme's .org URL and the path of the framework in your theme.
                    <?php else : ?>
                        Oops... We couldn't find any <?php echo $item_label ?> using the framework on WordPress.org. Our current identification mechanism is not (yet) supporting recognition of <?php echo $item_label ?> that include the framework using TGM. If your product is listed on WordPress.org, not using TGM, and you can't find it here - please
                        <a href="https://github.com/Freemius/IncludeWP/issues/new" target="_blank">open an issue on our
                            GitHub
                            repo</a>. In the ticket, provide the items .org URL and the path of the framework in your product.
                    <?php endif ?>
                </p>
                </div>
                </div>
                <div class="row">
                <div class="col s12">
            <?php else : ?>
                <a href="https://twitter.com/home?status=<?php echo urlencode($tweet . " $framework_url") ?>"
                   target="_blank"><i class="fa fa-twitter"></i> <?php echo $tweet ?></a>
                <span class="tweet-button right"><a href="https://twitter.com/share" class="twitter-share-button"
                                              data-text="<?php echo htmlspecialchars($tweet) ?>"
                                              data-url="<?php echo $framework_url ?>"
                                              data-size="large"
                                              data-count="none">Tweet</a></span>
            <?php endif ?>
            </div>
        </div>
        </div>
        </div>
        <?php if ( ! $is_empty_result) : ?>
            <script type="text/javascript">!function (d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (!d.getElementById(id)) {
                        js = d.createElement(s);
                        js.id = id;
                        js.src = "https://platform.twitter.com/widgets.js";
                        fjs.parentNode.insertBefore(js, fjs);
                    }
                }(document, "script", "twitter-wjs");</script>
        <?php endif ?>
    </div>
</section>

<?php if ( ! $is_empty_result) : ?>
    <?php require dirname(__DIR__) . '/templates/filters.php' ?>

    <?php require dirname(__DIR__) . '/templates/framework-products.php' ?>
<?php endif ?>

<script type="text/javascript">
    sortItems('installs');
</script>
