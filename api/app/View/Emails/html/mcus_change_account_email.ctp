<?php echo __('Hello'); ?> <?php echo $user['fullname']; ?>,<br /><br />

<?php echo __('Welcome to MostCoupon.'); ?>  <br /><br />

<?php echo __('MostCoupon is a brand new and modern tool for gathering customer feedback. We are glad that you would like to get to know us!'); ?> <br /><br />

<?php echo __('Get going straight away …<br />To activate your account please use the following link'); ?>:<br />
<a href="<?php echo Configure::read('Url') . 'activation/'.$user['token']; ?>"><big><b><?php echo Configure::read('Url') . 'activation/'.$user['token']; ?></b></big></a> <br /><br />

<?php echo __('We wish you a lot of success with your MostCoupon!'); ?>    <br /><br />

<?php echo __('Your MostCoupon Team'); ?>