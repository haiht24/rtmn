angular.module('fdb').controller('UserListCtrl', function ($scope, $http, $timeout, $filter) {
  $scope.searchMatch = function (haystack, needle) {
    if (!needle) {
      return true;
    }
    if (!haystack) {
      return false;
    }
    return haystack.toLowerCase().indexOf(needle.toLowerCase()) !== -1;
  };

  $scope.search = function () {
    for (var key in $scope.roles) {
      if ($scope.roles.hasOwnProperty(key)) {
        $scope.roles[key].currentPageInc = 1;
        $scope.roles[key].currentPage = 0;
        if ($scope.roles[key].users.length > 0) {
          var filteredItems = $filter('filter')($scope.roles[key].users, function (item) {
            if ($scope.searchMatch(item.user.fullname, $scope.filter))
              return true;
            if ($scope.searchMatch(item.user.email, $scope.filter))
              return true;
            if ($scope.searchMatch(item.user.phone, $scope.filter))
              return true;
            if ($scope.searchMatch(item.user.skype, $scope.filter))
              return true;
            return false;
          });
          var pagedItems = [];
          for (var i = 0; i < filteredItems.length; i++) {
            if (i % $scope.itemsPerPage === 0) {
              pagedItems[Math.floor(i / $scope.itemsPerPage)] = [filteredItems[i]];
            } else {
              pagedItems[Math.floor(i / $scope.itemsPerPage)].push(filteredItems[i]);
            }
          }
          $scope.roles[key].pages = pagedItems;
        }
      }
    }
  };

  $scope.range = function (start, end) {
    var ret = [];
    if (!end) {
      end = start;
      start = 0;
    }
    for (var i = start; i < end; i++) {
      ret.push(i);
    }
    return ret;
  };

  $scope.prevPage = function (role) {
    if (role.currentPage > 0) {
      $scope.setPage(role, role.currentPage - 1);
    }
  };

  $scope.nextPage = function (role) {
    if (role.currentPage < role.pages.length - 1) {
      $scope.setPage(role, role.currentPage + 1);
    }
  };

  $scope.setPage = function (role, n) {
    role.currentPage = n;
    role.currentPageInc = role.currentPage + 1;
    role.currentPageInc = role.currentPageInc > role.pages.length ? role.pages.length : role.currentPageInc;
    role.currentPageInc = role.currentPageInc < 1 ? 1 : role.currentPageInc;
  };

  $scope.changePage = function (role) {
    role.currentPage = role.currentPageInc - 1;
    role.currentPage = role.currentPage > role.pages.length - 1 ? role.pages.length - 1 : role.currentPage;
    $scope.currentPage = $scope.currentPage < 0 ? 0 : $scope.currentPage;
    $scope.setPage(role, role.currentPage);
  };
});