<?php $this->Ng->ngController('PrivacyCtrl') ?>
<?php
    $this->Ng->ngInit(
        [
            'docs' => isset($docs) ? $docs : []
        ]
    );
?>
<?php echo $this->Html->script('/js/ckeditor/ckeditor'); ?>
<script>
    var UpdatePath = '<?php echo $this->Html->url(array('controller' => 'StaticPages', 'action' => 'update')); ?>';
    // DO NOT REMOVE : GLOBAL FUNCTIONS FOR CKEDITOR!
    $(document).ready(function() {
        CKEDITOR.replace('ckeditor', {
            height: '380px',
            startupFocus: true
        });
    })
</script>
<!-- Configuration -->
<div class="row">
    <!-- Breadcrumb -->
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
    	<h1 class="page-title txt-color-blueDark">
            <i class="fa fa-edit fa-fw "></i>Documents Config <span><a target="_blank" href="<?php echo str_replace('portal', 'frontend', $this->Html->url('/PrivacyPolicy/index', true));?>">&gt; Page {{pageName}}</a></span>
    	</h1>
    </div>
</div>

<div data-widget-sortable="false" data-widget-fullscreenbutton="false" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false" id="wid-id-0" class="jarviswidget jarviswidget-color-darken">
    <header> <span class="widget-icon"> <i class="fa fa-pencil"></i> </span>
        <h2>{{pageName}}</h2> </header>
    <!-- widget div-->
    <div>
        <!-- widget edit box -->
        <div class="jarviswidget-editbox">
            <!-- This area used as dropdown edit box -->
        </div>
        <!-- end widget edit box -->
        <!-- widget content -->
        <div class="widget-body no-padding">
            <textarea ng-model = "privacy" name="ckeditor" style="visibility: hidden; display: none;"></textarea>
        </div>
        <!-- end widget content -->
    </div>
    <!-- end widget div -->
</div>
<a ng-click = "save()" class="btn btn-primary">Save</a>
<p ng-bind = "mess"></p>