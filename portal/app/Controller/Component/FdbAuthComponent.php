<?php

App::uses('Component', 'Controller');

App::uses('Auth', 'Lib');
class FdbAuthComponent extends Component
{

    public $components = ['Session'];
    protected $controller = null;

    public $rules = [
        'public' => [
            'home' => '*',
            'pages' => '*',
            'proxy' => '*',
            'session' => '*',
            'less' => '*',
            'seo' => '*',
            'widgets' => '*',
            'offline' => '*',
            'upload' => '*',
            'users' => [
                'register',
                'registerWithRefCode',
                'login',
                'logout',
                'passwordReset',
                'requestActivation',
                'activation',
                'facebook',
                'promotion',
                'facebookLogin',
                'facebookRegister'
            ],
            'surveys' => [
                'fillOut',
                'fillOutDone',
                'design',
                'preview',
                'rotation',
                'fillOutTerminal',
                'synchronize',
                'privacy',
                'tweet'
            ],
            'fillOut' => [
                'index',
                'preview',
                'privacy'
            ],
            'fill_out' => [
                'index',
                'preview',
                'privacy'
            ],
            'reviews' => ['index'],
            'incentives' => ['downloadvoucher']
        ],
        'noAccount' => [
            'launchpad' => '*',
            'cockpit' => ['master']
        ]
    ];

    protected function _matchRule($uri, $rule)
    {
        if (is_string($uri)) {
            $uri = Router::parse($uri);
        }
        $c = $uri['controller'];
        $a = $uri['action'];
        return array_key_exists($c, $rule) && ($rule[$c] == '*' || in_array($a, $rule[$c]));
    }

    protected function _redirectToReferer()
    {
        if ($referer = $this->Session->read('Auth.referer')) {
            $this->Session->delete('Auth.referer');
            $this->controller->redirect($referer);
        }
    }

    protected function _authorize()
    {
        if ($this->_matchRule($this->controller->params, $this->rules['public'])) {
            return;
        }
        if ($this->Session->check('Auth.User.Id')) {
            $this->_redirectToReferer();
        }
        // set referrer
        $this->Session->write('Auth.referer', Router::normalize($this->controller->request->here()));
        // redirect to login page
        $this->controller->redirect(
            [
                //'language' => $this->controller->request->param('language'),
                'controller' => 'users',
                'action' => 'login',
                'plugin' => false
            ]
        );
    }

    public function initialize(Controller $controller)
    {
        parent::initialize($controller);
        $this->controller = $controller;
        $this->_authorize();
    }

    public function user($field = null)
    {
        if ($this->Session->check('Auth.User.Id')) {
            return Auth::Instance()->user($field);
        } else {
            return false;
        }
    }

    public function permission($key = null)
    {
        if ($this->Session->check('Auth.User.Id')) {
            return Auth::Instance()->permission($key);
        } else {
            return false;
        }
    }

    public function is($what)
    {

        if ($this->Session->check('Auth.User.Id')) {
            return Auth::Instance()->is($what);
        } else {
            return false;
        }
    }

}
