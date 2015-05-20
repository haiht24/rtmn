<?php $this->Html->script('/lib/fromjs/from', ['inline' => false]); ?>
<?php $this->Ng->ngController('ProductCouponCtrl') ?>
<?php $this->Ng->fdbDirective(['image_upload']); ?>
<?php $this->Ng->ngInit(
    [
        'user' => isset($user) ? $user : [],
        'categories' => isset($categories) ? $categories : [],
        'users' => isset($users) ? $users : []
    ])
?>
<style>
    .coupon-detail > td {
        padding: 0 !important;
    }

    .coupon-detail > td .coupon-info {
        padding: 8px 10px;
    }

    .coupon-info label {
        font-weight: bold;
    }

    #modal-add-coupon .smart-form .row {
        margin-bottom: 10px;
    }
    #modal-add-coupon .smart-form .select2-choice {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
    }

    .row-sub-title .close,
    .row-sub-description .close {
        float: none;
        padding-top: 5px;
    }
</style>

<!-- Breadscrums -->
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
                <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-"></i>
            MostCoupon <span>Coupons</span>
        </h1>
    </div>
</div>

<!-- Main widget grid -->
<section id="widget-grid" class="">

<!-- row -->
<div class="row">

<!-- SEARCH BOX -->
<div class="search-box-container col-xs-12">
    <div class="input-group input-group-lg">
        <input class="form-control input-lg" type="text"
               ng-model="filter"
               placeholder="Filter by title or coupon type"
               id="search-user">

        <div class="input-group-btn">
            <button type="submit" class="btn btn-default"
                    ng-click="search()"
                    id="search-coupon">
                <i class="fa fa-fw fa-search fa-lg"></i>
            </button>
        </div>
    </div>
    <br>
    <div>
        <label>Filter by Author:</label>
        <select ng-model="userFilter" ng-change="search()">
            <option></option>
            <option ng-repeat="user in users" ng-value='user.user.id'>{{user.user.fullname}}</option>
        </select>
        <label>Filter by Created Date:</label>
        <input class="date-capture-mode"
            id ='dateFilter'
            ng-model="createdFilter"
            ng-change="search()"
            date-picker/>
        <label>Filter by Publish Date:</label>
        <input class="date-capture-mode"
            id ='datePublishFilter'
            ng-model="publishFilter"
            ng-change="search()"
            date-picker/>
    </div>
    <br>
</div>

<!-- BUTTON LIST & MODAL -->
<div class="button-container col-xs-12">
    <div class="input-group">
        <a class="btn btn-primary" ng-click="showAll();">Clear</a>
<!--        <a class="btn btn-primary btn-add-coupon"
           data-toggle="modal"
           data-target="#modal-add-coupon"
           ng-click='initTitleDescription()'>
            <i class="fa fa-plus"></i> Add Coupon
        </a>
        &nbsp;
        <a class="btn btn-success">
            <i class="fa fa-level-up"></i> Approve
        </a>-->
    </div>

    <div>
        <label>Total: {{totalCoupons | number}} Coupons</label>
    </div>

    <div class="modal fade in" id="modal-add-coupon" tabindex="-1" role="dialog" aria-labelledby="modal-label-add-coupon" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal-label-add-coupon">Update Coupon</h4>
                </div>
                <div class="modal-body">
                    <form class="smart-form" name='addCouponForm' novalidate>
                        <section>
                            <label class="label">Title</label>
                            <div class="row row-title" ng-repeat="(index, title) in title_coupons"
                                 ng-class="{'note row-sub-title': index !=0}" ng-form="titleForm">
                                <div class="col col-6">
                                    <label class="input">
                                        <i class="icon-append fa fa-certificate"></i>
                                        <input type="text" placeholder="Title" name="title" ng-model='title.value' required>
                                    </label>
                                    <p class='error' ng-show='showError && titleForm.$error.required'>Please enter title.</p>
                                </div>
                                <div class="col " ng-class="{'col-6': index ==0, 'col-5': index !=0}">
                                    <label class="select">
                                        <select name="title" ng-disabled ='index == 0' ng-model='title.key'>
                                            <option ng-repeat='(oIndex, item) in keys_title_coupons'
                                                    ng-value='item.key'
                                                    ng-selected='title.key == item.key'
                                                    ng-if='showOptionTitle(item.key,title.key)'>{{item.lable}}</option>
                                        </select>
                                        <i></i>
                                    </label>
                                </div>
                                <button ng-if='index > 0' class="close close-current-row"
                                        type="button" ng-click='removeTitle(index)'>×</button>
                            </div>
                            <div class="note" ng-if='showAddTitle()'>
                                <a ng-click='addTitle()'>+ Add more Title</a>
                            </div>
                        </section>
                        <section>
                            <label class="label">Description</label>
                            <div class="row row-description"
                                 ng-repeat="(index, description) in description_coupons"
                                 ng-form="descriptionForm"
                                 ng-class="{'note row-sub-title': index !=0}">
                                <div class="col col-6">
                                    <label class="input">
                                        <i class="icon-append fa fa-comment"></i>
                                        <input type="text" placeholder="Description"
                                               name="description_title"
                                               ng-model='description.value' required>
                                    </label>
                                    <p class='error' ng-show='showError && descriptionForm.$error.required'>Please enter description.</p>
                                </div>
                                <div class="col" ng-class="{'col-6': index ==0, 'col-5': index !=0}">
                                    <label class="select">
                                        <select name="description" ng-disabled ='index == 0' ng-model='description.key'>
                                            <option ng-repeat='(oIndex, item) in keys_description_coupons'
                                                    ng-value='item.key'
                                                    ng-selected='description.key == item.key'
                                                    ng-if='showOptionDescription(item.key,description.key)'>{{item.lable}}</option>
                                        </select>
                                        <i></i>
                                    </label>
                                </div>
                                <button ng-if='index > 0' class="close close-current-row"
                                        type="button" ng-click='removeDescription(index)'>×</button>
                            </div>
                            <div class="note"  ng-if='showAddDescription()'>
                                <a ng-click='addDescription()'>+ Add more Description</a>
                            </div>
                        </section>
                        <section>
                            <label class="label">Coupon Image</label>
                            <div class="image-upload account-logo-upload" image-upload="newCoupon.coupon_image" fixed image-loading
                                max-image-size="307200" title="<?php echo __('Click on the image to choose another one'); ?>">
                            </div>
                        </section>
                        <section>
                            <label class="label">Social Image</label>
                            <div class="image-upload account-logo-upload" image-upload="newCoupon.social_image" fixed image-loading
                                max-image-size="307200" title="<?php echo __('Click on the image to choose another one'); ?>">
                            </div>
                        </section>
                        <section>
                            <label class="label">Product Link</label>
                            <label class="input">
                                <i class="icon-append fa fa-link"></i>
                                <input type="text" placeholder="Product Link" ng-model='newCoupon.product_link'>
                            </label>
                        </section>
                        <section>
                            <label class="label">Exclusive</label>
                            <div class="inline-group">
                                <label class="radio">
                                    <input ng-model="newCoupon.exclusive"
                                            type="radio" ng-value="1"
                                            ng-init="initDefaultDefault();">
                                    <i></i>Yes
                                </label>
                                <label class="radio">
                                    <input ng-model="newCoupon.exclusive"
                                            type="radio" ng-value="0">
                                    <i></i>No
                                </label>
                            </div>
                        </section>
                        <section>
                            <label class="label">Sticky</label>
                            <div class="inline-group">
                                <label class="radio">
                                    <input ng-model="newCoupon.sticky"
                                            type="radio" ng-value="'top'">
                                    <i></i>Top
                                </label>
                                <label class="radio">
                                    <input ng-model="newCoupon.sticky"
                                            type="radio" ng-value="'hot'">
                                    <i></i>Hot
                                </label>
                            </div>
                        </section>
                        <section>
                            <label class="label">Coupon Type</label>
                            <label class="select">
                                <select name="coupon_type" ng-model='newCoupon.coupon_type'>
                                    <option>Coupon Code</option>
                                    <option>Get Offer</option>
                                    <option>Free shipping</option>
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section>
                            <label class="label">Discount</label>
                            <div class="clearfix">
                                <div class="col-sm-2">
                                    <label class="select">
                                        <select name="select_dc_currency" ng-model='newCoupon.currency'>
                                            <option>$</option>
                                            <option>£</option>
                                            <option>¥</option>
                                            <option>€</option>
                                        </select>
                                        <i></i>
                                    </label>
                                </div>
                                <div class="col-sm-10">
                                    <label class="input">
                                        <i class="icon-append fa fa-tag"></i>
                                        <input type="text" placeholder="Discount" ng-model='newCoupon.discount'>
                                    </label>
                                </div>
                            </div>
                        </section>
                        <section>
                            <label class="label">Expire Date</label>
                            <label class="input">
                                <i class="icon-append fa fa-calendar"></i>
                                <input class="date-capture-mode" ng-model="newCoupon.expire_date"
                                        date-picker/>
                            </label>
                        </section>
                        <section>
                            <label class="label">Select Event</label>
                            <label class="select">
                                <select name="select_event" ng-model='newCoupon.event'>
                                    <option>Back to School</option>
                                    <option>Black Friday</option>
                                    <option>Christmas</option>
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section>
                            <label class="label">Select Store</label>
                            <div class="clearfix">
                                <div class="col-sm-4">
                                    <label class="select">
                                        <select name="select_store_category"
                                                ng-change="bindStores(newCoupon.category_id);"
                                                ng-model="newCoupon.category_id">
                                            <option ng-repeat="cate in categories"
                                                    ng-selected='newCoupon.category_id == cate.category.id'
                                                    ng-value='cate.category.id'>{{cate.category.name}}</option>
                                        </select>
                                        <i></i>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select name="select_store" class="select2" ng-model="newCoupon.store_id" required>
                                        <option ng-repeat="store in stores"
                                                ng-value='store.store.id'
                                                ng-selected='newCoupon.store_id == store.store.id'>{{store.store.name}}</option>
                                    </select>
                                    <img ng-show="loadingStores" src="<?php echo $this->base ?>/img/select2-spinner.gif">
                                    <p class='error' ng-show='showError && addCouponForm.select_store.$invalid'>Please choose store.</p>
                                </div>
                            </div>
                        </section>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-default"
                            data-dismiss="modal"
                            id="cancelCoupon">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary"
                            ng-click="saveCoupon()"
                            id="saveCoupon">
                        Update
                    </button>
                    <button type="button" class="btn btn-primary"
                            ng-click="saveCoupon('published')"
                            ng-show="newCoupon.status == 'pending'
                            && user.permissions.allow_add_active_coupon == 1">
                        Publish
                    </button>
                    <button type="button" class="btn btn-primary"
                            ng-click="saveCoupon('pending')"
                            ng-show="arrayContains(newCoupon.status,['trash','published'])
                            && user.permissions.allow_add_active_coupon == 1">
                        Pending Review
                    </button>
                    <button type="button" class="btn btn-primary"
                            ng-click="saveCoupon('trash')"
                            ng-show="arrayContains(newCoupon.status, ['pending','published'])
                            && user.permissions.allow_add_active_coupon == 1">
                        Move to Trash
                    </button>
                    <button type="button" class="btn btn-primary"
                            ng-click="deleteCoupon(newCoupon.id)"
                            ng-show="newCoupon.status == 'trash'
                            && user.permissions.allow_add_active_coupon == 1">
                        Delete
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <br>
</div>

<!-- NEW WIDGET START -->
<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <!-- Widget ID (each widget will need unique ID)-->
    <div class="jarviswidget jarviswidget-color-blueDark" id="wid-coupon-list"
         data-widget-deletebutton="false"
         data-widget-colorbutton="false"
         data-widget-editbutton="false">

        <header>
            <span class="widget-icon"> <i class="fa fa-tag"></i> </span>
            <h2>Coupons List</h2>
        </header>

        <!-- widget div-->
        <div>

            <!-- widget edit box -->
            <div class="jarviswidget-editbox">
                <!-- This area used as dropdown edit box -->

            </div>
            <!-- end widget edit box -->

            <!-- widget content -->
            <div class="widget-body">

                <div class="table-responsive">

                    <table id="coupon-list" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th ng-click="sortBy('title_store');"
                                ng-class="{'asc':(filterOptions.sortBy == true
                                && filterOptions.sortField == 'title_store'),
                                'desc': (filterOptions.sortBy == false
                                && filterOptions.sortField == 'title_store')}">Title (Store)</th>
                            <th ng-click="sortBy('coupon_type');"
                                ng-class="{'asc':(filterOptions.sortBy == true
                                && filterOptions.sortField == 'coupon_type'),
                                'desc': (filterOptions.sortBy == false
                                && filterOptions.sortField == 'coupon_type')}">Coupon Type</th>
                            <th ng-click="sortBy('expire_date');"
                                ng-class="{'asc':(filterOptions.sortBy == true
                                && filterOptions.sortField == 'expire_date'),
                                'desc': (filterOptions.sortBy == false
                                && filterOptions.sortField == 'expire_date')}">Expire Date</th>
                            <td ng-click="sortBy('status');"
                                ng-class="{'asc':(filterOptions.sortBy == true
                                && filterOptions.sortField == 'status'),
                                'desc': (filterOptions.sortBy == false
                                && filterOptions.sortField == 'status')}">Status</td>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody ng-repeat="(index, coupon) in pages">
                            <tr>
                                <td>{{coupon.coupon.title_store}}</td>
                                <td>{{coupon.coupon.coupon_type}}</td>
                                <td>{{coupon.coupon.expire_date | formatDateTimeLocal}}</td>
                                <td><span class="label"
                                        ng-class="{'label-success': coupon.coupon.status == 'published',
                                        'label-warning' : arrayContains(coupon.coupon.status, ['pending','trash'])}">
                                    {{coupon.coupon.status}}
                                </span>
                            </td>
                                <td>
                                    <button ng-click="editCoupon(coupon.coupon)"
                                            data-toggle="modal"
                                            data-target="#modal-add-coupon">
                                        Edit
                                    </button>
                                    <button ng-show="coupon.coupon.status == 'pending'
                                                    && user.permissions.allow_add_active_coupon == 1"
                                            ng-click="setStatusCoupon(coupon.coupon.id,'published')">Publish</button>
                                    <button ng-show="arrayContains(coupon.coupon.status, ['pending','published'])
                                                    && user.permissions.allow_add_active_coupon == 1"
                                            ng-click="setStatusCoupon(coupon.coupon.id,'trash')">Move To Trash</button>
                                    <button ng-show="coupon.coupon.status == 'trash'
                                                    && user.permissions.allow_add_active_coupon == 1"
                                                    ng-click='deleteCoupon(coupon.coupon.id)'>Delete</button>
                                    <button ng-show="arrayContains(coupon.coupon.status,['trash','published'])
                                                && user.permissions.allow_add_active_coupon == 1"
                                                ng-click="setStatusCoupon(coupon.coupon.id, 'pending')">Pending Review</button>
                                    <a href="#"><i class="fa fa-plus accordion-toggle" data-target="#demo{{index}}" data-toggle="collapse"></i></a>
                                </td>
                            </tr>
                            <tr class="coupon-detail">
                                <td colspan="8">
                                    <div class="collapse" id="demo{{index}}">
                                        <div class="coupon-info row">
                                            <div class="col-md-6">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td><label>Title (Store)</label></td>
                                                        <td>{{coupon.coupon.title_store}}</td>
                                                    </tr>
                                                    <tr ng-show="coupon.coupon.title_category">
                                                        <td><label>Title (Category)</label></td>
                                                        <td>{{coupon.coupon.title_category}}</td>
                                                    </tr>
                                                    <tr ng-show="coupon.coupon.title_event">
                                                        <td><label>Title (Event)</label></td>
                                                        <td>{{coupon.coupon.title_event}}</td>
                                                    </tr>
                                                    <tr ng-show="coupon.coupon.title_top_coupon">
                                                        <td><label>Title (Top Coupon)</label></td>
                                                        <td>{{coupon.coupon.title_top_coupon}}</td>
                                                    </tr>
                                                    <tr ng-show="coupon.coupon.title_related_coupon">
                                                        <td><label>Title [Deal (Notable Coupons)]</label></td>
                                                        <td>{{coupon.coupon.title_related_coupon}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Status</label></td>
                                                        <td>{{coupon.coupon.status}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Coupon Image</label></td>
                                                        <td><img ng-src="{{coupon.coupon.coupon_image}}"/></td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Social Image</label></td>
                                                        <td><img ng-src="{{coupon.coupon.social_image}}"/></td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Author</label></td>
                                                        <td>{{coupon.author.fullname}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Created date</label></td>
                                                        <td>{{coupon.coupon.created | formatDateTimeLocal}}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td><label>Description Store</label></td>
                                                        <td>{{coupon.coupon.description_store}}</td>
                                                    </tr>
                                                    <tr  ng-show="coupon.coupon.description_category">
                                                        <td><label>Description (Category)</label></td>
                                                        <td>{{coupon.coupon.description_category}}</td>
                                                    </tr>
                                                    <tr ng-show="coupon.coupon.description_event">
                                                        <td><label>Description (Event)</label></td>
                                                        <td>{{coupon.coupon.description_event}}</td>
                                                    </tr>
                                                    <tr ng-show="coupon.coupon.description_top_coupon">
                                                        <td><label>Description (Top Coupon)</label></td>
                                                        <td>{{coupon.coupon.description_top_coupon}}</td>
                                                    </tr>
                                                    <tr ng-show="coupon.coupon.description_related_coupon">
                                                        <td><label>Description [Deal (Notable Coupons)]</label></td>
                                                        <td>{{coupon.coupon.description_related_coupon}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Product Link</label></td>
                                                        <td>{{coupon.coupon.product_link}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Exculsive</label></td>
                                                        <td>{{getExculsive(coupon.coupon.exclusive);}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Sticky</label></td>
                                                        <td>{{coupon.coupon.sticky}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Discount</label></td>
                                                        <td>{{coupon.coupon.currency}}{{coupon.coupon.discount}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Coupon Type</label></td>
                                                        <td>{{coupon.coupon.coupon_type}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Expire Date</label></td>
                                                        <td>{{coupon.coupon.expire_date | formatDateTimeLocal}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Select Event</label></td>
                                                        <td>{{coupon.coupon.event}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Select Store</label></td>
                                                        <td>{{coupon.store.name}}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                <div class="text-center">
                    <hr>
                    <ul class="pagination no-margin">
                        <li class="arrow" ng-click="prevPage()" ng-show="numberOfPages > 1"
                            ng-class="{'disabled': 0 == currentPage}">
                            <a>Previous</a>
                        </li>
                        <li ng-repeat="n in range(numberOfPages)"
                            ng-class="{active: n == currentPage}" ng-click="setPage(n)">
                            <a ng-show="n >= 0 && n < 10">{{ n + 1 }}</a>
                        </li>
                        <li>
                            <input type="number" ng-model="currentPageInc" ng-show="numberOfPages > 10" ng-change="changePage()"/>
                        </li>
                        <li ng-click="setPage(numberOfPages - 1)"
                            ng-class="{active: (numberOfPages - 1) == currentPage}">
                            <a ng-show="numberOfPages > 10">{{ pages.length }}</a>
                        </li>
                        <li class="arrow" ng-show="numberOfPages > 1"
                            ng-click="nextPage()"
                            ng-class="{'disabled': (numberOfPages - 1) == currentPage}">
                            <a href="">Next</a>
                        </li>
                    </ul>
                </div>

            </div>
            <!-- end widget content -->

        </div>
        <!-- end widget div -->

    </div>
    <!-- end widget -->

</article>
<!-- WIDGET END -->

</div>
<!-- end row -->


</section>
<!-- end Main widget grid -->