function CompetitionTermsCtrl($scope, $http, $timeout, $filter) {
    angular.forEach($scope.docs, function(value, key){
        if(value['StaticPage']['doc_key'] == 'competitionTerms'){
            $scope.content = value['StaticPage']['doc_value'];
            $scope.title = 'Competition Terms';
        }
    })
}