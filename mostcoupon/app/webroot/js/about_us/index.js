function aboutusCtrl($scope, $http, $timeout, $filter) {
    angular.forEach($scope.docs, function(value, key){
        if(value['StaticPage']['doc_key'] == 'aboutTitle'){
            $scope.aboutTitle = value['StaticPage']['doc_value'];
        }
        if(value['StaticPage']['doc_key'] == 'about'){
            $scope.aboutContent = value['StaticPage']['doc_value'];
        }
        if(value['StaticPage']['doc_key'] == 'welcomeText'){
            $arrWelcomeText = (value['StaticPage']['doc_value']).split('|');
            $scope.welcome_1 = $arrWelcomeText[0];
            $scope.welcome_2 = $arrWelcomeText[1];
        }
        if(value['StaticPage']['doc_key'] == 'calText'){
            $arrCalText = (value['StaticPage']['doc_value']).split('|');

            member = $arrCalText[0].split('***');
            $scope.memText = member[1];
            $scope.memValue = member[0];

            coupon = $arrCalText[1].split('***');
            $scope.couponValue = coupon[0];
            $scope.couponText = coupon[1];

            store = $arrCalText[2].split('***');
            $scope.storeValue = store[0];
            $scope.storeText = store[1];

            follow = $arrCalText[3].split('***');
            $scope.followValue = follow[0];
            $scope.followText = follow[1];
        }
        if(value['StaticPage']['doc_key'] == 'slide'){
            //$scope.imgs = (value['StaticPage']['doc_value']).split(',');
            arr = [];
            angular.forEach((value['StaticPage']['doc_value']).split(','), function(v, k){
                slide = [];
                if(v){
                    v = v.split('|');
                    slide = [v[0], v[1], v[2]];
                    arr.push(slide);
                }
            })
            $scope.imgs = arr;
        }
        if(value['StaticPage']['doc_key'] == 'skill_1'){
            sk1 = (value['StaticPage']['doc_value']).split('|');
            $scope.sk1Title = sk1[0];
            $scope.sk1Value = sk1[1];
        }
        if(value['StaticPage']['doc_key'] == 'skill_2'){
            sk2 = (value['StaticPage']['doc_value']).split('|');
            $scope.sk2Title = sk2[0];
            $scope.sk2Value = sk2[1];
        }
        if(value['StaticPage']['doc_key'] == 'skill_3'){
            sk3 = (value['StaticPage']['doc_value']).split('|');
            $scope.sk3Title = sk3[0];
            $scope.sk3Value = sk3[1];
        }
        if(value['StaticPage']['doc_key'] == 'skill_4'){
            sk4 = (value['StaticPage']['doc_value']).split('|');
            $scope.sk4Title = sk4[0];
            $scope.sk4Value = sk4[1];
        }
    })
};