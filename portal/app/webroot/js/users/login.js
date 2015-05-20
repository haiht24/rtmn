angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', []);
angular.module('fdb', ['fdb.services','fdb.directives', 'fdb.filters']).
controller('LoginCtrl',  function($scope) {
    $scope.emailRegex = /^[a-z0-9!#$%&'*+/=?^_`{|}~.-]+@[a-z0-9-]+(\.[a-z0-9-]+)*$/i;
    $scope.user = {
        email : '',
        password : ''
    };
    $scope.errorMessageVisible = false;
    $scope.doLogin = function(event) {
        $scope.errorMessageVisible = true;
        //$('form[name=userLoginForm] input').checkAndTriggerAutoFillEvent();
        if ($scope.userLoginForm.$invalid) {
            event.preventDefault();
            setTimeout(function() {
                $(event.currentTarget).trigger('reset');
            }, 0);
        }
    };
});