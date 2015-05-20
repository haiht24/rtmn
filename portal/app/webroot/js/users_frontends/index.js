angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', []);
angular.module('fdb', ['fdb.services', 'fdb.directives', 'fdb.filters']).
controller('UserFrontendCtrl', function($scope, $http, $timeout, $filter, $location) {

//    $scope.deleteUser = function(user){
//        if(confirm('Delete user ' + user.username + ' ?')){
//            $http.post(Config.baseUrl + '/UsersFrontends/delete', {'id' : user.id}).success(function (resp) {
//                if(resp == 'true'){
//                    for(var index = 0; index < $scope.users.length; index++) {
//                        if ($scope.users[index].UsersFrontend.id == user.id) {
//                            $scope.users.splice(index, 1);
//                            break;
//                        }
//                    }
//                }else{
//                    alert('Error. Can not delete this user. View logs for more detail');
//                }
//            })
//        }
//    }

    $scope.lockUser = function(user){
        if(user.status == 'lock'){
            message = 'Unlock this user with email: ';
            status = 'active';
        }else if(user.status == 'active'){
            message = 'Lock this user with email: ';
            status = 'lock';
        }
        if(confirm(message + user.email + '?')){
            $http.post(Config.baseUrl + '/UsersFrontends/edit', {'id' : user.id, 'status' : status}).success(function (resp) {
                console.log(resp);
                if(resp.data){
                    user.status = status;
                }else{
                    alert('Error');
                }
            })
        }
    }
    $scope.search = function (row) {
        if(row.UsersFrontend.username &&
        angular.lowercase(row.UsersFrontend.username).indexOf($scope.query || '') !== -1){
            return true;
        }
        if(row.UsersFrontend.fullname &&
        angular.lowercase(row.UsersFrontend.fullname).indexOf($scope.query || '') !== -1){
            return true;
        }
        if(row.UsersFrontend.email &&
        angular.lowercase(row.UsersFrontend.email).indexOf($scope.query || '') !== -1){
            return true;
        }
        if(row.UsersFrontend.facebook_id &&
        angular.lowercase(row.UsersFrontend.facebook_id).indexOf($scope.query || '') !== -1){
            return true;
        }
        if(row.UsersFrontend.status &&
        angular.lowercase(row.UsersFrontend.status).indexOf($scope.query || '') !== -1){
            return true;
        }
        return false;
    };

    $scope.editUser = function(user){
        $scope.showError = true;
        $scope.editingUser = angular.copy(user);
        if ($scope.editUserForm.$invalid) {
          return;
        }
    }

    $scope.applyEditUser = function(){
        $scope.showError = true;
        console.log($scope.editUserForm);
        if ($scope.editUserForm.$invalid) {
          return;
        }
        var ptNotAllowSpecialCharacter = /[^a-zA-Z0-9]/;
        if (ptNotAllowSpecialCharacter.test($scope.editingUser.username)) {
            alert('Error, username contain special characters');
            return false;
        }

        $http.post(Config.baseUrl + '/UsersFrontends/checkExistEmail', {'email' : $scope.editingUser.email, 'id' : $scope.editingUser.id}).success(function (res) {
            // Not allow edit with existed email
            if(res.check > 0){
                alert('Error!. This email existed');
                return;
            }
        })

        var userData = {};
        userData.id = $scope.editingUser.id;
        userData.fullname = $scope.editingUser.fullname;
        userData.username = $scope.editingUser.username;
        userData.email = $scope.editingUser.email;
        userData.facebook_id = $scope.editingUser.facebook_id;
        userData.status = $scope.editingUser.status;

        $http.post(Config.baseUrl + '/UsersFrontends/edit', userData).success(function (res) {

            if(res.data){
                response = res.data;
                response.UsersFrontend.created = $scope.editingUser.created;
                response.UsersFrontend.modified = $scope.editingUser.modified;

                // remove old user object
                for(var index = 0; index < $scope.users.length; index++) {
                    if ($scope.users[index].UsersFrontend.id == $scope.editingUser.id) {
                        $scope.users.splice(index, 1);
                        break;
                    }
                }
                // add new user object
                $scope.users.push(response);
                angular.element('#cancelEdit').trigger('click');
            }
        })

    }
})