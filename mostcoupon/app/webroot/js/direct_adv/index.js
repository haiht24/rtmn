function DirectAdvCtrl($scope, $http, $timeout, $filter) {
    angular.forEach($scope.docs, function(value, key){
        if(value['StaticPage']['doc_key'] == 'directAdv'){
            $scope.content = value['StaticPage']['doc_value'];
            $scope.title = 'Direct Advertising';
        }
    })
}