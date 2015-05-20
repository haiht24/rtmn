function ContactUsCtrl($scope, $http, $timeout, $filter) {
    angular.forEach($scope.docs, function(value, key) {
        if (value['StaticPage']['doc_key'] == 'contactUs') {
            content = (value['StaticPage']['doc_value']).split('|');
            $scope.text1 = content[0];
            $scope.text2 = content[1];
        }
    });
    $scope.sendUsText = 'SEND US';

    $scope.send = function() {
        $scope.showError = true;
        $scope.sendUsText = 'SEND US ...';
        if(!$scope.name || !$scope.email || !$scope.subject || !$scope.keyword || !$scope.message){
            $scope.sendUsText = 'SEND US';
            return false;
        }
        recaptcha_response_field = $('#recaptcha_response_field').val();
        if(!recaptcha_response_field){
            alert('Enter captcha code');
            $scope.sendUsText = 'SEND US';
            return false;
        }
        time = new Date();
        var requestSend = $http({
            method: "post",
            url: SendPath,
            data: {
                name : $scope.name,
                email : $scope.email,
                subject : $scope.subject,
                keyword : $scope.keyword,
                message : $scope.message,
                time : time,
                recaptcha_challenge_field : $('#recaptcha_challenge_field').val(),
                recaptcha_response_field : recaptcha_response_field
            },
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });
        requestSend.success(function(rs) {
            //rs = JSON.parse(rs.data);
            if(rs.success == 1){
                $scope.sendUsText = 'SEND US';
                alert('Thank for contact us');
            }
            else{
                $scope.sendUsText = 'SEND US';
                alert('Invalid captcha');
            }
            $('#recaptcha_reload').click();
            //location.reload();
        });
    }
    };
