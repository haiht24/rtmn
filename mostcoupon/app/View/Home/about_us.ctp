<?php $this->Ng->ngController('aboutusCtrl'); ?>
<?php
$this->Ng->ngInit(
    [
        'docs' => isset($docs) ? $docs : [],
        'stores' => isset($stores) ? $stores : []
    ]
);

?>
<div class="container main-content about-us-content">
    <h1 class="page-title font-quark">
        <strong ng-bind="welcome_1"></strong>
    </h1>

    <p class="sub-title" ng-bind="welcome_2"></p>

    <div class="row highlight">
        <div class="item col-sm-3">
            <div class="inner">
                <div class="ooh"><i class="icon mc mc-users"></i></div>
                <div class="aah">
                    <div class="how" ng-bind="memValue"></div>
                    <div class="what"></div>
                </div>
                <p ng-bind="memText"></p>

                <div class="more"><a href="##">READ MORE</a></div>
            </div>
        </div>
        <div class="item col-sm-3">
            <div class="inner">
                <div class="ooh"><i class="icon mc mc-barcode"></i></div>
                <div class="aah">
                    <div class="how" ng-bind="couponValue"></div>
                    <div class="what"></div>
                </div>
                <p ng-bind="couponText"></p>

                <div class="more"><a href="##">READ MORE</a></div>
            </div>
        </div>
        <div class="item col-sm-3">
            <div class="inner">
                <div class="ooh"><i class="icon mc mc-store"></i></div>
                <div class="aah">
                    <div class="how" ng-bind="storeValue"></div>
                    <div class="what"></div>
                </div>
                <p ng-bind="storeText"></p>

                <div class="more"><a href="##">READ MORE</a></div>
            </div>
        </div>
        <div class="item col-sm-3">
            <div class="inner">
                <div class="ooh"><i class="icon mc mc-heart"></i></div>
                <div class="aah">
                    <div class="how" ng-bind="followValue"></div>
                    <div class="what"></div>
                </div>
                <p ng-bind="followText"></p>

                <div class="more"><a href="##">READ MORE</a></div>
            </div>
        </div>
    </div>
</div>
<!-- SLIDE -->
<div class="team-slider">
    <div class="container">
        <div class="slider flexslider" data-flexslider-animation="slide" data-flexslider-animation-speed="2000"
             data-flexslider-control-nav="false" data-flexslider-direction-nav="true"
             data-flexslider-selector=".slides .slide" data-flexslider-item-width="400" data-flexslider-item-margin="0">
            <div class="slides">
                <div ng-repeat="img in imgs" class="team-member slide" style="background-image:url('{{img[0]}}')">
                    <div class="inner">
                        <div class="name">{{img[1]}}</div>
                        <div class="title">{{img[2]}}</div>
                        <div class="social">
                            <a href="##" class="item"> <i class="mc mc-facebook"></i> </a>
                            <a href="##" class="item"> <i class="mc mc-twitter"></i> </a>
                            <a href="##" class="item"> <i class="mc mc-google-plus"></i> </a>
                            <a href="##" class="item"> <i class="mc mc-linkedin"></i> </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About US -->
<div class="container">
    <div class="who-we-are">
        <div class="row">
            <div class="section col-sm-5">
                <h2 class="title" ng-bind="aboutTitle"></h2>

                <div class="desc" ng-bind-html="aboutContent|trusted"></div>
            </div>
            <div class="section col-sm-5 col-sm-offset-2">
                <h2 class="title"> Our Skills </h2>

                <div class="skill-set">
                    <div class="skill">
                        <label class="skill-name" ng-bind="sk1Title"></label>

                        <div class="skill-level progress">
                            <div class="progress-bar" style="width:{{sk1Value}}%"><span>{{sk1Value}}%</span></div>
                        </div>
                    </div>
                    <div class="skill">
                        <label class="skill-name" ng-bind="sk2Title"></label>

                        <div class="skill-level progress">
                            <div class="progress-bar" style="width:{{sk2Value}}%"><span>{{sk2Value}}%</span></div>
                        </div>
                    </div>
                    <div class="skill">
                        <label class="skill-name" ng-bind="sk3Title"></label>

                        <div class="skill-level progress">
                            <div class="progress-bar" style="width:{{sk3Value}}%"><span>{{sk3Value}}%</span></div>
                        </div>
                    </div>
                    <div class="skill">
                        <label class="skill-name" ng-bind="sk4Title"></label>

                        <div class="skill-level progress">
                            <div class="progress-bar" style="width:{{sk4Value}}%"><span>{{sk4Value}}%</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Slide Stores -->
<div class="container" style="margin-bottom: 20px">
    <div class="aligned-store-container">
        <div class="store-list slider-box">
            <div class="caption"> OUR <strong>STORE</strong></div>
            <div class="slider flexslider" data-flexslider-animation="slide" data-flexslider-animation-speed="2000"
                 data-flexslider-control-nav="false" data-flexslider-direction-nav="true"
                 data-flexslider-selector=".slides .slide" data-flexslider-item-width="85"
                 data-flexslider-item-margin="15" data-flexslider-max-items="9">
                <div class="slides">
                    <a ng-repeat="s in stores.stores"
                       ng-href="<?php echo $this->Html->url('/') ?>{{s.Store.alias}}-coupons" class="slide"
                       style="background-image: url('{{s.Store.logo}}')"></a>

                </div>
            </div>
        </div>
    </div>
</div>