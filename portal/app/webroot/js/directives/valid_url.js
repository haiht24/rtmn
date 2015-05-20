angular.module('fdb.directives').directive('validUrl', function(){
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
                    if (valueCheck) {
                        validUrl = valueCheck.match("^[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$");
                    } else {
                        validUrl = false;
                    }
                }
                ctrl.$setValidity('url', validUrl);
                return value;
            }
            ;

            ctrl.$parsers.unshift(function(viewValue) {
                return validator(viewValue);
            });
            ctrl.$formatters.unshift(function(modelValue) {
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