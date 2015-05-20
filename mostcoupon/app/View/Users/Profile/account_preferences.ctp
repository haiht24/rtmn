<?php $this->Ng->ngController('UserAccountPreferencesCtrl') ?>
<!-- Begin account preferences content -->
<div class="profile-paper" style="width: 100%;">
    <form class="account-preferences section profile-form form-horizontal">
        <div class="title underline"> Change Email Address</div>
        <p class="subtitle"> Your are currently registered with this email address: abc.xyz@gmail.com </p>

        <div class="content">
            <div class="form-group">
                <label class="control-label col-sm-3 required"> New Email </label>

                <div class="col-sm-8">
                    <input type="text" class="form-control"/></div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3 required"> Confirm Email </label>

                <div class="col-sm-8">
                    <input type="text" class="form-control"/></div>
            </div>
            <div class="row">
                <div class="col-sm-11 clearfix">
                    <button class="btn btn-primary btn-submit pull-right"> Save Changes</button>
            <span class="updated pull-right">
              <i class="icon mc mc-check-circle-o"></i> Changed Email </span>
                </div>
            </div>
        </div>
    </form>
    <form class="account-preferences section profile-form form-horizontal">
        <div class="title underline"> Change Password</div>
        <p class="subtitle"> Please use this form to change your password. Your new password must be at least 6
            characters long. </p>

        <div class="content">
            <div class="form-group">
                <label class="control-label col-sm-3 required"> New Password </label>

                <div class="col-sm-8">
                    <input type="password" class="form-control"/></div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3 required"> Confirm Password </label>

                <div class="col-sm-8">
                    <input type="password" class="form-control"/></div>
            </div>
            <div class="row">
                <div class="col-sm-11 clearfix">
                    <button class="btn btn-primary btn-submit pull-right"> Save Changes</button>
            <span class="updated pull-right">
              <i class="icon mc mc-check-circle-o"></i> Changed Password </span>
                </div>
            </div>
        </div>
    </form>
    <div class="account-preferences section profile-form">
        <div class="title underline iconic">
            <i class="icon mc mc-stats"></i>
            <strong>DEAL ALERTS</strong>
        </div>
        <p class="subtitle"> Get the newest deals. Select and customize email alerts from your favorite stores. </p>

        <div class="content head-less">
            <div class="row">
                <div class="col-md-4 deal-alert">
                    <div class="title">
                        <div class="no">1</div>
                        <strong>Customize</strong>
                        <span>Your Alerts</span>
                    </div>
                    <div class="block">
                        <div class="title">How often:</div>
                        <div class="fe-wrapper">
                            <label class="tick square">
                                <input checked="checked" type="checkbox">
                                <i></i>
                                <span>Daily Updates</span>
                            </label>
                        </div>
                        <div class="fe-wrapper">
                            <label class="tick square">
                                <input checked="checked" type="checkbox">
                                <i></i>
                                <span>Weekly Updates</span>
                            </label>
                        </div>
                        <div class="fe-wrapper">
                            <label class="tick square">
                                <input checked="checked" type="checkbox">
                                <i></i>
                                <span>Never (Stop All Alerts)</span>
                            </label>
                        </div>
                    </div>
                    <div class="block">
                        <div class="title">Farorite Stores:</div>
                        <div class="fe-wrapper">
                            <label class="tick square">
                                <input checked="checked" type="checkbox">
                                <i></i>
                                <span>Add My Farorite Stores to Deal Alerts</span>
                            </label>
                        </div>
                    </div>
                    <button class="btn btn-dark btn-submit"> Save Changes</button>
                </div>
                <div class="col-md-4  deal-alert">
                    <div class="title">
                        <div class="no">2</div>
                        <strong>Search</strong>
                        <span>for Stores</span>
                    </div>
                    <div class="block">
                        <div class="title">Select Featured Stores:</div>
                        <p>Add My Farorite Stores to Deal Alerts</p>
                        <select class="form-control">
                            <option>Categories</option>
                        </select>

                        <p>Or Search</p>

                        <div class="search-input">
                            <input type="text" class="form-control" placeholder="ex. Home Depot"/>
                            <i class="icon mc mc-search"></i>
                        </div>
                        <ul>
                            <li>Lorem ipsum dolor sit amet</li>
                            <li>Lorem ipsum dolor sit amet</li>
                            <li>Lorem ipsum dolor sit amet</li>
                            <li>Lorem ipsum dolor sit amet</li>
                            <li>Lorem ipsum dolor sit amet</li>
                            <li>Lorem ipsum dolor sit amet</li>
                            <li>Lorem ipsum dolor sit amet</li>
                            <li>Lorem ipsum dolor sit amet</li>
                            <li>Lorem ipsum dolor sit amet</li>
                            <li>Lorem ipsum dolor sit amet</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-md-offset-1 deal-alert">
                    <div class="title">
                        <div class="no">3</div>
                        <span>Your Current</span>
                        <strong>Alerts</strong>
                    </div>
                    <div class="block">
                        <div class="current-alert">
                            <i class="icon mc mc-check"></i>
                            <span>Lorem ipsum dolor sit amet</span>
                            <a href="##" class="remove">x</a>
                        </div>
                        <div class="current-alert">
                            <i class="icon mc mc-check"></i>
                            <span>Lorem ipsum dolor sit amet</span>
                            <a href="##" class="remove">x</a>
                        </div>
                        <div class="current-alert">
                            <i class="icon mc mc-check"></i>
                            <span>Lorem ipsum dolor sit amet</span>
                            <a href="##" class="remove">x</a>
                        </div>
                        <div class="current-alert">
                            <i class="icon mc mc-check"></i>
                            <span>Lorem ipsum dolor sit amet</span>
                            <a href="##" class="remove">x</a>
                        </div>
                        <div class="current-alert">
                            <i class="icon mc mc-check"></i>
                            <span>Lorem ipsum dolor sit amet</span>
                            <a href="##" class="remove">x</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End account preferences Content -->
