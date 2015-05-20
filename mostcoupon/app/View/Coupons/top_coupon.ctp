<?php $this->Ng->ngController('topCouponCtrl') ?>
<?php
$this->Ng->ngInit(
    [
        'stores' => isset($stores) ? $stores : '',
        'hotDeals' => isset($hotDeals) ? $hotDeals : [],
        'coupons' => isset($coupons) ? $coupons : [],
        'userLogin' => $this->Session->check('User.id') ? $this->Session->read('User.id') : '',
    ]
);
?>
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 links">
                <ul>
                    <li id="a" itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemref="b">
                        <a href="<?php echo $this->Html->url('/') ?>" itemprop="url">
                            <span itemprop="title">Home</span>
                        </a>
                    </li>
                    <li id="b" itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemprop="child">
                        <a href="<?php echo $this->Html->url(array('controller' => 'coupons', 'action' => 'topCoupon')) ?>"
                           itemprop="url">
                            <span itemprop="title">Top coupon</span>
                        </a>
                    </li>
                </ul>
            </div>
            <form class="col-sm-8 search hidden-xs">
                <div class="input">
                    <input type="text" class="form-control" placeholder="Search by store name, deal, coupon"/>
                    <i class="icon mc mc-search"></i>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="heading main-content">
    <h1 class="title">
        <strong>Top</strong> Coupons </h1>

    <div class="container top-coupon-labels">
        <span>Enjoy Coupons, Sales, and Deals with generous Cash Back!</span>

        <a class="label silent" ng-repeat="store in stores" ng-bind="store.Store.name" href=""></a>

    </div>
</div>
<div class="container">
    <div class="main-content page-top-coupon-content">
        <div class="row">
            <div class="col-sm-3 side hidden-xs">
                <div class="side-box store-box hidden-xs">
                    <div class="header underline">
                        <div class="text">
                            <div class="title"><h4>TOP STORES</h4></div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="store-item" ng-repeat="store in stores">
                            <div class="vs">
                                <img ng-src="{{getStoreImage(store)}}"
                                     alt="{{store.Store.name}}"/></div>
                            <div class="tt">
                                <a href="{{baseUrl}}/{{store.Store.alias}}-coupons"
                                   class="title">{{store.Store.name}}</a>

                                <div class="desc-more top-store-desc">
                                    {{store.Store.description}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="side-box deal-box">
                    <div class="header underline">
                        <div class="text">
                            <div class="title"><h4>HOT DEAL!</h4>
                                <span class="label hot small">HOT</span>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="deal-item" ng-repeat="deal in hotDeals">
                            <div class="vs">
                                <img ng-src="{{deal.Deal.deal_image}}" alt="{{deal.Deal.title}}"/>
                            </div>
                            <div class="tt">
                                <a ng-href="{{baseUrl}}/deals/details/{{deal.Deal.id}}" class="title dotdotdot"
                                   ng-bind="deal.Deal.title"></a>
                                <em>Sale: </em>
                                <span class="deprecated" ng-bind="deal.Deal.origin_price + deal.Deal.currency"></span>
                                <span class="price" ng-bind="deal.Deal.discount_price + deal.Deal.currency"></span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="side-box submit-box">
                    <div class="header box activator">
                        <div class="icon">
                            <i class="mc mc-pencil"></i>
                        </div>
                        <div class="text"> SUBMIT COUPON
                            <span class="arr"></span>
                        </div>
                    </div>
                    <?php echo $this->element('submit_form') ?>
                </div>
                <div class="side-box coupon-box">
                    <div class="header underline">
                        <div class="text">
                            <div class="title"><h4>STORE COUPON</h4></div>
                        </div>
                    </div>
                    <div class="body">
                        <?php echo $this->element('store_coupon') ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-9 main">
                <div class="main-box code-box">
                    <div class="header counter">
                        <div class="text">
                            <h1 class="title">COUPONS</h1>
                            <span class="counter">({{coupons.count}})</span>
                        </div>
                    </div>
                    <div class="body">
                        <div ng-repeat="item in coupons.coupons">
                            <div class="item code-item">
                                <div class="vs">
                                    <div class="card green"
                                         ng-if="((item.Coupon.coupon_type | uppercase) == 'COUPON CODE')">
                                        <div class="body">
                                            <strong>{{item.Coupon.discount}}{{item.Coupon.currency}}</strong>
                                            OFF
                                        </div>
                                        <div class="footer">
                                            {{item.Coupon.coupon_type}}
                                        </div>
                                    </div>
                                    <div class="card light-green"
                                         ng-if="((item.Coupon.coupon_type | uppercase) == 'FREE SHIPPING')">
                                        <div class="body">
                                            <strong>FREE</strong> SHIPPING
                                        </div>
                                        <div class="footer"> SALE</div>
                                    </div>
                                    <div class="card orange"
                                         ng-if="((item.Coupon.coupon_type | uppercase) == 'GREAT OFFER')">
                                        <div class="body">
                                            <strong>GREAT</strong> OFFER
                                        </div>
                                        <div class="footer"> SALE</div>
                                    </div>
                                </div>
                                <div class="tt">
                                    <div class="labels">
                                    <span ng-if="item.Coupon.event_id"
                                          class="label label-primary">{{item.Event.name}}</span>
                                        <span ng-if="item.Coupon.exclusive"
                                              class="label label-danger"> EXCLUSIVE </span>
                                        <span ng-if="item.Coupon.verified"
                                              class="label label-success"> 100% VERIFY </span>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <a href="<?php echo $this->Html->url('/go/') ?>{{item.Property.foreign_key_right}}"
                                               coupon_id="{{item.Property.foreign_key_right}}"><h3
                                                    class="title hidden-xs">
                                                    {{item.Coupon.title_store}}</h3></a>
                                            <a href="<?php echo $this->Html->url('/go/') ?>{{item.Property.foreign_key_right}}"
                                               class="mobile-title dotdotdot visible-xs"
                                               coupon_id="{{item.Property.foreign_key_right}}">{{item.Coupon.title_store}}</a>
                                            <a href=""
                                               class="mobile-title dotdotdot visible-xs">{{item.Coupon.title_store}}</a>

                                            <div class="desc desc-more">{{item.Coupon.description_store}}
                                            </div>
                                            <div class="extra hidden-xs" ng-if="item.User.group != 'admin'"> Shared by
                                                <a href="#">{{item.User.fullname}}</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-5 hidden-xs">
                                            <a coupon_id="{{item.Property.foreign_key_right}}"
                                               class="btn btn-get-code a-block"></a>
                                            <!--                                           ng-if="((item.Coupon.coupon_type | uppercase) == 'GREAT OFFER')"></a>-->
                                            <a data-toggle="modal" coupon_id="{{item.Property.foreign_key_right}}"
                                               href="<?php echo $this->Html->url('/coupons/getCode/') ?>{{item.Coupon.id}}"
                                               data-target="#get-coupon-code" class="btn btn-get-code hidden"></a>

                                            <div class="text-center margin-bottom-15">
                                                <a ng-class="checkLike(item.Like,userLogin,1) ? 'like-coupon btn btn-circle btn-thumb liked' : 'like-coupon btn btn-circle btn-thumb'"
                                                   id="likeCoupon{{item.Coupon.id}}"
                                                   ng-click="likeCoupon($index,item.Coupon.id,1)"
                                                   data-toggle="popover" data-placement="left"
                                                   coupon-id="{{item.Coupon.id}}">
                                                    <i class="fa fa-thumbs-o-up"></i>
                                                </a>
                                                <a ng-class="checkLike(item.Like,userLogin,-1) ? 'btn btn-circle btn-thumb tooltips disliked' : 'btn btn-circle btn-thumb tooltips'"
                                                   data-placement="top"
                                                   id="dislikeCoupon{{item.Coupon.id}}"
                                                   title="I dislike this"
                                                   ng-click="likeCoupon($index,item.Coupon.id,-1)">
                                                    <i class="fa fa-thumbs-o-down fa-flip-horizontal"></i>
                                                </a>

                                                <div>
                                                    {{percentLikes(item.Like)}} %
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="stats hidden-xs">
                                        <div class="stat" ng-if="item.Coupon.expire_date">
                                            <i class="icon mc mc-check-square-o"></i>
                                            <!--
                                            <span>Expire: {{formatDateTimeLocal(item.Coupon.expire_date) | date: "dd MMM yyyy"}}</span>
                                            -->
                                            <span ng-if="item.Coupon.expire_date">Expire: {{item.Coupon.expire_date | formatDateLocal}}</span>
                                        </div>
                                        <div class="stat">
                                            <i class="icon mc mc-comment-o"></i>
                                            <button class="btn btn-link show-comments" type="button"
                                                    data-toggle="collapse"
                                                    data-target="#collapse{{item.Coupon.id}}"
                                                    ng-if="item.Comments.length > 0">
                                                {{(item.Comments.count > item.Comments.length) ? item.Comments.count :
                                                item.Comments.length}}
                                                Comment(s)
                                            </button>
                                            <button class="btn btn-link show-comments" type="button"
                                                    data-toggle="collapse"
                                                    data-target="#collapse{{item.Coupon.id}}"
                                                    ng-if="item.Comments.length == 0">
                                                Add a Comment
                                            </button>
                                        </div>
                                        <div class="stat">
                                            <i class="icon mc mc-envelope-o"></i>
                                            <a class="popup-send-mail" data-toggle="popover"
                                               action="<?php echo $this->Html->url('/') . 'coupons/sendInfo/' ?>"
                                               coupon-id="{{item.Coupon.id}}">Send
                                                Mail</a>
                                        </div>
                                        <div class="stat report">
                                            <i class="icon mc mc-flag-o"></i>
                                            <a class="popup-report" tabindex="0" role="button" data-toggle="popover"
                                               data-trigger="focus">Report</a>

                                            <div class="content-report">
                                                <a href="#" class="item">
                                                    <i class="fa fa-ban"></i>
                                                    <span>Invalid Coupon Code</span>
                                                </a>
                                                <a href="#" class="item">
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                    <span>Expired Coupon</span>
                                                </a>
                                                <a href="#" class="item">
                                                    <i class="fa fa-user-secret"></i>
                                                    <span>Offensive Content</span>
                                                </a>
                                                <a href="#" class="item">
                                                    <i class="fa fa-chain-broken"></i>
                                                    <span>Invalid Link</span>
                                                </a>
                                                <a href="#" class="item">
                                                    <i class="fa fa-life-ring"></i>
                                                    <span>Other</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cell-caret-right visible-xs-block"><i
                                            class="fa fa-angle-right fa-2x"></i>
                                    </div>
                                </div>

                                <div class="collapse comments-collapse" id="collapse{{item.Coupon.id}}">
                                    <div class="well margin-bottom-0">
                                        <div class="row">
                                            <?php if ($this->Session->check('User.id')) : ?>
                                                <form class="add-comment-form" id="comment{{item.Coupon.id}}">
                                                    <div class="form-group col-sm-6">
                                                    <textarea name="content" class="form-control"
                                                      placeholder="add a comment..."
                                                      rows="6"></textarea>
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <div id="reCaptchaComment{{item.Coupon.id}}"
                                                             class="reCaptcha"></div>
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <a class="btn btn-success btn-block"
                                                           ng-click="addComment($index)">Post
                                                            Comment</a>
                                                    </div>
                                                </form>
                                            <?php else : ?>
                                                <a href="#" class="active" data-toggle="modal"
                                                   data-target="#sign-in-modal">
                                                    Please login before add a comment</a>
                                            <?php endif; ?>
                                            <div class="col-sm-12">
                                                <hr>
                                            </div>
                                            <div class="col-sm-12 comments-list">
                                                <div class="col-sm-6" ng-repeat="comment in item.Comments">
                                                    <h5>{{comment.Comment.content}}<br>
                                                        <small><i><span
                                                                    title="{{convertTimeZone(comment.Comment.created)}}"
                                                                    class="timeago"></span> by
                                                                {{comment.User.fullname}}</i>
                                                        </small>
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 text-center show-more-cm"
                                                 ng-if="item.Comments.length < item.Comments.count">
                                                <a href=""
                                                   ng-click="moreComments($index,item.Coupon.id,10,item.Comments.length)">Show
                                                    more</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item subscribe-item hidden-xs" ng-if="$index == 0">
                                <div class="row">
                                    <div class="vs col-sm-2"></div>
                                    <div class="tt col-sm-10">
                                        <h4 class="title"> Never Miss The Best Coupon Codes Again </h4>

                                        <p class="desc"> Get our best Amazon Promo Codes with our Deal Alerts and Weekly
                                            Newsletter </p>

                                        <form class="input-group">
                                            <input ng-model="emailSubscribe" type="email" class="form-control"
                                                   placeholder="Enter your email"/>
                                            <span class="input-group-btn">
                                                <button ng-click="subscribe()" class="btn btn-default"> Subscribe
                                                </button>
                                            </span>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var widgetId2;
    $(document).ready(function () {
        $('div.collapse.comments-collapse').on('show.bs.collapse', function (e) {
            var coupon_id = $(e.currentTarget).attr('id').replace('collapse', '');
            angular.element($('#content')).scope().loadComments(coupon_id);
            $("div.collapse.comments-collapse").not(this).each(function () {
                if ($(this).hasClass('in')) {
                    var collapse_id = $(this).attr('id');
                    $("#" + collapse_id).collapse('hide');
                }
            });
            var captcha = $(e.currentTarget).find('div.reCaptcha')[0];
            if (captcha) {
                widgetId2 = grecaptcha.render($(captcha).attr('id'), {'sitekey': '<?php echo $public_key ?>'});
            }
            var comment_form = $(e.currentTarget).find('.add-comment-form')[0];
            var conmmentValidator = $(comment_form).validate({
                rules: {
                    content: {
                        required: true,
                        maxlength: 200
                    }
                },
                errorElement: "span", // contain the error msg in a small tag
                errorClass: 'help-block myErrorClass',
                errorPlacement: function (error, element) { // render error placement for each input type
                    if (element.parent().hasClass("input-group")) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                        // for other inputs, just perform default behavior
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    var elem = $(element);
                    $(element).closest('.help-block').removeClass('valid');
                    // display OK icon
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                    // add the Bootstrap error class to the control group
                },
                unhighlight: function (element, errorClass, validClass) {
                    // revert the change done by hightlight
                    var elem = $(element);
                    $(element).closest('.form-group').removeClass('has-error');
                    // set error class to the control group
                },
                success: function (label, element) {
                    label.addClass('help-block valid');
                    // mark the current input as valid and display OK icon
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
                }
            });
        });
        $('div.comments-collapse').on('hide.bs.collapse', function (e) {
            var captcha = $(e.currentTarget).find('div.reCaptcha')[0];
            $(captcha).empty();
        });
        $("a.like-coupon").popover({
            "html": true,
            "placement": 'top',
            "content": function (e) {
                var val = '<div class="cool">'
                    + "<p>Cool, how much did you save?</p>"
                    + "<form class='input'>"
                    + "<div class='inner'>"
                    + '<input type="text" class="price" placeholder="0.00$"/>'
                    + '<div class="sep">'
                    + '<span>on</span>'
                    + '</div>'
                    + '<input type="text" class="cat" placeholder="Shoes, plants .."/>'
                    + '<button type="submit" class="btn"> Tell us</button>'
                    + '</div></form></div>';
                return val;
            }
        }).on('shown.bs.popover', function (e) {
            var coupon_id = $(e.currentTarget).attr('coupon-id');
            var $form = $($(e.currentTarget).next('div.popover').find('div.cool form.input')[0]);
            var $that = $(this);
            $form.on('submit', function (e) {
                e.preventDefault();
                var content = 'Saved ' + $form.find('input.price').val() + ' on ' + $form.find('input.cat').val();
                $that.attr('data-content', "<div style='width: 300px;height: 63px'><div style='margin: auto;width: 28px'><i class='fa fa-spinner fa-pulse fa-2x'></i></div></div>");
                var popover = $that.data('bs.popover');
                popover.setContent();
                popover.$tip.addClass(popover.options.placement);
                $.ajax({
                    type: 'post',
                    url: '<?php echo $this->Html->Url('/') ?>coupons/addSaveoff',
                    data: {'content': content, 'coupon_id': coupon_id},
                    success: function (data) {
                        $that.attr('data-content', "<div style='color: #30b24b;width: 300px;height: 58px'><h4>Nice one!</h4><p>Thanks for sharing how much you saved.</p></div>");
                        if (data.status == 'success') angular.element($('#content')).scope().updateListComment(data.comment);
                        var popover = $that.data('bs.popover');
                        popover.setContent();
                        popover.$tip.addClass(popover.options.placement);
                        setTimeout(function () {
                            $that.popover('hide')
                        }, 5000);
                    }
                });
            });
        });
    });

</script>