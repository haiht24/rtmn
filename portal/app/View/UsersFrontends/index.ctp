<?php $this->Html->script('users/index/edit', ['inline' => false]); ?>
<?php $this->Ng->ngController('UserFrontendCtrl') ?>
<?php
    $this->Ng->ngInit(
        [
            'users' => isset($users) ? $users : []
        ]
    );
?>

<div class="row">
  <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
    <h1 class="page-title txt-color-blueDark">
      <i class="fa fa-table fa-users "></i>
      Users <span>>User Management</span>
    </h1>
  </div>
</div>
<section id="widget-grid" class="">
  <div class="row">
    <div class="search-box-container col-xs-12">
      <div class="input-group input-group-lg">
        <input class="form-control input-lg" type="text"
        ng-model="query"
        placeholder="Filter by fullname, username, email, facebook id, status" id="search-user"/>
        <div class="input-group-btn">
          <button type="submit" class="btn btn-default">
            <i class="fa fa-fw fa-search fa-lg"></i>
          </button>
        </div>
      </div>
      <br>
    </div>
  </div>
</section>
<div class="jarviswidget jarviswidget-color" id="wid"
     data-widget-deletebutton="false"
     data-widget-colorbutton="false"
     data-widget-editbutton="false">
  <header>
    <span class="widget-icon"> <i class="fa fa-user"></i> </span>
    <h2>Users List</h2>
  </header>
  <div>
    <div class="jarviswidget-editbox">
    </div>
    <div class="widget-body">
      <div class="table-responsive">
        <table id="resultTable" class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th>Full Name</th>
              <th>Username</th>
              <th>Email</th>
              <th>Facebook ID</th>
              <th>Created</th>
              <th>Modified</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="(index, item) in users | filter:search">
              <td>{{item.UsersFrontend.fullname}}</td>
              <td>{{item.UsersFrontend.username}}</td>
              <td>{{item.UsersFrontend.email}}</td>
              <td>{{item.UsersFrontend.facebook_id}}</td>
              <td>{{item.UsersFrontend.created}}</td>
              <td>{{item.UsersFrontend.modified}}</td>
              <td>
                <span ng-show = "item.UsersFrontend.status == 'lock' || item.UsersFrontend.status == 'inactive'" class="label label-danger">
                    {{item.UsersFrontend.status}}
                </span>
                <span ng-show = "item.UsersFrontend.status == 'active'" class="label label-success">
                    {{item.UsersFrontend.status}}
                </span>
                <a ng-show = "item.UsersFrontend.locked == 1" class="pull-right" style="cursor: none;">
                    <i class="fa fa-key"></i>
                </a>
              </td>
              <td>
                <a style = "cursor:pointer;text-decoration: none"
                data-toggle="modal" data-target="#formEditUser"
                ng-click = "editUser(item.UsersFrontend)" class="fa fa-pencil-square-o"></a>

                <a class="pull-right" style="cursor: pointer;"
                ng-click = "lockUser(item.UsersFrontend)"
                ng-show = "item.UsersFrontend.status != 'lock' && item.UsersFrontend.status != 'inactive'"
                >
                <i class="fa fa-lock"></i>
                </a>

                <a class="pull-right" style="cursor: pointer;"
                ng-click = "lockUser(item.UsersFrontend)"
                ng-show = "item.UsersFrontend.status == 'lock' && item.UsersFrontend.status != 'inactive'"
                >
                <i class="fa fa-key"></i>
                </a>
<!--
                <a ng-click = "disableUser(item.UsersFrontend)"
                ng-show = "item.UsersFrontend.status == 'active'"
                style = "cursor:pointer;text-decoration: none" title="Disable this user" class="fa fa-wheelchair"></a>

                <a ng-click = "enableUser(item.UsersFrontend)"
                ng-show = "item.UsersFrontend.status == 'inactive'"
                style = "cursor:pointer;text-decoration: none" title="Enable this user" class="fa fa-check"></a>

                <a class="pull-right" style="cursor: pointer;"><i class="fa fa-key"></i></a>

                <a style = "cursor:pointer;text-decoration: none" ng-click="deleteUser(item.UsersFrontend)" class="fa fa-trash"></a>
-->
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="text-center">
        <hr>
        <ul class="pagination no-margin">
          <li class="arrow" ng-click="prevPage(role)" ng-show="role.pages.length > 1"
              ng-class="{'disabled': 0 == role.currentPage}">
            <a>Previous</a>
          </li>
          <li ng-repeat="n in range(role.pages.length)"
              ng-class="{active: n == role.currentPage}" ng-click="setPage(role, n)">
            <a ng-show="n >= 0 && n < 10">{{ n + 1}}</a>
          </li>
          <li>
            <input type="number" ng-model="role.currentPageInc" ng-show="role.pages.length > 10" ng-change="changePage(role)"/>
          </li>
          <li ng-click="setPage(role, role.pages.length - 1)"
              ng-class="{active: (role.pages.length - 1) == role.currentPage}">
            <a ng-show="role.pages.length > 10">{{ role.pages.length}}</a>
          </li>
          <li class="arrow" ng-show="role.pages.length > 1"
              ng-click="nextPage(role)"
              ng-class="{'disabled': (role.pages.length - 1) == role.currentPage}">
            <a href="">Next</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit User  -->
<div class="modal fade" id="formEditUser" tabindex="-1" role="dialog" aria-labelledby="formEditUserLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="formEditUserLabel">Edit User</h4> </div>
      <div class="modal-body">
        <form class="smart-form" name='editUserForm' >
          <section>
            <label class="label">Full Name</label>
            <label class="input">
              <input type="text" placeholder="First Name and Last Name" name='fullname' ng-model="editingUser.fullname" > </label>
          </section>
          <section>
            <label class="label">Username</label>
            <label class="input">
              <input type="text" placeholder="Username" name='username' ng-model="editingUser.username" required> </label>
            <p class='error' ng-show='showError && editUserForm.username.$invalid'> Please enter username </p>
          </section>
          <section>
            <label class="label">Email</label>
            <label class="input">
              <input type="email" placeholder="Email Address" name='email' ng-model="editingUser.email" required> </label>
            <p class='error' ng-show='showError && editUserForm.email.$invalid'> Please enter valid email </p>
          </section>
          <section>
            <label class="label">Facebook ID</label>
            <label class="input">
              <input type="tel" placeholder="Facebook ID" ng-model="editingUser.facebook_id" /> </label>
          </section>
<!--
                  <section>
            <label class="label">User status</label>
            <div class="inline-group">
              <label class="radio" ng-show = "editingUser.status != 'inactive'">
                <input name="radio-status" type="radio" ng-model="editingUser.status" ng-checked="editingUser.status == 'active'" ng-value = '"active"'> <i></i>Active </label>

              <label class="radio" ng-show = "editingUser.status != 'active' && editingUser.status != 'lock'">
                <input name="radio-status" type="radio" ng-model="editingUser.status" ng-checked="editingUser.status == 'inactive'" ng-value = '"inactive"'> <i></i>Inactive </label>

              <label class="radio" ng-show = "editingUser.status != 'inactive'">
                <input name="radio-status" type="radio" ng-model="editingUser.status" ng-checked="editingUser.status == 'lock'" ng-value = '"lock"'> <i></i>Lock </label>
            </div>
          </section>
-->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="cancelEdit" class="btn btn-default" data-dismiss="modal"> Cancel </button>
        <button type="button" class="btn btn-primary" ng-click='applyEditUser(editingUser)'> Save </button>
      </div>
    </div>
  </div>
</div>
