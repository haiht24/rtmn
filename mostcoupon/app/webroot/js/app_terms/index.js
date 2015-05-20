function AppTermsCtrl($scope, $http, $timeout, $filter) {
    angular.forEach($scope.docs, function(value, key){
        if(value['StaticPage']['doc_key'] == 'appTerms'){
            $scope.content = value['StaticPage']['doc_value'];
            $scope.title = 'App Terms';
        }
    })
}