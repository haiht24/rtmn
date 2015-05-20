angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', []);
angular.module('fdb', ['fdb.services', 'fdb.directives', 'fdb.filters']).
controller('AdsCtrl', function($scope, $http, $timeout, $filter) {
    // Array position display at home page
    $scope.arrAdPos = {
        'ad_home_pos_1' : 'Home page position 1',
        'ad_home_pos_2' : 'Home page position 2',
    }
    // Default select position
    $scope.ad = {
        pos : 'ad_home_pos_1'
    }
    // Save Ad
    $scope.saveAd = function(ad){
        if(!ad.pos || !ad.image || !ad.des){
            $scope.Message = {'error' : 'Missing fields'};
            return;
        }

        $http.post(Config.baseUrl + '/ads/save/', ad).success(function(res) {
            $scope.ads.push(res.rs);
            angular.element('#cancelAdd').trigger('click');
        })
    }
    // Delete Ad
    $scope.deleteAds = function(id){
        if(confirm('Delete this Ad ?')){
            $http.post(Config.baseUrl + '/ads/delete/', {'id' : id}).success(function(res) {
                if(res.status == true){
                    for(var index = 0; index < $scope.ads.length; index++) {
                        if ($scope.ads[index].Property.id == id) {
                            $scope.ads.splice(index, 1);
                            break;
                        }
                    }
                }
                angular.element('#cancelAdd').trigger('click');
            })
        }

    }
})