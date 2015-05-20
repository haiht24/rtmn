<?php $roleName = 'AAA'; $roleColor = 'blueDard';?>
<script type="text/ng-template" id="user-role-list.html">
<div class="jarviswidget jarviswidget-color-<?= $roleColor ?>" id="wid-<?= $roleName  ?>"
     data-widget-deletebutton="false"
     data-widget-colorbutton="false"
     data-widget-editbutton="false">
  <header>
    <span class="widget-icon"> <i class="fa fa-table"></i> </span>
    <h2>User Role : {{key}}</h2>
  </header>
  <div>
    <div class="jarviswidget-editbox">
    </div>
    <div class="widget-body">
      <div class="table-responsive">
        <table id="resultTable" class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th style="width:30px">Avatar</th>
              <th>Full Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Skype</th>
              <th>Department</th>
              <th>Postal</th>
              <th>Status</th>
              <th ng-show="user.permissions.allow_edit_user == 1">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="(index, item) in role.pages[role.currentPage]">
              <td>
                <img ng-hide="item.user.avatar" src="<?php echo $this->Html->url('/img/avatars/male.png') ?>" alt="" width="20">
                <img ng-show="item.user.avatar" ng-src="{{item.user.avatar}}" alt="" width="20">
              </td>
              <td>{{item.user.fullname}}</td>
              <td>{{item.user.email}}<a href="javascript:void(0);" class="pull-right"><i class="fa fa-key"></i></a></td>
              <td>{{item.user.phone}}</td>
              <td>{{item.user.skype}}</td>
              <td>{{item.user.department}}</td>
              <td>{{item.user.postal}}</td>
              <td><span class="label label-success">{{item.user.status}}</span></td>
              <td ng-show="user.permissions.allow_edit_user == 1">
                <a style = "cursor:pointer;text-decoration: none" ng-click="editUser(item.user,role.currentPage,index)"
                  class="fa fa-pencil-square-o"></a>
                <a style = "cursor:pointer;text-decoration: none" ng-click="deleteUser(item.user)" class="fa fa-trash"></a>
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
</script>
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
               ng-model="filter"
               ng-change="search()"
               placeholder="Filter by fullname, email, phone or skype" id="search-user"/>

        <div class="input-group-btn">
          <button type="submit" class="btn btn-default">
            <i class="fa fa-fw fa-search fa-lg"></i>
          </button>
        </div>
      </div>
      <br>
    </div>
    <div class="button-container col-xs-12">
      <div class="input-group" ng-if="user.permissions.allow_edit_user == 1">
        <a class="btn btn-primary btn-add-user" href="#/add"><i class="fa fa-plus"></i> Add User</a>
      </div>
      <br>
    </div>
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div ng-repeat="(key, role) in roles" ng-include=" 'user-role-list.html'">
      </div>
    </article>
  </div>
</section>