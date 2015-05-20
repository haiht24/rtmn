<div class="row">
  <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
    <h1 class="page-title txt-color-blueDark">
      <i class="fa fa-table fa-users "></i>
      <a href="#">Users</a> <span>>Edit User</span>
    </h1>
  </div>
</div>
<section id="widget-grid" class="">
  <div class="row">
    <div class="button-container col-xs-12">
      <div class="modal-content">
        <div class="modal-body">
          <form class="smart-form" name='editUserForm' novalidate>
            <section>
              <label class="label">Full Name</label>
              <label class="input">
                <i class="icon-append fa fa-user"></i>
                <input type="text" placeholder="First Name and Last Name"
                       name='fullname' ng-model="user.fullname" required>
              </label>
              <p class='error'
                 ng-show='showError && editUserForm.fullname.$invalid'>
                Please enter full name
              </p>
            </section>
            <section>
              <label class="label">Email</label>
              <label class="input">
                <i class="icon-append fa fa-envelope"></i>
                <input type="email" placeholder="Email Address"
                       name='email' ng-model="user.email" ng-pattern="emailRegex" required>
              </label>
              <p class='error'
                 ng-show='showError && editUserForm.email.$invalid'>
                Please enter valid email
              </p>
            </section>
            <section>
              <label class="label">Phone</label>
              <label class="input">
                <i class="icon-append fa fa-phone"></i>
                <input type="tel" data-mask="(999) 999-9999"
                       placeholder="Phone Number" ng-model="user.phone"/>
              </label>
            </section>
            <section>
              <label class="label">Skype</label>
              <label class="input">
                <i class="icon-append fa fa-skype"></i>
                <input type="text" placeholder="Skype Username"
                       ng-model="user.skype">
              </label>
            </section>
            <section>
              <label class="label">Department</label>
              <label class="input">
                <i class="icon-append fa fa-briefcase"></i>
                <input type="text" placeholder="Current Department"
                       ng-model="user.department">
              </label>
            </section>
            <section>
              <label class="label">Avatar</label>
              <div class="image-upload account-logo-upload"
                   image-upload="user.avatar" fixed image-loading
                   default-image= "'<?php echo $this->Html->url('/img/avatars/male.png') ?>'"
                   max-image-size="307200"
                   title="<?php echo ('Click on the image to choose another one'); ?>">
              </div>
            </section>
            <section>
              <label class="label">System Role</label>
              <div class="inline-group">
                <label class="radio">
                  <input name="radio-inline" ng-model="user.role"
                         ng-value="'subscriber'" type="radio">
                  <i></i>subscriber
                </label>
                <label class="radio">
                  <input name="radio-inline" type="radio"
                         ng-model="user.role" ng-value="'editor'">
                  <i></i>editor
                </label>
                <label class="radio">
                  <input name="radio-inline" type="radio"
                         ng-model="user.role" ng-value="'publisher'">
                  <i></i>publisher
                </label>
                <label class="radio">
                  <input name="radio-inline" type="radio"
                         ng-model="user.role" ng-value="'administrator'">
                  <i></i>Administrator
                </label>
              </div>
            </section>
            <section>
              <label class="label">Change Password</label>
              <label class="input">
                <input type="password" placeholder="New password"
                       ng-model="user.newPwd">
              </label>
              <br />
              <label class="input">
                <input type="password" placeholder="Confirm new password"
                       ng-model="user.cfNewPwd">
              </label>
              <br />
              <label class="checkbox">
                  <input name="chkSendNewPwdToUser" type="checkbox"
                         ng-model="user.sendNewPwd" ng-checked="user.sendNewPwd">
                  <i></i>Email new password to user
              </label>
              <p class='error'
                 ng-show='changePwdMess == "show"'>
                Confirm password not match
              </p>
            </section>
            <section>
              <label class="label">User status</label>
              <div class="inline-group">
                <label class="radio">
                  <input name="radio-status" type="radio"
                  ng-model="user.status"
                  ng-checked = "user.status == 'active'" ng-value = "'active'" >
                  <i></i>Enable
                </label>
                <label class="radio">
                  <input name="radio-status" type="radio"
                  ng-model="user.status"
                  ng-checked="user.status == 'inactive'" ng-value="'inactive'">
                  <i></i>Disable
                </label>
              </div>
            </section>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default"
                  ng-click="cancelUser()">
            Cancel
          </button>
          <button type="button" class="btn btn-primary"
                  ng-click='editUser()'>
            Save
          </button>
        </div>
      </div>
    </div>
  </div>
</section>