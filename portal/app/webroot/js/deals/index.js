angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', ['mcus.filters'])
.filter('formatDate', function() {
    return function(input) {
        if (input) {
            return moment.utc(input).format('L');
        }
        return input;
    };
});
angular.module('fdb', ['fdb.services', 'fdb.directives', 'fdb.filters']).
controller('DealsCtrl', function($scope, $http, $filter, $timeout) {
    $scope.showError = false;
    $scope.itemsPerPage = 20;
    $scope.newDeal = {};
    $scope.pages = [];
    $scope.filter = '';
    $scope.currentPageInc = 1;
    $scope.currentPage = 0;
    $scope.showListCategories = false;
    $scope.totalDeals = 0;
    $scope.numberOfPages = 0;
    $scope.loadingStores = false;
    $scope.userFilter = '';
    $scope.createdFilter = '';
    $scope.publishFilter = '';
    $scope.showPopup = false;
    $scope.copyOldItem = null;
    $scope.showDraft = true;
    $scope.currentChanged = false;
    $scope.popupDraft = null;
    $scope.addMode = false;
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
    $scope.initAddNewDeals = function() {
        $scope.incentiveAdd = true;
        $scope.addMode = true;
        $('#saveDeal').text('Add');
        $scope.newDeal = {};
        $scope.stores = [];
        if ($('.select2-chosen').length > 0) {
            $('.select2-chosen').text('');
        }
        $scope.newDeal.start_date = $scope.getDefaultDate();
        $scope.newDeal.expire_date = $scope.getDefaultDate();
        $scope.initDefaultDefault();
        $scope.getFromDbDealDraft();
        $scope.showDraft = true;
        $scope.copyOldItem = angular.copy($scope.newDeal);
        $scope.showPopup = true;
    };
    
    $scope.initDefaultDefault = function() {
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
        if (!$scope.newDeal.category_id && $scope.categories.length > 0) {
            $scope.newDeal.category_id = $scope.categories[0].category.id;
            $scope.bindStores($scope.newDeal.category_id);
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

    $scope.getDeals = function() {
        var params = $scope.getDefaultParams();
        $http({method: 'GET', url: Config.baseUrl + '/deals/queryDeal', params: params}).then(function(response) {
            if (response.data.count > 0) {
                $scope.pages = response.data.deals;
                $scope.totalDeals = response.data.count;
                $scope.numberOfPages = Math.ceil($scope.totalDeals / $scope.itemsPerPage);
            } else {
                $scope.pages = [];
                $scope.totalDeals = 0;
                $scope.numberOfPages = 0;
            }
        }, function(response) {
            throw response;
        });
    };

    $scope.getDeals();

    $('#search-deal').bind('keypress', function(e) {
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
        $scope.getDeals();
    };

    $scope.changePage = function() {
        $scope.currentPage = $scope.currentPageInc - 1;
        $scope.currentPage = $scope.currentPage > $scope.numberOfPages - 1 ? $scope.numberOfPages - 1 : $scope.currentPage;
        $scope.currentPage = $scope.currentPage < 0 ? 0 : $scope.currentPage;
        $scope.setPage($scope.currentPage);
    };

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

    $scope.deleteDeal = function(id) {
        $http.post(Config.baseUrl + '/deals/deleteDeal/' + id).success(function(response) {
            if (response.status == true) {
                $scope.setPage(0);
            }
        });
    };

    $scope.setStatusDeal = function($id, status) {
        var data = {
            id: $id,
            status: status
        };
        $http.post(Config.baseUrl + '/deals/saveDeal', data).success(function(response) {
            if (response.status == true) {
                $scope.setPage(0);
            }
        });
    };

    $scope.editDeal = function(deal) {
        $scope.incentiveAdd = true;
        $scope.addMode = false;
        $('#saveDeal').text('Save');
        $scope.newDeal = angular.copy(deal.deal);
        $scope.showPopup = true;
        $scope.copyOldItem = angular.copy(deal.deal);
        $scope.stores = [];
        $scope.loadingStores = true;
        $scope.bindStores($scope.newDeal.category_id);
    };
    
    $scope.saveDeal = function($status) {
        if ($scope.addDealForm.$invalid) {
            $scope.showError = true;
            return;
        }
        if ($scope.newDeal.id) {
            $scope.newDeal.status = $status;
        }
        $scope.showError = false;
        $scope.showPopup = false;
        if (!$scope.newDeal.id) {
            $scope.newDeal.status = 'pending';
        }
        $http.post(Config.baseUrl + '/deals/saveDeal', $scope.newDeal).success(function(response) {
            if (response.status == true) {
                $http({method: 'GET', url: Config.baseUrl + '/products/deleterac', params: {"type": "deal"}}).then(function(dataRes) {
                    $scope.imgLoading = false;
                    $timeout(function() {
                        $('#saveDeal').trigger('reset');
                    });
                    $scope.newDeal = {};
                    $scope.setPage(0);
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
            }
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
    
    $scope.$watch('newDeal', function() {
        if ($scope.showPopup) {
            if ($scope.copyOldItem && (!angular.equals($scope.copyOldItem, $scope.newDeal))) {
                $scope.currentChanged = true;
            } else {
                $scope.currentChanged = false;
            }
        } else {
            $scope.currentChanged = false;
        }
    }, true);
    
    // if user is logged in save survey as draft every 10 sec
    var timeout = 10000;
    setInterval(function(){
        if ($scope.newDeal && $scope.currentChanged) {
            var data = {};
            if ($scope.newDeal && $scope.newDeal.id) {
                data.left_id = $scope.newDeal.id;
            }
            data.type = "deal";
            data.content = angular.toJson($scope.newDeal);
            $http.post(Config.baseUrl + '/products/addrac', data).success(function(response) {
                $scope.currentChanged = false;
            });
        }
    }, timeout);
    
    $scope.getFromDbDealDraft = function() {
        //load drafts
        $http({method: 'GET', url: Config.baseUrl + '/products/queryrac', params: {"type": "deal"}}).then(function(response) {
            if (response.data && response.data.draft && response.data.draft.content) {
                $scope.popupDraft = {};
                $scope.popupDraft.draft = angular.fromJson(response.data.draft.content);
                $scope.popupDraft.created = response.data.draft.created;
            } else {
                $scope.popupDraft = null;
            }
        }, function(response) {
            throw response;
        });
    };
    $scope.getFromDbDealDraft();
    // load a draft and overwrites the current survey
    $scope.loadDraft = function() {
        if (confirm('Do you want to load this draft?')) {
            $scope.newDeal = angular.copy($scope.popupDraft.draft);
        }
        $scope.showDraft = false;
    };
});