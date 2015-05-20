angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', ['mcus.filters'])
.directive('validCategories', function() {
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
});
angular.module('fdb', ['fdb.services', 'fdb.directives', 'fdb.filters']).
controller('ProductStoreCtrl', function($scope, $http, $filter, $timeout) {
    $scope.mostCouponUrlRegex = /^[0-9a-zA-Z-]+$/;
    $scope.showError = false;
    $scope.itemsPerPage = 20;
    $scope.newStore = {};
    $scope.showListCategories = false;
    $scope.pages = [];
    $scope.filter = '';
    $scope.currentPageInc = 1;
    $scope.currentPage = 0;
    $scope.isExist = false;
    $scope.isExistURL = false;
    $scope.totalStores = 0;
    $scope.numberOfPages = 0;
    $scope.suggestList = [];
    $scope.limitSuggest = 20;
    $scope.checkNameExist = false;
    $scope.checkNotNameExist = false;
    $scope.checkStoreURLExist = false;
    $scope.checkNotStoreURLExist = false;
    $scope.userFilter = '';
    $scope.createdFilter = '';
    $scope.publishFilter = '';
    $scope.listcategories = [];
    //for store draft
    $scope.showStorePopup = false;
    $scope.copyOldStoreItem = null;
    $scope.showStoreDraft = true;
    $scope.currentStoreChanged = false;
    $scope.popupStoreDraft = null;
    $scope.addStoreMode = false;
    
    //for deal draft
    $scope.showDealPopup = false;
    $scope.copyOldDealItem = null;
    $scope.showDealDraft = true;
    $scope.currentDealChanged = false;
    $scope.popupDealDraft = null;
    $scope.addDealMode = false;
    
    //for coupon draft
    $scope.showCouponPopup = false;
    $scope.copyOldCouponItem = null;
    $scope.showCouponDraft = true;
    $scope.currentCouponChanged = false;
    $scope.popupCouponDraft = null;
    $scope.addCouponMode = false;
    
    $scope.filterOptions = {
        textFilter: null,
        sortField: null,
        sortBy: null,
        limit: 20,
        offset: 0
    };
    $scope.incentiveAdd = false;
    
    $scope.initAddNewStore = function() {
        $('#saveStore').text('Add');
        $('#modal-label-add-store').text('Add New Store');
        $scope.checkNameExist = false;
        $scope.checkNotNameExist = false;
        $scope.checkStoreURLExist = false;
        $scope.checkNotStoreURLExist = false;
        $scope.newStore = {};
        $scope.getFromDbStoreDrafts();
        $scope.showError = false;
        $scope.suggestList = [];
        $scope.incentiveAdd = true;
        if (!$scope.newStore.best_store) {
            $scope.newStore.best_store = 0;
        }
        $scope.newStore.custom_keywords = 'Coupon Codes';
        $scope.copyOldStoreItem = angular.copy($scope.newStore);
        $scope.showStoreDraft = true;
        $scope.addStoreMode = true;
        $scope.showStorePopup = true;
    };
    
    $(document).click(function(e) {
        $timeout(function() {
            if ($('.modal-backdrop').length <= 0) {
                $scope.$apply(function(){
                    $scope.incentiveAdd = false;
                    $scope.showStorePopup = false;
                    $scope.showDealPopup = false;
                    $scope.showCouponPopup = false;
                }); 
           }
        }, 500);
        
    });
    
    $scope.getDefaultParams = function() {
        var params = {};
        params['limit'] = $scope.filterOptions.limit;
        params['offset'] = $scope.filterOptions.offset;
        if ($scope.filterOptions.textFilter) {
            params['filter_name'] = $scope.filterOptions.textFilter;
        }
        if ($scope.filterOptions.userFilter) {
            params['user_id'] = $scope.filterOptions.userFilter;
        }
        if ($scope.filterOptions.createdFilter) {
            params['created'] = $scope.filterOptions.createdFilter;
        }
        if ($scope.filterOptions.publishDateFilter) {
            params['publish_date'] = $scope.filterOptions.publishDateFilter;
        }
        if ($scope.filterOptions.sortField) {
            params['sort_field'] = $scope.filterOptions.sortField;
            if ($scope.filterOptions.sortBy) {
                params['sort_by'] = 'ASC';
            } else {
                params['sort_by'] = 'DESC';
            }
        }
        return params;
    };

    $scope.getStores = function() {
        var params = $scope.getDefaultParams();
        $http({method: 'GET', url: Config.baseUrl + '/products/queryStore', params: params}).then(function(response) {
            if (response.data.count > 0) {
                $scope.pages = response.data.stores;
                $scope.totalStores = response.data.count;
                $scope.numberOfPages = Math.ceil($scope.totalStores / $scope.itemsPerPage);
            } else {
                $scope.pages = [];
                $scope.totalStores = 0;
                $scope.numberOfPages = 0;
            }
        }, function(response) {
            throw response;
        });
    };

    $scope.getStores();

    $scope.bindSuggest = function() {
        var params = {};
        params['limit'] = $scope.filterOptions.limit;
        params['offset'] = 0;
        if ($scope.newStore.name && $scope.newStore.name.length > 3) {
            params['filter_name'] = $scope.newStore.name;
            $http({method: 'GET', url: Config.baseUrl + '/products/queryStore', params: params}).then(function(response) {
                if (response.data.count > 0) {
                    $scope.suggestList = response.data.stores;
                } else {
                    $scope.suggestList = [];
                }
            }, function(response) {
                throw response;
            });
        }
    };
    
    $scope.initDefaultBestStore = function() {
        if (!$scope.newStore.best_store) {
            $scope.newStore.best_store = 0;
        }
    };

    $scope.arrayContains = function(container, value) {
        return $.inArray(value, container) != -1;
    };

    $scope.checkboxToArray = function(value) {
        if (!$scope.newStore.categories_id) {
            $scope.newStore.categories_id = [];
        }
        var index = $.inArray(value, $scope.newStore.categories_id);
        if (index == -1) {
            $scope.newStore.categories_id.push(value);
        } else if (index != -1) {
            $scope.newStore.categories_id.splice(index, 1);
        }
    };

    $scope.getBestStore = function(val) {
        if (val && val == 1) {
            return 'YES';
        } else {
            return 'NO';
        }
    };

    $scope.showDropdow = function() {
        $scope.showListCategories = !$scope.showListCategories;
    };

    $scope.change_alias = function( alias )
    {
        var str = alias;
        str= str.toLowerCase(); 
        str= str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a"); 
        str= str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e"); 
        str= str.replace(/ì|í|ị|ỉ|ĩ/g,"i"); 
        str= str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o"); 
        str= str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u"); 
        str= str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y"); 
        str= str.replace(/đ/g,"d"); 
        str= str.replace(/!|`|\$|\\|\,|\{|\}|\||@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g,"-");
        /* tìm và thay thế các kí tự đặc biệt trong chuỗi sang kí tự - */
        str= str.replace(/-+-/g,"-"); //thay thế 2- thành 1-
        str= str.replace(/^\-+|\-+$/g,""); 
        //cắt bỏ ký tự - ở đầu và cuối chuỗi 
        return str;
    };

    $scope.generateMostCoupon = function() {
        var url = '';
        if ($scope.newStore.name) {
            url += $scope.newStore.name.replace(/ /g, '-');
            url += '-coupons';
            url = $scope.change_alias(url);
        } else {
            url = '';
        }
        $scope.newStore.most_coupon_url = url;
    };

    $scope.editMostCoupon = function() {
        if ($scope.newStore.most_coupon_url) {
            $scope.newStore.most_coupon_url = $scope.newStore.most_coupon_url.replace(/ /g, '-');
            $scope.newStore.most_coupon_url = $scope.newStore.most_coupon_url.replace(/&/g, '-');
        }
    };

    $scope.addLocation = function(store) {
        if (!$scope.newStore.related_id) {
            $scope.newStore.related_id = [];
        }
        if (!$scope.newStore.locations) {
            $scope.newStore.locations = [];
        }
        var index = $.inArray(store.store.id, $scope.newStore.related_id);
        if (index == -1) {
            $scope.newStore.related_id.push(store.store.id);
            $scope.newStore.locations.push(store);
        }
    };
    
    $scope.removeLocation = function(store) {
        if (!$scope.newStore.related_id) {
            $scope.newStore.related_id = [];
        }
        if (!$scope.newStore.locations) {
            $scope.newStore.locations = [];
        }
        var index = $.inArray(store.store.id, $scope.newStore.related_id);
        if (index != -1) {
            $scope.newStore.related_id.splice(index, 1);
            $scope.newStore.locations.splice(index, 1);
        }
    };
    
    $scope.showAll = function() {
        $scope.filter = null;
        $scope.search();
    };
    
    $('#search-store').bind('keypress', function(e) {
        if (e.keyCode == 13)
        {
            $scope.$apply(function() {
                $scope.search();
            });
        }
    });
    
    $scope.search = function() {
        $scope.currentPageInc = 1;
        $scope.currentPage = 0;
        $scope.filterOptions.textFilter = $scope.filter;
        $scope.filterOptions.userFilter = $scope.userFilter;
        $scope.filterOptions.createdFilter = $scope.createdFilter;
        $scope.filterOptions.publishDateFilter = $scope.publishFilter;
        $scope.filterOptions.sortField = null;
        $scope.setPage(0);
    };

    $scope.sortBy = function(field) {
        $scope.currentPageInc = 1;
        $scope.currentPage = 0;
        if($scope.filterOptions.sortField && $scope.filterOptions.sortField == field) {
            $scope.filterOptions.sortBy = !$scope.filterOptions.sortBy;
        } else {
            $scope.filterOptions.sortBy = true;
        }
        $scope.filterOptions.sortField = field;
        $scope.setPage(0);
    };

    $scope.range = function(start, end) {
        var ret = [];
        if (!end) {
            end = start;
            start = 0;
        }
        for (var i = start; i < end; i++) {
            ret.push(i);
        }
        return ret;
    };

    $scope.prevPage = function() {
        if ($scope.currentPage > 0) {
            $scope.setPage($scope.currentPage - 1);
        }
    };

    $scope.nextPage = function() {
        if ($scope.currentPage < $scope.numberOfPages - 1) {
            $scope.setPage($scope.currentPage + 1);
        }
    };

    $scope.setPage = function(n) {
        $scope.currentPage = n;
        $scope.currentPageInc = $scope.currentPage + 1;
        $scope.currentPageInc = $scope.currentPageInc > $scope.numberOfPages ? $scope.numberOfPages : $scope.currentPageInc;
        $scope.currentPageInc = $scope.currentPageInc < 1 ? 1 : $scope.currentPageInc;
        $scope.filterOptions.offset = ($scope.currentPage == 0) ? 0 : ($scope.currentPage * $scope.itemsPerPage);
        $scope.getStores();
    };

    $scope.changePage = function() {
        $scope.currentPage = $scope.currentPageInc - 1;
        $scope.currentPage = $scope.currentPage > $scope.numberOfPages - 1 ? $scope.numberOfPages - 1 : $scope.currentPage;
        $scope.currentPage = $scope.currentPage < 0 ? 0 : $scope.currentPage;
        $scope.setPage($scope.currentPage);
    };
    
    $scope.deleteStore = function(id) {
        $http.post(Config.baseUrl + '/products/deleteStore/' + id).success(function(response) {
            if (response.status == true) {
                $scope.setPage(0);
            }
        });
    };
    $scope.setStatusStore = function($id,status) {
        var data = {
            id: $id,
            status: status
        };
        $http.post(Config.baseUrl + '/products/saveStore', data).success(function(response) {
            if (response.status == true) {
                $scope.setPage(0);
            }
        });
    };
    
    $scope.editStore = function(store) {
        $scope.incentiveAdd = true;
        $scope.suggestList = [];
        $('#saveStore').text('Update');
        $('#modal-label-add-store').text('Update Store');
        $scope.checkNameExist = false;
        $scope.checkNotNameExist = false;
        $scope.checkStoreURLExist = false;
        $scope.checkNotStoreURLExist = false;
        $scope.newStore = angular.copy(store);
        $scope.copyOldStoreItem = angular.copy(store);
        $scope.addStoreMode = false;
        $scope.showStorePopup = true;;
    };
    
    
    $scope.checkNameExists = function() {
        $http.post(Config.baseUrl + '/products/checkExistsStore',
                {name: $scope.newStore.name, id: $scope.newStore.id})
                .success(function(response) {
                if (response.existName == true) {
                    $scope.checkNameExist = true;
                    $scope.checkNotNameExist = false;
                } else {
                    $scope.checkNameExist = false;
                    $scope.checkNotNameExist = true;
                }
        });
    };
    $scope.checkStoreURLExists = function() {
        $http.post(Config.baseUrl + '/products/checkExistsStore',
                {most_coupon_url: $scope.newStore.most_coupon_url, id: $scope.newStore.id})
                .success(function(response) {
                if (response.existCouponURl == true) {
                    $scope.checkStoreURLExist = true;
                    $scope.checkNotStoreURLExist = false;
                } else {
                    $scope.checkStoreURLExist = false;
                    $scope.checkNotStoreURLExist = true;
                }
        });
    };
    
    $scope.saveStore = function($status) {
        if ($scope.addStoreForm.$invalid) {
            $scope.showError = true;
            return;
        }
        if ($scope.newStore.id) {
            $scope.newStore.status = $status;
        }
        $http.post(Config.baseUrl + '/products/checkExistsStore',
                {name: $scope.newStore.name, most_coupon_url: $scope.newStore.most_coupon_url, id: $scope.newStore.id})
                .success(function(response) {
            if (response.existName == true || response.existCouponURl == true) {
                if (response.existName == true) {
                    $scope.isExist = true;
                }
                if (response.existCouponURl == true) {
                    $scope.isExistURL = true;
                }
                $scope.showError = true;
                return;
            }
            $scope.isExistURL = false;
            $scope.isExist = false;
            $scope.showError = false;
            $scope.showStorePopup = false;
            if(!$scope.newStore.id) {
                $scope.newStore.status = 'pending';
            }
            $http.post(Config.baseUrl + '/products/saveStore', $scope.newStore).success(function(response) {
                if (response.status == true) {
                     $http({method: 'GET', url: Config.baseUrl + '/products/deleterac', params: {"type": "store"}})
                    .then(function(dataRes) {
                        $scope.newStore = {};
                        $scope.setPage(0);
                        $scope.imgLoading = false;
                        $timeout(function() {
                            $('#saveStore').trigger('reset');
                        });
                        $timeout(function() {
                            $('#cancelStore').click();
                        });
                     }, function(ex) {
                        throw ex;
                        $scope.imgLoading = false;
                        $timeout(function() {
                            $('#saveStore').trigger('reset');
                        });
                        $timeout(function() {
                            $('#cancelStore').click();
                        });
                     });
                } else {
                    $scope.imgLoading = false;
                    $timeout(function() {
                        $('#saveStore').trigger('reset');
                    });
                    $timeout(function() {
                        $('#cancelStore').click();
                    });
                }
            });
        });

    };
    $scope.newDeal = {};
    $scope.stores = [];
    $scope.bindStores = function(category_id) {
        $scope.loadingStores = true;
        var params = {};
        params['fields'] = ['store.id', 'store.name', 'store.categories_id'];
        params['categories_id'] = category_id;
        $http({method: 'GET', url: Config.baseUrl + '/products/queryStore', params: params}).then(function(response) {
            if (response.data.count > 0) {
                $scope.stores = response.data.stores;
                if ($scope.newDeal.store_id) {
                    if ($('.select2-chosen').length > 0) {
                        var selecStore = from($scope.stores).where('$.store.id == "' + $scope.newDeal.store_id + '"').toArray();
                        if (selecStore && selecStore.length > 0) {
                            $('.select2-chosen').text(selecStore[0].store.name);
                        }
                    }
                }
            } else {
                $scope.stores = [];
            }
            $scope.loadingStores = false;
        }, function(response) {
            $scope.loadingStores = false;
            throw response;
        });
    };
    $scope.listcategories = [];
    $scope.addDeal = function(store) {
        $scope.newDeal = {};
        $scope.stores = [];
        if ($('.select2-chosen').length > 0) {
            $('.select2-chosen').text('');
        }
        $scope.newDeal.start_date = $scope.getDefaultDate();
        $scope.newDeal.expire_date = $scope.getDefaultDate();
        $scope.initDefaultDeal();
        $scope.newDeal.category_id = angular.copy(store.store.categories_id[0]);
        $scope.listcategories = angular.copy(store.store.categories);
        $scope.newDeal.store_id = store.store.id;
        $scope.stores[0] = store;
        if ($scope.newDeal.category_id && $scope.listcategories.length > 0) {
            if ($('.select2-chosen').length > 0) {
                $('.select2-chosen').text(store.store.name);
            }
        }
        $scope.addDealMode = true;
        $scope.getFromDbDealDraft();
        $scope.showDealDraft = true;
        $scope.copyOldDealItem = angular.copy($scope.newDeal);
        $scope.showDealPopup = true;
    };
    
    $scope.saveDeal = function() {
        if ($scope.addDealForm.$invalid) {
            $scope.showError = true;
            return;
        }
        $scope.showError = false;
        $scope.showDealPopup = false;
        if (!$scope.newDeal.id) {
            $scope.newDeal.status = 'pending';
        }
        $http.post(Config.baseUrl + '/deals/saveDeal', $scope.newDeal).success(function(response) {
            if (response.status == true) {
                $http({method: 'GET', url: Config.baseUrl + '/products/deleterac', params: {"type": "deal"}})
                .then(function(dataRes) {
                    $scope.newDeal = {};
                    $scope.imgLoading = false;
                    $timeout(function() {
                        $('#saveDeal').trigger('reset');
                    });
                    $timeout(function() {
                        $('#cancelDeal').click();
                    });
                 }, function(ex) {
                    $scope.imgLoading = false;
                    $timeout(function() {
                        $('#saveDeal').trigger('reset');
                    });
                    $timeout(function() {
                        $('#cancelDeal').click();
                    });
                     throw ex;
                 });
            } else {
                $scope.imgLoading = false;
                $timeout(function() {
                    $('#saveDeal').trigger('reset');
                });
                $timeout(function() {
                    $('#cancelDeal').click();
                });
            }
        });
    };
    
    $scope.initAddNewDeals = function() {
        $scope.incentiveAdd = false;
        $scope.incentiveAdd = true;
        $scope.newDeal = {};
        $scope.stores = [];
        if ($('.select2-chosen').length > 0) {
            $('.select2-chosen').text('');
        }
    };
    
    $scope.initDefaultDeal = function() {
        if (!$scope.newDeal.exclusive) {
            $scope.newDeal.exclusive = 0;
        }
        if (!$scope.newDeal.hot_deal) {
            $scope.newDeal.hot_deal = 0;
        }
        if (!$scope.newDeal.free_shipping) {
            $scope.newDeal.free_shipping = 0;
        }
        if (!$scope.newDeal.currency) {
            $scope.newDeal.currency = '$';
        }
    };
    
    $scope.getYesNo = function(val) {
        if (val && val == 1) {
            return 'YES';
        } else {
            return 'NO';
        }
    };
    
    $scope.getDefaultDate = function() {
        return moment().format('YYYY-MM-DD');
    };
    
    ///
    $scope.title_coupons = [];
    $scope.description_coupons = [];
    $scope.keys_title_coupons = [
        {key: "title_store", lable: "Store"},
        {key: "title_category", lable: "Category"},
        {key: "title_event", lable: "Event"},
        {key: "title_top_coupon", lable: "Top Coupon"},
        {key: "title_related_coupon", lable: "Deal (Notable Coupons)"}
    ];
    $scope.keys_description_coupons = [
        {key: "description_store", lable: "Store"},
        {key: "description_category", lable: "Category"},
        {key: "description_event", lable: "Event"},
        {key: "description_top_coupon", lable: "Top Coupon"},
        {key: "description_related_coupon", lable: "Deal (Notable Coupons)"}
    ];
    $scope.newCoupon = {};
    $scope.initTitleDescription = function() {
        $scope.title_coupons = [{key: "title_store", value: ""}];
        $scope.description_coupons = [{key: "description_store", value: ""}];
    };

    $scope.showOptionTitle = function(key, selected) {
        if (!$scope.title_coupons || ($scope.title_coupons.length == 0)) {
            return true;
        }
        var find = false;
        for (var i = 0; i < $scope.title_coupons.length; i++) {
            if ($scope.title_coupons[i].key == key && $scope.title_coupons[i].key != selected) {
                find = true;
                break;
            }
        }
        return !find;
    };

    $scope.showAddTitle = function() {
        return $scope.keys_title_coupons.length > $scope.title_coupons.length;
    };

    $scope.addTitle = function() {
        for (var i = 0; i < $scope.keys_title_coupons.length; i++) {
            var find = false;
            for (var j = 0; j < $scope.title_coupons.length; j++) {
                if ($scope.title_coupons[j].key == $scope.keys_title_coupons[i].key) {
                    find = true;
                    break;
                }
            }
            if (!find) {
                $scope.title_coupons.push({key: $scope.keys_title_coupons[i].key, value: ""});
                break;
            }
        }
    };

    $scope.removeTitle = function(index) {
        if (confirm('Are you sure want to remove this line ?'))
            $scope.title_coupons.splice(index, 1);
    };

    $scope.showOptionDescription = function(key, selected) {
        if (!$scope.description_coupons || ($scope.description_coupons.length == 0)) {
            return true;
        }
        var find = false;
        for (var i = 0; i < $scope.description_coupons.length; i++) {
            if ($scope.description_coupons[i].key == key && $scope.description_coupons[i].key != selected) {
                find = true;
                break;
            }
        }
        return !find;
    };

    $scope.showAddDescription = function() {
        return $scope.keys_description_coupons.length > $scope.description_coupons.length;
    };

    $scope.addDescription = function() {
        for (var i = 0; i < $scope.keys_description_coupons.length; i++) {
            var find = false;
            for (var j = 0; j < $scope.description_coupons.length; j++) {
                if ($scope.description_coupons[j].key == $scope.keys_description_coupons[i].key) {
                    find = true;
                    break;
                }
            }
            if (!find) {
                $scope.description_coupons.push({key: $scope.keys_description_coupons[i].key, value: ""});
                break;
            }
        }
    };

    $scope.removeDescription = function(index) {
        if (confirm('Are you sure want to remove this line ?'))
            $scope.description_coupons.splice(index, 1);
    };
    
    $scope.initDefaultDefault = function() {
        $scope.initTitleDescription();
        $scope.newDeal.expire_date = $scope.getDefaultDate();
        if (!$scope.newCoupon.exclusive) {
            $scope.newCoupon.exclusive = 0;
        }
        if (!$scope.newCoupon.sticky) {
            $scope.newCoupon.sticky = 'hot';
        }
        if (!$scope.newCoupon.coupon_type) {
            $scope.newCoupon.coupon_type = 'Coupon Code';
        }
        if (!$scope.newCoupon.currency) {
            $scope.newCoupon.currency = '$';
        }
        if (!$scope.newCoupon.event) {
            $scope.newCoupon.event = 'Back to School';
        }
    };

    $scope.getExculsive = function(val) {
        if (val && val == 1) {
            return 'YES';
        } else {
            return 'NO';
        }
    };
    
    $scope.addCoupon = function(store) {
        $scope.incentiveAdd = true;
        $scope.newCoupon = {};
        $scope.initDefaultDefault();
        $scope.newCoupon.store_id = store.store.id;
        $scope.newCoupon.storeName = store.store.name;
        $scope.addCouponMode = true;
        $scope.getFromDbCouponDraft();
        $scope.showCouponDraft = true;
        $scope.copyOldCouponItem = angular.copy($scope.newCoupon);
        $scope.showCouponPopup = true;
    };
    
    $scope.saveCoupon = function() {
        if ($scope.addCouponForm.$invalid) {
            $scope.showError = true;
            return;
        }
        $scope.showError = false;
        $scope.showCouponPopup = false;
        for (var j = 0; j < $scope.title_coupons.length; j++) {
            $scope.newCoupon[$scope.title_coupons[j].key] = $scope.title_coupons[j].value;
        }
        for (var j = 0; j < $scope.description_coupons.length; j++) {
            $scope.newCoupon[$scope.description_coupons[j].key] = $scope.description_coupons[j].value;
        }
        if (!$scope.newCoupon.id) {
            $scope.newCoupon.status = 'pending';
        }
        $http.post(Config.baseUrl + '/products/addCoupon', $scope.newCoupon).success(function(response) {
            if (response.status == true) {
                $http({method: 'GET', url: Config.baseUrl + '/products/deleterac', params: {"type": "coupon"}})
                .then(function(dataRes) {
                    $scope.newCoupon = {};
                    $scope.imgLoading = false;
                    $timeout(function() {
                        $('#saveCoupon').trigger('reset');
                    });
                    $timeout(function() {
                        $('#cancelCoupon').click();
                    });
                 }, function(ex) {
                    $scope.imgLoading = false;
                    $timeout(function() {
                        $('#saveCoupon').trigger('reset');
                    });
                    $timeout(function() {
                        $('#cancelCoupon').click();
                    });
                     throw ex;
                 });
            } else {
                $scope.imgLoading = false;
                $timeout(function() {
                    $('#saveCoupon').trigger('reset');
                });
                $timeout(function() {
                    $('#cancelCoupon').click();
                });
            }
            
        });
    };
    
    $('#dateFilter').bind('keypress', function(e) {
        $('#dateFilter').val('');
        $scope.$apply(function() {
            $scope.createdFilter = '';
            $scope.search();
        });
    });
    
    $('#datePublishFilter').bind('keypress', function(e) {
        $('#datePublishFilter').val('');
        $scope.$apply(function() {
            $scope.publishFilter = '';
            $scope.search();
        });
    });
    
    
    $scope.$watch('newStore', function() {
        if ($scope.showStorePopup) {
            if ($scope.copyOldStoreItem && (!angular.equals($scope.copyOldStoreItem, $scope.newStore))) {
                $scope.currentStoreChanged = true;
            } else {
                $scope.currentStoreChanged = false;
            }
        } else {
            $scope.currentStoreChanged = false;
        }
    }, true);
    
    $scope.$watch('newDeal', function() {
        if ($scope.showDealPopup) {
            if ($scope.copyOldDealItem && (!angular.equals($scope.copyOldDealItem, $scope.newDeal))) {
                $scope.currentDealChanged = true;
            } else {
                $scope.currentDealChanged = false;
            }
        } else {
            $scope.currentDealChanged = false;
        }
    }, true);
    
    $scope.$watch('newCoupon', function() {
        if ($scope.showCouponPopup) {
            if ($scope.copyOldCouponItem && (!angular.equals($scope.copyOldCouponItem, $scope.newCoupon))) {
                $scope.currentCouponChanged = true;
            } else {
                $scope.currentCouponChanged = false;
            }
        } else {
            $scope.currentCouponChanged = false;
        }
    }, true);
    
    // if user is logged in save survey as draft every 10 sec
    var timeout = 10000;
    setInterval(function(){
        if ($scope.newStore && $scope.currentStoreChanged) {
            var data = {};
            if ($scope.newStore && $scope.newStore.id) {
                data.left_id = $scope.newStore.id;
            }
            data.type = "store";
            data.content = angular.toJson($scope.newStore);
            $http.post(Config.baseUrl + '/products/addrac', data).success(function(response) {
                $scope.currentStoreChanged = false;
            });
        } else if ($scope.newDeal && $scope.currentDealChanged) {
            var data = {};
            if ($scope.newDeal && $scope.newDeal.id) {
                data.left_id = $scope.newDeal.id;
            }
            data.type = "deal";
            data.content = angular.toJson($scope.newDeal);
            $http.post(Config.baseUrl + '/products/addrac', data).success(function(response) {
                $scope.currentDealChanged = false;
            });
        } else if ($scope.newCoupon && $scope.currentCouponChanged) {
            var data = {};
            if ($scope.newCoupon && $scope.newCoupon.id) {
                data.left_id = $scope.newCoupon.id;
            }
            data.type = "coupon";
            data.content = angular.toJson($scope.newCoupon);
            $http.post(Config.baseUrl + '/products/addrac', data).success(function(response) {
                $scope.currentCouponChanged = false;
            });
        }
    }, timeout);
    
    $scope.getFromDbStoreDrafts = function() {
        //load store drafts
        $http({method: 'GET', url: Config.baseUrl + '/products/queryrac', params: {"type": "store"}}).then(function(response) {
            if (response.data && response.data.draft && response.data.draft.content) {
                $scope.popupStoreDraft = {};
                $scope.popupStoreDraft.draft = angular.fromJson(response.data.draft.content);
                $scope.popupStoreDraft.created = response.data.draft.created;
            } else {
                $scope.popupStoreDraft = null;
            }
        }, function(response) {
            throw response;
        });
    };
    $scope.getFromDbStoreDrafts();
    
    $scope.getFromDbDealDraft = function() {
        //load deal drafts
        $http({method: 'GET', url: Config.baseUrl + '/products/queryrac', params: {"type": "deal"}}).then(function(response) {
            if (response.data && response.data.draft && response.data.draft.content) {
                $scope.popupDealDraft = {};
                $scope.popupDealDraft.draft = angular.fromJson(response.data.draft.content);
                $scope.popupDealDraft.created = response.data.draft.created;
            } else {
                $scope.popupDealDraft = null;
            }
        }, function(response) {
            throw response;
        });
    };
    $scope.getFromDbDealDraft();

    $scope.getFromDbCouponDraft = function() {
        //load coupon drafts
        $http({method: 'GET', url: Config.baseUrl + '/products/queryrac', params: {"type": "coupon"}}).then(function(response) {
            if (response.data && response.data.draft && response.data.draft.content) {
                $scope.popupCouponDraft = {};
                $scope.popupCouponDraft.draft = angular.fromJson(response.data.draft.content);
                $scope.popupCouponDraft.created = response.data.draft.created;
            } else {
                $scope.popupCouponDraft = null;
            }
        }, function(response) {
            throw response;
        });
    };
    $scope.getFromDbCouponDraft();
    // load a draft and overwrites the current survey
    $scope.loadStoreDraft = function() {
        if (confirm('Do you want to load this draft?')) {
            $scope.newStore = angular.copy($scope.popupStoreDraft.draft);
            $scope.suggestList = [];
            $scope.checkNameExist = false;
            $scope.checkNotNameExist = false;
            $scope.checkStoreURLExist = false;
            $scope.checkNotStoreURLExist = false;
        }
        $scope.showStoreDraft = false;
    };
    
    // load a draft and overwrites the current deal
    $scope.loadDealDraft = function() {
        if (confirm('Do you want to load this draft?')) {
            $scope.newDeal = angular.copy($scope.popupDealDraft.draft);
        }
        $scope.showDealDraft = false;
    };
    
    // load a draft and overwrites the current coupon
    $scope.loadCouponDraft = function() {
        if (confirm('Do you want to load this draft?')) {
            $scope.newCoupon = angular.copy($scope.popupCouponDraft.draft);
        }
        $scope.showCouponDraft = false;
    };
});