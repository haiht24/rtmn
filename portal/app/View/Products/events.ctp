<?php $this->Html->script('/lib/fromjs/from', ['inline' => false]); ?>
<?php $this->Ng->ngController('ProductEventCtrl') ?>
<?php $this->Ng->ngInit(
    [
        'events' => isset($events) ? $events : [],
        'user' => isset($user) ? $user : [],
        'users' => isset($users) ? $users : []
    ])
?>
<style>
    .event-detail > td {
        padding: 0 !important;
    }

    .event-detail > td .event-info {
        padding: 8px 10px;
    }

    .event-info label {
        font-weight: bold;
    }
</style>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-"></i>
            MostCoupon <span>Events</span>
        </h1>
    </div>
</div>
<div class="row">
    <div class="button-container col-xs-12">
        <div class="modal fade in" id="modal-add-event" tabindex="-1" role="dialog" aria-labelledby="modal-label-add-event" aria-hidden="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="modal-label-add-event">Add New Event</h4>
                        <div class="draft-warning" style="float: right;"
                             ng-show="eventItem.showDraft && eventItem.popupDraft && eventItem.addMode">
                            <a ng-click="loadDraftEvent()" ><?php echo __('Last draft for your Event') ?> ({{ eventItem.popupDraft.created }})</a>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form class="smart-form" name='addEventForm' novalidate>
                            <section>
                                <label class="label">Name</label>
                                <label class="input">
                                    <i class="icon-append fa fa-tag"></i>
                                    <input type="text"
                                           placeholder="Event Name"
                                           ng-model="currentEvent.name"
                                           required
                                           name="name"
                                           ng-disabled="user.permissions.allow_add_event == 0
                                           && user.permissions.allow_edit_event == 1"/>
                                </label>
                                <p class='error' ng-show='eventItem.showError && addEventForm.name.$invalid'>Please enter Event name</p>
                                <div ng-show="currentEvent.name">
                                    <button ng-click="checkExistsNameEvent()">Check Exists Name</button>
                                    <p ng-show="eventItem.checkExist">Name is exists.</p>
                                    <p ng-show="eventItem.checkNotExist">Name is not exists.</p>
                                </div>
                            </section>
                            <section>
                                <label class="label" for="">Description</label>
                                <label class="textarea">
                                    <i class="icon-append fa fa-comment"></i>
                                    <textarea rows="3" name="description" ng-model="currentEvent.description"
                                              placeholder="Event Description"></textarea>
                                </label>
                            </section>
                            <section ng-show="!eventItem.addMode">
                                <label class="label" for="">Created</label>
                                <label class="label">
                                    {{currentEvent.created | formatDateTimeLocal}}
                                </label>
                            </section>
                            <section ng-show="!eventItem.addMode">
                                <label class="label" for="">Author</label>
                                <label class="label">
                                    {{currentEvent.author.fullname}}
                                </label>
                            </section>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal"
                                id="cancelEvent">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary"
                                ng-click="saveEvent()"
                                id="saveEvent">
                            Add
                        </button>
                        <button type="button" class="btn btn-primary"
                                ng-click="saveEvent('published')"
                                id="publishCate"
                                ng-show="currentEvent.status == 'pending'
                                            && user.permissions.allow_add_event == 1">
                            Publish
                        </button>
                        <button type="button" class="btn btn-primary"
                                ng-click="saveEvent('pending')"
                                ng-show="arrayContains(currentEvent.status, ['trash','published'])
                                            && user.permissions.allow_add_event == 1">
                            Pending Review
                        </button>
                        <button type="button" class="btn btn-primary"
                                ng-click="saveEvent('trash')"
                                ng-show="arrayContains(currentEvent.status, ['pending','published'])
                                            && user.permissions.allow_add_event == 1">
                            Move to Trash
                        </button>
                        <button type="button" class="btn btn-primary"
                                ng-click="deleteEvent(currentEvent.id)"
                                ng-show="currentEvent.id && currentEvent.status == 'trash'
                                && user.permissions.allow_add_event == 1">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-cate-list"
                 data-widget-deletebutton="false"
                 data-widget-colorbutton="false"
                 data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-tag"></i> </span>
                    <span>Total: {{eventItem.totalItems | number}} Events</span>
                </header>
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body">
                        <div class="search-box-container col-xs-12">
                            <div class="input-group">
                                <label>Filter by:</label>
                                <a class="btn btn-primary" ng-click="showAllEvent();">Clear</a>
                            </div>
                            <br>
                            <div class="input-group input-group-lg">
                                <input class="form-control input-lg"
                                       type="text"
                                       ng-model="eventItem.filter"
                                       ng-change="searchEvents()"
                                       placeholder="name, description">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fa fa-fw fa-search fa-lg"></i>
                                    </button>
                                </div>
                            </div>
                            <br>
                            <div>
                                <label>Filter by Author:</label>
                                <select ng-model="eventItem.userFilter" ng-change="searchEvents()">
                                    <option></option>
                                    <option ng-repeat="user in users" ng-value='user.user.id'>{{user.user.fullname}}</option>
                                </select>
                                <label>Filter by Status:</label>
                                <select ng-model="eventItem.statusFilter" ng-change="searchEvents()">
                                    <option></option>
                                    <option value='pending'>pending</option>
                                    <option value='published'>published</option>
                                    <option value='trash'>trash</option>
                                </select>
                                <label>Filter by Created: From </label>
                                <input class="date-capture-mode datepicker"
                                    id ='dateCategoryFilter'
                                    ng-model="eventItem.createdFromFilter"
                                    ng-change="searchEvents()"/>
                                <label>- To </label>
                                <input class="date-capture-mode datepicker"
                                    id ='datePublishCategoryFilter'
                                    ng-model="eventItem.createdToFilter"
                                    ng-change="searchEvents()"/>
                            </div>
                            <br>
                            <div>
                                <a class="btn btn-primary btn-add-event"
                                   ng-if="user.permissions.allow_add_event == 1"
                                   data-toggle="modal"
                                   data-target="#modal-add-event"
                                   ng-click="initEvent();">
                                    <i class="fa fa-plus"></i>
                                    Add Event
                                </a>
                            </div>
                            <br>
                        </div>
                        <div class='clearfix'></div>
                        <div class="table-responsive">
                            <table id="resultTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th ng-click="sortByEvent('name');"
                                        ng-class="{'asc':(filterEventOptions.sortBy == true
                                        && filterEventOptions.sortField == 'name'),
                                        'desc': (filterEventOptions.sortBy == false
                                        && filterEventOptions.sortField == 'name')}">Name</th>
                                    <th ng-click="sortByEvent('author');"
                                        ng-class="{'asc':(filterEventOptions.sortBy == true
                                        && filterEventOptions.sortField == 'author'),
                                        'desc': (filterEventOptions.sortBy == false
                                        && filterEventOptions.sortField == 'author')}">Author</th>
                                    <th  ng-click="sortByEvent('created');"
                                        ng-class="{'asc':(filterEventOptions.sortBy == true
                                        && filterEventOptions.sortField == 'created'),
                                        'desc': (filterEventOptions.sortBy == false
                                        && filterEventOptions.sortField == 'created')}">Created date</th>
                                    <th ng-click="sortByEvent('status');"
                                        ng-class="{'asc':(filterEventOptions.sortBy == true
                                        && filterEventOptions.sortField == 'status'),
                                        'desc': (filterEventOptions.sortBy == false
                                        && filterEventOptions.sortField == 'status')}">Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody  ng-repeat="(indexItem, cate) in eventItem.pages[eventItem.currentPage]">
                                    <tr>
                                        <td>{{cate.event.name}}</td>
                                        <td>{{cate.author.fullname}}</td>
                                        <td>{{cate.event.created | formatDateTimeLocal}}</td>
                                        <td>
                                            <span class="label"
                                                  ng-class="{'label-success': cate.event.status == 'published',
                                                  'label-warning' : arrayContains(cate.event.status, ['pending','trash'])}">
                                                {{cate.event.status}}
                                            </span>
                                        </td>
                                        <td>
                                            <button ng-click='editEvent(cate, indexItem)'
                                                    ng-if="user.permissions.allow_edit_event == 1"
                                                    data-target="#modal-add-event"
                                                    data-toggle="modal">Edit</button>
                                            <button ng-show="cate.event.status == 'pending'
                                                    && user.permissions.allow_add_event == 1"
                                                    ng-click="setStatusEvent(cate.event.id, indexItem, 'published')">Publish</button>
                                            <button ng-show="arrayContains(cate.event.status, ['pending','published'])
                                                    && user.permissions.allow_add_event == 1"
                                                    ng-click="setStatusEvent(cate.event.id, indexItem, 'trash')">Move To Trash</button>
                                            <button ng-show="cate.event.status == 'trash' && user.permissions.allow_add_event == 1"
                                                    ng-click="deleteEvent(cate.event.id)">Delete</button>
                                            <button ng-show="arrayContains(cate.event.status, ['trash','published'])
                                                    && user.permissions.allow_add_event == 1"
                                                    ng-click="setStatusEvent(cate.event.id, indexItem, 'pending')">Pending Review</button>
                                            <a href="#">
                                                <i class="fa fa-plus accordion-toggle"
                                                   data-target="#demo-{{indexItem}}-event"
                                                   data-toggle="collapse"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="event-detail">
                                        <td colspan="7">
                                            <div class="collapse" id="demo-{{indexItem}}-event">
                                                <div class="event-info row">
                                                    <div class="col-md-6">
                                                        <table class="table table-bordered">
                                                             <tr>
                                                                <td>
                                                                    <label>Publish Date</label>
                                                                </td>
                                                                <td>
                                                                    <span ng-show="cate.event.publish_date && cate.event.status == 'published'">
                                                                        {{cate.event.publish_date | formatDateTimeLocal}}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <td><label>Description</label></td>
                                                                <td>{{cate.event.description}}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <hr>
                            <ul class="pagination no-margin">
                                <li class="arrow" ng-click="prevPageEvent()" ng-show="eventItem.pages.length > 1"
                                    ng-class="{'disabled': 0 == eventItem.currentPage}">
                                    <a>Previous</a>
                                </li>
                                <li ng-repeat="n in range(eventItem.pages.length)"
                                    ng-class="{active: n == eventItem.currentPage}" ng-click="setPageEvent(n)">
                                    <a ng-show="n >= 0 && n < 10">{{ n + 1 }}</a>
                                </li>
                                <li>
                                    <input type="number" ng-model="eventItem.currentPageInc"
                                           ng-show="eventItem.pages.length > 10" ng-change="changePageEvent()"/>
                                </li>
                                <li ng-click="setPageEvent(eventItem.pages.length - 1)"
                                    ng-class="{active: (eventItem.pages.length - 1) == eventItem.currentPage}">
                                    <a ng-show="eventItem.pages.length > 10">{{ eventItem.pages.length }}</a>
                                </li>
                                <li class="arrow" ng-show="eventItem.pages.length > 1"
                                    ng-click="nextPageEvent()"
                                    ng-class="{'disabled': (eventItem.pages.length - 1) == eventItem.currentPage}">
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