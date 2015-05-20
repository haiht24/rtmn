angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']).
    factory('User', function (mcModel) {
        return mcModel('User');
    }).
    factory('Comment', function (mcModel) {
        return mcModel('Comment');
    }).
    factory('Deal', function (mcModel) {
        return mcModel('Deal');
    })
;
angular.module('fdb.filters', ['mcus.filters']).
    filter('formatDateLocal', function () {
        return function (input) {
            if (input) {
                return moment.utc(input).tz(Config.timeZone).format('ll');
            }
            return input;
        };
    }).
    filter('formatDateTimeLocal', function () {
        return function (input) {
            if (input) {
                return moment.utc(input).tz(Config.timeZone).format('ll LT');
            }
            return input;
        };
    }).
    filter('trusted', ['$sce', function($sce){
        return function(text) {
            return $sce.trustAsHtml(text);
        };
    }]);
angular.module('fdb', ['fdb.services', 'fdb.directives', 'fdb.filters', 'validation.match'])
    .controller('headerCtrl', function ($scope, User, $http, $filter, showFormMessage, $compile) {
        if (Config.user == null) {
            $scope.user = {};
        } else {
            $scope.user = Config.user;
        }

        $scope.runAng = function (res) {
            var data = {};
            data.username = res.email;
            data.email = res.email;
            data.fullname = jQuery('#hdRegFullname').val();
            data.registFrom = jQuery('#registFrom').val();
            if (data.registFrom != 'gg') {
                data.FbID = res.id;
            } else {
                data.FbID = '';
            }
            $http.post(Config.baseUrl + '/users/registFromSocial/', data).success(function (res) {
                if (res.userStatus == 'lock') {
                    alert('Your account temporarily locked');
                }
                location.reload();
            })
        };

        $scope.Register = function () {
            if($scope.frmRegister.username.$error.required || $scope.frmRegister.username.$error.minlength || $scope.frmRegister.username.$error.maxlength){
                $('#username').select();
            }
            else if($scope.frmRegister.email.$error.required || $scope.frmRegister.email.$error.email){
                $('#email').select();
            }
            else if($scope.frmRegister.password.$error.required || $scope.frmRegister.password.$error.minlength || $scope.frmRegister.password.$error.maxlength){
                $('#password').select();
            }
            else if($scope.frmRegister.cfPassword.$error.required){
                $('#cfPassword').select();
            }

            $scope.showError = true;
            $scope.errorMessage = false;

            $('#btnRegister').attr('disabled', 'disabled');
            var username = $scope.regUsername;
            var email = $scope.regEmail;
            var pwd = $scope.regPwd;
            var cfPwd = $scope.regCfPwd;
            if (!username || !email || !pwd || !cfPwd) {
                $('#btnRegister').removeAttr('disabled');
                return false;
            }
            if (pwd != cfPwd) {
                $scope.errorMessage = 'Confirm password not match!';
                $('#btnRegister').removeAttr('disabled');
                return false;
            }
            if (pwd.length < 6) {
                $scope.messValidPassword = 'show';
                $('#btnRegister').removeAttr('disabled');
                return false;
            }
            var ptNotAllowSpecialCharacter = /[^a-zA-Z0-9]/;
            if (ptNotAllowSpecialCharacter.test(username)) {
                alert('Error, your username contain special characters');
                $('#btnRegister').removeAttr('disabled');
                return false;
            }
            if (username.length > 100) {
                alert('Username too long!');
                $('#btnRegister').removeAttr('disabled');
                return false;
            }
            var data = {};
            data.username = username;
            data.email = email;
            data.pwd = pwd;
            data.registFrom = 'form';
            $http.post(Config.baseUrl + '/users/register/', data).success(function (res) {
                if (res.duplicate == 'email') {
                    $scope.errMess = 'This email already exists in system';
                } else if (res.duplicate == 'username') {
                    $scope.errMess = 'This username has already been taken by another user. Please pick another username';
                } else if (res.user) {
                    alert('Success! We will send an email contain active code to your inbox.');
                    location.reload();
                }
                $('#btnRegister').removeAttr('disabled');
            })
        };
        $scope.Login = function () {
            $scope.showError = true;
            var username = $scope.logUsername;
            var password = $scope.logPassword;
            if (!username || !password) {
                return false;
            }
            var data = {};
            data.username = username;
            data.password = password;
            $http.post(Config.baseUrl + '/users/login/', data).success(function (res) {
                if (res.id) {
                    if (res.status == 'active') {
                        location.reload();
                        window.location.replace(Config.baseUrl);
                    } else if (res.status == 'inactive') {
                        $scope.messInactive = 'show';
                    } else if (res.status == 'lock') {
                        alert('Your account temporarily locked');
                        location.reload();
                        window.location.replace(Config.baseUrl);
                    }
                } else {
                    $scope.messLogin = 'show';
                }
            })
        };
        $scope.sendReActiveEmail = function () {
            if (!$scope.emailReActive) {
                return false;
            }
            $('#btnReActiveEmail').attr('disabled', 'disabled');
            var data = {'email': $scope.emailReActive};
            $http.post(Config.baseUrl + '/users/reSendActiveEmail/', data).success(function (res) {
                if (res.data == '"Email not found"') {
                    alert('Your email not found in our system');
                    return false;
                }
                $scope.messResend = 'show';
                $scope.emailReActive = '';
                $('#btnReActiveEmail').removeAttr('disabled');
            })
        };
        $scope.forgotPassword = function () {
            $scope.showError = true;
            $scope.errorMessage = '';
            if($scope.frmForgot.$valid){
                $('#btnForgotPwd').attr('disabled', 'disabled');
                var data = {'email': $scope.emailForgot};
                $http.post(Config.baseUrl + '/users/forgotPassword/', data).success(function (res) {
                    // error
                    if (res.data == '"Your email not found in our system"') {
                        $scope.errorMessage = 'Your email not found in our system';
                        $('#btnForgotPwd').removeAttr('disabled');
                        return false;
                    }
                    // success
                    $scope.messResetPassword = 'show';
                    $scope.emailForgot = '';
                    $('#btnForgotPwd').removeAttr('disabled');
                    $scope.showError = false;
                })
            }
        };
        $scope.resetPassword = function () {
            if (!$scope.rsNewPassword || !$scope.rsReNewPassword) {
                return false;
            }
            if ($scope.rsNewPassword != $scope.rsReNewPassword) {
                $scope.messChange = 'Confirm password not match';
                return false;
            }
            var password = $scope.rsNewPassword;
            if (password.length < 6) {
                $scope.messChange = 'Password must be contain at least 6 characters';
                return false;
            }
            if ($scope.tokenResetPassword == '') {
                $scope.messChange = 'Token ID not found';
                return false;
            }

            var data = {'password': $scope.rsNewPassword, 'token': $scope.tokenResetPassword};
            $http.post(Config.baseUrl + '/users/actionResetPassword/', data).success(function (res) {
                if (res['data'] == '"Token not found"') {
                    $scope.messChange = 'Error Token';
                } else {
                    $scope.messChange = 'Your password has been reset';
                }
            })
        };
        $scope.twLogin = function () {
            var data = {};
            data.oauth_consumer_key = "OqEqJeafRSF11jBMStrZz";
            data.oauth_signature = "POST&https%3A%2F%2Fapi.twitter.com%2F1.1%2F&oauth_consumer_key%3DsVGgOoxjtn4lsfnSkdIJATQTX%26oauth_nonce%3D778d1eee2d3aa5b1b078420939bc5299%26oauth_signature_method%3DHMAC-SHA1%26oauth_timestamp%3D1425627491%26oauth_token%3D2150474772-NVWX1qt1SZEHRLjm1FM6wMbmOly4SkAzg3beXKf%26oauth_version%3D1.0";
            $http.post('https://api.twitter.com/oauth/request_token', data).success(function (res) {
            })
        };

        $('#LoginNow').click(function () {
            $('#sign-up-modal').hide();
        });
        $('#linkToForgotPwd').click(function () {
            $('#sign-up-modal').hide();
        })
    });
