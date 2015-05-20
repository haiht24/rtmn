angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', []);
angular.module('fdb', ['fdb.services', 'fdb.directives', 'fdb.filters']).
controller('ContactUsCtrl', function($scope, $http, $timeout, $filter) {
    $scope.pageName = 'Contact Us';
    $scope.headerText1 = 'Text 1';
    $scope.headerText2 = 'Text 2';

    angular.forEach($scope.docs, function(value, key) {
        if (value.StaticPage.doc_key == 'contactUs') {
            contactUs = (value.StaticPage.doc_value).split('|');
            $scope.text1 = contactUs[0];
            $scope.text2 = contactUs[1];
        }
    })

    $scope.save = function() {
        var content1 = CKEDITOR.instances.ckeditor1.getData();
        var content2 = CKEDITOR.instances.ckeditor2.getData();
        var requestUpdate = $http({
            method: "post",
            url : UpdatePath,
            data: {
                value: content1 + '|' + content2,
                key : 'contactUs'
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