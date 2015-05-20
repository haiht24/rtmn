<?php $this->Ng->ngController('UserSubmitCouponsCtrl') ?>
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 links">
                <ul>
                    <li>
                        <a href="<?php echo $this->Html->url('/') ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->Html->url('/users/') ?>">Profile</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->Html->url('/users/SubmitCoupons') ?>">Submit coupons</a>
                    </li>
                </ul>
            </div>
            <form class="col-sm-8 search">
                <div class="input">
                    <input type="text" class="form-control" placeholder="Search by store name, deal, coupon" />
                    <i class="icon mc mc-search"></i>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Profile avarta -->
<div class="profile-heading">
    <div class="container">
        <div class="inner rooow">
            <div class="block block-avatar col-sm-2">
                <div class="avatar">
                    <div class="img" style="background-image:url('<?php echo $this->Html->url('/assets/photos/avatar-nerd.png'); ?>')"> </div>
                </div>
            </div>
            <div class="block block-inf col-sm-10">
                <?php if($this->Session->read('User.email')): ?>
                    <h1 class="name"> <?php echo $this->Session->read('User.fullname'); ?>   </h1>
                    <?php echo $this->Session->read('User.email'); ?>   <p class="quote">  "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum deleniti pariatur aliquid quaerat blanditiis magnam incidunt quo impedit sit fugiat soluta ullam optio iure in eligendi ex aspernatur sint odit." </p>
                <?php elseif($this->Session->read('User.id')): ?>
                    <h1 class="name"> <?php echo $this->Session->read('User.username'); ?> </h1>
                    <p class="quote"> <?php echo $this->Session->read('User.email'); ?> "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum deleniti pariatur aliquid quaerat blanditiis magnam incidunt quo impedit sit fugiat soluta ullam optio iure in eligendi ex aspernatur sint odit." </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- End profile avarta -->
<!-- Begin Profile tab -->
<div class="profile-tab">
    <div class="container">
        <ul class="nav nav-tabs nav-justified profile-nav">
            <li \>
            <a href="<?php echo $this->Html->url('/users/profile') ?>">
                <i class="icon mc mc-user-verified"></i>
                <span>My Profile</span>
            </a>
            </li>
            <li>
                <a href="<?php echo $this->Html->url('/users/SavedCoupons') ?>">
                    <i class="icon mc mc-tag"></i>
                    <span>Saved Coupons</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="icon mc mc-heart"></i>
                    <span>Favourite Stores</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $this->Html->url('/users/AccountPreferences') ?>">
                    <i class="icon mc mc-envelope-curved"></i>
                    <span>Account Preferences</span>
                </a>
            </li>
            <li class="active">
                <a href="<?php echo $this->Html->url('/users/community') ?>">
                    <i class="icon mc mc-users"></i>
                    <span>Community</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- End Profile Tab -->
<!-- Begin community content -->
<div class="container">
    <div class="profile-paper" style="width: 100%;">
        <div class="profile-lite-nav">
            <a href="<?php echo $this->Html->url('/users/community') ?>">DASHBOARD</a>
            <a href="#">MY BADGES</a>
            <a href="<?php echo $this->Html->url('/users/SubmitCoupons') ?>" class="active">SUBMITTED</a>
            <a href="<?php echo $this->Html->url('/users/faqs') ?>">FAQS</a>
        </div>
        <div class="section" style="width: 100%;">
            <div class="title fluid">
                <h2 class="profile-flag ">
              <span class="inner">
                <span>Your</span>
                <strong>Scorecard</strong>
              </span>
                </h2>
            </div>
            <div class="content">
                <table class="table score-card">
                    <tr>
                        <td>
                            <i class="icon mc mc-money-bag-circle-inverted"></i>
                        </td>
                        <td>You&#39;ve Saved Others</td>
                        <td>$0</td>
                    </tr>
                    <tr>
                        <td>
                            <i class="icon mc mc-coins-circle-inverted"></i>
                        </td>
                        <td>Average Saved / Coupon</td>
                        <td>$0.00</td>
                    </tr>
                    <tr>
                        <td>
                            <i class="icon mc mc-sale-tag-circle-inverted"></i>
                        </td>
                        <td>Total Coupons Accepted</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>
                            <i class="icon mc mc-code-circle-inverted"></i>
                        </td>
                        <td>Codes</td>
                        <td>$0</td>
                    </tr>
                    <tr>
                        <td>
                            <i class="icon mc mc-percentage-circle-inverted"></i>
                        </td>
                        <td>Sales</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>
                            <i class="icon mc mc-printable-circle-inverted"></i>
                        </td>
                        <td>Printables</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>
                            <i class="icon mc mc-star-circle-inverted"></i>
                        </td>
                        <td>Thanks received</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>
                            <i class="icon mc mc-comments-circle-inverted"></i>
                        </td>
                        <td>Comments</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>
                            <i class="icon mc mc-thumbs-up-circle-inverted"></i>
                        </td>
                        <td>Yes Votes</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>
                            <i class="icon mc mc-thumbs-down-circle-inverted"></i>
                        </td>
                        <td>No Votes</td>
                        <td>0</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="section submit-coupon" style="width: 100%;">
            <div class="title fluid">
                <h2 class="profile-flag ">
              <span class="inner">
                <span>Recently</span>
                <strong>Submitted Coupon</strong>
              </span>
                </h2>
            </div>
            <div class="content">
                <div class="empty"> You haven't submitted any coupons yet. </div>
                <a class="btn btn-dark btn-submit-a-coupon" href="##">
                    <i class="icon mc mc-plus-circle-o"></i>
                    <span>Submit a Coupon</span>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- End community Content -->
