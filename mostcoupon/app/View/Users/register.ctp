
<?php echo $this->element('facebook') ?>

<?php $this->Ng->ngController('registerCtrl') ?>
<?php $this->start('script') ?>
<script type="text/javascript">
    Config.user = <?php echo isset($tokenUser) ? json_encode($tokenUser, true) : '{}' ?>;
    Config.message = [
        {'field': 'email', 'condition': 'required', 'content': '<?php echo 'Email is required' ?>'},
        {'field': 'email', 'condition': 'email', 'content': '<?php echo 'Email is invalid' ?>'},
        {'field': 'password', 'condition': 'required', 'content': '<?php echo 'Password is required' ?>'},
        {'field': 'password', 'condition': 'minlength', 'content': '<?php echo 'Minimum length of password is 6 characters' ?>'},
        {'field': 'password', 'condition': 'maxlength', 'content': '<?php echo 'Maximum length of password length is 30 characters' ?>'},
        {'field': 'password', 'condition': 'pattern', 'content': '<?php echo 'Password musts contain number and letter' ?>'},
        {'field': 'username', 'condition': 'pattern', 'content': '<?php echo 'Username musts start with a letter and can contain numbers and underscore' ?>'},
        {'field': 'username', 'condition': 'minlength', 'content': '<?php echo 'Minimum length of username is 6 characters' ?>'},
        {'field': 'username', 'condition': 'maxlength', 'content': '<?php echo 'Maximum length of username length is 15 characters' ?>'},
        {'field': 'username', 'condition': 'required', 'content': '<?php echo 'Username is required' ?>'}
    ];

</script>
<?php echo $this->end(); ?>
<div id="content">
    <div class="section-register">
    
       <div id="notification" style="display: none;"></div>

        <h2><?php echo __('Join mCus') ?></h2>
       
        <div class="box">
            <h3><?php echo __('Already a member?') ?> <a href="<?php echo $this->Html->url(['controller' => 'users', 'action' => 'login']) ?>"><?php echo __('Sign In') ?></a> <span>»</span></h3>
            <div class="content">

                <form name="registerForm" novalidate method="post" action="<?php echo $this->here ?>">
                    <div class="column-one">
                        <ul class="input">
                            <li id="registerValidationMessage">
                            </li>

                            <li>
                                <label for="user-email"><?php echo 'Your email' ?></label>
                                <input name="email" ng-model="user.email"  type="email" maxlength="50" required/>
                            </li>
                            <li>
                                <label for="username"><?php echo 'Choose a username' ?></label>
                                <input name="username" ng-model="user.username"  type="text" required 
                                       ng-minlength=6 ng-maxlength=15 ng-pattern="/^[A-z][A-z0-9_]*$/"/>
                            </li>
                            <li>
                                <label for="userpass"><?php echo 'Choose a password' ?></label>
                                <input type="password" name="password"  ng-model="user.password" required 
                                       ng-minlength=6 ng-maxlength=30 ng-pattern="/(?=.*[a-z])(?=.*[^a-zA-Z])/" />
                            </li>

                        </ul>
                        <p class="term"><?php echo 'By signing up, I agree to mCus' ?> <a href=""><?php echo 'terms of service' ?></a>. </p>
                        <button type="submit" ng-click="doRegister($event, user)" class="buttons button-blue done">
                            <span ng-show="!atSubmitting"><?php echo 'Sign up' ?></span>
                            <span ng-show="atSubmitting"><?php echo 'Submitting...' ?></span>
                        </button>
                    </div>
                </form>
                <div class="column-two">

                    <div class="or">or</div>

                    <ul class="social-network">
                        <li>
                            <a ng-click="facebookRegister($event, user)" class="button-social facebook">
                                <i class="icon i-facebook" ></i>
                                <span><?php echo 'Join with facebook'; ?></span>
                            </a>
                            <a class="create-new-user" ng-hide="true" action="createNewAccount()" ></a>
                        </li>
                    </ul>

                </div>

            </div>
        </div>

    </div>
</div>