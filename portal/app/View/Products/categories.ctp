<?php $this->Html->script('/lib/fromjs/from', ['inline' => false]); ?>
<?php $this->Ng->ngController('ProductCateCtrl') ?>
<?php $this->Ng->ngInit(
    [
        'categories' => isset($categories) ? $categories : [],
        'listCategories' => isset($listCategories) ? $listCategories : [],
        'user' => isset($user) ? $user : [],
        'users' => isset($users) ? $users : []
    ])
?>
<!-- Breadscrums -->
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-"></i>
            MostCoupon <span>Categories</span>
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
                       ng-change="search()"
                       placeholder="Filter by name, alias, status" id="search-user">

                <div class="input-group-btn">
                    <button type="submit" class="btn btn-default">
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

        <!-- BUTTON LIST -->
        <div class="button-container col-xs-12">
            <div class="input-group">
                <a class="btn btn-primary" ng-click="showAll();">Clear</a>&nbsp;&nbsp;&nbsp;
                <a class="btn btn-primary btn-add-cate"
                   ng-if="user.permissions.allow_add_category == 1"
                   data-toggle="modal"
                   data-target="#modal-add-cate"
                   ng-click="initCategory();">
                    <i class="fa fa-plus"></i>
                    Add Category
                </a>
            </div>
            <div>
                <label>Total: {{listCategories.length | number}} Categories</label>
            </div>


            <div class="modal fade in" id="modal-add-cate" tabindex="-1" role="dialog" aria-labelledby="modal-label-add-cate" aria-hidden="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="modal-label-add-cate">Add New Category</h4>
                            <div class="draft-warning" style="float: right;" ng-show="showDraft && popupDraft && addMode" >
                                <a ng-click="loadDraft()" ><?php echo __('Last draft for your Category') ?> ({{ popupDraft.created }})</a>
                            </div>
                        </div>
                        <div class="modal-body">
                            <form class="smart-form" name='addCateForm' novalidate>
                                <section>
                                    <label class="label">Name</label>
                                    <label class="input">
                                        <i class="icon-append fa fa-tag"></i>
                                        <input type="text"
                                               placeholder="Category Name"
                                               ng-model="newCategory.name"
                                               required
                                               name="name"
                                               ng-disabled="user.permissions.allow_add_category == 0
                                               && user.permissions.allow_edit_category == 1"/>
                                    </label>
                                    <p class='error' ng-show='showError && addCateForm.name.$invalid'>Please enter category name</p>
                                    <div ng-show="newCategory.name">
                                        <button ng-click="checkExists()">Check Exists Name</button>
                                        <p ng-show="checkExist">Name is exists.</p>
                                        <p ng-show="checkNotExist">Name is not exists.</p>
                                    </div>
                                </section>
                                <section>
                                    <label class="label">Alias</label>
                                    <label class="input">
                                        <i class="icon-append fa fa-info"></i>
                                        <input type="text" placeholder="Category Alias"
                                               ng-model="newCategory.alias"
                                               required name="alias"
                                               ng-disabled="user.permissions.allow_add_category == 0
                                               && user.permissions.allow_edit_category == 1">
                                    </label>
                                    <p class='error' ng-show='showError && addCateForm.alias.$invalid'>Please enter category alias</p>
                                </section>
                                <section>
                                    <label class="label">Parent</label>
                                    <label class="select">
                                        <select ng-model="newCategory.parent_id" ng-disabled="newCategory.id">
                                            <option value=""></option>
                                            <option ng-repeat="item in categories" value="{{item.category.id}}"
                                                    ng-selected="newCategory.parent_id == item.category.id">
                                                {{item.category.name}}
                                            </option>
                                        </select>
                                        <i></i>
                                    </label>
                                </section>
                                <section>
                                    <label class="label" for="">Description</label>
                                    <label class="textarea">
                                        <i class="icon-append fa fa-comment"></i>
                                        <textarea rows="3" name="description" ng-model="newCategory.description"  placeholder="Category Description"></textarea>
                                    </label>
                                </section>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default"
                                    data-dismiss="modal"
                                    id="cancelCate">
                                Cancel
                            </button>
                            <button type="button" class="btn btn-primary"
                                    ng-click="saveCategory()"
                                    id="saveCate">
                                Add
                            </button>
                            <button type="button" class="btn btn-primary"
                                    ng-click="saveCategory('published')"
                                    id="publishCate"
                                    ng-show="newCategory.status == 'pending'
                                                && user.permissions.allow_add_category == 1">
                                Publish
                            </button>
                            <button type="button" class="btn btn-primary"
                                    ng-click="saveCategory('pending')"
                                    ng-show="arrayContains(newCategory.status, ['trash','published'])
                                                && user.permissions.allow_add_category == 1">
                                Pending Review
                            </button>
                            <button type="button" class="btn btn-primary"
                                    ng-click="saveCategory('trash')"
                                    ng-show="arrayContains(newCategory.status, ['pending','published'])
                                                && user.permissions.allow_add_category == 1">
                                Move to Trash
                            </button>
                            <button type="button" class="btn btn-primary"
                                    ng-click="deleteCategory(newCategory.id)"
                                    ng-show="newCategory.id && newCategory.status == 'trash'
                                    && user.permissions.allow_add_category == 1">
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
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-cate-list"
                 data-widget-deletebutton="false"
                 data-widget-colorbutton="false"
                 data-widget-editbutton="false">

                <!-- widget options:
                usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

                data-widget-colorbutton="false"
                data-widget-editbutton="false"
                data-widget-togglebutton="false"
                data-widget-deletebutton="false"
                data-widget-fullscreenbutton="false"
                data-widget-custombutton="false"
                data-widget-collapsed="true"
                data-widget-sortable="false"

                -->
                <header>
                    <span class="widget-icon"> <i class="fa fa-tag"></i> </span>

                    <h2>Categories List</h2>

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

                            <table id="resultTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th ng-click="sortBy('name');"
                                        ng-class="{'asc':(filterOptions.sortBy == true
                                        && filterOptions.sortField == 'name'),
                                        'desc': (filterOptions.sortBy == false
                                        && filterOptions.sortField == 'name')}">Name</th>
                                    <th ng-click="sortBy('alias');"
                                        ng-class="{'asc':(filterOptions.sortBy == true
                                        && filterOptions.sortField == 'alias'),
                                        'desc': (filterOptions.sortBy == false
                                        && filterOptions.sortField == 'alias')}">Alias</th>
                                    <th ng-click="sortBy('father');"
                                        ng-class="{'asc':(filterOptions.sortBy == true
                                        && filterOptions.sortField == 'father'),
                                        'desc': (filterOptions.sortBy == false
                                        && filterOptions.sortField == 'father')}">Parent</th>
                                    <th ng-click="sortBy('description');"
                                        ng-class="{'asc':(filterOptions.sortBy == true
                                        && filterOptions.sortField == 'description'),
                                        'desc': (filterOptions.sortBy == false
                                        && filterOptions.sortField == 'description')}">Description</th>
                                    <th ng-click="sortBy('author');"
                                        ng-class="{'asc':(filterOptions.sortBy == true
                                        && filterOptions.sortField == 'author'),
                                        'desc': (filterOptions.sortBy == false
                                        && filterOptions.sortField == 'author')}">Author</th>
                                    <th  ng-click="sortBy('created');"
                                        ng-class="{'asc':(filterOptions.sortBy == true
                                        && filterOptions.sortField == 'created'),
                                        'desc': (filterOptions.sortBy == false
                                        && filterOptions.sortField == 'created')}">Created date</th>
                                    <th ng-click="sortBy('status');"
                                        ng-class="{'asc':(filterOptions.sortBy == true
                                        && filterOptions.sortField == 'status'),
                                        'desc': (filterOptions.sortBy == false
                                        && filterOptions.sortField == 'status')}">Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Replace this by roleUserList loop -->
                                <tr ng-repeat="(indexItem, cate) in pages[currentPage]">
                                    <td>{{cate.category.name}}</td>
                                    <td>{{cate.category.alias}}</td>
                                    <td>{{cate.father.name}}</td>
                                    <td>{{cate.category.description}}</td>
                                    <td>{{cate.author.fullname}}</td>
                                    <td>{{cate.category.created | formatDateTimeLocal}}</td>
                                    <td><span class="label"
                                              ng-class="{'label-success': cate.category.status == 'published',
                                              'label-warning' : arrayContains(cate.category.status, ['pending','trash'])}">{{cate.category.status}}</span></td>
                                    <td>
                                        <button ng-click='editCategory(cate.category, indexItem)'
                                                ng-if="user.permissions.allow_edit_category == 1"
                                                data-target="#modal-add-cate"
                                                data-toggle="modal">Edit</button>
                                        <button ng-show="cate.category.status == 'pending'
                                                && user.permissions.allow_add_category == 1"
                                                ng-click="setStatusCategory(cate.category.id, indexItem, 'published')">Publish</button>
                                        <button ng-show="arrayContains(cate.category.status, ['pending','published'])
                                                && user.permissions.allow_add_category == 1"
                                                ng-click="setStatusCategory(cate.category.id, indexItem, 'trash')">Move To Trash</button>
                                        <button ng-show="cate.category.status == 'trash' && user.permissions.allow_add_category == 1"
                                                ng-click="deleteCategory(cate.category.id)">Delete</button>
                                        <button ng-show="arrayContains(cate.category.status, ['trash','published'])
                                                && user.permissions.allow_add_category == 1"
                                                ng-click="setStatusCategory(cate.category.id, indexItem, 'pending')">Pending Review</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>

                        <div class="text-center">
                            <hr>
                            <ul class="pagination no-margin">
                                <li class="arrow" ng-click="prevPage()" ng-show="pages.length > 1"
                                    ng-class="{'disabled': 0 == currentPage}">
                                    <a>Previous</a>
                                </li>
                                <li ng-repeat="n in range(pages.length)"
                                    ng-class="{active: n == currentPage}" ng-click="setPage(n)">
                                    <a ng-show="n >= 0 && n < 10">{{ n + 1 }}</a>
                                </li>
                                <li>
                                    <input type="number" ng-model="currentPageInc" ng-show="pages.length > 10" ng-change="changePage()"/>
                                </li>
                                <li ng-click="setPage(pages.length - 1)"
                                    ng-class="{active: (pages.length - 1) == currentPage}">
                                    <a ng-show="pages.length > 10">{{ pages.length }}</a>
                                </li>
                                <li class="arrow" ng-show="pages.length > 1"
                                    ng-click="nextPage()"
                                    ng-class="{'disabled': (pages.length - 1) == currentPage}">
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
            <!-- end widget -->

        </article>
        <!-- WIDGET END -->

    </div>
    <!-- end row -->


</section>
<!-- end Main widget grid -->