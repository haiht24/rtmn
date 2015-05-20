<?php
App::import('Component', 'Sanction.PermitComponent');

// These rules define the actions which must be NOT authenticated
Permit::access(
	['controller' => ['pages', 'proxy', 'session', 'less']], [], []
);

Permit::access(
    [
        'controller' => ['users'],
        'action' => ['register', 'login', 'passwordReset', 'forgotactivelink', 'activation', 'facebook', 'portfolio']
    ],
    [],[]
);
Permit::access(
    [
        'controller' => ['posts'],
		'action' => ['index','detail','categories']
    ],
    [],[]
);
Permit::access(
	[
        'controller' => ['exchangedb'],
		'action' => ['index', 'backupStore', 'backupCoupon', 'doneStore', 'doneCoupon']
    ],
    [],[]
);
Permit::access(
	[
        'controller' => ['landing'],
		'action' => ['index']
    ],
    [],[]
);
Permit::access(
    [
        'controller' => ['collections'],
		'action' => ['index','detail']
    ],
    [],[]
);
// Block all controllers without an active account
$controllers = [];
foreach (App::objects('controller') as $controller) {
    $controllers[] = strtolower(str_replace('Controller', '', $controller));
}
Permit::access(
	['controller' => $controllers],
	['auth' => true],
	[
        'redirect' => ['controller' => 'users', 'action' => 'login'],
        'message' => 'Your session has ended. Please log in again.'
    ]
);
?>
