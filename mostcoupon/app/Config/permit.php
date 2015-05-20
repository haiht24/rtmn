<?php
App::import('Component', 'Sanction.PermitComponent');

// These rules define the actions which must be NOT authenticated
Permit::access(
	array(
        'controller' => array('home', 'pages', 'proxy', 'session', 'less', 'seo', 'subscribes', 'users', 'go', 'likes', 'errors', 'blog')
	),
	array(),
	array()
);
Permit::access(
	array(
		'controller' => array('users'),
		'action' => array(
                    'register', 'login', 'forgotpassword', 'forgotactivelink',
                    'activation', 'facebook', 'portfolio')
            ),
	array(
	),
	array()
);

Permit::access(
	array(
		'controller' => array('stores'),
        'action' => array('index', 'details', 'listStoreToSubmit', 'addStore')
	),
	array(
	),
	array()
);
Permit::access(
    array(
        'controller' => array('deals'),
        'action' => array('index', 'details', 'search', 'submitDeal', 'getCode', 'getMore')
    ),
    array(
    ),
    array()
);

Permit::access(
    array(
        'controller' => array('coupons'),
        'action' => array('index', 'details', 'topCoupon', 'submitCoupon', 'getCode', 'sendInfo', 'addComment', 'addSaveoff')
    ),
    array(
    ),
    array()
);

Permit::access(
    array(
        'controller' => array('categories'),
        'action' => array('index', 'details')
    ),
    array(
    ),
    array()
);
// Block all controllers without an active account
$controllers = array();
foreach (App::objects('controller') as $controller) {
    $controllers[] = strtolower(str_replace('Controller', '', $controller));
}
Permit::access(
	array('controller' => $controllers),
	array('auth' => true),
	array('redirect' => array('controller' => 'users', 'action' => 'login'), 'message' => 'Your session has ended. Please log in again.')
);
?>
