<?php $this->Ng->ngController('LandingIndexCtrl') ?>
<?php $this->start('script') ?>
<script type="text/javascript">

    Config.message = [
        {'field': 'show_email', 'condition': 'required', 'content': '<?php echo __('Email is required') ?>'},
        {'field': 'show_email', 'condition': 'email', 'content': '<?php echo __('Email is invalid') ?>'},
        {'field': 'show_username', 'condition': 'required', 'content': '<?php echo __('Email is required') ?>'},


        {'field': 'show_password', 'condition': 'required', 'content': '<?php echo __('Password is required') ?>'},
        {'field': 'show_password', 'condition': 'minlength', 'content': '<?php echo __('Minimum length of password is 6 characters') ?>'},
        {'field': 'show_password', 'condition': 'maxlength', 'content': '<?php echo __('Maximum length of password length is 30 characters') ?>'},
        {'field': 'show_password', 'condition': 'pattern', 'content': '<?php echo __('Password musts contain number and letter') ?>'},

        {'field': 'show_confirmPassword', 'condition': 'match', 'content': '<?php echo __('Confirm password does not match') ?>'},           
    ];

</script>

<?php echo $this->end(); ?>
<div class="container">
    <div class="home-slider">
        <div class="slider flexslider" data-flexslider-animation="fade" data-flexslider-animation-speed="2000" data-flexslider-control-nav="true" data-flexslider-direction-nav="false" data-flexslider-selector=".slide">
            <div class="slide">
                <div class="image" style="background-image: url('<?php echo $this->Html->url('/assets/photos/home-slide-01.png') ?>')"> </div>
                <div class="caption">
                    <p>
                        <strong>
                            <em>9,419</em> FREE COUPON CODES </strong>
                    </p>
                    <p> &amp; Coupon, Discount Codes added this Week Update daily, Get it today! </p>
                    <div class="view-more">
                        <button class="btn btn-primary btn-outline dark-text">
                            <span>View More</span>
                            <i class="icon mc mc-chevron-circle-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="slide">
                <div class="image" style="background-image: url('<?php echo $this->Html->url('/assets/photos/home-slide-01.png') ?>')"> </div>
                <div class="caption">
                    <p>
                        <strong>
                            <em>9,149</em> FREE COUPON CODES </strong>
                    </p>
                    <p> &amp; Coupon, Discount Codes added this Week Update daily, Get it today! </p>
                    <div class="view-more">
                        <button class="btn btn-primary btn-outline dark-text">
                            <span>View More</span>
                            <i class="icon mc mc-chevron-circle-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="regbox hidden-xs">
            <div class="section">
                <div class="title pull-left"> Register </div>
                <div class="pull-right">
                    <a href="##" class="text-success underline">
                        <em>Lost password</em>
                    </a>
                </div>
            </div>
            <div class="section">
                <input type="text" class="form-control" placeholder="your username *" />
                <input type="text" class="form-control" placeholder="your email *" />
                <input type="text" class="form-control" placeholder="your password *" />
            <input type="text" class="form-control" placeholder="confirm password *" /> </div>
            <div class="section">
                <button class="btn btn-primary btn-block dark-text"> Register </button>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="home-search hidden-sm">
        <div class="inner">
            <div class="title"> Look For Coupon Codes To Save More At Your Favourite Stores
                <div class="sub">
                    <em class="highlighted"> 9,419 </em> Free Coupon Codes &amp; Discount Codes added this Week </div>
            </div>
            <form class="form">
                <div class="input-group">
                    <div class="input-group-btn">
                        <button type="button" class="btn dropdown-toggle btn-adv">
                            <i class="icon mc mc-sort-desc"></i>
                        </button>
                    </div>
                    <input type="text" class="form-control" placeholder="Search by Store name, deal or tag event ..." />
                    <div class="input-group-btn">
                        <button class="btn btn-search"> Search </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="store-list slider flexslider" data-flexslider-animation="slide" data-flexslider-animation-speed="2000" data-flexslider-control-nav="false" data-flexslider-direction-nav="true" data-flexslider-selector=".slides .slide" data-flexslider-item-width="85"
        data-flexslider-item-margin="15">
        <div class="slides">  
            <?php foreach ($stores as $store) : ?>
                <a href="<?php echo $this->Html->url('/' . $store['Store']['alias']) ?>-coupons" class="slide"
                   style="background-image: url('<?php echo (!empty($store['Store']['social_image'])) ? $store['Store']['social_image'] : 'http://lorempixel.com/50/50' ?>')"></a>
                 
            <?php endforeach; ?>        
        </div>
    </div>
</div>
<div class="container">
    <div class="main-content home-content">
        <div class="row">
            <div class="col-md-5 side">
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
                        <?php foreach ($categories as $category) : ?>
                            <a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'details', $category['Category']['id'])) ?>" class="list-group-item">
                                <span class="icon">
                                    <i class="mc mc-soccer-ball-o"></i>
                                </span>
                                <span class="text">
                                    <span class="heading"><?php echo $category['Category']['name'] ?></span>
                                    <span class="sub"> (<?php echo $category[0]['store_count'] ?> Stores) </span>
                                </span>
                                <i class="arr mc mc-chevron-circle-right"></i>
                            </a>
                        <?php endforeach; ?>

                        <a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index')) ?>" class="list-group-item not-a-list-group-item view-all"> View All Categories </a>
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
                    <form class="body form">
                        <input type="text" class="form-control" placeholder="http://www.store.com" />
                        <input type="text" class="form-control" placeholder="Enter title coupon" />
                        <div class="bubbles">
                            <div class="bubble primary"> COUPON </div>
                            <div class="bubble"> DEAL </div>
                        </div>
                        <input type="text" class="form-control" placeholder="Enter your code" />
                        <input type="text" class="form-control" placeholder="Enter image url" />
                        <input type="text" class="form-control" placeholder="Enter product link" />
                        <select class="form-control">
                            <option>Choose event (optional)</option>
                        </select>
                        <textarea class="form-control" placeholder="Example .."></textarea>
                        <input type="text" class="form-control" placeholder="yy/mm/dd" />
                        <div class="captcha">
                            <div class="img">
                            <img src="http://lorempixel.com/90/30" alt="" /> </div>
                            <div class="input">
                            <input type="text" class="form-control" /> </div>
                            <a href="##">
                                <i class="refresh"></i>
                            </a>
                        </div>
                        <button class="btn btn-primary btn-block"> SUBMIT </button>
                    </form>
                </div>
            </div>
            <div class="col-md-15 main">
                <div class="main-box deal-box hot">
                    <div class="header link">
                        <div class="icon">
                            <div class="label taily hot vertical">HOT</div>
                        </div>
                        <div class="text">
                            <h3 class="title">Hot Deals</h3>
                            <span class="link">
                                <a href="<?php echo $this->Html->url(array('controller' => 'deals', 'action' => 'index', 'hotdeal')) ?>">View all &raquo;</a>
                            </span>
                        </div>
                    </div>
                    <div class="body">
                        <div class="rooow">
                            <?php foreach ($hotdeals as $deal) : ?>
                            <div class="item deal-item col-md-5">
                                <div class="inner">
                                    <div class="nq-tag"> SALE
                                        <strong><?php echo $deal['Deal']['discount_percent'] ?></strong>
                                    </div>
                                    <div class="image" style="background-image:url('<?php echo (!empty($deal['Deal']['deal_image'])) ? $deal['Deal']['deal_image'] : 'http://lorempixel.com/200/200' ?>')"> </div>
                                    <div class="caption">
                                        <div class="price">
                                            <span class="deprecated"><?php echo $deal['Deal']['origin_price'].$deal['Deal']['currency'] ?></span>
                                            <span class="featured"><?php echo $deal['Deal']['discount_price'].$deal['Deal']['currency'] ?></span>
                                        </div>
                                        <div class="desc"><?php echo $deal['Deal']['title'] ?></div>
                                        <div class="actions">
                                            <div class="inner">
                                                <button class="btn btn-default btn-square">
                                                    <i class="icon mc mc-star"></i>
                                                </button>
                                                <button class="btn btn-primary">
                                                    <span>Get Deal</span>
                                                    <i class="touch mc mc-touch"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="banner main-box">
                    <a href="##" class="a-block">
                    <img src="<?php echo $this->Html->url('/assets/photos/banner-cosmetic-01.jpg') ?>" alt="" /> </a>
                </div>
                <div class="main-box deal-box latest">
                    <div class="header link">
                        <div class="text">
                            <h3 class="title">Latest Deals</h3>
                            <span class="link">
                                <a href="<?php echo $this->Html->url(array('controller' => 'deals', 'action' => 'index', 'latest')) ?>">View all &raquo;</a>
                            </span>
                        </div>
                    </div>
                    <div class="body">
                        <div class="rooow">
                            <?php foreach ($latestDeals1 as $deal) : ?>
                            <div class="item deal-item col-md-5">
                                <div class="inner">
                                    <div class="nq-tag"> SALE
                                        <strong><?php echo $deal['Deal']['discount_percent'] ?></strong>
                                    </div>
                                    <div class="image" style="background-image:url('<?php echo (!empty($deal['Deal']['deal_image'])) ? $deal['Deal']['deal_image'] : 'http://lorempixel.com/200/200' ?>')"> </div>
                                    <div class="caption">
                                        <div class="price">
                                            <span class="deprecated"><?php echo $deal['Deal']['origin_price'].$deal['Deal']['currency'] ?></span>
                                            <span class="featured"><?php echo $deal['Deal']['discount_price'].$deal['Deal']['currency'] ?></span>
                                        </div>
                                        <div class="desc"><?php echo $deal['Deal']['title'] ?></div>
                                        <div class="actions">
                                            <div class="inner">
                                                <button class="btn btn-default btn-square">
                                                    <i class="icon mc mc-star"></i>
                                                </button>
                                                <button class="btn btn-primary">
                                                    <span>Get Deal</span>
                                                    <i class="touch mc mc-touch"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="banner rooow">
                            <a href="##" class="a-block">
                            <img src="./assets/photos/banner-appliances-01.jpg" alt="" /> </a>
                        </div>
                        <div class="rooow">
                            <?php foreach ($latestDeals2 as $deal) : ?>
                            <div class="item deal-item col-md-5">
                                <div class="inner">
                                    <div class="nq-tag"> SALE
                                        <strong><?php echo $deal['Deal']['discount_percent'] ?></strong>
                                    </div>
                                    <div class="image" style="background-image:url('<?php echo (!empty($deal['Deal']['deal_image'])) ? $deal['Deal']['deal_image'] : 'http://lorempixel.com/200/200' ?>')"> </div>
                                    <div class="caption">
                                        <div class="price">
                                            <span class="deprecated"><?php echo $deal['Deal']['origin_price'].$deal['Deal']['currency'] ?></span>
                                            <span class="featured"><?php echo $deal['Deal']['discount_price'].$deal['Deal']['currency'] ?></span>
                                        </div>
                                        <div class="desc"><?php echo $deal['Deal']['title'] ?></div>
                                        <div class="actions">
                                            <div class="inner">
                                                <button class="btn btn-default btn-square">
                                                    <i class="icon mc mc-star"></i>
                                                </button>
                                                <button class="btn btn-primary">
                                                    <span>Get Deal</span>
                                                    <i class="touch mc mc-touch"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="main-box subscribe-box">
                    <p>Saving sent straight to your inbox. Subscribe to the Best of Most Coupon</p>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Enter your email" />
                        <span class="input-group-btn">
                            <button class="btn btn-primary dark-text"> Subscribe </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>