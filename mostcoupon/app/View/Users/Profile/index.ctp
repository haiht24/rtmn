<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 links">
                <ul>
                    <li>
                        <a href="<?php echo $this->Html->url('/') ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->Html->url('/users/profile') ?>">Profile</a>
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
<!-- Profile avarta -->
<div class="profile-heading">
    <div class="container">
        <div class="inner rooow">
            <div class="block block-avatar col-sm-2">
                <div class="avatar">
                    <div class="img"
                         style="background-image:url('<?php echo $this->Html->url('/assets/photos/avatar-nerd.png'); ?>')"></div>
                </div>
            </div>
            <div class="block block-inf col-sm-10">
                <?php if ($this->Session->read('User.email')): ?>
                    <h1 class="name"> <?php echo $this->Session->read('User.fullname'); ?>   </h1>
                    <?php echo $this->Session->read('User.email'); ?>   <p class="quote"> "Lorem ipsum dolor sit amet,
                        consectetur adipisicing elit. Voluptatum deleniti pariatur aliquid quaerat blanditiis magnam
                        incidunt quo impedit sit fugiat soluta ullam optio iure in eligendi ex aspernatur sint
                        odit." </p>
                <?php elseif ($this->Session->read('User.id')): ?>
                    <h1 class="name"> <?php echo $this->Session->read('User.username'); ?> </h1>
                    <p class="quote"> <?php echo $this->Session->read('User.email'); ?> "Lorem ipsum dolor sit amet,
                        consectetur adipisicing elit. Voluptatum deleniti pariatur aliquid quaerat blanditiis magnam
                        incidunt quo impedit sit fugiat soluta ullam optio iure in eligendi ex aspernatur sint
                        odit." </p>
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
            <li class="active"><a href="#profile" data-url="<?php echo $this->Html->url('/users/profile') ?>">
                    <i class="fa fa-user"></i>
                    <span>My Profile</span></a>
            </li>
            <li><a href="#savedcoupons" data-url="<?php echo $this->Html->url('/users/SavedCoupons') ?>">
                    <i class="icon mc mc-tag"></i>
                    <span>Saved Coupons</span></a></li>
            <li><a href="#favouritestores" data-url="<?php echo $this->Html->url('/users/FavouriteStores') ?>">
                    <i class="fa fa-heart"></i>
                    <span>Favourite Stores</span></a></li>
            <li><a href="#accountpreferences" data-url="<?php echo $this->Html->url('/users/AccountPreferences') ?>">
                    <i class="fa fa-cog"></i>
                    <span>Account Preferences</span></a></li>
            <li><a href="#community" data-url="<?php echo $this->Html->url('/users/community') ?>">
                    <i class="fa fa-users"></i>
                    <span>Community</span></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="profile"></div>
            <div class="tab-pane" id="editprofile"></div>
            <div class="tab-pane" id="savedcoupons"></div>
            <div class="tab-pane" id="favouritestores"></div>
            <div class="tab-pane" id="accountpreferences"></div>
            <div class="tab-pane" id="community"></div>
        </div>
    </div>
</div>
<!-- End Profile Tab -->
<!-- Begin Profile content -->
<div class="container">

</div>
<!-- End Profile Content -->
<script>
    $('.profile-nav a').click(function (e) {
        e.preventDefault();

        var url = $(this).attr("data-url");
        if (url) {
            var href = this.hash;
            var pane = $(this);

            // ajax load from data-url
            $(href).load(url, function (result) {
                pane.tab('show');
            });
        }
    });
    $('#profile').load($('.profile-nav .active a').attr("data-url"),function(result){
        $('.profile-nav .active a').tab('show');
    });
    $('#editprofile').load($('.profile-nav .active a').attr("data-url"),function(result){
        $('.profile-nav .active a').tab('show');
    });
</script>
<!--
<?php //echo $this->element('facebook') ?>
<div>
<ul>
<li>
                                    <div ng-show="user.facebook_id">
                                        <a class="button-social facebook" ng-click="facebookUnlink()"><?php echo 'Unlink' ?><i class="i-unlink"></i></a>
                                        <a target="_blank" href="https://www.facebook.com/{{user.facebook_id}}"><?php echo 'Facebook' ?></a>
                                        <br/>
                                        <div ng-show="needUpdatePassword"><?php echo 'Please update your Mcus password first' ?></div>
                                    </div>
                                    <div ng-hide="user.facebook_id">
                                        <a class="button-social facebook" ng-click="facebookLink()">
                                            <i class="fa fa-facebook"></i>
                                            <?php echo 'Connect with Facebook' ?>
                                        </a>
                                        <br/>
                                        <div ng-class="{error : facebookLinkStatus === false}" ng-show="facebookLinkMessage" ng-bind="facebookLinkMessage"></div>
                                    </div>
                                </li>
</ul>
</div>
-->