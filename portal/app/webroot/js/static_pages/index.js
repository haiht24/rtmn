angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', []);
angular.module('fdb', ['fdb.services', 'fdb.directives', 'fdb.filters']).
controller('DocsCtrl', function($scope, $http, $timeout, $filter) {
    // Init
    $welcomeText = '';
    $calText = '';
    angular.forEach($scope.docs, function(value, key) {
        if (value.StaticPage.doc_key == 'aboutTitle') {
            $scope.aboutTitle = value.StaticPage.doc_value;
        }
        if (value.StaticPage.doc_key == 'about') {
            $scope.txtAbout = value.StaticPage.doc_value;
        }
        if(value.StaticPage.doc_key == 'slide'){
            $scope.txtSlide = value.StaticPage.doc_value;
        }
        if(value.StaticPage.doc_key == 'topStores'){
            $scope.topStoreIDs = value.StaticPage.doc_value;
        }
        if(value.StaticPage.doc_key == 'welcomeText'){
            $welcomeText = value.StaticPage.doc_value;
        }
        if(value.StaticPage.doc_key == 'calText'){
            $calText = value.StaticPage.doc_value;
        }
        if(value.StaticPage.doc_key == 'skill_1'){
            $scope.skill_1 = value.StaticPage.doc_value;
        }
        if(value.StaticPage.doc_key == 'skill_2'){
            $scope.skill_2 = value.StaticPage.doc_value;
        }
        if(value.StaticPage.doc_key == 'skill_3'){
            $scope.skill_3 = value.StaticPage.doc_value;
        }
        if(value.StaticPage.doc_key == 'skill_4'){
            $scope.skill_4 = value.StaticPage.doc_value;
        }
    });
    $arrWelcomeText = $welcomeText.split('|');
    $scope.welcome_1 = $arrWelcomeText[0];
    $scope.welcome_2 = $arrWelcomeText[1];

    $arrCalText = $calText.split('|');
    $scope.memberText = $arrCalText[0];
    $scope.couponText = $arrCalText[1];
    $scope.storeText = $arrCalText[2];
    $scope.followText = $arrCalText[3];
    // Update welcome text
    $scope.saveWelcomeText = function() {
            var requestWelcomeText = $http({
                method: "post",
                url : UpdatePath,
                data: {
                    value: $scope.welcome_1 + '|' + $scope.welcome_2,
                    key: 'welcomeText'
                },
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            });
            requestWelcomeText.success(function(rs) {
                $scope.messWelcome = 'Update Success';
            });
    }
    // Update Calculate text
    $scope.saveText = function() {
            var requestCalText = $http({
                method: "post",
                url : UpdatePath,
                data: {
                    value: $scope.memberText + '|' + $scope.couponText + '|' + $scope.storeText + '|' + $scope.followText,
                    key: 'calText'
                },
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            });
            requestCalText.success(function(rs) {
                $scope.messText = 'Update Success';
            });
    }
    // Update about
    $scope.saveAboutTitle = function(element){
        var requestAboutUs = $http({
            method: "post",
            url : UpdatePath,
            data: {
                value: $scope.aboutTitle,
                key: 'aboutTitle'
            },
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });
        requestAboutUs.success(function(rs) {
            $scope.messAbout = 'Update Success';
        });
    }
    $scope.saveAbout = function(element) {
            var txtAbout = CKEDITOR.instances.ckeditor.getData();
            var requestAboutUs = $http({
                method: "post",
                url : UpdatePath,
                data: {
                    value: txtAbout,
                    key: 'about'
                },
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            });
            requestAboutUs.success(function(rs) {
                $scope.messAbout = 'Update Success';
            });
    }
    // Update Slide
    $scope.saveSlide = function() {
        var requestSlide = $http({
            method: "post",
            url : UpdatePath,
            data: {
                value: $scope.txtSlide,
                key : 'slide'
            },
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });
        requestSlide.success(function(rs) {
            $scope.messSlide = 'Update Success';
        });
    }
    // Update Skills
    $scope.saveSkill = function() {
        var requestSkill = $http({
            method: "post",
            url : UpdatePath,
            data: {
                key : 'skills',
                value :{
                    'skill_1' : $scope.skill_1,
                    'skill_2' : $scope.skill_2,
                    'skill_3' : $scope.skill_3,
                    'skill_4' : $scope.skill_4
                }
            },
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });
        requestSkill.success(function(rs) {
            $scope.messSkills = 'Update Success';
        });
    }
    // Update Top stores
    $scope.saveTopStores = function() {
        var requestTopStores = $http({
            method: "post",
            url : UpdatePath,
            data: {
                value: $scope.topStoreIDs,
                key : 'topStores'
            },
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });
        requestTopStores.success(function(rs) {
            $scope.messTopStores = 'Update Success';
        });
    }
})