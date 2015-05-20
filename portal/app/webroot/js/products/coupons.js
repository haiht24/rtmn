angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', ['mcus.filters']);
angular.module('fdb', ['fdb.services', 'fdb.directives', 'fdb.filters']).
controller('ProductCouponCtrl', function($scope, $http, $filter, $timeout) {
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
    $scope.showError = false;
    $scope.itemsPerPage = 20;
    $scope.newCoupon = {};
    $scope.pages = [];
    $scope.filter = '';
    $scope.currentPageInc = 1;
    $scope.currentPage = 0;
    $scope.showListCategories = false;
    $scope.totalCoupons = 0;
    $scope.numberOfPages = 0;
    $scope.loadingStores = false;
    $scope.userFilter = '';
    $scope.createdFilter = '';
    $scope.publishFilter = '';
    $scope.showPopup = false;
    $scope.copyOldItem = null;
    $scope.currentChanged = false;
    $scope.filterOptions = {
        textFilter: null,
        sortField: null,
        sortBy: null,
        limit: 20,
        offset: 0
    };
    $scope.incentiveAdd = false;
    
    $(document).click(function(e) {
        $timeout(function() {
            if ($('.modal-backdrop').length <= 0) {
                $scope.$apply(function(){
                    $scope.incentiveAdd = false;
                    $scope.showPopup = false;
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

    $scope.getCoupons = function() {
        var params = $scope.getDefaultParams();
        $http({method: 'GET', url: Config.baseUrl + '/products/queryCoupon', params: params}).then(function(response) {
            if (response.data.count > 0) {
                $scope.pages = response.data.coupons;
                $scope.totalCoupons = response.data.count;
                $scope.numberOfPages = Math.ceil($scope.totalCoupons / $scope.itemsPerPage);
            } else {
                $scope.pages = [];
                $scope.totalCoupons = 0;
                $scope.numberOfPages = 0;
            }
        }, function(response) {
            throw response;
        });
    };

    $scope.getCoupons();

    $scope.searchMatch = function(haystack, needle) {
        if (!needle) {
            return true;
        }
        if (!haystack) {
            return false;
        }
        return haystack.toLowerCase().indexOf(needle.toLowerCase()) !== -1;
    };

    $('#search-coupon').bind('keypress', function(e) {
        if (e.keyCode == 13)
        {
            $scope.$apply(function() {
                $scope.search();
            });
        }
    });
    
    $scope.showAll = function() {
        $scope.filter = null;
        $scope.search();
    };

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
        $scope.getCoupons();
    };

    $scope.changePage = function() {
        $scope.currentPage = $scope.currentPageInc - 1;
        $scope.currentPage = $scope.currentPage > $scope.numberOfPages - 1 ? $scope.numberOfPages - 1 : $scope.currentPage;
        $scope.currentPage = $scope.currentPage < 0 ? 0 : $scope.currentPage;
        $scope.setPage($scope.currentPage);
    };

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

    $scope.bindStores = function(category_id) {
        $scope.loadingStores = true;
        var params = {};
        params['fields'] = ['store.id', 'store.name', 'store.categories_id'];
        params['categories_id'] = category_id;
        $http({method: 'GET', url: Config.baseUrl + '/products/queryStore', params: params}).then(function(response) {
            if (response.data.count > 0) {
                $scope.stores = response.data.stores;
                if ($scope.newCoupon.store_id) {
                    if ($('.select2-chosen').length > 0) {
                        var selecStore = from($scope.stores).where('$.store.id == "' + $scope.newCoupon.store_id + '"').toArray();
                        if (selecStore && selecStore.length > 0) {
                            $('.select2-chosen').text(selecStore[0].store.name);
                        } else {
                            $('.select2-chosen').text('');
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
    
    $scope.initDefaultDefault = function() {
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
        if (!$scope.newCoupon.category_id && $scope.categories.length > 0) {
            $scope.newCoupon.category_id = $scope.categories[0].category.id;
            $scope.bindStores($scope.newCoupon.category_id);
        }

    };

    $scope.getExculsive = function(val) {
        if (val && val == 1) {
            return 'YES';
        } else {
            return 'NO';
        }
    };

    $scope.deleteCoupon = function(id) {
        $http.post(Config.baseUrl + '/products/deleteCoupon/' + id).success(function(response) {
            if (response.status == true) {
                $scope.setPage(0);
            }
        });
    };
    $scope.setStatusCoupon = function($id, status) {
        var data = {
            id: $id,
            status: status
        };
        $http.post(Config.baseUrl + '/products/addCoupon', data).success(function(response) {
            if (response.status == true) {
                $scope.setPage(0);
            }
        });
    };
    
    $scope.editCoupon = function(coupon) {
        $scope.showPopup = true;
        $scope.copyOldItem = angular.copy(coupon);
        $scope.incentiveAdd = true;
        $scope.newCoupon = angular.copy(coupon);
        $scope.title_coupons = [];
        $scope.description_coupons = [];
        if ($scope.newCoupon.title_store) {
            $scope.title_coupons.push({key: "title_store", value: $scope.newCoupon.title_store});
        }
        if ($scope.newCoupon.title_category) {
            $scope.title_coupons.push({key: "title_category", value: $scope.newCoupon.title_category});
        }
        if ($scope.newCoupon.title_event) {
            $scope.title_coupons.push({key: "title_event", value: $scope.newCoupon.title_event});
        }
        if ($scope.newCoupon.title_top_coupon) {
            $scope.title_coupons.push({key: "title_top_coupon", value: $scope.newCoupon.title_top_coupon});
        }
        if ($scope.newCoupon.title_related_coupon) {
            $scope.title_coupons.push({key: "title_related_coupon", value: $scope.newCoupon.title_related_coupon});
        }
        if ($scope.newCoupon.description_store) {
            $scope.description_coupons.push({key: "description_store", value: $scope.newCoupon.description_store});
        }
        if ($scope.newCoupon.description_category) {
            $scope.description_coupons.push({key: "description_category", value: $scope.newCoupon.description_category});
        }
        if ($scope.newCoupon.description_event) {
            $scope.description_coupons.push({key: "description_event", value: $scope.newCoupon.description_event});
        }
        if ($scope.newCoupon.description_top_coupon) {
            $scope.description_coupons.push({key: "description_top_coupon", value: $scope.newCoupon.description_top_coupon});
        }
        if ($scope.newCoupon.description_related_coupon) {
            $scope.description_coupons.push({key: "description_related_coupon", value: $scope.newCoupon.description_related_coupon});
        }
        $scope.stores = [];
        $scope.loadingStores = true;
        var params = {};
        params['id'] = coupon.store_id;
        $http({method: 'GET', url: Config.baseUrl + '/products/queryStore', params: params}).then(function(response) {
            if (response.data.count > 0) {
                if (response.data.stores[0]['store']['categories_id'].length > 0) {
                    $scope.newCoupon.category_id = response.data.stores[0]['store']['categories_id'][0];
                    if ($scope.newCoupon.category_id && $scope.categories.length > 0) {
                        $scope.bindStores($scope.newCoupon.category_id);
                    }
                }
            }
        }, function(response) {
            throw response;
        });
    };
    
    $scope.saveCoupon = function($status) {
        if ($scope.addCouponForm.$invalid) {
            $scope.showError = true;
            return;
        }
        if ($scope.newCoupon.id) {
            $scope.newCoupon.status = $status;
        }
        $scope.showError = false;
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
            $scope.imgLoading = false;
            $timeout(function() {
                $('#saveCoupon').trigger('reset');
            });
            if (response.status == true) {
                $scope.newCoupon = {};
                $scope.setPage(0);
            }
            $timeout(function() {
                $('#cancelCoupon').click();
            });
        });
    };
    
    $scope.arrayContains = function(value, container) {
        return $.inArray(value, container) != -1;
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
    
    $scope.$watch('newCoupon', function() {
        if ($scope.showPopup) {
            if ($scope.copyOldItem && (!angular.equals($scope.copyOldItem, $scope.newCoupon))) {
                $scope.currentChanged = true;
            } else {
                $scope.currentChanged = false;
            }
        } else {
            $scope.currentChanged = false;
        }
    }, true);
    
    // if user is logged in save coupon as draft every 10 sec
    var timeout = 10000;
    setInterval(function(){
        if ($scope.newCoupon && $scope.currentChanged) {
            var data = {};
            if ($scope.newCoupon && $scope.newCoupon.id) {
                data.left_id = $scope.newCoupon.id;
            }
            data.type = "coupon";
            data.content = angular.toJson($scope.newCoupon);
            $http.post(Config.baseUrl + '/products/addrac', data).success(function(response) {
                $scope.currentChanged = false;
            });
        }
    }, timeout);
});