<?php echo __('Hello'); ?> <?php echo $user['fullname']; ?>,<br /><br />

<?php echo __('Welcome to MostCoupon.'); ?>  <br /><br />


<?php echo __('You can get going right away!<br />To activate your account, please click on the following link:'); ?>:<br />
<a href="<?php echo $user['active_link'].$user['token']; ?>">
  <big>
    <b>
      <?php echo $user['active_link'].$user['token']; ?>
    </b>
  </big>
</a>
<br /><br />

<?php echo __('We wish you lots of success with your MostCoupon account!'); ?>    <br /><br />

<?php echo __('Your MostCoupon Team'); ?>