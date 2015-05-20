<?php $this->Ng->ngController('HomeIndexCtrl') ?>
<?php $this->start('script') ?>
<script type="text/javascript">
    Config.hotdeals = <?php echo !empty($hotdeals) ? json_encode($hotdeals) : '[]' ?>;
    Config.lastDeals = <?php echo !empty($latestDeals) ? json_encode($latestDeals) : '[]' ?>;
    Config.listCategories = <?php echo !empty($categories) ? json_encode($categories) : '[]' ?>;
    Config.message = [
        {'field': 'show_email', 'condition': 'required', 'content': '<?php echo __('Email is required') ?>'},
        {'field': 'show_email', 'condition': 'email', 'content': '<?php echo __('Email is invalid') ?>'},
        {'field': 'show_username', 'condition': 'required', 'content': '<?php echo __('Email is required') ?>'},
        {'field': 'show_password', 'condition': 'required', 'content': '<?php echo __('Password is required') ?>'},
        {
            'field': 'show_password',
            'condition': 'minlength',
            'content': '<?php echo __('Minimum length of password is 6 characters') ?>'
        },
        {
            'field': 'show_password',
            'condition': 'maxlength',
            'content': '<?php echo __('Maximum length of password length is 30 characters') ?>'
        },
        {
            'field': 'show_password',
            'condition': 'pattern',
            'content': '<?php echo __('Password musts contain number and letter') ?>'
        },
        {
            'field': 'show_confirmPassword',
            'condition': 'match',
            'content': '<?php echo __('Confirm password does not match') ?>'
        },
    ];
</script>

<?php echo $this->end(); ?>
<div class="container hidden-xs">
    <div class="home-slider">
        <div class="slider flexslider" data-flexslider-animation="fade" data-flexslider-animation-speed="2000"
             data-flexslider-control-nav="true" data-flexslider-direction-nav="false" data-flexslider-selector=".slide">
            <div class="slide">
                <div class="image"
                     style="background-image: url('<?php echo $this->Html->url('/assets/photos/home-slide-01.png') ?>')"></div>
                <div class="caption">
                    <p>
                        <strong>
                            <em><?php echo $totalCoupons ?></em> FREE COUPON CODES </strong>
                    </p>

                    <p> &amp; Coupon, Discount Codes added this Week Update daily, Get it today! </p>

                    <div class="view-more">
                        <button class="btn btn-primary btn-outline dark-text" style="border-color: white;">
                            <span>View More</span>
                            <i class="icon mc mc-chevron-circle-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="slide">
                <div class="image"
                     style="background-image: url('<?php echo $this->Html->url('/assets/photos/home-slide-01.png') ?>')"></div>
                <div class="caption">
                    <p>
                        <strong>
                            <em><?php echo $totalCoupons ?></em> FREE COUPON CODES </strong>
                    </p>

                    <p> &amp; Coupon, Discount Codes added this Week Update daily, Get it today! </p>

                    <div class="view-more">
                        <button class="btn btn-primary btn-outline dark-text" style="border-color: white;">
                            <span>View More</span>
                            <i class="icon mc mc-chevron-circle-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="home-search hidden-xs">
        <div class="inner">
            <div class="title">
                <img src="<?php echo $this->Html->url('/assets/img/sprite1.png') ?>" alt="" style="width: 100%"/>

                <div class="text">Look For Coupon Codes To Save More At Your Favourite Stores
                    <div class="sub">
                        <em class="highlighted"> 9,419 </em> Free Coupon Codes &amp; Discount Codes added this Week
                    </div>
                </div>
            </div>
            <form class="form">
                <div class="input-group">
                    <div class="input-group-btn">
                        <button type="button" class="btn dropdown-toggle btn-adv">
                            <i class="icon mc mc-sort-desc"></i>
                        </button>
                    </div>
                    <input type="text" class="form-control" placeholder="Search by Store name, deal or tag event ..."/>

                    <div class="input-group-btn">
                        <button class="btn btn-search"> Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="store-list slider flexslider" data-flexslider-animation="slide" data-flexslider-animation-speed="2000"
         data-flexslider-control-nav="false" data-flexslider-direction-nav="true"
         data-flexslider-selector=".slides .slide" data-flexslider-item-width="85"
         data-flexslider-item-margin="15" data-flexslider-max-items="10">
        <div class="slides">
            <?php foreach ($stores as $store) : ?>
                <a href="<?php echo $this->Html->url('/' . $store['Store']['alias']) ?>-coupons" class="slide"
                   style="background-image: url('<?php echo (!empty($store['Store']['logo'])) ? $store['Store']['logo'] : 'http://lorempixel.com/50/50' ?>')"></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="container">
    <div class="main-content home-content">
        <div class="row">
            <div class="col-sm-3 side hidden-xs">
                <div class="side-box category-box">
                    <div class="header box activator">
                        <div class="icon">
                            <i class="mc mc-list"></i>
                        </div>
                        <div class="text"> CATEGORIES
                            <span class="arr"></span>
                        </div>
                    </div>
                    <div class="body list-group">
                        <a ng-click="loadDealsByCategory(category.Category.id)"
                           class="list-group-item" ng-repeat="(index, category) in listCategories">
                  <span class="icon">
                    <i class="{{category.Category.icon}}"></i>
                  </span>
                  <span class="text">
                    <span class="heading"> {{category.Category.name }} </span>
                    <span class="sub"> ({{category.Category.store_count}} Stores) </span>
                  </span>
                            <i class="arr mc mc-chevron-circle-right"></i>
                        </a>
                        <a ng-click="showMoreCategories(5)" id="view-more-categories"
                           class="list-group-item not-a-list-group-item view-all"> Show more Categories </a>
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
            </div>
            <div class="col-sm-9 main">
                <div class="main-box deal-box hot">
                    <div class="header link">
                        <div class="icon">
                            <div class="label taily hot vertical">HOT</div>
                        </div>
                        <div class="text">
                            <h3 class="title">Hot Deals</h3>
                              <span class="link">
                                <a href="<?php echo $this->Html->url('/deals##hotdeals') ?>">View all &raquo;</a>
                              </span>
                        </div>
                    </div>
                    <div class="body">
                        <div class="deal-loading">
                            <div>
                                <i class='fa fa-spinner fa-pulse fa-2x'></i>
                            </div>
                        </div>
                        <div class="rooow">
                            <div class="item deal-item col-sm-3" ng-repeat="deal in hotdeals">
                                <div class="inner">
                                    <div class="nq-tag"> SALE
                                        <strong>{{deal.Deal.discount_percent}}%</strong>
                                    </div>
                                    <div class="image">
                                        <img ng-src="{{deal.Deal.deal_image}}" alt="{{deal.Deal.title}}" />
                                    </div>
                                    <div class="caption">
                                        <div class="price">
                                            <span class="deprecated"
                                                  ng-bind="deal.Deal.origin_price + deal.Deal.currency"></span>
                                            <span class="featured"
                                                  ng-bind="deal.Deal.discount_price + deal.Deal.currency"></span>
                                        </div>

                                        <div class="desc dotdotdot" ng-bind="deal.Deal.title"
                                             ng-click="goDeal(deal)"></div>

                                        <div class="actions">
                                            <div class="inner">
                                                <button class="btn btn-default btn-square">
                                                    <i class="icon mc mc-star"></i>
                                                </button>
                                                <a data-toggle="modal"
                                                   deal_id="{{deal.Property.foreign_key_right}}"
                                                   href="{{baseUrl}}/deals/getCode/{{deal.Deal.id}}"
                                                   data-target="#get-coupon-code"
                                                   class="btn btn-get-deal hidden"></a>
                                                <a deal_id="{{deal.Property.foreign_key_right}}"
                                                   class="btn btn-primary">
                                                    <span>Get Deal</span>
                                                    <i class="touch mc mc-touch"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="banner main-box">
                    <?php foreach($ads['ads'] as $a): ?>
                    <?php if($a['Property']['key'] == 'ad_home_pos_1'): ?>
                    <a href="#" class="a-block">
                        <img src="<?php echo $a['Property']['foreign_key_left'] ?>" />
                    </a>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <div class="main-box deal-box latest">
                    <div class="header link">
                        <div class="text">
                            <h3 class="title">Latest Deals</h3>
                          <span class="link">
                            <a href="<?php echo $this->Html->url(array('controller' => 'deals', 'action' => 'index', 'latest')) ?>">View
                                all &raquo;</a>
                          </span>
                        </div>
                    </div>
                    <div class="body">
                        <div class="rooow">
                            <div class="deal-loading">
                                <div>
                                    <i class='fa fa-spinner fa-pulse fa-2x'></i>
                                </div>
                            </div>
                            <div class="item deal-item col-sm-3" ng-repeat="deal in lastDeals">
                                <div class="inner">
                                    <div class="nq-tag"> SALE
                                        <strong>{{deal.Deal.discount_percent}}%</strong>
                                    </div>
                                    <div class="image">
                                        <img ng-src="{{deal.Deal.deal_image}}" alt="{{deal.Deal.title}}">
                                    </div>
                                    <div class="caption">
                                        <div class="price">
                                                <span
                                                    class="deprecated">{{deal.Deal.origin_price+deal.Deal.currency}}</span>
                                                <span
                                                    class="featured">{{deal.Deal.discount_price+deal.Deal.currency}}</span>
                                        </div>
                                        <div class="desc dotdotdot" style="cursor: pointer;" ng-click="goDeal(deal)">
                                            {{deal.Deal.title}}
                                        </div>
                                        <div class="actions">
                                            <div class="inner">
                                                <button class="btn btn-default btn-square">
                                                    <i class="icon mc mc-star"></i>
                                                </button>
                                                <a data-toggle="modal"
                                                   deal_id="{{deal.Property.foreign_key_right}}"
                                                   href="{{baseUrl}}/deals/getCode/{{deal.Deal.id}}"
                                                   data-target="#get-coupon-code"
                                                   class="btn btn-get-deal hidden"></a>
                                                <a deal_id="{{deal.Property.foreign_key_right}}"
                                                   class="btn btn-primary">
                                                    <span>Get Deal</span>
                                                    <i class="touch mc mc-touch"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="banner rooow">
                            <?php foreach($ads['ads'] as $a): ?>
                            <?php if($a['Property']['key'] == 'ad_home_pos_2'): ?>
                            <a href="<?php echo $a['Property']['foreign_key_right'] ?>" class="a-block">
                                <img src="<?php echo $a['Property']['foreign_key_left'] ?>" />
                            </a>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="main-box subscribe-box hidden-xs">
                    <p>Saving sent straight to your inbox. Subscribe to the Best of Most Coupon</p>

                    <form class="input-group">
                        <input ng-model='emailSubscribe' type="email" class="form-control"
                               placeholder="Enter your email"/>
                        <span class="input-group-btn">
                            <button ng-click="subscribe()" class="btn btn-primary dark-text"> Subscribe</button>
                        </span>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>