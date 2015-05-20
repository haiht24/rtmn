'use strict';

/**
* Directives
*/
angular.module('mcus.directives', ['placeholderShim']).
directive('validUrl', function(){
    return {
        require: 'ngModel',
        link: function(scope, elm, attrs, ctrl) {
            function validator(value) {
                if (value) {
                    // remove http:// or https:// before display it
                    if (value.indexOf('http://') == 0) {
                        value = value.trim().substring(7);
                    } else if (value.indexOf('https://') == 0) {
                        value = value.trim().substring(8);
                    }
                }
                var validUrl = true;
                if (value) {
                    var valueCheck = value;
                    if (valueCheck.indexOf('www.') == 0) {
                        valueCheck = valueCheck.substring(4);
                    }
                    if(valueCheck) {
                        validUrl = valueCheck.match("^[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$");
                    } else {
                        validUrl = false;
                    }
                }
                ctrl.$setValidity('url', validUrl);
                return value;
            };

            ctrl.$parsers.unshift(function(viewValue){
                return validator(viewValue);
            });
            ctrl.$formatters.unshift(function(modelValue){
                return validator(modelValue);
            });
            ctrl.$parsers.push(function(valueFromInput) {
                if (valueFromInput && (valueFromInput.indexOf('http://') < 0) && (valueFromInput.indexOf('https://') < 0)) {
                    return 'http://' + valueFromInput;
                } else {
                    return valueFromInput;
                }
            });
        }
    };
});

/**
* Services
*/

angular.module('mcus.services', []).
config(function($httpProvider) {
  $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}).
factory('Auth',function ($http) {
    return {
        setCredentials: function (username, password) {
            var keyStr = 'ABCDEFGHIJKLMNOP' +
            'QRSTUVWXYZabcdef' +
            'ghijklmnopqrstuv' +
            'wxyz0123456789+/' +
            '=';
            var input = username + ':' + password;
            var output = "";
            var chr1, chr2, chr3 = "";
            var enc1, enc2, enc3, enc4 = "";
            var i = 0;

            do {
                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);

                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;

                if (isNaN(chr2)) {
                    enc3 = enc4 = 64;
                } else if (isNaN(chr3)) {
                    enc4 = 64;
                }

                output = output +
                keyStr.charAt(enc1) +
                keyStr.charAt(enc2) +
                keyStr.charAt(enc3) +
                keyStr.charAt(enc4);
                chr1 = chr2 = chr3 = "";
                enc1 = enc2 = enc3 = enc4 = "";
            } while (i < input.length);


            $http.defaults.headers.common.Authorization = 'Basic ' + output;
        },
        clearCredentials: function () {
            document.execCommand("ClearAuthenticationCache");
            $http.defaults.headers.common.Authorization = 'Basic ';
        }
    };
}).
factory('mcModel', function($http, $q) {
    return function(modelName) {
        //var orModelName = modelName;
        modelName = modelName.toUnderscore();
        var uri = Config.baseUrl + '/proxy/';

        if(modelName === 'category') {
            uri += 'categories';
        } else if(modelName === 'property') {
            uri += 'properties';
        } else if (modelName === 'invoice') {
            uri += 'invoice';
        } else {
            uri += modelName + 's';
        }
        return {
            getUri: function() {
                return uri;
            },
            query: function(params) {
                return $http({method: 'GET', url: uri + '.json', params: params}).then(function(response){
                    return response['data'];
                    }, function(response) {
                        return $q.reject(response);
                });
            },
            get: function(id) {
                return $http({method: 'GET', url: uri + '/' + id + '.json'}).then(function(response){
                    return response['data'][modelName];
                    }, function(response) {
                        return $q.reject(response);
                });
            },
            add: function(data) {
                return $http({method: 'POST', url: uri + '/add', data: data}).then(function (response) {
                    return response['data'][modelName];
                    }, function(response) {
                        return $q.reject(response);
                });
            },
            edit: function(id, data) {
                return $http({method: 'POST', url: uri + '/' + id + '.json', data: data}).then(function(response){
                    return response['data'][modelName] ? response['data'][modelName] : response;
                    }, function(response) {
                        return $q.reject(response);
                });
            },
            request: function(path, data) {
                  return $http({method: 'POST', url: uri + '/' + path + '.json', data: data}).then(function(response){
                    return response['data'][modelName] ? response['data'][modelName] : response;
                    }, function(response) {
                    return $q.reject(response);
                  });
            },
            remove: function(id) {
                return $http({method: 'POST', url: uri + '/delete/' + id + '.json'}).then(function(response){
                    return response['data'];
                    }, function(response) {
                        return $q.reject(response);
                });
            }
        };
    };
}).
factory('showFormMessage', function() {
    return {
        error : function (errorValidation, objMessages, messageDivId) {
            var  validationMessage = '<ul>';
            for (var i = 0 ; i < objMessages.length ; i ++) {
                if (typeof errorValidation[objMessages[i].field] != 'undefined' && errorValidation[objMessages[i].field].$error[objMessages[i].condition]) {
                    validationMessage += '<li>'+objMessages[i].content+ '</li>';
                }
            }
            validationMessage += '</ul>';
            $(messageDivId).show().removeClass('error').removeClass('success').removeClass('message').addClass('error').addClass('message').html(validationMessage);
        },
        success : function (message, messageDivId) {
            $(messageDivId).show().removeClass('error').removeClass('success').addClass('success').html(message);
        },
        notification : function (message, messageDivId) {
            $(messageDivId).show().removeClass('error').removeClass('success').html(message);
        },
        fail : function (message, messageDivId) {
            $(messageDivId).show().removeClass('error').removeClass('success').addClass('error').html(message);
        },
        clear : function (messageDivId) {
            $(messageDivId).html('').hide();
        }
    };
});
angular.module('mcus.filters', [])
    .filter('formatDate', function () {
        return function (input) {
            if (input) {
                var m = moment(input);
                m.add(19, 'hours');
                return moment.utc(m).format('ll');
            }
            return input;
        };
    })
    .filter('formatDateTime', function () {
        return function (input) {
            if (input) {
                return moment.utc(input).format('ll LT');
            }
            return input;
        };
    });
