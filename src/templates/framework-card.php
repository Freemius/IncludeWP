<?php $has_wp_repo = isset($framework['wp_slug']) ?>
<div class="card-container col s12 m6 l4<?php if ($framework['is_for_plugins'])
    echo ' plugins'; ?><?php if ($framework['is_for_themes'])
    echo ' themes'; ?>">
    <div class="card small">
        <div class="card-image">
            <a href="/<?php echo $framework['slug'] ?>/#focus">
                <div class="image-container">
                    <img src="<?php echo $framework['banner'] ?>">
                </div>
            </a>
            <span class="card-title"><a
                    href="/<?php echo $framework['slug'] ?>/#focus"><?php echo $framework['name'] ?> <i class="fa fa-angle-double-right"></i></a></span>
            <!--            <span class="card-title"><a href="/--><?php //echo $framework['slug'] ?><!--/">-->
            <?php //echo $framework['name'] ?><!--</a></span>-->

            <div class="compatibility">
                <?php if ($framework['is_for_plugins']) : ?>
                    <i class="fa fa-plug plugin"></i>
                <?php endif ?><?php if ($framework['is_for_themes']) : ?>
                    <i class="fa fa-paint-brush theme"></i>
                <?php endif ?>
            </div>
        </div>
        <div class="card-content">
            <!-- GitHub Summary -->
            <div class="row details center">
                <div class="col s4 stars" title="Stars">
                    <i class="fa fa-star"></i>
                    <span><?php echo number_format($framework['github']['stars']) ?></span>
                </div>
                <div class="col s4 forks" title="Forks">
                    <i class="fa fa-code-fork"></i>
                    <span><?php echo number_format($framework['github']['forks']) ?></span>
                </div>
                <div class="col s4 issues" title="Open Issues">
                    <i class="fa fa-bug"></i>
                    <span><?php echo number_format($framework['github']['issues']) ?></span>
                </div>
            </div>

            <!-- Short Desc -->
            <p><?php echo $framework['description'] ?></p>
        </div>
        <div class="card-action center">
            <?php $col_width = $has_wp_repo ? 's4' : 's6' ?>
            <?php if ($has_wp_repo) : ?>
                <div class="col s4">
                    <nobr><a class="wordpress"
                             href="https://wordpress.org/plugins/<?php echo trim($framework['wp_slug'], '/') ?>/"
                             target="_blank" title="WordPress.org"><i class="fa fa-wordpress"></i> WP.org</a></nobr>
                </div>
            <?php endif ?>
            <div class="col <?php echo $col_width ?>">
                <nobr><a class="github" href="https://github.com/<?php echo trim($framework['github_repo'], '/') ?>/"
                         target="_blank" title="GitHub"><i class="fa fa-github"></i> GitHub</a></nobr>
            </div>
            <div class="col <?php echo $col_width ?>">
                <nobr><a class="homepage" href="<?php echo trim($framework['homepage'], '/') ?>/" target="_blank"
                         title="Homepage"><i class="fa fa-home"></i> Homepage</a></nobr>
            </div>
        </div>
    </div>
</div>
