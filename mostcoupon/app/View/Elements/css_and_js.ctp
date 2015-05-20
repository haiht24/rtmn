

<?php 

$layout = Inflector::underscore($this->layout);
$controller = Inflector::underscore($this->request->controller);
$action = Inflector::underscore($this->request->action);
/**
 * Include compiled less
 */
//echo $this->Html->css($layout . '/' . $controller . '/' . $action);
echo $this->Html->css('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
//echo $this->Html->css('/vendors/fontawesome/css/font-awesome');
echo $this->Html->css('/assets/css/bootstrap.min');
echo $this->Html->css('/assets/css/flexslider');
echo $this->Html->css('/assets/css/perfect-scrollbar');
echo $this->Html->css('/vendors/jasny-bootstrap/css/jasny-bootstrap.min');
echo $this->Html->css('/vendors/bootstrap-datepicker/datepicker3');
echo $this->Html->css('/vendors/select2/select2');
echo $this->Html->css('/vendors/select2/select2-bootstrap');
echo $this->Html->css('/assets/css/app');

echo $this->Html->css('/lib/jquery-ui/themes/smoothness/jquery-ui.min');

echo $this->Html->script('/lib/jquery/dist/jquery.min');
echo $this->Html->script('/lib/jquery-ui/jquery-ui.min');
echo $this->Html->script('/lib/angular/angular.min');

echo $this->Html->script('/lib/autofill-event/src/autofill-event'); 
echo $this->Html->script('/lib/jquery-html5-placeholder-shim/jquery.html5-placeholder-shim');
echo $this->Html->script('/lib/angular-placeholder-shim/angular-placeholder-shim');
echo $this->Html->script('/lib/angular-validation-match/dist/angular-input-match.min');
echo $this->Html->script('/lib/bpopup/jquery.bpopup.min');

echo $this->Html->script('global');
echo $this->Html->script('angular-mcus');

// moment
echo $this->Html->script('/lib/moment/min/moment.min');
if(is_file(APP . WEBROOT_DIR . DS . 'lib' . DS . 'moment' . DS  . 'locale' . DS .  'en-au' . '.js')) {
    echo $this->Html->script('/lib/moment/locale/en-au.js');
}
echo $this->Html->script('/lib/moment-timezone/builds/moment-timezone-with-data.min');
// end moment

echo $this->Html->script('/vendors/bootstrap/dist/js/bootstrap.min');
echo $this->Html->script('/vendors/flexslider/jquery.flexslider');
echo $this->Html->script('/vendors/perfect-scrollbar/src/perfect-scrollbar');
echo $this->Html->script('/assets/js/jquery.dotdotdot.min');
echo $this->Html->script('/vendors/jasny-bootstrap/js/jasny-bootstrap.min');
echo $this->Html->script('/vendors/bootstrap-datepicker/bootstrap-datepicker.min');
echo $this->Html->script('/vendors/jquery.validate.min');
echo $this->Html->script('/vendors/autoNumeric.min');
echo $this->Html->script('/vendors/select2/select2.min');
echo $this->Html->script('/vendors/holder');
echo $this->Html->script('/vendors/zero-clipboard/ZeroClipboard');
//echo $this->Html->script('/vendors/angular-moment/moment-timezone');
echo $this->Html->script('/vendors/jquery.timeago');
echo $this->Html->script('/assets/js/app');
echo $this->Html->script('/assets/js/main');
/**
 * Include layout specific js
*/
if(is_file(APP.WEBROOT_DIR . DS . 'js' . DS . 'layout-' . $layout . '.js')) {
    echo $this->Html->script('layout-'.$layout);
}

/**
 * Incudes controller specific js
 */
if (is_file(APP . WEBROOT_DIR . DS . 'js' . DS . $controller . '.js')){
    echo $this->Html->script($controller);
}

/**
 * Incudes action specific js
 */
if (is_file(APP . WEBROOT_DIR . DS . 'js' . DS . $controller . DS . $action . '.js')){
    echo $this->Html->script($controller . '/' . $action);
}
?>
<link href='http://fonts.googleapis.com/css?family=Dancing+Script' rel='stylesheet' type='text/css'>
<style>
[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
  display: none !important;
}
</style>
<script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script>
<script type="text/javascript">            
    Config = {        
        baseUrl: "<?php echo $this->base ?>",        
        GoogleAnalytics: <?php if (Configure::read('GoogleAnalytics')) { echo 'true';} else { echo 'false'; } ?>,
        user:  <?php echo json_encode($user) ?>,
        timeZone: <?php echo json_encode($timeZone) ?>
    };    
</script>
