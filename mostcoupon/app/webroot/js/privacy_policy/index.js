function PrivacyCtrl($scope, $http, $timeout, $filter) {
    angular.forEach($scope.docs, function(value, key){
        if(value['StaticPage']['doc_key'] == 'privacy'){
            $scope.content = value['StaticPage']['doc_value'];
            $scope.title = 'Privacy Policy';
        }
    })
}