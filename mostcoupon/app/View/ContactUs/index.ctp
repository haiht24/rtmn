<?php $this->Ng->ngController('ContactUsCtrl'); ?>
<?php
    $this->Ng->ngInit(
        [
            'docs' => isset($docs) ? $docs : [],
            'hotStores' => isset($hotStores) ? json_encode($hotStores) : [],
            'hotDeals' => isset($hotDeals) ? json_encode($hotDeals) : [],
            'stores' => isset($stores) ? json_encode($stores) : []
        ]
    );
    // reCaptcha public key
    //$publickey = '6LcT5gATAAAAAGi5xWVPK82sz9YipvJtaD2btugZ';
    $publickey = '6Lfr5QATAAAAAOFjNFPOk5lQwhCQsIUZ2Ez23OvL';
?>
<script>
    var SendPath = '<?php echo $this->Html->url(array('controller' => 'ContactUs', 'action' => 'send')); ?>';
</script>



<div class="container main-content contact-us-content">
    <div class="row">
        <div class="col-sm-3 side">
            <div class="side-box store-box hidden-xs">
                <div class="header underline">
                    <div class="text">
                        <div class="title"> TOP STORES</div>
                    </div>
                </div>
                <div class="body">
                    <div class="store-item" ng-repeat="s in hotStores.stores track by $index">
                        <div class="vs">
                            <img ng-src="{{s.Store.logo}}"
                                 alt="{{s.Store.name}}"/></div>
                        <div class="tt">
                            <a href="<?php echo $this->Html->url('/') ?>{{s.Store.alias}}-coupons"
                               class="title">{{s.Store.name}}</a>

                            <div class="desc-more top-store-desc">
                                {{s.Store.description}}
                            </div>
                        </div>
                    </div>
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
                    <div class="deal-item" ng-repeat="deal in hotDeals.deals">
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
        </div>
        <div class="col-sm-9 main">
            <h1 class="page-title font-quark">
            <strong class="text-success">Contact</strong> Us </h1>
            <p class="desc" ng-bind-html = "text1|limitTo:262|trusted"></p>
            <form class="letter gray-form" name="frmContactUs" novalidate>
                <div class="paper row">
                    <div class="col-sm-6">
                        <input ng-model = "name" name="name" type="text" ng-minlength="6" ng-maxlength="50"
                        class="form-control" placeholder="Your name*" required />
                        <small class = "text-danger" ng-show = "showError && frmContactUs.name.$error.required">
                            <?php echo __('require field', ['Name']); ?>
                        </small>
                        <small class = "text-danger" ng-show = "showError && frmContactUs.name.$error.minlength">
                            <?php echo __('minlen', ['Name', 6]); ?>
                        </small>
                        <small class = "text-danger" ng-show = "showError && frmContactUs.name.$error.maxlength">
                            <?php echo __('maxlen', ['Name', 50]); ?>
                        </small>
                    </div>
                    <div class="col-sm-6">
                        <input ng-model = "email" name="email" type="email" class="form-control" ng-minlength="6"
                        placeholder="Your Email*" required  /> 
                        <small class = "text-danger" ng-show = "showError && frmContactUs.email.$error.required">
                            <?php echo __('require field', ['Email']); ?>
                        </small>
                        <small class = "text-danger" ng-show = "showError && frmContactUs.email.$error.email">
                            <?php echo __('invalid email'); ?>
                        </small> 
                        <small class = "text-danger" ng-show = "showError && frmContactUs.email.$error.minlength">
                            <?php echo __('minlen', ['Email', 6]); ?>
                        </small>
                    </div>
                    <div class="col-sm-6">
                        <input ng-model = "subject" name="subject" type="text" ng-minlength="6" ng-maxlength="200"
                        class="form-control" placeholder="Your Subject*" required  /> 
                        <small class = "text-danger" ng-show = "showError && frmContactUs.subject.$error.required">
                            <?php echo __('require field', ['Subject']); ?>
                        </small>
                        <small class = "text-danger" ng-show = "showError && frmContactUs.subject.$error.minlength">
                            <?php echo __('minlen', ['Subject', 6]); ?>
                        </small>
                        <small class = "text-danger" ng-show = "showError && frmContactUs.subject.$error.maxlength">
                            <?php echo __('maxlen', ['Subject', 200]); ?>
                        </small>
                    </div>
                    <div class="col-sm-6">
                        <input ng-model = "keyword" name="keyword" type="text" ng-minlength="2" ng-maxlength="20"
                        class="form-control" placeholder="Enter Keyword*" required /> 
                        <small class = "text-danger" ng-show = "showError && frmContactUs.keyword.$error.required">
                            <?php echo __('require field', ['Keyword']); ?>
                        </small>
                        <small class = "text-danger" ng-show = "showError && frmContactUs.keyword.$error.minlength">
                            <?php echo __('minlen', ['Keyword', 2]); ?>
                        </small>
                        <small class = "text-danger" ng-show = "showError && frmContactUs.keyword.$error.maxlength">
                            <?php echo __('maxlen', ['Keyword', 20]); ?>
                        </small>
                    </div>
                    <div class="col-sm-12">
                        <textarea ng-model = "message" name="message" ng-minlength="6" ng-maxlength="500"
                        class="form-control" rows="6" placeholder="Your message" required></textarea>
                         <small class = "text-danger" ng-show = "showError && frmContactUs.message.$error.required">
                            <?php echo __('require field', ['Message']); ?>
                        </small>
                        <small class = "text-danger" ng-show = "showError && frmContactUs.message.$error.minlength">
                            <?php echo __('minlen', ['Message', 6]); ?>
                        </small>
                        <small class = "text-danger" ng-show = "showError && frmContactUs.message.$error.maxlength">
                            <?php echo __('maxlen', ['Message', 500]); ?>
                        </small>
                    </div>

                    <div class="col-sm-6">
                        <div class="g-recaptcha" data-sitekey="<?php echo $public_key ?>"></div>
                    </div>

                    <div class="col-sm-6">
                        <button ng-bind = "sendUsText" ng-click = "send()" id="btnSendUs" class="btn btn-primary btn-submit btn-block"></button>
                    </div>
                </div>
                <div class="cover" ng-bind-html = "text2|limitTo:680|trusted"></div>
            </form>
            <div class="store-list slider-box">
                <div class="caption"> OUR <strong>STORES</strong> </div>
                <div class="slider flexslider" data-flexslider-animation="slide" data-flexslider-animation-speed="2000"
                     data-flexslider-control-nav="false" data-flexslider-direction-nav="true"
                     data-flexslider-selector=".slides .slide" data-flexslider-item-width="95"
                     data-flexslider-item-margin="15" data-flexslider-max-items="9">
                    <div class="slides" >
                        <a ng-repeat="s in stores.stores"
                           ng-href="<?php echo $this->Html->url('/') ?>{{s.Store.alias}}-coupons"
                           class="slide" style="background-image: url('{{s.Store.logo}}')"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>