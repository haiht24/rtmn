<div class="modal-header text-left">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <div class="congratulation"><img src="<?php echo $this->Html->url('/assets/img/congratulation.png') ?>"></div>
    <p class="header-get-code">Your Coupon Code has been
        <strong>activated</strong> and the discount will be applied after checkout</p>
</div>
<div class="modal-body main-box ">
    <div class="item code-item related-item">
        <div class="vs">
            <div class="avatar">
                <img src="<?php echo $deal['Deal']['deal_image'] ?>"
                     alt="<?php echo $deal['Deal']['title'] ?>">
            </div>
        </div>
        <div class="tt">
            <div class="labels">
                <?php if ($deal['Deal']['exclusive']) : ?>
                    <span class="label label-danger"> EXCLUSIVE </span>
                <?php endif; ?>
                <?php if ($deal['Deal']['free_shipping']) : ?>
                    <span class="label label-success"> FREE SHIPPING </span>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="title"><?php echo $deal['Deal']['title'] ?></h4>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="desc desc-more"><?php echo $deal['Deal']['description'] ?></div>
                            <a href="<?php echo $this->Html->url('/' . $deal['Store']['alias']) ?>-coupons"
                               class="btn btn-labeled btn-view-store">View all
                                <b><?php echo $deal['Store']['name'] ?> Coupons</b>
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