<?php $this->Html->script(
[
    '/lib/angular-route/angular-route.min',
    '/lib/angular-animate/angular-animate.min',
    'users/index/list',
    'users/index/add',
    'users/index/edit'
],
['inline' => false]
); ?>

<?php $this->Ng->ngController('UserCtrl') ?>
<?php $this->Ng->fdbDirective(['image_upload']); ?>
<?php $this->Ng->ngInit(
    [
        'users' => isset($users) ? $users : [],
        'user' => isset($user) ? $user : []
    ])
?>

<div class="section main-pane" ng-cloak
     ng-class="{'anim-left-to-right': history.direction == 'back',
               'anim-right-to-left': history.direction == 'forward'}">
  <div class="sub-page" ng-view ></div>
</div>