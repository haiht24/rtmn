<div class="modal-header text-left">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <div class="congratulation"><img src="<?php echo $this->Html->url('/assets/img/congratulation.png') ?>"></div>
    <p class="header-get-code">Your Coupon Code has been
        <strong>activated</strong> and the discount will be applied after checkout</p>
</div>
<div class="modal-body main-box ">
    <div class="item code-item">
        <div class="vs">
            <?php if (strtoupper($coupon['Coupon']['coupon_type']) == 'COUPON CODE') : ?>
            <div class="card green">
                <div class="body">
                    <strong><?php echo $coupon['Coupon']['discount'] . $coupon['Coupon']['currency']; ?></strong>
                    OFF
                </div>
                <div class="footer">
                    <?php echo strtoupper($this->Tn->underscore2Camelcase($coupon['Coupon']['coupon_type'])) ?>
                </div>
            </div>
            <?php elseif (strtoupper($coupon['Coupon']['coupon_type']) == 'COUPON CODE') : ?>
            <div class="card light-green">
                <div class="body">
                    <strong>FREE</strong> SHIPPING
                </div>
                <div class="footer"> SALE</div>
            </div>
            <?php else :?>
            <div class="card orange">
                <div class="body">
                    <strong>GREAT</strong> OFFER
                </div>
                <div class="footer"> SALE</div>
            </div>
            <?php endif; ?>
        </div>
        <div class="tt">
            <div class="labels">
                <?php if ($coupon['Coupon']['event_id']) : ?>
                    <span
                        class="label label-primary"> <?php echo $coupon['Event']['name'] ?> </span>
                <?php endif; ?>
                <?php if ($coupon['Coupon']['exclusive']) : ?>
                    <span class="label label-danger"> EXCLUSIVE </span>
                <?php endif; ?>
                <?php if ($coupon['Coupon']['verified']) : ?>
                    <span class="label label-success"> 100% VERIFY </span>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="title"><?php echo $coupon['Coupon']['title_store'] ?></h4>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php if (!empty($coupon['Coupon']['coupon_code'])) :?>
                            <div class="col-sm-7 code-desc">
                                <div class="row">
                                    <div class="pull-left coupon-code"
                                         id="code_text"><?php echo $coupon['Coupon']['coupon_code'] ?></div>
                                    <div class="pull-right">
                                        <a class="btn btn-copy" id="d_clip_button" data-clipboard-target="code_text" data-toggle="tooltip" data-placement="bottom" title="Copy to clipboard">Copy Code</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5"><h6 class="code-tips"><b>Paste this code</b> at checkout when you are
                                    done shopping</h6>
                            </div>
                            <?php else : ?>
                                <div class="desc desc-more"><?php echo $coupon['Coupon']['description_store'] ?></div>
                            <?php endif; ?>
                            <a href="<?php echo $this->Html->url('/' . $coupon['Store']['alias']) ?>-coupons"
                               class="btn btn-labeled btn-view-store">View all
                                <b><?php echo $coupon['Store']['name'] ?> Coupons</b>
                                <span class="btn-label btn-label-right"><i
                                        class="fa fa-chevron-circle-right"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="item subscribe-item hidden-xs">
        <div class="row">
            <div class="vs col-sm-3"></div>
            <div class="tt col-sm-9">
                <h4 class="title"> Never Miss The Best Coupon Codes Again </h4>

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
<div class="modal-footer text-right">
    <span>Share Save off % to everybody</span>
    <a class="btn btn-social-icon btn-circle btn-xs btn-facebook"><i class="fa fa-facebook"></i></a>
    <a class="btn btn-social-icon btn-circle btn-xs btn-twitter"><i class="fa fa-twitter"></i></a>
    <a class="btn btn-social-icon btn-circle btn-xs btn-google-plus"><i
            class="fa fa-google-plus"></i></a>
    <a class="btn btn-social-icon btn-circle btn-xs btn-pinterest"><i
            class="fa fa-pinterest"></i></a>
</div>
<script>
    $(document).ready(function() {
        var btn_copy = $('#d_clip_button');
        var clip = new ZeroClipboard(btn_copy);

        clip.on("ready", function() {
            this.on("aftercopy", function(event) {
                btn_copy.text('Copied');
                setTimeout(function () {
                    btn_copy.text('Copy Code');
                }, 5000);
            });
        });

        clip.on("error", function(event) {
            ZeroClipboard.destroy();
        });

        $('.main-box .vs .card .body strong').each(function () {
            switch ($(this).text().length) {
                case 6:
                    $(this).addClass('font-size-29');
                    break;
                case 7:
                    $(this).addClass('font-size-24');
                    break;
                case 8:
                    $(this).addClass('font-size-21');
                    break;
                case 10:
                    $(this).addClass('font-size-18');
                    break;
                case 11:
                    $(this).addClass('font-size-14');
                    break;
                case 12:
                    $(this).addClass('font-size-14');
                    break;
            }
        });

    });
</script>