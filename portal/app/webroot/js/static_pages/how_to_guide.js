angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', []);
angular.module('fdb', ['fdb.services', 'fdb.directives', 'fdb.filters']).
controller('HowToGuidesCtrl', function($scope, $http, $timeout, $filter) {
    $scope.pageName = 'How to Guides';

    angular.forEach($scope.docs, function(value, key) {
        if (value.StaticPage.doc_key == 'howToGuides') {
            $scope.howToGuides = value.StaticPage.doc_value;
        }
    })

    $scope.save = function() {
        var content = CKEDITOR.instances.ckeditor.getData();
        var requestUpdate = $http({
            method: "post",
            url : UpdatePath,
            data: {
                value: content,
                key : 'howToGuides'
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