<?php $this->Ng->ngController('CategoryDetailsCtrl') ?>
<?php
$this->Ng->ngInit(
    [
        'categoryID' => isset($category['Category']['id']) ? $category['Category']['id'] : '',
        'userLogin' => $this->Session->check('User.id') ? $this->Session->read('User.id') : '',
        'category' => !empty($category) ? $category : '',
        'hotDeals' => !empty($hotDeals) ? $hotDeals : [],
        'coupons' => !empty($coupons) ? $coupons : [],
        'deals' => !empty($deals) ? $deals : [],
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
                    <li id="b" itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemprop="child" itemref="c">
                        <a href="<?php echo $this->Html->url('/categories/'). $category['Category']['alias'] ?>"
                           itemprop="url">
                            <span itemprop="title"><?php echo $category['Category']['name'] ?></span>
                        </a>
                    </li>
                </ul>
            </div>
            <form class="col-sm-8 search">
                <div class="input">
                    <input type="text" class="form-control" placeholder="Search by store name, deal, coupon"/>
                    <i class="icon mc mc-search"></i>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container">
    <div class="main-content page-top-coupon-content">
        <div class="row">
            <div class="col-sm-3 side">
                <div class="side-box promo-box textual category-box">
                    <p><?php echo $category['Category']['description'] ?></p>

                    <div class="social">
                        <span>Share</span>
                        <a class="btn btn-social-icon btn-circle btn-xs btn-facebook"><i class="fa fa-facebook"></i></a>
                        <a class="btn btn-social-icon btn-circle btn-xs btn-twitter"><i class="fa fa-twitter"></i></a>
                        <a class="btn btn-social-icon btn-circle btn-xs btn-google-plus"><i
                                class="fa fa-google-plus"></i></a>
                        <a class="btn btn-social-icon btn-circle btn-xs btn-pinterest"><i
                                class="fa fa-pinterest"></i></a>
                    </div>
                </div>
                <div class="side-box store-box">
                    <div class="header underline">
                        <div class="text">
                            <div class="title"><h4>TOP STORES</h4></div>
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
                            <div class="title"> <h4>HOT DEAL!</h4>
                                <span class="label hot small">HOT</span>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="deal-item" ng-repeat="deal in hotDeals">
                            <div class="vs">
                                <img ng-src="{{deal.Deal.deal_image}}"/>
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
                            <div class="title"> <h4>STORE COUPON</h4></div>
                        </div>
                    </div>
                    <div class="body">
                        <?php echo $this->element('store_coupon') ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-9 main">
                <div class="main-box code-box" id="category-deal">
                    <div class="header counter">
                        <div class="text">
                            <h1 class="title" id="related-deal"><?php echo $category['Category']['name'] ?> Coupon & Discount Codes</h1>
                            <span class="counter">({{coupons.count}})</span>
                        </div>
                    </div>
                    <div class="body">
                        <div class="item related-item" ng-repeat="item in coupons.coupons">
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
                                <div class="labels">
                                    <span ng-if="item.Coupon.event_id"
                                          class="label label-primary">{{item.Event.name}}</span>
                                    <span ng-if="item.Coupon.exclusive" class="label label-danger"> EXCLUSIVE </span>
                                    <span ng-if="item.Coupon.expire_date" class="label label-default"> End: {{item.Coupon.expire_date | formatDateLocal}} </span>
                                    <span ng-if="item.Coupon.verified" class="label label-success"> 100% VERIFY </span>
                                </div>
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
                    <div class="item subscribe-item hidden-xs">
                        <div class="row">
                            <div class="vs col-sm-2"></div>
                            <div class="tt col-sm-10">
                                <h4 class="title"> Never Miss The Best Coupon Codes Again </h4>

                                <p class="desc"> Get our best Amazon Promo Codes with our Deal Alerts and Weekly
                                    Newsletter </p>

                                <form class="input-group">
                                    <input ng-model = "emailSubscribe" type="email" class="form-control" placeholder="Enter your email"/>
                                    <span class="input-group-btn">
                                        <button ng-click = "subscribe()" class="btn btn-default"> Subscribe</button>
                                    </span>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="main-box deal-box" id="related-deal">
                    <div class="header counter">
                        <div class="text">
                            <h2 class="title" id="category-deal">
                                <?php echo $category['Category']['name'] ?>'s Deals</h2>
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
                                                       data-target="#get-coupon-code"
                                                       class="btn btn-get-deal hidden"></a>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
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