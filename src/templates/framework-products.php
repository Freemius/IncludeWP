<section id="frameworks" class="section">
    <div class="container row">
        <?php foreach ($plugins as $slug => $plugin) : ?>
            <?php
            $wp_item = array(
                'type' => 'plugin',
                'item' => &$plugin,
            );
            require dirname(__DIR__) . '/templates/wp-item-card.php';
            ?>
        <?php endforeach ?>
        <?php foreach ($themes as $slug => $theme) : ?>
            <?php
            $wp_item = array(
                'type' => 'theme',
                'item' => &$theme,
            );
            require dirname(__DIR__) . '/templates/wp-item-card.php';
            ?>
        <?php endforeach ?>
    </div>
</section>