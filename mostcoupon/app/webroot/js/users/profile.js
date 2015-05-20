angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']).
factory('User', function(mcModel) {
    return mcModel('User');
});
angular.module('fdb.filters', []);
angular.module('fdb', ['fdb.services','fdb.directives', 'fdb.filters']).
controller('UserProfileCtrl',  function($scope, $http, User, showFormMessage, $timeout) {
    
    //facebook part.
    $scope.facebookLinkMessage = null;
    $scope.facebookLinkStatus = true;
    $scope.needUpdatePassword = false;
    
    $scope.facebookLink = function() {
        $scope.facebookLinkStatus = true;
        FB.getLoginStatus(function(response) {
            if (response.status === 'connected') {  
                $scope.connectFB(response.authResponse.userID);
            } else if (response.status === 'not_authorized') {               
                FB.login(function(fbres){
                    if (fbres.authResponse) {                        
                        $scope.connectFB(fbres.authResponse.userID);
                    } 
                    }, {scope: 'email, publish_stream'});
            } else {
                // the user isn't logged in to Facebook.
                FB.login(function(fbres){
                    if (fbres.authResponse) {
                        $scope.connectFB(fbres.authResponse.userID);
                    }
                    }, {scope: 'email, publish_stream'});
            }}, true);
    };
    $scope.connectFB = function(fbId) {     
        $http.post(Config.baseUrl + '/users/mapFacebookToUser/', { facebook_id :  fbId  })
          .success(function(response){ 
            $scope.facebookLinkMessage = response.message;         
            if (response.status == 'success') {
                $scope.user.facebook_id = fbId;                               
            } else {                
                $scope.facebookLinkStatus = false;
            }
        });     
    };
    $scope.facebookUnlink = function() {
        $scope.needUpdatePassword = false;
        User.get($scope.user.id).then(function(user){
            if (user.User.password == null || !user.User.password) { 
                $scope.needUpdatePassword = true;
                return false;                               
            } else {                
                User.edit($scope.user.id, { facebook_id : '' }).then(function(){
                    window.location =  Config.baseUrl + '/users/logout';
                });
            }
        });

    };
    $scope.loadProfileTab = function(){
        $scope.current_tab = 1;
        $scope.changeTab = function(index){
            $scope.current_tab = index;
        };
        $scope.isActiveTab = function(index){
            return index === $scope.current_tab;
        };
    }
});
