function DownloadAppCtrl($scope, $http, $timeout, $filter) {
    angular.forEach($scope.docs, function(value, key){
        if(value['StaticPage']['doc_key'] == 'downloadApp'){
            $scope.content = value['StaticPage']['doc_value'];
            $scope.title = 'Download App';
        }
    })
}