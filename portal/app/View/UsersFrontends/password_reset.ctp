<?php $this->Ng->ngController('PasswordResetCtrl') ?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
        <h1 class="txt-color-red login-header-big">SmartAdmin</h1>
        <div class="hero">

            <div class="pull-left login-desc-box-l">
                <h4 class="paragraph-header">It's Okay to be Smart. Experience the simplicity of SmartAdmin, everywhere you go!</h4>
                <div class="login-app-icons">
                    <a href="javascript:void(0);" class="btn btn-danger btn-sm">Frontend Template</a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-sm">Find out more</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <h5 class="about-heading">About SmartAdmin - Are you up to date?</h5>
                <p>
                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa.
                </p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <h5 class="about-heading">Not just your average template!</h5>
                <p>
                    Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi voluptatem accusantium!
                </p>
            </div>
        </div>

    </div>
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
        <div class="well no-padding">
            <form id="login-form" class="smart-form client-form"
                  name="resetPasswordForm" novalidate action="<?php echo $this->Html->url() ?>" method="post">
                <header>
                    <?php echo ('Please enter your email to get new password')?>
                </header>

                <fieldset>

                    <section>
                        <label class="label">E-mail</label>
                        <label class="input"
                            ng-class="{'state-error': errorMessageVisible && userLoginForm.email.$invalid }">
                            <i class="icon-append fa fa-user"></i>
                            <input type="email" name="email" ng-model="user.email" required ng-pattern="emailRegex">
                            <b class="tooltip tooltip-top-right">
                                <i class="fa fa-user txt-color-teal"></i>
                                Please enter email address
                            </b>
                        </label>
                        <em class="invalid" for="email"
                            ng-if="errorMessageVisible && userLoginForm.email.$error.required">
                            Please enter your email address
                        </em>
                        <em class="invalid" for="email"
                            ng-if="errorMessageVisible && userLoginForm.email.$error.pattern">
                            Please enter a VALID email address
                        </em>
                    </section>
                </fieldset>
                <footer>
                    <button type="submit" class="btn btn-primary" ng-click="doLogin($event)">
                        Sign in
                    </button>
                </footer>
            </form>
        </div>
    </div>
</div>
