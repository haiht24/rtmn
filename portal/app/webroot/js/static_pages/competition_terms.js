angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', []);
angular.module('fdb', ['fdb.services', 'fdb.directives', 'fdb.filters']).
controller('CompetitionTermsCtrl', function($scope, $http, $timeout, $filter) {
    $scope.pageName = 'Competition Terms';

    angular.forEach($scope.docs, function(value, key) {
        if (value.StaticPage.doc_key == 'competitionTerms') {
            $scope.competitionTerms = value.StaticPage.doc_value;
        }
    })

    $scope.save = function() {
        var content = CKEDITOR.instances.ckeditor.getData();
        var requestUpdate = $http({
            method: "post",
            url : UpdatePath,
            data: {
                value: content,
                key : 'competitionTerms'
            },
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });
        requestUpdate.success(function(rs) {
            $scope.mess = 'Update Success';
        });
    }
})