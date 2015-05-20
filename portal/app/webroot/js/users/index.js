angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', []);
angular.module('fdb', ['fdb.services','fdb.directives', 'fdb.filters', 'ngAnimate', 'ngRoute']).
config(function($routeProvider) {
  var getView = function(name) {
    return Config.baseUrl +'/templates/users/index/' + name;
  };
  $routeProvider.when('/edit', {
    templateUrl: getView('edit'),
    controller: 'UserEditCtrl'
  }).when('/add', {
    templateUrl: getView('add'),
    controller: 'UserAddCtrl'
  }).otherwise({
    templateUrl: getView('list'),
    controller: 'UserListCtrl'
  });
}).
controller('UserCtrl', function($scope, $location, $http) {

  $scope.emailRegex = /^[a-z0-9!#$%&'*+/=?^_`{|}~.-]+@[a-z0-9-]+(\.[a-z0-9-]+)*$/i;
  $scope.busy = false;
  $scope.dataLoaded = false;
  $scope.roles = {};
    $scope.roles['administrator'] = {
        currentPageInc : 1,
        currentPage : 0,
        users: [],
        pages: []
    };
    $scope.roles['publisher'] = {
        currentPageInc : 1,
        currentPage : 0,
        users: [],
        pages: []
    };;
    $scope.roles['editor'] = {
        currentPageInc : 1,
        currentPage : 0,
        users: [],
        pages: []
    };
    $scope.roles['subscriber'] = {
        currentPageInc : 1,
        currentPage : 0,
        users: [],
        pages: []
    };
    $scope.itemsPerPage = 20;
    $scope.edittingUser = null;
    $scope.edittingCurrentPage = 0;
    $scope.edittingCurrentIndex = 0;

    $scope.setBusy = function(value) {
      $scope.busy = value;
    };

    $scope.loadUsers = function() {
      $scope.busy = true;
      for (var index in $scope.users) {
        if($scope.users.hasOwnProperty(index)) {
            $scope.roles[$scope.users[index].user.role].users.push($scope.users[index]);
        }
      }
      for (var key in $scope.roles) {
        if($scope.roles.hasOwnProperty(key)) {
            if ($scope.roles[key].users.length > 0) {
                var pagedItems = [];
                for (var i = 0; i < $scope.roles[key].users.length; i++) {
                    if (i % $scope.itemsPerPage === 0) {
                        pagedItems[Math.floor(i / $scope.itemsPerPage)] = [$scope.roles[key].users[i]];
                    } else {
                        pagedItems[Math.floor(i / $scope.itemsPerPage)].push($scope.roles[key].users[i]);
                    }
                }
                $scope.roles[key].pages = pagedItems;
            }
        }
      }
      $scope.dataLoaded = true;
    };

    $scope.loadUsers();

    $scope.applyAddUser = function(user) {
      if (user) {
        $scope.roles[user.user.role].users.push(user);
        if (($scope.roles[user.user.role].users.length - 1) % $scope.itemsPerPage === 0) {
          $scope.roles[user.user.role].pages[Math.floor(($scope.roles[user.user.role].users.length - 1) / $scope.itemsPerPage)] = [user];
        } else {
          $scope.roles[user.user.role].pages[Math.floor(($scope.roles[user.user.role].users.length - 1) / $scope.itemsPerPage)].push(user);
        }
      }
    };

    $scope.applyEditUser = function(user) {
      if (user) {
        if ($scope.edittingUser.role == user.user.role) {
          $scope.roles[user.user.role].pages[$scope.edittingCurrentPage][$scope.edittingCurrentIndex] = angular.copy(user);
        } else {
          //delete old list
          $scope.deleteUser($scope.edittingUser, 'editMode');
          //add other list
          $scope.roles[user.user.role].users.push(user);
          if (($scope.roles[user.user.role].users.length - 1) % $scope.itemsPerPage === 0) {
            $scope.roles[user.user.role].pages[Math.floor(($scope.roles[user.user.role].users.length - 1) / $scope.itemsPerPage)] = [user];
          } else {
            $scope.roles[user.user.role].pages[Math.floor(($scope.roles[user.user.role].users.length - 1) / $scope.itemsPerPage)].push(user);
          }
        }
      }
    };

    $scope.editUser = function(user, currentPage, index) {
      $scope.edittingUser = user;
      $scope.edittingCurrentPage = currentPage;
      $scope.edittingCurrentIndex = index;
      $location.path('/edit');
    };

    $scope.deleteUser = function(user, mode) {
        if(mode != 'editMode'){
            if(confirm('Are you sure want to delete this user ?')){

            }else{
                return;
            }
        }
        $http.post(Config.baseUrl + '/users/delete/', {'id' : user.id, 'mode' : mode}).success(function(response) {
            if(response == 'true'){
                  for (var index = 0; index < $scope.roles[user.role].users.length; index++) {
                      if ($scope.roles[user.role].users[index].user.id == user.id) {
                          $scope.roles[user.role].users.splice(index, 1);
                          break;
                      }
                  }
                  var pagedItems = [];
                  for (var i = 0; i < $scope.roles[user.role].users.length; i++) {
                      if (i % $scope.itemsPerPage === 0) {
                          pagedItems[Math.floor(i / $scope.itemsPerPage)] = [$scope.roles[user.role].users[i]];
                      } else {
                          pagedItems[Math.floor(i / $scope.itemsPerPage)].push($scope.roles[user.role].users[i]);
                      }
                  }
                  $scope.roles[user.role].pages = pagedItems;
                  if ($scope.roles[user.role].pages.length < $scope.roles[user.role].currentPage - 1) {
                      $scope.roles[user.role].currentPage = $scope.roles[user.role].pages.length - 1;
                  }
              }else{
                alert('Can not delete this user. User ID not exist');
              }
        })
    };

});