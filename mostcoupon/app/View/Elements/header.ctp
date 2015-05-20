<?php if ($this->request->controller == 'stores') : ?>
    <div class="pre-header" data-spy="affix" data-offset-top="1">
        <div class="container">
            <div class="inner">
                <?php if (!empty($seoConfig['storeHeaderH1'])): ?>
                <h4 class="title"><?php echo $seoConfig['storeHeaderH1'] ?></h4>
                <?php endif; ?>
                <p><?php echo(isset($seoConfig['storeHeaderP']) ? $seoConfig['storeHeaderP'] : '') ?></p>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php $profileURL = $this->Html->url(['controller' => 'users', 'action' => 'profile']);?>

<script>
    /**
     * Twitter
     */
    /**
     * FB callback
     */
    function fb_login() {
        FB.login(function (res) {
            if (res.authResponse) {
                FB.api('/me', function (res) {
                    if (res.email) {
                        document.getElementById('hdRegEmail').value = res.email;
                        var fullName = '';
                        if (res.first_name) {
                            fullName += res.first_name;
                        }
                        if (res.middle_name) {
                            fullName += ' ' + res.middle_name;
                        }
                        if (res.last_name) {
                            fullName += " " + res.last_name;
                        }
                        document.getElementById('hdRegFullname').value = fullName;
                        document.getElementById('registFrom').value = 'fb';
                        //window.confirm('Dow you want link this Facebook account to this email?');
                        angular.element(document.getElementById('hdRegEmail')).scope().runAng(res);
                    } else {
                        console.log(res);
                        alert('This account not allow return user email');
                    }
                });
            } else {
                //user hit cancel button
                console.log('User cancelled login or did not fully authorize.');
            }
        }, {
            scope: 'publish_stream,email'
        });
    }
    /**
     * Google+ callback
     */
    var first_run = true;
    function signinCallback(authResult) {
        //alert(authResult['status']['method']);
        //return false;
        if (authResult['status']['method'] == 'PROMPT') {
            //document.getElementById('signinButton').setAttribute('style', 'display: none');
            //alert('Success');
            console.log(authResult);
            gapi.client.load('oauth2', 'v2', apiClientLoaded);
        } else {
            // Possible error values:
            //   "user_signed_out" - User is signed-out
            //   "access_denied" - User denied access to your app
            //   "immediate_failed" - Could not automatically log in the user
            //alert('Error');
            console.log(authResult);
        }
        function apiClientLoaded() {
            gapi.client.oauth2.userinfo.get().execute(handleResponse);
        }
        function handleResponse(res) {
            document.getElementById('hdRegEmail').value = res.email;
            document.getElementById('hdRegFullname').value = res.name;
            document.getElementById('registFrom').value = 'gg';
            angular.element(document.getElementById('hdRegEmail')).scope().runAng(res);
        }
    }
</script>

<!-- AngularJS Error : headerCtrl not found
<div class="header" ng-controller="headerCtrl">
-->
<div class="header" ng-controller="headerCtrl">
    <nav id="myNavmenu" class="navmenu navmenu-default navmenu-fixed-left offcanvas" role="navigation">
        <div class="navmenu-brand">
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#sign-in-modal"> Sign in </a>
            <a href="#" class="btn btn-default" data-toggle="modal" data-target="#sign-up-modal"> Sign up </a>
        </div>

        <ul class="nav navmenu-nav">
            <li>
                <a href="<?php echo $this->Html->url('/') ?>">HOME</a>
            </li>
            <li>
                <a href="<?php echo $this->Html->url(array("controller" => "blog", "action" => "index")) ?>">BLOG</a>
            </li>
            <li>
                <a href="<?php echo $this->Html->url('/deals') ?>">ALL DEALS</a>
            </li>
            <li>
                <a href="<?php echo $this->Html->url(array("controller" => "coupons", "action" => "topCoupon")) ?>">
                    TOP COUPON CODES
                </a>
            </li>
            <li>
                <a href="#"><strong>SUMMER SEASON</strong> 2014</a>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">CATEGORIES <b class="caret"></b></a>
                <ul class="dropdown-menu navmenu-nav" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li><a href="#">Separated link</a></li>
                    <li><a href="#">One more separated link</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top navbar-default-black" data-spy="affix" data-offset-top="1">
        <div class="container">

            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <!--
                    <button type="button" class="navbar-toggle" data-toggle="collapse"
                            data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                       <span class="icon-bar"></span>
                    </button>
                -->
                <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target="#myNavmenu"
                        data-canvas="body" style="float: left;margin-left: 15px;">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-toggle" style="padding: 0">
                    <i class="search-mobile fa search-btn fa-search"></i>
                </a>

                <a href="<?php echo $this->Html->url('/') ?>" class="navbar-brand page-scroll logo"
                   style="float: left;display: block;margin-left: 80px">
                    <img src="<?php echo $this->Html->url('/assets/img/logo.png') ?>" alt="Most coupon" width="122"
                         height="57"/> </a>

                <div class="search-open">
                    <div class="input-group animated fadeInDown">
                        <input type="text" class="form-control" placeholder="Search">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="button"><i class="fa fa-search"></i></button>
                            </span>
                    </div>
                </div>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="<?php echo $this->Html->url('/') ?>">HOME</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="<?php echo $this->Html->url(array("controller" => "blog", "action" => "index")) ?>">BLOG</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="<?php echo $this->Html->url('/deals') ?>">ALL DEALS</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="<?php echo $this->Html->url(array("controller" => "coupons", "action" => "topCoupon")) ?>">TOP
                            COUPON CODES</a>
                    </li>
                    <li>
                        <button class="btn btn-primary">
                            <strong>SUMMER SEASON</strong> 2014
                        </button>
                        <button
                            onclick="location.href='<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index')) ?>'"
                            class="btn btn-default">
                            <strong>CATEGORIES</strong>
                        </button>
                    </li>
                </ul>
                <div class="tail">
					<?php if(!$this->Session->read('User.email') && !$this->Session->read('User.id')): ?>
                    <div class="inner">
                        <a href="#" class="active" data-toggle="modal" data-target="#sign-in-modal"> Sign in </a>
                        <span class="sep"></span>
                        <a href="#" data-toggle="modal" data-target="#sign-up-modal"> Sign up </a>
                        <i class="curvy right"></i>
                    </div>
                <?php elseif($this->Session->read('User.email')): ?>
                    <div class="inner">
                        <div class="dropdown">
                            <a class="dropdown-toggle account-name ellipsis" type="button"
                                    id="dropdownAccount" data-toggle="dropdown" aria-expanded="true">
                                Hi, <?php echo $this->Session->read('User.fullname');?>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right dropdown-account" role="menu"
                                aria-labelledby="dropdownAccount">
                                <li role="presentation">
                                    <a href="<?php echo($this->Session->read('User.status') != 'lock' ? $profileURL : '') ?>"
                                    role="menuitem" tabindex="-1">Profile</a>
                                </li>
                                <li role="presentation" class="divider"></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo $this->Html->url(['controller' => 'users', 'action' => 'logout']);?>">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                <?php elseif($this->Session->read('User.id')): ?>
                    <div class="inner">
                        <div class="dropdown">
                            <a class="dropdown-toggle account-name ellipsis" type="button"
                                    id="dropdownAccount" data-toggle="dropdown" aria-expanded="true">
                                Hi, <?php echo $this->Session->read('User.username');?>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right dropdown-account" role="menu"
                                aria-labelledby="dropdownAccount">
                                <li role="presentation">
                                    <a href="<?php echo($this->Session->read('User.status') != 'lock' ? $profileURL : '') ?>"
                                    role="menuitem" tabindex="-1">Profile</a>
                                </li>
                                <li role="presentation" class="divider"></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo $this->Html->url(['controller' => 'users', 'action' => 'logout']);?>">Logout</a></li>
                            </ul>
                        </div>
                    <?php elseif ($this->Session->read('User.id')): ?>
                        <div class="inner">
                            <div class="dropdown">
                                <a class="dropdown-toggle account-name ellipsis" type="button"
                                   id="dropdownAccount" data-toggle="dropdown" aria-expanded="true">
                                    Hi, <?php echo $this->Session->read('User.username'); ?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right dropdown-account" role="menu"
                                    aria-labelledby="dropdownAccount">
                                    <li role="presentation"><a role="menuitem" tabindex="-1"
                                                               href="<?php echo $this->Html->url(['controller' => 'users', 'action' => 'index']); ?>">Profile</a>
                                    </li>
                                    <li role="presentation" class="divider"></li>
                                    <li role="presentation"><a role="menuitem" tabindex="-1"
                                                               href="<?php echo $this->Html->url(['controller' => 'users', 'action' => 'logout']); ?>">Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    <div class="modal fade" id="sign-up-modal">
        <div class="modal-dialog">
            <form class="modal-content" name="frmRegister" novalidate>
                <div class="modal-header"> Register</div>
                <div class="modal-body row">
                    <div class="col-sm-6">
                        <a onclick="fb_login()" class="btn btn-block btn-social btn-facebook">
                            <i class="fa fa-facebook"></i> Sign in with Facebook
                        </a>
                    </div>
                    <!--
                                        <div class="col-sm-6">
                                            <a class="btn btn-block btn-social btn-pinterest">
                                                <i class="fa fa-pinterest"></i> Sign in with Pinterest
                                            </a>
                                        </div>
                                        <div class="col-sm-6">
                                            <a class="btn btn-block btn-social btn-twitter">
                                                <i class="fa fa-twitter"></i> Sign in with Twitter
                                            </a>
                                        </div>
                    -->
                    <span id="signinButton" class="col-sm-6">
                      <span
                          class="btn btn-block btn-social btn-google-plus g-signin"
                          data-callback="signinCallback"
                          data-clientid="374964164994-t48bjbmr019rg8d4nq4vrie42vc19h3n.apps.googleusercontent.com"
                          data-cookiepolicy="single_host_origin"
                          data-requestvisibleactions="http://schema.org/AddAction"
                          data-scope="
                        https://www.googleapis.com/auth/plus.login
                        https://www.googleapis.com/auth/userinfo.email
                        "
                          >
                        <i class="fa fa-google-plus"></i> Sign in with Google
                      </span>
                    </span>

                    <input name="username" id="username" ng-model="regUsername" ng-minlength="6" ng-maxlength="100"
                    id="regUsername" type="text" class="form-control" placeholder="Your username *" required/>
                    <label class = "text-danger" ng-show = "showError && frmRegister.username.$error.required">
                        <?php echo __('require field', ['Username']); ?>
                    </label>
                    <label class = "text-danger" ng-show = "showError && frmRegister.username.$error.minlength">
                        <?php echo __('minlen', ['Username', 6]); ?>
                    </label>
                    <label class = "text-danger" ng-show = "showError && frmRegister.username.$error.maxlength">
                        <?php echo __('maxlen', ['Username', 100]); ?>
                    </label>

                    <input name="email" id="email" ng-model="regEmail" id="regEmail" type="email" class="form-control"
                           placeholder="Your email *" required/>
                    <label class = "text-danger" ng-show = "showError && frmRegister.email.$error.required">
                        <?php echo __('require field', ['Email']); ?>
                    </label>
                    <label class = "text-danger" ng-show = "showError && frmRegister.email.$error.email">
                        <?php echo __('invalid email'); ?>
                    </label>

                    <label ng-show="errMess" style="color: red;">{{errMess}}</label>
                    <a id="LoginNow" ng-click="loginNow()" ng-show="errMess" href="#" data-toggle="modal"
                       data-target="#sign-in-modal">Sign in now</a>
                    <label ng-show="errMess">OR</label>
                    <a ng-show="errMess" data-dismiss="modal" aria-label="Close" data-toggle="modal"
                       data-target="#forgot-modal"
                       style="cursor: pointer">
                        <em>Forgot password</em>
                    </a>
                    <input name="password" id="password" ng-model="regPwd" ng-minlength="6" ng-maxlength="100"
                    type="password" class="form-control" placeholder="Your password *" required/>
                    <label class = "text-danger" ng-show = "showError && frmRegister.password.$error.required">
                        <?php echo __('require field', ['Password']); ?>
                    </label>
                    <label class = "text-danger" ng-show = "showError && frmRegister.password.$error.minlength">
                        <?php echo __('minlen', ['Password', 6]); ?>
                    </label>
                    <label class = "text-danger" ng-show = "showError && frmRegister.password.$error.maxlength">
                        <?php echo __('maxlen', ['Password', 100]); ?>
                    </label>

                    <label ng-show="messValidPassword" class = "text-danger">Password must be contain at
                        least 6 characters</label>
                    <input name="cfPassword" id="cfPassword" ng-model="regCfPwd"
                    type="password" class="form-control" placeholder="Confirm password *" required/>
                    <label class = "text-danger" ng-show = "showError && frmRegister.cfPassword.$error.required">
                        <?php echo __('require field', ['Confirm Password']); ?>
                    </label>
                    <label class = "text-danger" ng-show = "errorMessage">{{errorMessage}}</label>

                    <button ng-click="Register()" id="btnRegister" class="btn btn-primary dark-text btn-block">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="sign-in-modal">
        <div class="modal-dialog">
            <form class="modal-content" name="frmLogin" novalidate>
                <div class="modal-header"> Sign in</div>
                <div class="modal-body row">
                    <div class="open-auth text-center row">
                        <!--
<div class="fb-login-button" onclick="fb_login()"  data-max-rows="1" data-size="large" data-show-faces="false" data-auto-logout-link="true"></div>
                        -->
                        <div class="col-sm-6">
                            <a onclick="fb_login()" class="btn btn-block btn-social btn-facebook">
                                <i class="fa fa-facebook"></i> Sign in with Facebook
                            </a>
                        </div>
                        <!--
                        <div class="col-sm-6">
                            <a href="<?php echo $this->Html->url(['controller' => 'TwitterLogin', 'action' => 'login']); ?>" class="btn btn-block btn-social btn-twitter">
                                <i class="fa fa-twitter"></i> Sign in with Twitter
                            </a>
                        </div>
                        -->
                        <span id="signinButton" class="col-sm-6">
                          <span
                              class="btn btn-block btn-social btn-google-plus g-signin"
                              data-callback="signinCallback"
                              data-clientid="374964164994-t48bjbmr019rg8d4nq4vrie42vc19h3n.apps.googleusercontent.com"
                              data-cookiepolicy="single_host_origin"
                              data-requestvisibleactions="http://schema.org/AddAction"
                              data-scope="
                            https://www.googleapis.com/auth/plus.login
                            https://www.googleapis.com/auth/userinfo.email
                            "
                              >
                            <i class="fa fa-google-plus"></i> Sign in with Google
                          </span>
                        </span>
                    </div>
                    <p> Or sign in with a MostCoupon account </p>
                    <input name="logUsername" ng-model="logUsername" type="text" class="form-control"
                           placeholder="Your username OR email address *" required/>
                    <label class = "text-danger" ng-show = "showError && frmLogin.logUsername.$error.required">
                        <?php echo __('require field', ['Username']); ?>
                    </label>

                    <input name="logPassword" ng-model="logPassword" type="password" class="form-control" placeholder="Your password *" required/>
                    <label class = "text-danger" ng-show = "showError && frmLogin.logPassword.$error.required">
                        <?php echo __('require field', ['Password']); ?>
                    </label>

                    <input type="hidden" ng-model="hdRegEmail" id="hdRegEmail"/>
                    <input type="hidden" ng-model="hdRegFullname" id="hdRegFullname"/>
                    <input type="hidden" ng-model="registFrom" id="registFrom"/>

                    <button ng-click="Login()" class="btn btn-primary dark-text btn-block"> Sign in</button>
                    <label style="color: red;" ng-show="messLogin == 'show'">Incorrect username or password</label>
                    <label style="color: red;" ng-show="messInactive == 'show'">
                        Your account has not yet been activated. Please check your email to activate. If you didn't
                        receive any activation email, click Re-active Your Account
                    </label>
                    <div class="text-center">
                        <a ng-show="messInactive == 'show'" data-dismiss="modal" aria-label="Close" data-toggle="modal"
                           data-target="#re-active-modal"
                           class="text-success underline" style="cursor: pointer">
                            <em>Re-active your account</em>
                        </a> &nbsp;&nbsp;&nbsp;
                        <a data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#forgot-modal"
                           class="text-success underline" style="cursor: pointer">
                            <em>Forgot password</em>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="forgot-modal">
        <div class="modal-dialog">
            <form class="modal-content" name="frmForgot" ng-submit = "forgotPassword()" novalidate>
                <div class="modal-header">Forgot your password?</div>
                <div class="modal-body row">
                    <p class="text-center">No problem! <br/> We'll email you a link to create a new one.</p>
                    <input ng-model="emailForgot" name="emailForgot" type="email" class="form-control" placeholder="Enter your registed email *" required />
                    <label class = "text-danger" ng-show = "showError && frmForgot.emailForgot.$error.email">
                        <?php echo __('invalid email');?>
                    </label>
                    <label class = "text-danger" ng-show = "showError && frmForgot.emailForgot.$error.required">
                        <?php echo __('require field', ['Email']);?>
                    </label>
                     <label class = "text-danger" ng-show = "errorMessage">{{errorMessage}}</label>

                    <button type="submit" id="btnForgotPwd" class="btn btn-primary dark-text btn-block"
                            style="margin-bottom: 10px">Send Email
                    </button>
                    <label ng-show="messResetPassword == 'show'">An email has been send to your inbox</label>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="re-active-modal">
        <div class="modal-dialog">
            <form class="modal-content">
                <div class="modal-header">Resend Activation Email</div>
                <div class="modal-body row">
                    <p class="text-center">If your activation email does not arrive, enter your email address and click
                        Send</p>
                    <input ng-model="emailReActive" type="email" class="form-control"
                           placeholder="Enter your registed email *" required/>
                    <button ng-click="sendReActiveEmail()" class="btn btn-primary dark-text btn-block" id="btnReActiveEmail"
                            style="margin-bottom: 10px">Send Email
                    </button>
                    <label ng-show="messResend == 'show'">Active Email has been send to your inbox</label>
                </div>
            </form>
        </div>
    </div>
</div>

