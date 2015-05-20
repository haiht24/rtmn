<?php $this->Ng->ngController('DealIndexCtrl') ?>
<?php $this->start('script') ?>
<script type="text/javascript">
    Config.hotDeals = <?php echo !empty($hotDeals) ? json_encode($hotDeals) : '[]' ?>;
    Config.latestDeals = <?php echo !empty($latestDeals) ? json_encode($latestDeals) : '[]' ?>;
</script>

<?php echo $this->end(); ?>
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 links">
                <ul>
                    <li>
                        <a href="<?php echo $this->Html->url('/') ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->Html->url('/deals') ?>">All deals</a>
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
<div class="heading ribbon main-content">
    <h1 class="title font-quark">
        <strong class="text-success">Today's Best</strong> Deals </h1>

    <div class="body">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 form">
                    <label class="title"> Finding by Categories </label>
                    <input ng-model="category" type="text" class="form-control" placeholder="All Deal"/>
                    <span class="icon" ng-click="searchDealByCategory()">
                        <i class="mc mc-chevron-circle-down"></i>
                    </span>
                </div>
                <div class="col-sm-6 text-right">
                    <label class="title"> Jump to </label>
                    <a ng-click="jumpToLocation('latestDeals')" class="btn btn-primary">
                        <strong>Latest</strong>
                    </a>
                    <a ng-click="jumpToLocation('hotdeals')" class="btn btn-primary">
                        <strong>Hot Deal</strong>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="main-content all-deal-content">
        <div class="main-box deal-box hot separated">
            <div class="header link">
                <div class="icon">
                    <div class="label taily hot vertical">HOT</div>
                </div>
                <div class="text">
                    <h3 class="title" id="hotdeals">Hot Deals</h3>
                    <!--  <span class="link">
                        <a href="<?php echo $this->Html->url('/deals##hotdeals') ?>">View all &raquo;</a>
                    </span> -->
                </div>
            </div>
            <div class="body">
                <div class="row">
                    <div class="item deal-item col-sm-3" ng-repeat="deal in hotDeals">
                        <div class="inner" style="margin: 0;position: relative">
                            <div class="nq-tag"> SALE
                                <strong>{{deal.Deal.discount_percent}}%</strong>
                            </div>
                            <div class="image">
                                <img ng-src="{{deal.Deal.deal_image}}" alt="{{deal.Deal.title}}"/>
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
                                           href="<?php echo $this->Html->url('/deals/getCode/') ?>{{deal.Deal.id}}"
                                           data-target="#get-coupon-code" class="btn btn-get-deal hidden"></a>
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
                    <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <div ng-show="!hotDealLimited" class="show-more show-more-hot-deals">
                            <a ng-click="getMoreHotDeals(8)" href="">Show More <i
                                    class="fa fa-arrow-circle-o-down"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-box deal-box separated">
            <div class="header counter">
                <div class="text">
                    <h3 class="title" id="latestDeals">Latest Deals</h3>
                </div>
            </div>
            <div class="body">
                <div class="row">
                    <div class="item deal-item col-sm-3" ng-repeat="deal in latestDeals">
                        <div class="inner" style="margin: 0;position: relative">
                            <div class="nq-tag"> SALE
                                <strong>{{deal.Deal.discount_percent}}%</strong>
                            </div>
                            <div class="image">
                                <img ng-src="{{deal.Deal.deal_image}}" alt="{{deal.Deal.title}}"/>
                            </div>
                            <div class="caption">
                                <div class="price">
                                    <span class="deprecated"
                                          ng-bind="deal.Deal.origin_price + deal.Deal.currency"></span>
                                    <span class="featured"
                                          ng-bind="deal.Deal.discount_price + deal.Deal.currency"></span>
                                </div>
                                <div class="desc dotdotdot" ng-bind="deal.Deal.title" ng-click="goDeal(deal)"></div>
                                <div class="actions">
                                    <div class="inner">
                                        <button class="btn btn-default btn-square">
                                            <i class="icon mc mc-star"></i>
                                        </button>
                                        <a data-toggle="modal"
                                           deal_id="{{deal.Property.foreign_key_right}}"
                                           href="<?php echo $this->Html->url('/deals/getCode/') ?>{{deal.Deal.id}}"
                                           data-target="#get-coupon-code" class="btn btn-get-deal hidden"></a>
                                        <a deal_id="{{deal.Property.foreign_key_right}}"
                                           class="btn btn-primary">
                                            <span>Get Deal</span>
                                            <i class="touch mc mc-touch"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <div ng-show="!latestDealLimited" class="show-more show-more-latest-deals">
                            <a ng-click="getMoreLatestDeals(8)" href="">Show More <i
                                    class="fa fa-arrow-circle-o-down"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>