<?php echo __('Hello'); ?> <?php echo $user['fullname']; ?>,<br /><br />

<?php echo __('Welcome to MostCoupon.'); ?>  <br /><br />


<?php echo __('You has been set to <b>' . $user['role'] . '</b> of MostCoupon'); ?><br />
<?php echo __('Your password: ' . $user['userPassword']); ?><br />
<?php echo __('You can login to our system with this link: '); ?><a href="<?php echo $user['dashboardUrl']?>">Login to MostCoupon Dashboard</a><br />

<br /><br />
<?php echo __('Regards,'); ?>
<br />
<?php echo __('MostCoupon Team'); ?>