<?php $this->Html->script('/lib/fromjs/from', ['inline' => false]); ?>
<?php $this->Ng->ngController('ProductStoreCtrl') ?>
<?php $this->Ng->fdbDirective(['image_upload']); ?>
<?php $this->Ng->ngInit(
    [
        'categories' => isset($categories) ? $categories : [],
        'user' => isset($user) ? $user : [],
        'users' => isset($users) ? $users : []
    ])
?>
<style>
    .store-detail > td {
        padding: 0 !important;
    }

    .store-detail > td .store-info {
        padding: 8px 10px;
    }

    .store-info label {
        font-weight: bold;
    }
</style>

<!-- Breadscrums -->
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-"></i>
            MostCoupon <span>Stores</span>
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
                <input class="form-control input-lg"
                       type="text"
                       ng-model="filter"
                       placeholder="Filter by name" id="search-store">
                <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"
                            ng-click="search()">
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
        </div>

        <!-- BUTTON LIST & MODAL -->
        <div class="button-container col-xs-12">
            <div class="input-group">
                <a class="btn btn-primary" ng-click="showAll();">Clear</a>&nbsp;&nbsp;&nbsp;
                <a class="btn btn-primary btn-add-store"
                   ng-if="user.permissions.allow_add_store == 1"
                   data-toggle="modal"
                   data-target="#modal-add-store"
                   ng-click="initAddNewStore()"><i class="fa fa-plus"></i> Add Store</a>
            </div>

            <div>
                <label>Total: {{totalStores | number}} Stores</label>
            </div>

            <div class="modal fade in" id="modal-add-store" tabindex="-1" role="dialog" aria-labelledby="modal-label-add-store" aria-hidden="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="modal-label-add-store">Add New Store</h4>
                            <div class="draft-warning" style="float: right;" ng-show="showStoreDraft && popupStoreDraft && addStoreMode" >
                                <a ng-click="loadStoreDraft()" ><?php echo __('Last draft for your Store') ?> ({{ popupStoreDraft.created }})</a>
                            </div>
                        </div>
                        <div class="modal-body">
                            <form class="smart-form" name='addStoreForm' novalidate>
                                <section>
                                    <label class="label">Name</label>
                                    <label class="input">
                                        <i class="icon-append fa fa-tag"></i>
                                        <input type="text" placeholder="Store Name"
                                               name='name'
                                               ng-model="newStore.name"
                                               ng-change="generateMostCoupon()"
                                               required
                                               ng-disabled="user.permissions.allow_add_store == 0">
                                    </label>
                                    <p class='error' ng-show='showError && addStoreForm.name.$invalid'>Please enter name</p>
                                    <div ng-show="newStore.name">
                                        <button ng-click="checkNameExists()">Check Exists Name</button>
                                        <p ng-show="checkNameExist">Name is exists.</p>
                                        <p ng-show="checkNotNameExist">Name is not exists.</p>
                                    </div>
                                </section>
                                <section>
                                    <label class="label">Logo</label>
                                    <img ng-if="newStore.logo && user.permissions.allow_add_store == 0"
                                         ng-src="{{newStore.logo}}" />
                                    <div class="image-upload account-logo-upload"
                                         ng-if='user.permissions.allow_add_store == 1'
                                         image-upload="newStore.logo"
                                         fixed image-loading
                                         max-image-size="307200"
                                         title="<?php echo __('Click on the image to choose another one'); ?>">
                                    </div>
                                </section>
                                <section>
                                    <label class="label">Social Image</label>
                                    <img ng-if="newStore.social_image && user.permissions.allow_add_store == 0"
                                         ng-src="{{newStore.social_image}}" />
                                    <div class="image-upload account-logo-upload"
                                         ng-if='user.permissions.allow_add_store == 1'
                                         image-upload="newStore.social_image"
                                         fixed image-loading
                                         max-image-size="307200"
                                         title="<?php echo __('Click on the image to choose another one'); ?>">
                                    </div>
                                </section>
                                <section>
                                    <label class="label">Store URL</label>
                                    <label class="input">
                                        <i class="icon-append fa fa-link"></i>
                                        <input type="text" placeholder="Store URL"
                                               ng-model="newStore.store_url"
                                               ng-disabled="user.permissions.allow_add_store == 0">
                                    </label>
                                </section>
                                <section>
                                    <label class="label">MostCoupon URL</label>
                                    <label class="input">
                                        <i class="icon-append fa fa-link"></i>
                                        <span><?php echo $this->Html->url('/', true);?></span>
                                        <input type="text" placeholder="Store MostCoupon URL"
                                               ng-model="newStore.alias"
                                               name='alias'
                                               pattern-if="mostCouponUrlRegex"
                                               ng-change="editMostCoupon()"
                                               required
                                               ng-disabled="user.permissions.allow_add_store == 0">
                                    </label>
                                    <p class='error' ng-show='addStoreForm.alias.$invalid'>Please enter valid MostCoupon URL (only contain alphabet or number or '-' character)</p>

                                    <div ng-show="newStore.alias">
                                        <button ng-click="checkStoreURLExists()">Check Exists MostCoupon URL</button>
                                        <p ng-show="checkStoreURLExist">MostCoupon URL is exists.</p>
                                        <p ng-show="checkNotStoreURLExist">MostCoupon URL is not exists.</p>
                                    </div>
                                </section>
                                <section>
                                    <label class="label">Affiliate URL</label>
                                    <label class="input">
                                        <i class="icon-append fa fa-link"></i>
                                        <input type="text" placeholder="Store Affiliate URL"
                                               ng-model="newStore.affiliate_url"
                                               ng-disabled="user.permissions.allow_add_store == 0">
                                    </label>
                                </section>
                                <section>
                                    <label class="label">Location</label>
                                    <ul>
                                        <li  ng-repeat="(indexLo, location) in newStore.locations">
                                            {{location.store.name}}
                                            <button ng-click="removeLocation(location)"
                                                    ng-disabled="user.permissions.allow_add_store == 0">x</button>
                                        </li>
                                    </ul>
                                    <div ng-if="user.permissions.allow_add_store == 1">
                                        <p><button ng-click='bindSuggest()'>Get Suggest</button></p>
                                        <div ng-show='suggestList && suggestList.length > 0'>
                                            <label>Suggest:</label>
                                            <ul>
                                                <li ng-repeat="(indexSug, suggest) in suggestList | limitTo:limitSuggest"
                                                    ng-click="addLocation(suggest)">
                                                    {{suggest.store.name}}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </section>
                                <section>
                                    <label class="label" for="">Description</label>
                                    <label class="textarea">
                                        <i class="icon-append fa fa-comment"></i>
                                        <textarea rows="3" name="description"
                                                  placeholder="Store Description"
                                                  ng-model="newStore.description">
                                        </textarea>
                                    </label>
                                </section>
                                <section>
                                    <label class="label">Custom Keywords</label>
                                    <label class="input">
                                        <i class="icon-append fa fa-tag"></i>
                                        <input type="text" placeholder="Custom Keywords"
                                               ng-model="newStore.custom_keywords"
                                               ng-init="newStore.custom_keywords = 'Coupon Codes'"
                                               ng-disabled="user.permissions.allow_add_store == 0">
                                    </label>
                                </section>
                                <section>
                                    <label class="label">Category</label>
                                    <div ng-mouseleave="showListCategories = false">
                                        <a ng-click="showDropdow()">Choose category</a>
                                        <ul ng-show="showListCategories">
                                            <li ng-repeat="item in categories">
                                                <label>
                                                    <input type="checkbox"
                                                           ng-click="checkboxToArray(item.category.id)"
                                                           ng-checked="(newStore.categories_id && arrayContains(newStore.categories_id, item.category.id))"
                                                           ng-disabled="user.permissions.allow_add_store == 0"/>
                                                    <span>{{item.category.name}}</span>
                                                </label>
                                                <ul ng-if="item.category.sub_category">
                                                    <li ng-repeat="child in item.category.sub_category">
                                                        <label>
                                                            <input type="checkbox"
                                                                   ng-click="checkboxToArray(child.category.id)"
                                                                   ng-checked="(newStore.categories_id && arrayContains(newStore.categories_id, child.category.id))"
                                                                   ng-disabled="user.permissions.allow_add_store == 0"/>
                                                            <span>{{child.category.name}}</span>
                                                        </label>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                    <input type='hidden' ng-model='newStore.categories_id' valid-categories name='categories'/>
                                    <p class='error' ng-show='showError && addStoreForm.categories.$invalid'>Please choose Category</p>
                                </section>
                                <section>
                                    <label class="label">Best Store</label>
                                    <ul>
                                        <li>
                                            <input ng-model="newStore.best_store"
                                                   type="radio" ng-value="1"
                                                   ng-disabled="user.permissions.allow_add_store == 0"/>
                                            <span><?php echo __('Yes') ?></span>
                                        </li>
                                        <li>
                                            <input ng-model="newStore.best_store"
                                                   ng-init="initDefaultBestStore();"
                                                   ng-value="0"  type="radio"
                                                   ng-disabled="user.permissions.allow_add_store == 0"/>
                                            <span><?php echo __('No') ?></span>
                                        </li>
                                    </ul>
                                </section>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <p class='error' ng-show='showError && isExist'>Name already exists. Please enter name again.</p>
                            <p class='error' ng-show='showError && isExistURL'>MostCoupon URL is already exists. Please enter MostCoupon URL again.</p>
                            <button type="button"
                                    class="btn btn-default"
                                    data-dismiss="modal"
                                    id="cancelStore">
                                Cancel
                            </button>
                            <button type="button"
                                    class="btn btn-primary"
                                    ng-click="saveStore()"
                                    id="saveStore">
                                Add
                            </button>
                            <button type="button" class="btn btn-primary"
                                    ng-click="saveStore('published')"
                                    ng-show="newStore.status == 'pending'
                                    && user.permissions.allow_add_store == 1">
                                Publish
                            </button>
                            <button type="button" class="btn btn-primary"
                                    ng-click="saveStore('pending')"
                                    ng-show="arrayContains(['trash','published'],newStore.status)
                                    && user.permissions.allow_add_store == 1">
                                Pending Review
                            </button>
                            <button type="button" class="btn btn-primary"
                                    ng-click="saveStore('trash')"
                                    ng-show="arrayContains(['pending','published'],newStore.status)
                                    && user.permissions.allow_add_store == 1">
                                Move to Trash
                            </button>
                            <button type="button" class="btn btn-primary"
                                    ng-click="deleteStore(newStore.id)"
                                    ng-show="newStore.status == 'trash'
                                    && user.permissions.allow_add_store == 1">
                                Delete
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <div class="modal fade in" id="modal-add-deal" tabindex="-1" role="dialog" aria-labelledby="modal-label-add-deal" aria-hidden="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="modal-label-add-deal">Add New Deal</h4>
                            <div class="draft-warning" style="float: right;" ng-show="showDealDraft && popupDealDraft && addDealMode" >
                                <a ng-click="loadDealDraft()" ><?php echo __('Last draft for your Deal') ?> ({{ popupDealDraft.created }})</a>
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
                                                        ng-model="newDeal.category_id">
                                                    <option ng-repeat="cate in listcategories"
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
                                    <img ng-if="newDeal.deal_image"
                                         ng-src="{{newDeal.deal_image}}" />
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
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <div class="modal fade in" id="modal-add-coupon" tabindex="-1" role="dialog" aria-labelledby="modal-label-add-coupon" aria-hidden="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="modal-label-add-coupon">Add New Coupon</h4>
                            <div class="draft-warning" style="float: right;" ng-show="showCouponDraft && popupCouponDraft && addCouponMode" >
                                <a ng-click="loadCouponDraft()" ><?php echo __('Last draft for your Coupon') ?> ({{ popupCouponDraft.created }})</a>
                            </div>
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
                                                    type="radio" ng-value="1">
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
                                    <span>{{newCoupon.storeName}}</span>
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
                                Add
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
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-store-list"
                 data-widget-deletebutton="false"
                 data-widget-colorbutton="false"
                 data-widget-editbutton="false">

                <header>
                    <span class="widget-icon"> <i class="fa fa-tag"></i> </span>
                    <h2>Stores List</h2>
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

                            <table id="store-list" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th ng-click="sortBy('name');"
                                        ng-class="{'asc':(filterOptions.sortBy == true
                                        && filterOptions.sortField == 'name'),
                                        'desc': (filterOptions.sortBy == false
                                        && filterOptions.sortField == 'name')}">Name</th>
                                    <th ng-click="sortBy('custom_keywords');"
                                        ng-class="{'asc':(filterOptions.sortBy == true
                                        && filterOptions.sortField == 'custom_keywords'),
                                        'desc': (filterOptions.sortBy == false
                                        && filterOptions.sortField == 'custom_keywords')}">Keyword</th>
                                    <th ng-click="sortBy('status');"
                                        ng-class="{'asc':(filterOptions.sortBy == true
                                        && filterOptions.sortField == 'status'),
                                        'desc': (filterOptions.sortBy == false
                                        && filterOptions.sortField == 'status')}">Status</th>
                                    <th ng-click="sortBy('best_store');"
                                        ng-class="{'asc':(filterOptions.sortBy == true
                                        && filterOptions.sortField == 'best_store'),
                                        'desc': (filterOptions.sortBy == false
                                        && filterOptions.sortField == 'best_store')}">Best Store</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody ng-repeat="(index, store) in pages">
                                    <tr>
                                        <td>{{store.store.name}}</td>
                                        <td>{{store.store.custom_keywords}}</td>
                                        <td><span class="label"
                                                    ng-class="{'label-success': store.store.status == 'published',
                                                    'label-warning' : arrayContains(['pending','trash'], store.store.status)}">
                                                {{store.store.status}}
                                            </span>
                                        </td>
                                        </td>
                                        <td>{{getBestStore(store.store.best_store);}}</td>
                                        <td>
                                            <button ng-click="editStore(store.store)"
                                                    data-toggle="modal"
                                                    data-target="#modal-add-store"
                                                    ng-show='user.permissions.allow_edit_store == 1'>
                                                Edit
                                            </button>
                                            <button ng-show="store.store.status == 'pending'
                                                            && user.permissions.allow_add_store == 1"
                                                    ng-click="setStatusStore(store.store.id, 'published')">Publish</button>
                                            <button ng-show="arrayContains(['pending','published'],store.store.status)
                                                && user.permissions.allow_add_store == 1"
                                                ng-click="setStatusStore(store.store.id,'trash')">Move To Trash</button>
                                            <button ng-show="store.store.status == 'trash'
                                                            && user.permissions.allow_add_store == 1"
                                                            ng-click='deleteStore(store.store.id)'>Delete</button>
                                            <button ng-show="arrayContains(['trash','published'],store.store.status)
                                                && user.permissions.allow_add_store == 1"
                                                ng-click="setStatusStore(store.store.id, 'pending')">Pending Review</button>
                                            <button ng-click="addDeal(store)"
                                                    data-toggle="modal"
                                                    data-target="#modal-add-deal">Add Deal</button>
                                            <button ng-click="addCoupon(store)"
                                                    data-toggle="modal"
                                                    data-target="#modal-add-coupon">Add Coupon</button>
                                            <a href="#">
                                                <i class="fa fa-plus accordion-toggle"
                                                   data-target="#demo{{index}}"
                                                   data-toggle="collapse"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="store-detail">
                                        <td colspan="7">
                                            <div class="collapse" id="demo{{index}}">
                                                <div class="store-info row">
                                                    <div class="col-md-6">
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <td><label>Name</label></td>
                                                                <td>{{store.store.name}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Status</label></td>
                                                                <td>
                                                                    <span ng-repeat="cate in store.store.categories"
                                                                          ng-bind-html="cate.category.name | trustAsHtml">
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Best Store</label></td>
                                                                <td>{{getBestStore(store.store.best_store);}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Status</label></td>
                                                                <td>{{store.store.status}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Logo</label></td>
                                                                <td>
                                                                    <img ng-src="{{store.store.logo}}"/>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Social Image</label></td>
                                                                <td>
                                                                    <img ng-src="{{store.store.social_image}}"/>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <td><label>Store URL</label></td>
                                                                <td>{{store.store.store_url}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>MostCoupon URL</label></td>
                                                                <td><?php echo $this->Html->url('/', true);?>{{store.store.most_coupon_url}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Affiliate URL</label></td>
                                                                <td>{{store.store.affiliate_url}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Location</label></td>
                                                                <td>
                                                                    <span  ng-repeat="(indexLo, location) in store.store.locations">
                                                                        {{location.store.name}}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Description</label></td>
                                                                <td>{{store.store.description}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Custom Keywords</label></td>
                                                                <td>{{store.store.custom_keywords}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Author</label></td>
                                                                <td>{{store.author.fullname}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Created date</label></td>
                                                                <td>{{store.store.created | formatDateTimeLocal}}</td>
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
                                    <a ng-show="numberOfPages > 10">{{ numberOfPages }}</a>
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