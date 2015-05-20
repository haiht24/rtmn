<!DOCTYPE html>
<html lang="en-us">
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

        <?php echo $this->Html->css('smartadmin-production-plugins.min', array('type' => 'text/css', 'media' => 'screen')) ?>
        <?php echo $this->Html->css('smartadmin-production.min', array('type' => 'text/css', 'media' => 'screen')) ?>
        <?php echo $this->Html->css('smartadmin-skins.min', array('type' => 'text/css', 'media' => 'screen')) ?>
        <?php echo $this->Html->css('jasny-bootstrap.min', array('type' => 'text/css', 'media' => 'screen')) ?>
        <?php echo $this->Html->css('datepicker3', array('type' => 'text/css', 'media' => 'screen')) ?>
        <?php echo $this->Html->css('main', array('type' => 'text/css', 'media' => 'screen')) ?>
        <!-- SmartAdmin RTL Support is under construction
        This RTL CSS will be released in version 1.5
        <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-rtl.min.css"> -->

        <!-- We recommend you use "your_style.css" to override SmartAdmin
        specific styles this will also ensure you retrain your customization with each SmartAdmin update.
        <link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css"> -->

        <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->

        <?php echo $this->Html->css('demo.min', array('type' => 'text/css', 'media' => 'screen')) ?>

        <!-- FAVICONS -->
<!--        <link rel="shortcut icon" href="<?php //echo $this->Html->url('img/favicon/favicon.ico') ?>" type="image/x-icon">
        <link rel="icon" href="<?php //echo $this->Html->url('img/favicon/favicon.ico') ?>" type="image/x-icon">-->

        <!-- GOOGLE FONT -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

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
        echo $this->element('css_and_js');
        echo $this->fetch('css');
        echo $this->fetch('script');
        echo $this->element('google_analytics');
        echo $this->element('browser_update');
        echo $this->element('alexa');
        echo $this->Html->meta(array('name' => 'robots', 'content' => 'noindex, nofollow'));
        echo $this->Html->meta(array('name' => 'googlebot', 'content' => 'noindex, nofollow'));
        ?>

    </head>
    <body class="">
        <!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->

        <!-- HEADER -->
        <?php echo $this->element('header') ?>
        <!-- END HEADER -->

        <!-- Left panel : Navigation area -->
        <!-- Note: This width of the aside area can be adjusted through LESS variables -->
        <?php echo $this->element('left_panel') ?>
        <!-- END NAVIGATION -->

        <!-- MAIN PANEL -->
        <div id="main" role="main" <?php echo $this->Ng->ngAppOut() ?> <?php echo $this->Ng->ngInitOut() ?>>

            <!-- RIBBON -->
            <div id="ribbon">

                <span class="ribbon-button-alignment"> 
                    <span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
                        <i class="fa fa-refresh"></i>
                    </span> 
                </span>

                <!-- breadcrumb -->
                <ol class="breadcrumb">
                    <?php if (isset($breadcrumbs) && count($breadcrumbs)): ?>
                        <?php foreach ($breadcrumbs as $breadcrumb): ?>
                            <li><?php echo $breadcrumb; ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ol>
                <!-- end breadcrumb -->

                <!-- You can also add more buttons to the
                ribbon for further usability

                Example below:

                <span class="ribbon-button-alignment pull-right">
                <span id="search" class="btn btn-ribbon hidden-xs" data-title="search"><i class="fa-grid"></i> Change Grid</span>
                <span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa-plus"></i> Add</span>
                <span id="search" class="btn btn-ribbon" data-title="search"><i class="fa-search"></i> <span class="hidden-mobile">Search</span></span>
                </span> -->

            </div>
            <!-- END RIBBON -->

            <!-- MAIN CONTENT -->
            <div id="content" <?php echo $this->Ng->ngControllerOut() ?>>
                <?php
                    echo $this->Session->flash('flash', ['element' => 'flash']);
                    echo $this->Session->flash('error', ['element' => 'flash', 'params' => ['class' => 'error']]);
                    echo $this->Session->flash('success', ['element' => 'flash', 'params' => ['class' => 'success']]);
                ?>
                <?php echo $this->element('notifications') ?>
                <?php echo $this->fetch('content') ?>

            </div>
            <!-- END MAIN CONTENT -->

        </div>
        <!-- END MAIN PANEL -->

        <!-- PAGE FOOTER -->
        <?php echo $this->element('footer') ?>
        <!-- END PAGE FOOTER -->

        <!-- SHORTCUT AREA : With large tiles (activated via clicking user name tag)
        Note: These tiles are completely responsive,
        you can add as many as you like
        -->
        <div id="shortcut">
            <ul>
                <li>
                    <a href="#inbox.html" class="jarvismetro-tile big-cubes bg-color-blue"> <span class="iconbox"> <i class="fa fa-envelope fa-4x"></i> <span>Mail <span class="label pull-right bg-color-darken">14</span></span> </span> </a>
                </li>
                <li>
                    <a href="#calendar.html" class="jarvismetro-tile big-cubes bg-color-orangeDark"> <span class="iconbox"> <i class="fa fa-calendar fa-4x"></i> <span>Calendar</span> </span> </a>
                </li>
                <li>
                    <a href="#gmap-xml.html" class="jarvismetro-tile big-cubes bg-color-purple"> <span class="iconbox"> <i class="fa fa-map-marker fa-4x"></i> <span>Maps</span> </span> </a>
                </li>
                <li>
                    <a href="#invoice.html" class="jarvismetro-tile big-cubes bg-color-blueDark"> <span class="iconbox"> <i class="fa fa-book fa-4x"></i> <span>Invoice <span class="label pull-right bg-color-darken">99</span></span> </span> </a>
                </li>
                <li>
                    <a href="#gallery.html" class="jarvismetro-tile big-cubes bg-color-greenLight"> <span class="iconbox"> <i class="fa fa-picture-o fa-4x"></i> <span>Gallery </span> </span> </a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="jarvismetro-tile big-cubes selected bg-color-pinkDark"> <span class="iconbox"> <i class="fa fa-user fa-4x"></i> <span>My Profile </span> </span> </a>
                </li>
            </ul>
        </div>
        <!-- END SHORTCUT AREA -->

        <!--================================================== -->

        <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
        <script data-pace-options='{ "restartOnRequestAfter": true }' src="<?php echo $this->webroot ?>js/plugin/pace/pace.min.js"></script>

        <!-- Overall Libs -->
        <?php
            echo $this->Html->script(array(
                'app.config.js',
                'plugin/jquery-touch/jquery.ui.touch-punch.min.js',
                'bootstrap/bootstrap.min.js',
                'notification/SmartNotification.min.js',
                'smartwidgets/jarvis.widget.min.js',
                'plugin/easy-pie-chart/jquery.easy-pie-chart.min.js',
                'plugin/sparkline/jquery.sparkline.min.js',
                'plugin/jquery-validate/jquery.validate.min.js',
                'plugin/masked-input/jquery.maskedinput.min.js',
                'plugin/select2/select2.min.js',
                'plugin/bootstrap-slider/bootstrap-slider.min.js',
                'plugin/msie-fix/jquery.mb.browser.min.js',
                'plugin/fastclick/fastclick.min.js'
            ));
        ?>

        <?php /*
        <!-- IMPORTANT: APP CONFIG -->
        <script src="js/app.config.js"></script>

        <!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
        <script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script>

        <!-- BOOTSTRAP JS -->
        <script src="js/bootstrap/bootstrap.min.js"></script>

        <!-- CUSTOM NOTIFICATION -->
        <script src="js/notification/SmartNotification.min.js"></script>

        <!-- JARVIS WIDGETS -->
        <script src="js/smartwidgets/jarvis.widget.min.js"></script>

        <!-- EASY PIE CHARTS -->
        <script src="js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

        <!-- SPARKLINES -->
        <script src="js/plugin/sparkline/jquery.sparkline.min.js"></script>

        <!-- JQUERY VALIDATE -->
        <script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>

        <!-- JQUERY MASKED INPUT -->
        <script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>

        <!-- JQUERY SELECT2 INPUT -->
        <script src="js/plugin/select2/select2.min.js"></script>

        <!-- JQUERY UI + Bootstrap Slider -->
        <script src="js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

        <!-- browser msie issue fix -->
        <script src="js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

        <!-- FastClick: For mobile devices -->
        <script src="js/plugin/fastclick/fastclick.min.js"></script>

        */ ?>

        <!--[if IE 8]>

        <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

        <![endif]-->

        <!-- Another Overall Libs -->
        <?php
            echo $this->Html->script(array(
                'demo.min.js',
                'app.min.js',
                'speech/voicecommand.min.js',
                'plugin/flot/jquery.flot.cust.min.js',
                'plugin/flot/jquery.flot.resize.min.js',
                'plugin/flot/jquery.flot.tooltip.min.js',
                'plugin/vectormap/jquery-jvectormap-1.2.2.min.js',
                'plugin/vectormap/jquery-jvectormap-world-mill-en.js',
                'plugin/fullcalendar/jquery.fullcalendar.min.js',
                'plugin/x-editable/x-editable.min.js',
                'jquery.validate.min.js',
                'plugin/jasny-bootstrap/js/jasny-bootstrap.min.js',
                'bootstrap-datepicker.min.js',
                'autoNumeric.min.js',
                '/lib/bootstrap-datetimepicker-master/build/js/bootstrap-datetimepicker.min.js',
                'string.min.js',
                'bootbox.min.js',
                'plugin/jquery-lazy-load/jquery.lazy.min.js',
                'main.js'
            ));
        ?>

        <?php /*
        <!-- Demo purpose only -->
        <script src="js/demo.min.js"></script>

        <!-- MAIN APP JS FILE -->
        <script src="js/app.min.js"></script>

        <!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
        <!-- Voice command : plugin -->
        <script src="js/speech/voicecommand.min.js"></script>

        <!-- PAGE RELATED PLUGIN(S) -->

        <!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
        <script src="js/plugin/flot/jquery.flot.cust.min.js"></script>
        <script src="js/plugin/flot/jquery.flot.resize.min.js"></script>
        <script src="js/plugin/flot/jquery.flot.tooltip.min.js"></script>

        <!-- Vector Maps Plugin: Vectormap engine, Vectormap language -->
        <script src="js/plugin/vectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="js/plugin/vectormap/jquery-jvectormap-world-mill-en.js"></script>

        <!-- Full Calendar -->
        <script src="js/plugin/fullcalendar/jquery.fullcalendar.min.js"></script>
        */ ?>
        <script>
            $(document).ready(function() {
                // DO NOT REMOVE : GLOBAL FUNCTIONS!
                pageSetUp();
            });

        </script>

        <!-- Your GOOGLE ANALYTICS CODE Below -->
        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-XXXXXXXX-X']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();

        </script>

    </body>

</html>