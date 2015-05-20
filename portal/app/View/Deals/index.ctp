<?php $this->Html->script('/lib/fromjs/from', ['inline' => false]); ?>
<?php $this->Ng->ngController('DealsCtrl') ?>
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

    .modal-backdrop-override {
        background-color: red;
        opacity:.5;
        filter:alpha(opacity=50);
        bottom: 0;
        left: 0;
        position: fixed;
        right: 0;
        top: 0;
        z-index: 1048;
    }
</style>

<!-- Breadscrums -->
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
                <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-"></i>
            MostCoupon <span>Deals</span>
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
               placeholder="Filter by title or deal status"
               id="search-user">

        <div class="input-group-btn">
            <button type="submit" class="btn btn-default"
                    ng-click="search()"
                    id="search-deal">
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
        <a class="btn btn-primary" ng-click="showAll();">Clear</a>&nbsp;&nbsp;&nbsp;
        <a class="btn btn-primary btn-add-coupon"
           data-toggle="modal"
           id='add-new-deal'
           data-target="#modal-add-deal"
           ng-click='initAddNewDeals()'>
            <i class="fa fa-plus"></i> Add Deal
        </a>
    </div>

    <div>
        <label>Total: {{totalDeals | number}} Deals</label>
    </div>

    <div class="modal fade in" id="modal-add-deal" tabindex="-1" role="dialog" aria-labelledby="modal-label-add-deal" aria-hidden="true" ng-model="test">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" ng-click='incentiveAdd = false;' aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal-label-add-deal">Add New Deal</h4>
                    <div class="draft-warning" style="float: right;" ng-show="showDraft && popupDraft && (addMode)" >
                        <a ng-click="loadDraft()" ><?php echo __('Last draft for your Deal') ?> ({{ popupDraft.created }})</a>
                    </div>
                </div>
                <div class="modal-body">
                    <form class="smart-form" name='addDealForm' novalidate>
                        <section>
                            <label class="label">Title</label>
                            <label class="input">
                                <i class="icon-append fa fa-link"></i>
                                <input type="text" placeholder="Title" ng-model='newDeal.title'>
                            </label>
                        </section>
                        <section>
                            <label class="label">Description</label>
                            <label class="input">
                                <i class="icon-append fa fa-link"></i>
                                <input type="text" placeholder="Description" ng-model='newDeal.description'>
                            </label>
                        </section>
                        <section>
                            <label class="label">Currency</label>
                            <label class="select">
                                <select name="select_dc_currency" ng-model='newDeal.currency'>
                                    <option>$</option>
                                    <option>£</option>
                                    <option>¥</option>
                                    <option>€</option>
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section>
                            <label class="label">Origin price</label>
                            <label class="input">
                                <i class="icon-append fa fa-link"></i>
                                <input type="text" placeholder="Origin price" ng-model='newDeal.origin_price'>
                            </label>
                        </section>
                        <section>
                            <label class="label">Discount price</label>
                            <label class="input">
                                <i class="icon-append fa fa-link"></i>
                                <input type="text" placeholder="Discount price" ng-model='newDeal.discount_price'>
                            </label>
                        </section>
                        <section>
                            <label class="label">Discount percent</label>
                            <label class="input">
                                <i class="icon-append fa fa-link"></i>
                                <input type="text" placeholder="Discount percent" ng-model='newDeal.discount_percent'>
                            </label>
                        </section>
                        <section>
                            <label class="label">Select Store</label>
                            <div class="clearfix">
                                <div class="col-sm-4">
                                    <label class="select">
                                        <select name="select_store_category"
                                                ng-change="bindStores(newDeal.category_id);"
                                                ng-model="newDeal.category_id">
                                            <option ng-repeat="cate in categories"
                                                    ng-selected='newDeal.category_id == cate.category.id'
                                                    ng-value='cate.category.id'>{{cate.category.name}}</option>
                                        </select>
                                        <i></i>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select name="select_store" class="select2" ng-model="newDeal.store_id" required>
                                        <option ng-repeat="store in stores"
                                                ng-value='store.store.id'
                                                ng-selected='newDeal.store_id == store.store.id'>{{store.store.name}}</option>
                                    </select>
                                    <img ng-show="loadingStores" src="<?php echo $this->base ?>/img/select2-spinner.gif">
                                    <p class='error' ng-show='showError && addDealForm.select_store.$invalid'>Please choose store.</p>
                                </div>
                            </div>
                        </section>
                        <section>
                            <label class="label">Deal Image</label>
                            <div class="image-upload account-logo-upload"
                                 image-upload="newDeal.deal_image"
                                 fixed image-loading
                                 max-image-size="307200"
                                 title="<?php echo __('Click on the image to choose another one'); ?>">
                            </div>
                        </section>
                        <section>
                            <label class="label">Produc url</label>
                            <label class="input">
                                <i class="icon-append fa fa-link"></i>
                                <input type="text" placeholder="Produc url" ng-model='newDeal.produc_url'>
                            </label>
                        </section>
                        <section>
                            <label class="label">Exclusive</label>
                            <div class="inline-group">
                                <label class="radio">
                                    <input ng-model="newDeal.exclusive"
                                            type="radio" ng-value="1"
                                            ng-init="initDefaultDefault();">
                                    <i></i>Yes
                                </label>
                                <label class="radio">
                                    <input ng-model="newDeal.exclusive"
                                            type="radio" ng-value="0">
                                    <i></i>No
                                </label>
                            </div>
                        </section>
                        <section>
                            <label class="label">Hot deal</label>
                            <div class="inline-group">
                                <label class="radio">
                                    <input ng-model="newDeal.hot_deal"
                                            type="radio" ng-value="1">
                                    <i></i>Yes
                                </label>
                                <label class="radio">
                                    <input ng-model="newDeal.hot_deal"
                                            type="radio" ng-value="0">
                                    <i></i>No
                                </label>
                            </div>
                        </section>
                        <section>
                            <label class="label">Free shipping</label>
                            <div class="inline-group">
                                <label class="radio">
                                    <input ng-model="newDeal.free_shipping"
                                            type="radio" ng-value="1">
                                    <i></i>Yes
                                </label>
                                <label class="radio">
                                    <input ng-model="newDeal.free_shipping"
                                            type="radio" ng-value="0">
                                    <i></i>No
                                </label>
                            </div>
                        </section>
                        <section>
                            <label class="label">Start Date</label>
                            <label class="input">
                                <i class="icon-append fa fa-calendar"></i>
                                <input class="date-capture-mode"
                                       ng-model="newDeal.start_date"
                                       ng-init ="newDeal.start_date = getDefaultDate()"
                                        date-picker/>
                            </label>
                        </section>
                        <section>
                            <label class="label">Expire Date</label>
                            <label class="input">
                                <i class="icon-append fa fa-calendar"></i>
                                <input class="date-capture-mode"
                                       ng-model="newDeal.expire_date"
                                       ng-init ="newDeal.expire_date = getDefaultDate()"
                                        date-picker/>
                            </label>
                        </section>
                        <section>
                            <label class="label">Deal Tag</label>
                            <label class="input">
                                <i class="icon-append fa fa-link"></i>
                                <input type="text" placeholder="Deal Tag" ng-model='newDeal.deal_tag'>
                            </label>
                        </section>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-default"
                            data-dismiss="modal"
                            id="cancelDeal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary"
                            ng-click="saveDeal()"
                            id="saveDeal">
                        Add
                    </button>
                    <button ng-show="newDeal.status == 'pending'
                                    && user.permissions.allow_add_active_coupon == 1"
                            ng-click="saveDeal('published')">Publish</button>
                    <button ng-show="arrayContains(newDeal.status, ['pending','published'])
                                    && user.permissions.allow_add_active_coupon == 1"
                            ng-click="saveDeal('trash')">Move To Trash</button>
                    <button ng-show="newDeal.status == 'trash'
                                    && user.permissions.allow_add_active_coupon == 1"
                                    ng-click='deleteDeal(newDeal.id)'>Delete</button>
                    <button ng-show="arrayContains(newDeal.status,['trash','published'])
                                && user.permissions.allow_add_active_coupon == 1"
                                ng-click="saveDeal('pending')">Pending Review</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <br>
</div>

<!-- NEW WIDGET START -->
<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <!-- Widget ID (each widget will need unique ID)-->
    <div class="jarviswidget jarviswidget-color-blueDark" id="wid-deal-list"
         data-widget-deletebutton="false"
         data-widget-colorbutton="false"
         data-widget-editbutton="false">

        <header>
            <span class="widget-icon"> <i class="fa fa-tag"></i> </span>
            <h2>Deals List</h2>
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
                            <th ng-click="sortBy('title');"
                                ng-class="{'asc':(filterOptions.sortBy == true
                                && filterOptions.sortField == 'title'),
                                'desc': (filterOptions.sortBy == false
                                && filterOptions.sortField == 'title')}">Title</th>
                            <th ng-click="sortBy('exclusive');"
                                ng-class="{'asc':(filterOptions.sortBy == true
                                && filterOptions.sortField == 'exclusive'),
                                'desc': (filterOptions.sortBy == false
                                && filterOptions.sortField == 'exclusive')}">Exclusive</th>
                            <th ng-click="sortBy('hot_deal');"
                                ng-class="{'asc':(filterOptions.sortBy == true
                                && filterOptions.sortField == 'hot_deal'),
                                'desc': (filterOptions.sortBy == false
                                && filterOptions.sortField == 'hot_deal')}">Hot Deal</th>
                            <th ng-click="sortBy('free_shipping');"
                                ng-class="{'asc':(filterOptions.sortBy == true
                                && filterOptions.sortField == 'free_shipping'),
                                'desc': (filterOptions.sortBy == false
                                && filterOptions.sortField == 'free_shipping')}">Free shipping</th>
                            <td ng-click="sortBy('status');"
                                ng-class="{'asc':(filterOptions.sortBy == true
                                && filterOptions.sortField == 'status'),
                                'desc': (filterOptions.sortBy == false
                                && filterOptions.sortField == 'status')}">Status</td>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody ng-repeat="(index, deal) in pages">
                            <tr>
                                <td>{{deal.deal.title}}</td>
                                <td>{{getYesNo(deal.deal.exclusive);}}</td>
                                <td>{{getYesNo(deal.deal.hot_deal);}}</td>
                                <td>{{getYesNo(deal.deal.free_shipping);}}</td>
                                <td><span class="label"
                                        ng-class="{'label-success': deal.deal.status == 'published',
                                        'label-warning' : arrayContains(deal.deal.status, ['pending','trash'])}">
                                    {{deal.deal.status}}
                                </span>
                            </td>
                                <td>
                                    <button ng-click="editDeal(deal)"
                                            data-toggle="modal"
                                            data-target="#modal-add-deal">
                                        Edit
                                    </button>
                                    <button ng-show="deal.deal.status == 'pending'
                                                    && user.permissions.allow_add_active_coupon == 1"
                                            ng-click="setStatusDeal(deal.deal.id,'published')">Publish</button>
                                    <button ng-show="arrayContains(deal.deal.status, ['pending','published'])
                                                    && user.permissions.allow_add_active_coupon == 1"
                                            ng-click="setStatusDeal(deal.deal.id,'trash')">Move To Trash</button>
                                    <button ng-show="deal.deal.status == 'trash'
                                                    && user.permissions.allow_add_active_coupon == 1"
                                                    ng-click='deleteDeal(deal.deal.id)'>Delete</button>
                                    <button ng-show="arrayContains(deal.deal.status,['trash','published'])
                                                && user.permissions.allow_add_active_coupon == 1"
                                                ng-click="setStatusDeal(deal.deal.id, 'pending')">Pending Review</button>
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
                                                        <td><label>Title</label></td>
                                                        <td>{{deal.deal.title}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Exclusive</label></td>
                                                        <td>{{getYesNo(deal.deal.exclusive);}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Hot Deal</label></td>
                                                        <td>{{getYesNo(deal.deal.hot_deal);}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Free shipping</label></td>
                                                        <td>{{getYesNo(deal.deal.free_shipping);}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Status</label></td>
                                                        <td>{{deal.deal.status}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Author</label></td>
                                                        <td>{{deal.author.fullname}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Created date</label></td>
                                                        <td>{{deal.deal.created | formatDateTimeLocal}}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td><label>Description</label></td>
                                                        <td>{{deal.deal.description}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Currency</label></td>
                                                        <td>{{deal.deal.currency}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Origin Price</label></td>
                                                        <td>{{deal.deal.origin_price}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>discount_price</label></td>
                                                        <td>{{deal.deal.discount_price}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Discount Percent</label></td>
                                                        <td>{{deal.deal.discount_percent}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Select Store</label></td>
                                                        <td>{{deal.store.name}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Start Date</label></td>
                                                        <td>{{deal.deal.start_date | formatDateTimeLocal}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Expire Date</label></td>
                                                        <td>{{deal.deal.expire_date | formatDateTimeLocal}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Produc Url</label></td>
                                                        <td>{{deal.deal.produc_url}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Deal Tag</label></td>
                                                        <td>{{coupon.deal.deal_tag}}</td>
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