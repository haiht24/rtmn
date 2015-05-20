angular.module('fdb.filters', ['mcus.filters']).
    filter('formatDateLocal', function () {
        return function (input) {
            if (input) {
                return moment.utc(input).tz(Config.timeZone).format('ll');
            }
            return input;
        };
    }).
    filter('formatDateTimeLocal', function () {
        return function (input) {
            if (input) {
                return moment.utc(input).tz(Config.timeZone).format('ll LT');
            }
            return input;
        };
    });
function DealDetailsCtrl($scope,User, $http, Comment, $filter, showFormMessage, $compile) {

    $scope.textComment = '';

    $scope.limit = 5;

    $scope.couponLimit = 5;

    $scope.commentPosted = false;

    $scope.addCommentDeal = function () {
        var content = $scope.textComment;
        if (0 < content.length && content.length <= 200) {
            $scope.textComment = null;
            Comment.add({
                content: content,
                deal_id: $scope.deal.Deal.id,
                user_id: $scope.userLogin
            }).then(function (response) {
                console.log(response);
                $scope.commentPosted = true;
                $scope.commentPostError = false;
                if (!$scope.deal.Comment) $scope.deal.Comment = [];
                $scope.deal.Comment.unshift(response);
            });
        } else {
            $scope.commentPosted = false;
            $scope.commentPostError = true;
            $scope.commentErrMsg = 'Please enter at least 1 - 200 character!'
        }
        setTimeout(function () {
            $('.comment .inner .body .form .posted').hide();
        }, 5000);
    };
    $scope.goDeal = function (deal) {
        window.location = Config.baseUrl + '/deals/details/' + deal.Deal.id;
    };
    $scope.gotoCoupon = function (coupon) {
        window.location = Config.baseUrl + '/coupons/details/' + coupon.Coupon.id;
    };
    $scope.goStore = function (url) {
        window.location = Config.baseUrl + '/' + url;
    };
    $scope.addCouponBoolean = true;
    $scope.addCouponForm = function () {
        $('.submit-box .header .text').text('SUBMIT COUPON');
        $('.submit-box-title').attr('placeholder', 'Enter title coupon');
        $('#btnCouponForm').addClass('primary');
        $('#btnDealForm').removeClass('primary');
        $scope.addCouponBoolean = true;
    };

    $scope.addDealForm = function () {
        $('.submit-box .header .text').text('SUBMIT DEAL');
        $('.submit-box-title').attr('placeholder', 'Enter title deal');
        $('#btnCouponForm').removeClass('primary');
        $('#btnDealForm').addClass('primary');
        $scope.addCouponBoolean = false;
    };

    // Subscribe email
    $scope.subscribe = function(){
        var email = $scope.emailSubscribe;
        if(!email){
            return false;
        }
        var data = {};
        data.email = email;
        data.key = 'subscribe_store';
        data.foreign_key_right = $scope.storeID;

        User.request('addFromSubscribe', data);
        alert('Thank you for subscribe us!');
        $scope.emailSubscribe = '';
    };
    $scope.loadComments = function (coupon_id) {
        for (var i = 0; i < $scope.coupons.coupons.length; i++) {
            if ($scope.coupons.coupons[i].Coupon.id == coupon_id) {
                if (typeof($scope.coupons.coupons[i].Comments) === "undefined") {
                    Comment.query({coupon_id: coupon_id, limit: 10}).then(function (response) {
                        $scope.coupons.coupons[i].Comments = response.comments;
                        $scope.coupons.coupons[i].Comments.count = response.count;
                        $('.timeago').timeago();
                    });
                }
                break;
            }
        }
    };
    $scope.moreComments = function (index, coupon_id, limit, offset) {
        Comment.query({coupon_id: coupon_id, limit: limit, offset: offset}).then(function (response) {
            $scope.coupons.coupons[index].Comments = $scope.coupons.coupons[index].Comments.concat(response.comments);
        });
    };
    $scope.addComment = function (index) {
        var id = $scope.coupons.coupons[index].Coupon.id;
        var $form = $('#comment' + id + '.add-comment-form');
        var formValidation = $form.validate();
        if (!$form.valid()) return;
        $('#comment' + id + ' .btn.btn-success.btn-block').empty().append("<i class='fa fa-spinner fa-pulse'></i>").addClass('disabled');
        var dataSave = $form.serializeObject();
        dataSave.coupon_id = id;
        $http.post(Config.baseUrl + '/coupons/addComment', dataSave).success(function (response) {
            var alert = "";
            if (response.status == 'success') {
                $scope.coupons.coupons[index].Comments.unshift(response.comment);
                alert = "<div class='col-sm-12 alert alert-success alert-dismissible' role='alert'>"
                + "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span"
                + "aria-hidden='true'>&times;</span></button>" + response.msg + "</div>";
                formValidation.resetForm();
                $form[0].reset();
                setTimeout(function () {
                    $('.timeago').timeago();
                }, 500);
            } else {
                var msg = response.msg ? response.msg : 'Error!';
                alert = "<div class='col-sm-12 alert alert-danger alert-dismissible' role='alert'>"
                + "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span"
                + "aria-hidden='true'>&times;</span></button>" + msg + "</div>";
            }
            $('#comment' + id + ' .btn.btn-success.btn-block').empty().text("Post Comment").removeClass('disabled');
            $('#comment' + id).append(alert);
            grecaptcha.reset(widgetId2);
            setTimeout(function () {
                $('#comment' + id + ' .alert').remove();
            }, 5000);
        });
    };

    $scope.updateListComment = function (comment, index) {
        for (var i = 0; i < $scope.coupons.coupons.length; i++) {
            if ($scope.coupons.coupons[i].Coupon.id == comment.Comment.coupon_id) {
                if (!$scope.coupons.coupons[i].Comments) $scope.coupons.coupons[i].Comments = [];
                $scope.coupons.coupons[i].Comments.unshift(comment);
                $scope.coupons.coupons[i].Coupon.comment_count++;
                break;
            }
        }
    };

    $scope.convertTimeZone = function (date_value) {
        var d = new Date();
        var n = d.getTimezoneOffset() / 60;
        if (n >= 0) {
            n = '0' + n;
            n = '+' + n.substr(n.length - 2);
        } else {
            n = '0' + Math.abs(n);
            n = '-' + n.substr(n.length - 2);
        }
        return date_value + 'Z' + n;
    };
    $scope.percentLikes = function (likes) {
        var val = 0;
        var leng = 0;
        if (likes.length) {
            for (var i = 0; i < likes.length; i++) {
                if (likes[i].value == 1) val++;
                if (likes[i].value != 0) leng++;
            }
            if (leng == 0) return 0;
            return ((val / leng) * 100).toFixed(2);
        } else return 0;
    };
    $scope.checkLike = function (likes, user_id, val) {
        if (user_id) {
            for (var i = 0; i < likes.length; i++) {
                if (likes[i].user_id == user_id && likes[i].value == val) return true;
            }
            return false;
        } else return false;
    };
    $scope.updateLike = function (index, like) {
        for (var i = 0; i < $scope.coupons.coupons[index].Like.length; i++) {
            if ($scope.coupons.coupons[index].Like[i].user_id == like.user_id) {
                $scope.coupons.coupons[index].Like[i].value = like.value;
                break;
            }
        }
    };
    $scope.likeCoupon = function (index, id, val) {
        if ($scope.userLogin) {
            for (var i = 0; i < $scope.coupons.coupons[index].Like.length; i++) {
                if ($scope.coupons.coupons[index].Like[i].user_id == $scope.userLogin) {
                    if (val == -1) {
                        $("a.like-coupon[coupon-id='" + id + "']").popover('hide');
                    } else if (val == 1 && $scope.coupons.coupons[index].Like[i].value == 1) {
                        $("a.like-coupon[coupon-id='" + id + "']").popover('show');
                    } else if (val == 1 && $scope.coupons.coupons[index].Like[i].value == 0) {
                        $("a.like-coupon[coupon-id='" + id + "']").popover('hide');
                    }
                    break;
                }
            }
        } else {
            $('#sign-in-modal').modal('show');
            setTimeout(function () {
                $('a.like-coupon').popover('hide');
            }, 100);
            return;
        }
        var data = {
            object_id: id,
            value: val
        };
        $http.post(Config.baseUrl + '/likes/submit', data).success(function (response) {
            var alert = "";
            if (response.status == 'success') {
                if (response.cm == 'create') {
                    $scope.coupons.coupons[index].Like.push(response.like.Like);
                } else {
                    $scope.updateLike(index, response.like.Like);
                }

            } else {
                if (val == 1) {

                } else {
                    $("a#dislikeCoupon" + id).tooltip('hide')
                        .attr('data-original-title', response.msg)
                        .tooltip('fixTitle')
                        .tooltip('show');
                }
            }
        });
    };

    $scope.formatDate = function (date) {
        var date = date.split("-").join("/");
        var dateOut = new Date(date);
        return dateOut;
    };
}