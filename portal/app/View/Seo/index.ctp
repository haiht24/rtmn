<?php $this->Ng->ngController('SeoCtrl') ?>
<?php
    $this->Ng->ngInit(
        [
            'Seos' => isset($Seos) ? $Seos : []
        ]
    );
    //echo '<pre>';var_dump($Seos);echo '</pre>';
?>
<style type="text/css">
input{width: 100%;}
</style>
<div class="row">
    <div class="col-sm-12">
        <ul class="nav nav-tabs bordered" id="myTab1">
            <li class="active"> <a data-toggle="tab" href="#s1">General</a> </li>
            <li> <a data-toggle="tab" href="#s2">Home page</a> </li>
            <li> <a data-toggle="tab" href="#s3">Store</a> </li>
            <li> <a data-toggle="tab" href="#s4">Category</a> </li>
            <!-- <li> <a data-toggle="tab" href="#s5">Event</a> </li> -->
            <li class="pull-right hidden-mobile">
                <a href="javascript:void(0);"> <span class="note">Seo Configuration</span> </a>
            </li>
        </ul>
        <div class="tab-content bg-color-white padding-10" id="myTabContent1">
            <div id="s1" class="tab-pane fade in active">
                <h1>General Settings</h1>
                <table class="table" style="width: 60%;">
                    <tr>
                        <td>Site name</td>
                        <td><input type="text" ng-blur = "saveSeoSiteName()" ng-model = "seo_siteName" /></td>
                    </tr>
                    <tr>
                        <td>Site description</td>
                        <td><input type="text" ng-blur = "saveSeoSiteDesc()" ng-model = "seo_siteDescription" /></td>
                    </tr>
                    <tr>
                        <td><button class="btn btn-primary" ng-click = "saveGeneral()" >Save</button></td>
                        <td></td>
                    </tr>
                </table>
                <div ng-show = "generalSucc == 1" class="alert alert-success fade in">
                    <i class="fa-fw fa fa-check"></i>
                    <strong>Saved</strong>
                </div>
            </div>
            <div id="s2" class="tab-pane fade">
                <h1>Home page</h1>
                <table class="table" style="width: 60%;">
                    <tr>
                        <td>Title template</td>
                        <td><input type="text" ng-model = "seo_homeTitle" /></td>
                    </tr>
                    <tr>
                        <td>Meta description template</td>
                        <td><input type="text" ng-model = "seo_homeMetaDesc" /></td>
                    </tr>
                    <tr>
                        <td>Meta keyword template</td>
                        <td><input type="text" ng-model = "seo_homeMetaKeyword" /></td>
                    </tr>
                    <tr>
                        <td>Disable Google no index, no follow</td>
                        <td>
                            <input type="checkbox" ng-model = "seo_disableHomeNoIndex" ng-checked = "seo_disableHomeNoIndex == 1" />
                        </td>
                    </tr>
                    <tr>
                        <td><button class="btn btn-primary" ng-click = "saveHome()">Save</button></td>
                        <td></td>
                    </tr>
                </table>
                <div ng-show = "homeSucc == 1" class="alert alert-success fade in">
                    <i class="fa-fw fa fa-check"></i>
                    <strong>Saved</strong>
                </div>
            </div>
            <div id="s3" class="tab-pane fade ">
                <h1>Store</h1>
                <table class="table" style="width: 100%;">
                    <th></th>
                    <th>Settings</th>
                    <th>Default values</th>
                    <tr>
                        <td>Title template</td>
                        <td><input type="text" ng-model = "seo_storeTitle" /></td>
                        <td><input type="text" ng-model = "seo_defaultStoreTitle" /></td>
                    </tr>
                    <tr>
                        <td>Meta description template</td>
                        <td><input type="text" ng-model = "seo_storeDesc" /></td>
                        <td><input type="text" ng-model = "seo_defaultStoreMetaDescription" /></td>
                    </tr>
                    <tr>
                        <td>Meta keyword template</td>
                        <td><input type="text" ng-model = "seo_storeKeyword" /></td>
                        <td><input type="text" ng-model = "seo_defaultStoreMetaKeyword" /></td>
                    </tr>
                    <tr>
                        <td><b>Store Header</b></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>H1 Tag</td>
                        <td><input type="text" ng-model = "seo_storeH1" /></td>
                        <td><input type="text" ng-model = "seo_defaultH1Store" /></td>
                    </tr>
                    <tr>
                        <td>p Tag</td>
                        <td><input type="text" ng-model = "seo_storeP" /></td>
                        <td><input type="text" ng-model = "seo_defaultPStore" /></td>
                    </tr>
                    <tr>
                        <td>Disable Google no index, no follow</td>
                        <td>
                            <input type="checkbox" ng-model = "seo_disableStoreNoIndex" ng-checked = "seo_disableStoreNoIndex == 1" />
                        </td>
                    </tr>
                    <tr>
                        <td><button class="btn btn-primary" ng-click = "saveStoreConfig()">Save</button></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
                <div ng-show = "storeSucc == 1" class="alert alert-success fade in">
                    <i class="fa-fw fa fa-check"></i>
                    <strong>Saved</strong>
                </div>
            </div>
            <div id="s4" class="tab-pane fade ">
                <h1>Category</h1>
                <table class="table" style="width: 60%;">
                    <tr>
                        <td>Title template</td>
                        <td><input type="text" ng-model = "seo_CatTitle" /></td>
                    </tr>
                    <tr>
                        <td>Meta description template</td>
                        <td><input type="text" ng-model = "seo_CatDesc" /></td>
                    </tr>
                    <tr>
                        <td>Meta keyword template</td>
                        <td><input type="text" ng-model = "seo_CatKeyword" /></td>
                    </tr>
                    <tr>
                        <td>Disable Google no index, no follow</td>
                        <td>
                            <input type="checkbox" ng-model = "seo_DisableCatNoIndex" ng-checked = "seo_DisableCatNoIndex == 1" />
                        </td>
                    </tr>
                    <tr>
                        <td><button class="btn btn-primary" ng-click = "saveCate()">Save</button></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
                <div ng-show = "cateSucc == 1" class="alert alert-success fade in">
                    <i class="fa-fw fa fa-check"></i>
                    <strong>Saved</strong> <i>
                </div>
            </div>
            <!--
            <div id="s5" class="tab-pane fade ">
                <h1>Event</h1>
                <table class="table" style="width: 60%;">
                    <tr>
                        <td>Title template</td>
                        <td><input type="text" ng-blur = "saveEventTitle()" ng-model = "eventTitle" /></td>
                    </tr>
                    <tr>
                        <td>Meta description template</td>
                        <td><input type="text" ng-blur = "saveEventMetaDesc()" ng-model = "eventMetaDescription" /></td>
                    </tr>
                    <tr>
                        <td>Meta keyword template</td>
                        <td><input type="text" ng-blur = "saveEventMetaKeyword()" ng-model = "eventMetaKeyword" /></td>
                    </tr>
                    <tr>
                        <td>Disable Google no index, no follow</td>
                        <td>
                            <input type="checkbox" ng-model = "disableEventNoIndex" ng-click = "saveEventNoIndex()" ng-checked = "disableEventNoIndex == 1" />
                        </td>
                    </tr>
                </table>
                <div ng-show = "success == 1" class="alert alert-success fade in">
                    <i class="fa-fw fa fa-check"></i>
                    <strong>Success</strong> <i>{{fieldName}}</i> has been updated.
                </div>
            </div>
            -->
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="tab-content bg-color-white padding-10">
            <h5>Avaiable keywords</h5>
            <ul>
                <li ng-repeat = "a in allowKeywords track by $index" ng-bind = "a"></li>
            </ul>
        </div>
    </div>
</div>.
