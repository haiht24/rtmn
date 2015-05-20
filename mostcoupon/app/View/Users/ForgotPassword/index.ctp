<?php $this->Ng->ngController('headerCtrl') ;
    $this->Ng->ngInit(
        [
            'tokenResetPassword' => isset($this->request['pass'][0]) ? $this->request['pass'][0] : []
        ]
    );
?>
<div class="container main-content paper show-text-content">
    <h1 class="title font-quark">Reset password</h1>
    <div class="body">
        <form>
            <input ng-model = "rsNewPassword" type="password" style="width: 50%;" placeholder="Enter your new password" required />
            <br /><br />
            <input ng-model = "rsReNewPassword" type="password" style="width: 50%;" placeholder="Confirm your new password" required />
            <br /><br />
            <input type="hidden" ng-model = "tokenResetPassword"/>
            <button ng-click = "resetPassword()"  class="btn btn-primary dark-text btn-block" style="width: 20%;">Reset Password</button>
            <label style="color: green;">{{messChange}}</label>
        </form>
    </div>
</div>