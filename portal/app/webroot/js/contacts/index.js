angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', []);
angular.module('fdb', ['fdb.services', 'fdb.directives', 'fdb.filters']).
controller('ContactsCtrl', function($scope, $http, $timeout, $filter) {
    $scope.pageName = 'Contacts';
    $scope.deleteContact = function(id){
        var answer = confirm("Are you sure you want to delete this contact?");
        if (answer) {
            $http.post(Config.baseUrl + '/contacts/delete/' + id).success(function(response) {
                if(response.status == true){
                    for(var index=0; index < $scope.contacts.length; index++) {
                        if ($scope.contacts[index].Contact.id == id) {
                            $scope.contacts.splice(index, 1);
                            break;
                        }
                    }
                }
            })
        }
    }
})