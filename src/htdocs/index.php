<?php define('NAME', 'IncludeWP') ?>
<?php define('SITE_ADDRESS', 'https://includewp.com') ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="theme-color" content="#333333">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

    <!-- Always force latest IE rendering engine or request Chrome Frame -->
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">

    <title>Top Frameworks for WordPress Plugin & Theme Developers &ndash; IncludeWP</title>

    <meta name="description"
          content="IncludeWP is a leaderboard of the top open-source frameworks for WordPress plugin & theme developers. Because code reusability is awesome."/>

    <!-- Twitter Card data -->
    <meta name="twitter:card"
          value="IncludeWP is a leaderboard of the top open-source frameworks for WordPress plugin & theme developers. Because code reusability is awesome.">

    <!-- Open Graph data -->
    <meta property="og:title" content="IncludeWP"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="<?php echo SITE_ADDRESS ?>"/>
    <meta property="og:image" content="<?php echo SITE_ADDRESS ?>/assets/images/share.png"/>
    <meta property="og:description"
          content="IncludeWP is a leaderboard of the top open-source frameworks for WordPress plugin & theme developers. Because code reusability is awesome."/>

</head>
<body>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

<!-- Font Awesome -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet"
      integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

<!-- Lato Font -->
<link href='https://fonts.googleapis.com/css?family=Lato|Pacifico&subset=latin,latin-ext&subset=latin' rel='stylesheet'
      type='text/css'>

<!-- Compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.min.css">

<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.min.js"></script>

<link rel="stylesheet" href="/assets/css/style.css">

<?php require dirname(__DIR__) . '/templates/header.php' ?>

<?php require dirname(__DIR__) . '/templates/cover.php' ?>

<?php require dirname(__DIR__) . '/templates/filters.php' ?>

<?php require dirname(__DIR__) . '/templates/frameworks.php' ?>

<?php require dirname(__DIR__) . '/templates/footer.php' ?>

</body>
</html>