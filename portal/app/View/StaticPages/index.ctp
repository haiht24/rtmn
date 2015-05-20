<?php $this->Ng->ngController('DocsCtrl') ?>
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
            <i class="fa fa-edit fa-fw "></i>Documents Config <span><a target="_blank" href="<?php echo str_replace('portal', 'frontend', $this->Html->url('/AboutUs/index', true));?>">&gt; Page About Us</a></span>
    	</h1> </div>
    <!-- Welcome text -->
    <form class="col-sm-12">
        <h1>Welcome text</h1>
        <h3>Line 1</h3>
        <input type="text" ng-model="welcome_1" ng-blur="saveWelcomeText()" style="width: 100%;" />
        <h3>Line 2</h3>
        <input type="text" ng-model="welcome_2" ng-blur="saveWelcomeText()" style="width: 100%;" />
        <p></p> {{messWelcome}} </form>
    <!-- Member text -->
    <form class="col-sm-12">
        <h1>Member text</h1>
        <input type="text" ng-model="memberText" ng-blur="saveText()" style="width: 100%;" />
        <p></p>
        <h1>Coupon text</h1>
        <input type="text" ng-model="couponText" ng-blur="saveText()" style="width: 100%;" />
        <h1>Store text</h1>
        <input type="text" ng-model="storeText" ng-blur="saveText()" style="width: 100%;" />
        <h1>Follow text</h1>
        <input type="text" ng-model="followText" ng-blur="saveText()" style="width: 100%;" />
        <p></p> {{messText}} </form>
    <!-- Slide -->
    <form class="col-sm-12">
        <h1>Slide</h1>
        <div class="note">Separate by commas (eg: imageURL_1, imageURL_2,...)</div>
        <textarea class="custom-scroll" rows="5" ng-model="txtSlide" ng-blur="saveSlide()" style="height: 85px;width: 100%;"></textarea>
        <p></p> {{messSlide}} </form>
    <!-- Skills -->
    <form class="col-sm-12">
        <h1>Skills</h1>
        <label class="input">Skill 1</label>
        <input type="text" style="width: 100%;" ng-blur="saveSkill()" placeholder="Title | Value" ng-model="skill_1">
        <p></p>
        <label class="input">Skill 2</label>
        <input type="text" style="width: 100%;" ng-blur="saveSkill()" placeholder="Title | Value" ng-model="skill_2">
        <p></p>
        <label class="input">Skill 3</label>
        <input type="text" style="width: 100%;" ng-blur="saveSkill()" placeholder="Title | Value" ng-model="skill_3">
        <p></p>
        <label class="input">Skill 4</label>
        <input type="text" style="width: 100%;" ng-blur="saveSkill()" placeholder="Title | Value" ng-model="skill_4">
        <p></p> {{messSkills}} </form>
    <!-- Top stores -->
    <form class="col-sm-12">
        <h1>Slide Top Stores</h1>
        <label class="input">Enter Store ID</label>
        <div class="note">Separate by commas</div>
        <input type="text" ng-model="topStoreIDs" ng-blur="saveTopStores()" style="width: 100%;" placeholder="eg: storeID1, storeID2,..." />
        <p></p> {{messTopStores}} </form>
</div>
<h1>About Us</h1>
<input type="text" ng-model="aboutTitle" ng-blur="saveAboutTitle()" style="width: 100%;" placeholder="Who we are ?" />
<p></p>
<div data-widget-sortable="false" data-widget-fullscreenbutton="false" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false" id="wid-id-0" class="jarviswidget jarviswidget-color-darken">
    <header> <span class="widget-icon"> <i class="fa fa-pencil"></i> </span>
        <h2>About Content</h2> </header>
    <!-- widget div-->
    <div>
        <!-- widget edit box -->
        <div class="jarviswidget-editbox">
            <!-- This area used as dropdown edit box -->
        </div>
        <!-- end widget edit box -->
        <!-- widget content -->
        <div class="widget-body no-padding">
            <textarea ng-model="txtAbout" name="ckeditor" style="visibility: hidden; display: none;"></textarea>
        </div>
        <!-- end widget content -->
    </div>
    <!-- end widget div -->
</div> <a ng-click="saveAbout()" class="btn btn-primary">Save</a>
{{messAbout}}