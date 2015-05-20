angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', []);
angular.module('fdb', ['fdb.services','fdb.directives', 'fdb.filters']).
controller('ActiveAccountCtrl',  function($scope, $http) {
    $('#left-panel').remove();
    $('#ribbon').remove();

    $scope.activeNewAccount = function(){
        $('#btnActiveNewAcc').attr('disabled', 'disabled');
        if(!$scope.user.oldPassword || !$scope.user.newPassword || !$scope.user.confirmNewPassword){
            $('#btnActiveNewAcc').removeAttr('disabled');
            return;
        }
        if(($scope.user.confirmNewPassword != $scope.user.newPassword)){
            $scope.message = 'Confirm password not match';
            $('#btnActiveNewAcc').removeAttr('disabled');
            return;
        }
        if($scope.user.newPassword.length < 6){
            $scope.message = 'Your password too short. Require minimum 6 characters';
            $('#btnActiveNewAcc').removeAttr('disabled');
            return;
        }
        if($scope.user.newPassword == $scope.user.oldPassword){
            $scope.message = 'Please create new password difference with old';
            $('#btnActiveNewAcc').removeAttr('disabled');
            return;
        }
        var data = {};
        data.first_login = 0;
        data.id = $scope.user.id;
        data.password = $scope.user.newPassword;
        data.oldPassword = $scope.user.oldPassword;

        $http.post(Config.baseUrl + '/users/doActiveAccount', data)
        .success(function (response) {
            if(response != 'false'){
                $scope.message = 'Change password success';
                $scope.user = {};
            }else{
                $scope.message = 'Invalid old password or this account already active before';
                $scope.user = {};
            }
            $('#btnActiveNewAcc').removeAttr('disabled');
        })
    }
});