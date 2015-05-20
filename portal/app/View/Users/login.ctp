<?php $this->Ng->ngController('LoginCtrl') ?>
<div class="row">
    <!--    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">-->
    <!--        <h1 class="txt-color-red login-header-big">SmartAdmin</h1>-->
    <!--        <div class="hero">-->
    <!---->
    <!--            <div class="pull-left login-desc-box-l">-->
    <!--                <h4 class="paragraph-header">It's Okay to be Smart. Experience the simplicity of SmartAdmin, everywhere you go!</h4>-->
    <!--                <div class="login-app-icons">-->
    <!--                    <a href="javascript:void(0);" class="btn btn-danger btn-sm">Frontend Template</a>-->
    <!--                    <a href="javascript:void(0);" class="btn btn-danger btn-sm">Find out more</a>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!---->
    <!--        <div class="row">-->
    <!--            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">-->
    <!--                <h5 class="about-heading">About SmartAdmin - Are you up to date?</h5>-->
    <!--                <p>-->
    <!--                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa.-->
    <!--                </p>-->
    <!--            </div>-->
    <!--            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">-->
    <!--                <h5 class="about-heading">Not just your average template!</h5>-->
    <!--                <p>-->
    <!--                    Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi voluptatem accusantium!-->
    <!--                </p>-->
    <!--            </div>-->
    <!--        </div>-->
    <!---->
    <!--    </div>-->
    <div class="col-xs-12 col-sm-12 col-md-offset-3 col-md-5 col-lg-offset-4 col-lg-4">
        <div class="well no-padding">
            <form id="login-form" class="smart-form client-form"
                  method="post"
                  action="<?php echo $this->Html->url(['controller' => 'users', 'action' => 'login']) ?>"
                  name="userLoginForm" novalidate>
                <header>
                    Sign In
                </header>

                <fieldset>
                    <section>
                        <?php
                        echo $this->Session->flash('flash', ['element' => 'flash', 'params' => ['class' => 'text-info']]);
                        echo $this->Session->flash('error', ['element' => 'flash', 'params' => ['class' => 'text-danger']]);
                        echo $this->Session->flash('success', ['element' => 'flash', 'params' => ['class' => 'text-success']]);
                        ?>
                        <?php echo $this->element('notifications') ?>
                    </section>
                    <section>
                        <label class="label">E-mail</label>
                        <label class="input"
                            ng-class="{'state-error': errorMessageVisible && userLoginForm.email.$invalid }">
                            <i class="icon-append fa fa-user"></i>
                            <input type="email" name="email" ng-model="user.email" required ng-pattern="emailRegex">
                            <b class="tooltip tooltip-top-right">
                                <i class="fa fa-user txt-color-teal"></i>
                                Please enter email address/username
                            </b>
                        </label>
                        <!--                        <em class="invalid" for="email"-->
                        <!--                            ng-if="errorMessageVisible && userLoginForm.email.$error.required">-->
                        <!--                            Please enter your email address-->
                        <!--                        </em>-->
                        <!--                        <em class="invalid" for="email"-->
                        <!--                            ng-if="errorMessageVisible && userLoginForm.email.$error.pattern">-->
                        <!--                            Please enter a VALID email address-->
                        <!--                        </em>-->
                    </section>

                    <section>
                        <label class="label">Password</label>
                        <label class="input"
                            ng-class="{'state-error': errorMessageVisible && userLoginForm.password.$invalid }">
                            <i class="icon-append fa fa-lock"></i>
                            <input type="password" name="password" ng-model="user.password" required>
                            <b class="tooltip tooltip-top-right"
                               ng-show="errorMessageVisible && userLoginForm.password.$invalid">
                                <i class="fa fa-lock txt-color-teal"></i>
                                Enter your password
                            </b>
                        </label>
                        <!--                        <em class="invalid" for="password"-->
                        <!--                            ng-if="errorMessageVisible && userLoginForm.password.$invalid">-->
                        <!--                            Please enter your password-->
                        <!--                        </em>-->
                        <div class="note">
                          <a href="<?php echo $this->html->url(['controller' => 'users', 'action' => 'passwordReset'])?>">Forgot password?</a>
                        </div>
                    </section>

                    <section>
                        <label class="checkbox">
                            <input type="checkbox" name="remember">
                            <i></i>Stay signed in</label>
                    </section>
                </fieldset>
                <footer>
                    <button type="submit" class="btn btn-primary" ng-click="doLogin($event)">
                        Sign in
                    </button>
                </footer>
            </form>

        </div>

        <!--        <h5 class="text-center"> - Or sign in using -</h5>-->
        <!--        <ul class="list-inline text-center">-->
        <!--            <li>-->
        <!--                <a href="javascript:void(0);" class="btn btn-primary btn-circle"><i class="fa fa-facebook"></i></a>-->
        <!--            </li>-->
        <!--            <li>-->
        <!--                <a href="javascript:void(0);" class="btn btn-info btn-circle"><i class="fa fa-twitter"></i></a>-->
        <!--            </li>-->
        <!--            <li>-->
        <!--                <a href="javascript:void(0);" class="btn btn-warning btn-circle"><i class="fa fa-linkedin"></i></a>-->
        <!--            </li>-->
        <!--        </ul>-->

    </div>
</div>
