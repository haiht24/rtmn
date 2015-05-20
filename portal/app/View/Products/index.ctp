<?php $this->Html->script('/lib/fromjs/from', ['inline' => false]); ?>
<?php $this->Ng->ngController('ProductsCtrl') ?>
<?php $this->Ng->fdbDirective(['image_upload']); ?>
<?php $this->Ng->ngInit(
    [
        'categories' => isset($categories) ? $categories : [],
        'glstCategoriesFull' => isset($listCategories) ? $listCategories : [],
        'user' => isset($user) ? $user : [],
        'users' => isset($users) ? $users : [],
        'events' => isset($events) ? $events : [],
		'timeZone' => isset($timeZone) ? $timeZone : 'utc',
        'storeCurrentPage' => $this->Session->read('Stores.currentPage') ? $this->Session->read('Stores.currentPage') : 0,
        'couponCurrentPage' => $this->Session->read('Coupons.currentPage') ? $this->Session->read('Coupons.currentPage') : 0,
        'dealCurrentPage' => $this->Session->read('Deals.currentPage') ? $this->Session->read('Deals.currentPage') : 0,
        'categoryCurrentPage' => $this->Session->read('Categories.currentPage') ? $this->Session->read('Categories.currentPage') : 0,
        'countries' => isset($countries) ? $countries : []
    ]);

?>
<script type="text/javascript">
  Config.timeZone = <?php echo json_encode($timeZone) ?> ;
</script>
<link rel="stylesheet" type="text/css" href="../portal/lib/x-editable/css/bootstrap-editable.css">
<style>
    .category-detail > td {
        padding: 0 !important;
    }

    .category-detail > td .category-info {
        padding: 8px 10px;
    }

    .category-info label {
        font-weight: bold;
    }

    .store-detail > td {
        padding: 0 !important;
    }

    .store-detail > td .store-info {
        padding: 8px 10px;
    }

    .store-info label {
        font-weight: bold;
    }

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

    .deal-detail > td {
        padding: 0 !important;
    }

    .deal-detail > td .deal-info {
        padding: 8px 10px;
    }

    .deal-info label {
        font-weight: bold;
    }

    #modal-add-deal .smart-form .row {
        margin-bottom: 10px;
    }

    #modal-add-deal .smart-form .select2-choice {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
    }


</style>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-"></i>
            MostCoupon <span>> Content Management</span>
        </h1>
    </div>
</div>
<?php if ($this->Session->read('Auth.User.Email') == 'admin@mccorp.com'): ?>
<div class="row">
    <div class="col-sm-2"><a class="btn btn-danger btn-clear-data btn-block" ng-click="clearData()" href="">Clear Old
            Data</a></div>
    <div class="col-sm-2">
        <select class="form-control" id="db_name">
            <option value="mcold">MostCoupon</option>
            <option value="dvold">DiscountsVoucher</option>
        </select>
    </div>
    <div class="col-sm-2"><a class="btn btn-success btn-pull-data btn-block" ng-click="pullData()" href="">Pull
            Stores</a></div>
    <div class="col-sm-2"><a class="btn btn-warning btn-pull-coupons btn-block" ng-click="pullCoupons()" href="">Pull Coupons</a></div>
    <div class="col-sm-offset-2 col-sm-2"><a class="btn btn-info btn-update-code btn-block" ng-click="updateGoCode()"
                                             href="">Update Go's Code</a></div>
    <p class="col-sm-12">&nbsp;</p>
</div>
<?php endif; ?>
<section id="widget-grid" class=" ">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-cate-list"
                 data-widget-deletebutton="false"
                 data-widget-colorbutton="false"
                 data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-tag"></i> </span>
                    <span class="header-h2">Total: {{categoryItem.totalItems | number}} Categories</span>
                </header>
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body">
                        <div class="search-box-container col-xs-12 form-horizontal">
                            <div class="form-group">
                                <label>Filter by:</label>
                                <a class="btn btn-primary" ng-click="showAllCategory();">Clear</a>
                            </div>
                            <div class="form-group">
                                <div class="icon-addon addon-md">
                                    <input type="text" ng-model="categoryItem.filter"
                                           ng-change="searchCategory()"
                                           placeholder="name, alias, description" class="form-control">
                                    <label class="glyphicon glyphicon-search" rel="tooltip" title=""
                                           data-original-title="keyword"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <select class="form-control" ng-model="categoryItem.userFilter"
                                                ng-change="searchCategory()">
                                            <option value='0' selected>All Author</option>
                                            <option ng-repeat="user in users" ng-value='user.user.id'>
                                                {{user.user.fullname ? user.user.fullname : user.user.email}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control" ng-model="categoryItem.statusFilter"
                                                ng-change="searchCategory()">
                                            <option value='0' selected>All Status</option>
                                            <option value='pending'>pending</option>
                                            <option value='published'>published</option>
                                            <option value='trash'>trash</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input type="text" id='dateCategoryFilter' placeholder="Created From"
                                                   ng-model="categoryItem.createdFromFilter"
                                                   ng-change="searchCategory()"
                                                   class="form-control start-date" readonly/>
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input type="text" id='datePublishCategoryFilter'
                                                   ng-model="categoryItem.createdToFilter"
                                                   ng-change="searchCategory()" placeholder="Created To"
                                                   class="form-control end-date" readonly>
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <a class="btn btn-primary btn-add-cate"
                                   ng-if="user.permissions.allow_add_category == 1"
                                   data-toggle="modal"
                                   data-target="#modal-add-cate"
                                   ng-click="initCategory();">
                                    <i class="fa fa-plus"></i>
                                    Add Category
                                </a>
                                <a class="btn btn-danger"
                                   ng-if="user.permissions.allow_edit_category == 1"
                                   ng-click="deleteCategories();" ng-disabled="categoryItem.disalbledDeleteAll">
                                    <i class="fa fa-times"></i>
                                    Delete
                                </a>
                            </div>
                        </div>
                        <div class='clearfix'></div>
                        <div class="table-responsive">
                            <table id="category-table"
                                   class="table table-striped table-bordered table-hover dataTable">
                                <thead>
                                <tr>
                                    <th class="checkbox-column smart-form">
                                        <label class="checkbox">
                                            <input type="checkbox" class="check_all" ng-click="checkStatusCategories()">
                                            <i style="top: -17px !important;left: 1px;"></i>
                                        </label>
                                    </th>
                                    <th ng-click="sortByCategory('name');"
                                        ng-class="{'sorting_asc':(filterCategoryOptions.sortBy == true
                                        && filterCategoryOptions.sortField == 'name'),
                                        'sorting_desc': (filterCategoryOptions.sortBy == false
                                        && filterCategoryOptions.sortField == 'name')}" class="sorting">Name
                                    </th>
                                    <th>Author</th>
                                    <th ng-click="sortByCategory('created');"
                                        ng-class="{'sorting_asc':(filterCategoryOptions.sortBy == true
                                        && filterCategoryOptions.sortField == 'created'),
                                        'sorting_desc': (filterCategoryOptions.sortBy == false
                                        && filterCategoryOptions.sortField == 'created')}" class="sorting">Created
                                        date
                                    </th>
                                    <th>Status</th>
                                    <th class="action">Action</th>
                                </tr>
                                </thead>
                                <tbody ng-repeat="(indexItem, cate) in categoryItem.pages[categoryItem.currentPage]">
                                <tr>
                                    <td class="smart-form">
                                        <label class="checkbox">
                                            <input type="checkbox" class="check_element" id="{{cate.category.id}}"
                                                   ng-click="checkStatusCategories()">
                                            <i></i>
                                        </label>
                                    </td>
                                    <td data-target="#demo-{{indexItem}}-category"
                                        data-toggle="collapse">{{cate.category.name}}
                                        <br/>
                                        <a href="" ng-click="">Store(s): {{cate.category.store_count}}</a>
                                    </td>
                                    <td data-target="#demo-{{indexItem}}-category"
                                        data-toggle="collapse">{{cate.author.fullname}}
                                    </td>
                                    <td data-target="#demo-{{indexItem}}-category"
                                        data-toggle="collapse">{{cate.category.created | formatDateTimeLocal}}
                                    </td>
                                    <td><a href="#" class="status" data-name="status" data-type="select"
                                           data-pk="{{cate.category.id}}"
                                           data-value="{{cate.category.status}}" data-title="Change Status"
                                           data-url="<?php echo $this->webroot; ?>products/changeStatusCategory">{{cate.category.status}}</a>
                                    </td>
                                    <td>
                                        <a ng-click='editCategory(cate, indexItem)'
                                           ng-if="user.permissions.allow_edit_category == 1"
                                           data-target="#modal-add-cate"
                                           data-toggle="modal" class="btn btn-xs btn-link"><i
                                                class="fa fa-pencil-square-o"></i>
                                        </a>
                                        <a href="" class="btn btn-xs btn-link btn-trash"
                                           ng-show="cate.category.status == 'trash' && user.permissions.allow_add_category == 1"
                                           ng-click="deleteCategory(cate.category.id)" ><i
                                                class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr class="category-detail">
                                    <td colspan="7">
                                        <div class="collapse" id="demo-{{indexItem}}-category">
                                            <div class="category-info">
                                                <div class="col-md-6">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td><label>Icon</label></td>
                                                            <td><i class="{{cate.category.icon}}"></i></td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Alias</label></td>
                                                            <td>{{cate.category.alias}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Parent</label></td>
                                                            <td>{{cate.father.name}}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td>
                                                                <label>Publish Date</label>
                                                            </td>
                                                            <td>
                                                                    <span
                                                                        ng-show="cate.category.publish_date">
                                                                        {{cate.category.publish_date | formatDateTimeLocal}}
                                                                    </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Description</label></td>
                                                            <td>{{cate.category.description}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Tags</label></td>
                                                            <td>
                                                            <span class="label label-primary"
                                                                  ng-repeat="tag in arraySplit(cate.category.tags,',')"
                                                                  style="margin: 0 3px 3px 0;display: inline-block;">{{tag}}</span>
                                                            </td>
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
                                <li class="arrow" ng-click="prevPageCategory()"
                                    ng-show="categoryItem.pages.length > 1"
                                    ng-class="{'disabled': 0 == categoryItem.currentPage}">
                                    <a href="">Previous</a>
                                </li>
                                <li ng-repeat="n in range(categoryItem.pages.length)"
                                    ng-class="{active: n == categoryItem.currentPage}"
                                    ng-click="setPageCategory(n)">
                                    <a href="" ng-show="n >= 0 && n < 10">{{ n + 1 }}</a>
                                </li>
                                <li>
                                    <input type="number" ng-model="categoryItem.currentPageInc"
                                           ng-show="categoryItem.pages.length > 10"
                                           ng-change="changePageCategory()"/>
                                </li>
                                <li ng-click="setPageCategory(categoryItem.pages.length - 1)"
                                    ng-class="{active: (categoryItem.pages.length - 1) == categoryItem.currentPage}">
                                    <a href="" ng-show="categoryItem.pages.length > 10">{{
                                        categoryItem.pages.length }}</a>
                                </li>
                                <li class="arrow" ng-show="categoryItem.pages.length > 1"
                                    ng-click="nextPageCategory()"
                                    ng-class="{'disabled': (categoryItem.pages.length - 1) == categoryItem.currentPage}">
                                    <a href="">Next</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
<section id="widget-grid-store" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-store-list"
                 data-widget-deletebutton="false"
                 data-widget-colorbutton="false"
                 data-widget-editbutton="false">

                <header>
                    <span class="widget-icon"> <i class="fa fa-tag"></i> </span>
                    <span class="header-h2">Total: {{storeItem.totalStores | number}} Stores</span>
                </header>
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body">
                        <div class="search-box-container col-xs-12 form-horizontal">
                            <div class="form-group">
                                <label>Filter by:</label>
                                <a class="btn btn-primary" ng-click="showAllStore();">Clear</a>
                            </div>
                            <div class="form-group">
                                <div class="icon-addon addon-md">
                                    <input type="text" ng-model="storeItem.filter"
                                           ng-change="searchStore()"
                                           placeholder="name, most coupon url" class="form-control">
                                    <label class="glyphicon glyphicon-search" rel="tooltip" title=""
                                           data-original-title="keyword"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <select class="form-control" ng-model="storeItem.userFilter"
                                                ng-change="searchStore()">
                                            <option value='0' selected>All Author</option>
                                            <option ng-repeat="user in users" ng-value='user.user.id'>
                                                {{user.user.fullname ? user.user.fullname : user.user.email}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control" ng-model="storeItem.statusFilter"
                                                ng-change="searchStore()">
                                            <option value='0' selected>All Status</option>
                                            <option value='pending'>pending</option>
                                            <option value='published'>published</option>
                                            <option value='trash'>trash</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input type="text" id='dateStoreFilter' placeholder="Created From"
                                                   ng-model="storeItem.createdFromFilter"
                                                   ng-change="searchStore()" class="form-control start-date"
                                                   readonly>
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input type="text" id='datePublishStoreFilter'
                                                   ng-model="storeItem.createdToFilter"
                                                   ng-change="searchStore()" placeholder="Created To"
                                                   class="form-control end-date" readonly>
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <a class="btn btn-primary btn-add-store"
                                   ng-if="user.permissions.allow_add_store == 1"
                                   data-toggle="modal"
                                   data-target="#modal-add-store"
                                   ng-click="initAddNewStore()"><i class="fa fa-plus"></i> Add Store</a>
                                <a class="btn btn-danger"
                                   ng-if="user.permissions.allow_edit_store == 1"
                                   ng-click="deleteStores()" ng-disabled="storeItem.disalbledDeleteAll"><i
                                        class="fa fa-times"></i> Delete</a>
                            </div>
                        </div>
                        <div class='clearfix'></div>
                        <div class="table-responsive">
                            <table id="store-table" class="table table-striped table-bordered table-hover dataTable">
                                <thead>
                                <tr>
                                    <th class="checkbox-column smart-form">
                                        <label class="checkbox">
                                            <input type="checkbox" class="check_all" ng-click="checkStatusStores()">
                                            <i style="top: -17px !important;left: 1px;"></i>
                                        </label>
                                    </th>
                                    <th ng-click="sortByStore('name');"
                                        ng-class="{'sorting_asc':(filterStoreOptions.sortBy == true
                                        && filterStoreOptions.sortField == 'name'),
                                        'sorting_desc': (filterStoreOptions.sortBy == false
                                        && filterStoreOptions.sortField == 'name')}" class="sorting">Name
                                    </th>
                                    <th>Author</th>
                                    <th ng-click="sortByStore('created');"
                                        ng-class="{'sorting_asc':(filterStoreOptions.sortBy == true
                                        && filterStoreOptions.sortField == 'created'),
                                        'sorting_desc': (filterStoreOptions.sortBy == false
                                        && filterStoreOptions.sortField == 'created')}" class="sorting">Created date
                                    </th>
                                    <th>Status</th>
                                    <th style="width: 121px;">Actions</th>
                                </tr>
                                </thead>
                                <tbody ng-repeat="(index, store) in storeItem.pages">
                                <tr>
                                    <td class="smart-form">
                                        <label class="checkbox">
                                            <input type="checkbox" id="{{store.store.id}}" class="check_element"
                                                   ng-click="checkStatusStores()">
                                            <i></i>
                                        </label>
                                    </td>
                                    <td data-target="#demo-{{index}}-store"
                                        data-toggle="collapse">
                                        <a ng-show="store.store.status == 'published'"
                                           target="_blank" class="store-title-link"
                                           href="<?php echo str_replace('portal', 'mostcoupon', $this->Html->url('/', true)); ?>{{store.store.alias}}-coupons">
                                            {{store.store.name}}
                                        </a>
                                        <label ng-hide="store.store.status == 'published'">
                                            {{store.store.name}}
                                        </label>
                                        <!--                                        <br/>-->
                                        <!--                                        <a ng-click="" href="">-->
                                        <!--                                            Deal(s): {{store.store.deal_count}}-->
                                        <!--                                        </a> &nbsp;-->
                                        <!--                                        <a ng-click="" href="">-->
                                        <!--                                            Coupon(s): {{store.store.coupon_count}}-->
                                        <!--                                        </a>-->
                                    </td>
                                    <td data-target="#demo-{{index}}-store" class="store-lazy-load"
                                        data-toggle="collapse">{{store.author.fullname}}
                                    </td>
                                    <td data-target="#demo-{{index}}-store" class="store-lazy-load"
                                        data-toggle="collapse">{{store.store.created | formatDateTimeLocal}}
                                    </td>
                                    <td>
                                        <a href="#" class="status" data-name="status" data-type="select"
                                           data-pk="{{store.store.id}}"
                                           data-value="{{store.store.status}}" data-title="Change Status"
                                           data-url="<?php echo $this->webroot; ?>products/changeStatusStore">{{store.store.status}}</a>
                                    </td>
                                    <td>
                                        <a ng-click="editStore(store,index)"
                                           data-toggle="modal"
                                           data-target="#modal-add-store"
                                           ng-show='user.permissions.allow_edit_store == 1' class="btn btn-xs btn-link"><i
                                                class="fa fa-pencil-square-o"></i>
                                        </a>
                                        <a ng-click="addDeal(store)" class="btn btn-info btn-xs"
                                           data-toggle="modal"
                                           data-target="#modal-add-deal"
                                           ng-show="store.store.status == 'published'"><i class="fa fa-plus"></i>
                                            D
                                        </a>
                                        <a ng-click="addCoupon(store)" class="btn btn-info btn-xs"
                                           data-toggle="modal"
                                           data-target="#modal-add-coupon"
                                           ng-show="store.store.status == 'published'"><i class="fa fa-plus"></i>
                                            C
                                        </a>
                                        <a href="" class="btn btn-xs btn-link btn-trash"
                                           ng-show="store.store.status == 'trash'
                                                            && user.permissions.allow_add_store == 1"
                                           ng-click='deleteStore(store.store.id)' ><i
                                                class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr class="store-detail">
                                    <td colspan="7">
                                        <div class="collapse store-collapse" id="demo-{{index}}-store">
                                            <div class="store-info">
                                                <div class="col-md-6">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td><label>Custom Keywords</label></td>
                                                            <td>{{store.store.custom_keywords}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Best Store</label></td>
                                                            <td>{{getYesNo(store.store.best_store);}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Logo</label></td>
                                                            <td>
                                                                <img style="width: 200px;height: 200px" class="store-lazy-load-image"
                                                                     data-src="{{store.store.logo}}"/>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Social Image</label></td>
                                                            <td>
                                                                <img style="width: 200px;height: 200px" class="store-lazy-load-image"
                                                                     data-src="{{store.store.social_image}}"/>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>Publish Date</label>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    ng-show="store.store.publish_date">
                                                                    {{store.store.publish_date | formatDateTimeLocal}}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td><label>Category</label></td>
                                                            <td><span class="label label-info"
                                                                      ng-repeat="cate in glstCategories"
                                                                      ng-if="arrayContains(cate.category.id,store.store.categories_id)"
                                                                      style="margin: 0 3px 3px 0;display: inline-block;">{{cate.category.name}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Store URL</label></td>
                                                            <td>{{store.store.store_url}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Alias</label></td>
                                                            <td>{{store.store.alias}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Affiliate URL</label></td>
                                                            <td>{{store.store.affiliate_url}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Related Store</label></td>
                                                            <td>
                                                                    <span
                                                                        ng-repeat="(indexLo, location) in store.store.locations">
                                                                        {{location.store.name}}
                                                                    </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Description</label></td>
                                                            <td>{{store.store.description}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Tag</label></td>
                                                            <td><span class="label label-primary"
                                                                      ng-repeat="tag in arraySplit(store.store.tags,',')"
                                                                      style="margin: 0 3px 3px 0;display: inline-block;">{{tag}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Country</label></td>
                                                            <td>
                                                              <span class="label label-info"
                                                                    ng-repeat="country in countries"
                                                                    ng-if="arrayContains(country.Country.countrycode,store.store.countries_code)"
                                                                    style="margin: 0 3px 3px 0;display: inline-block;">{{country.Country.countryname}}</span>
                                                            </td>
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
                            <paging
                                page="storeItem.currentPage"
                                page-size="storeItem.itemsPerPage"
                                total="storeItem.totalStores"
                                adjacent="2"
                                dots="..."
                                scroll-top="{{scrollTop}}"
                                hide-if-empty="true"
                                ul-class="pagination"
                                active-class="active"
                                disabled-class="disabled"
                                show-prev-next="true"
                                paging-action="setPageStore(page)">
                            </paging>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
<section id="widget-grid-coupon" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="wid-coupon-list"
                 class="jarviswidget jarviswidget-color-blueDark"
                 data-widget-deletebutton="false"
                 data-widget-colorbutton="false"
                 data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-tag"></i> </span>
                    <span class="header-h2">Total: {{couponItem.totalCoupons | number}} Coupons</span>
                </header>
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body">
                        <div class="search-box-container col-xs-12 form-horizontal">
                            <div class="form-group">
                                <label>Filter by:</label>
                                <a class="btn btn-primary" ng-click="showAllCoupon();">Clear</a>
                            </div>
                            <div class="form-group">
                                <div class="icon-addon addon-md">
                                    <input type="text" ng-change="searchCoupon()"
                                           ng-model="couponItem.filter"
                                           placeholder="title, coupon type"
                                           id="search-coupon" class="form-control">
                                    <label class="glyphicon glyphicon-search" rel="tooltip" title=""
                                           data-original-title="keyword"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <select class="form-control" ng-model="couponItem.userFilter"
                                                ng-change="searchCoupon()">
                                            <option value='0' selected>All Author</option>
                                            <option ng-repeat="user in users" ng-value='user.user.id'>
                                                {{user.user.fullname ? user.user.fullname : user.user.email}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control" ng-model="couponItem.statusFilter"
                                                ng-change="searchCoupon()">
                                            <option value='0' selected>All Status</option>
                                            <option value='pending'>pending</option>
                                            <option value='published'>published</option>
                                            <option value='trash'>trash</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input type="text" id='dateCouponFilter' placeholder="Created From"
                                                   ng-model="couponItem.createdFromFilter"
                                                   ng-change="searchCoupon()"
                                                   class="form-control start-date"
                                                   readonly>
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input type="text" id='datePublishCouponFilter'
                                                   ng-model="couponItem.createdToFilter"
                                                   ng-change="searchCoupon()" placeholder="Created To"
                                                   class="form-control end-date" readonly>
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <a class="btn btn-danger" ng-disabled="couponItem.disalbledDeleteAll"
                                   ng-click="deleteCoupons()"><i class="fa fa-times"></i> Delete</a>
                            </div>
                        </div>
                        <div class='clearfix'></div>
                        <div class="table-responsive">

                            <table id="coupon-table"
                                   class="table table-striped table-bordered table-hover dataTable">
                                <thead>
                                <tr>
                                    <th class="checkbox-column smart-form">
                                        <label class="checkbox">
                                            <input type="checkbox" class="check_all" ng-click="checkStatusCoupons()">
                                            <i style="top: -17px !important;left: 1px;"></i>
                                        </label>
                                    </th>
                                    <th ng-click="sortByCoupon('title_store');"
                                        ng-class="{'sorting_asc':(filterCouponOptions.sortBy == true
                                        && filterCouponOptions.sortField == 'title_store'),
                                        'sorting_desc': (filterCouponOptions.sortBy == false
                                        && filterCouponOptions.sortField == 'title_store')}" class="sorting">Title
                                        (Store)
                                    </th>
                                    <th>Author</th>
                                    <th ng-click="sortByCoupon('created');"
                                        ng-class="{'sorting_asc':(filterCouponOptions.sortBy == true
                                        && filterCouponOptions.sortField == 'created'),
                                        'sorting_desc': (filterCouponOptions.sortBy == false
                                        && filterCouponOptions.sortField == 'created')}" class="sorting">Created
                                        date
                                    </th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody ng-repeat="(index, coupon) in couponItem.pages">
                                <tr>
                                    <td class="smart-form"><label class="checkbox">
                                            <input type="checkbox" class="check_element" id="{{coupon.coupon.id}}"
                                                   ng-click="checkStatusCoupons()">
                                            <i></i>
                                        </label>
                                    </td>
                                    <td data-target="#demo-{{index}}-coupon" class="coupon-lazy-load"
                                        data-toggle="collapse">{{coupon.coupon.title_store}}
                                    </td>
                                    <td data-target="#demo-{{index}}-coupon" class="coupon-lazy-load"
                                        data-toggle="collapse">{{coupon.author.fullname}}
                                    </td>
                                    <td data-target="#demo-{{index}}-coupon" class="coupon-lazy-load"
                                        data-toggle="collapse">{{coupon.coupon.created | formatDateTimeLocal}}
                                    </td>
                                    <td><a href="#" class="status" data-name="status" data-type="select"
                                           data-pk="{{coupon.coupon.id}}"
                                           data-value="{{coupon.coupon.status}}" data-title="Change Status"
                                           data-url="<?php echo $this->webroot; ?>products/changeStatusCoupon">{{coupon.coupon.status}}</a>
                                    </td>
                                    <td>
                                        <a ng-click="editCoupon(coupon,index)"
                                           data-toggle="modal"
                                           data-target="#modal-add-coupon" class="btn btn-xs btn-link"><i
                                                class="fa fa-pencil-square-o"></i>
                                        </a>
                                        <a href="" class="btn btn-xs btn-lin btn-trash"
                                           ng-show="coupon.coupon.status == 'trash'
                                                            && user.permissions.allow_add_active_coupon == 1"
                                           ng-click='deleteCoupon(coupon.coupon.id)' ><i
                                                class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr class="coupon-detail">
                                    <td colspan="8">
                                        <div class="collapse" id="demo-{{index}}-coupon">
                                            <div class="coupon-info">
                                                <div class="col-md-6">
                                                    <table class="table table-bordered">
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
                                                            <td><label>Coupon Image</label></td>
                                                            <td><img style="width: 200px;height: 200px" class="coupon-lazy-load-image"
                                                                     data-src="{{coupon.coupon.coupon_image}}"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Social Image</label></td>
                                                            <td><img style="width: 200px;height: 200px" class="coupon-lazy-load-image"
                                                                     data-src="{{coupon.coupon.social_image}}"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>Publish Date</label>
                                                            </td>
                                                            <td>
                                                                <span ng-show="coupon.coupon.publish_date">
                                                                    {{coupon.coupon.publish_date | formatDateTimeLocal}}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td><label>Description Store</label></td>
                                                            <td>{{coupon.coupon.description_store}}</td>
                                                        </tr>
                                                        <tr ng-show="coupon.coupon.description_category">
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
                                                            <td><label>Description [Deal (Notable Coupons)]</label>
                                                            </td>
                                                            <td>{{coupon.coupon.description_related_coupon}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Product Link</label></td>
                                                            <td>{{coupon.coupon.product_link}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Exculsive</label></td>
                                                            <td>{{getYesNo(coupon.coupon.exclusive);}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Verified</label></td>
                                                            <td>{{getYesNo(coupon.coupon.verified);}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Sticky</label></td>
                                                            <td>{{coupon.coupon.sticky}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Discount</label></td>
                                                            <td>
                                                                {{coupon.coupon.currency}}{{coupon.coupon.discount}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Coupon Type</label></td>
                                                            <td>{{coupon.coupon.coupon_type}}</td>
                                                        </tr>
                                                        <tr ng-show="coupon.coupon.coupon_type == 'Coupon Code' || coupon.coupon.coupon_type == 'Free Shipping'">
                                                            <td><label>Coupon Code</label></td>
                                                            <td>{{coupon.coupon.coupon_code}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Expire Date</label></td>
                                                            <td>{{coupon.coupon.expire_date | formatDateTimeLocal}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Event</label></td>
                                                            <td>{{coupon.event.name}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Store</label></td>
                                                            <td>{{coupon.store.name}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Category</label></td>
                                                            <td><span class="label label-info"
                                                                      ng-repeat="cate in coupon.store.categories"
                                                                      ng-if="arrayContains(cate.category.id,coupon.coupon.categories_id)"
                                                                      style="margin: 0 3px 3px 0;display: inline-block;">{{cate.category.name}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Tags</label></td>
                                                            <td>
                                                                <span class="label label-primary"
                                                                      ng-repeat="tag in arraySplit(coupon.coupon.tags,',')"
                                                                      style="margin: 0 3px 3px 0;display: inline-block;">{{tag}}</span>
                                                            </td>
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
                            <paging
                                page="couponItem.currentPage"
                                page-size="couponItem.itemsPerPage"
                                total="couponItem.totalCoupons"
                                adjacent="2"
                                dots="..."
                                scroll-top="{{scrollTop}}"
                                hide-if-empty="true"
                                ul-class="pagination"
                                active-class="active"
                                disabled-class="disabled"
                                show-prev-next="true"
                                paging-action="setPageCoupon(page)">
                            </paging>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
<section id="widget-grid-deal" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="wid-deal-list"
                 class="jarviswidget jarviswidget-color-blueDark"
                 data-widget-deletebutton="false"
                 data-widget-colorbutton="false"
                 data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-tag"></i> </span>
                    <span class="header-h2">Total: {{dealItem.totalDeals | number}} Deals</span>
                </header>
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body">
                        <div class="search-box-container col-xs-12 form-horizontal">
                            <div class="form-group">
                                <label>Filter by:</label>
                                <a class="btn btn-primary" ng-click="clearAllDealFilter();">Clear</a>
                            </div>
                            <div class="form-group">
                                <div class="icon-addon addon-md">
                                    <input type="text" ng-change="searchDeal()"
                                           ng-model="dealItem.filter"
                                           placeholder="title, deal status"
                                           id="search-text-deal" class="form-control">
                                    <label class="glyphicon glyphicon-search" rel="tooltip" title=""
                                           data-original-title="keyword"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <select class="form-control" ng-model="dealItem.userFilter"
                                                ng-change="searchDeal()">
                                            <option value='0' selected>All Author</option>
                                            <option ng-repeat="user in users" ng-value='user.user.id'>
                                                {{user.user.fullname ? user.user.fullname : user.user.email}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control" ng-model="dealItem.statusFilter"
                                                ng-change="searchDeal()">
                                            <option value='0' selected>All Status</option>
                                            <option value='pending'>pending</option>
                                            <option value='published'>published</option>
                                            <option value='trash'>trash</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input type="text" id='dateDealFilter' placeholder="Created From"
                                                   ng-model="dealItem.createdFromFilter"
                                                   ng-change="searchDeal()" class="form-control start-date"
                                                   readonly>
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input type="text" id='datePublishDealFilter'
                                                   ng-model="dealItem.createdToFilter"
                                                   ng-change="searchDeal()" placeholder="Created To"
                                                   class="form-control end-date" readonly>
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <a class="btn btn-danger" ng-disabled="dealItem.disalbledDeleteAll"
                                   ng-click="deleteDeals()"><i class="fa fa-times"></i> Delete</a>
                            </div>
                        </div>

                        <div class='clearfix'></div>
                        <div class="table-responsive">
                            <table id="deal-table" class="table table-striped table-bordered table-hover dataTable">
                                <thead>
                                <tr>
                                    <th class="checkbox-column smart-form">
                                        <label class="checkbox">
                                            <input type="checkbox" class="check_all" ng-click="checkStatusDeals()">
                                            <i style="top: -17px !important;left: 1px;"></i>
                                        </label>
                                    </th>
                                    <th ng-click="sortByDeal('title');"
                                        ng-class="{'sorting_asc':(filterDealOptions.sortBy == true
                                        && filterDealOptions.sortField == 'title'),
                                        'sorting_desc': (filterDealOptions.sortBy == false
                                        && filterDealOptions.sortField == 'title')}" class="sorting">Title
                                    </th>
                                    <th>Author</th>
                                    <th ng-click="sortByDeal('created');"
                                        ng-class="{'sorting_asc':(filterDealOptions.sortBy == true
                                        && filterDealOptions.sortField == 'created'),
                                        'sorting_desc': (filterDealOptions.sortBy == false
                                        && filterDealOptions.sortField == 'created')}" class="sorting">Created
                                        date
                                    </th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody ng-repeat="(index, deal) in dealItem.pages">
                                <tr>
                                    <td class="smart-form"><label class="checkbox">
                                            <input type="checkbox" class="check_element" id="{{deal.deal.id}}"
                                                   ng-click="checkStatusDeals()">
                                            <i></i>
                                        </label>
                                    </td>
                                    <td data-target="#demo-{{index}}-deal" class="deal-lazy-load"
                                        data-toggle="collapse"><a ng-show="deal.deal.status == 'published'"
                                                                  target="_blank"
                                                                  href="<?php echo str_replace('portal', 'mostcoupon', $this->Html->url('/', true)); ?>deals/details/{{deal.deal.id}}">
                                            {{deal.deal.title}}
                                        </a>
                                        <label ng-hide="deal.deal.status == 'published'">
                                            {{deal.deal.title}}
                                        </label>
                                    </td>
                                    <td data-target="#demo-{{index}}-deal" class="deal-lazy-load"
                                        data-toggle="collapse">{{deal.author.fullname}}
                                    </td>
                                    <td data-target="#demo-{{index}}-deal" class="deal-lazy-load"
                                        data-toggle="collapse">{{deal.deal.created | formatDateTimeLocal}}
                                    </td>
                                    <td>
                                        <a href="#" class="status" data-name="status" data-type="select"
                                           data-pk="{{deal.deal.id}}"
                                           data-value="{{deal.deal.status}}" data-title="Change Status"
                                           data-url="<?php echo $this->webroot; ?>deals/changeStatusDeal">{{deal.deal.status}}</a>
                                    </td>
                                    <td>
                                        <a ng-click="editDeal(deal,index)"
                                           data-toggle="modal"
                                           data-target="#modal-add-deal" class="btn btn-xs btn-link"><i
                                                class="fa fa-pencil-square-o"></i>
                                        </a>
                                        <a href="" class="btn btn-xs btn-link btn-trash"
                                           ng-show="deal.deal.status == 'trash'
                                                            && user.permissions.allow_add_active_coupon == 1"
                                           ng-click='deleteDeal(deal.deal.id)' ><i
                                                class="fa fa-trash"></i></a>

                                    </td>
                                </tr>
                                <tr class="deal-detail">
                                    <td colspan="8">
                                        <div class="collapse" id="demo-{{index}}-deal">
                                            <div class="deal-info">
                                                <div class="col-md-6">
                                                    <table class="table table-bordered">
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
                                                            <td>
                                                                <label>Deal image</label>
                                                            </td>
                                                            <td>
                                                                <img style="width: 100%" class="deal-lazy-load-image"
                                                                     data-src="{{deal.deal.deal_image}}"/>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>Publish Date</label>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    ng-show="deal.deal.publish_date">
                                                                    {{deal.deal.publish_date | formatDateTimeLocal}}
                                                                </span>
                                                            </td>
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
                                                            <td class="auto-numeric">{{deal.deal.origin_price}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Real price</label></td>
                                                            <td class="auto-numeric">{{deal.deal.discount_price}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Discount Percent</label></td>
                                                            <td>{{deal.deal.discount_percent}}%</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Store</label></td>
                                                            <td>{{deal.store.name}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Category</label></td>
                                                            <td><span class="label label-info"
                                                                      ng-repeat="cate in deal.store.categories"
                                                                      ng-if="arrayContains(cate.category.id,deal.deal.categories_id)"
                                                                      style="margin: 0 3px 3px 0;display: inline-block;">{{cate.category.name}}</span>
                                                            </td>
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
                                                            <td><span class="label label-primary"
                                                                      ng-repeat="tag in arraySplit(deal.deal.deal_tag,',')"
                                                                      style="margin: 0 3px 3px 0;display: inline-block;">{{tag}}</span>
                                                            </td>
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
                            <paging
                                page="dealItem.currentPage"
                                page-size="couponItem.itemsPerPage"
                                total="dealItem.totalDeals"
                                adjacent="2"
                                dots="..."
                                scroll-top="{{scrollTop}}"
                                hide-if-empty="true"
                                ul-class="pagination"
                                active-class="active"
                                disabled-class="disabled"
                                show-prev-next="true"
                                paging-action="setPageDeal(page)">
                            </paging>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
<div class="modal fade in" id="modal-add-cate" tabindex="-1" role="dialog"
     aria-labelledby="modal-label-add-cate" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modal-label-add-cate">Add New Category</h4>

                <div class="draft-warning" style="float: right;"
                     ng-show="categoryItem.showDraft && categoryItem.popupDraft && categoryItem.addMode">
                    <a ng-click="loadDraftCategory()"><?php echo __('Last draft for your Category') ?> ({{
                        categoryItem.popupDraft.created }})</a>
                </div>
            </div>
            <div class="modal-body">
                <form class="smart-form" name='addCateForm' novalidate>
                    <section>
                        <label class="control-label">Name</label>
                        <label class="input">
                            <i class="icon-append fa fa-tag"></i>
                            <input type="text"
                                   placeholder="Category Name"
                                   ng-model="currentCategory.name" id="category-name"
                                   required
                                   name="name"
                                   ng-disabled="user.permissions.allow_add_category == 0
                                           && user.permissions.allow_edit_category == 1"/>
                        </label>

                        <p class='error' ng-show='showError && addCateForm.name.$invalid'>Please enter category
                            name</p>

                        <div ng-show="currentCategory.name">
                            <button ng-click="checkExistsNameCategory()">Check Exists Name</button>
                            <p ng-show="categoryItem.checkExist">Name is exists.</p>

                            <p ng-show="categoryItem.checkNotExist">Name is not exists.</p>
                        </div>
                    </section>
                    <section>
                        <label class="control-label">Alias</label>
                        <label class="input">
                            <i class="icon-append fa fa-info"></i>
                            <input type="text" placeholder="Category Alias"
                                   ng-model="currentCategory.alias"
                                   name="alias" id="category-alias"
                                   ng-disabled="user.permissions.allow_add_category == 0
                                           && user.permissions.allow_edit_category == 1">
                        </label>

                        <p class='error' ng-show='showError && addCateForm.alias.$invalid'>Please enter category
                            alias</p>
                    </section>
                    <section>
                        <label class="control-label" for="">Icon</label>
                        <label class="input">
                            <i class="icon-append {{currentCategory.icon}}"></i>
                            <input type="text" placeholder="fa fa-car"
                                   ng-model="currentCategory.icon"
                                   name="icon" id="category-icon">
                        </label>
                        <div>Find a icon at <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a></div>
                    </section>
                    <section>
                        <label class="control-label">Parent</label>
                        <label class="select">
                            <select ng-model="currentCategory.parent_id">
                                <option ng-repeat="item in categories" value="{{item.category.id}}"
                                        ng-selected="currentCategory.parent_id == item.category.id">
                                    {{item.category.name}}
                                </option>
                            </select>
                            <i></i>
                        </label>
                    </section>
                    <section>
                        <label class="control-label">Description</label>
                        <label class="textarea">
                            <i class="icon-append fa fa-comment"></i>
                                    <textarea rows="3" name="description" ng-model="currentCategory.description"
                                              placeholder="Category Description"></textarea>
                        </label>
                    </section>
                    <section>
                        <label class="control-label" for="">Tags</label>
                        <input class="tagsinput category-tags" ng-model="currentCategory.tags"
                               value="{{currentCategory.tags}}"
                               type="hidden" style="width: 100%;display: block">
                    </section>
                    <section ng-show="!categoryItem.addMode">
                        <label class="control-label" for="">Created</label>
                        <label class="control-label">
                            {{currentCategory.created | formatDateTimeLocal}}
                        </label>
                    </section>
                    <section ng-show="!categoryItem.addMode">
                        <label class="control-label" for="">Author</label>
                        <label class="control-label"> <b>
                                {{currentCategory.author.fullname}}</b>
                        </label>
                    </section>
                </form>
            </div>
            <div class="modal-footer">
                <a class="btn btn-default" data-dismiss="modal" id="cancelCate">Cancel</a>
                <button type="button" class="btn btn-primary" ng-click="saveCategory()" id="saveCate"
                        ng-show="(currentCategory.id)">Add
                </button>
                <a class="btn btn-success"
                   ng-click="saveCategory('published')"
                   id="publishCate"
                   ng-show="(currentCategory.status == 'pending' || !currentCategory.id)
                                            && user.permissions.allow_add_category == 1">
                    Publish
                </a>
                <a class="btn btn-info"
                   ng-click="saveCategory('pending')"
                   ng-show="(arrayContains(currentCategory.status, ['trash','published']) || !currentCategory.id)
                                            && user.permissions.allow_add_category == 1">
                    Pending
                </a>
                <a class="btn btn-warning"
                   ng-click="saveCategory('trash')"
                   ng-show="arrayContains(currentCategory.status, ['pending','published'])
                                            && user.permissions.allow_add_category == 1">
                    Trash
                </a>
                <a class="btn btn-danger"
                   ng-click="deleteCategory(currentCategory.id)"
                   ng-show="currentCategory.id && currentCategory.status == 'trash'
                                && user.permissions.allow_add_category == 1">
                    Delete
                </a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade in" id="modal-add-store" tabindex="-1" role="dialog"
     aria-labelledby="modal-label-add-store" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modal-label-add-store">Add New Store</h4>

                <div class="draft-warning" style="float: right;"
                     ng-show="storeItem.showStoreDraft && storeItem.popupStoreDraft && storeItem.addStoreMode">
                    <a ng-click="loadStoreDraft()"><?php echo __('Last draft for your Store') ?> ({{
                        storeItem.popupStoreDraft.created }})</a>
                </div>
            </div>
            <div class="modal-body">
                <form class="addStoreForm form-validator" name='addStoreForm' novalidate>
                    <div class="form-group"
                         ng-class="{'has-error' : (currentStore.name && storeItem.checkNameExist),
                          'has-success' : (currentStore.name && storeItem.checkNotNameExist)}">
                        <label class="control-label">Name <span class="symbol required"></span></label>
                        <input class="form-control required" type="text" placeholder="Store Name"
                               name='name'
                               ng-model="currentStore.name"
                               ng-change="generateMostCoupon()"
                               ng-disabled="user.permissions.allow_add_store == 0"
                               ng-blur="checkNameExists()">

                        <div ng-show="currentStore.name" class="help-block">
                            <p ng-show="storeItem.checkNameExist">This Store name maybe existed. Re-check bellow
                                item:<br>
                                <a ng-click="searchFollowNameOrURL(currentStore.name)" class="btn btn-danger">
                                    {{storeItem.existNameStore.store.name}}
                                </a>
                            </p>

                            <p ng-show="storeItem.checkNotNameExist">Name is not exists.</p>
                        </div>
                    </div>
                    <div class="">
                        <div class="row">
                            <div class="col-sm-6 store-logo form-group">
                                <label class="control-label" style="display: block">Logo <span
                                        class="symbol required"></span></label>
                                <div
                                    ng-class="currentStore.logo ? 'fileinput fileinput-exists' : 'fileinput fileinput-new'"
                                    data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" style="width: 150px; height: 150px;">
                                        <img ng-if="currentStore.logo" ng-src="{{currentStore.logo}}"/>
                                    </div>

                                    <div>
                                        <span class="btn btn-default btn-file"
                                              ng-if='user.permissions.allow_add_store == 1'
                                              image-upload="currentStore.logo" image-loading
                                              max-image-size="307200" nopreview noremove jasny-fileinput no-set-height>
                                        </span>
                                        <a ng-show = "currentStore.logo != ''" ng-click = "currentStore.logo = ''"
                                        href="#" class="btn btn-default" data-dismiss="fileinput">Remove</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 store-social-image form-group">
                                <label class="control-label" style="display: block">Social Image <span
                                        class="symbol required"></span></label>

                                <div
                                    ng-class="currentStore.social_image ? 'fileinput fileinput-exists' : 'fileinput fileinput-new'"
                                    data-provides="fileinput">
                                    <div class="fileinput-preview  thumbnail" style="width: 150px; height: 150px;">
                                        <img ng-if="currentStore.social_image" ng-src="{{currentStore.social_image}}"/>
                                    </div>
                                    <div>
                                        <span class="btn btn-default btn-file"
                                              ng-if='user.permissions.allow_add_store == 1'
                                              image-upload="currentStore.social_image" image-loading
                                              max-image-size="307200" nopreview noremove jasny-fileinput no-set-height>
                                        </span>
                                        <a ng-show = "currentStore.social_image != ''" ng-click = "currentStore.social_image = ''"
                                        href="#" class="btn btn-default" data-dismiss="fileinput">Remove</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" ng-class="{'has-error' : (currentStore.store_url && storeItem.checkURLExist),
                        'has-success' : (currentStore.store_url && storeItem.checkNotURLExist)}">
                        <label class="control-label">Store URL <span class="symbol required"></span></label>
                        <input type="text" class="form-control required" placeholder="Store URL"
                               ng-model="currentStore.store_url" name="store_url"
                               ng-disabled="user.permissions.allow_add_store == 0" ng-blur="checkURLExists()">

                        <div ng-show="currentStore.store_url" class="help-block">
                            <p ng-show="storeItem.checkURLExist">This URL maybe existed. Re-check bellow
                                link:<br>

                            <p ng-repeat="itemURL in storeItem.listURlExist">
                                <a ng-click="searchFollowNameOrURL(currentStore.store_url)">
                                    {{itemURL.store.store_url}} | {{itemURL.store.name}}
                                </a>
                            </p>
                            </p>
                            <p ng-show="storeItem.checkNotURLExist">Store URL is not exists.</p>
                        </div>
                    </div>
                    <div class="form-group" ng-class="{'has-error' : (currentStore.alias && storeItem.checkStoreURLExist),
                        'has-success' : (currentStore.alias && storeItem.checkNotStoreURLExist)}">
                        <label class="control-label">Alias <span class="symbol required"></span></label>
                        <input class="form-control tooltips" type="text" placeholder="Store Alias"
                               ng-model="currentStore.alias"
                               name='alias' id="store_alias"
                               pattern-if="mostCouponUrlRegex"
                               ng-disabled="user.permissions.allow_add_store == 0"
                               data-placement="top" data-rel="tooltip" data-original-title="Please enter valid
                            MostCoupon URL (only contain alphabet or number or '-' character)"
                               ng-blur="checkStoreURLExists()">
                        <span class='help-block'
                              style="display: inline-block"><?php echo str_replace('portal', 'mostcoupon', $this->Html->url('/', true)); ?>
                            {{currentStore.alias}}-coupons</span>

                        <div ng-show="currentStore.alias" class='help-block'>
                            <p ng-show="storeItem.checkStoreURLExist">This URL maybe existed. Re-check bellow
                                link:<br>

                            <p ng-repeat="itemURL in storeItem.existCouponURlStore">
                                <a ng-click="searchFollowNameOrURL(currentStore.alias)">
                                    <?php echo str_replace('portal', 'mostcoupon', $this->Html->url('/', true)); ?>
                                    {{itemURL.store.alias}} | {{itemURL.store.name}}
                                </a>
                            </p>
                            </p>
                            <p ng-show="storeItem.checkNotStoreURLExist">MostCoupon URL is not exists.</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Affiliate URL</label>
                        <input class="form-control" type="text" placeholder="Store Affiliate URL"
                               ng-model="currentStore.affiliate_url" name="affiliate_url"
                               ng-disabled="user.permissions.allow_add_store == 0">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Related Store</label>
                        <ul style="list-style-type: none;padding-left: 20px">
                            <li ng-repeat="(indexLo, location) in currentStore.locations" style="margin-bottom: 7px;">
                                <a ng-click="removeLocation(location)"
                                   ng-disabled="user.permissions.allow_add_store == 0"
                                   class="btn btn-labeled btn-primary">{{location.store.name}} <span
                                        class="btn-label btn-label-right"><i class="fa fa-times"></i></span> </a>
                            </li>
                        </ul>
                        <fieldset class="scheduler-border"
                                  ng-if="user.permissions.allow_add_store && storeItem.suggestList">
                            <legend class="scheduler-border">Suggest List</legend>
                            <div ng-show='storeItem.suggestList && storeItem.suggestList.length > 0'>
                                <ul style="padding-left: 20px">
                                    <li ng-repeat="(indexSug, suggest) in storeItem.suggestList  | limitTo:storeItem.limitSuggest"
                                        ng-click="addLocation(suggest)" style="cursor: pointer">
                                        {{suggest.store.name}}
                                    </li>
                                </ul>
                            </div>
                        </fieldset>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Description</label>
                        <textarea class="form-control" rows="3" name="description"
                                  placeholder="Store Description"
                                  ng-model="currentStore.description">
                        </textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Custom Keywords</label>
                        <input class="form-control" type="text" placeholder="Custom Keywords"
                               ng-model="currentStore.custom_keywords"
                               ng-disabled="user.permissions.allow_add_store == 0">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Category <span class="symbol required"></span></label>
                        <input type="text" id="listCategories" ng-model='currentStore.categories_id'
                               name='listCategories' style="width:100%;display: block" class="required"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Country <span class="symbol required"></span></label>
                        <input type="text" id="listCountries" ng-model='currentStore.countries_code'
                               name='listCountries' style="width:100%;display: block" class="required"/>
                    </div>
                    <div class="form-group more-vendors">
                        <fieldset class="scheduler-border" ng-repeat="vendor in currentStore.vendors">
                            <legend class="scheduler-border">{{vendor.countrycode}}</legend>
                            <input type="hidden" name="{{vendor.countrycode}}-countrycode"
                                   ng-model='vendor.countrycode'>

                            <div class="form-group">
                                <label class="control-label">Store URL <span class="symbol required"></span></label>
                                <input type="text" class="form-control required url" placeholder="Store URL"
                                       ng-model="vendor.store_url"
                                       name="{{vendor.countrycode}}-store_url">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Affiliate URL</label>
                                <input type="text" class="form-control url" placeholder="Affiliate URL"
                                       ng-model="vendor.affiliate_url"
                                       name="{{vendor.countrycode}}-affiliate_url">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Description <span class="symbol required"></span></label>
                                <input type="text" class="form-control required" placeholder="Description"
                                       ng-model="vendor.description"
                                       name="{{vendor.countrycode}}-description">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Custom Keywords</label>
                                <input class="form-control" type="text" placeholder="Custom Keywords"
                                       ng-model="vendor.custom_keywords" name="{{vendor.countrycode}}-custom_keywords">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Best Store</label>

                                <div class="col-sm-12 form-horizontal" style="margin-bottom: 10px">
                                    <label class="radio radio-inline">
                                        <input type="radio" class="radiobox" ng-value="1"
                                               ng-model="vendor.best_store" name="{{vendor.countrycode}}-best_store">
                                        <span><?php echo __('Yes') ?></span>
                                    </label>
                                    <label class="radio radio-inline">
                                        <input type="radio" class="radiobox" ng-value="0"
                                               ng-model="vendor.best_store" name="{{vendor.countrycode}}-best_store">
                                        <span><?php echo __('No') ?></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Show in Home Page</label>

                                <div class="col-sm-12 form-horizontal" style="margin-bottom: 10px">
                                    <label class="radio radio-inline">
                                        <input type="radio" class="radiobox" ng-value="1"
                                               ng-model="vendor.show_in_homepage"
                                               name="{{vendor.countrycode}}-show_in_homepage">
                                        <span><?php echo __('Yes') ?></span>
                                    </label>
                                    <label class="radio radio-inline">
                                        <input type="radio" class="radiobox" ng-value="0"
                                               ng-model="vendor.show_in_homepage"
                                               name="{{vendor.countrycode}}-show_in_homepage">
                                        <span><?php echo __('No') ?></span>
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="form-group ">
                        <label class="control-label">Best Store</label>

                        <div class="col-sm-12 form-horizontal" style="margin-bottom: 10px">
                            <label class="radio radio-inline">
                                <input ng-model="currentStore.best_store" type="radio" class="radiobox" ng-value="1"
                                       ng-disabled="user.permissions.allow_add_store == 0">
                                <span><?php echo __('Yes') ?></span>
                            </label>
                            <label class="radio radio-inline">
                                <input ng-model="currentStore.best_store" type="radio" class="radiobox" ng-value="0"
                                       ng-disabled="user.permissions.allow_add_store == 0">
                                <span><?php echo __('No') ?></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="control-label">Show in Home Page</label>

                        <div class="col-sm-12 form-horizontal" style="margin-bottom: 10px">
                            <label class="radio radio-inline">
                                <input ng-model="currentStore.show_in_homepage" type="radio" class="radiobox"
                                       ng-value="1"
                                       ng-disabled="user.permissions.allow_add_store == 0">
                                <span><?php echo __('Yes') ?></span>
                            </label>
                            <label class="radio radio-inline">
                                <input ng-model="currentStore.show_in_homepage" type="radio" class="radiobox"
                                       ng-value="0"
                                       ng-disabled="user.permissions.allow_add_store == 0">
                                <span><?php echo __('No') ?></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tags</label>
                        <input class="tagsinput store-tags" ng-model="currentStore.tags" value="{{currentStore.tags}}"
                               type="hidden" style="width: 100%;display: block">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Publish Date</label>

                        <div class="input-group">
                            <input type="text"
                                   ng-model="currentStore.publish_date"
                                   class="form-control datetimepicker"
                                   id="store-publish-date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <section ng-show="!storeItem.addStoreMode">
                        <label class="control-label">Created </label>
                        <label class="control-label"><i>
                                {{currentStore.created | formatDateTimeLocal}}</i>
                        </label>
                    </section>
                    <section ng-show="!storeItem.addStoreMode">
                        <label class="control-label">Author </label>
                        <label class="control-label"><b>
                                {{currentStore.author.fullname}}</b>
                        </label>
                    </section>
                </form>
            </div>
            <div class="modal-footer">
                <a type="button"
                   class="btn btn-default"
                   data-dismiss="modal"
                   id="cancelStore">
                    Cancel
                </a>
                <button type="button"
                        class="btn btn-primary" ng-show="(currentStore.id)"
                        ng-click="saveStore()"
                        id="saveStore">
                    Add
                </button>
                <a class="btn btn-success"
                   ng-click="saveStore('published')"
                   ng-show="(!currentStore.id || currentStore.status == 'pending')
                                && user.permissions.allow_add_store == 1">
                    Publish
                </a>
                <a class="btn btn-info"
                   ng-click="saveStore('pending')"
                   ng-show="(!currentStore.id || arrayContains(currentStore.status, ['trash','published']))
                                && user.permissions.allow_add_store == 1">
                    Pending
                </a>
                <a class="btn btn-warning"
                   ng-click="saveStore('trash')"
                   ng-show="(!storeItem.addStoreMode) && arrayContains(currentStore.status, ['pending','published'])
                                && user.permissions.allow_add_store == 1">
                    Trash
                </a>
                <a class="btn btn-danger"
                   ng-click="deleteStore(currentStore.id)"
                   ng-show="(!storeItem.addStoreMode) && currentStore.status == 'trash'
                                && user.permissions.allow_add_store == 1">
                    Delete
                </a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade in" id="modal-add-deal" tabindex="-1" role="dialog"
     aria-labelledby="modal-label-add-deal" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modal-label-add-deal">Add New Deal</h4>

                <div class="draft-warning" style="float: right;"
                     ng-show="storeItem.showDealDraft && storeItem.popupDealDraft && storeItem.addDealMode">
                    <a ng-click="loadDealDraft()"><?php echo __('Last draft for your Deal') ?> ({{
                        storeItem.popupDealDraft.created }})</a>
                </div>
            </div>
            <div class="modal-body">
                <form class="addDealForm" name='addDealForm' novalidate>
                    <div class="form-group">
                        <label class="control-label">Title <span class="symbol required"></span></label>
                        <input type="text" placeholder="Title" name="deal_title" class="form-control required"
                               ng-model='newDeal.title'>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Description <span class="symbol required"></span></label>
                        <textarea type="text" placeholder="Description" class="form-control required"
                                  ng-model='newDeal.description'></textarea>
                    </div>
                    <fieldset class="scheduler-border" ng-repeat="vendor in newDeal.vendors">
                        <legend class="scheduler-border">{{vendor.countrycode}}</legend>
                        <input type="hidden" name="{{vendor.countrycode}}-countrycode"
                               ng-model='vendor.countrycode'>

                        <div class="form-group">
                            <label class="control-label">Title <span class="symbol required"></span></label>
                            <input type="text" placeholder="Title" name="{{vendor.countrycode}}-deal_title"
                                   class="form-control required"
                                   ng-model='vendor.title'>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Description <span class="symbol required"></span></label>
                        <textarea type="text" placeholder="Description" class="form-control required"
                                  ng-model='vendor.description'></textarea>
                        </div>
                    </fieldset>
                    <div class="form-group">
                        <label class="control-label">Currency</label>
                        <select name="select_dc_currency" class="form-control required" ng-model='newDeal.currency'>
                            <option>$</option>
                            <option>£</option>
                            <option>¥</option>
                            <option>€</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Origin price <span class="symbol required"></span></label>
                        <input type="text" placeholder="Origin price"
                               name="originPriceDeal"
                               ng-model='newDeal.origin_price' ng-change="autoCalculatePriceDeal(1)"
                               id="originPriceDeal" class="form-control auto-numeric required">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Real price <span class="symbol required"></span></label>
                        <input type="text" placeholder="Discount price"
                               name="realPriceDeal"
                               ng-model='newDeal.discount_price' id="realPriceDeal"
                               ng-change="autoCalculatePriceDeal(2)" class="form-control auto-numeric required">

                    </div>
                    <div class="form-group">
                        <label class="control-label">Discount percent <span class="symbol required"></span></label>
                        <input type="text" placeholder="Discount percent"
                               name="discountPercentDeal"
                               ng-model='newDeal.discount_percent' id="discountPercentDeal"
                               ng-change="autoCalculatePriceDeal(3)" class="form-control auto-numeric required"
                               data-v-max="100">

                    </div>
                    <div class="form-group">
                        <label class="control-label">Store</label>
                        <label class="btn bg-color-blue txt-color-white">{{newDeal.storeName}}</label>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Category <span class="symbol required"></span></label>
                        <select class="required select2" multiple="multiple" name="newDealCategory" id="newDealCategory"
                                ng-model="newDeal.categories_id">
                            <option ng-repeat="cate in storeItem.listcategories"
                                    value='{{cate.category.id}}'
                                    ng-selected='arrayContains(cate.category.id,newDeal.categories_id)'>
                                {{cate.category.name}}
                            </option>
                        </select>
                    </div>
                    <div class="form-group deal-image">
                        <label class="control-label" style="display: block">Deal Image <span
                                class="symbol required"></span></label>

                        <div
                            ng-class="newDeal.deal_image ? 'fileinput fileinput-exists' : 'fileinput fileinput-new'"
                            data-provides="fileinput">
                            <div class="fileinput-preview thumbnail" style="width: 150px; height: 150px;">
                                <img ng-if="newDeal.deal_image" ng-src="{{newDeal.deal_image}}"/>
                            </div>
                            <div>
                                <span class="btn btn-default btn-file"
                                      image-upload="newDeal.deal_image" image-loading
                                      max-image-size="307200" nopreview noremove jasny-fileinput no-set-height>
                                        </span>
                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Product url <span class="symbol required"></span></label>
                        <input type="text" class="form-control required" placeholder="http://example.com"
                               name="produc_url"
                               ng-model='newDeal.produc_url'>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Exclusive </label>

                        <div class="col-sm-12 form-horizontal" style="margin-bottom: 10px">
                            <label class="radio radio-inline">
                                <input ng-model="newDeal.exclusive" class="radiobox" type="radio" ng-value="1">
                                <span>Yes</span>
                            </label>
                            <label class="radio radio-inline">
                                <input ng-model="newDeal.exclusive" class="radiobox" type="radio" ng-value="0">
                                <span>No</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Hot deal</label>

                        <div class="col-sm-12 form-horizontal" style="margin-bottom: 10px">
                            <label class="radio radio-inline">
                                <input ng-model="newDeal.hot_deal" class="radiobox" type="radio" ng-value="1">
                                <span>Yes</span>
                            </label>
                            <label class="radio radio-inline">
                                <input ng-model="newDeal.hot_deal" class="radiobox" type="radio" ng-value="0">
                                <span>No</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Free shipping</label>

                        <div class="col-sm-12 form-horizontal" style="margin-bottom: 10px">
                            <label class="radio radio-inline">
                                <input ng-model="newDeal.free_shipping" class="radiobox" type="radio" ng-value="1">
                                <span>Yes</span>
                            </label>
                            <label class="radio radio-inline">
                                <input ng-model="newDeal.free_shipping" class="radiobox" type="radio" ng-value="0">
                                <span>No</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Start Date</label>

                        <div class="input-group">
                            <input type="text" ng-model="newDeal.start_date"
                                   class="form-control start-date" id="deal-start-date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Expire Date <span class="symbol required"></span></label>

                        <div class="input-group">
                            <input type="text"
                                   ng-model="newDeal.expire_date"
                                   class="form-control end-date required"
                                   id="deal-expire-date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Deal Tag</label>
                        <input class="tagsinput deal-tags" ng-model="newDeal.deal_tag"
                               type="hidden" style="width: 100%;display: block">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Publish Date <span class="symbol required"></span></label>

                        <div class="input-group">
                            <input type="text"
                                   ng-model="newDeal.publish_date"
                                   class="form-control datetimepicker required"
                                   id="deal-publish-date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <section ng-show="!storeItem.addDealMode">
                        <label class="control-label" for="">Created</label>
                        <label class="control-label"><i>
                                {{newDeal.created | formatDateTimeLocal}}</i>
                        </label>
                    </section>
                    <section ng-show="!storeItem.addDealMode">
                        <label class="control-label" for="">Author</label>
                        <label class="control-label"><b>
                                {{newDeal.author.fullname}}</b>
                        </label>
                    </section>
                </form>
            </div>
            <div class="modal-footer">
                <a type="button"
                   class="btn btn-default"
                   data-dismiss="modal"
                   id="cancelDeal">
                    Cancel
                </a>
                <button type="button" class="btn btn-primary"
                        ng-click="saveDeal()" ng-show="(!storeItem.addDealMode)"
                        id="saveDeal">
                    Add
                </button>
                <a ng-show="(!newDeal.id || newDeal.status == 'pending')
                                        && user.permissions.allow_add_active_coupon == 1"
                   ng-click="saveDeal('published')" class="btn btn-success">Publish</a>
                <a ng-show="(!newDeal.id || arrayContains(newDeal.status,['trash','published']))
                                    && user.permissions.allow_add_active_coupon == 1"
                   ng-click="saveDeal('pending')" class="btn btn-info">Pending</a>
                <a ng-show="(!storeItem.addDealMode) && arrayContains(newDeal.status, ['pending','published'])
                                        && user.permissions.allow_add_active_coupon == 1"
                   ng-click="saveDeal('trash')" class="btn btn-warning">Trash</a>
                <a ng-show="(!storeItem.addDealMode) && newDeal.status == 'trash'
                                        && user.permissions.allow_add_active_coupon == 1"
                   ng-click='deleteDeal(currentDeal.id)' class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade in" id="modal-add-coupon" tabindex="-1" role="dialog"
     aria-labelledby="modal-label-add-coupon" aria-hidden="false">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modal-label-add-coupon">Add New Coupon</h4>

                <div class="draft-warning" style="float: right;"
                     ng-show="storeItem.showCouponDraft && storeItem.popupCouponDraft && storeItem.addCouponMode">
                    <a ng-click="loadCouponDraft()"><?php echo __('Last draft for your Coupon') ?> ({{
                        storeItem.popupCouponDraft.created }})</a>
                </div>
            </div>
            <div class="modal-body">
                <form class="addCouponForm" name='addCouponForm' novalidate>
                    <div class="form-group">
                        <label class="control-label">Title <span class="symbol required"></span></label>
                        <input class="form-control required" type="text" placeholder="Title" name="title"
                               ng-model='newCoupon.title_store'>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Description <span class="symbol required"></span></label>
                        <textarea placeholder="Description"
                               name="description_title"
                               ng-model='newCoupon.description_store' class="form-control required"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 coupon-image">
                                <label class="control-label" style="display: block">Coupon Image <span
                                        class="symbol required"></span></label>

                                <div
                                    ng-class="newCoupon.coupon_image ? 'fileinput fileinput-exists' : 'fileinput fileinput-new'"
                                    data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" style="width: 150px; height: 150px;">
                                        <img ng-if="newCoupon.coupon_image" ng-src="{{newCoupon.coupon_image}}"/>
                                    </div>
                                    <div>
                                         <span class="btn btn-default btn-file"
                                               image-upload="newCoupon.coupon_image" image-loading
                                               max-image-size="307200" nopreview noremove jasny-fileinput no-set-height>
                                        </span>
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 coupon-socical-image">
                                <label class="control-label" style="display: block">Social Image <span
                                        class="symbol required"></span></label>

                                <div
                                    ng-class="newCoupon.social_image ? 'fileinput fileinput-exists' : 'fileinput fileinput-new'"
                                    data-provides="fileinput">
                                    <div class="fileinput-preview  thumbnail" style="width: 150px; height: 150px;">
                                        <img ng-if="newCoupon.social_image" ng-src="{{newCoupon.social_image}}"/>
                                    </div>
                                    <div>
                                        <span class="btn btn-default btn-file"
                                              image-upload="newCoupon.social_image" image-loading
                                              max-image-size="307200" nopreview noremove jasny-fileinput no-set-height>
                                        </span>
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Product Link <span class="symbol required"></span></label>
                        <input type="text" placeholder="http://example.com"
                               ng-model='newCoupon.product_link'
                               name="productLink" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Exclusive </label>

                        <div class="col-sm-12 form-horizontal" style="margin-bottom: 10px">
                            <label class="radio radio-inline">
                                <input ng-model="newCoupon.exclusive"
                                       type="radio" ng-value="1" class="radiobox">
                                <span>Yes</span>
                            </label>
                            <label class="radio radio-inline">
                                <input ng-model="newCoupon.exclusive"
                                       type="radio" ng-value="0" class="radiobox">
                                <span>No</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group" ng-show="newCoupon.exclusive == 0">
                        <label class="control-label">Verified</label>

                        <div class="col-sm-12 form-horizontal" style="margin-bottom: 10px">
                            <label class="radio radio-inline">
                                <input ng-model="newCoupon.verified"
                                       type="radio" ng-value="1" class="radiobox">
                                <span>Yes</span>
                            </label>
                            <label class="radio radio-inline">
                                <input ng-model="newCoupon.verified"
                                       type="radio" ng-value="0" class="radiobox">
                                <span>No</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Sticky </label>

                        <div class="col-sm-12 form-horizontal" style="margin-bottom: 10px">
                            <label class="radio radio-inline">
                                <input ng-model="newCoupon.sticky"
                                       type="radio" ng-value="'top'" class="radiobox">
                                <span>Top</span>
                            </label>
                            <label class="radio radio-inline">
                                <input ng-model="newCoupon.sticky"
                                       type="radio" ng-value="'hot'" class="radiobox">
                                <span>Hot</span>
                            </label>
                            <label class="radio radio-inline">
                                <input ng-model="newCoupon.sticky"
                                       type="radio" ng-value="'none'" class="radiobox">
                                <span>None</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Coupon Type</label>
                        <select name="coupon_type" id="coupon_type" ng-model='newCoupon.coupon_type' class="form-control">
                            <option>Coupon Code</option>
                            <option>Great Offer</option>
                            <option>Free Shipping</option>
                        </select>
                    </div>
                    <div class="form-group"
                         ng-show="arrayContains(newCoupon.coupon_type, ['Coupon Code','Free Shipping']);">
                        <label class="control-label">Coupon code <span class="symbol required"></span></label>
                        <input type="text" placeholder="Coupon code" ng-model='newCoupon.coupon_code'
                               class="form-control required">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Discount <span class="symbol required"></span></label>

                        <div class="clearfix">
                            <div class="input-group">
                                <select name="select_dc_currency" id="currency-coupon" ng-model='newCoupon.currency' class="form-control">
                                    <option>$</option>
                                    <option>£</option>
                                    <option>¥</option>
                                    <option>€</option>
                                    <option>%</option>
                                </select>
                                <input name='discountCoupon' type="text" placeholder="Discount"
                                       ng-model='newCoupon.discount'
                                       class="form-control auto-numeric required coupon-discount">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Store</label>
                        <label class="btn bg-color-blue txt-color-white">{{newCoupon.storeName}}</label>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Category</label>
                        <select class="select2" multiple="multiple" name="newCouponCategory" id="newCouponCategory"
                                ng-model="newCoupon.categories_id">
                            <option ng-repeat="cate in storeItem.listcategories"
                                    value='{{cate.category.id}}'>
                                {{cate.category.name}}
                            </option>
                        </select>
                    </div>
                    <div class="form-group more-vendors">
                        <fieldset class="scheduler-border" ng-repeat="vendor in newCoupon.vendors">
                            <legend class="scheduler-border">{{vendor.countrycode}}</legend>
                            <input type="hidden" name="{{vendor.countrycode}}-countrycode"
                                   ng-model='vendor.countrycode'>

                            <div class="form-group">
                                <label class="control-label">Title <span class="symbol required"></span></label>
                                <input type="text" class="form-control required" placeholder="Title"
                                       ng-model="vendor.title"
                                       name="{{vendor.countrycode}}-title">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Description <span class="symbol required"></span></label>
                                <textarea type="text" class="form-control required" placeholder="Description"
                                          ng-model="vendor.description"
                                          name="{{vendor.countrycode}}-description"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Sticky </label>

                                <div class="col-sm-12 form-horizontal" style="margin-bottom: 10px">
                                    <label class="radio radio-inline">
                                        <input ng-model="vendor.sticky"
                                               type="radio" ng-value="'top'" class="radiobox">
                                        <span>Top</span>
                                    </label>
                                    <label class="radio radio-inline">
                                        <input ng-model="vendor.sticky"
                                               type="radio" ng-value="'hot'" class="radiobox">
                                        <span>Hot</span>
                                    </label>
                                    <label class="radio radio-inline">
                                        <input ng-model="vendor.sticky"
                                               type="radio" ng-value="'none'" class="radiobox">
                                        <span>None</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Select Event</label>
                                <select ng-model="vendor.event_id" class="form-control">
                                    <option value="">None</option>
                                    <option ng-repeat="event in events" ng-value="event.event.id"
                                            ng-selected="vendor.event_id == event.event.id">
                                        {{event.event.name}}
                                    </option>
                                </select>
                            </div>
                        </fieldset>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Select Event</label>
                        <select ng-model="newCoupon.event_id" class="form-control">
                            <option value="">None</option>
                            <option ng-repeat="event in events" ng-value="event.event.id"
                                    ng-selected="newCoupon.event_id == event.event.id">
                                {{event.event.name}}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Coupon Tags</label>
                        <input class="tagsinput coupon-tags" ng-model="newCoupon.tags" value="{{newCoupon.tags}}"
                               type="hidden" style="width: 100%;display: block">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Expire Date <span class="symbol required"></span></label>

                        <div class="input-group">
                            <input type="text" ng-model="newCoupon.expire_date"
                                   class="form-control datetimepicker required" id="coupon-expire-date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Publish Date <span class="symbol required"></span></label>

                        <div class="input-group">
                            <input type="text"
                                   ng-model="newCoupon.publish_date"
                                   class="form-control datetimepicker required" id="coupon-publish-date"
                                >
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>

                    <div class="form-group" ng-show="!storeItem.addCouponMode">
                        <label class="control-label">Created</label>
                        <label class="control-label"><i>
                                {{newCoupon.created | formatDateTimeLocal}}</i>
                        </label>
                    </div>
                    <div class="form-group" ng-show="!storeItem.addCouponMode">
                        <label class="control-label">Author</label>
                        <label class="control-label"><b>
                                {{newCoupon.author.fullname}}</b>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a
                    class="btn btn-default"
                    data-dismiss="modal"
                    id="cancelCoupon">
                    Cancel
                </a>
                <button type="button" class="btn btn-primary"
                        ng-click="saveCoupon()" ng-show="(newCoupon.id)"
                        id="saveCoupon">
                    Add
                </button>
                <a class="btn btn-success"
                   ng-click="saveCoupon('published')"
                   ng-show="(!newCoupon.id || newCoupon.status == 'pending')
                                && user.permissions.allow_add_active_coupon == 1">
                    Publish
                </a>
                <a class="btn btn-info"
                   ng-click="saveCoupon('pending')"
                   ng-show="(!newCoupon.id || arrayContains(newCoupon.status,['trash','published']))
                                && user.permissions.allow_add_active_coupon == 1">
                    Pending
                </a>
                <a type="button" class="btn btn-warning"
                   ng-click="saveCoupon('trash')"
                   ng-show="(!storeItem.addCouponMode) && arrayContains(newCoupon.status, ['pending','published'])
                                && user.permissions.allow_add_active_coupon == 1">
                    Trash
                </a>
                <a class="btn btn-danger"
                   ng-click="deleteCoupon(newCoupon.id)"
                   ng-show="(!storeItem.addCouponMode) && newCoupon.status == 'trash'
                                && user.permissions.allow_add_active_coupon == 1">
                    Delete
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('#store-table').on('click', '.store-lazy-load', function () {
            $("img.store-lazy-load-image").lazy({
                chainable: false,
                bind: "event"
            });
        });
        $('#coupon-table').on('click', '.coupon-lazy-load', function () {
            $("img.coupon-lazy-load-image").lazy({
                chainable: false,
                bind: "event"
            });
        });
        $('#deal-table').on('click', '.deal-lazy-load', function () {
            $("img.deal-lazy-load-image").lazy({
                chainable: false,
                bind: "event"
            });
        });
        $('#listCountries').select2({
            multiple: true,
            placeholder: "Select Country",
            maximumSelectionSize: 0,
            closeOnSelect: false,
            minimumInputLength: 2,
            ajax: {
                url: "<?php echo $this->Html->url('/')?>products/getCountries",
                dataType: 'json',
//                quietMillis: 250,
                data: function (term, page) { // page is the one-based page number tracked by Select2
                    return {
                        q: term, //search term
                        page: page // page number
                    };
                },
                results: function (data, page) {
                    var more = (page * 30) < data.total_count; // whether or not there are more results available

                    // notice we return the value of more so Select2 knows if more results can be loaded
                    return {results: data.items, more: more};
                }
            },
            initSelection: function (element, callback) {
                // the input tag has a value attribute preloaded that points to a preselected repository's id
                // this function resolves that id attribute to an object that select2 can render
                // using its formatResult renderer - that way the repository name is shown preselected
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?php echo $this->Html->url('/')?>products/getCountriesSelected/", {
                        dataType: "json",
                        data: {id: id}
                    }).done(function (data) {
                        callback(data.items);
                    });
                }
            },
            formatResult: repoFormatResult,
            formatSelection: repoFormatSelection
        }).on('change', function (e) {
            angular.element($('#content')).scope().updateListVendor(e.added, e.removed);
        });
        function repoFormatResult(repo) {
            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__title'>" + repo.name + "</div>";
            markup += "</div>";
            return markup;
        }

        function repoFormatSelection(repo) {
            return repo.name;
        }
    });
</script>
