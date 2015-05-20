function HelpCtrl($scope, $http, $timeout, $filter) {
    angular.forEach($scope.docs, function(value, key){
        if(value['StaticPage']['doc_key'] == 'help'){
            $scope.content = value['StaticPage']['doc_value'];
            $scope.title = 'Help';
        }
    })
}