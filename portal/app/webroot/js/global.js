

/**
* some string helper
*/
String.prototype.toUnderscore = function(){
    return this.replace(/(?!^.?)([A-Z])/g, function($1){return "_" + $1;}).toLowerCase();
};
String.prototype.trim = function(){
    return this.replace(/^\s+|\s+$/g, '');
};

/**
 * some array helper
 */
Array.prototype.clean = function(deleteValue) {
    for ( var i = 0; i < this.length; i++) {
        if (this[i] == deleteValue) {
            this.splice(i, 1);
            i--;
        }
    }
    return this;
};


/**
* some global angular directives
*/
angular.module('ng').
    controller('NotificationCtrl', function ($scope, $rootScope, $timeout) {
        $scope.messages = [];

        function scrollInView() {
            $('#main-panel').animate({scrollTop: 0}, 1000);
        }

        var normalizeMessage = function (data, type) {
            if (angular.isString(data)) {
                data = {content: data};
            }
            data.title = data.title || type.charAt(0).toUpperCase() + type.slice(1);
            data.type = type == 'flash' ? 'info' : type;
            return data;
        }

        if ($scope.flashMessages) {
            var i = 0;
            angular.forEach(['error', 'flash', 'success'], function (flashType) {
                if ($scope.flashMessages[flashType]) {
                    i++;
                    $timeout(function () {
                        var message = $scope.flashMessages[flashType].message;
                        $scope.messages.push(normalizeMessage(message, flashType));
                    }, i * 1000);
                }
            });
        }

        $scope.deleteNotification = function (index) {
            $scope.messages.splice(index, 1)
        }

        angular.forEach(['info', 'error', 'success'], function (messageType) {
            $rootScope.$on(messageType, function (event, data) {
                console.log(data);
                $scope.messages.push(normalizeMessage(data, messageType));
                scrollInView();
            })
        });
    }).
// disables the button after click to prevent multiple clicks
directive('disableOnClick', function(){
    return function(scope, elm, attrs){
        elm.click(function() {
            elm.attr('disabled', 'disabled');
            elm.addClass(attrs.disableOnClick);
        });
    };
}).
directive('validNumberOfChar', function(){
    return {
        require: 'ngModel',
        link: function(scope, elm, attrs, ctrl) {
            function validator(value, count) {              
                var valid = true;
                if (count) {
                    if(value && (!isNaN(count)) && value.length < count) {
                            valid = false;
                   }
                }             
                ctrl.$setValidity('number', valid); 
                return value;                
            };
            ctrl.$parsers.unshift(function(viewValue){//when input change
                return validator(viewValue, scope.$eval(attrs.validNumberOfChar));
            });
            ctrl.$formatters.unshift(function(modelValue){//when load exist value
                return validator(modelValue, scope.$eval(attrs.validNumberOfChar));
            });
            scope.$watch(attrs.validNumberOfChar, function (newValue){
                return validator(scope.$eval(attrs.ngModel), newValue);
            }, true);
        }
    };
}).
directive('patternIf', function () {//check pattern and return value
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, elm, attrs, ctrl) {
            var pattern = scope.$eval(attrs.patternIf);
            if (pattern) {
                var validator = function (viewValue) {
                    ctrl.$setValidity('pattern', !viewValue || (viewValue && viewValue.match(pattern)));
                    return viewValue;
                };
                ctrl.$parsers.push(function (viewValue) {
                    return validator(viewValue);
                });
                ctrl.$formatters.push(function (modelValue) {
                    return validator(modelValue);
                });
            }
        }
    };
}).
directive('validCategories', function() {
    return {
        require: 'ngModel',
        link: function(scope, elm, attrs, ctrl) {
            function validator(value) {
                var emptyCate = true;
                if (!(value && value.length > 0)) {
                    emptyCate = false;
                }
                ctrl.$setValidity('empty-categories', emptyCate);
                return value;
            }
            ;

            ctrl.$parsers.unshift(function(viewValue) {
                return validator(viewValue);
            });
            ctrl.$formatters.unshift(function(modelValue) {
                return validator(modelValue);
            });
            scope.$watch(attrs.ngModel, function(newValue) {
                return validator(newValue);
            }, true);
        }
    };
}).
// disables the button after click to prevent multiple clicks and adds the loading animation
//directive('loadingOnClick', function(){
//    return function(scope, elm, attrs){
//        elm.click(function() {
//            elm.attr('disabled', 'disabled');
//            elm.addClass('loading');
//            elm.append('<span class="loading"><span><i class="icon i-icon"></i>');
//        });
//    };
//}).
        // disables the button after click to prevent multiple clicks and adds the loading animation
directive('loadingOnClick', function(){
    return function(scope, elm, attrs){
        elm.click(function() {
            var loading = true
            if (attrs.loadingOnClick != '') {
                loading = scope.$eval(attrs.loadingOnClick);
            }
            if (loading) {
                elm.attr('disabled', 'disabled');
                elm.addClass('loading');
                elm.append('<span class="loading"><span><i class="icon i-icon"></i>');
            }
            return false;
        });
    };
}).
//This directive sets a type of the input field to date if the browser supports it and appends a datepicker otherwise
directive('datePicker', function(){

    var el = document.createElement('input');
    el.setAttribute('type','date');
    var typeDateSupport = (el.type === "date");

    return {
        require: 'ngModel',
        link: function(scope, elm, attrs, ctrl) {
            if (typeDateSupport) {
                elm.attr("type", "date");
                elm.attr("placeholder", null);
            } else {
                elm.attr("type", "text");
                elm.attr("readonly", "readonly");
                elm.datepicker({
                    dateFormat: 'dd/mm/yy' // TODO: internationalize this
                });
            }
            ctrl.$parsers.unshift(function(value) {
                if (typeDateSupport) {
                    return value
                } else {
                    if (value) {
                        return moment(value, 'DD/MM/YYYY').format('YYYY-MM-DD');
                    } else {
                        return value;
                    }
                }
            });
            ctrl.$formatters.unshift(function(value) {
                if (typeDateSupport) {
                    if (value) {
                        return moment(value).format('YYYY-MM-DD');
                    } else {
                        return value;
                    }
                } else {
                    if (value) {
                        return moment(value).format('DD/MM/YYYY');
                    } else {
                        return value;
                    }
                }
            });
        }
    };
}).
directive('colorPicker', function($parse){

    var el = document.createElement('input');
    el.setAttribute('type','color');
    var typeColorSupport = (el.type === "color");

        // 0 = not loaded
        // 1 = loading first script
        // 2 = loading second script
        // 3 = done
    var minicolorsStatus = 0;
        callbacks = [];
    var loadMinicolor = function(callback) {
        if (minicolorsStatus == 3) {
            callback();
        } else if (minicolorsStatus < 3 && minicolorsStatus > 0) {
            callbacks.push(callback);
        } else { // load minicolors
            callbacks.push(callback);
            minicolorsStatus = 1;
            var script = document.createElement('script');
            script.setAttribute('type', 'text/javascript');
            script.setAttribute('src', Config.baseUrl + '/lib/jquery-minicolors/jquery.minicolors.min.js');
            var css = document.createElement('link');
            css.setAttribute('rel', 'stylesheet')
            css.setAttribute('type', 'text/css')
            css.setAttribute("href", Config.baseUrl + '/lib/jquery-minicolors/jquery.minicolors.css')
            document.getElementsByTagName('head')[0].appendChild(script);
            document.getElementsByTagName('head')[0].appendChild(css);
            var onLoad = function(e) {
                minicolorsStatus++;
                if (minicolorsStatus == 3) {
                    for (var i = 0; i < callbacks.length; i++) {
                        callbacks[i]();
                    }
                }
            };
            script.addEventListener('load', onLoad);
            css.addEventListener('load', onLoad);
        }
    };
    return {
        require: 'ngModel',
        link: function(scope, elm, attrs, ngModel) {

            var settings = angular.extend({sharp: true}, scope.$eval(attrs.colorPicker));

            if (typeColorSupport) {
                elm.attr("type", "color");
            } else {
                elm.attr("type", "hidden");
                loadMinicolor(function() {
                    elm.minicolors();
                    ngModel.$render = function() {
                        elm.minicolors('value', ngModel.$viewValue);
                    }
                });
            }
            ngModel.$formatters.unshift(function(modelValue) {
                if (modelValue) {
                    if (modelValue.match(/^[0-9a-fA-F]{6}$/)) {
                        return '#' + modelValue;
                    }
                    if (modelValue.match(/^#[0-9a-fA-F]{6}$/)) {
                        return modelValue;
                    }
                }
                return null;
            });
            ngModel.$parsers.unshift(function(viewValue) {
                if(settings.sharp){
                    return viewValue;
                } else {
                    return (viewValue) ? viewValue.replace('#','') : viewValue;
                }
            });
        }
    };
}).
directive('confirm', function() {
    return function(scope, iElement, iAttrs) {
        iElement.click(function(){
            var html = '<div id="confirmdelete" class="alert-message" style="display:none;">';
            html += '<div class="alert-content">';
            html += '<p class="message-content">' + iAttrs.confirm + '</p>';
            html += '<p class="button-proceed">';
            html += "<button type='button' class='buttons confirm'>" + __('OK') + "</button>";
            html += '<button style="margin-left: 10px;" type="button" class="buttons cancel">' + __('Cancel') + '</button>';
            html += '</p>';
            html += '</div>';

            var popup = $(html);
            $('body').append(popup);
            popup.find('.confirm').click(function() {
                if(iAttrs.action) {
                    scope.$apply(function(){
                        scope.$eval(iAttrs.action);
                    });
                } else if (iAttrs.href) {
                    window.location = iAttrs.href;
                }
                popup.fadeOut(200, function() {
                    popup.remove();
                });
            });
            popup.find('.cancel').click(function() {
                popup.fadeOut(200, function() {
                    popup.remove();
                });
            });
            
            popup.css('top', ($(window).height() - popup.height()) / 2 + 'px')
                .css('left', ($(window).width() - popup.width())/2+'px')
                .css('opacity',1).fadeIn(200);
            
            return false;
        });
    }
}).
directive('validNumber', function(){
    return {
        require: 'ngModel',
        link: function(scope, elm, attrs, ctrl) {
            function validator(value) {              
                var numberValid = true;                
                if ((value) && (isNaN(value))) {
                        numberValid = false;
               }                             
                ctrl.$setValidity('number', numberValid); 
                return value;                
            };
            ctrl.$parsers.unshift(function(viewValue){//when input change
                return validator(viewValue);
            });
            ctrl.$formatters.unshift(function(modelValue){//when load exist value
                return validator(modelValue);
            });
        }
    };
}).
directive('validNumberFormat', function() {
    return {
        require: 'ngModel',
        link: function(scope, elm, attrs, ctrl) {
            function validator(value) {
                var numberValid = true;
                var numberRegrex = /(?:^\d{1,3}(?:\.?\d{3})*(?:,\d{2})?$)|(?:^\d{1,3}(?:,?\d{3})*(?:\.\d{2})?$)/;
                if (value) {
                    numberValid = numberRegrex.test(value);
                }
                ctrl.$setValidity('number-format', numberValid);
                if (numberValid) {
                    return value;
                } else {
                    return null;
                }
            };
            ctrl.$parsers.unshift(function(viewValue) {//when input change
                return validator(viewValue);
            });
            ctrl.$formatters.unshift(function(modelValue) {//when load exist value
                return validator(modelValue);
            });
        }
    };
}).
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
                    if (valueCheck) {
                        validUrl = valueCheck.match("^[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$");
                    } else {
                        validUrl = false;
                    }
                }
                ctrl.$setValidity('url', validUrl);
                return value;
            };

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
})
.filter('startFrom', function() {
    return function(input, start) {
        
        if(input!==undefined){
            start = +start; //parse to int
            return input.slice(start);
        }
         
    }
})
.filter('sanitizeLink', function(){
    return function(input) {
        if(input) {
            if (input.indexOf('http')) {
                return 'http://'+input;
            }    
            else return input;         
        }
        else return input;
    };
})
.filter('trustAsHtml', function($sce){
    return function(input) {
        // TODO: sanitize input, maybe we should include ng-sanitize
        return $sce.trustAsHtml(input);
    };
})
.filter('stripHtmlTags', function() {
    return function(text) {
        return String(text).replace(/<[^>]+>/gm, '');
    };
})
.filter('truncate', function () {
    return function (text, length, end) {
        if (isNaN(length))
            length = 10;

        if (end === undefined)
            end = "...";

        if (text.length <= length || text.length - end.length <= length) {
            return text;
        }
        else {
            return String(text).substring(0, length-end.length) + end;
        }
    };
})
// add some usefull stuff to the rootScope
.run(function($rootScope, $location){
    // routing
    $rootScope.history = {
        queue: [],
        _isBack: false,
        _isFirst: true,
        back: function() {
            $rootScope.history._isBack = true;
            $rootScope.history.queue.pop();
            var path = $rootScope.history.queue.pop();
            if (path) {
                $location.url(path);
            } else {
                $location.url('/');
            }
        }
    }
    $rootScope.$on('$locationChangeSuccess', function(){
        if ($rootScope.history._isBack) {
            $rootScope.history.direction = 'back';
            $rootScope.history._isBack = false;
        } else {
            $rootScope.history.direction = 'forward';
        }
        if ($rootScope.history._isFirst) {
            $rootScope.animationClass = null
            $rootScope.history.direction = 'first';
            $rootScope.history._isFirst = false;
        }
        $rootScope.history.queue.push($location.url());
    });

});

//check for existing console object and create it if not existing
//used for internet explorer which does not create this object automatically
if (!window.console) { 
    window.console = {
        log: function(obj){}
    };
}          
function shareOnFacebook(options) {
    
    options = options || {};
    options.forceRedirect = options.forceRedirect || false;
    options.link = options.link || location.href;
    options.redirect = options.redirect || (/feedbackstr\.com/i.test(location.href) ? location.href : 'http://www.feedbackstr.com');
    options.picture = options.picture || 'http://www.feedbackstr.com/img/acp/logo.png';
    options.name = options.name || 'Feedbackstr';
    options.caption = options.caption || 'Verbessern Sie die Beziehungen zur Ihren Kunden durch direktes Feedback!';
    options.description = options.description || 'Feedbackstr';

    if( options.forceRedirect || /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        var fbUrl = 'https://www.facebook.com/dialog/feed?' + 
          'app_id=' + options.fbId + '&' +
          'link=' + encodeURI(options.link) + '&' +
          'picture=' + encodeURI(options.picture) + '&' +
          'name=' + encodeURI(options.name) + '&' +
          'caption=' + encodeURI(options.caption) + '&' +
          'description=' + encodeURI(options.description) + '&' +
          'redirect_uri=' + encodeURI(options.redirect);
        window.location.assign(fbUrl);
    }else{
        FB.ui({
            method: 'feed',
            //redirect_uri: 'http://www.feedbackstr.com/js-close.html',
            link: options.link,
            picture: options.picture,
            name: options.name,
            caption: options.caption,
            description: options.description,
          }, function(response){
              //alert("response");
          });
    }
}
