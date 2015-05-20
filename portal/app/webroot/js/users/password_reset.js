angular.module('sp', ['gettext']).
    controller('PasswordResetCtrl', function ($scope) {

        $scope.errorMessageVisible = false;

        $scope.resetPassword = function (event) {
            $scope.errorMessageVisible = true;
            if ($scope.resetPasswordForm.$invalid) {
                event.preventDefault();
                setTimeout(function() {
                    $(event.currentTarget).trigger('reset');
                }, 0)
            }
        }

    });