function HomeIndexCtrl($scope, User, $http, $filter, showFormMessage, $compile) {
    $scope.hotdeals = Config.hotdeals;
    $scope.lastDeals = Config.lastDeals;
    $scope.baseUrl = Config.baseUrl;
    $scope.formatDate = function (date) {
        var date = date.split("-").join("/");
        var dateOut = new Date(date);
        return dateOut;
    };
    $scope.goDeal = function (deal) {
        window.location = Config.baseUrl + '/deals/details/' + deal.Deal.id;
    };

    $scope.totalCategories = 10;
    $scope.listCategories = Config.listCategories;
    $scope.addCouponBoolean = true;
    $scope.showMoreCategories = function (number) {
        var data = {
            limit: number,
            offset: $scope.totalCategories
        };
        $('#view-more-categories').text('');
        $('#view-more-categories').append("<i class='fa fa-spinner fa-pulse'></i>");
        $http({method: 'GET', url: Config.baseUrl + '/home/getCategories', params: data}).then(function (response) {
            if (response.data.count > 0) {
                $scope.totalCategories += response.data.count;
                var cate = angular.copy(response.data.categories);
                $scope.listCategories = $scope.listCategories.concat(cate);
                if (response.data.count < number) {
                    $('#view-more-categories').remove();
                } else {
                    $('#view-more-categories').empty();
                    $('#view-more-categories').text(' Show more Categories ');
                }
            } else {
                $('#view-more-categories').remove();
            }
        }, function (response) {
            throw response;
        });
    };

    $scope.addCouponForm = function () {
        $('.submit-box .header .text').text('SUBMIT COUPON');
        $('.submit-box-title').attr('placeholder', 'Enter title coupon');
        $('#btnCouponForm').addClass('primary');
        $('#btnDealForm').removeClass('primary');
        $scope.addCouponBoolean = true;
        $('#submit-box-form').attr('action', Config.baseUrl + '/Coupons/submitCoupon');
    };

    $scope.addDealForm = function () {
        $('.submit-box .header .text').text('SUBMIT DEAL');
        $('.submit-box-title').attr('placeholder', 'Enter title deal');
        $('#btnCouponForm').removeClass('primary');
        $('#btnDealForm').addClass('primary');
        $scope.addCouponBoolean = false;
        $('#submit-box-form').attr('action', Config.baseUrl + '/Deals/submitDeal');
    };

    $scope.loadDealsByCategory = function (category_id) {
        $(".deal-loading").show();
        $scope.hotdeals = [];
        $scope.lastDeals = [];
        var data = {
            id: category_id
        };
        $http({
            method: 'GET',
            url: Config.baseUrl + '/home/loadDealsByCategory',
            params: data
        }).then(function (response) {
            if (response.data.count > 0) {
                $scope.totalCategories += response.data.count;
                var cate = angular.copy(response.data.categories);
                $scope.listCategories = $scope.listCategories.concat(cate);
                $(".deal-loading").hide();
            } else {

            }
        }, function (response) {
            throw response;
        });
    };
    // Subscribe email
    $scope.subscribe = function(){
        var email = $scope.emailSubscribe;
        if(!email){
            return false;
        }
        var data = {};
        data.email = email;
        data.key = 'subscribe_home';
        data.foreign_key_right = '';

        User.request('addFromSubscribe', data);
        alert('Thank you for subscribe us!');
        $scope.emailSubscribe = '';
    };

}