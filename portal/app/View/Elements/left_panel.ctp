<aside id="left-panel">

    <!-- User info -->
    <div class="login-info">
        <span> <!-- User image size is adjusted inside CSS, it should stay as it -->

            <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                <img src="<?php echo $this->Html->url('/img/avatars/sunny.png') ?>" alt="me" class="online" />
                <span>
                    <?php echo (!empty($user['user'])) ? $user['user']['fullname']: '';?>
                </span>
                <i class="fa fa-angle-down"></i>
            </a>

        </span>
    </div>
    <!-- end user info -->

    <!-- NAVIGATION : This navigation is also responsive

    To make this navigation dynamic please make sure to link the node
    (the reference to the nav > ul) after page load. Or the navigation
    will not initialize.
    -->
    <nav>
        <!-- NOTE: Notice the gaps after each icon usage <i></i>..
        Please note that these links work a bit different than
        traditional href="" links. See documentation for details.
        -->

        <ul class="nav">
            <li>
                <a href="<?= $this->Html->url('/', true) ?>" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a>
            </li>
            <li>
                <a href="<?= $this->Html->url('/home/inbox') ?>"><i class="fa fa-lg fa-fw fa-inbox"></i> <span class="menu-item-parent">Inbox</span><span class="badge pull-right inbox-badge">14</span></a>
            </li>

            <li>
                <a href="<?= $this->Html->url('/home/calendar') ?>"><i class="fa fa-lg fa-fw fa-calendar"><em>3</em></i>
                    <span class="menu-item-parent">Calendar</span></a>
            </li>
            <li>
                <a href="#"><i class="fa fa-lg fa-fw fa-users"></i> <span class="menu-item-parent">Staff</span></a>
                <ul>
                    <li>
                        <a href="<?= $this->Html->url('/users/index')  ?>">User Management</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-lg fa-fw fa-gears"></i> <span class="menu-item-parent">Systems</span></a>
                <ul>
                    <li><a href="<?= $this->Html->url('/Crawls/index') ?>">Web Crawler</a></li>
                    <li><a href="<?= $this->Html->url('/home/email_temp')  ?>">Email Template</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-lg fa-fw fa-ticket"></i> <span
                        class="menu-item-parent">MostCoupon</span></a>
                <ul>
                    <li>
                      <a href="<?php echo $this->Html->url(['controller' => 'Rtmn']) ?>">
                        RTMN
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo $this->Html->url(['controller' => 'Rtmn', 'action' => 'index_2']) ?>">
                        RTMN 2
                      </a>
                    </li>
                    <li>
                      <a href="<?= $this->Html->url(['controller' => 'contents']) ?>">
                        Content Management
                      </a>
                    </li>
                    <li>
                        <a href="<?= $this->Html->url(['controller' => 'products', 'action' => 'events']) ?>">Events</a>
                    </li>
                    <li>
                        <a href="<?= $this->Html->url(['controller' => 'contacts', 'action' => 'index']) ?>">User's
                            Messages</a>
                    </li>
                    <li>
                        <a href="<?= $this->Html->url(['controller' => 'UsersFrontends', 'action' => 'index']) ?>">User Management</a>
                    </li>
                    <li class="">
						<a href="#">Documents Config</a>
                        <ul>
                            <li>
                                <a href="<?= $this->Html->url(['controller' => 'ads', 'action' => 'index']) ?>">Advertise banner </a>
                            </li>
                            <li>
                                <a href="<?= $this->Html->url('/StaticPages/aboutCookies'); ?>">About Cookies</a>
                            </li>
							<li>
								<a href="<?= $this->Html->url('/StaticPages/index'); ?>">About Us</a>
							</li>
                            <li>
								<a href="<?= $this->Html->url('/StaticPages/contactUs'); ?>">Contact Us</a>
							</li>
                            <li>
								<a href="<?= $this->Html->url('/StaticPages/downloadApp'); ?>">Download App</a>
							</li>
                            <li>
								<a href="<?= $this->Html->url('/StaticPages/pressCentre'); ?>">Press Centre</a>
							</li>
                            <li>
								<a href="<?= $this->Html->url('/StaticPages/careers'); ?>">Careers</a>
							</li>
                            <li>
								<a href="<?= $this->Html->url('/StaticPages/help'); ?>">Help</a>
							</li>
                            <li>
								<a href="<?= $this->Html->url('/StaticPages/terms'); ?>">Terms</a>
							</li>
                            <li>
								<a href="<?= $this->Html->url('/StaticPages/privacy'); ?>">Privacy Policy</a>
							</li>
                            <li>
								<a href="<?= $this->Html->url('/StaticPages/appTerms'); ?>">App Terms</a>
							</li>
                            <li>
								<a href="<?= $this->Html->url('/StaticPages/competitionTerms'); ?>">Competition Terms</a>
							</li>
                            <li>
								<a href="<?= $this->Html->url('/StaticPages/directAdv'); ?>">Direct Advertising</a>
							</li>
						</ul>
					</li>
                    <li>
                        <a href="<?= $this->Html->url(['controller' => 'seo', 'action' => 'index']) ?>">Seo Config</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <span class="minifyme" data-action="minifyMenu">
        <i class="fa fa-arrow-circle-left hit"></i>
    </span>

</aside>