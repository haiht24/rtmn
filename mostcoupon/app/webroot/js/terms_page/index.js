function TermsCtrl($scope, $http, $timeout, $filter) {
    angular.forEach($scope.docs, function(value, key){
        if(value['StaticPage']['doc_key'] == 'terms'){
            $scope.content = value['StaticPage']['doc_value'];
            $scope.title = 'Terms and Conditions';
        }
    })
}