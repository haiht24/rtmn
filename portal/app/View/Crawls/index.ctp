<?php $this->Ng->ngController('CrawlsCtrl') ?>
<?php
    // Load custom scripts from webroot/js/crawls/crawls.js
    echo $this->Html->script('/js/crawls/crawls.js');
?>
<script type="text/javascript">
    var crawlCategory = '<?php echo $this->Html->url(array('controller' => 'Crawls', 'action' => 'crawlCategory')); ?>';
    var crawlStore = '<?php echo $this->Html->url(array('controller' => 'Crawls', 'action' => 'crawlStore')); ?>';
    var loadStores = '<?php echo $this->Html->url(array('controller' => 'Wpposts', 'action' => 'loadStores')); ?>';
    var getStoreInfo = '<?php echo $this->Html->url(array('controller' => 'Wpposts', 'action' => 'getStoreInfo')); ?>';
    var addTerm = '<?php echo $this->Html->url(array('controller' => 'WpTerms', 'action' => 'add')); ?>';
    var addPostByJq = '<?php echo $this->Html->url(array('controller' => 'Wpposts', 'action' => 'addByJq')); ?>';
    var ctrlCrawl = '<?php echo $this->Html->url(array('controller' => 'Crawls', 'action' => '')); ?>';
</script>
<div class="control">
<label class="select" for="slDb">Select database</label>
<select id="slDb" class="input-sm ">
    <?php
    Configure::load('crawl_settings');
    $db =  Configure::read('Crawl.databases');
    foreach($db as $databaseName):
    ?>
    <option value="<?php echo $databaseName ?>"><?php echo $databaseName; ?></option>
    <?php endforeach; ?>
</select>
<?php echo $this->Form->button('Load Config', array('class' => 'btn btn-default', 'id' => 'btnLoadConfig')); ?>
<?php
    echo $this->Form->input('URL', array('class' => 'form-control input-lg', 'placeholder' => 'Enter URL', 'value' => 'http://www.vouchercodes.co.uk/all-voucher-codes.html', 'id' => 'ipUrl', 'placeholder' => 'eg: http://www.myvouchercodes.co.uk/categories'));
    echo $this->Form->input('Homepage', array('class' => 'form-control input-lg', 'placeholder' => '', 'value' => '', 'id' => 'homepage'));
    echo $this->Form->input('Parent CSS Class', array('class' => 'form-control input-lg', 'placeholder' => 'Enter Parent CSS class', 'value' => 'div[class=all-merchants]', 'id' => 'ipParentClass'));
    echo $this->Html->tag('br');
    echo $this->Form->input('Select Type', array('options' => array('Select type', 'category' => 'Category', 'store' => 'Store'), 'name' => 'slType', 'id' => 'slType', 'class' => 'input-sm'));
    echo $this->Html->tag('br');
    // Button
    echo $this->Form->button('Crawl', array('class' => 'btn btn-danger', 'id' => 'btnCrawl'));
    echo '&nbsp;';
    echo $this->Form->button('Load Pending Stores', array('class' => 'btn btn-warning', 'id' => 'btnLoadStore'));
    echo '&nbsp;';
    echo $this->Form->button('Get Stores info and coupons', array('class' => 'btn btn-warning', 'id' => 'btnGetStoreInfo', 'disabled' => 'disabled'));
    echo '&nbsp;';
    echo $this->Form->button('Clear data', array('class' => 'btn btn-default', 'id' => 'btnClearData'));
    echo '&nbsp;';
    echo $this->Form->button('Clear input text', array('class' => 'btn btn-default', 'id' => 'btnClearInput'));
    echo '&nbsp;';
    echo $this->Form->button('Save Config', array('class' => 'btn btn-default', 'id' => 'btnSaveConfig'));
    echo '&nbsp;';

    echo $this->Form->label('', '', array('display' => 'none', 'id' => 'lblMess'));
?>
<!-- Form Store -->
<div class='tmpStore' style='background-color:whitesmoke;' hidden="true" >
<?php
    // HIDDEN FORM TO ADD STORE
    echo $this->Html->tag('br');
    echo $this->Html->tag('h1', 'Get below STORE classes from CATEGORIES LIST PAGE', array('class' => 'alert alert-info'));
    echo $this->Form->input('Store Name', array('id' => 'cl_storeName', 'value' => 'li a', 'class' => 'form-control input-lg', 'placeholder' => 'Enter class name'));
    echo $this->Form->input('Get URL ? (Enter "href")', array('id' => 'cl_storeUrl', 'value' => 'href', 'class' => 'form-control input-lg', 'placeholder' => 'Enter class name'));

    echo $this->Html->tag('br');
    echo $this->Html->tag('h1', 'Get below STORE classes from Store detail page', array('class' => 'alert alert-info'));
    echo $this->Form->input('Store Description', array('id' => 'cl_storeDesc', 'value' => '/html/body/div[5]/div[1]/section/div/hgroup/h2', 'class' => 'form-control input-lg', 'placeholder' => 'Enter class name'));
    echo $this->Form->input('Store Homepage', array('id' => 'cl_storeHomepage', 'value' => '/html/body/div[5]/div[2]/section/aside[1]/div/p[2]/a', 'class' => 'form-control input-lg', 'placeholder' => 'Enter class name'));
    echo $this->Form->input('Logo', array('id' => 'cl_storeLogo', 'value' => '/html/body/div[5]/div[1]/section/div/div/a/img|data-lazy-src', 'class' => 'form-control input-lg', 'placeholder' => 'Enter class name | Attribute name (default get attribute src)'));
    echo $this->Form->input('Breadcrumb', array('id' => 'cl_storeBreadcrumb', 'value' => 'aside[class="block categories"] li a', 'class' => 'form-control input-lg', 'placeholder' => 'Enter class name'));

    echo $this->Html->tag('br');
    echo $this->Html->tag('h1', 'Get below COUPON classes from Store detail page', array('class' => 'alert alert-info'));
    echo $this->Form->input('Coupon', array('id' => 'clCouponParentClass', 'value' => 'div[class=merch-offers]|.offer-module', 'class' => 'form-control input-lg', 'placeholder' => 'Enter class name'));
    echo $this->Form->input('Coupon Title', array('id' => 'clCouponTitle', 'value' => 'h3', 'class' => 'form-control input-lg', 'placeholder' => 'Enter class name'));
    echo $this->Form->input('Coupon Code', array('id' => 'clCouponCode', 'value' => '.code-value', 'class' => 'form-control input-lg', 'placeholder' => 'Enter class name'));
    echo $this->Form->input('Coupon Description', array('id' => 'clCouponDesc', 'value' => 'p[class=additional-info]', 'class' => 'form-control input-lg', 'placeholder' => 'Enter class name'));
    echo $this->Form->input('Coupon Expire Date', array('id' => 'clCouponExpire', 'value' => 'span[class=merchant-flagdate]', 'class' => 'form-control input-lg', 'placeholder' => 'Enter class name'));
?>
</div>
<!--#-->
<!-- Form Category -->
<div class='tmpCategory' hidden="true"></div>

</div>
<div id="output" ></div>
<div id="divMessage" style='height: 200px; overflow-y: scroll;'></div>