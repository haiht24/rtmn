<div class="row">
  <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
    <h1 class="page-title txt-color-blueDark">
      <i class="fa fa-table fa-users "></i>
      <a href="#">Users</a> <span>>Add New User</span>
    </h1>
  </div>
</div>
<section id="widget-grid" class="">
  <div class="row">
    <div class="button-container col-xs-12">
      <div class="modal-content">
        <div class="modal-body">
          <form class="smart-form" name='addUserForm' novalidate>
            <section>
              <label class="label">Full Name</label>
              <label class="input">
                <i class="icon-append fa fa-user"></i>
                <input type="text" placeholder="First Name and Last Name" name='fullname' ng-model="newUser.fullname" required>
              </label>
              <p class='error' ng-show='showError && addUserForm.fullname.$invalid'>Please enter full name</p>
            </section>
            <section>
              <label class="label">Email</label>
              <label class="input">
                <i class="icon-append fa fa-envelope"></i>
                <input type="email" placeholder="Email Address" name='email' ng-model="newUser.email" ng-pattern="emailRegex" required>
              </label>
              <p class='error' ng-show='showError && addUserForm.email.$invalid'>Please enter valid email</p>
            </section>
            <section>
              <label class="label">Password</label>
              <label class="input">
                <i class="icon-append fa fa-lock"></i>
                <input type="password" placeholder="Password" name='password' ng-model="newUser.password" required>
              </label>
              <p class='error' ng-show='showError && addUserForm.password.$invalid'>Please enter password</p>
            </section>
            <section>
              <label class="label">Phone</label>
              <label class="input">
                <i class="icon-append fa fa-phone"></i>
                <input type="tel" data-mask="(999) 999-9999" placeholder="Phone Number" ng-model="newUser.phone">
              </label>
            </section>
            <section>
              <label class="label">Skype</label>
              <label class="input">
                <i class="icon-append fa fa-skype"></i>
                <input type="text" placeholder="Skype Username" ng-model="newUser.skype">
              </label>
            </section>
            <section>
              <label class="label">Department</label>
              <label class="input">
                <i class="icon-append fa fa-briefcase"></i>
                <input type="text" placeholder="Current Department" ng-model="newUser.department">
              </label>
            </section>
            <section>
              <label class="label">Avatar</label>
              <div class="image-upload account-logo-upload" image-upload="newUser.avatar" fixed image-loading
                   default-image= "'<?php echo $this->Html->url('/img/avatars/male.png') ?>'"
                   max-image-size="307200" title="<?php echo __('Click on the image to choose another one'); ?>">
              </div>
            </section>
            <section>
              <label class="label">System Role</label>
              <div class="inline-group">
                <label class="radio">
                  <input name="radio-inline" ng-model="newUser.role" ng-value="'subscriber'" type="radio">
                  <i></i>subscriber
                </label>
                <label class="radio">
                  <input name="radio-inline" type="radio" ng-model="newUser.role" ng-value="'editor'">
                  <i></i>editor
                </label>
                <label class="radio">
                  <input name="radio-inline" type="radio" ng-model="newUser.role" ng-value="'publisher'">
                  <i></i>publisher
                </label>
                <label class="radio">
                  <input name="radio-inline" type="radio" ng-model="newUser.role" ng-value="'administrator'">
                  <i></i>Administrator
                </label>
              </div>
            </section>
            <section>
              <label class="label">User status</label>
              <div class="inline-group">
                <label class="radio">
                  <input name="radio-status" type="radio"
                  ng-model="newUser.status"
                  ng-checked = "newUser.status == 'active'" ng-value = "'active'" >
                  <i></i>Enable
                </label>
                <label class="radio">
                  <input name="radio-status" type="radio"
                  ng-model="newUser.status"
                  ng-checked="newUser.status == 'inactive'" ng-value="'inactive'">
                  <i></i>Disable
                </label>
              </div>
            </section>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default"
                  id='cancelUser' ng-click="cancelUser()">
            Cancel
          </button>
          <button type="button" class="btn btn-primary"
                  id='addNew' ng-click='addNewUser()'>
            Add
          </button>
        </div>
      </div>
    </div>
  </div>
</section>