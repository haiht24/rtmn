function DealIndexCtrl($scope, $http, $filter, Deal, showFormMessage, $compile, $location, $anchorScroll) {
    $scope.category = null;
    $scope.hotDeals = Config.hotDeals;
    $scope.latestDeals = Config.latestDeals;
    $scope.hotDealLimited = false;
    $scope.latestDealLimited = false;
    $scope.dealLimit = 8;
    $scope.latestdealLimit = 8;
    $scope.dealOffset = 8;
    $scope.latestdealOffest = 8;

    $scope.goDeal = function (deal) {
        window.location = Config.baseUrl + '/deals/details/' + deal.Deal.id;
    };

    $scope.filter = function (data) {
        $scope.hotDeals = [];
        $scope.latestDeals = [];

        for (var i = 0; i < data.length; i++) {
            if (data[i].Deal.hot_deal == 1) {
                $scope.hotDeals.push(data[i]);
            } else {
                $scope.latestDeals.push(data[i]);
            }
        }
    };

    $scope.jumpToLocation = function (key) {
        $location.hash(key);
        $anchorScroll();
    };

    $scope.searchDealByCategory = function () {
        if ($scope.category) {
            $http.post(Config.baseUrl + '/deals/search', {'text': $scope.category}).then(function (response) {
                $scope.filter(response.data);

            });
        }
    };

    $scope.getMoreHotDeals = function (number) {
        $('div.show-more-hot-deals a').empty().append("<i class='fa fa-spinner fa-pulse fa-2x'></i>");
        var data = {
            limit: number,
            offset: $scope.dealOffset,
            hot_deal: 1,
            status: 'published',
            expire_date_greater_null: true
        };
        $http({method: 'GET', url: Config.baseUrl + '/deals/getMore', params: data}).then(function (response) {
            if (response.data.deals.length > 0) {
                $scope.hotDeals = $scope.hotDeals.concat(response.data.deals);
            }
            $scope.dealOffset = $scope.hotDeals.length;
            if (response.data.count == $scope.dealOffset) $scope.hotDealLimited = true;
            $('div.show-more-hot-deals a').empty().append("Show More <i class='fa fa-arrow-circle-o-down'></i>");
        });
    };
    $scope.getMoreLatestDeals = function (number) {
        $('div.show-more-latest-deals a').empty().append("<i class='fa fa-spinner fa-pulse fa-2x'></i>");
        var data = {
            limit: number,
            offset: $scope.latestdealOffest,
            status: 'published',
            expire_date_greater_null: true
        };
        $http({method: 'GET', url: Config.baseUrl + '/deals/getMore', params: data}).then(function (response) {
            if (response.data.deals.length > 0) {
                $scope.latestDeals = $scope.latestDeals.concat(response.data.deals);
            }
            $scope.latestdealOffest = $scope.latestDeals.length;
            if (response.data.count == $scope.latestdealOffest) $scope.latestDealLimited = true;
            $('div.show-more-latest-deals a').empty().append("Show More <i class='fa fa-arrow-circle-o-down'></i>");
        });
    }
}