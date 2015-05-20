<?php
if($user['password'] != ''){
    $password = $user['password'];
}else{
    $password = 'Your selected password';
}
?>

<?php echo __('Hello'); ?><br /><br />
<?php echo __('Your password:'.$password); ?> <br/>
<?php echo __('To activate your new email address please click on the following link:'); ?> <br/>
<?php echo str_replace('api', 'mostcoupon', Configure::read('Url')) .'users/activation/'.$user['token']; ?>
<br/><br/>
<?php echo __('Regards, MostCoupon Team'); ?>