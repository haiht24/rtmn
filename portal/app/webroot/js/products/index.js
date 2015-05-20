angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
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
    });
angular.module('fdb', ['fdb.services', 'fdb.directives', 'fdb.filters', 'brantwills.paging']).
    controller('ProductsCtrl', function ($scope, $http, $timeout, $filter) {
        $scope.mostCouponUrlRegex = /^[0-9a-zA-Z-]+$/;
        $scope.numberRegex = /^[0-9-]+$/; // /^((([0-9]{1,3})(,[0-9]{3})*)|([0-9]+))(.[0-9]+)+$/;
        $scope.currentCategory = {};
        $scope.categoryItem = {
            totalItems: 0,
            addMode: false,
            copyOldItem: {},
            checkExist: false,
            isExist: false,
            showError: false,
            showDraft: true,
            popupDraft: null,
            editIndex: -1,
            showPopupCategory: false,
            currentCategoryChanged: false,
            pages: [],
            itemsPerPage: 10,
            currentPageInc: 1,
            currentPage: 0,
            filter: '',
            userFilter: '0',
            statusFilter: '0',
            createdFromFilter: '',
            createdToFilter: '',
            listCategories: angular.copy($scope.glstCategoriesFull),
            disalbledDeleteAll: false
        };
        $scope.currentStore = {};
        $scope.newDeal = {};
        $scope.storeItem = {
            showError: false,
            itemsPerPage: 10,
            showListCategories: false,
            pages: [],
            filter: '',
            currentPageInc: 1,
            currentPage: 0,
            isExist: false,
            isExistURL: false,
            totalStores: 0,
            numberOfPages: 0,
            suggestList: [],
            limitSuggest: 10,
            checkNameExist: false,
            checkNotNameExist: false,
            checkStoreURLExist: false,
            checkNotStoreURLExist: false,
            userFilter: '0',
            createdToFilter: '',
            createdFromFilter: '',
            statusFilter: '0',
            listcategories: [],
            existCouponURlStore: [],
            existNameStore: [],
            //for store draft
            showStorePopup: false,
            copyOldStoreItem: null,
            showStoreDraft: true,
            currentStoreChanged: false,
            popupStoreDraft: null,
            addStoreMode: false,

            //for deal draft
            showDealPopup: false,
            copyOldDealItem: null,
            showDealDraft: true,
            currentDealChanged: false,
            popupDealDraft: null,
            addDealMode: false,
            stores: [],

            //for coupon draft
            showCouponPopup: false,
            copyOldCouponItem: null,
            showCouponDraft: true,
            currentCouponChanged: false,
            popupCouponDraft: null,
            addCouponMode: false,
            disalbledDeleteAll: false
        };
        $scope.filterStoreOptions = {
            textFilter: null,
            sortField: null,
            userFilter: null,
            createdFromFilter: null,
            createdToFilter: null,
            statusFilter: null,
            sortBy: null,
            limit: 10,
            offset: 0
        };
        $scope.newCoupon = {};
        $scope.couponItem = {
            showError: false,
            itemsPerPage: 10,
            pages: [],
            filter: '',
            currentPageInc: 1,
            currentPage: 0,
            totalCoupons: 0,
            numberOfPages: 0,
            userFilter: '0',
            createdToFilter: '',
            createdFromFilter: '',
            statusFilter: '0',
            showPopup: false,
            copyOldItem: null,
            currentChanged: false,
            disalbledDeleteAll: false
        };

        $scope.filterCouponOptions = {
            textFilter: null,
            sortField: null,
            statusFilter: null,
            createdFromFilter: null,
            createdToFilter: null,
            sortBy: null,
            limit: 10,
            offset: 0
        };
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

        $scope.dealItem = {
            showError: false,
            itemsPerPage: 10,
            pages: [],
            filter: '',
            currentPageInc: 1,
            currentPage: 0,
            totalDeals: 0,
            numberOfPages: 0,
            loadingStores: false,
            userFilter: '0',
            statusFilter: '0',
            createdFromFilter: '',
            createdToFilter: '',
            showPopup: false,
            copyOldItem: null,
            showDraft: true,
            currentChanged: false,
            popupDraft: null,
            addMode: false,
            storesDeal: [],
            disalbledDeleteAll: false
        };

        $scope.filterDealOptions = {
            textFilter: null,
            sortField: null,
            statusFilter: null,
            createdFromFilter: null,
            createdToFilter: null,
            sortBy: null,
            limit: 10,
            offset: 0
        };
        $scope.incentiveAdd = false;

        $scope.filterCategoryOptions = {
            textFilter: null,
            createdFromFilter: null,
            createdToFilter: null,
            statusFilter: null,
            sortField: 'created',
            sortBy: false
        };
        $scope.setSubCategories = function () {
            $scope.glbCategories = [];
            if ($scope.glstCategories) {
                $scope.glbCategories = from($scope.glstCategories).where('!$.category.parent_id').orderBy('$.category.name').toArray();
                for (var g = 0; g < $scope.glbCategories.length; g++) {
                    $scope.glbCategories[g].category.sub_category = from($scope.glstCategories).where('$.category.parent_id == "' + $scope.glbCategories[g].category.id + '"').orderBy('$.category.name').toArray();
                }
            }
        };

        $scope.getSubCategories = function () {
            var jsonArr = [];
            for (var i = 0; i < $scope.glbCategories.length; i++) {
                //var jsonSubArr = [];
                jsonArr.push({
                    id: $scope.glbCategories[i].category.id,
                    text: $scope.glbCategories[i].category.name
                    //children: jsonSubArr
                });
                for (var j = 0; j < $scope.glbCategories[i].category.sub_category.length; j++) {
                    jsonArr.push({
                        id: $scope.glbCategories[i].category.sub_category[j].category.id,
                        text: '-- ' + $scope.glbCategories[i].category.sub_category[j].category.name
                    });
                }
            }
            return jsonArr;
        };
        $scope.glstCategories = from($scope.glstCategoriesFull).where('$.category.status == "published"').orderBy('$.category.name').toArray();
        $scope.setSubCategories();

        $(document).click(function (e) {
            $timeout(function () {
                if ($('.modal-backdrop').length <= 0) {
                    $scope.$apply(function () {
                        $scope.categoryItem.showPopupCategory = false;
                        $scope.incentiveAdd = false;
                        $scope.storeItem.showStorePopup = false;
                        $scope.storeItem.showDealPopup = false;
                        $scope.storeItem.showCouponPopup = false;
                        $scope.couponItem.showPopup = false;
                        $scope.dealItem.showPopup = false;
                    });
                }
            }, 500);
        });

        $scope.autoCalculatePriceDeal = function (onChangeIndex) {
            if ($scope.newDeal.origin_price) {
                var x = $('#originPriceDeal').autoNumeric('get');
                if ($scope.newDeal.discount_price && (onChangeIndex == 1 || onChangeIndex == 2)) {
                    var y = $('#realPriceDeal').autoNumeric('get') ? $('#realPriceDeal').autoNumeric('get') : 0;
                    if (x >= y) $('#discountPercentDeal').autoNumeric('set', ((x - y) / x) * 100);
                } else if ($scope.newDeal.discount_percent && (onChangeIndex == 1 || onChangeIndex == 3)) {
                    var z = $('#discountPercentDeal').autoNumeric('get') ? $('#discountPercentDeal').autoNumeric('get') : 0;
                    $('#realPriceDeal').autoNumeric('set', ((100 - z ) * x) / 100);
                }
            }
//            if ($scope.newDeal.origin_price) {
//                var o = $scope.newDeal.origin_price;
//                if($scope.newDeal.discount_price && (onChangeIndex == 1 || onChangeIndex == 2)){
//                    var r = $scope.newDeal.discount_price;
//                    d = (r*100)/o;
//                    $scope.newDeal.discount_percent = d;
//                }
//                if($scope.newDeal.discount_percent && (onChangeIndex == 1 || onChangeIndex == 3 )){
//                    var d = $scope.newDeal.discount_percent;
//                    r = o - ((d*o)/100);
//                    $scope.newDeal.discount_price = r;
//                }
//            }
        };

        //$('#originPriceDeal').on("blur", function () {
        //    $scope.$apply(function () {
        //        $scope.autoCaculatePriceDeal(1);
        //    });
        //});
        $scope.getFromDbCategoryDraft = function () {
            //load drafts
            $http({
                method: 'GET',
                url: Config.baseUrl + '/products/queryrac',
                params: {"type": "category"}
            }).then(function (response) {
                if (response.data && response.data.draft && response.data.draft.content) {
                    $scope.categoryItem.popupDraft = {};
                    $scope.categoryItem.popupDraft.draft = angular.fromJson(response.data.draft.content);
                    $scope.categoryItem.popupDraft.created = response.data.draft.created;
                } else {
                    $scope.categoryItem.popupDraft = null;
                }
            }, function (response) {
                throw response;
            });
        };

        $scope.getFromDbStoreDrafts = function () {
            //load store drafts
            $http({
                method: 'GET',
                url: Config.baseUrl + '/products/queryrac',
                params: {"type": "store"}
            }).then(function (response) {
                if (response.data && response.data.draft && response.data.draft.content) {
                    $scope.storeItem.popupStoreDraft = {};
                    $scope.storeItem.popupStoreDraft.draft = angular.fromJson(response.data.draft.content);
                    $scope.storeItem.popupStoreDraft.created = response.data.draft.created;
                } else {
                    $scope.storeItem.popupStoreDraft = null;
                }
            }, function (response) {
                throw response;
            });
        };

        $scope.getFromDbDealDraft = function () {
            //load deal drafts
            $http({
                method: 'GET',
                url: Config.baseUrl + '/products/queryrac',
                params: {"type": "deal"}
            }).then(function (response) {
                if (response.data && response.data.draft && response.data.draft.content) {
                    $scope.storeItem.popupDealDraft = {};
                    $scope.storeItem.popupDealDraft.draft = angular.fromJson(response.data.draft.content);
                    $scope.storeItem.popupDealDraft.created = response.data.draft.created;
                } else {
                    $scope.storeItem.popupDealDraft = null;
                }
            }, function (response) {
                throw response;
            });
        };

        $scope.getFromDbCouponDraft = function () {
            //load coupon drafts
            $http({
                method: 'GET',
                url: Config.baseUrl + '/products/queryrac',
                params: {"type": "coupon"}
            }).then(function (response) {
                if (response.data && response.data.draft && response.data.draft.content) {
                    $scope.storeItem.popupCouponDraft = {};
                    $scope.storeItem.popupCouponDraft.draft = angular.fromJson(response.data.draft.content);
                    $scope.storeItem.popupCouponDraft.created = response.data.draft.created;
                } else {
                    $scope.storeItem.popupCouponDraft = null;
                }
            }, function (response) {
                throw response;
            });
        };

        $scope.initCategory = function () {
            $scope.currentCategory = {};
            $('#modal-label-add-cate').text('Add New Category');
            $('#saveCate').text('Add');
            $scope.categoryItem.addMode = true;
            $scope.categoryItem.copyOldItem = {};
            $scope.categoryItem.checkExist = false;
            $scope.categoryItem.isExist = false;
            $scope.categoryItem.showError = false;
            $scope.categoryItem.showDraft = true;
            $scope.categoryItem.popupDraft = null;
            $scope.categoryItem.editIndex = -1;
            $scope.getFromDbCategoryDraft();
            $('.category-tags').select2('val', '');
        };

        $scope.initAddNewStore = function () {
            $('#saveStore').text('Add');
            $('#modal-label-add-store').text('Add New Store');
            $scope.storeItem.checkNameExist = false;
            $scope.storeItem.checkNotNameExist = false;
            $scope.storeItem.checkStoreURLExist = false;
            $scope.storeItem.checkNotStoreURLExist = false;
            $scope.currentStore = {};
            $scope.getFromDbStoreDrafts();
            $scope.storeItem.showError = false;
            $scope.storeItem.suggestList = [];
            $scope.storeItem.existCouponURlStore = [];
            $scope.storeItem.existNameStore = [];
            $scope.storeItem.listURlExist = [];
            $scope.storeItem.checkURLExist = false;
            $scope.storeItem.checkNotURLExist = false;
            $scope.incentiveAdd = true;
            if (!$scope.currentStore.best_store) {
                $scope.currentStore.best_store = 0;
            }
            $scope.currentStore.custom_keywords = 'Coupon Codes';
            $scope.storeItem.copyOldStoreItem = angular.copy($scope.currentStore);
            $scope.storeItem.showStoreDraft = true;
            $scope.storeItem.addStoreMode = true;
            $scope.storeItem.showStorePopup = true;
            $('#listCategories').select2('val', '');
            $('#listCountries').select2('val', ['US']);
            $('.store-tags').select2('val', '');
        };

        $scope.arrayContains = function (value, container) {
            if (value) {
                return $.inArray(value, container) != -1;
            }
            return false
        };
        $scope.arraySplit = function (value, separator) {
            if (value) {
                return value.split(separator);
            } else return null;
        };
        $scope.arrCateOfStore = function () {
            return $scope.currentStore.categories_id;
        };

        $scope.searchMatch = function (haystack, needle) {
            if (!needle) {
                return true;
            }
            if (!haystack) {
                return false;
            }
            return haystack.toLowerCase().indexOf(needle.toLowerCase()) !== -1;
        };

        $scope.compareDateGreater = function (date1, date2) {
            var d1 = new Date(date1);
            d1.setHours(0, 0, 0, 0);
            var d2 = new Date(date2);
            d2.setHours(0, 0, 0, 0);

            if (d1 >= d2) {
                return true;
            } else return false
        };

        $scope.range = function (start, end) {
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

        $scope.getYesNo = function (val) {
            if (val && val == 1) {
                return 'YES';
            } else {
                return 'NO';
            }
        };

        $scope.getDefaultDate = function () {
            return moment().format('YYYY-MM-DD');
        };

        $scope.loadDraftCategory = function () {
            if (confirm('Do you want to load this draft?')) {
                $scope.currentCategory = angular.copy($scope.categoryItem.popupDraft.draft);
            }
            $scope.categoryItem.showDraft = false;
        };

        $scope.loadStoreDraft = function () {
            if (confirm('Do you want to load this draft?')) {
                $scope.currentStore = angular.copy($scope.storeItem.popupStoreDraft.draft);
                $scope.storeItem.suggestList = [];
                $scope.storeItem.checkNameExist = false;
                $scope.storeItem.checkNotNameExist = false;
                $scope.storeItem.checkStoreURLExist = false;
                $scope.storeItem.checkNotStoreURLExist = false;
            }
            $scope.storeItem.showStoreDraft = false;
        };

        // load a draft and overwrites the current deal
        $scope.loadDealDraft = function () {
            if (confirm('Do you want to load this draft?')) {
                $scope.newDeal = angular.copy($scope.storeItem.popupDealDraft.draft);
            }
            $scope.storeItem.showDealDraft = false;
        };

        // load a draft and overwrites the current coupon
        $scope.loadCouponDraft = function () {
            if (confirm('Do you want to load this draft?')) {
                $scope.newCoupon = angular.copy($scope.storeItem.popupCouponDraft.draft);
            }
            $scope.storeItem.showCouponDraft = false;
        };

        $scope.checkExistsNameCategory = function () {
            $http.post(Config.baseUrl + '/products/checkExistsCate',
                {name: $scope.currentCategory.name, id: $scope.currentCategory.id})
                .success(function (response) {
                    if (response.existName == true) {
                        $scope.categoryItem.checkExist = true;
                        $scope.categoryItem.checkNotExist = false;
                    } else {
                        $scope.categoryItem.checkNotExist = true;
                        $scope.categoryItem.checkExist = false;
                    }
                    return;
                });
        };

        $scope.$watch('currentCategory', function () {
            if ($scope.categoryItem.showPopupCategory) {
                if ($scope.categoryItem.copyOldItem &&
                    (!angular.equals($scope.categoryItem.copyOldItem, $scope.currentCategory))) {
                    $scope.categoryItem.currentCategoryChanged = true;
                } else {
                    $scope.categoryItem.currentCategoryChanged = false;
                }
            } else {
                $scope.categoryItem.currentCategoryChanged = false;
            }
        }, true);

        $scope.$watch('currentStore', function () {
            if ($scope.storeItem.showStorePopup) {
                if ($scope.storeItem.copyOldStoreItem && (!angular.equals($scope.storeItem.copyOldStoreItem, $scope.currentStore))) {
                    $scope.storeItem.currentStoreChanged = true;
                } else {
                    $scope.storeItem.currentStoreChanged = false;
                }
            } else {
                $scope.storeItem.currentStoreChanged = false;
            }
        }, true);

        $scope.$watch('newDeal', function () {
            if ($scope.storeItem.showDealPopup) {
                if ($scope.storeItem.copyOldDealItem && (!angular.equals($scope.storeItem.copyOldDealItem, $scope.newDeal))) {
                    $scope.storeItem.currentDealChanged = true;
                } else {
                    $scope.storeItem.currentDealChanged = false;
                }
            } else {
                $scope.storeItem.currentDealChanged = false;
            }
        }, true);

        $scope.$watch('newCoupon', function () {
            if ($scope.storeItem.showCouponPopup) {
                if ($scope.storeItem.copyOldCouponItem && (!angular.equals($scope.storeItem.copyOldCouponItem, $scope.newCoupon))) {
                    $scope.storeItem.currentCouponChanged = true;
                } else {
                    $scope.storeItem.currentCouponChanged = false;
                }
            } else {
                $scope.storeItem.currentCouponChanged = false;
            }
        }, true);

        // if add category then save as draft every 10 sec
        var timeout = 10000;
        setInterval(function () {
            if ($scope.currentCategory && $scope.categoryItem.addMode && $scope.categoryItem.currentCategoryChanged) {
                var data = {};
                if ($scope.currentCategory && $scope.currentCategory.id) {
                    data.left_id = $scope.currentCategory.id;
                }
                data.type = "category";
                data.content = angular.toJson($scope.currentCategory);
                $http.post(Config.baseUrl + '/products/addrac', data).success(function (response) {
                    $scope.categoryItem.currentCategoryChanged = false;
                });
            } else if ($scope.currentStore && $scope.storeItem.addStoreMode && $scope.storeItem.currentStoreChanged) {
                var data = {};
                if ($scope.currentStore && $scope.currentStore.id) {
                    data.left_id = $scope.currentStore.id;
                }
                data.type = "store";
                data.content = angular.toJson($scope.currentStore);
                $http.post(Config.baseUrl + '/products/addrac', data).success(function (response) {
                    $scope.storeItem.currentStoreChanged = false;
                });
            } else if ($scope.newDeal && $scope.storeItem.currentDealChanged) {
                var data = {};
                if ($scope.newDeal && $scope.newDeal.id) {
                    data.left_id = $scope.newDeal.id;
                }
                data.type = "deal";
                data.content = angular.toJson($scope.newDeal);
                $http.post(Config.baseUrl + '/products/addrac', data).success(function (response) {
                    $scope.storeItem.currentDealChanged = false;
                });
            } else if ($scope.newCoupon && $scope.storeItem.currentCouponChanged) {
                var data = {};
                if ($scope.newCoupon && $scope.newCoupon.id) {
                    data.left_id = $scope.newCoupon.id;
                }
                data.type = "coupon";
                data.content = angular.toJson($scope.newCoupon);
                $http.post(Config.baseUrl + '/products/addrac', data).success(function (response) {
                    $scope.storeItem.currentCouponChanged = false;
                });
            }
        }, timeout);

        $scope.showAllCategory = function () {
            $scope.categoryItem.filter = null;
            $scope.categoryItem.userFilter = '0';
            $scope.categoryItem.createdFromFilter = null;
            $scope.categoryItem.createdToFilter = null;
            $scope.categoryItem.statusFilter = '0';
            $scope.searchCategory();
            $http.post(Config.baseUrl + '/products/deleteProductSessions', {'type': 'Categories'}).success(function (res) {
            })
        };

        $scope.searchCategory = function () {
            $('#category-table .check_all').prop("checked", false);
            $scope.categoryItem.currentPageInc = 1;
            $scope.categoryItem.currentPage = 0;
            $scope.filterCategoryOptions.textFilter = $scope.categoryItem.filter;
            $scope.filterCategoryOptions.userFilter = $scope.categoryItem.userFilter;

            $scope.filterCategoryOptions.createdFromFilter = $scope.categoryItem.createdFromFilter;
            $scope.filterCategoryOptions.createdToFilter = $scope.categoryItem.createdToFilter;

            $scope.filterCategoryOptions.statusFilter = $scope.categoryItem.statusFilter;
            if ($scope.categoryItem.listCategories.length > 0) {
                if ($scope.filterCategoryOptions.textFilter || $scope.filterCategoryOptions.userFilter != '0'
                    || $scope.filterCategoryOptions.createdFromFilter || $scope.filterCategoryOptions.createdToFilter
                    || $scope.filterCategoryOptions.statusFilter != '0') {
                    var filteredItems = $filter('filter')($scope.categoryItem.listCategories, function (item) {
                        var check = false;
                        if ($scope.filterCategoryOptions.textFilter) {
                            if ($scope.searchMatch(item.category.name, $scope.filterCategoryOptions.textFilter)) {
                                check = true;
                            }
                            if ($scope.searchMatch(item.category.alias, $scope.filterCategoryOptions.textFilter)) {
                                check = true;
                            }
                            if ($scope.searchMatch(item.category.description, $scope.filterCategoryOptions.textFilter)) {
                                check = true;
                            }
                        } else {
                            check = true;
                        }
                        var check1 = false;
                        if ($scope.filterCategoryOptions.userFilter != '0') {
                            if ($scope.searchMatch(item.category.user_id, $scope.filterCategoryOptions.userFilter)) {
                                check1 = true;
                            }
                        } else {
                            check1 = true;
                        }
                        var check2 = false;
                        if ($scope.filterCategoryOptions.createdFromFilter) {
                            if (item.category.created) {
                                var op1 = moment.tz($scope.categoryItem.createdFromFilter, Config.timeZone);
                                var op2Date = moment.utc(item.category.created).tz(Config.timeZone).format('YYYY-MM-DD');
                                var op2 = moment.tz(op2Date, Config.timeZone);
                                if (op1.unix() <= op2.unix()) {
                                    check2 = true;
                                }
                            }
                        } else {
                            check2 = true;
                        }
                        var check3 = false;
                        if ($scope.filterCategoryOptions.createdToFilter) {
                            var op1 = moment.tz($scope.categoryItem.createdToFilter, Config.timeZone);
                            var op2Date = moment.utc(item.category.created).tz(Config.timeZone).format('YYYY-MM-DD');
                            var op2 = moment.tz(op2Date, Config.timeZone);
                            if (op1.unix() >= op2.unix()) {
                                check3 = true;
                            }
                        } else {
                            check3 = true;
                        }
                        var check4 = false;
                        if ($scope.filterCategoryOptions.statusFilter != '0') {
                            if ($scope.searchMatch(item.category.status, $scope.filterCategoryOptions.statusFilter)) {
                                check4 = true;
                            }
                        } else {
                            check4 = true;
                        }
                        return (check & check1 && check2 && check3 && check4);
                    });
                } else {
                    filteredItems = $scope.categoryItem.listCategories;
                }
                var pagedItems = [];
                $scope.categoryItem.totalItems = filteredItems.length;
                for (var i = 0; i < filteredItems.length; i++) {
                    if (i % $scope.categoryItem.itemsPerPage === 0) {
                        pagedItems[Math.floor(i / $scope.categoryItem.itemsPerPage)] = [filteredItems[i]];
                    } else {
                        pagedItems[Math.floor(i / $scope.categoryItem.itemsPerPage)].push(filteredItems[i]);
                    }
                }
                $scope.categoryItem.pages = pagedItems;
                $('html,body').animate({
                        scrollTop: $("#wid-cate-list").offset().top
                    },
                    'slow');
                setTimeout(function () {
                    initStatusEditable();
                }, 500);
            }
        };

        $scope.sortCategories = function () {
            if ($scope.categoryItem.listCategories.length > 0) {
                if ($scope.filterCategoryOptions.sortBy) {
                    $scope.categoryItem.listCategories.sort(function (a, b) {
                        var nameA = '';
                        var nameB = '';
                        if ($scope.filterCategoryOptions.sortField == 'father') {
                            nameA = a.father.name;
                            nameB = b.father.name;
                        } else if ($scope.filterCategoryOptions.sortField == 'author') {
                            nameA = a.author.fullname ? a.author.fullname : '';
                            nameB = b.author.fullname ? b.author.fullname : '';
                        } else {
                            nameA = a.category[$scope.filterCategoryOptions.sortField];
                            nameB = b.category[$scope.filterCategoryOptions.sortField];
                        }
                        nameA = nameA ? nameA.toLowerCase() : '';
                        nameB = nameB ? nameB.toLowerCase() : '';
                        if (nameB > nameA) {
                            return -1;
                        } else if (nameB < nameA) {
                            return 1;
                        } else {
                            return 0;
                        }
                    });
                } else {
                    $scope.categoryItem.listCategories.sort(function (a, b) {
                        var nameA = '';
                        var nameB = '';
                        if ($scope.filterCategoryOptions.sortField == 'father') {
                            nameA = a.father.name;
                            nameB = b.father.name;
                        } else if ($scope.filterCategoryOptions.sortField == 'author') {
                            nameA = a.author.fullname ? a.author.fullname : '';
                            nameB = b.author.fullname ? b.author.fullname : '';
                        } else {
                            nameA = a.category[$scope.filterCategoryOptions.sortField];
                            nameB = b.category[$scope.filterCategoryOptions.sortField];
                        }
                        nameA = nameA ? nameA.toLowerCase() : '';
                        nameB = nameB ? nameB.toLowerCase() : '';
                        if (nameB < nameA) {
                            return -1;
                        } else if (nameB > nameA) {
                            return 1;
                        } else {
                            return 0;
                        }
                    });
                }
                var filteredItems = null;
                if ($scope.filterCategoryOptions.textFilter || $scope.filterCategoryOptions.userFilter != '0'
                    || $scope.filterCategoryOptions.createdToFilter || $scope.filterCategoryOptions.createdFromFilter) {
                    filteredItems = $filter('filter')($scope.categoryItem.listCategories, function (item) {
                        var check = false;
                        if ($scope.filterCategoryOptions.textFilter) {
                            if ($scope.searchMatch(item.category.name, $scope.filterCategoryOptions.textFilter)) {
                                check = true;
                            }
                            if ($scope.searchMatch(item.category.alias, $scope.filterCategoryOptions.textFilter)) {
                                check = true;
                            }
                            if ($scope.searchMatch(item.category.status, $scope.filterCategoryOptions.textFilter)) {
                                check = true;
                            }
                        } else {
                            check = true;
                        }
                        var check1 = false;
                        if ($scope.filterCategoryOptions.userFilter != '0') {
                            if ($scope.searchMatch(item.category.user_id, $scope.filterCategoryOptions.userFilter)) {
                                check1 = true;
                            }
                        } else {
                            check1 = true;
                        }
                        var check2 = false;
                        if ($scope.filterCategoryOptions.createdFromFilter) {
                            var op1 = moment($scope.filterCategoryOptions.createdFromFilter).format('YYYY-MM-DD');
                            var op2 = moment(item.category.created).format('YYYY-MM-DD');
                            if (op1 <= op2) {
                                check2 = true;
                            }
                        } else {
                            check2 = true;
                        }
                        var check3 = false;
                        if ($scope.filterCategoryOptions.createdToFilter) {
                            var op1 = moment($scope.filterCategoryOptions.createdToFilter).format('YYYY-MM-DD');
                            var op2 = moment(item.category.created).format('YYYY-MM-DD');
                            if (op1 >= op2) {
                                check3 = true;
                            }
                        } else {
                            check3 = true;
                        }
                        return (check & check1 && check2 && check3);
                    });
                } else {
                    filteredItems = $scope.categoryItem.listCategories;
                }
                var pagedItems = [];
                $scope.categoryItem.totalItems = filteredItems.length;
                for (var i = 0; i < filteredItems.length; i++) {
                    if (i % $scope.categoryItem.itemsPerPage === 0) {
                        pagedItems[Math.floor(i / $scope.categoryItem.itemsPerPage)] = [filteredItems[i]];
                    } else {
                        pagedItems[Math.floor(i / $scope.categoryItem.itemsPerPage)].push(filteredItems[i]);
                    }
                }
                $scope.categoryItem.pages = pagedItems;
            }
        };

        $scope.sortByCategory = function (field) {
            if ($scope.filterCategoryOptions.sortField && $scope.filterCategoryOptions.sortField == field) {
                $scope.filterCategoryOptions.sortBy = !$scope.filterCategoryOptions.sortBy;
            } else {
                $scope.filterCategoryOptions.sortBy = true;
            }
            $scope.filterCategoryOptions.sortField = field;
            $scope.sortCategories();
        };

        $scope.sortCategories();

        $scope.saveCategory = function ($status) {
            if ($scope.addCateForm.$invalid) {
                $scope.categoryItem.showError = true;
                return;
            }
            $http.post(Config.baseUrl + '/products/checkExistsCate',
                {name: $scope.currentCategory.name, id: $scope.currentCategory.id})
                .success(function (response) {
                    if (response.existName == true) {
                        $scope.categoryItem.isExist = true;
                        $scope.categoryItem.checkExist = true;
                        $scope.categoryItem.checkNotExist = false;
                        $scope.categoryItem.showError = true;
                        return;
                    } else {
                        $scope.categoryItem.isExist = false;
                        $scope.categoryItem.checkNotExist = true;
                        $scope.categoryItem.checkExist = false;
                        $scope.categoryItem.showError = false;
                        var dataSave = angular.copy($scope.currentCategory);
                        $scope.currentCategory = {};
                        if ($.isEmptyObject(dataSave)) return;
                        dataSave.tags = $('.category-tags').select2('val').toString();
                        if ($status) {
                            dataSave.status = $status;
                        }
                        $scope.currentCategory.alias = $('#category-alias').val();
                        $scope.categoryItem.showPopupCategory = false;
                        $http.post(Config.baseUrl + '/products/saveCategory', dataSave).success(function (response) {
                            if (response.status == true) {
                                $http({
                                    method: 'GET',
                                    url: Config.baseUrl + '/products/deleterac',
                                    params: {"type": "category"}
                                }).then(function (dataRes) {
                                    if (dataSave.id) {
                                        $scope.categoryItem.pages[$scope.categoryItem.currentPage][$scope.categoryItem.editIndex] = angular.copy(response.category);
                                        for (var i = 0; i < $scope.categoryItem.listCategories.length; i++) {
                                            if ($scope.categoryItem.listCategories[i].category.id == response.category.category.id) {
                                                $scope.categoryItem.listCategories[i] = angular.copy(response.category);
                                                break;
                                            }
                                        }
                                    } else {
                                        var cate = angular.copy(response.category);
                                        $scope.categoryItem.listCategories.push(cate);
                                        if (!cate.category.parent_id) {
                                            $scope.categories.push(cate);
                                        } else {
                                            for (var i = 0; i < $scope.categories.length; i++) {
                                                if (cate.category.parent_id == $scope.categories[i].category.id) {
                                                    if (!$scope.categories[i].category.sub_category) {
                                                        $scope.categories[i].category.sub_category = [];
                                                    }
                                                    $scope.categories[i].category.sub_category.push(cate);
                                                    break;
                                                }
                                            }
                                        }
                                        $scope.categoryItem.filter = null;
                                        $scope.categoryItem.userFilter = null;
                                        $scope.categoryItem.createdFilter = null;
                                        $scope.categoryItem.publishFilter = null;
                                        $scope.categoryItem.statusFilter = null;
                                        $scope.filterCategoryOptions.textFilter = $scope.categoryItem.filter;
                                        $scope.filterCategoryOptions.userFilter = $scope.categoryItem.userFilter;
                                        $scope.filterCategoryOptions.createdToFilter = $scope.categoryItem.createdToFilter;
                                        $scope.filterCategoryOptions.createdFromFilter = $scope.categoryItem.createdFromFilter;
                                        $scope.filterCategoryOptions.statusFilter = $scope.categoryItem.statusFilter;
                                        $scope.filterCategoryOptions.sortField = 'created';
                                        $scope.filterCategoryOptions.sortBy = false;
                                        $scope.sortCategories();
                                    }
                                    $scope.checkStatusCategories();
                                    $scope.glstCategories = from($scope.categoryItem.listCategories).where('$.category.status == "published"').orderBy('$.category.name').toArray();
                                    $scope.setSubCategories();
                                    setTimeout(function () {
                                        initStatusEditable();
                                    }, 500);
                                    $timeout(function () {
                                        $('#saveCate').trigger('reset');
                                    });
                                    $timeout(function () {
                                        $('#cancelCate').click();
                                    });
                                }, function (ex) {
                                    $timeout(function () {
                                        $('#saveCate').trigger('reset');
                                    });
                                    $timeout(function () {
                                        $('#cancelCate').click();
                                    });
                                    throw ex;
                                });
                            } else {
                                $timeout(function () {
                                    $('#saveCate').trigger('reset');
                                });
                                $timeout(function () {
                                    $('#cancelCate').click();
                                });
                            }

                        });
                    }
                });
        };

        $scope.prevPageCategory = function () {
            if ($scope.categoryItem.currentPage > 0) {
                $scope.setPageCategory($scope.categoryItem.currentPage - 1);
            }
        };

        $scope.nextPageCategory = function () {
            if ($scope.categoryItem.currentPage < $scope.categoryItem.pages.length - 1) {
                $scope.setPageCategory($scope.categoryItem.currentPage + 1);
            }
        };

        $scope.setPageCategory = function (n) {
            $http.post(Config.baseUrl + '/products/setCurrentPage', {
                'currentPage': n,
                'type': 'Categories'
            }).success(function (res) {
            })

            $scope.categoryItem.currentPage = n;
            $scope.categoryItem.currentPageInc = $scope.categoryItem.currentPage + 1;
            $scope.categoryItem.currentPageInc = $scope.categoryItem.currentPageInc > $scope.categoryItem.pages.length ? $scope.categoryItem.pages.length : $scope.categoryItem.currentPageInc;
            $scope.categoryItem.currentPageInc = $scope.categoryItem.currentPageInc < 1 ? 1 : $scope.categoryItem.currentPageInc;
            $('html,body').animate({
                    scrollTop: $("#wid-cate-list").offset().top
                },
                'slow');
            $('#category-table .check_all').prop("checked", false);
            initStatusEditable();
        };
        if ($scope.categoryCurrentPage > 0) {
            $scope.categoryItem.currentPage = $scope.categoryCurrentPage;
        }


        $scope.changePageCategory = function () {
            $scope.categoryItem.currentPage = $scope.categoryItem.currentPageInc - 1;
            $scope.categoryItem.currentPage = $scope.categoryItem.currentPage > $scope.categoryItem.pages.length - 1 ? $scope.categoryItem.pages.length - 1 : $scope.categoryItem.currentPage;
            $scope.categoryItem.currentPage = $scope.categoryItem.currentPage < 0 ? 0 : $scope.categoryItem.currentPage;
            $scope.setPageCategory($scope.categoryItem.currentPage);
        };

        $scope.editCategory = function (category, indexItem) {
            $scope.categoryItem.addMode = false;
            $scope.currentCategory = angular.copy(category.category);
            $scope.currentCategory.author = angular.copy(category.author);
            $scope.categoryItem.copyOldItem = angular.copy($scope.currentCategory);
            $('#modal-label-add-cate').text('Update Category');
            $('#saveCate').text('Update');
            $scope.categoryItem.editIndex = indexItem;
            $scope.categoryItem.checkExist = false;
            $scope.categoryItem.checkNotExist = false;
            $scope.categoryItem.isExist = false;
            $scope.categoryItem.showError = false;
            $scope.categoryItem.showPopupCategory = true;
            if ($scope.currentCategory.tags) {
                $('.category-tags').select2('val', $scope.currentCategory.tags.split(","));
            } else {
                $('.category-tags').select2('val', '');
            }
        };

        $scope.deleteCategory = function (id) {
            $http.post(Config.baseUrl + '/products/deleteCategory/' + id).success(function (response) {
                if (response.status == true) {
                    for (var index = 0; index < $scope.categoryItem.listCategories.length; index++) {
                        if ($scope.categoryItem.listCategories[index].category.id == id) {
                            $scope.categoryItem.listCategories.splice(index, 1);
                            break;
                        }
                    }
                    $scope.searchCategory();
                    $scope.glstCategories = from($scope.categoryItem.listCategories).where('$.category.status == "published"').orderBy('$.category.name').toArray();
                    $scope.setSubCategories();
                    if ($scope.categoryItem.currentPage < $scope.categoryItem.numberOfPages - 1) {
                        $scope.setPageCategory($scope.categoryItem.currentPage);
                    } else if (0 < $scope.categoryItem.currentPage) {
                        $scope.setPageCategory($scope.categoryItem.currentPage - 1);
                    } else $scope.setPageCategory(0);
                } else {
                    alert(response.msg);
                }
                $timeout(function () {
                    $('#saveCate').trigger('reset');
                });
                $timeout(function () {
                    $('#cancelCate').click();
                });
            });
        };

        $scope.deleteCategories = function () {
            var data = {};
            data.ids = [];
            $('#category-table .check_element').each(function () {
                if ($(this).is(':checked')) {
                    var id = $(this).attr('id');
                    if (id) {
                        for (var i = 0; i < $scope.categoryItem.pages[$scope.categoryItem.currentPage].length; i++) {
                            if ($scope.categoryItem.pages[$scope.categoryItem.currentPage][i].category.id == id) {
                                if ($scope.categoryItem.pages[$scope.categoryItem.currentPage][i].category.status == 'trash') {
                                    data.ids.push(id);
                                }
                                break;
                            }
                        }
                    }
                }
            });
            if (data.ids.length > 0) {
                bootbox.confirm("Are you sure?", function (result) {
                    if (!result) return;
                    $http.post(Config.baseUrl + '/products/deleteCategories', data).success(function (response) {
                        if (response.status == true) {
                            var removeValFromIndex = [];
                            $scope.categoryItem.listCategories.forEach(function (element, index, array) {
                                if ($scope.arrayContains(element.category.id, data.ids)) {
                                    removeValFromIndex.push(index);
                                }
                            });
                            var arr = $.grep($scope.categoryItem.listCategories, function (n, i) {
                                return $.inArray(i, removeValFromIndex) == -1;
                            });
                            $scope.categoryItem.listCategories = arr;
                            $scope.searchCategory();
                            if ($scope.categoryItem.currentPage < $scope.categoryItem.numberOfPages - 1) {
                                $scope.setPageCategory($scope.categoryItem.currentPage);
                            } else if (0 < $scope.categoryItem.currentPage) {
                                $scope.setPageCategory($scope.categoryItem.currentPage - 1);
                            } else $scope.setPageCategory(0);
                        }
                    });
                });
            } else bootbox.alert("Please choose at least a category!");
        };

        $scope.checkStatusCategories = function () {
            setTimeout(function () {
                $scope.categoryItem.disalbledDeleteAll = false;
                $('#category-table .check_element').each(function () {
                    if ($scope.categoryItem.disalbledDeleteAll) return;
                    if ($(this).is(':checked')) {
                        for (var i = 0; i < $scope.categoryItem.pages[$scope.categoryItem.currentPage].length; i++) {
                            if ($scope.categoryItem.pages[$scope.categoryItem.currentPage][i].category.id == $(this).attr('id')) {
                                if ($scope.categoryItem.pages[$scope.categoryItem.currentPage][i].category.status != 'trash') {
                                    $scope.categoryItem.disalbledDeleteAll = true;
                                }
                                break;
                            }
                        }
                    }
                });
            }, 50);
        };

        $scope.checkStatusStores = function () {
            setTimeout(function () {
                $scope.storeItem.disalbledDeleteAll = false;
                $('#store-table .check_element').each(function () {
                    if ($scope.storeItem.disalbledDeleteAll) return;
                    if ($(this).is(':checked')) {
                        for (var i = 0; i < $scope.storeItem.pages.length; i++) {
                            if ($scope.storeItem.pages[i].store.id == $(this).attr('id')) {
                                if ($scope.storeItem.pages[i].store.status != 'trash') {
                                    $scope.storeItem.disalbledDeleteAll = true;
                                }
                                break;
                            }
                        }
                    }
                });
            }, 50);
        };

        $scope.changeStatusStore = function (id, status) {
            for (var i = 0; i < $scope.storeItem.pages.length; i++) {
                if ($scope.storeItem.pages[i].store.id == id) {
                    $scope.storeItem.pages[i].store.status = status;
                    break;
                }
            }
            $scope.checkStatusStores();
        };

        $scope.checkStatusCoupons = function () {
            setTimeout(function () {
                $scope.couponItem.disalbledDeleteAll = false;
                $('#coupon-table .check_element').each(function () {
                    if ($scope.couponItem.disalbledDeleteAll) return;
                    if ($(this).is(':checked')) {
                        for (var i = 0; i < $scope.couponItem.pages.length; i++) {
                            if ($scope.couponItem.pages[i].coupon.id == $(this).attr('id')) {
                                if ($scope.couponItem.pages[i].coupon.status != 'trash') {
                                    $scope.couponItem.disalbledDeleteAll = true;
                                }
                                break;
                            }
                        }
                    }
                });
            }, 50);
        };

        $scope.changeStatusCoupon = function (id, status) {
            for (var i = 0; i < $scope.couponItem.pages.length; i++) {
                if ($scope.couponItem.pages[i].coupon.id == id) {
                    $scope.couponItem.pages[i].coupon.status = status;
                    break;
                }
            }
            $scope.checkStatusCoupons();
        };

        $scope.deleteCoupons = function () {
            var data = {};
            data.ids = [];
            $('#coupon-table .check_element').each(function () {
                if ($(this).is(':checked')) {
                    var id = $(this).attr('id');
                    if (id) {
                        for (var i = 0; i < $scope.couponItem.pages.length; i++) {
                            if ($scope.couponItem.pages[i].coupon.id == id) {
                                if ($scope.couponItem.pages[i].coupon.status == 'trash') {
                                    data.ids.push(id);
                                }
                                break;
                            }
                        }
                    }
                }
            });
            if (data.ids.length > 0) {
                bootbox.confirm("Are you sure?", function (result) {
                    if (!result) return;
                    $http.post(Config.baseUrl + '/products/deleteCoupons', data).success(function (response) {
                        if (response.status == true) {
                            if ($scope.couponItem.currentPage < $scope.couponItem.numberOfPages - 1) {
                                $scope.setPageCoupon($scope.couponItem.currentPage);
                            } else if (0 < $scope.couponItem.currentPage) {
                                $scope.setPageCoupon($scope.couponItem.currentPage - 1);
                            } else $scope.setPageCoupon(1);
                        }
                    });
                });
            } else bootbox.alert('Please choose at least a coupon!')
        };

        $scope.checkStatusDeals = function () {
            setTimeout(function () {
                $scope.dealItem.disalbledDeleteAll = false;
                $('#deal-table .check_element').each(function () {
                    if ($scope.dealItem.disalbledDeleteAll) return;
                    if ($(this).is(':checked')) {
                        for (var i = 0; i < $scope.dealItem.pages.length; i++) {
                            if ($scope.dealItem.pages[i].deal.id == $(this).attr('id')) {
                                if ($scope.dealItem.pages[i].deal.status != 'trash') {
                                    $scope.dealItem.disalbledDeleteAll = true;
                                }
                                break;
                            }
                        }
                    }
                });
            }, 50);
        };

        $scope.changeStatusDeal = function (id, status) {
            for (var i = 0; i < $scope.dealItem.pages.length; i++) {
                if ($scope.dealItem.pages[i].deal.id == id) {
                    $scope.dealItem.pages[i].deal.status = status;
                    break;
                }
            }
            $scope.checkStatusDeals();
        };

        $scope.deleteDeals = function () {
            var data = {};
            data.ids = [];
            $('#deal-table .check_element').each(function () {
                if ($(this).is(':checked')) {
                    var id = $(this).attr('id');
                    if (id) {
                        for (var i = 0; i < $scope.dealItem.pages.length; i++) {
                            if ($scope.dealItem.pages[i].deal.id == id) {
                                if ($scope.dealItem.pages[i].deal.status == 'trash') {
                                    data.ids.push(id);
                                }
                                break;
                            }
                        }
                    }
                }
            });
            if (data.ids.length > 0) {
                bootbox.confirm("Are you sure?", function (result) {
                    if (!result) return;
                    $http.post(Config.baseUrl + '/deals/deleteDeals', data).success(function (response) {
                        if (response.status == true) {
                            if ($scope.dealItem.currentPage < $scope.dealItem.numberOfPages - 1) {
                                $scope.setPageDeal($scope.dealItem.currentPage);
                            } else if (0 < $scope.dealItem.currentPage) {
                                $scope.setPageDeal($scope.dealItem.currentPage - 1);
                            } else $scope.setPageDeal(1);
                        }
                    });
                });
            } else bootbox.alert('Please choose at least a deal!')
        };

        $scope.setStatusCategory = function (id, index, status) {
            var data = {};
            data.id = id;
            data.status = status;
            $http.post(Config.baseUrl + '/products/saveCategory', data).success(function (response) {
                if (response.status == true) {
                    $scope.categoryItem.pages[$scope.categoryItem.currentPage][index].category.status = response.category.category.status;
                    for (var s = 0; s < $scope.categoryItem.listCategories.length; s++) {
                        if ($scope.categoryItem.listCategories[s].category.id == response.category.category.id) {
                            $scope.categoryItem.listCategories[s].category.status = response.category.category.status
                            break;
                        }
                    }
                    $scope.glstCategories = from($scope.categoryItem.listCategories).where('$.category.status == "published"').orderBy('$.category.name').toArray();
                    $scope.setSubCategories();
                }
            });
        };

        $scope.changeStatusCategory = function (id, status) {
            for (var s = 0; s < $scope.categoryItem.listCategories.length; s++) {
                if ($scope.categoryItem.listCategories[s].category.id == id) {
                    if (s == 0) {
                        $scope.categoryItem.pages[$scope.categoryItem.currentPage][s].category.status = status;
                    } else if (s % $scope.categoryItem.itemsPerPage === 0) {
                        $scope.categoryItem.pages[$scope.categoryItem.currentPage][$scope.categoryItem.itemsPerPage - 1].category.status = status;
                    } else {
                        $scope.categoryItem.pages[$scope.categoryItem.currentPage][Math.floor(s / $scope.categoryItem.itemsPerPage)].category.status = status;
                    }
                    $scope.categoryItem.listCategories[s].category.status = status;
                    break;
                }
            }
            $scope.glstCategories = from($scope.categoryItem.listCategories).where('$.category.status == "published"').orderBy('$.category.name').toArray();
            $scope.setSubCategories();
            $scope.checkStatusCategories();
        };


        //$('#dateCategoryFilter').bind('keypress', function (e) {
        //    $('#dateCategoryFilter').val('');
        //    $scope.$apply(function () {
        //        $scope.categoryItem.createdFromFilter = '';
        //        $scope.searchCategory();
        //    });
        //});
        //$('#datePublishCategoryFilter').bind('keypress', function (e) {
        //    $('#datePublishCategoryFilter').val('');
        //    $scope.$apply(function () {
        //        $scope.categoryItem.createdToFilter = '';
        //        $scope.searchCategory();
        //    });
        //});
        //
        //$('#dateStoreFilter').bind('keypress', function (e) {
        //    $('#dateStoreFilter').val('');
        //    $scope.$apply(function () {
        //        $scope.storeItem.createdFromFilter = '';
        //        $scope.searchStore();
        //    });
        //});
        //
        //$('#datePublishStoreFilter').bind('keypress', function (e) {
        //    $('#datePublishStoreFilter').val('');
        //    $scope.$apply(function () {
        //        $scope.storeItem.createdToFilter = '';
        //        $scope.searchStore();
        //    });
        //});

        $scope.getDefaultStoreParams = function () {
            var params = {};
            params['limit'] = $scope.filterStoreOptions.limit;
            params['offset'] = $scope.filterStoreOptions.offset;
            if ($scope.filterStoreOptions.textFilter) {
                params['filter_name'] = $scope.filterStoreOptions.textFilter;
            }
            if ($scope.filterStoreOptions.userFilter) {
                params['user_id'] = $scope.filterStoreOptions.userFilter;
            }
            if ($scope.filterStoreOptions.createdFromFilter) {
                params['created_from'] = $scope.filterStoreOptions.createdFromFilter;
            }
            if ($scope.filterStoreOptions.createdToFilter) {
                params['created_to'] = $scope.filterStoreOptions.createdToFilter;
            }
            if ($scope.filterStoreOptions.statusFilter) {
                params['status'] = $scope.filterStoreOptions.statusFilter;
            }
            if ($scope.filterStoreOptions.sortField) {
                params['sort_field'] = $scope.filterStoreOptions.sortField;
                if ($scope.filterStoreOptions.sortBy) {
                    params['sort_by'] = 'ASC';
                } else {
                    params['sort_by'] = 'DESC';
                }
            } else {
                $scope.filterStoreOptions.sortField = 'created';
                $scope.filterStoreOptions.sortBy = false;
            }
            return params;
        };

        $scope.getStores = function () {
            $('#store-table .check_all').prop("checked", false);
            var params = $scope.getDefaultStoreParams();
            $http({
                method: 'GET',
                url: Config.baseUrl + '/products/queryStore',
                params: params
            }).then(function (response) {
                if (response.data.count > 0) {
                    $scope.storeItem.pages = response.data.stores;
                    $scope.storeItem.totalStores = response.data.count;
                    $scope.storeItem.numberOfPages = Math.ceil($scope.storeItem.totalStores / $scope.storeItem.itemsPerPage);
                    setTimeout(function () {
                        initStatusEditable()
                    }, 700);
                    //console.log($scope.storeItem.totalStores, $scope.storeItem.itemsPerPage, $scope.storeItem.numberOfPages);
                } else {
                    $scope.storeItem.pages = [];
                    $scope.storeItem.totalStores = 0;
                    $scope.storeItem.numberOfPages = 0;
                }
            }, function (response) {
                throw response;
            });
        };
        // Below functions wrote by HaiHT
        $scope.setStoreCurrentPage = function () {
            $('#store-table .check_all').prop("checked", false);
            var params = {};
            if ($scope.storeCurrentPage > 0) {
                params.offset = $scope.storeCurrentPage * 10;
            } else {
                params.offset = 0;
            }
            params.limit = $scope.storeItem.itemsPerPage;

            $http({
                method: 'GET',
                url: Config.baseUrl + '/products/queryStore',
                params: params
            }).then(function (response) {
                if (response.data.count > 0) {
                    $scope.storeItem.pages = response.data.stores;
                    $scope.storeItem.totalStores = response.data.count;
                    $scope.storeItem.numberOfPages = Math.ceil($scope.storeItem.totalStores / $scope.storeItem.itemsPerPage);
                    setTimeout(function () {
                        initStatusEditable()
                    }, 700);
                    $scope.setPageStore($scope.storeCurrentPage);
                } else {
                    $scope.storeItem.pages = [];
                    $scope.storeItem.totalStores = 0;
                    $scope.storeItem.numberOfPages = 0;
                }
            }, function (response) {
                throw response;
            });
        };

        $scope.setCouponCurrentPage = function () {
            $('#coupon-table .check_all').prop("checked", false);
            var params = {};
            if ($scope.couponCurrentPage > 0) {
                params.offset = $scope.couponCurrentPage * 10;
            } else {
                params.offset = 0;
            }
            params.limit = 10;
            $http({
                method: 'GET',
                url: Config.baseUrl + '/products/queryCoupon',
                params: params
            }).then(function (response) {
                if (response.data.count > 0) {
                    $scope.couponItem.pages = response.data.coupons;
                    $scope.couponItem.totalCoupons = response.data.count;
                    $scope.couponItem.numberOfPages = Math.ceil($scope.couponItem.totalCoupons / $scope.couponItem.itemsPerPage);
                    setTimeout(function () {
                        initStatusEditable()
                    }, 800);
                    $scope.setPageCoupon($scope.couponCurrentPage);
                } else {
                    $scope.couponItem.pages = [];
                    $scope.couponItem.totalCoupons = 0;
                    $scope.couponItem.numberOfPages = 0;
                }
            }, function (response) {
                throw response;
            });
        }

        // Load stores from last visit page after F5(reload page)
        if ($scope.storeCurrentPage > 0) {
            $scope.setStoreCurrentPage();
        } else {
            $scope.getStores();
        }

        $scope.bindSuggest = function () {
            var params = {};
            params['limit'] = 10;
            params['offset'] = 0;
            if ($scope.currentStore.name) {
                params['filter_name'] = $scope.currentStore.name;
                $http({
                    method: 'GET',
                    //url: Config.baseUrl + '/products/queryStore',
                    url: Config.baseUrl + '/products/suggestStore',
                    params: params
                }).then(function (response) {
                    if (response.data.stores) {
                        $scope.storeItem.suggestList = response.data.stores;
                    } else {
                        $scope.storeItem.suggestList = [];
                    }
                }, function (response) {
                    throw response;
                });
            }
        };

        $scope.checkboxToArray = function (value) {
            if (!$scope.currentStore.categories_id) {
                $scope.currentStore.categories_id = [];
            }
            var index = $.inArray(value, $scope.currentStore.categories_id);
            if (index == -1) {
                $scope.currentStore.categories_id.push(value);
            } else if (index != -1) {
                $scope.currentStore.categories_id.splice(index, 1);
            }
        };

        $scope.updateListCategories = function (arr) {
            $scope.currentStore.categories_id = [];
            for (var i = 0; i < arr.length; i++) {
                $scope.currentStore.categories_id.push(arr[i]);
            }
        };

        $scope.showDropdow = function () {
            $scope.storeItem.showListCategories = !$scope.storeItem.showListCategories;
        };

        $scope.change_alias = function (alias) {
            var str = alias;
            str = str.toLowerCase();
            str = str.replace(/Ă |Ă¡|áº¡|áº£|Ă£|Ă¢|áº§|áº¥|áº­|áº©|áº«|Äƒ|áº±|áº¯|áº·|áº³|áºµ/g, "a");
            str = str.replace(/Ă¨|Ă©|áº¹|áº»|áº½|Ăª|á»|áº¿|á»‡|á»ƒ|á»…/g, "e");
            str = str.replace(/Ă¬|Ă­|á»‹|á»‰|Ä©/g, "i");
            str = str.replace(/Ă²|Ă³|á»|á»|Ăµ|Ă´|á»“|á»‘|á»™|á»•|á»—|Æ¡|á»|á»›|á»£|á»Ÿ|á»¡/g, "o");
            str = str.replace(/Ă¹|Ăº|á»¥|á»§|Å©|Æ°|á»«|á»©|á»±|á»­|á»¯/g, "u");
            str = str.replace(/á»³|Ă½|á»µ|á»·|á»¹/g, "y");
            str = str.replace(/Ä‘/g, "d");
            str = str.replace(/!|`|\$|\\|\,|\{|\}|\||@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g, "-");
            /* tĂ¬m vĂ  thay tháº¿ cĂ¡c kĂ­ tá»± Ä‘áº·c biá»‡t trong chuá»—i sang kĂ­ tá»± - */
            str = str.replace(/-+-/g, "-"); //thay tháº¿ 2- thĂ nh 1-
            str = str.replace(/^\-+|\-+$/g, "");
            //cáº¯t bá» kĂ½ tá»± - á»Ÿ Ä‘áº§u vĂ  cuá»‘i chuá»—i
            return str;
        };

        $scope.editMostCoupon = function () {
            if ($scope.currentStore.alias) {
                $scope.currentStore.alias = $scope.currentStore.alias.replace(/ /g, '-');
                $scope.currentStore.alias = $scope.currentStore.alias.replace(/&/g, '-');
                $('span.alias').text($scope.currentStore.alias);
            }
        };

        $scope.generateMostCoupon = function () {
            var url = '';
            if ($scope.currentStore.name) {
                url += $scope.currentStore.name.replace(/ /g, '-');
                //url += '-coupons';
                url = $scope.change_alias(url);
            } else {
                url = '';
            }
            $scope.currentStore.alias = url;
            $scope.editMostCoupon();
        };

        $scope.addLocation = function (store) {
            if (!$scope.currentStore.related_id) {
                $scope.currentStore.related_id = [];
            }
            if (!$scope.currentStore.locations) {
                $scope.currentStore.locations = [];
            }
            var index = $.inArray(store.store.id, $scope.currentStore.related_id);
            if (index == -1) {
                $scope.currentStore.related_id.push(store.store.id);
                $scope.currentStore.locations.push(store);
            }
        };

        $scope.removeLocation = function (store) {
            if (!$scope.currentStore.related_id) {
                $scope.currentStore.related_id = [];
            }
            if (!$scope.currentStore.locations) {
                $scope.currentStore.locations = [];
            }
            var index = $.inArray(store.store.id, $scope.currentStore.related_id);
            if (index != -1) {
                $scope.currentStore.related_id.splice(index, 1);
                $scope.currentStore.locations.splice(index, 1);
            }
        };

        $scope.showAllStore = function () {
            $scope.storeItem.filter = null;
            $scope.storeItem.userFilter = '0';
            $('#dateStoreFilter').val('');
            $('#datePublishStoreFilter').val('');
            $scope.storeItem.statusFilter = '0';
            $scope.searchStore();
            $http.post(Config.baseUrl + '/products/deleteProductSessions', {'type': 'Stores'}).success(function (res) {
            })
        };

        $scope.searchStore = function () {
            $scope.storeItem.currentPageInc = 1;
            $scope.storeItem.currentPage = 0;
            $scope.filterStoreOptions.textFilter = $scope.storeItem.filter;
            $scope.filterStoreOptions.userFilter = $scope.storeItem.userFilter;
            if ($('#dateStoreFilter').val()) {
                $scope.filterStoreOptions.createdFromFilter = moment.tz($('#dateStoreFilter').val(), Config.timeZone).utc().format('YYYY-MM-DD');
            } else $scope.filterStoreOptions.createdFromFilter = null;
            if ($('#datePublishStoreFilter').val()) {
                $scope.filterStoreOptions.createdToFilter = moment.tz($('#datePublishStoreFilter').val(), Config.timeZone).utc().format('YYYY-MM-DD');
            } else $scope.filterStoreOptions.createdToFilter = null;
            $scope.filterStoreOptions.statusFilter = $scope.storeItem.statusFilter;
            $scope.setPageStore(1);
        };

        $scope.searchFollowNameOrURL = function (name) {
            $scope.storeItem.filter = name;
            $scope.storeItem.userFilter = null;
            $scope.storeItem.createdFromFilter = null;
            $scope.storeItem.createdToFilter = null;
            $scope.storeItem.statusFilter = null;
            $scope.searchStore();
            $scope.storeItem.isExistURL = false;
            $scope.storeItem.isExist = false;
            $scope.storeItem.showError = false;
            $scope.storeItem.showStorePopup = false;
            $scope.storeItem.existCouponURlStore = [];
            $scope.storeItem.existNameStore = [];
            $scope.storeItem.checkURLExist = false;
            $scope.storeItem.checkNotURLExist = false;
            $scope.storeItem.listURlExist = [];
            $timeout(function () {
                $('#cancelStore').click();
            });
        };

        $scope.sortByStore = function (field) {
            $scope.storeItem.currentPageInc = 1;
            $scope.storeItem.currentPage = 0;
            if ($scope.filterStoreOptions.sortField && $scope.filterStoreOptions.sortField == field) {
                $scope.filterStoreOptions.sortBy = !$scope.filterStoreOptions.sortBy;
            } else {
                $scope.filterStoreOptions.sortBy = true;
            }
            $scope.filterStoreOptions.sortField = field;
            $scope.setPageStore(1);
        };

        $scope.prevPageStore = function () {
            if ($scope.storeItem.currentPage > 0) {
                $scope.setPageStore($scope.storeItem.currentPage - 1);
            }
        };

        $scope.nextPageStore = function () {
            if ($scope.storeItem.currentPage < $scope.storeItem.numberOfPages - 1) {
                $scope.setPageStore($scope.storeItem.currentPage + 1);
            }
        };

        $scope.setPageStore = function (n) {
            $http.post(Config.baseUrl + '/products/setCurrentPage', {
                'currentPage': n,
                'type': 'Stores'
            }).success(function (res) {
            });

            $scope.storeItem.currentPage = n;
            $scope.storeItem.currentPageInc = $scope.storeItem.currentPage + 1;
            $scope.storeItem.currentPageInc = $scope.storeItem.currentPageInc > $scope.storeItem.numberOfPages ? $scope.storeItem.numberOfPages : $scope.storeItem.currentPageInc;
            $scope.storeItem.currentPageInc = $scope.storeItem.currentPageInc < 1 ? 1 : $scope.storeItem.currentPageInc;
            $scope.filterStoreOptions.offset = (n - 1) * $scope.storeItem.itemsPerPage;
            $scope.getStores();
            setTimeout(function () {
                $('html,body').animate({
                        scrollTop: $("#wid-store-list").offset().top
                    },
                    'slow');
            }, 600);
        };

        //$scope.setPageStore($scope.storeCurrentPage);

        $scope.changePageStore = function () {
            $scope.storeItem.currentPage = $scope.storeItem.currentPageInc - 1;
            $scope.storeItem.currentPage = $scope.storeItem.currentPage > $scope.storeItem.numberOfPages - 1 ? $scope.storeItem.numberOfPages - 1 : $scope.storeItem.currentPage;
            $scope.storeItem.currentPage = $scope.storeItem.currentPage < 0 ? 0 : $scope.storeItem.currentPage;
            $scope.setPageStore($scope.storeItem.currentPage);
        };

        $scope.deleteStore = function (id) {
            $http.post(Config.baseUrl + '/products/deleteStore/' + id).success(function (response) {
                if (response.status == true) {
                    if ($scope.storeItem.currentPage < $scope.storeItem.numberOfPages - 1) {
                        $scope.setPageStore($scope.storeItem.currentPage);
                    } else if (0 < $scope.storeItem.currentPage) {
                        $scope.setPageStore($scope.storeItem.currentPage - 1);
                    } else $scope.setPageStore(1);
                } else alert(response.msg);
            });
        };

        $scope.deleteStores = function () {
            var data = {};
            data.ids = [];
            $('#store-table .check_element').each(function () {
                if ($(this).is(':checked')) {
                    var id = $(this).attr('id');
                    if (id) {
                        for (var i = 0; i < $scope.storeItem.pages.length; i++) {
                            if ($scope.storeItem.pages[i].store.id == id) {
                                if ($scope.storeItem.pages[i].store.status == 'trash') {
                                    data.ids.push(id);
                                }
                                break;
                            }
                        }
                    }
                }
            });
            if (data.ids.length > 0) {
                bootbox.confirm("Are you sure?", function (result) {
                    if (!result) return;
                    $http.post(Config.baseUrl + '/products/deleteStores', data).success(function (response) {
                        if (response.status == true) {
                            if ($scope.storeItem.currentPage < $scope.storeItem.numberOfPages - 1) {
                                $scope.setPageStore($scope.storeItem.currentPage);
                            } else if (0 < $scope.storeItem.currentPage) {
                                $scope.setPageStore($scope.storeItem.currentPage - 1);
                            } else $scope.setPageStore(1);
                        }
                    });
                });
            } else bootbox.alert('Please choose at least a store!')
        };

        $scope.setStatusStore = function (id, status, index) {
            var data = {
                id: id,
                status: status
            };
            $http.post(Config.baseUrl + '/products/saveStore', data).success(function (response) {
                if (response.status == true) {
                    $scope.storeItem.pages[index].store.status = status;
                }
            });
        };

        $scope.editStore = function (store, index) {
            $scope.incentiveAdd = true;
            $scope.storeItem.suggestList = [];
            $('#saveStore').text('Update');
            $('#modal-label-add-store').text('Update Store');
            $scope.storeItem.checkNameExist = false;
            $scope.storeItem.checkNotNameExist = false;
            $scope.storeItem.checkStoreURLExist = false;
            $scope.storeItem.checkNotStoreURLExist = false;
            $scope.currentStore = angular.copy(store.store);
            $scope.currentStore.author = angular.copy(store.author);
            $scope.currentStore.vendors = angular.copy(store.vendors);
            $scope.storeItem.copyOldStoreItem = angular.copy($scope.currentStore);
            $scope.storeItem.addStoreMode = false;
            $scope.storeItem.showStorePopup = true;
            $scope.storeItem.existCouponURlStore = [];
            $scope.storeItem.existNameStore = [];
            $scope.storeItem.listURlExist = [];
            $scope.storeItem.checkURLExist = false;
            $scope.storeItem.checkNotURLExist = false;
            $scope.storeItem.editIndex = index;
            $('#listCategories').select2('val', $scope.currentStore.categories_id);
            $('#listCountries').select2('val', $scope.currentStore.countries_code);
            $scope.bindSuggest();
            if ($scope.currentStore.publish_date) {
                $scope.currentStore.publish_date = moment.utc($scope.currentStore.publish_date).tz(Config.timeZone).format('ll LT');
            }
            if ($scope.currentStore.tags) {
                $('.store-tags').select2('val', $scope.currentStore.tags.split(","));
            } else {
                $('.store-tags').select2('val', '');
            }
            if ($scope.currentStore.logo) {
                $('.store-logo div.fileinput-preview.thumbnail').html("<img src='" + $scope.currentStore.logo + "' />");
            }
            if ($scope.currentStore.social_image) {
                $('.store-social-image div.fileinput-preview.thumbnail').html("<img src='" + $scope.currentStore.social_image + "' />");
            }
            //$('.fileinput').fileinput('clear');
        };

        $scope.checkNameExists = function () {
            $http.post(Config.baseUrl + '/products/checkExistsStore',
                {name: $scope.currentStore.name, id: $scope.currentStore.id})
                .success(function (response) {
                    if (response.existName == true) {
                        $scope.storeItem.checkNameExist = true;
                        $scope.storeItem.existNameStore = response.existNameStore;
                        $scope.storeItem.checkNotNameExist = false;
                    } else {
                        $scope.storeItem.checkNameExist = false;
                        $scope.storeItem.checkNotNameExist = true;
                        $scope.storeItem.existNameStore = [];
                    }
                    $scope.checkStoreURLExists();
                    $scope.bindSuggest();
                });
        };

        $scope.checkStoreURLExists = function () {
            $http.post(Config.baseUrl + '/products/checkExistsStore',
                {alias: $scope.currentStore.alias, id: $scope.currentStore.id})
                .success(function (response) {
                    if (response.existCouponURl == true) {
                        $scope.storeItem.checkStoreURLExist = true;
                        $scope.storeItem.checkNotStoreURLExist = false;
                        $scope.storeItem.existCouponURlStore = response.existCouponURlStore;
                    } else {
                        $scope.storeItem.checkStoreURLExist = false;
                        $scope.storeItem.checkNotStoreURLExist = true;
                        $scope.storeItem.existCouponURlStore = [];
                    }
                });
        };

        $scope.checkURLExists = function () {
            $http.post(Config.baseUrl + '/products/checkExistsStore',
                {store_url: $scope.currentStore.store_url, id: $scope.currentStore.id})
                .success(function (response) {
                    if (response.existStoreURL == true) {
                        $scope.storeItem.checkURLExist = true;
                        $scope.storeItem.checkNotURLExist = false;
                        $scope.storeItem.listURlExist = response.listStoreURL;
                    } else {
                        $scope.storeItem.checkURLExist = false;
                        $scope.storeItem.checkNotURLExist = true;
                        $scope.storeItem.listURlExist = [];
                    }
                });
        };

        $scope.saveStore = function ($status) {
            var formValidation = $('.addStoreForm');
            formValidation.validate();
            if (!formValidation.valid()) return;
            //var img_vaild = false;
            //if ($('.store-logo').find('img').length == 0) {
            //    $('.store-logo').addClass('has-error');
            //    img_vaild = true;
            //}
            //if ($('.store-social-image').find('img').length == 0) {
            //    $('.store-social-image').addClass('has-error');
            //    img_vaild = true;
            //}
            //if (img_vaild) return;
            $http.post(Config.baseUrl + '/products/checkExistsStore',
                {
                    name: $scope.currentStore.name,
                    alias: $scope.currentStore.alias,
                    id: $scope.currentStore.id
                })
                .success(function (response) {
                    if (response.existName == true || response.existCouponURl == true) {
                        if (response.existName == true) {
                            $scope.storeItem.isExist = true;
                        }
                        if (response.existCouponURl == true) {
                            $scope.storeItem.isExistURL = true;
                        }
                        if (response.existStoreURL == true) {
                            $scope.storeItem.isExistStoreURL = true;
                        }

                        $scope.storeItem.showError = true;
                        return;
                    }
                    $scope.storeItem.isExistStoreURL = false;
                    $scope.storeItem.isExistURL = false;
                    $scope.storeItem.isExist = false;
                    $scope.storeItem.showError = false;
                    $scope.storeItem.showStorePopup = false;
                    $scope.storeItem.existCouponURlStore = [];
                    $scope.storeItem.existNameStore = [];
                    $scope.storeItem.checkURLExist = false;
                    $scope.storeItem.checkNotURLExist = false;
                    $scope.storeItem.listURlExist = [];
                    var dataSave = angular.copy($scope.currentStore);
                    $scope.currentStore = {};
                    if ($.isEmptyObject(dataSave)) return;
                    if ($status) {
                        dataSave.status = $status;
                    }
                    if ($('#store-publish-date').val()) {
                        dataSave.publish_date = moment.tz($('#store-publish-date').val(), Config.timeZone).utc().format('YYYY-MM-DD HH:mm');//moment(dataSave.publish_date).format('YYYY/MM/DD');
                    } else dataSave.publish_date = null;
                    dataSave.tags = $('.store-tags').select2('val').toString();
                    dataSave.countries_code = $('#listCountries').select2('val');
                    dataSave.categories_id = $('#listCategories').select2('val');
                    dataSave.alias = $('#store_alias').val();
                    $http.post(Config.baseUrl + '/products/saveStore', dataSave).success(function (response) {
                        if (response.status == true) {
                            $http({
                                method: 'GET',
                                url: Config.baseUrl + '/products/deleterac',
                                params: {"type": "store"}
                            })
                                .then(function (dataRes) {
                                    if (dataSave.id) {
                                        $scope.storeItem.pages[$scope.storeItem.editIndex] = angular.copy(response.store);
                                    } else {
                                        $scope.storeItem.filter = null;
                                        $scope.storeItem.userFilter = null;
                                        $scope.storeItem.createdToFilter = null;
                                        $scope.storeItem.createdFromFilter = null;
                                        $scope.storeItem.statusFilter = null;
                                        $scope.filterStoreOptions.textFilter = $scope.storeItem.filter;
                                        $scope.filterStoreOptions.userFilter = $scope.storeItem.userFilter;
                                        $scope.filterStoreOptions.createdFromFilter = $scope.storeItem.createdFromFilter;
                                        $scope.filterStoreOptions.createdToFilter = $scope.storeItem.createdToFilter;
                                        $scope.filterStoreOptions.statusFilter = $scope.storeItem.statusFilter;
                                        $scope.filterStoreOptions.sortBy = false;
                                        $scope.filterStoreOptions.sortField = 'created';
                                        $scope.setPageStore(1);
                                    }
                                    setTimeout(function () {
                                        initStatusEditable();
                                    }, 500);
                                    $scope.imgLoading = false;
                                    $timeout(function () {
                                        $('#saveStore').trigger('reset');
                                    });
                                    $timeout(function () {
                                        $('#cancelStore').click();
                                    });
                                }, function (ex) {
                                    $timeout(function () {
                                        $('#saveStore').trigger('reset');
                                    });
                                    $timeout(function () {
                                        $('#cancelStore').click();
                                    });
                                    throw ex;
                                });
                            $scope.checkStatusStores();
                        } else {
                            $scope.imgLoading = false;
                            $timeout(function () {
                                $('#saveStore').trigger('reset');
                            });
                            $timeout(function () {
                                $('#cancelStore').click();
                            });
                        }
                    });
                });
        };

        $scope.initDefaultDeal = function () {
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

        $scope.addDeal = function (store) {
            $scope.incentiveAdd = true;
            $('#saveDeal').text('Add');
            $('#modal-label-add-deal').text('Add New Deal');
            $scope.newDeal = {};
            $scope.storeItem.stores = [];
            $scope.incentiveAdd = true;
            $scope.newDeal.start_date = moment.utc($scope.getDefaultDate()).format('ll');
            $scope.newDeal.expire_date = moment.utc($scope.getDefaultDate()).format('ll');
            $scope.initDefaultDeal();
            $scope.newDeal.storeName = store.store.name;
            $scope.storeItem.listcategories = store.store.categories;
            $scope.newDeal.store_id = store.store.id;
            $scope.storeItem.stores[0] = store;
            $scope.storeItem.addDealMode = true;
            $scope.getFromDbDealDraft();
            $scope.storeItem.showDealDraft = true;
            $scope.storeItem.copyOldDealItem = angular.copy($scope.newDeal);
            $scope.storeItem.showDealPopup = true;
            $('.deal-tags').select2('val', '');
            $('#newDealCategory').select2('val', '');

            var pos_us = store.store.countries_code.indexOf("US");
            if (pos_us != -1) {
                store.store.countries_code.splice(pos_us, 1)
            }
            for (var i = 0; i < store.store.countries_code.length; i++) {
                var data = {
                    countrycode: store.store.countries_code[i],
                    id: null,
                    table_name: "deal"
                };
                $scope.newCoupon.vendors.push(data);
            }
        };

        $scope.saveDeal = function ($status) {
            var formValidation = $('.addDealForm');
            formValidation.validate();
            if (!formValidation.valid()) return;
            if ($('.deal-image').find('img').length == 0) {
                $('.deal-image').addClass('has-error');
                return;
            }
            $scope.storeItem.showError = false;
            $scope.storeItem.showDealPopup = false;
            if(!$scope.newDeal.deal_image){
                alert('Please upload deal image');
                return false;
            }

            var dataSave = angular.copy($scope.newDeal);
            $scope.newDeal = {};
            if ($.isEmptyObject(dataSave)) return;
            if ($status) {
                dataSave.status = $status;
            }
            if ($('#deal-start-date').val()) {
                dataSave.start_date = moment.tz($('#deal-start-date').val(), Config.timeZone).utc().format('YYYY-MM-DD HH:mm');//moment(dataSave.publish_date).format('YYYY/MM/DD');
            } else dataSave.start_date = null;
            if ($('#deal-expire-date').val()) {
                dataSave.expire_date = moment.tz($('#deal-expire-date').val(), Config.timeZone).utc().format('YYYY-MM-DD HH:mm');//moment(dataSave.publish_date).format('YYYY/MM/DD');
            } else dataSave.expire_date = null;
            if ($('#deal-publish-date').val()) {
                dataSave.publish_date = moment.tz($('#deal-publish-date').val(), Config.timeZone).utc().format('YYYY-MM-DD HH:mm');//moment(dataSave.publish_date).format('YYYY/MM/DD');
            } else dataSave.publish_date = null;
            dataSave.deal_tag = $('.deal-tags').select2('val').toString();
            dataSave.origin_price = $('#originPriceDeal').autoNumeric('get');
            dataSave.discount_price = $('#realPriceDeal').autoNumeric('get');
            dataSave.discount_percent = $('#discountPercentDeal').autoNumeric('get');
            $http.post(Config.baseUrl + '/deals/saveDeal', dataSave).success(function (response) {
                if (response.status == true) {
                    $http({method: 'GET', url: Config.baseUrl + '/products/deleterac', params: {"type": "deal"}})
                        .then(function (dataRes) {
                            if ($scope.newDeal.id) {
                                $scope.dealItem.pages[$scope.storeItem.editDeal] = response.deal;
                            } else {
                                $scope.dealItem.filter = null;
                                $scope.dealItem.userFilter = null;
                                $scope.dealItem.createdFromFilter = null;
                                $scope.dealItem.createdToFilter = null;
                                $scope.dealItem.statusFilter = null;
                                $scope.filterDealOptions.textFilter = $scope.dealItem.filter;
                                $scope.filterDealOptions.userDealFilter = $scope.dealItem.userFilter;
                                $scope.filterDealOptions.createdFromFilter = $scope.dealItem.createdFromFilter;
                                $scope.filterDealOptions.createdToFilter = $scope.dealItem.createdToFilter;
                                $scope.filterDealOptions.statusFilter = $scope.dealItem.statusFilter;
                                $scope.filterDealOptions.sortField = 'created';
                                $scope.filterDealOptions.sortBy = false;
                                $scope.setPageDeal($scope.dealItem.currentPage);
                            }
                            setTimeout(function () {
                                initStatusEditable();
                            }, 500);
                            $timeout(function () {
                                $('#saveDeal').trigger('reset');
                            });
                            $timeout(function () {
                                $('#cancelDeal').click();
                            });
                        }, function (ex) {
                            $timeout(function () {
                                $('#saveDeal').trigger('reset');
                            });
                            $timeout(function () {
                                $('#cancelDeal').click();
                            });
                            throw ex;
                        });
                    $scope.checkStatusDeals();
                } else {
                    $timeout(function () {
                        $('#saveDeal').trigger('reset');
                    });
                    $timeout(function () {
                        $('#cancelDeal').click();
                    });
                }
            });
        };

        $scope.initTitleDescription = function () {
            $scope.title_coupons = [{key: "title_store", value: ""}];
            $scope.description_coupons = [{key: "description_store", value: ""}];
        };

        $scope.showOptionTitle = function (key, selected) {
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

        $scope.showAddTitle = function () {
            return $scope.keys_title_coupons.length > $scope.title_coupons.length;
        };

        $scope.addTitle = function () {
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

        $scope.removeTitle = function (index) {
            if (confirm('Are you sure want to remove this line ?'))
                $scope.title_coupons.splice(index, 1);
        };

        $scope.showOptionDescription = function (key, selected) {
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

        $scope.showAddDescription = function () {
            return $scope.keys_description_coupons.length > $scope.description_coupons.length;
        };

        $scope.addDescription = function () {
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

        $scope.removeDescription = function (index) {
            if (confirm('Are you sure want to remove this line ?'))
                $scope.description_coupons.splice(index, 1);
        };

        $scope.initCoupon = function () {
            $scope.initTitleDescription();
            $scope.newCoupon.expire_date = $scope.getDefaultDate();
            if (!$scope.newCoupon.exclusive) {
                $scope.newCoupon.exclusive = 0;
            }
            if (!$scope.newCoupon.verified) {
                $scope.newCoupon.verified = 0;
            }
            if (!$scope.newCoupon.sticky) {
                $scope.newCoupon.sticky = 'none';
            }
            if (!$scope.newCoupon.coupon_type) {
                $scope.newCoupon.coupon_type = 'Coupon Code';
            }
            if (!$scope.newCoupon.currency) {
                $scope.newCoupon.currency = '$';
            }
            if (!$scope.newCoupon.event) {
                $scope.newCoupon.event = 'None';
            }
        };

        $scope.addCoupon = function (store) {
            $scope.incentiveAdd = true;
            $scope.newCoupon = {};
            $scope.initCoupon();
            $scope.newCoupon.store_id = store.store.id;
            $scope.newCoupon.storeName = store.store.name;
            $scope.storeItem.listcategories = store.store.categories;
            $('#newCouponCategory').select2('val', '');
            $scope.storeItem.addCouponMode = true;
            $scope.getFromDbCouponDraft();
            $scope.storeItem.showCouponDraft = true;
            $scope.storeItem.copyOldCouponItem = angular.copy($scope.newCoupon);
            $scope.storeItem.showCouponPopup = true;
            if ($scope.newCoupon.publish_date) {
                $scope.newCoupon.publish_date = moment.utc($scope.newCoupon.publish_date).tz(Config.timeZone).format('ll LT');
            }
            if ($scope.newCoupon.expire_date) {
                $scope.newCoupon.expire_date = moment.utc($scope.newCoupon.expire_date).tz(Config.timeZone).format('ll LT');
            }
            $('.coupon-tags').select2('val', '');

            var pos_us = store.countries_code.indexOf("US");
            if (pos_us != -1) {
                store.countries_code.splice(pos_us, 1)
            }
            for (var i = 0; i < store.countries_code.length; i++) {
                var data = {
                    countrycode: store.countries_code[i],
                    id: null,
                    table_name: "coupon"
                };
                $scope.newCoupon.vendors.push(data);
            }
        };


        $scope.getCouponParams = function () {
            var params = {};
            params['limit'] = $scope.filterCouponOptions.limit;
            params['offset'] = $scope.filterCouponOptions.offset;
            if ($scope.filterCouponOptions.textFilter) {
                params['filter_name'] = $scope.filterCouponOptions.textFilter;
            }
            if ($scope.filterCouponOptions.userFilter) {
                params['user_id'] = $scope.filterCouponOptions.userFilter;
            }
            if ($scope.filterCouponOptions.createdFromFilter) {
                params['created_from'] = $scope.filterCouponOptions.createdFromFilter;
            }
            if ($scope.filterCouponOptions.createdToFilter) {
                params['created_to'] = $scope.filterCouponOptions.createdToFilter;
            }
            if ($scope.filterCouponOptions.statusFilter) {
                params['status'] = $scope.filterCouponOptions.statusFilter;
            }
            if ($scope.filterCouponOptions.sortField) {
                params['sort_field'] = $scope.filterCouponOptions.sortField;
                if ($scope.filterCouponOptions.sortBy) {
                    params['sort_by'] = 'ASC';
                } else {
                    params['sort_by'] = 'DESC';
                }
            } else {
                $scope.filterCouponOptions.sortField = 'created';

                $scope.filterCouponOptions.sortBy = false;
            }
            return params;
        };

        $scope.getCoupons = function () {
            $('#coupon-table .check_all').prop("checked", false);
            var params = $scope.getCouponParams();
            $http({
                method: 'GET',
                url: Config.baseUrl + '/products/queryCoupon',
                params: params
            }).then(function (response) {
                if (response.data.count > 0) {
                    $scope.couponItem.pages = response.data.coupons;
                    $scope.couponItem.totalCoupons = response.data.count;
                    $scope.couponItem.numberOfPages = Math.ceil($scope.couponItem.totalCoupons / $scope.couponItem.itemsPerPage);
                    setTimeout(function () {
                        initStatusEditable()
                    }, 800);
                } else {
                    $scope.couponItem.pages = [];
                    $scope.couponItem.totalCoupons = 0;
                    $scope.couponItem.numberOfPages = 0;
                }
            }, function (response) {
                throw response;
            });
        };
        // Load coupon from last visit page after F5(reload page)
        if ($scope.couponCurrentPage > 0) {
            $scope.setCouponCurrentPage();
        } else {
            $scope.getCoupons();
        }
        //$scope.getCoupons();

        $scope.searchCoupon = function () {
            $scope.couponItem.currentPageInc = 1;
            $scope.couponItem.currentPage = 0;
            $scope.filterCouponOptions.textFilter = $scope.couponItem.filter;
            $scope.filterCouponOptions.userFilter = $scope.couponItem.userFilter;
            if ($('#dateCouponFilter').val()) {
                $scope.filterCouponOptions.createdFromFilter = moment.tz($('#dateCouponFilter').val(), Config.timeZone).utc().format('YYYY-MM-DD');
            } else $scope.filterCouponOptions.createdFromFilter = null;
            if ($('#datePublishCouponFilter').val()) {
                $scope.filterCouponOptions.createdToFilter = moment.tz($('#datePublishCouponFilter').val(), Config.timeZone).utc().format('YYYY-MM-DD');
            } else $scope.filterCouponOptions.createdToFilter = null;
            $scope.filterCouponOptions.statusFilter = $scope.couponItem.statusFilter;
            $scope.setPageCoupon(1);
        };

        $scope.showAllCoupon = function () {
            $scope.couponItem.filter = null;
            $scope.couponItem.statusFilter = '0';
            $scope.couponItem.userFilter = '0';
            $('#dateCouponFilter').val('');
            $('#datePublishCouponFilter').val('');
            $scope.searchCoupon();
            $http.post(Config.baseUrl + '/products/deleteProductSessions', {'type': 'Coupons'}).success(function (res) {
            })
        };

        $scope.sortByCoupon = function (field) {
            $scope.couponItem.currentPageInc = 1;
            $scope.couponItem.currentPage = 0;
            if ($scope.filterCouponOptions.sortField && $scope.filterCouponOptions.sortField == field) {
                $scope.filterCouponOptions.sortBy = !$scope.filterCouponOptions.sortBy;
            } else {
                $scope.filterCouponOptions.sortBy = true;
            }
            $scope.filterCouponOptions.sortField = field;
            $scope.setPageCoupon(1);
        };

        $scope.prevPageCoupon = function () {
            if ($scope.couponItem.currentPage > 0) {
                $scope.setPage($scope.couponItem.currentPage - 1);
            }
        };

        $scope.nextPageCoupon = function () {
            if ($scope.couponItem.currentPage < $scope.couponItem.numberOfPages - 1) {
                $scope.setPageCoupon($scope.couponItem.currentPage + 1);
            }
        };

        $scope.setPageCoupon = function (n) {
            $http.post(Config.baseUrl + '/products/setCurrentPage', {
                'currentPage': n,
                'type': 'Coupons'
            }).success(function (res) {
            });

            $scope.couponItem.currentPage = n;
            $scope.couponItem.currentPageInc = $scope.couponItem.currentPage + 1;
            $scope.couponItem.currentPageInc = $scope.couponItem.currentPageInc > $scope.couponItem.numberOfPages ? $scope.couponItem.numberOfPages : $scope.couponItem.currentPageInc;
            $scope.couponItem.currentPageInc = $scope.couponItem.currentPageInc < 1 ? 1 : $scope.couponItem.currentPageInc;
            $scope.filterCouponOptions.offset = (n - 1) * $scope.couponItem.itemsPerPage;
            $scope.getCoupons();
            setTimeout(function () {
                $('html,body').animate({
                        scrollTop: $("#wid-coupon-list").offset().top
                    },
                    'slow');
            }, 600);
        };

        $scope.changePageCoupon = function () {
            $scope.couponItem.currentPage = $scope.couponItem.currentPageInc - 1;
            $scope.couponItem.currentPage = $scope.couponItem.currentPage > $scope.couponItem.numberOfPages - 1 ? $scope.couponItem.numberOfPages - 1 : $scope.couponItem.currentPage;
            $scope.couponItem.currentPage = $scope.couponItem.currentPage < 0 ? 0 : $scope.couponItem.currentPage;
            $scope.setPageCoupon($scope.couponItem.currentPage);
        };

        $scope.deleteCoupon = function (id) {
            $http.post(Config.baseUrl + '/products/deleteCoupon/' + id).success(function (response) {
                if (response.status == true) {
                    if ($scope.couponItem.currentPage < $scope.couponItem.numberOfPages - 1) {
                        $scope.setPageCoupon($scope.couponItem.currentPage);
                    } else if (0 < $scope.couponItem.currentPage) {
                        $scope.setPageCoupon($scope.couponItem.currentPage - 1);
                    } else $scope.setPageCoupon(1);
                }
            });
        };

        $scope.setStatusCoupon = function ($id, status, index) {
            var data = {
                id: $id,
                status: status
            };
            $http.post(Config.baseUrl + '/products/addCoupon', data).success(function (response) {
                if (response.status == true) {
                    $scope.couponItem.pages[index].coupon.status = status;
                }
            });
        };

        $scope.editCoupon = function (coupon, index) {
            $scope.storeItem.editCoupon = index;
            $scope.storeItem.addCouponMode = false;
            $('#saveCoupon').text('Update');
            $('#modal-label-add-coupon').text('Edit Coupon');

            $scope.getFromDbCouponDraft();
            $scope.storeItem.showCouponDraft = true;
            $scope.storeItem.showCouponPopup = true;
            $scope.incentiveAdd = true;
            $scope.newCoupon = angular.copy(coupon.coupon);
            $scope.newCoupon.author = angular.copy(coupon.author);
            $scope.newCoupon.vendors = angular.copy(coupon.vendors);

            var pos_us = coupon.store.countries_code.indexOf("US");
            if (pos_us != -1) {
                coupon.store.countries_code.splice(pos_us, 1)
            }
            var has_country = false;
            for (var i = 0; i < coupon.store.countries_code.length; i++) {
                has_country = false;
                for (var j = 0; j < coupon.vendors.length; j++) {
                    if (coupon.store.countries_code[i] == coupon.vendors[j].countrycode) {
                        has_country = true;
                        break;
                    }
                }
                if (!has_country) {
                    var data = {
                        countrycode: coupon.store.countries_code[i],
                        id: null,
                        parent_id: $scope.newCoupon.id,
                        table_name: "coupon"
                    };
                    $scope.newCoupon.vendors.push(data);
                }
            }

            if (!$scope.newCoupon.sticky) {
                $scope.newCoupon.sticky = 'none';
            }
            $scope.newCoupon.store_id = coupon.store.id;
            $scope.storeItem.copyOldCouponItem = angular.copy($scope.newCoupon);
            $scope.stores = [];
            $scope.storeItem.listcategories = coupon.store.categories;
            $scope.newCoupon.storeName = coupon.store.name;
            //$scope.bindStore($scope.newCoupon.store_id);
            if ($scope.newCoupon.publish_date) {
                $scope.newCoupon.publish_date = moment.utc($scope.newCoupon.publish_date).tz(Config.timeZone).format('ll LT');
            }
            if ($scope.newCoupon.expire_date) {
                $scope.newCoupon.expire_date = moment.utc($scope.newCoupon.expire_date).tz(Config.timeZone).format('ll LT');
            }
            if ($scope.newCoupon.tags) {
                $('.coupon-tags').select2('val', $scope.newCoupon.tags.split(","));
            } else {
                $('.coupon-tags').select2('val', '');
            }
            if ($scope.newCoupon.categories_id) {
                setTimeout(function () {
                    $('#newCouponCategory').select2('val', $scope.newCoupon.categories_id);
                }, 500);
            } else {
                $('#newCouponCategory').select2('val', '');
            }
            if ($scope.newCoupon.coupon_image) {
                $('.coupon-image div.fileinput-preview.thumbnail').append("<img src='" + $scope.newCoupon.coupon_image + "' />");
            }
            if ($scope.newCoupon.social_image) {
                $('.coupon-socical-image div.fileinput-preview.thumbnail').append("<img src='" + $scope.newCoupon.social_image + "' />");
            }
        };

        $scope.saveCoupon = function ($status) {
            var cur = $scope.newCoupon.coupon_type;
            if (cur == 'Coupon Code') {
                $('.coupon-discount').attr('placeholder', 'Discount *').rules('add', {
                    required: true
                });
                $('.coupon-code').parent().show();
            } else if (cur == 'Free Shipping') {
                $('.coupon-discount').attr('placeholder', 'Discount').removeClass('required').rules('remove', 'required');
                $('.coupon-code').parent().show();
            } else if (cur == 'Great Offer') {
                $('.coupon-discount').attr('placeholder', 'Discount *').rules('add', {
                    required: true
                });
                $('.coupon-code').parent().hide();
            }
            var formValidation = $('.addCouponForm');
            formValidation.validate();
            if (!formValidation.valid()) return;

            var dataSave = angular.copy($scope.newCoupon);
            $scope.newCoupon = {};
            if ($.isEmptyObject(dataSave)) return;
            if (dataSave) {
                if ($('#coupon-expire-date').val()) {
                    dataSave.expire_date = moment.tz($('#coupon-expire-date').val(), Config.timeZone).utc().format('YYYY-MM-DD HH:mm');//moment(dataSave.publish_date).format('YYYY/MM/DD');
                } else dataSave.expire_date = null;
                if ($('#coupon-publish-date').val()) {
                    dataSave.publish_date = moment.tz($('#coupon-publish-date').val(), Config.timeZone).utc().format('YYYY-MM-DD HH:mm');//moment(dataSave.publish_date).format('YYYY/MM/DD');
                } else dataSave.publish_date = null;
                dataSave.tags = $('.coupon-tags').select2('val').toString();
                dataSave.categories_id = $('#newCouponCategory').select2('val');
                if ($status) {
                    dataSave.status = $status;
                }
                if (!$scope.arrayContains(dataSave.coupon_type, ['Coupon Code', 'Free Shipping'])) {
                    dataSave.coupon_code = '';
                }
                $scope.storeItem.showError = false;
                $scope.storeItem.showCouponPopup = false;
                for (var j = 0; j < $scope.title_coupons.length; j++) {
                    dataSave[$scope.title_coupons[j].key] = $scope.title_coupons[j].value;
                }
                for (var j = 0; j < $scope.description_coupons.length; j++) {
                    dataSave[$scope.description_coupons[j].key] = $scope.description_coupons[j].value;
                }
                $http.post(Config.baseUrl + '/products/addCoupon', dataSave).success(function (response) {
                    if (response.status == true) {
                        $http({method: 'GET', url: Config.baseUrl + '/products/deleterac', params: {"type": "coupon"}})
                            .then(function (dataRes) {
                                $scope.imgLoading = false;
                                if (response.status == true) {
                                    if (dataSave.id) {
                                        $scope.couponItem.pages[$scope.storeItem.editCoupon] = angular.copy(response.coupon);
                                    } else {
                                        $scope.couponItem.filter = null;
                                        $scope.couponItem.statusFilter = null;
                                        $scope.couponItem.userFilter = null;
                                        $scope.couponItem.createdFromFilter = null;
                                        $scope.couponItem.createdToFilter = null;
                                        $scope.filterCouponOptions.textFilter = $scope.couponItem.filter;
                                        $scope.filterCouponOptions.userFilter = $scope.couponItem.userFilter;
                                        $scope.filterCouponOptions.createdFromFilter = $scope.couponItem.createdFromFilter;
                                        $scope.filterCouponOptions.createdToFilter = $scope.couponItem.createdToFilter;
                                        $scope.filterCouponOptions.statusFilter = false;
                                        $scope.filterCouponOptions.sortField = 'created';
                                        $scope.setPageCoupon(1);
                                    }
                                }
                                setTimeout(function () {
                                    initStatusEditable();
                                }, 500);
                                $timeout(function () {
                                    $('#saveCoupon').trigger('reset');
                                });
                                $timeout(function () {
                                    $('#cancelCoupon').click();

                                });
                            }, function (ex) {
                                $scope.imgLoading = false;
                                $timeout(function () {
                                    $('#saveCoupon').trigger('reset');
                                });
                                $timeout(function () {
                                    $('#cancelCoupon').click();
                                });
                                throw ex;
                            });
                        $scope.checkStatusCoupons();
                    } else {
                        $scope.imgLoading = false;
                        $timeout(function () {
                            $('#saveCoupon').trigger('reset');
                        });
                        $timeout(function () {
                            $('#cancelCoupon').click();
                        });
                    }

                });
            }
        };

        $scope.bindStoresDeal = function (category_id) {
            $scope.dealItem.loadingStores = true;
            var params = {};
            params['fields'] = ['store.id', 'store.name', 'store.categories_id'];
            params['categories_id'] = category_id;
            $http({
                method: 'GET',
                url: Config.baseUrl + '/products/queryStore',
                params: params
            }).then(function (response) {
                if (response.data.count > 0) {
                    $scope.storeItem.stores = response.data.stores;
                    if ($scope.newDeal.store_id) {
                        if ($('.select2-chosen').length > 0) {
                            var selecStore = from($scope.storeItem.stores).where('$.store.id == "' + $scope.newDeal.store_id + '"').toArray();
                            if (selecStore && selecStore.length > 0) {
                                $('.select2-chosen').text(selecStore[0].store.name);
                            } else {
                                $('.select2-chosen').text('');
                            }
                        }
                    }
                } else {
                    $scope.storeItem.stores = [];
                }
                $scope.dealItem.loadingStores = false;
            }, function (response) {
                $scope.dealItem.loadingStores = false;
                throw response;
            });
        };

        $scope.bindStore = function (store_id) {
            var params = {};
            params['id'] = store_id;
            $http({
                method: 'GET',
                url: Config.baseUrl + '/products/queryStore',
                params: params
            }).then(function (response) {
                if (response.data.count > 0) {
                    $scope.storeItem.stores = response.data.stores;
                    var store = $scope.storeItem.stores[0];
                    $scope.storeItem.listcategories = store.store.categories;
                    $scope.newCoupon.storeName = store.store.name;
                } else {
                    $scope.storeItem.stores = [];
                    $scope.storeItem.listcategories = [];

                }
            }, function (response) {
                throw response;
            });
        };

        $scope.editDeal = function (deal, index) {
            $scope.incentiveAdd = true;
            $scope.storeItem.addDealMode = false;
            $('#saveDeal').text('Update');
            $('#modal-label-add-deal').text('Edit Deal');
            $scope.newDeal = angular.copy(deal.deal);
            $scope.newDeal.author = angular.copy(deal.author);
            $scope.storeItem.showDealPopup = true;
            $scope.dealItem.loadingStores = true;
            $scope.storeItem.showDealDraft = false;
            $scope.newDeal.storeName = deal.store.name;
            $scope.storeItem.listcategories = deal.store.categories;
            //$scope.bindStore($scope.newDeal.store_id);
            //$scope.bindStoresDeal($scope.newDeal.category_id);
            $scope.storeItem.copyOldDealItem = angular.copy($scope.newDeal);

            $scope.newDeal.vendors = angular.copy(deal.vendors);
            var pos_us = deal.store.countries_code.indexOf("US");
            if (pos_us != -1) {
                deal.store.countries_code.splice(pos_us, 1)
            }
            var has_country = false;
            for (var i = 0; i < deal.store.countries_code.length; i++) {
                has_country = false;
                for (var j = 0; j < deal.vendors.length; j++) {
                    if (deal.store.countries_code[i] == deal.vendors[j].countrycode) {
                        has_country = true;
                        break;
                    }
                }
                if (!has_country) {
                    var data = {
                        countrycode: deal.store.countries_code[i],
                        id: null,
                        parent_id: $scope.newDeal.id,
                        table_name: "deal"
                    };
                    $scope.newDeal.vendors.push(data);
                }
            }

            $scope.storeItem.editDeal = index;
            if ($scope.newDeal.start_date) {
                $scope.newDeal.start_date = moment.utc($scope.newDeal.start_date).tz(Config.timeZone).format('ll LT');
            }
            if ($scope.newDeal.expire_date) {
                $scope.newDeal.expire_date = moment.utc($scope.newDeal.expire_date).tz(Config.timeZone).format('ll LT');
            }
            if ($scope.newDeal.publish_date) {
                $scope.newDeal.publish_date = moment.utc($scope.newDeal.publish_date).tz(Config.timeZone).format('ll LT');
            }
            if ($scope.newDeal.deal_tag) {
                $('.deal-tags').select2('val', $scope.newDeal.deal_tag.split(","));
            } else {
                $('.deal-tags').select2('val', '');
            }
            if ($scope.newDeal.categories_id) {
                setTimeout(function () {
                    $('#newDealCategory').select2('val', $scope.newDeal.categories_id);
                }, 500);
            } else {
                $('#newDealCategory').select2('val', '');
            }
            if ($scope.newDeal.deal_image) {
                $('.deal-image div.fileinput-preview.thumbnail').append("<img src='" + $scope.newDeal.deal_image + "' />");
            }
        };

        $scope.getDefaultParams = function () {
            var params = {};
            params['limit'] = $scope.filterDealOptions.limit;
            params['offset'] = $scope.filterDealOptions.offset;
            if ($scope.filterDealOptions.textFilter) {
                params['filter_name'] = $scope.filterDealOptions.textFilter;
            }
            if ($scope.filterDealOptions.userDealFilter) {
                params['user_id'] = $scope.filterDealOptions.userDealFilter;
            }
            if ($scope.filterDealOptions.createdFromFilter) {
                params['created_from'] = moment($scope.filterDealOptions.createdFromFilter).format('YYYY-MM-DD');
            }
            if ($scope.filterDealOptions.createdToFilter) {
                params['created_to'] = moment($scope.filterDealOptions.createdToFilter).format('YYYY-MM-DD');
            }
            if ($scope.filterDealOptions.statusFilter) {
                params['status'] = $scope.filterDealOptions.statusFilter;
            }
            if ($scope.filterDealOptions.sortField) {
                params['sort_field'] = $scope.filterDealOptions.sortField;
                if ($scope.filterDealOptions.sortBy) {
                    params['sort_by'] = 'ASC';
                } else {
                    params['sort_by'] = 'DESC';
                }
            } else {
                $scope.filterDealOptions.sortField = 'created';
                $scope.filterDealOptions.sortBy = false;
            }
            return params;
        };

        $scope.getDeals = function () {
            $('#deal-table .check_all').prop("checked", false);
            var params = $scope.getDefaultParams();
            $http({method: 'GET', url: Config.baseUrl + '/deals/queryDeal', params: params}).then(function (response) {
                if (response.data.count > 0) {
                    $scope.dealItem.pages = response.data.deals;
                    $scope.dealItem.totalDeals = response.data.count;
                    $scope.dealItem.numberOfPages = Math.ceil($scope.dealItem.totalDeals / $scope.dealItem.itemsPerPage);
                    setTimeout(function () {
                        initStatusEditable()
                    }, 800);
                } else {
                    $scope.dealItem.pages = [];
                    $scope.dealItem.totalDeals = 0;
                    $scope.dealItem.numberOfPages = 0;
                }
            }, function (response) {
                throw response;
            });
        };

        $scope.setDealCurrentPage = function () {
            $('#deal-table .check_all').prop("checked", false);
            var params = {};
            if ($scope.dealCurrentPage > 0) {
                params.offset = $scope.dealCurrentPage * 10;
            } else {
                params.offset = 0;
            }
            params.limit = 10;
            $http({method: 'GET', url: Config.baseUrl + '/deals/queryDeal', params: params}).then(function (response) {
                if (response.data.count > 0) {
                    $scope.dealItem.pages = response.data.deals;
                    $scope.dealItem.totalDeals = response.data.count;
                    $scope.dealItem.numberOfPages = Math.ceil($scope.dealItem.totalDeals / $scope.dealItem.itemsPerPage);
                    setTimeout(function () {
                        initStatusEditable()
                    }, 800);
                    $scope.setPageDeal($scope.dealCurrentPage);
                } else {
                    $scope.dealItem.pages = [];
                    $scope.dealItem.totalDeals = 0;
                    $scope.dealItem.numberOfPages = 0;
                }
            }, function (response) {
                throw response;
            });
        };

        if ($scope.dealCurrentPage > 0) {
            $scope.setDealCurrentPage();
        } else {
            $scope.getDeals();
        }


        $('#search-text-deal').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                $scope.$apply(function () {
                    $scope.searchDeal();
                });
            }
        });

        $scope.clearAllDealFilter = function () {
            $scope.dealItem.filter = null;
            $scope.dealItem.userFilter = '0';
            $('#dateDealFilter').val('');
            $('#datePublishDealFilter').val('');
            $scope.dealItem.statusFilter = '0';
            $scope.searchDeal();
            $http.post(Config.baseUrl + '/products/deleteProductSessions', {'type': 'Deals'}).success(function (res) {
            })
        };

        $scope.searchDeal = function () {
            $scope.dealItem.currentPageInc = 1;
            $scope.dealItem.currentPage = 0;
            $scope.filterDealOptions.textFilter = $scope.dealItem.filter;
            $scope.filterDealOptions.userDealFilter = $scope.dealItem.userFilter;
            if ($('#dateDealFilter').val()) {
                $scope.filterDealOptions.createdFromFilter = moment.tz($('#dateDealFilter').val(), Config.timeZone).utc().format('YYYY-MM-DD');
            } else $scope.filterDealOptions.createdFromFilter = null;
            if ($('#datePublishDealFilter').val()) {
                $scope.filterDealOptions.createdToFilter = moment.tz($('#datePublishDealFilter').val(), Config.timeZone).utc().format('YYYY-MM-DD');
            } else $scope.filterDealOptions.createdToFilter = null;
            $scope.filterDealOptions.statusFilter = $scope.dealItem.statusFilter;
            $scope.setPageDeal(1);
        };

        $scope.sortByDeal = function (field) {
            $scope.dealItem.currentPageInc = 1;
            $scope.dealItem.currentPage = 0;
            if ($scope.filterDealOptions.sortField && $scope.filterDealOptions.sortField == field) {
                $scope.filterDealOptions.sortBy = !$scope.filterDealOptions.sortBy;
            } else {
                $scope.filterDealOptions.sortBy = true;
            }
            $scope.filterDealOptions.sortField = field;
            $scope.setPageDeal(1);
        };

        $scope.prevPageDeal = function () {
            if ($scope.dealItem.currentPage > 0) {
                $scope.setPageDeal($scope.dealItem.currentPage - 1);
            }
        };

        $scope.nextPageDeal = function () {
            if ($scope.dealItem.currentPage < $scope.dealItem.numberOfPages - 1) {
                $scope.setPageDeal($scope.dealItem.currentPage + 1);
            }
        };

        $scope.setPageDeal = function (n) {
            $http.post(Config.baseUrl + '/products/setCurrentPage', {
                'currentPage': n,
                'type': 'Deals'
            }).success(function (res) {
            });

            $scope.dealItem.currentPage = n;
            $scope.dealItem.currentPageInc = $scope.dealItem.currentPage + 1;
            $scope.dealItem.currentPageInc = $scope.dealItem.currentPageInc > $scope.dealItem.numberOfPages ? $scope.dealItem.numberOfPages : $scope.dealItem.currentPageInc;
            $scope.dealItem.currentPageInc = $scope.dealItem.currentPageInc < 1 ? 1 : $scope.dealItem.currentPageInc;
            $scope.filterDealOptions.offset = (n - 1) * $scope.dealItem.itemsPerPage;
            $scope.getDeals();
            setTimeout(function () {
                $('html,body').animate({
                        scrollTop: $("#wid-deal-list").offset().top
                    },
                    'slow');
            }, 600);
        };

        $scope.changePageDeal = function () {
            $scope.dealItem.currentPage = $scope.dealItem.currentPageInc - 1;
            $scope.dealItem.currentPage = $scope.dealItem.currentPage > $scope.dealItem.numberOfPages - 1 ? $scope.dealItem.numberOfPages - 1 : $scope.dealItem.currentPage;
            $scope.dealItem.currentPage = $scope.dealItem.currentPage < 0 ? 0 : $scope.dealItem.currentPage;
            $scope.setPageDeal($scope.dealItem.currentPage);
        };

        $scope.deleteDeal = function (id) {
            $http.post(Config.baseUrl + '/deals/deleteDeal/' + id).success(function (response) {
                if (response.status == true) {
                    if ($scope.dealItem.currentPage < $scope.dealItem.numberOfPages - 1) {
                        $scope.setPageDeal($scope.dealItem.currentPage);
                    } else if (0 < $scope.dealItem.currentPage) {
                        $scope.setPageDeal($scope.dealItem.currentPage - 1);
                    } else $scope.setPageDeal(1);
                }
            });
        };

        $scope.setStatusDeal = function ($id, status, index) {
            var data = {
                id: $id,
                status: status
            };
            $http.post(Config.baseUrl + '/deals/saveDeal', data).success(function (response) {
                if (response.status == true) {
                    $scope.dealItem.pages[index].deal.status = status;
                }
            });
        };

        $scope.emptyStorePublishedDate = function () {
            $scope.currentStore.publish_date = '';
        };

        $scope.updateListVendor = function (itemAdd, itemRemove) {
            console.log(itemAdd, itemRemove);
            $scope.$apply(function () {
                if (typeof itemAdd != 'undefined') {
                    var data = {
                        affiliate_url: $scope.currentStore.affiliate_url,
                        best_store: 0,
                        countrycode: itemAdd.id,
                        custom_keywords: "",
                        description: "",
                        id: null,
                        parent_id: $scope.currentStore.id,
                        show_in_homepage: 0,
                        status: "",
                        store_url: $scope.currentStore.store_url,
                        table_name: "store"
                    };
                    if (itemAdd.id == 'US') {
                        data.custom_keywords = 'Coupons';
                    } else if (itemAdd.id == 'GB') {
                        data.custom_keywords = 'Voucher Codes';
                    }
                    $scope.currentStore.vendors.push(data);
                }
                if (typeof itemRemove != 'undefined') {
                    for (var i = 0; i < $scope.currentStore.vendors.length; i++) {
                        if ($scope.currentStore.vendors[i].countrycode == itemRemove.id) {
                            $scope.currentStore.vendors.splice(i, 1);
                            break;
                        }
                    }
                }
                console.log($scope.currentStore.vendors);
            });
        };
        $scope.updateGoCode = function () {
            $('a.btn-update-code').addClass('disabled').empty().append("<i class='fa fa-spinner fa-pulse'></i>");
            $http({method: 'GET', url: Config.baseUrl + '/products/updateGoCode'}).then(function (response) {
                $('a.btn-update-code').removeClass('disabled').text("Update Go's Code");
            });
        };
        $scope.pullStoreLimit = 5000;
        $scope.pullStoreOffset = 0;
        $scope.pullCouponLimit = 1000;
        $scope.pullCouponOffset = 0;
        $scope.percentPull = 0;
        $scope.pullData = function () {
            $('a.btn-pull-data').addClass('disabled').empty().append("<i class='fa fa-spinner fa-pulse'></i> Saving... " + $scope.percentPull + "%");
            $http({
                method: 'GET',
                url: Config.baseUrl + '/products/pullData',
                params: {dbname: $('#db_name').val(), limit: $scope.pullStoreLimit, offset: $scope.pullStoreOffset}
            }).then(function (response) {
                if (response.data.status) {
                    $scope.pullStoreOffset = $scope.pullStoreOffset + response.data.count;
                    $scope.percentPull = Math.round(($scope.pullStoreOffset / response.data.total) * 100);
                    if (response.data.count < $scope.pullStoreLimit || $scope.percentPull == 100) {
                        $('a.btn-pull-data').text("Successful!");
                        location.reload();
                    } else {
                        $scope.pullData();
                    }
                } else location.reload();
                //$('a.btn-pull-data').removeClass('disabled').text("Pull Data");
            });
        };
        $scope.pullCoupons = function () {
            $('a.btn-pull-coupons').addClass('disabled').empty().append("<i class='fa fa-spinner fa-pulse'></i> Saving... " + $scope.percentPull + "%");
            $http({
                method: 'GET',
                url: Config.baseUrl + '/products/pullCoupons',
                params: {dbname: $('#db_name').val(), limit: $scope.pullCouponLimit, offset: $scope.pullCouponOffset}
            }).then(function (response) {
                if (response.data.status) {
                    $scope.pullCouponOffset = $scope.pullCouponOffset + response.data.count;
                    $scope.percentPull = Math.round(($scope.pullCouponOffset / response.data.total) * 100);
                    if (response.data.count < $scope.pullCouponLimit || $scope.percentPull == 100) {
                        $('a.btn-pull-coupons').text("Successful!");
                        location.reload();
                    } else {
                        $scope.pullCoupons();
                    }
                } else location.reload();
                //$('a.btn-pull-coupons').removeClass('disabled').text("Pull Coupons");
            });
        };

        $scope.clearData = function () {
            $('a.btn-clear-data').addClass('disabled').empty().append("<i class='fa fa-spinner fa-pulse'></i>");
            $http({
                method: 'GET',
                url: Config.baseUrl + '/products/clearData',
                params: {dbname: $('#db_name').val()}
            }).then(function (response) {
                $('a.btn-clear-data').removeClass('disabled').text("Clear Data");
            });
        };

        $scope.getPercentPull = function (table) {
            $http({
                method: 'GET',
                url: Config.baseUrl + '/products/getPercentPull',
                params: {table: table}
            }).then(function (response) {
                $scope.percentPull = response.percent;
            });
        }
    });
$(document).ready(function () {
    $.fn.editable.defaults.mode = 'popup';
    initStatusEditable();
    $('.pagination li ').on('click', function () {
        $('html,body').animate({
                scrollTop: $("#wid-cate-list").offset().top
            },
            'slow');
        initStatusEditable();
//            setTimeout(function(){initStatusEditable()},500);
    });
    $('.dataTable thead th').on('click', function () {
        initStatusEditable();
    });
    $('.tooltips').tooltip();
    $('body').on('hidden.bs.modal', '.modal', function () {
        $('.fileinput').fileinput('clear');
        addStoreValidator.resetForm();
        addDealValidator.resetForm();
        addCouponValidator.resetForm();
        $('.has-error').removeClass('has-error');
        $('.myErrorClass').removeClass('myErrorClass');
        $('.has-success').removeClass('has-success');
        $('.form-group').find(".symbol.ok").removeClass('ok').addClass('required');
        //$('#category-alias').val('');
        $(this).data('bs.modal', null);
    });
    $('#listCategories').select2({
        multiple: true,
        placeholder: "Select Category",
        maximumSelectionSize: 0,
        closeOnSelect: false,
        data: angular.element($('#content')).scope().getSubCategories()
    }).on('change', function (e) {
        //angular.element($('#content')).scope().updateListCategories(e.val);
        if (!$.isEmptyObject(addStoreValidator.submitted)) {
            addStoreValidator.form();
        }
    });

    $('.select2').select2({
        placeholder: "Select Category",
        width: "100%"
    });
    $('.tagsinput').select2({
        tags: true,
        tokenSeparators: [','],
        createSearchChoice: function (term) {
            var val = term.split(' ');
            if (val.length <= 5) {
                return {
                    id: term,
                    text: term
                }
            } else {
                return {
                    id: val[0] + ' ' + val[1] + ' ' + val[2] + ' ' + val[3] + ' ' + val[4],
                    text: val[0] + ' ' + val[1] + ' ' + val[2] + ' ' + val[3] + ' ' + val[4]
                }
            }
        },
        //            ajax: {
//                url: '',
//                dataType: 'json',
//                data: function (term, page) {
//                    return {
//                        q: term
//                    };
//                },
//                results: function (data, page) {
//                    return {
//                        results: data
//                    };
//                }
//            },

        // max tags is 10
        maximumSelectionSize: 10,
        // override message for max tags
        formatSelectionTooBig: function (limit) {
            return "Max tags is only " + limit;
        },
        maximumInputLength: 16,
        formatInputTooLong: function (term, maxLength) {
            return "Max length of a tag is " + maxLength;
        }
    });
    var addStoreValidator = $(".addStoreForm").validate({
        rules: {
            store_url: {
                required: true,
                url: true
            },
            affiliate_url: {
                url: true
            }
        },
        messages: {
            name: "The Store name is required and cannot be empty",
            listCategories: {
                required: "Please choose at least one Category"
            },
            listCountries: {
                required: "Please choose at least one Country"
            }
        },
        errorElement: "span", // contain the error msg in a small tag
        errorClass: 'help-block myErrorClass',
        errorPlacement: function (error, element) { // render error placement for each input type
            if (element.attr("type") == "radio" || element.attr("type") == "checkbox" || element.attr("type") == "file" || element.hasClass("datepicker")) { // for chosen elements, need to insert the error after the chosen container
                error.insertAfter($(element).closest('.form-group').children('div').children().last());
            } else if (element.hasClass("ckeditor")) {
                error.appendTo($(element).closest('.form-group'));
            } else {
                error.insertAfter(element);
                // for other inputs, just perform default behavior
            }
        },
        highlight: function (element, errorClass, validClass) {
            var elem = $(element);
            if (elem.hasClass("select2-offscreen")) {
                $("#s2id_" + elem.attr("id") + " ul").addClass(errorClass);
                $("#s2id_" + elem.attr("id")).removeClass('has-success').addClass('has-error');
            } else {
                $(element).closest('.help-block').removeClass('valid');
                // display OK icon
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                // add the Bootstrap error class to the control group
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            // revert the change done by hightlight
            var elem = $(element);
            if (elem.hasClass("select2-offscreen")) {
                $("#s2id_" + elem.attr("id") + " ul").removeClass(errorClass);
            } else {
                $(element).closest('.form-group').removeClass('has-error');
                // set error class to the control group
            }
        },
        success: function (label, element) {
            label.addClass('help-block valid');
            // mark the current input as valid and display OK icon
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
        }
//            focusInvalid: false,
//            invalidHandler: function (form, validator) {
//                if (!validator.numberOfInvalids())
//                    return;
//
//                $('.modal').animate({
//                    scrollTop: $(validator.errorList[0].element).offset().top - 400
//                }, 1000);
//
//            }
    });
    var addDealValidator = $(".addDealForm").validate({
        rules: {
            produc_url: {
                required: true,
                url: true
            },
            originPriceDeal: {
                greaterThan: '#realPriceDeal'
            },
            realPriceDeal: {
                lessThan: '#originPriceDeal'
            }
        },
        errorElement: "span", // contain the error msg in a small tag
        errorClass: 'help-block myErrorClass',
        errorPlacement: function (error, element) { // render error placement for each input type
            if (element.attr("type") == "radio" || element.attr("type") == "checkbox" || element.attr("type") == "file") { // for chosen elements, need to insert the error after the chosen container
                error.insertAfter($(element).closest('.form-group').children('div').children().last());
            } else if (element.hasClass("ckeditor")) {
                error.appendTo($(element).closest('.form-group'));
            } else {
                error.insertAfter(element);
                // for other inputs, just perform default behavior
            }
        },
        highlight: function (element, errorClass, validClass) {
            var elem = $(element);
            if (elem.hasClass("select2-offscreen")) {
                $("#s2id_" + elem.attr("id") + " ul").addClass(errorClass);
            } else {
                $(element).closest('.help-block').removeClass('valid');
                // display OK icon
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                // add the Bootstrap error class to the control group
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            // revert the change done by hightlight
            var elem = $(element);
            if (elem.hasClass("select2-offscreen")) {
                $("#s2id_" + elem.attr("id") + " ul").removeClass(errorClass);
            } else {
                $(element).closest('.form-group').removeClass('has-error');
                // set error class to the control group
            }
        },
        success: function (label, element) {
            label.addClass('help-block valid');
            // mark the current input as valid and display OK icon
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
        }
    });
    var addCouponValidator = $(".addCouponForm").validate({
        rules: {
            productLink: {
                required: true,
                url: true
            }
        },
        errorElement: "span", // contain the error msg in a small tag
        errorClass: 'help-block myErrorClass',
        errorPlacement: function (error, element) { // render error placement for each input type
            if (element.attr("type") == "radio" || element.attr("type") == "checkbox" || element.attr("type") == "file") { // for chosen elements, need to insert the error after the chosen container
                error.insertAfter($(element).closest('.form-group').children('div').children().last());
            } else if (element.hasClass("ckeditor")) {
                error.appendTo($(element).closest('.form-group'));
            } else {
                error.insertAfter(element);
                // for other inputs, just perform default behavior
            }
        },
        highlight: function (element, errorClass, validClass) {
            var elem = $(element);
            if (elem.hasClass("select2-offscreen")) {
                $("#s2id_" + elem.attr("id") + " ul").addClass(errorClass);
            } else {
                $(element).closest('.help-block').removeClass('valid');
                // display OK icon
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                // add the Bootstrap error class to the control group
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            // revert the change done by hightlight
            var elem = $(element);
            if (elem.hasClass("select2-offscreen")) {
                $("#s2id_" + elem.attr("id") + " ul").removeClass(errorClass);
            } else {
                $(element).closest('.form-group').removeClass('has-error');
                // set error class to the control group
            }
        },
        success: function (label, element) {
            label.addClass('help-block valid');
            // mark the current input as valid and display OK icon
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
        }
    });
    $('#category-name').on('keyup', function () {
        var str = S($(this).val()).stripPunctuation().trim().replaceAll(' ', '-').s;
        $('#category-alias').val(str.toLowerCase());
        angular.element($('#content')).scope().currentCategory.alias = str.toLowerCase();
    });
    $('#currency-coupon').on('change', function () {
        var cur = $(this).val();
        if (cur == '%') {
            var cur_val = $(this).next('input.auto-numeric').autoNumeric('get');
            if (cur_val > 100) {
                $(this).next('input.auto-numeric').autoNumeric('set', 100);
            }
            $(this).next('input.auto-numeric').autoNumeric('update', {vMax: 100});
        } else $(this).next('input.auto-numeric').autoNumeric('update', {vMax: 999999999.99});
    });
    $('#coupon_type').on('change', function () {
        var cur = $(this).val();
        if (cur == 'Coupon Code') {
            $('.coupon-discount').attr('placeholder', 'Discount *').rules('add', {
                required: true
            });
            $('.coupon-code').parent().show();
        } else if (cur == 'Free Shipping') {
            $('.coupon-discount').attr('placeholder', 'Discount').rules('remove', 'required');
            $('.coupon-code').parent().show();
        } else if (cur == 'Great Offer') {
            $('.coupon-discount').attr('placeholder', 'Discount *').rules('add', {
                required: true
            });
            $('.coupon-code').parent().hide();
        }
    });
});
function initStatusEditable() {
    $('a.status').editable({
        source: [
            {value: 'published', text: 'published'},
            {value: 'pending', text: 'pending'},
            {value: 'trash', text: 'trash'}
        ],
        display: function (value, sourceData) {
            var colors = {
                "pending": "blue",
                "published": "green",
                "trash": "red"
            }, elem = $.grep(sourceData, function (o) {
                return o.value == value;
            });

            if (elem.length) {
                $(this).text(elem[0].text).css("color", colors[value]);
            } else {
                $(this).empty();
            }
        },
        success: function (response, newValue) {
            var res = response;
            if (res.status == 'category') {
                angular.element($('#content')).scope().changeStatusCategory(res.msg, newValue);
            } else if (res.status == 'store') {
                angular.element($('#content')).scope().changeStatusStore(res.msg, newValue);
            } else if (res.status == 'coupon') {
                angular.element($('#content')).scope().changeStatusCoupon(res.msg, newValue);
            } else if (res.status == 'deal') {
                angular.element($('#content')).scope().changeStatusDeal(res.msg, newValue);
            }
        }
    }).on('save', function (e, params) {
        var td = $(this).parent().next('td').find('a.btn-trash');
        if (td) {
            if (params.newValue == 'trash') {
                $(td[0]).removeClass('ng-hide');
            } else {
                $(td[0]).addClass('ng-hide');
            }
        }
        var btn = $(this).parent().next('td').find('button');
        if (btn) {
            if (params.newValue == 'published') {
                $(btn).removeClass('ng-hide');
            } else {
                $(btn).addClass('ng-hide');
            }
        }
        var tdlink = $(this).parent().parent('tr').find('td').first();
        if (tdlink) {
            if (params.newValue == 'published') {
                $(tdlink).find('a').removeClass('ng-hide');
                $(tdlink).find('label').addClass('ng-hide');
            } else {
                $(tdlink).find('a').addClass('ng-hide');
                $(tdlink).find('label').removeClass('ng-hide');
            }
        }
    });

}
