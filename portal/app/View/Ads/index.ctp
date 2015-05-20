<?php $this->Ng->ngController('AdsCtrl') ?>
<?php $this->Ng->fdbDirective(['image_upload']); ?>
<?php
    $this->Ng->ngInit(
        [
            'ads' => isset($ads) ? $ads : ''
        ]
    );
?>
<div class="row">
    <!-- Breadcrumb -->
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
    	<h1 class="page-title txt-color-blueDark">
            <i class="fa fa-edit fa-fw "></i>Ads Manage
    	</h1>
    </div>
</div>
<!-- List Ads -->
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <button class="btn btn-primary" data-toggle="modal" data-target="#formAddAd">New Ad</button><p></p>
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-cate-list"
            data-widget-deletebutton="false" data-widget-colorbutton="false" data-widget-editbutton="false">
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body">
                        <div class='clearfix'></div>
                        <div class="table-responsive">
                            <table id="resultTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Position</th>
                                        <th>Image URL</th>
                                        <th>URL</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody ng-repeat="a in ads track by $index">
                                    <tr>
                                        <td>
                                            <label ng-bind = "(a.Property.key == 'ad_home_pos_1') ? 'Home page position 1' : 'Home page position 2'">
                                            </label>
                                        </td>
                                        <td>
                                            <img ng-src = "{{a.Property.foreign_key_left}}" />
                                        </td>
                                        <td>
                                            <a ng-show = "a.Property.foreign_key_right" target="_blank"
                                            href="{{a.Property.foreign_key_right}}"
                                            title="{{a.Property.foreign_key_right}}">View</a>
                                        </td>
                                        <td>
                                            <a ng-click="deleteAds(a.Property.id)" href="#">
                                            <i class="fa fa-lg fa-times"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>

<!-- Add form -->
<div class="modal fade" id="formAddAd" tabindex="-1" role="dialog" aria-labelledby="formAddAd" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="formAddAd">Create new Ad</h4> <label>Image size : 848 x 113</label></div>
            <div class="modal-body">
                <form class="smart-form" name='addNewAd'>
                    <section>
                        <label class="label">Position</label>
                        <select ng-model = 'ad.pos' class="form-control">
                            <option ng-repeat = "(k, v) in arrAdPos" value="{{k}}">{{v}}</option>
                        </select>
                    </section>
                    <section>
                        <label class="label">Select image</label>
                        <div id="ad_upload" ad_width_allow = "848" ad_height_allow = "113"
                            class="image-upload account-logo-upload" image-upload="ad.image"
                            max-image-size="400000"
                            title="<?php echo __('Click on the image to choose another one'); ?>">
                        </div>
                    </section>
                    <section>
                        <label class="label">Destination URL</label>
                        <input class="form-control" placeholder="eg:http://www.amazon.com" type="text"
                        ng-model = "ad.des" required />
                    </section>
                    <section>
                        <label class="label" ng-show = "Message.error">{{Message.error}}</label>
                    </section>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="cancelAdd" class="btn btn-default" data-dismiss="modal"> Cancel </button>
                <button type="button" class="btn btn-danger" ng-click='saveAd(ad)'> Save </button>
            </div>
        </div>
    </div>
</div>