/**
 * Created by Phuong on 3/6/2015.
 */
function submitCouponCtrl($scope, $http, $filter, showFormMessage, $compile) {
    $scope.addCouponBoolean = true;
    $scope.addCouponForm = function () {
        $('.submit-coupon-content .text-success').text('Submit New Coupon');
        $('.submit-box-title').attr('placeholder', 'Enter title coupon');
        $('#btnCouponForm').addClass('primary');
        $('#btnDealForm').removeClass('primary');
        $scope.addCouponBoolean = true;
    };

    $scope.addDealForm = function () {
        $('.submit-coupon-content .text-success').text('Submit New Deal');
        $('.submit-box-title').attr('placeholder', 'Enter title deal');
        $('#btnCouponForm').removeClass('primary');
        $('#btnDealForm').addClass('primary');
        $scope.addCouponBoolean = false;
    };
}
