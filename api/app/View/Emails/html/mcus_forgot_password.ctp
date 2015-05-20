<?php echo __('Hello'); ?><br /><br />
<?php echo __('Click here to reset your password'); ?> <br/>
<?php echo str_replace('api', 'mostcoupon', Configure::read('Url')) .'users/resetpassword/'.$user['token']; ?>
<br/><br/>
<?php echo __('Regards, MostCoupon Team'); ?>