<?php echo __('Hello'); ?> <?php echo $user['fullname']; ?>,<br /><br />
<?php echo __('Your password has been change by MostCoupon Administrator'); ?>
<br /><br />


<?php if(isset($user['newPwd'])): ?>
<?php echo __('New Password: ' . $user['newPwd']); ?>
<br /><br />
<?php endif; ?>


<?php echo __('Regards,'); ?>
<br />
<?php echo __('MostCoupon Team'); ?>