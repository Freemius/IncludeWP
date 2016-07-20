<?php
    $item = $wp_item['item'];
    $type = $wp_item['type'];
?>
<div class="card-container col s12 m6 l4 <?php echo $type ?>s">
    <div class="card small">
        <div class="card-image">
            <div class="image-container">
                <img src="<?php echo $item['banner'] ?>">
            </div>
            <span class="card-title"><?php echo $item['wordpress']['name'] ?></span>

            <div class="compatibility">
                <i class="fa fa-<?php echo ('plugin' === $type) ? 'plug' : 'paint-brush' ?> <?php echo $type ?>"></i>
            </div>
        </div>
        <div class="card-content">
            <!-- WordPress.org Summary -->
            <div class="row details center">
                <div class="col s4 installs" title="Active Installs">
                    <i class="fa fa-heartbeat"></i>
                    <span><?php echo number_format($item['wordpress']['active']) . ('000' === substr($item['wordpress']['active'], -3) ? '+' : '') ?></span>
                </div>
                <div class="col s4 rate" title="Avg. Rate">
                    <i class="fa fa-star"></i>
                    <span><?php echo number_format($item['wordpress']['avg_rate'], 2) ?></span>
                </div>
                <div class="col s4 downloads" title="Downloads">
                    <i class="fa fa-download"></i>
                    <span><?php echo number_format($item['wordpress']['downloads']) ?></span>
                </div>
            </div>

            <!-- Short Desc -->
            <p><?php echo $item['wordpress']['short_description'] ?></p>
        </div>
        <div class="card-action center">
            <div class="col s6">
                <nobr><a class="wordpress"
                         href="https://wordpress.org/<?php echo $type ?>s/<?php echo trim($item['wp_slug'], '/') ?>/"
                         target="_blank" title="WordPress.org"><i class="fa fa-wordpress"></i> WordPress.org</a></nobr>
            </div>
            <div class="col s6">
                <nobr><a class="homepage" href="<?php echo trim($item['wordpress']['homepage'], '/') ?>/" target="_blank"
                         title="Homepage"><i class="fa fa-home"></i> Homepage</a></nobr>
            </div>
        </div>
    </div>
</div>
