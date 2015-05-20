<?php $this->Ng->ngController('PrivacyCtrl'); ?>
<?php
    $this->Ng->ngInit(
        [
            'docs' => isset($docs) ? $docs : [],
            'stores' => isset($stores) ? $stores : []
        ]
    );
?>
<div class="container main-content paper show-text-content">
    <h1 class="title font-quark">
        <strong class="text-success" ng-bind-html = "title|trusted"></strong> Mostcoupon
    </h1>
    <div class="body" ng-bind-html = "content|trusted"></div>

</div>
<!-- Slide Stores -->
<div class="container" style="margin-bottom: 55px;">
    <div class="aligned-store-container">
        <div class="store-list slider-box">
            <div class="caption"> OUR <strong>STORE</strong> </div>
            <div class="slider flexslider" data-flexslider-animation="slide" data-flexslider-animation-speed="2000" data-flexslider-control-nav="false" data-flexslider-direction-nav="true" data-flexslider-selector=".slides .slide" data-flexslider-item-width="85" data-flexslider-item-margin="15" data-flexslider-max-items="9">
                <div class="slides">
                    <a ng-repeat="s in stores.stores"
                       ng-href="<?php echo $this->Html->url('/') ?>{{s.Store.alias}}-coupons" class="slide"
                       style="background-image: url('{{s.Store.logo}}')"></a>
                </div>
            </div>
        </div>
    </div>
</div>
