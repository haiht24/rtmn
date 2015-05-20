function LandingIndexCtrl($scope, User, $http, $filter, showFormMessage, $compile) {
   $scope.showUser = Config.user;
   $scope.atSubmit = false;
   
   $scope.doShowRegister = function(event, showUser) {
       if ($scope.atSubmit) {
           event.preventDefault();
           return;
       }
       if ($scope.registerShowForm.$invalid) {
            event.preventDefault();
            showFormMessage.error($scope.registerShowForm, Config.message, '#showFormError');
            $('html, body').animate({scrollTop: 0}, 'slow');
            $scope.atSubmit = false;
            return;
       }
       $scope.atSubmit = true;
       $('#registerShowForm').submit();       
   }
}