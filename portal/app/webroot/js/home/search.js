angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', []);
angular.module('fdb', ['fdb.services','fdb.directives', 'fdb.filters']).
controller('SearchCtrl',  function($scope , $http, $filter) {
    $(document).ready(function() {

        $("#search-project").focus();

    })
});