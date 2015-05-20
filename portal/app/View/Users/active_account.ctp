<?php $this->Ng->ngController('ActiveAccountCtrl') ?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-10">
        <div class="well no-padding">
            <form class="smart-form client-form">
                <header>
                    <?php echo ('This is first time you login, please create your new password')?>
                </header>

                <fieldset>

                    <section>
                        <label class="label">Old Password</label>
                        <label class="input">
                            <input type="password" ng-model="user.oldPassword" required placeholder="Please enter your old password">
                            <b class="tooltip tooltip-top-right">
                                <i class="fa fa-user txt-color-teal"></i>
                                Please enter your old password
                            </b>
                        </label>
                        <br />
                        <label class="input">
                            <input type="password" ng-model="user.newPassword" required placeholder="Please enter your new password">
                            <b class="tooltip tooltip-top-right">
                                <i class="fa fa-user txt-color-teal"></i>
                                Please enter your new password
                            </b>
                        </label>
                        <br />
                        <label class="input">
                            <input type="password" ng-model="user.confirmNewPassword" required placeholder="Please confirm your new password">
                            <b class="tooltip tooltip-top-right">
                                <i class="fa fa-user txt-color-teal"></i>
                                Please confirm your new password
                            </b>
                        </label>
                        <em class="invalid" ng-show = "message" >
                            {{message}}
                        </em>
                        <input type="hidden" ng-bind = "user.id = '<?php echo $this->Session->read('Auth.User.Id'); ?>'"  />
                    </section>
                </fieldset>
                <footer>
                    <button id="btnActiveNewAcc" class="btn btn-primary" ng-click="activeNewAccount()">
                        Change password and Active your account
                    </button>
                </footer>
            </form>
        </div>
    </div>
</div>
