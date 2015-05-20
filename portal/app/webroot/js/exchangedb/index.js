angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', []);
angular.module('fdb', ['fdb.services','fdb.directives', 'fdb.filters']).
controller('BackupCtrl',  function($scope , $http, $filter, $timeout, $q) {
        $scope.percentageDataLoaded = 0;
        $scope.percentageCouponsDataLoaded = 0;
        $scope.dataLoaded = false;
        $scope.itemCountStore = 0;
        $scope.totalStore = 0;
        $scope.itemCountCoupon = 0;
        $scope.totalCoupon = 0;
        $scope.percentageCouponsDataLoaded = 0;
        
        $scope.countQueueStore = 0;
        $scope.countQueueCoupon = 0;
        
        $scope.totalNumberOfRequest = 50;
        
        var queryFeedbacks = function() {
            return $http({method: 'GET', url: Config.baseUrl + '/exchangedb/backupStore'}).then(function(open) {
                $scope.countQueueStore ++;
                $scope.totalStore = open.data.total;
                $scope.itemCountStore = open.data.countDone;
                $scope.percentageDataLoaded = Math.round(Math.min(($scope.itemCountStore) / $scope.totalStore, 1) * 100);
                var promises = [];
                if ($scope.itemCountStore < $scope.totalStore && $scope.countQueueStore < $scope.totalNumberOfRequest) {
                    promises.push(queryFeedbacks());
                }
                return $q.all(promises);
            });
        };
        $scope.backupCoupons = function() {
            var queryCoupons = function() {
                return $http({method: 'GET', url: Config.baseUrl + '/exchangedb/backupCoupon'}).then(function(res) {
                    $scope.countQueueCoupon ++;
                    $scope.totalCoupon = res.data.total;
                    $scope.itemCountCoupon = res.data.countDone;
                    $scope.percentageCouponsDataLoaded = Math.round(Math.min(($scope.itemCountCoupon) / $scope.totalCoupon, 1) * 100);
                    var promises = [];
                    if ($scope.itemCountCoupon < $scope.totalCoupon && $scope.countQueueCoupon < $scope.totalNumberOfRequest) {
                        promises.push(queryCoupons());
                    }
                    return $q.all(promises);
                });
            };
            return queryCoupons().then(function() {
                location.reload();
            }, function(error) {
                throw error;
            });
        };
        $http({method: 'GET', url: Config.baseUrl + '/exchangedb/doneStore'}).then(function(data) {
            $scope.totalStore = data.data.total;
            $scope.itemCountStore = data.data.countDone;
            $scope.percentageDataLoaded = Math.round(Math.min(($scope.itemCountStore) / $scope.totalStore, 1) * 100);
            console.log($scope.itemCountStore);
            console.log($scope.totalStore);
            if ($scope.itemCountStore < $scope.totalStore) {
                return queryFeedbacks().then(function() {
                    location.reload();
                }, function(error) {
                    throw error;
                });
            } else {
                //$http({method: 'GET', url: Config.baseUrl + '/exchangedb/doneCoupon'}).then(function(response) {
                //    $scope.dataLoaded = true;
                //    $scope.totalCoupon = response.data.total;
                //    $scope.itemCountCoupon = response.data.countDone;
                //    $scope.percentageCouponsDataLoaded = Math.round(Math.min(($scope.totalCoupon) / $scope.itemCountCoupon, 1) * 100);
                //    console.log($scope.itemCountCoupon);
                //    console.log($scope.totalCoupon);
                //    if ($scope.itemCountCoupon < $scope.totalCoupon) {
                //        $scope.backupCoupons();
                //    } else {
                //        console.log('done');
                //    }
                //});
                
            }
        });
        
});