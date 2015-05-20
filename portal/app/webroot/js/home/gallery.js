angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', []);
angular.module('fdb', ['fdb.services','fdb.directives', 'fdb.filters']).
controller('GalleryCtrl',  function($scope , $http, $filter) {
    $(document).ready(function() {
        $('.superbox').SuperBox();
    })
});