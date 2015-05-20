function AboutCookiesCtrl($scope, $http, $timeout, $filter) {
	$scope.title = 'About Cookies';
    angular.forEach($scope.docs, function(value, key){
        if(value['StaticPage']['doc_key'] == 'aboutCookies'){
            $scope.content = value['StaticPage']['doc_value'];
        }
    });
}