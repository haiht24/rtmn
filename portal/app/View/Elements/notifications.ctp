<div ng-controller="NotificationCtrl" ng-cloak>
    <div class="message-new anim-appear" fade-out ng-class="message.type" ng-repeat="message in messages">
        <i class="ui-icon-checkmark"></i>
        <div class="message-content">
            <h5>{{ message.title }}</h5>
            <p>{{ message.content }} <a ng-show="message.detail" ng-click="showDetail = !showDetail">(details)</a></p>
            <span class="detail" ng-show="showDetail" ng-bind-html="message.detail | lineBreakToBr | trustAsHtml"></span>
        </div>
        <a ng-click="deleteNotification($index)" class="right ui-icon-cancel"></a>
    </div>
</div>