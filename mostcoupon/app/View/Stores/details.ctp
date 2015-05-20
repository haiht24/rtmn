<?php $this->Ng->ngController('StoreDetailsCtrl') ?>
<?php
$this->Ng->ngInit(
    [
        'store_id' => isset($storeId) ? $storeId : '',
        'expiredCoupons' => isset($expiredCoupons) ? $expiredCoupons : [],
        'expiredDeals' => isset($expiredDeals) ? $expiredDeals : [],
        'totalExpiredCoupons' => isset($totalExpiredCoupons) ? $totalExpiredCoupons : 0,
        'totalExpiredDeals' => isset($totalExpiredDeals) ? $totalExpiredDeals : 0,
        'coupons' => isset($coupons) ? $coupons : [],
        'userLogin' => $this->Session->check('User.id') ? $this->Session->read('User.id') : '',
        'relatedCoupons' => isset($relatedCoupons) ? $relatedCoupons : []
    ]
);
?>

<div class="breadcrumbs" xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <div class="row">
            <div class="col-sm-5 links">
                <ul>
                    <li id="a" itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemref="b">
                        <a href="<?php echo $this->Html->url('/') ?>" itemprop="url">
                            <span itemprop="title">Home</span>
                        </a>
                    </li>
                    <li id="b" itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemprop="child" itemref="c">
                        <a href="<?php echo $this->Html->url('/categories/') . $cate_store['Category']['alias'] ?>"
                           itemprop="url">
                            <span itemprop="title"><?php echo $cate_store['Category']['name'] ?></span>
                        </a>
                    </li>
                    <li id="c" itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemprop="child">
                        <a href="<?php echo $this->Html->url('/' . $store['Store']['alias']) ?>-coupons"
                           itemprop="url">
                            <span itemprop="title"><?php echo $store['Store']['name'] ?></span>
                        </a>
                    </li>
                </ul>
            </div>
            <form class="col-sm-7 search hidden-xs">
                <div class="input">
                    <input type="text" class="form-control" placeholder="Search by store name, deal, coupon"/>
                    <i class="icon mc mc-search"></i>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container main-content store-detail-content">
    <div class="row">
        <div class="col-sm-3 side">
            <div class="side-box promo-box visual">
                <div class="avatar">
                    <div class="inner">
                        <a target="_blank"
                           href="<?php echo $this->Html->url('/go/') . $store['Property'][0]['foreign_key_right'] ?>"><img
                                src="<?php echo $store['Store']['logo'] ?>"
                                alt="<?php echo $store['Store']['name'] ?>"/></a>
                        <a href="<?php echo $this->Html->url('/' . $store['Store']['alias']) ?>-coupons"
                           class="heart">
                            <i class="icon mc mc-heart"></i>
                        </a>
                    </div>
                </div>
                <small>
                    Active <?php echo $store['Store']['name'] . ' - ' . date("m/Y", strtotime($store['Store']['publish_date'])) ?></small>
                <div class="store-desc desc-more"><?php echo $store['Store']['description'] ?></div>
                <p>
                    <!-- <a href="#">How to
                        use <?php echo $store['Store']['name'] ?> Coupon Code</a> -->
                </p>
                <a target="_blank"
                   href="<?php echo $this->Html->url('/go/') . $store['Property'][0]['foreign_key_right'] ?>"
                   class="btn btn-primary dark-text btn-block"> Shop at <?php echo $store['Store']['name'] ?>
                    <i class="icon mc mc-chevron-circle-right"></i>
                </a>

                <div class="social hidden-xs">
                    <span>Share</span>
                    <a class='st_facebook_custom
                    btn btn-social-icon btn-circle btn-xs btn-facebook'><i class="fa fa-facebook"></i></a>
                    <a class='st_twitter_custom
                    btn btn-social-icon btn-circle btn-xs btn-twitter'><i class="fa fa-twitter"></i></a>
                    <a target="_blank"
                       href="https://plus.google.com/share?url=<?php echo $this->Html->url(null, true) ?>" class='
                    btn btn-social-icon btn-circle btn-xs btn-google-plus'><i class="fa fa-google-plus"></i></a>
                    <a class='st_pinterest_custom
                    btn btn-social-icon btn-circle btn-xs btn-pinterest'><i class="fa fa-pinterest"></i></a>
                </div>
            </div>
            <div class="side-box store-box hidden-xs">
                <div class="header underline">
                    <div class="text">
                        <div class="title"><h4>TOP STORES</h4></div>
                    </div>
                </div>
                <div class="body">
                    <?php foreach ($hotStores as $hot) : ?>
                        <div class="store-item">
                            <div class="vs">
                                <img
                                    src="<?php echo !empty($hot['Store']['logo']) ? $hot['Store']['logo'] : 'http://lorempixel.com/100/100' ?>"
                                    alt="<?php echo $hot['Store']['name'] ?>"/></div>
                            <div class="tt">
                                <a href="<?php echo $this->Html->url('/' . $hot['Store']['alias']) ?>-coupons"
                                   class="title"><?php echo $hot['Store']['name'] ?></a>

                                <div class="desc-more top-store-desc">
                                    <?php echo $hot['Store']['description'] ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
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
                    <?php foreach ($hotDeals as $deal) : ?>
                        <div class="deal-item">
                            <div class="vs">
                                <img
                                    src="<?php echo !empty($deal['Deal']['deal_image']) ? $deal['Deal']['deal_image'] : 'http://lorempixel.com/200/200' ?>"
                                    alt="<?php echo $deal['Deal']['title'] ?>"/>
                            </div>
                            <div class="tt">
                                <a href="<?php echo $this->Html->url(array('controller' => 'deals', 'action' => 'details', $deal['Deal']['id'])) ?>"
                                   class="title dotdotdot"><?php echo $deal['Deal']['title'] ?></a>
                                <em>Sale: </em>
                                <span
                                    class="deprecated"><?php echo $deal['Deal']['origin_price'] . $deal['Deal']['currency'] ?></span>
                                <span
                                    class="price"><?php echo $deal['Deal']['discount_price'] . $deal['Deal']['currency'] ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="side-box submit-box hidden-xs">
                <div class="header box activator">
                    <div class="icon">
                        <i class="mc mc-pencil"></i>
                    </div>
                    <div class="text">SUBMIT COUPON
                        <span class="arr"></span>
                    </div>
                </div>
                <?php echo $this->element('submit_form') ?>
            </div>
            <div class="side-box coupon-box hidden-xs">
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
            <div class="main-box counter-box hidden-xs">
                <div class="rooow">
                    <div class="col highlight">
                        <span class="title">
                            <i class="fa fa-bar-chart fa-lg"></i>
                        </span>
                    </div>
                    <div class="col active" ng-click="jumpToLocation('couponsCode')">
                        <span class="counter"><?php echo $coupons['count'] ?></span>
                        <span class="title">COUPONS CODE</span>
                    </div>
                    <div class="col" ng-click="jumpToLocation('deals')">
                        <span class="counter"><?php echo $deals['count'] ?></span>
                        <span class="title">DEALS</span>
                    </div>
                    <div class="col" ng-click="jumpToLocation('relatedCoupons')">
                        <span class="counter"><?php echo sizeof($relatedCoupons) ?></span>
                        <span class="title">RELATED COUPONS</span>
                    </div>
                    <div class="col bg">
                        <span class="title">
                            <a href="http://www.mostcoupon.com">www.mostcoupon.com</a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="main-box code-box">
                <div class="header counter" id="couponsCode">
                    <div class="text">
                        <h1 class="title"><?php echo $store['Store']['name'] ?> <?php echo $store['Store']['custom_keywords'] ?></h1>
                        <span class="counter"> (<?php echo $coupons['count'] ?>)</span>
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
                                    <span ng-if="item.Coupon.exclusive" class="label label-danger"> EXCLUSIVE </span>
                                    <span ng-if="item.Coupon.verified" class="label label-success"> 100% VERIFY </span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-7">
                                        <a href="<?php echo $this->Html->url('/go/') ?>{{item.Property.foreign_key_right}}"
                                           coupon_id="{{item.Property.foreign_key_right}}"><h3 class="title hidden-xs">
                                                {{item.Coupon.title_store}}</h3></a>
                                        <a href="<?php echo $this->Html->url('/go/') ?>{{item.Property.foreign_key_right}}"
                                           class="mobile-title dotdotdot visible-xs"
                                           coupon_id="{{item.Property.foreign_key_right}}">{{item.Coupon.title_store}}</a>

                                        <div class="desc desc-more">{{item.Coupon.description_store}}
                                        </div>
                                        <div class="extra hidden-xs" ng-if="item.User.fullname != 'Admin'"> Shared by
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
                                               data-toggle="popover"
                                               coupon-id="{{item.Coupon.id}}">
                                                <i class="fa fa-thumbs-o-up"></i>
                                            </a>
                                            <a ng-class="checkLike(item.Like,userLogin,-1) ? 'btn btn-circle btn-thumb tooltips disliked' : 'btn btn-circle btn-thumb tooltips'"
                                               data-placement="top"
                                               id="dislikeCoupon{{item.Coupon.id}}"
                                               title="I dislike this" ng-click="likeCoupon($index,item.Coupon.id,-1)">
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
                                        <span>Expire: {{item.Coupon.expire_date | formatDateLocal}}</span>
                                    </div>
                                    <div class="stat">
                                        <i class="icon mc mc-comment-o"></i>
                                        <button class="btn btn-link show-comments" type="button" data-toggle="collapse"
                                                data-target="#collapse{{item.Coupon.id}}"
                                                ng-if="item.Coupon.comment_count > 0">
                                            {{item.Coupon.comment_count}} Comment(s)
                                        </button>
                                        <button class="btn btn-link show-comments" type="button" data-toggle="collapse"
                                                data-target="#collapse{{item.Coupon.id}}"
                                                ng-if="item.Coupon.comment_count == 0">
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
                                <div class="cell-caret-right visible-xs-block"><i class="fa fa-angle-right fa-2x"></i>
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
                                                    <a class="btn btn-success btn-block" ng-click="addComment($index)">Post
                                                        Comment</a>
                                                </div>
                                            </form>
                                        <?php else : ?>
                                            <a href="#" class="active" data-toggle="modal" data-target="#sign-in-modal">
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
                    <div class="show-more" ng-if="coupons.count > 10">
                        <a href="#">Show More</a>
                        <i class="icon mc mc-arrow-circle-o-down"></i>
                    </div>
                    <div class="item subscribe-item hidden-xs" ng-if="coupons.coupons.length == 0">
                        <div class="row">
                            <div class="vs col-sm-2"></div>
                            <div class="tt col-sm-10">
                                <h4 class="title"> Never Miss The Best Coupon Codes Again </h4>

                                <p class="desc"> Get our best Amazon Promo Codes with our Deal Alerts and Weekly
                                    Newsletter </p>

                                <form class="input-group">
                                    <input type="text" class="form-control" placeholder="Enter your email"/>
                                            <span class="input-group-btn">
                                                <button class="btn btn-default"> Subscribe</button>
                                            </span>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main-box deal-box">
                <div class="header counter" id="deals">
                    <div class="text">
                        <h2 class="title">DEALS</h2>
                        <span class="counter">(<?php echo $deals['count'] ?>)</span>
                    </div>
                </div>
                <div class="body">
                    <div class="rooow">
                        <?php foreach ($deals['deals'] as $deal) : ?>
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
                                                   data-target="#get-coupon-code" class="btn btn-get-deal hidden"></a>
                                                <a deal_id="<?php echo $deal['Property']['foreign_key_right'] ?>"
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
                    <?php if (sizeof($deals['deals']) > 12) : ?>
                        <div class="rooow">
                            <div class="show-more">
                                <a href="#">Show More</a>
                                <i class="icon mc mc-arrow-circle-o-down"></i>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="main-box related-box ">
                <div class="header counter" id="relatedCoupons">
                    <div class="text">
                        <h2 class="title">COUPONS YOU MAY LIKE</h2>
                        <!--                        <span class="counter">(04)</span>-->
                    </div>
                </div>
                <div class="body">
                    <div class="item related-item" ng-repeat="item in relatedCoupons">
                        <div class="vs">
                            <div class="avatar" ng-if="item.Store.logo">
                                <img ng-src="{{item.Store.logo}}"
                                     alt="{{item.Store.name}}">
                            </div>
                            <div class="card green"
                                 ng-if="!item.Store.logo && ((item.Coupon.coupon_type | uppercase) == 'COUPON CODE')">
                                <div class="body">
                                    <strong>{{item.Coupon.discount}}{{item.Coupon.currency}}</strong>
                                    OFF
                                </div>
                                <div class="footer">
                                    {{item.Coupon.coupon_type}}
                                </div>
                            </div>
                            <div class="card light-green"
                                 ng-if="!item.Store.logo && ((item.Coupon.coupon_type | uppercase) == 'FREE SHIPPING')">
                                <div class="body">
                                    <strong>FREE</strong> SHIPPING
                                </div>
                                <div class="footer"> SALE</div>
                            </div>
                            <div class="card orange"
                                 ng-if="!item.Store.logo && ((item.Coupon.coupon_type | uppercase) == 'GREAT OFFER')">
                                <div class="body">
                                    <strong>GREAT</strong> OFFER
                                </div>
                                <div class="footer"> SALE</div>
                            </div>
                        </div>
                        <div class="tt">
                            <div class="row">
                                <div class="col-sm-7">
                                    <a href="<?php echo $this->Html->url('/go/') ?>{{item.Property.foreign_key_right}}"
                                       coupon_id="{{item.Property.foreign_key_right}}"
                                       onclick="window.open('?c='+$(this).attr('coupon_id'),'_blank');window.open(this.href,'_self');">
                                        <h3 class="title hidden-xs">{{item.Coupon.title_store}}</h3></a>
                                    <a href="<?php echo $this->Html->url('/go/') ?>{{item.Property.foreign_key_right}}"
                                       coupon_id="{{item.Property.foreign_key_right}}"
                                       onclick="window.open('?c='+$(this).attr('coupon_id'),'_blank');window.open(this.href,'_self');"
                                       class="mobile-title visible-xs">{{item.Coupon.title_store}}</a>

                                    <div class="extra hidden-xs" ng-if="item.User.fullname != 'Admin'"> Shared by
                                        <a href="#">{{item.User.fullname}}</a>
                                    </div>
                                    <div class="extra"> View all
                                        <a href="<?php echo $this->Html->url('/') ?>{{item.Store.alias}}-coupons">{{item.Store.name}}
                                            Coupon codes</a>
                                    </div>
                                </div>
                                <div class="col-sm-5 hidden-xs">
                                    <a coupon_id="{{item.Property.foreign_key_right}}"
                                       class="btn btn-get-code a-block"></a>
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
                                           title="I dislike this" ng-click="likeCoupon($index,item.Coupon.id,-1)">
                                            <i class="fa fa-thumbs-o-down fa-flip-horizontal"></i>
                                        </a>

                                        <div>
                                            {{percentLikes(item.Like)}} %
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main-box expired-box hidden-xs">
                <div class="header counter">
                    <div class="text">
                        <h2 class="title">EXPIRED COUPONS AND DEALS</h2>
                        <span class="counter">({{totalExpiredCoupons + totalExpiredDeals}})</span>
                    </div>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col col-sm-6" ng-repeat="deal in expiredDeals">
                            <div class="item expired-item">
                                <div class="rooow">
                                    <div class="vs col-sm-3">
                                        <span>EXPIRED</span>
                                        <span>DEAL</span>
                                    </div>
                                    <div class="tt col-sm-9">
                                        <div class="title ellipsis">
                                            <a href="<?php echo $this->Html->url('/go/') ?>{{deal.Property.foreign_key_right}}"
                                               deal_id="{{deal.Property.foreign_key_right}}">
                                                <h3>{{deal.Deal.title}}</h3>
                                            </a>
                                        </div>
                                        <div class="desc desc-more">
                                            {{deal.Deal.description}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col col-sm-6" ng-repeat="coupon in expiredCoupons">
                            <div class="item expired-item">
                                <div class="rooow">
                                    <div class="vs col-sm-3">
                                        <span>EXPIRED</span>
                                        <span>COUPON</span>
                                    </div>
                                    <div class="tt col-sm-9">
                                        <div class="title ellipsis">
                                            <a href="<?php echo $this->Html->url('/go/') ?>{{coupon.Property.foreign_key_right}}"
                                               coupon_id="{{coupon.Property.foreign_key_right}}">
                                                <h3>{{coupon.Coupon.title_store}}</h3>
                                            </a>
                                        </div>
                                        <div class="desc desc-more">
                                            {{coupon.Coupon.description_store}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rooow" ng-if="(totalExpiredCoupons + totalExpiredDeals) > 8">
                        <div class="show-more">
                            <a id="view-more-expired" ng-click="showMoreExpired(8)">Show More</a>
                            <i class="icon mc mc-arrow-circle-o-down"></i>
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

