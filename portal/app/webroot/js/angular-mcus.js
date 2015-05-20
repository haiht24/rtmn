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
                return $http({method: 'POST', url: uri + '.json', data: data}).then(function(response){
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
            

            ;
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
angular.module('mcus.directives', ['placeholderShim'])
.directive('validNumber', function() {
  return {
    require: '?ngModel',
    link: function(scope, element, attrs, ngModelCtrl) {
      if(!ngModelCtrl) {
        return;
      }

      ngModelCtrl.$parsers.push(function(val) {
        var clean = val.replace( /[^0-9]+/g, '');
        if (val !== clean) {
          ngModelCtrl.$setViewValue(clean);
          ngModelCtrl.$render();
        }
        return clean;
      });

      element.bind('keypress', function(event) {
        if(event.keyCode === 32) {
          event.preventDefault();
        }
      });
    }
  };
});
angular.module('brantwills.paging', []).directive('paging', function () {

    // Assign null-able scope values from settings
    function setScopeValues(scope, attrs) {

        scope.List = [];
        scope.Hide = false;
        scope.page = parseInt(scope.page) || 1;
        scope.total = parseInt(scope.total) || 0;
        scope.dots = scope.dots || '...';
        scope.ulClass = scope.ulClass || 'pagination';
        scope.adjacent = parseInt(scope.adjacent) || 2;
        scope.activeClass = scope.activeClass || 'active';
        scope.disabledClass = scope.disabledClass || 'disabled';

        scope.scrollTop = scope.$eval(attrs.scrollTop);
        scope.hideIfEmpty = scope.$eval(attrs.hideIfEmpty);
        scope.showPrevNext = scope.$eval(attrs.showPrevNext);

    }


    // Validate and clean up any scope values
    // This happens after we have set the
    // scope values
    function validateScopeValues(scope, pageCount) {

        // Block where the page is larger than the pageCount
        if (scope.page > pageCount) {
            scope.page = pageCount;
        }

        // Block where the page is less than 0
        if (scope.page <= 0) {
            scope.page = 1;
        }

        // Block where adjacent value is 0 or below
        if (scope.adjacent <= 0) {
            scope.adjacent = 2;
        }

        // Hide from page if we have 1 or less pages
        // if directed to hide empty
        if (pageCount <= 1) {
            scope.Hide = scope.hideIfEmpty;
        }
    }


    // Internal Paging Click Action
    function internalAction(scope, page) {

        // Block clicks we try to load the active page
        if (scope.page == page) {
            return;
        }

        // Update the page in scope
        scope.page = page;

        // Pass our parameters to the paging action
        scope.pagingAction({
            page: scope.page,
            pageSize: scope.pageSize,
            total: scope.total
        });

        // If allowed scroll up to the top of the page
        if (scope.scrollTop) {
            scrollTo(0, 0);
        }
    }


    // Adds the first, previous text if desired
    function addPrev(scope, pageCount) {

        // Ignore if we are not showing
        // or there are no pages to display
        if (!scope.showPrevNext || pageCount < 1) {
            return;
        }

        // Calculate the previous page and if the click actions are allowed
        // blocking and disabling where page <= 0
        var disabled = scope.page - 1 <= 0;
        var prevPage = scope.page - 1 <= 0 ? 1 : scope.page - 1;

        var first = {
            value: '<<',
            title: 'First Page',
            liClass: disabled ? scope.disabledClass : '',
            action: function () {
                if(!disabled) {
                    internalAction(scope, 1);
                }
            }
        };

        var prev = {
            value: '<',
            title: 'Previous Page',
            liClass: disabled ? scope.disabledClass : '',
            action: function () {
                if(!disabled) {
                    internalAction(scope, prevPage);
                }
            }
        };

        scope.List.push(first);
        scope.List.push(prev);
    }


    // Adds the next, last text if desired
    function addNext(scope, pageCount) {

        // Ignore if we are not showing
        // or there are no pages to display
        if (!scope.showPrevNext || pageCount < 1) {
            return;
        }

        // Calculate the next page number and if the click actions are allowed
        // blocking where page is >= pageCount
        var disabled = scope.page + 1 > pageCount;
        var nextPage = scope.page + 1 >= pageCount ? pageCount : scope.page + 1;

        var last = {
            value: '>>',
            title: 'Last Page',
            liClass: disabled ? scope.disabledClass : '',
            action: function () {
                if(!disabled){
                    internalAction(scope, pageCount);
                }
            }
        };

        var next = {
            value: '>',
            title: 'Next Page',
            liClass: disabled ? scope.disabledClass : '',
            action: function () {
                if(!disabled){
                    internalAction(scope, nextPage);
                }
            }
        };

        scope.List.push(next);
        scope.List.push(last);
    }


    // Add Range of Numbers
    function addRange(start, finish, scope) {

        var i = 0;
        for (i = start; i <= finish; i++) {

            var item = {
                value: i,
                title: 'Page ' + i,
                liClass: scope.page == i ? scope.activeClass : '',
                action: function () {
                    internalAction(scope, this.value);
                }
            };

            scope.List.push(item);
        }
    }


    // Add Dots ie: 1 2 [...] 10 11 12 [...] 56 57
    function addDots(scope) {
        scope.List.push({
            value: scope.dots
        });
    }


    // Add First Pages
    function addFirst(scope, next) {
        addRange(1, 2, scope);

        // We ignore dots if the next value is 3
        // ie: 1 2 [...] 3 4 5 becomes just 1 2 3 4 5
        if(next != 3){
            addDots(scope);
        }
    }


    // Add Last Pages
    function addLast(pageCount, scope, prev) {

        // We ignore dots if the previous value is one less that our start range
        // ie: 1 2 3 4 [...] 5 6  becomes just 1 2 3 4 5 6
        if(prev != pageCount - 2){
            addDots(scope);
        }

        addRange(pageCount - 1, pageCount, scope);
    }



    // Main build function
    function build(scope, attrs) {

        // Block divide by 0 and empty page size
        if (!scope.pageSize || scope.pageSize < 0) {
            return;
        }

        // Assign scope values
        setScopeValues(scope, attrs);

        // local variables
        var start,
            size = scope.adjacent * 2,
            pageCount = Math.ceil(scope.total / scope.pageSize);

        // Validate Scope
        validateScopeValues(scope, pageCount);

        // Calculate Counts and display
        addPrev(scope, pageCount);
        if (pageCount < (5 + size)) {

            start = 1;
            addRange(start, pageCount, scope);

        } else {

            var finish;

            if (scope.page <= (1 + size)) {

                start = 1;
                finish = 2 + size + (scope.adjacent - 1);

                addRange(start, finish, scope);
                addLast(pageCount, scope, finish);

            } else if (pageCount - size > scope.page && scope.page > size) {

                start = scope.page - scope.adjacent;
                finish = scope.page + scope.adjacent;

                addFirst(scope, start);
                addRange(start, finish, scope);
                addLast(pageCount, scope, finish);

            } else {

                start = pageCount - (1 + size + (scope.adjacent - 1));
                finish = pageCount;

                addFirst(scope, start);
                addRange(start, finish, scope);

            }
        }
        addNext(scope, pageCount);

    }


    // The actual angular directive return
    return {
        restrict: 'EA',
        scope: {
            page: '=',
            pageSize: '=',
            total: '=',
            dots: '@',
            hideIfEmpty: '@',
            ulClass: '@',
            activeClass: '@',
            disabledClass: '@',
            adjacent: '@',
            scrollTop: '@',
            showPrevNext: '@',
            pagingAction: '&'
        },
        template:
        '<ul ng-hide="Hide" ng-class="ulClass"> ' +
        '<li ' +
        'title="{{Item.title}}" ' +
        'ng-class="Item.liClass" ' +
        'ng-click="Item.action()" ' +
        'ng-repeat="Item in List"> ' +
        '<span ng-bind="Item.value"></span> ' +
        '</li>' +
        '</ul>',
        link: function (scope, element, attrs) {

            // Hook in our watched items
            scope.$watchCollection('[page,pageSize,total]', function () {
                build(scope, attrs);
            });
        }
    };
});
