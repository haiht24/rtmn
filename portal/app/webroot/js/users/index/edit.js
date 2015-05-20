angular.module('fdb').controller('UserEditCtrl',
function ($scope, $http, $timeout, $location) {
  // Init
  $scope.user = {};
  $scope.showError = true;
  $scope.user.sendNewPwd = false;

  if ($scope.edittingUser) {
    $scope.user = angular.copy($scope.edittingUser);
  } else {
    $location.path('/');
  }

  $scope.editUser = function () {

    $scope.showError = true;
    if ($scope.editUserForm.$invalid) {
      return;
    }
    if($scope.user.newPwd){
        if($scope.user.cfNewPwd != $scope.user.newPwd){
            $scope.changePwdMess = 'show';
            return;
        }
    }

    var dataSave = {};
    dataSave.id = $scope.user.id;
    dataSave.fullname = $scope.user.fullname;
    dataSave.email = $scope.user.email;
    dataSave.phone = $scope.user.phone;
    dataSave.skype = $scope.user.skype;
    dataSave.department = $scope.user.department;
    dataSave.avatar = $scope.user.avatar;
    dataSave.role = $scope.user.role;
    dataSave.status = $scope.user.status;
    if($scope.user.newPwd){
        dataSave.password = $scope.user.newPwd;
        if($scope.user.sendNewPwd){
            dataSave.sendNewPwd = true;
        }
    }



    $http.post(Config.baseUrl + '/users/edit', dataSave)
    .success(function (response) {
      if (response.status == true) {
        $scope.user = {};
        $scope.applyEditUser(response.user);
        $location.path('/');
      } else {
        alert(response.message);
        return;
      }
    });
  };

  $scope.cancelUser = function () {
    $location.path('/');
  };

});