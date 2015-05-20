<!DOCTYPE html>
<html lang="en-us" id="extr-page">
<head>
    <meta charset="utf-8">
    <!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

    <title> SmartAdmin </title>
    <meta name="description" content="">
    <meta name="author" content="">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Basic Styles -->

    <?php echo $this->Html->css('bootstrap.min', array('type' => 'text/css', 'media' => 'screen')) ?>

    <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->

    <?php echo $this->Html->css('smartadmin-production.min', array('type' => 'text/css', 'media' => 'screen')) ?>
    <?php echo $this->Html->css('smartadmin-skins.min', array('type' => 'text/css', 'media' => 'screen')) ?>

    <!-- SmartAdmin RTL Support is under construction
    This RTL CSS will be released in version 1.5
    <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-rtl.min.css"> -->

    <!-- We recommend you use "your_style.css" to override SmartAdmin
    specific styles this will also ensure you retrain your customization with each SmartAdmin update.
    <link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css"> -->

    <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->

    <?php echo $this->Html->css('demo.min', array('type' => 'text/css', 'media' => 'screen')) ?>

    <!-- FAVICONS -->
<!--    <link rel="shortcut icon" href="<?php //echo $this->Html->url('img/favicon/favicon.ico') ?>" type="image/x-icon">
    <link rel="icon" href="<?php //echo $this->Html->url('img/favicon/favicon.ico') ?>" type="image/x-icon">-->

    <!-- GOOGLE FONT -->
    <!--    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">-->

    <!-- Specifying a Webpage Icon for Web Clip
    Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
    <link rel="apple-touch-icon" href="<?php echo $this->Html->url('img/splash/sptouch-icon-iphone.png') ?>">

    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $this->Html->url('img/splash/touch-icon-ipad.png') ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $this->Html->url('img/splash/touch-icon-iphone-retina.png') ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $this->Html->url('img/splash/touch-icon-ipad-retina.png') ?>">

    <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- Startup image for web apps -->
    <link rel="apple-touch-startup-image" href="<?php echo $this->Html->url('img/splash/ipad-landscape.png') ?>" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
    <link rel="apple-touch-startup-image" href="<?php echo $this->Html->url('img/splash/ipad-portrait.png') ?>" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
    <link rel="apple-touch-startup-image" href="<?php echo $this->Html->url('img/splash/iphone.png') ?>" media="screen and (max-device-width: 320px)">

    <?php
    echo $this->Html->css('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
    echo $this->Html->script('/lib/jquery/dist/jquery.min');
    echo $this->Html->script('/lib/angular/angular.min');
    echo $this->Html->script('global');
    echo $this->Html->script('angular-mcus');
    //        echo $this->fetch('css');
    echo $this->fetch('script');
    //        echo $this->element('google_analytics');
    //        echo $this->element('browser_update');
    //        echo $this->element('alexa');
    echo $this->Html->meta(array('name' => 'robots', 'content' => 'noindex, nofollow'));
    echo $this->Html->meta(array('name' => 'googlebot', 'content' => 'noindex, nofollow'));
    ?>

</head>
<body class="animated fadeInDown">
<header id="header">

    <div id="logo-group">
        <span id="logo"> <img src="<?= $this->Html->webroot('/', true)?>img/logo.png" alt="SmartAdmin"> </span>
    </div>

    <!--    <span id="extr-page-header-space"> <span class="hidden-mobile">Need an account?</span> <a href="register.html" class="btn btn-danger">Create account</a> </span>-->

</header>

<div id="main" role="main" <?php echo $this->Ng->ngAppOut() ?> <?php echo $this->Ng->ngInitOut() ?>>

    <!-- MAIN CONTENT -->
    <div id="content" class="container" <?php echo $this->Ng->ngControllerOut() ?>>

    <?php echo $this->fetch('content') ?>

    </div>

</div>

<!--================================================== -->


<?php  ?>

<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
<script src="<?= $this->Html->webroot('/', true) ?>js/plugin/pace/pace.min.js"></script>

<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
<script src="<?= $this->Html->webroot('/', true) ?>js/libs/jquery-2.0.2.min.js"></script>
<script src="<?= $this->Html->webroot('/', true) ?>js/libs/jquery-ui-1.10.3.min.js"></script>

<!-- JS TOUCH : include this plugin for mobile drag / drop touch events
<script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

<!-- BOOTSTRAP JS -->
<script src="<?= $this->Html->webroot('/', true) ?>js/bootstrap/bootstrap.min.js"></script>

<!-- JQUERY VALIDATE -->
<script src="<?= $this->Html->webroot('/', true) ?>js/plugin/jquery-validate/jquery.validate.min.js"></script>

<!-- JQUERY MASKED INPUT -->
<script src="<?= $this->Html->webroot('/', true) ?>js/plugin/masked-input/jquery.maskedinput.min.js"></script>

<!--[if IE 8]>

<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

<![endif]-->

<!-- MAIN APP JS FILE -->
<script src="<?= $this->Html->webroot('/', true) ?>js/app.config.js"></script>
<script src="<?= $this->Html->webroot('/', true) ?>js/app.min.js"></script>

</body>
</html>