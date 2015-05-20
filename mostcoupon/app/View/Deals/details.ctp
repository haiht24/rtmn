<?php $this->Ng->ngController('DealDetailsCtrl') ?>
<?php
$this->Ng->ngInit(
    [
        'storeID' => isset($deal['Deal']['store_id']) ? $deal['Deal']['store_id'] : '',
        'userLogin' => $this->Session->check('User.id') ? $this->Session->read('User.id') : '',
        'deal' => !empty($deal) ? $deal : '',
        'hotdeals' => !empty($hotdeals) ? $hotdeals : [],
        'coupons' => !empty($coupons) ? $coupons : []
    ]
);
?>
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 links">
                <ul>
                    <li>
                        <a href="<?php echo $this->Html->url('/') ?>">Home</a>
                    </li>
                    <li>
                        <a class="breadcrumbs-deal ellipsis"
                           href="<?php echo $this->Html->url('/deals/details/' . $deal['Deal']['id']) ?>"><?php echo $deal['Deal']['title'] ?></a>
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
<div class="deal-head main-content">
    <div class="container">
        <div class="inner rooow hidden-xs">
            <div class="col-sm-6 vs">
                <img
                    src="<?php echo ($deal['Deal']['deal_image']) ? $deal['Deal']['deal_image'] : 'http://lorempixel.com/530/400' ?>"
                    alt="<?php echo $deal['Deal']['title'] ?>"/></div>
            <div class="col-sm-6 tt">
                <div class="title desc-more"> <?php echo $deal['Deal']['title'] ?> </div>
                <div class="desc">
                    <div class="off"><?php echo $deal['Deal']['discount_percent'] ?>%</div>
                    <div class="desc-more deal-desc"><?php echo $deal['Deal']['description'] ?></div>
                </div>
                <div class="row impress">
                    <div class="price col-sm-5">
                        <div class="inner">
                            <span
                                class="deprecated"><?php echo $deal['Deal']['origin_price'] . $deal['Deal']['currency'] ?></span>
                            <span
                                class="featured"><?php echo $deal['Deal']['discount_price'] . $deal['Deal']['currency'] ?></span>
                        </div>
                    </div>
                    <div class="get col-sm-7">
                        <div class="inner">
                            <a data-toggle="modal"
                               deal_id="<?php echo $deal['Property']['foreign_key_right'] ?>"
                               href="<?php echo $this->Html->url('/deals/getCode/') . $deal['Deal']['id'] ?>"
                               data-target="#get-coupon-code" class="btn btn-get-deal hidden"></a>
                            <a href="<?php echo $this->Html->url('/go/') . $deal['Property']['foreign_key_right'] ?>"
                               deal_id="<?php echo $deal['Property']['foreign_key_right'] ?>"
                               onclick="window.open('?d='+$(this).attr('deal_id'),'_blank');window.open(this.href,'_self');"
                               class="btn btn-primary btn-block"> Get Deal </a>
                        </div>
                    </div>
                </div>
                <div class="subscribe">
                    <div class="title">
                        <img src="<?php echo $this->Html->url('/assets/img/subscribe-img.png') ?>" alt=""
                             style="width: 100%;max-width: 366px;"/>
                    </div>
                    <form class="input-group">
                        <input ng-model="emailSubscribe" type="email" class="form-control"
                               placeholder="Enter your email"/>
                        <span class="input-group-btn">
                            <button ng-click="subscribe()" class="btn"> SUBSCRIBE</button>
                        </span>
                    </form>
                </div>
                <div class="comment">
                    <div class="inner">
                        <div class="toggle activator" data-activator-target="^.comment">
                            <i class="icon mc mc-comments"></i>
                            <span>{{deal.Comment.length}} Comments</span>

                            <div class="on-deactivated">
                                <i class="icon mc mc-chevron-down"></i>
                            </div>
                            <div class="on-activated">
                                <i class="icon mc mc-chevron-up"></i>
                            </div>
                        </div>
                        <div class="body">
                            <?php if ($this->Session->check('User.id')) : ?>
                                <form class="form">
                                <textarea ng-model="textComment" class="form-control"
                                          placeholder="Add Comment .."></textarea>
                                    <button class="btn btn-success" ng-click="addCommentDeal()"> SEND</button>
                                    <span class="posted" ng-show="commentPosted">
                                        <i class="icon mc mc-check-circle-o"></i> Your Comment Posted!
                                    </span>
                                    <span class="posted error" ng-show="commentPostError"
                                          ng-bind="commentErrMsg"></span>
                                </form>
                            <?php else : ?>
                                <a href="#" class="active" data-toggle="modal" data-target="#sign-in-modal">
                                    Please login before add a comment</a>
                            <?php endif; ?>
                            <div class="list activator-target activated">
                                <div class="title activator activated">
                                    <span class="on-activated">HIDE COMMENTS</span>
                                    <span class="on-deactivated">SHOW COMMENTS</span>
                                </div>
                                <div class="body">
                                    <div class="comment media" ng-repeat="comment in deal.Comment">
                                        <div class="avatar media-left">
                                          <span class="virtue">
                                            <i class="icon mc mc-user"></i>
                                          </span>
                                        </div>
                                        <div class="media-body">
                                            <p ng-bind="comment.Comment.content"></p>
                                            <small><i><span title="{{convertTimeZone(comment.Comment.created)}}"
                                                            class="timeago"></span> by
                                                    {{comment.User.fullname ? comment.User.fullname : comment.User.email}}</i></a>
                                            </small>
                                        </div>
                                    </div>

                                    <a ng-show="limit < deal.Comment.length" ng-click="limit =  limit + 5" class="more">Show
                                        more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 visible-xs">
            <div class="vs">
                <div class="avatar">
                    <img
                        src="<?php echo ($deal['Deal']['deal_image']) ? $deal['Deal']['deal_image'] : 'http://lorempixel.com/530/400' ?>"
                        alt="<?php echo $deal['Deal']['title'] ?>"/>
                </div>
            </div>
            <div class="tt">
                <div class="title"> <?php echo $deal['Deal']['title'] ?> </div>
                <div class="desc">
                    <div class="off"><?php echo $deal['Deal']['discount_percent'] ?>%</div>
                    <div class="desc-more deal-desc"><?php echo $deal['Deal']['description'] ?></div>
                </div>
                <div class="price col-sm-12">
                    <div class="inner">
                        <span
                            class="deprecated"><?php echo $deal['Deal']['origin_price'] . $deal['Deal']['currency'] ?></span>
                        <span
                            class="featured"><?php echo $deal['Deal']['discount_price'] . $deal['Deal']['currency'] ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="main-content deal-detail-content">
        <div class="row">
            <div class="col-sm-3 side hidden-xs">
                <div class="side-box store-box">
                    <div class="header underline">
                        <div class="text">
                            <h3 class="title"> TOP STORES </h3>
                        </div>
                    </div>
                    <div class="body">
                        <?php foreach ($bestStores as $store) : ?>
                            <div class="store-item">
                                <div class="vs">
                                    <img
                                        src="<?php echo (!empty($store['Store']['logo'])) ? $store['Store']['logo'] : 'http://lorempixel.com/100/100' ?>"
                                        alt="<?php echo $store['Store']['name'] ?>"/></div>
                                <div class="tt">
                                    <a href="<?php echo $this->Html->url('/' . $store['Store']['alias']) ?>-coupons"
                                       class="title"><?php echo $store['Store']['name'] ?></a></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="side-box deal-box">
                    <div class="header underline">
                        <div class="text">
                            <div class="title"> HOT DEAL!
                                <span class="label hot small">HOT</span>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="deal-item" ng-repeat="hd in hotdeals">
                            <div class="vs">
                                <img ng-src="{{hd.Deal.deal_image}}">
                            </div>
                            <div class="tt">
                                <a ng-click="goDeal(hd)" class="title" ng-bind="hd.Deal.title | truncate:25"></a>
                                <em>Sale: </em>
                                <span class="deprecated" ng-bind="hd.Deal.origin_price + hd.Deal.currency"></span>
                                <span class="price" ng-bind="hd.Deal.discount_price + hd.Deal.currency"></span>
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
                            <div class="title"> STORE COUPON</div>
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
                            <h3 class="title">NOTABLE COUPONS</h3>
                            of the Store
                            <a ng-click="goStore(deal.Store.alias + '-coupons')"
                               class="underline">{{deal.Store.name}}</a>
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
                                            <h4 class="title hidden-xs">{{item.Coupon.title_store}}</h4>
                                            <a href=""
                                               class="mobile-title dotdotdot visible-xs">{{item.Coupon.title_store}}</a>

                                            <div class="desc desc-more">{{item.Coupon.description_store}}
                                            </div>
                                            <div class="extra hidden-xs" ng-if="item.User.group != 'admin'"> Shared by
                                                <a href="#">{{item.User.fullname}}</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-5 hidden-xs">
                                            <a href="<?php echo $this->Html->url('/go/') ?>{{item.Property.foreign_key_right}}"
                                               coupon_id="{{item.Property.foreign_key_right}}"
                                               onclick="window.open('?c='+$(this).attr('coupon_id'),'_blank');window.open(this.href,'_self');"
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
                                            <span>Expire: {{formatDate(item.Coupon.expire_date) | date: "dd MMM yyyy"}}</span>
                                        </div>
                                        <div class="stat">
                                            <i class="icon mc mc-comment-o"></i>
                                            <button class="btn btn-link show-comments" type="button"
                                                    data-toggle="collapse"
                                                    data-target="#collapse{{item.Coupon.id}}"
                                                    ng-if="item.Comments.length > 0">
                                                {{(item.Comments.count > item.Comments.length) ? item.Comments.count :
                                                item.Comments.length}} Comment(s)
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
                                    <div class="cell-caret-right visible-xs-block">
                                        <i class="fa fa-angle-right fa-2x"></i>
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
                                                        <small>
                                                            <i>
                                                                <span
                                                                    title="{{convertTimeZone(comment.Comment.created)}}"
                                                                    class="timeago">
                                                                </span>
                                                                by
                                                                {{comment.User.fullname ? comment.User.fullname : comment.User.email}}
                                                            </i>
                                                        </small>
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 text-center show-more-cm"
                                                 ng-if="item.Comments.count >10">
                                                <a href="" ng-click="">Show more</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="main-box deal-box">
                    <div class="header counter">
                        <div class="text">
                            <h3 class="title">RELATED DEALS</h3>
                        </div>
                    </div>
                    <div class="body">
                        <div class="rooow">
                            <?php foreach ($deals as $deal) : ?>
                                <div class="item deal-item col-sm-3">
                                    <div class="inner">
                                        <div class="nq-tag"> SALE
                                            <strong><?php echo $deal['Deal']['discount_percent'] ?>%</strong>
                                        </div>
                                        <div class="image">
                                            <img
                                                src="<?php echo (!empty($deal['Deal']['deal_image'])) ? $deal['Deal']['deal_image'] : 'http://lorempixel.com/200/200' ?>"
                                                alt="<?php echo $deal['Deal']['title'] ?>">
                                        </div>
                                        <div class="caption">
                                            <div class="price">
                                            <span
                                                class="deprecated"><?php echo $deal['Deal']['origin_price'] . $deal['Deal']['currency'] ?></span>
                                            <span
                                                class="featured"><?php echo $deal['Deal']['discount_price'] . $deal['Deal']['currency'] ?></span>
                                            </div>
                                            <a href="<?php echo $this->Html->url('/') . 'deals/details/' . $deal['Deal']['id'] ?>"
                                               class="desc dotdotdot"><?php echo $deal['Deal']['title'] ?></a>

                                            <div class="actions">
                                                <div class="inner">
                                                    <button class="btn btn-default btn-square">
                                                        <i class="icon mc mc-star"></i>
                                                    </button>
                                                    <a data-toggle="modal"
                                                       deal_id="<?php echo $deal['Property']['foreign_key_right'] ?>"
                                                       href="<?php echo $this->Html->url('/deals/getCode/') . $deal['Deal']['id'] ?>"
                                                       data-target="#get-coupon-code"
                                                       class="btn btn-get-deal hidden"></a>
                                                    <a href="<?php echo $this->Html->url('/go/') . $deal['Property']['foreign_key_right'] ?>"
                                                       deal_id="<?php echo $deal['Property']['foreign_key_right'] ?>"
                                                       onclick="window.open('?d='+$(this).attr('deal_id'),'_blank');window.open(this.href,'_self');"
                                                       class="btn btn-primary">
                                                        <span>Get Deal</span>
                                                        <i class="touch mc mc-touch"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
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