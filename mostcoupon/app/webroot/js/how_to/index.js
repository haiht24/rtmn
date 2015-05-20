function HowToCtrl($scope, $http, $timeout, $filter) {
    angular.forEach($scope.docs, function(value, key){
        if(value['StaticPage']['doc_key'] == 'howToGuides'){
            $scope.content = value['StaticPage']['doc_value'];
            $scope.title = 'How To Guides';
        }
    })
}