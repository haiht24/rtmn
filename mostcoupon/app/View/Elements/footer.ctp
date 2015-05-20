<script>
    $(function () {
        $('a[coupon_id]').one('click', function () {
            window.open('?c=' + $(this).attr('coupon_id'), '_blank');
            window.open("<?php echo $this->Html->url('/go/') ?>" + $(this).attr('coupon_id'), '_self');
        });
        $('a[deal_id]').one('click', function () {
            window.open('?d=' + $(this).attr('deal_id'), '_blank');
            window.open("<?php echo $this->Html->url('/go/') ?>" + $(this).attr('deal_id'), '_self');
        });
    });
</script>
<div class="modal fade" id="get-coupon-code">
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
</div>
<div class="footer">
    <div class="highlight hidden-xs">
        <div class="container">
            <div class="inner">
                <div class="item col-sm-3">
                    <div class="inner">
                        <div class="ooh">
                            <i class="icon mc mc-users"></i>
                        </div>
                        <div class="aah">
                            <div class="how">7.3 Million</div>
                            <div class="what">Members</div>
                        </div>
                    </div>
                </div>
                <div class="item col-sm-3">
                    <div class="inner">
                        <div class="ooh">
                            <i class="icon mc mc-barcode"></i>
                        </div>
                        <div class="aah">
                            <div class="how">20 Million</div>
                            <div class="what">Coupon Codes</div>
                        </div>
                    </div>
                </div>
                <div class="item col-sm-3">
                    <div class="inner">
                        <div class="ooh">
                            <i class="icon mc mc-store"></i>
                        </div>
                        <div class="aah">
                            <div class="how">Over 500</div>
                            <div class="what">Stores</div>
                        </div>
                    </div>
                </div>
                <div class="item col-sm-3">
                    <div class="inner">
                        <div class="ooh">
                            <i class="icon mc mc-heart"></i>
                        </div>
                        <div class="aah">
                            <div class="how">20 Million</div>
                            <div class="what">Follows</div>
                        </div>
                    </div>
                </div>
                <i class="curvy left"></i>
                <i class="curvy right"></i>
            </div>
        </div>
    </div>
    <div class="content hidden-xs">
        <div class="container">
            <div class="row ">
                <div class="item col-sm-3">
                    <div class="title"><h4>About us</h4></div>
                    <ul class="list-unstyled">
                        <li>
                            <a rel='nofollow'
                               href="<?php echo $this->Html->url(['controller' => 'AboutUs', 'action' => 'index']) ?>">
                                About Us</a>
                        </li>
                        <li>
                            <a rel='nofollow'
                               href="<?php echo $this->Html->url(['controller' => 'DownloadApp', 'action' => 'index']) ?>">
                                Download our app</a>
                        </li>
                        <li>
                            <a rel='nofollow'
                               href="<?php echo $this->Html->url(['controller' => 'PressCentre', 'action' => 'index']) ?>">
                                Press Centre</a>
                        </li>
                        <li>
                            <a rel='nofollow'
                               href="<?php echo $this->Html->url(['controller' => 'CareerPage', 'action' => 'index']) ?>">
                                Careers</a>
                        </li>
                    </ul>
                </div>
                <div class="item col-sm-3">
                    <div class="title"><h4>Help</h4> </div>
                    <ul class="list-unstyled">
                        <li>
                            <a rel='nofollow'
                               href="<?php echo $this->Html->url(['controller' => 'HelpPage', 'action' => 'index']) ?>">
                                Help</a>
                        </li>
                        <li>
                            <a rel='nofollow'
                               href="<?php echo $this->Html->url(['controller' => 'HowTo', 'action' => 'index']) ?>">
                                How to Guides</a>
                        </li>
                        <li>
                            <a rel='nofollow'
                               href="<?php echo $this->Html->url(['controller' => 'ContactUs', 'action' => 'index']) ?>">
                                Contact us</a>
                        </li>
                    </ul>
                </div>
                <div class="item col-sm-3">
                    <div class="title"><h4>Legal</h4></div>
                    <ul class="list-unstyled">
                        <li>
                            <a rel='nofollow'
                               href="<?php echo $this->Html->url(['controller' => 'AboutCookies', 'action' => 'index']) ?>">
                                About Cookies</a>
                        </li>
                        <li>
                            <a rel='nofollow'
                               href="<?php echo $this->Html->url(['controller' => 'TermsPage', 'action' => 'index']) ?>">
                                Terms &amp; Conditions</a>
                        </li>
                        <li>
                            <a rel='nofollow'
                               href="<?php echo $this->Html->url(['controller' => 'PrivacyPolicy', 'action' => 'index']) ?>">
                                Privacy Policy</a>
                        </li>
                        <li>
                            <a rel='nofollow'
                               href="<?php echo $this->Html->url(['controller' => 'AppTerms', 'action' => 'index']) ?>">
                                App Terms</a>
                        </li>
                        <li>
                            <a rel='nofollow'
                               href="<?php echo $this->Html->url(['controller' => 'CompetitionTerms', 'action' => 'index']) ?>">
                                CompetitionTerms</a>
                        </li>
                        <li>
                            <a rel='nofollow'
                               href="<?php echo $this->Html->url(['controller' => 'DirectAdv', 'action' => 'index']) ?>">
                                Direct Advertising</a>
                        </li>
                    </ul>
                </div>
                <div class="item not-an-item col-md-3">
                    <div class="title"><h4>Connect with Most Coupon</h4>
                    </div>
                    <div>
                        <a rel='nofollow' href="#" class="weird-sticker">
                            <b><i class="icon mc mc-piggy-bank"></i></b>
                            <span>The Real Deals</span>
                        </a>
                        <a rel='nofollow'
                           href="<?php echo $this->Html->url(['controller' => 'coupons', 'action' => 'submitCoupon']) ?>"
                           class="weird-sticker">
                            <b><i class="icon mc mc-tag"></i></b>
                            <span>Submit a Coupon</span>
                        </a>
                        <a rel='nofollow' href="#" class="weird-sticker">
                            <b><i class="icon mc mc-thumbs-o-up"></i></b>
                            <span>Social Media</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container visible-xs-block">
        <div class="text-center">
            <div class="col-xs-12" style="margin-bottom: 10px;margin-top: 10px">
                <a class="btn btn-social-icon btn-circle btn-facebook"><i class="fa fa-facebook"></i></a>
                <a class="btn btn-social-icon btn-circle btn-twitter"><i class="fa fa-twitter"></i></a>
                <a class="btn btn-social-icon btn-circle btn-google-plus"><i class="fa fa-google-plus"></i></a>
                <a class="btn btn-social-icon btn-circle btn-pinterest"><i class="fa fa-pinterest"></i></a>
            </div>
            <div class="col-xs-12 footer-subscribe">
                <h4 style="color: #1c1d22">Subscribe to our newsletter!</h4>

                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Email Address">
                        <span class="input-group-btn">
                        <button class="btn btn-primary" type="button">Subscribe</button>
                    </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="container">
            <div class="pull-left">
                <p class="title">Most Coupon</p>

                <p>Copyright &copy; 2014 MostCoupon.All rights reserved.</p>
            </div>
            <div class="pull-right hidden-xs">
                <a class="btn btn-social-icon btn-circle btn-xs btn-facebook"><i class="fa fa-facebook"></i></a>
                <a class="btn btn-social-icon btn-circle btn-xs btn-twitter"><i class="fa fa-twitter"></i></a>
                <a class="btn btn-social-icon btn-circle btn-xs btn-google-plus"><i class="fa fa-google-plus"></i></a>
                <a class="btn btn-social-icon btn-circle btn-xs btn-pinterest"><i class="fa fa-pinterest"></i></a>
            </div>
        </div>
    </div>
</div>