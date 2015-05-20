<?php
    App::uses('AppController', 'Controller');
    App::uses('CakeEmail', 'Network/Email');
    class UsersController extends AppController {
        var $uses = ['User', 'Property'];

        public function index() {
            $user = $this->authenticate();
            if (!$user) {
                throw new NotAllowedRequestException();
            }
            $options = $this->User->buildOptions($this->params->query);

            if (isset($this->params->query['conditions'])) {
                $options['conditions'] = $this->params->query['conditions'];
            }
            if ($user['User']['group'] != 'admin') {
               $options['conditions']['User.id'] = $user['User']['id'];
            }

            $options['recursive'] = -1;

            $users = $this->User->find('all', $options);
            $this->set(array(
                'users' => $users,
                '_serialize' => array('users')
            ));
        }

        public function emailActiveAccount($userData = [], $emailConfig = 'mcus')  {
            $token = '';
            $password = '';
            if(isset($userData['token'])){
                $token = $userData['token'];
            }
            if(isset($userData['password'])){
                $password = $userData['password'];
            }
            $data = [];
            $data['token'] = $token;
            $data['password'] = $password;

            $email = new CakeEmail($emailConfig);
            $email->template('mcus_active_account')
                ->emailFormat('html')
                ->from(['mostcoupon@gmail.com' => 'MostCoupon'])
                ->to($userData['email'])
                ->subject('MostCoupon - Active Your Account')
                ->viewVars(['user' => $data])
                ->send();
            $this->set([
                'users' => [],
                '_serialize' => ['users']
            ]);
        }

        public function emailForgotPassword($userData = [], $emailConfig = 'mcus')  {
            $email = new CakeEmail($emailConfig);
            $email->template('mcus_forgot_password')
                ->emailFormat('html')
                ->from(['mostcoupon@gmail.com' => 'MostCoupon'])
                ->to($userData['email'])
                ->subject('MostCoupon - Reset password')
                ->viewVars(['user' => ['token' => $userData['token']]])
                ->send();
            $this->set([
                'users' => [],
                '_serialize' => ['users']
            ]);
        }

        public function emailWelcome($userData = [], $emailConfig = 'mcus'){
            $email = new CakeEmail($emailConfig);
            $email->template('mcus_welcome')
                ->emailFormat('html')
                ->from(['mostcoupon@gmail.com' => 'MostCoupon'])
                ->to($userData['email'])
                ->subject('MostCoupon - Welcome')
                ->viewVars([])
                ->send();
            $this->set([
                'users' => [],
                '_serialize' => ['users']
            ]);
        }

        public function emailPasswordChanged($userData = [], $emailConfig = 'mcus'){
            $email = new CakeEmail($emailConfig);
            $email->template('mcus_password_changed')
                ->emailFormat('html')
                ->from(['mostcoupon@gmail.com' => 'MostCoupon'])
                ->to($userData['email'])
                ->subject('MostCoupon - Password Changed')
                ->viewVars([])
                ->send();
            $this->set([
                'users' => [],
                '_serialize' => ['users']
            ]);
        }

        public function add() {
            if($this->getParam('registFrom') != 'form'){ // Regist from social
                // Generate a random password
                $randomPwd = $this->User->generatePassword();
                $userData = [
                    'fullname' => $this->getParam('fullname'),
                    'username' => $this->getParam('username'),
                    'email' => $this->getParam('email'),
                    'facebook_id' => $this->getParam('facebook_id'),
                    'status' => $this->getParam('status'),
                    'password' => $randomPwd
                ];
            }else if($this->getParam('registFrom') == 'form'){ // Regist from form
                $password = $this->getParam('pwd');
                $userData = [
                    'username' => $this->getParam('username'),
                    'email' => $this->getParam('email'),
                    'password' => $password,
                    'status' => $this->getParam('status')
                ];
            }else if($this->getParam('registFrom') == 'subscribe'){
                $userData = [
                    'email' => $this->getParam('email')
                ];
            }
            //send email
            if($userData['status'] == 'inactive'){
                $userData['token'] = $this->User->generateToken();
                $this->emailActiveAccount($userData);
            }else if($userData['status'] == 'active'){
                $this->emailWelcome(['email' => $userData['email']]);
            }

            $resp = $this->User->save($userData);
            if(!$resp){
                $resp = $this->User->find('first',['conditions' => ['email' => $this->getParam('email')]]);
            }
            $this->set(['data' => json_encode($resp), '_serialize' => ['data']]);
        }

        public function addFromSubscribe(){
            $email = $this->getParam('email');
            $key = $this->getParam('key');
            $foreign_key_right = $this->getParam('foreign_key_right');

            // save email to users table
            $user = $this->User->save(['email' => $email, 'subscribed' => 1]);
            $resp['saveUser'] = $user;
            if(!isset($user['User']['id'])){
                $findUser = $this->User->find('first', ['conditions' => ['email' => $email]]);
                $resp['findUser'] = $findUser;
                $uID = $findUser['User']['id'];
                // update value "subscribed"
                $changeUserSubscribed = $this->User->save(['id' => $uID, 'subscribed' => 1]);
                $resp['changeStatusSubscribed'] = $changeUserSubscribed;
            }else{
                $uID = $user['User']['id'];
            }
            // save uID to properties table
            $checkExist = $this->Property->find('count', ['conditions' =>
            ['foreign_key_left' => $uID, 'key' => $key, 'foreign_key_right' => $foreign_key_right]]);
            if($checkExist == 0){
                $properties = $this->Property->save(['foreign_key_left' => $uID, 'key' => $key, 'foreign_key_right' => $foreign_key_right]);
                $resp['saveProperties'] = $properties;
            }
            $this->set(['data' => json_encode($resp), '_serialize' => ['data']]);
        }

        public function checkExistUsername(){
            $username = $this->getParam('username');
            $email = $this->getParam('email');

            if($email){
                $foundUser = $this->User->find('first', ['conditions' => ['email' => $email]]);
                if($foundUser){
                    $resp['result'] = 'not allow';
                    $resp['duplicate'] = 'email';
                    $resp['userStatus'] = $foundUser['User']['status'];
                }else{
                    $resp['result'] = 'allow';
                }
            }
            if($username){
                $foundUser = $this->User->find('first', ['conditions' => ['username' => $username]]);
                if($foundUser){
                    $resp['result'] = 'not allow';
                    $resp['duplicate'] = 'username';
                    $resp['userStatus'] = $foundUser['User']['status'];
                }else{
                    $resp['result'] = 'allow';
                }
            }
            $this->set(['data' => json_encode($resp), '_serialize' => ['data']]);
        }

        public function edit($id) {
            if (!empty($this->request->data)) {

                $check = $this->authenticate();
                if(!$check) {
                    throw new NotAllowedRequestException();
                }

                $postData = $this->request->data;
                $user = (isset($postData['User']))? $postData['User'] : $postData;

                $user['id'] = $id;

                // unset email to prevent updating it directly but only if e-mail confirmation needed
                unset($user['email']);

                //for change password.
                if (!empty($user['new_password'])) {
                    $user['password'] = $user['new_password'];
                }

                //for change email.
                $changeEmail = false;
                if (!empty($user['temp_email'])) {
                    $changeEmail = true;
                    $user['email_temp'] = $user['temp_email'];
                    $user['token'] = md5(microtime());
                    if (empty($user['language'])) {
                        $user['language'] =  'en';
                    }
                }

                $this->User->unbindAll();

                if($user = $this->User->save($user)) {
                    $this->set(array(
                        'user' => $user,
                        '_serialize' => array('user')
                    ));
                } else {
                    throw new InvalidDataException($this->User->validationErrors);
                }
            } else {
                throw new MissingDataException('No user data provided');
            }
        }

        public function view($id) {
            $user = $this->authenticate();
            if(!$user) {
                throw new NotAllowedRequestException();
            }
            if($user['User']['id'] != $id) {
                $this->User->recursive =  -1;
                $user = $this->User->findById($id);
            }
            $this->set(array(
                'user' => $user,
                '_serialize' => array('user')
            ));
        }

        /**
        * Special actions needed to login and activate an account
        */
        public function auth() {
            try {
                $user = $this->authenticate();
            } catch (IncorrectCredentialsException $e) {
                $user = false;
            }
            $this->set(array(
                'user' => $user,
                '_serialize' => array('user')
            ));
        }

        public function viewByToken() {
            $this->User->unbindAll();
            $this->set(array(
                'user' => $this->User->findByToken($this->getParam('token')),
                '_serialize' => array('user')
            ));
        }

        public function viewByFacebookId() {
            $this->User->unbindAll();
            $this->set(array(
                'user' => $this->User->findByFacebookId($this->getParam('facebook_id')),
                '_serialize' => array('user')
            ));
        }

        public function viewByFacebookEmail() {
           $this->User->recursive = -1;
           $this->set(array(
                'user' => $this->User->findByEmail($this->getParam('email')),
                '_serialize' => array('user')
            ));
        }

        public function delete($id) {
          //TODO admin group to delete.
        }

        public function checkExistEmail(){
            $resp = $this->User->find('first', ['conditions' => ['User.email' => $this->getParam('email')]]);
            $this->set(['data' => json_encode($resp), '_serialize' => ['data']]);
        }

        public function AuthLogin(){
            //,'User.password' => $this->getParam('password')
            $conditions = [
                'OR' => [
                    ['User.username' => $this->getParam('username')],
                    ['User.email' => $this->getParam('username')]
                ],
                'User.password' => $this->getParam('password')
            ];
            $resp = $this->User->find('first', ['conditions' => $conditions]);
            $this->set(['data' => json_encode($resp), '_serialize' => ['data']]);
        }

        public function activation(){
            $userObj = $this->User->find('first', ['conditions' => ['User.token' => $this->getParam('token'), 'User.status' => 'inactive']]);
            $resp = [];
            if(isset($userObj['User']['id'])){
                $data = [
                    'id' => $userObj['User']['id'],
                    'token' => '',
                    'status' => 'active'
                ];
                $resp = $this->User->save($data);
                $this->emailWelcome(['email' => $userObj['User']['email']]);
            }
            $this->set(['data' => json_encode($resp), '_serialize' => ['data']]);
        }

        public function reSendActiveEmail(){
            $email = $this->getParam('email');
            $userObj = $this->User->find('first', ['conditions' => ['email' => $email]]);
            $resp = 'Email not found';
            if(isset($userObj['User']['id'])){
                $userData = [];
                $userData['email'] = $userObj['User']['email'];
                $userData['token'] = $userObj['User']['token'];
                $resp = $this->emailActiveAccount($userData);
            }
            $this->set(['data' => json_encode($resp), '_serialize' => ['data']]);
        }

        public function forgotPassword(){
            $email = $this->getParam('email');
            $token = $this->User->generateToken();
            $userObj = $this->User->find('first', ['conditions' => ['email' => $email]]);
            if($userObj){
                $userData = [
                    'id' => $userObj['User']['id'],
                    'token' => $token,
                    'email' => $email
                ];
                $this->User->save($userData);
                $resp = $this->emailForgotPassword($userData);
            }else{
                $resp = 'Your email not found in our system';
            }

            $this->set(['data' => json_encode($resp), '_serialize' => ['data']]);
        }

        public function actionResetPassword(){
            $newPassword = $this->getParam('password');
            $token = $this->getParam('token');
            $userObj = $this->User->find('first', ['conditions' => ['token' => $token]]);
            if($userObj){
                $userData = [
                    'id' => $userObj['User']['id'],
                    'password' => $newPassword,
                    'token' => ''
                ];
                $resp = $this->User->save($userData);
                $this->emailPasswordChanged(['email' => $userObj['User']['email']]);
            }else{
                $resp = 'Token not found';
            }
            // Send email password changed

            //
            $this->set(['data' => json_encode($resp), '_serialize' => ['data']]);
        }

        public function getUserByEmail(){
            $user = $this->User->find('first', ['conditions' => ['email' => $this->getParam('email')]]);
            $this->set(['user' => json_encode($user), '_serialize' => ['user']]);
        }
    }
?>