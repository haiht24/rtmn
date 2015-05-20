
<?php $this->start('script') ?>
<script type="text/javascript">
    Config.successRegister = <?php echo json_encode('Thank You for registering<br />We have sent a confirmation email to you to confirm your registration. Please click on the link in the email to confirm the registration and activate your account.<br />It is possible that the email could end up in your spam folder, so please check there just in case. If you do find an email in your spam folder, do not forget to mark it as safe to ensure that you receive future messages from us.') ?>;
    Config.message = [
        {'field': 'email', 'condition': 'required', 'content': '<?php echo 'Email is required' ?>'},
        {'field': 'email', 'condition': 'email', 'content': '<?php echo 'Email is invalid' ?>'},
        {'field': 'password', 'condition': 'required', 'content': '<?php echo 'Password is required' ?>'}
    ];
</script>
<?php $this->end() ?>
<?php echo $this->element('facebook') ?>
<?php $this->Ng->ngController('userLoginCtrl') ?>
<div id="content">
    <div class="section-login">
        <h2><?php echo __('Join MCus'); ?></h2>

        <div class="box">
            <div class="content">
                <form name="userLoginForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')) ?>" novalidate>
                    <ul class="input">
                        <li id="loginValidationMessage">
                        </li>
                        <li>
                            <label for="email-username"><?php echo 'Email/Username'; ?></label>
                            <input id="email-username" type="text"  name="email" ng-model="user.email" required/>
                        </li>
                        <li>
                            <label for="password"><?php echo 'Password'; ?></label>
                            <input id="password" type="password" name="password" ng-model="user.password"  required/>
                        </li>
                        <li>
                            <button class="buttons button-blue done" type="submit" ng-click="doLogin($event)"><?php echo 'Login'; ?></button>
                        </li>
                    </ul>
                </form>
            </div>
            <div class="footer">
                <ul class="social-network">
                    <li>
                        <a ng-show="showFacebookLogin" ng-click="facebookLogin($event)" class="button-social facebook">
                            <i class="icon i-facebook" ></i>
                            <span><?php echo 'Join with facebook'; ?></span>
                        </a>
                        <a class="create-new-user" ng-hide="true" action="createNewAccount()" ></a>
                    </li>
                </ul>
                <p><?php echo 'Not a member yet?'; ?> <a href="<?php echo Router::url('/', true) .'users/register' ?>"><?php echo 'Register now'; ?></a> - <?php echo "it's fun and easy!"; ?></p>
            </div>
        </div>
    </div>
</div>