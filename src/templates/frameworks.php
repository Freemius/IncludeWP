<?php require_once dirname(__DIR__) . "/frameworks/compiled/_index.php" ?>
<?php $framework_files_output = dirname(dirname(__DIR__)) . '/src/frameworks/compiled/' ?>
<section id="frameworks" class="section">
    <div class="container row">
        <?php foreach ($frameworks_index as $slug => $stars) : ?>
            <?php require_once $framework_files_output . $slug . '.php' ?>
            <?php require dirname(__DIR__) . '/templates/framework-card.php' ?>
        <?php endforeach ?>
    </div>
</section>