<?php $this->Ng->ngController('CareersCtrl'); ?>
<?php
    $this->Ng->ngInit(
        [
            'docs' => isset($docs) ? $docs : []
        ]
    );
?>
<div class="container main-content paper show-text-content">
    <h1 class="title font-quark">
        <strong class="text-success" ng-bind-html = "title|trusted"></strong>
    </h1>
    <div class="body" ng-bind-html = "content|trusted"></div>
</div>