angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', []);
angular.module('fdb', ['fdb.services', 'fdb.directives', 'fdb.filters']).
controller('SeoCtrl', function($scope, $http, $timeout, $filter) {

    // Allow keywords
    $scope.allowKeywords = [
    '%%title%% : Page title (Not avaiable in Home page)',
    '%%currentmonth%% : Current month',
    '%%currentyear%% : Current year',
    '%%sitedesc%% : site description',
    '%%sep%% : symbol -',
    '%%StickyCouponDiscountValue%% : Discount value of one sticky coupon (Avaiable in Store detail page)',
    '%%StickyCouponTitle%% : Title of one sticky coupon (Avaiable in Store detail page)'
    ];
    // Load data
    angular.forEach($scope.Seos, function(v, k) {
        // General
        if(v.Seo.option_name == 'seo_siteName'){
            $scope.seo_siteName = v.Seo.option_value;
        }
        if(v.Seo.option_name == 'seo_siteDescription'){
            $scope.seo_siteDescription = v.Seo.option_value;
        }
        // Home
        if(v.Seo.option_name == 'seo_homeTitle'){
            $scope.seo_homeTitle = v.Seo.option_value;
        }
        if(v.Seo.option_name == 'seo_homeMetaDesc'){
            $scope.seo_homeMetaDesc = v.Seo.option_value;
        }
        if(v.Seo.option_name == 'seo_homeMetaKeyword'){
            $scope.seo_homeMetaKeyword = v.Seo.option_value;
        }
        if(v.Seo.option_name == 'seo_disableHomeNoIndex'){
            $scope.seo_disableHomeNoIndex = v.Seo.option_value;
        }
        // Store
        if(v.Seo.option_name == 'seo_storeTitle'){
            $scope.seo_storeTitle = v.Seo.option_value;
        }
        if(v.Seo.option_name == 'seo_storeDesc'){
            $scope.seo_storeDesc = v.Seo.option_value;
        }
        if(v.Seo.option_name == 'seo_storeKeyword'){
            $scope.seo_storeKeyword = v.Seo.option_value;
        }
        if(v.Seo.option_name == 'seo_storeH1'){
            $scope.seo_storeH1 = v.Seo.option_value;
        }
        if(v.Seo.option_name == 'seo_storeP'){
            $scope.seo_storeP = v.Seo.option_value;
        }
        if(v.Seo.option_name == 'seo_disableStoreNoIndex'){
            $scope.seo_disableStoreNoIndex = v.Seo.option_value;
        }
        // default store value
        if(v.Seo.option_name == 'seo_defaultStoreTitle'){
            $scope.seo_defaultStoreTitle = v.Seo.option_value;
        }
        if(v.Seo.option_name == 'seo_defaultStoreMetaDescription'){
            $scope.seo_defaultStoreMetaDescription = v.Seo.option_value;
        }
        if(v.Seo.option_name == 'seo_defaultStoreMetaKeyword'){
            $scope.seo_defaultStoreMetaKeyword = v.Seo.option_value;
        }
        if(v.Seo.option_name == 'seo_defaultH1Store'){
            $scope.seo_defaultH1Store = v.Seo.option_value;
        }
        if(v.Seo.option_name == 'seo_defaultPStore'){
            $scope.seo_defaultPStore = v.Seo.option_value;
        }
        // Category
        if(v.Seo.option_name == 'seo_CatTitle'){
            $scope.seo_CatTitle = v.Seo.option_value;
        }
        if(v.Seo.option_name == 'seo_CatDesc'){
            $scope.seo_CatDesc = v.Seo.option_value;
        }
        if(v.Seo.option_name == 'seo_CatKeyword'){
            $scope.seo_CatKeyword = v.Seo.option_value;
        }
        if(v.Seo.option_name == 'seo_DisableCatNoIndex'){
            $scope.seo_DisableCatNoIndex = v.Seo.option_value;
        }
        // Event
    })
    /*
    ** Save
    */
    // Store
    $scope.saveStoreConfig = function(){
        var data = {};
        data.seo_storeTitle = $scope.seo_storeTitle;
        data.seo_defaultStoreTitle = $scope.seo_defaultStoreTitle;
        data.seo_storeDesc = $scope.seo_storeDesc;
        data.seo_defaultStoreMetaDescription = $scope.seo_defaultStoreMetaDescription;
        data.seo_storeKeyword = $scope.seo_storeKeyword;
        data.seo_defaultStoreMetaKeyword = $scope.seo_defaultStoreMetaKeyword;
        data.seo_storeH1 = $scope.seo_storeH1;
        data.seo_defaultH1Store = $scope.seo_defaultH1Store;
        data.seo_storeP = $scope.seo_storeP;
        data.seo_defaultPStore = $scope.seo_defaultPStore;
        if($scope.seo_disableStoreNoIndex){
            disableStore = 1;
        }else{
            disableStore = 0;
        }
        data.seo_disableStoreNoIndex = disableStore;

        $http.post(Config.baseUrl + '/seo/saveStoreConfig', data).success(function (response) {
            if(response.data == true){
                $scope.storeSucc = 1;
            }
        });
    }
    // General
    $scope.saveGeneral = function(){
        var data = {};
        data.seo_siteName = $scope.seo_siteName;
        data.seo_siteDescription = $scope.seo_siteDescription;

        $http.post(Config.baseUrl + '/seo/saveGeneral', data).success(function (response) {
            if(response.data == true){
                $scope.generalSucc = 1;
            }
        });
    }
    // Home
    $scope.saveHome = function(){
        var data = {};
        data.seo_homeTitle = $scope.seo_homeTitle;
        data.seo_homeMetaDesc = $scope.seo_homeMetaDesc;
        data.seo_homeMetaKeyword = $scope.seo_homeMetaKeyword;
        if($scope.seo_disableHomeNoIndex){
            data.seo_disableHomeNoIndex = 1;
        }else{
            data.seo_disableHomeNoIndex = 0;
        }

        $http.post(Config.baseUrl + '/seo/saveHome', data).success(function (response) {
            if(response.data == true){
                $scope.homeSucc = 1;
            }
        });
    }
    // Category
    $scope.saveCate = function(){
        var data = {};
        data.seo_CatTitle = $scope.seo_CatTitle;
        data.seo_CatDesc = $scope.seo_CatDesc;
        data.seo_CatKeyword = $scope.seo_CatKeyword;
        if($scope.seo_DisableCatNoIndex){
            data.seo_DisableCatNoIndex = 1;
        }else{
            data.seo_DisableCatNoIndex = 0;
        }

        $http.post(Config.baseUrl + '/seo/saveCate', data).success(function (response) {
            if(response.data == true){
                $scope.cateSucc = 1;
            }
        });
    }
    // Event

})