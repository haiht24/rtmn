angular.module('fdb').controller('UserAddCtrl',
function ($scope, $http, $location) {

  $scope.newUser = {};
  $scope.showError = true;
  $scope.newUser.password = 'MostCoupon@2015!';
  $scope.newUser.password = 'MostCoupon@2015!';

  $scope.initDefaultRole = function () {
    if (!$scope.newUser.role) {
      $scope.newUser.role = 'subscriber';
    }
  };

  $scope.initDefaultRole();

  $scope.addNewUser = function () {
    $scope.showError = true;
    if ($scope.addUserForm.$invalid) {
      return;
    }
    $scope.newUser.first_login = 1;
    $scope.newUser.status = 'active';
    $http.post(Config.baseUrl + '/users/add', $scope.newUser)
    .success(function (response) {
        console.log(response);
      if (response.status == true) {
        $scope.newUser = {};
        $scope.applyAddUser(response.user);
        $location.path('/');
      } else {
        alert(response.message);
        return;
      }
    });
  };

  $scope.cancelUser = function (){
    $location.path('/');
  };

});