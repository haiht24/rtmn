<?php

App::uses('CakeTime', 'Utility');
App::uses('AppController', 'Controller');

class UsersController extends AppController {

    public $uses = ['User', 'Permission'];

    public function index() {
        $users = $this->User->find('all', ["conditions" => ['group' => 'admin']]);
        $this->set('users', $users);
    }

    public function add() {
        $this->response->type('json');
        if ($this->request->is('post') && !empty($this->request->data)) {
            $response = ['status' => false, 'message' => null, 'user' => []];
            $propsToUnset = ['id', 'salt', 'token', 'group'];
            foreach ($propsToUnset as $field) {
                unset($this->request->data[$field]);
            }
            // check email
            $this->User->recursive = -1;
            $checkEmail = $this->User->findByEmail($this->request->data['email']);
            if(!empty($checkEmail)) {
                $response['message'] = 'Duplicate Email';
            } else {
                $this->request->data['token'] = md5(microtime());
                $this->request->data['group'] = 'admin';
                $user = $this->User->save($this->request->data);
                if(!$user) {
                    $response['message'] = $this->User->validationErrors;
                } else {
                    $response['status'] = true;
                    $user['user']['dashboardUrl'] = Configure::read('Url') .'users/login';
                    $user['user']['userPassword'] = $this->request->data('password');

                    $response['user'] = $user;
                    $to = $user['user']['email'];
                    $email = new CakeEmail('mcus');
                    $response['sendEmailStatus'] = $email->template('mostcoupon_welcome')
                    ->emailFormat('html')
                    ->from(['mostcoupon@gmail.com' => 'MostCoupon'])
                    ->to($to)
                    ->subject(__('Most Coupon - Welcome'))
                    ->viewVars(['user' => $user['user']])
                    ->send();
                }
            }
            $this->response->body(json_encode($response));
        }
        return $this->response;
    }

    public function activation($token) {
        $this->Session->delete('Auth');
        if (empty($token)) {
            $this->Session->setFlash('This link is invalid', 'default', [], 'error');
            $this->redirect(
                [
                    'language' => Configure::read('Config.language'),
                    'controller' => 'users',
                    'action' => 'login'
                ]
            );
        }
        try {
             $result = $this->activate($token);
            if (!empty($result)) {
                $this->Session->setFlash($result, 'default', [], 'success');
            }
        } catch (ApiServerException $e) {
            $this->Session->setFlash($e->message(), 'default', [], 'error');
        }
        $this->redirect(['controller' => 'users', 'action' => 'login']);
    }

    private function activate($token) {
        $this->User->recursive = -1;
        $user = $this->User->findByToken($token);
        if(empty($user)) {
            return 'Could not find user with token '.$token;
        }
        $user['user']['token'] = null;
        unset($user['user']['password']);
        $user['User']['status'] = 'active';
        if(!$this->User->save($user)) {
            throw new InvalidDataException($this->User->validationErrors);
        }
        return 'Your account has been activated. Please login to start using MostCoupon.';
    }

    public function passwordReset()
    {
        $this->layout = 'login';
        if ($email = $this->request->data('email')) {
            $user = $this->User->asAdmin()->find('first', ['email' => $email]);
            if (empty($user)) {
                $this->Session->setFlash(__('User with email %s not found', $email), 'default', [], 'error');
            } else {
                $this->User->asAdmin()->request('/' . $user['user']['id'] . '/password-reset', ['method' => 'POST']);
                $this->Session->setFlash(__('Reset password has been sent to your email.'), 'default', [], 'success');
                $this->redirect(
                    ['language' => Configure::read('Config.language'), 'controller' => 'users', 'action' => 'login']
                );
            }
        }
    }

    public function edit() {
        $this->response->type('json');
        if ($this->request->is('post')) {
            $editUser = $this->request->data;
            $response = ['status' => false, 'message' => null];
            $user = $this->User->save($editUser);
            if (!empty($editUser['password'])) {
                $this->Session->write('Auth.User.Password', $editUser['password']);
            }
            if(!$user) {
                $response['status'] = false;
                $response['message'] = $this->User->validationErrors;
            } else {
                if(isset($editUser['sendNewPwd'])){
                    $user['user']['newPwd'] = $editUser['password'];
                }
                $response['status'] = true;
                $response['user'] = $user;
                if(isset($editUser['password'])){
                    $this->emailPasswordChanged($user['user']);
                }

            }
            $this->response->body(json_encode($response));
         }
         return $this->response;
    }

    public function delete(){
        $this->response->type('json');
        $mode = $this->request->data('mode');
        if($mode == 'editMode'){
            $this->response->body(json_encode(true));
            return $this->response;
        }
        if($this->request->is('post')){
            $userID = $this->request->data['id'];
            $resp = $this->User->delete($userID);
            $this->response->body(json_encode($resp));
        }
        return $this->response;
    }

    public function logout()
    {
        $this->Cookie->destroy();
        $this->Session->destroy();
        $this->redirect(
            [
                'controller' => 'users',
                'action' => 'login'
            ]
        );
    }

    public function login() {
        $this->layout = 'login';
        if ($this->Session->check('Auth.User.Id')) {
            $this->redirect(
                    [
                        'controller' => 'home',
                        'action' => 'index'
                    ]
            );
            return;
        }

        if (empty($this->request->data)) {
            $this->Cookie->write('cookiesEnabled', '1', false);
            if ($this->Cookie->check('localUser.email') && $this->Cookie->check('localUser.password')) {
                $result = $this->User->find('first', [
                    'conditions' => ['email' => $this->Cookie->read('localUser.email'), 'password' => $this->Cookie->read('localUser.password')]
                ]);
                $result['user']['password'] = $this->Cookie->read('localUser.password');
                $this->loginUser($result['user']);
            }
            return;
        }

        $cookiesEnabled = $this->Cookie->read('cookiesEnabled');
        if (empty($cookiesEnabled)) {
            $this->set('cookiesDisabled', __('Please allow cookies to login'));
            return;
        }

        $user = $this->request->data;

        if (!empty($user['js_disabled'])) {
            $this->Session->setFlash(__('Your can not login without javascript'), 'default', [], 'error');
            $this->redirect(
                    [
                        'controller' => 'users',
                        'action' => 'login'
                    ]
            );
        }

        try {
            $result = $this->authenticate();
        } catch (ApiServerException $e) {
            $this->Session->setFlash(__('Incorrect email or password'), 'default', [], 'error');
            return;
        }
        if (empty($result['user'])) {
            $this->Session->setFlash(__('Incorrect email or password'), 'default', [], 'error');
            return;
        }
        if ($result['user']['status'] != 'active') {
            $message = __(
                    'Your account has not yet been activated. Please check your email to activate.
                    If you didn\'t receive any activation email, <a href="%s">click here</a> to request a new one',
                    Router::url(
                            [
                                'controller' => 'users',
                                'action' => 'activation-link'
                            ]
                    )
            );
            $this->Session->setFlash($message, 'default', [], 'error');
            return;
        } elseif ($result['user']['status'] == 'active' && !empty($user['remember'])) {
            $this->Cookie->write('localUser.email', $user['email']);
            $this->Cookie->write('localUser.password', $user['password']);
        }
        if (empty($user['remember']) && $this->Cookie->check('localUser.email')) {
            $this->Cookie->delete('localUser.email');
            $this->Cookie->delete('localUser.password');
        }
        $this->loginUser(array_merge($result['user'], $user));
    }

    // logs the user in by setting the required session entries and redirects to the landing page
    private function loginUser($user) {
        $this->Session->write('Auth.User.Id', $user['id']);
        $this->Session->write('Auth.User.Email', $user['email']);
        $this->Session->write('Auth.User.Password', $user['password']);

        if($user['first_login'] == 1){
            $this->redirect(['controller' => 'users', 'action' => 'activeAccount']);
        }

        try {
            $this->User->save(['id'=> $user['id'], 'last_login' => date('Y-m-d H:i:s', time())]);
            $this->redirect(
                    [
                        'controller' => 'home',
                        'action' => 'index'
                    ]
            );
        } catch (Exception $e) {
            $this->Session->delete('Auth');
            throw $e;
        }
    }

    public function authenticate() {
        $email = $this->request->data('email');
        $password = $this->request->data('password');
        $user = $this->User->find('first', [
            'conditions' => ['email' => $email, 'password' => $password]
        ]);
        return $user;
    }

    public function sessionData() {
        $result = [];
        if ($userId = $this->Session->check('Auth.User.Id')) {
            $this->User->virtualFields = ['number_of_accounts' => 'COUNT(DISTINCT(accounts.id))'];
            $user = $this->User->findById($userId);
            $result = $user;
            $result['permissions'] = $this->Permission->find('list', [
                'fields' => ['key', 'value'],
                'conditions' => ['role_type' => $user['User']['role']]
            ]);
        }
    }

    private function emailPasswordChanged($user){
        $email = new CakeEmail('mcus');
        $response['sendEmailStatus'] = $email->template('mostcoupon_password_change')
        ->emailFormat('html')
        ->from(['mostcoupon@gmail.com' => 'MostCoupon'])
        ->to($user['email'])
        ->subject(__('Most Coupon - Password Change'))
        ->viewVars(['user' => $user])
        ->send();
    }

    public function activeAccount(){
        $this->render('active_account');
    }

    public function doActiveAccount(){
        $this->response->type('json');
        if ($this->request->is('post')) {
            $findUser = $this->User->find('first',['conditions' => [
                'id' => $this->request->data('id'),
                'password' => $this->request->data('oldPassword'),
                'first_login' => 1
            ]]);
            if($findUser){
                $response['user'] = $this->User->save($this->request->data);
            }else{
                $response = false;
            }
            $this->response->body(json_encode($response));
         }
         return $this->response;
    }
}