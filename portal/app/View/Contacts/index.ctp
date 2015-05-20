<?php $this->Ng->ngController('ContactsCtrl') ?>
<?php
    $this->Ng->ngInit(
        [
            'contacts' => isset($contacts) ? $contacts : [],
            'count' => isset($count) ? $count : []
        ]
    );
?>
<script>
    //contactPath = '<?php echo $this->Html->url(['controller' => 'contact']); ?>';
</script>
<!-- Configuration -->
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-"></i>
            MostCoupon <span>> Contact</span>
        </h1>
    </div>
</div>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-cate-list" data-widget-deletebutton="false" data-widget-colorbutton="false" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-tag"></i> </span>
                    <span class="header-h2">Total: {{count}} contacts</span>
                </header>
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body">
                        <div class='clearfix'></div>
                        <div class="table-responsive">
                            <table id="resultTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                        <th>Keywords</th>
                                        <th>Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody ng-repeat="c in contacts track by $index">
                                    <tr>
                                        <td ng-bind = "c.Contact.name"></td>
                                        <td ng-bind = "c.Contact.email"></td>
                                        <td ng-bind = "c.Contact.subject"></td>
                                        <td>
                                            <textarea ng-bind = "c.Contact.message"></textarea>
                                        </td>
                                        <td>
                                            <input ng-value = "c.Contact.keywords" />
                                        </td>
                                        <td ng-bind = "c.Contact.sendtime | date:'HH:mm:ss dd MMM yyyy'"></td>

                                        <td>
                                            <a ng-click="deleteContact(c.Contact.id)" class="btn btn-xs btn-link btn-trash"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <hr>
                            <ul class="pagination no-margin">
                                <li class="arrow" ng-click="prevPageCategory()" ng-show="categoryItem.pages.length > 1" ng-class="{'disabled': 0 == categoryItem.currentPage}">
                                    <a>Previous</a>
                                </li>
                                <li ng-repeat="n in range(categoryItem.pages.length)" ng-class="{active: n == categoryItem.currentPage}" ng-click="setPageCategory(n)">
                                    <a ng-show="n >= 0 && n < 10">{{ n + 1 }}</a>
                                </li>
                                <li>
                                    <input type="number" ng-model="categoryItem.currentPageInc" ng-show="categoryItem.pages.length > 10" ng-change="changePageCategory()" />
                                </li>
                                <li ng-click="setPageCategory(categoryItem.pages.length - 1)" ng-class="{active: (categoryItem.pages.length - 1) == categoryItem.currentPage}">
                                    <a ng-show="categoryItem.pages.length > 10">{{ categoryItem.pages.length }}</a>
                                </li>
                                <li class="arrow" ng-show="categoryItem.pages.length > 1" ng-click="nextPageCategory()" ng-class="{'disabled': (categoryItem.pages.length - 1) == categoryItem.currentPage}">
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