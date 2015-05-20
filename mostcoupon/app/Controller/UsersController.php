<?php
 App::uses('Checkout', 'Lib');
 class UsersController extends AppController {
     public $components = array('Session');
     public $helper = ['Html'];
     //basic function.
     private function __setupUserAndAccountFacebook($user, $fbId = '') {
         $this->Session->write('Auth.User.Email', $user['email']);
         $this->Session->write('Auth.User.Password', Configure::read('facebookPassword'));
         $this->Session->write('Auth.User.Id', $user['id']);

         if ($fbId) {
             $result = $this->mCusApi->resource('User')->edit($this->Session->read('Auth.User.Id'), array('data' =>
                     array('facebook_id' => $fbId)));
         }
         $this->Permission->reset();
     }
     public function facebook() {
         $response = array('status' => 'error', 'message' => __('No facebook id provide', true));
         if (!empty($this->request->data)) {
             $facebookUser = $this->mCusApi->resource('User')->request('/viewByFacebookId', array('data' => array
                     ('facebook_id' => $this->request->data['id'])));
             $message1 = "Your account has not yet been activated. Please check your email to activate. If you didn't receive any activation email,";
             $message2 = "click here";
             $message3 = "to request a new one.";
             $message = $message1 . " <a href='" . $this->base . '/users/forgotactivelink' . "'>" . $message2 .
                 "</a> " . $message3;
             if (!empty($facebookUser['user']['User'])) {

                 $user = $facebookUser['user']['User'];
                 if ($user['status'] != 'active') {
                     $response['status'] = 'error';
                     $response['message'] = $message;
                 } else {
                     $response['status'] = 'success';
                     $this->__setupUserAndAccountFacebook($user);
                 }
             } else {
                 $emailUser = $this->mCusApi->resource('User')->request('/viewByFacebookEmail', array('data' => array
                         ('email' => $this->request->data['email'])));
                 if (!empty($emailUser['user']['User'])) {
                     $user = $emailUser['user']['User'];
                     if ($user['status'] != 'active') {
                         $response['status'] = 'error';
                         $response['message'] = $message;
                         ;
                     } else {
                         $response['status'] = 'success';
                         $this->__setupUserAndAccountFacebook($user, $this->request->data['id']);
                     }
                 } else {
                     $response['status'] = 'nonExistFacebookId';
                     $response['message'] =
                         'Do you want to create a new Feedbackstr account with your existing Facebook Login? Then click "OK". If you already have an existing Feedbackstr account you can connect it to Facebook in your user settings.';
                 }
             }
         }
         die(json_encode($response));
     }
     public function mapFacebookToUser() {
         if (!empty($this->request->data)) {
             $facebookUser = $this->mCusApi->resource('User')->request('/viewByFacebookId', array('data' => array
                     ('facebook_id' => $this->request->data['facebook_id'])));
             if (empty($facebookUser['user']['User'])) {
                 $result = $this->mCusApi->resource('User')->edit($this->Session->read('Auth.User.Id'), array('data' =>
                         array('facebook_id' => $this->request->data['facebook_id'])));
                 if (!empty($result['user']['User'])) {
                     $this->Permission->reset();
                     die(json_encode(array(
                         'status' => 'success',
                         'message' => 'Successfully linked your Facebook account with your Feedbackstr account.',
                         )));
                 } else {
                     die(json_encode(array('status' => 'error', 'message' =>
                             'Can not map this Facebook account with this user.')));
                 }
             } else {
                 die(json_encode(array('status' => 'error', 'message' =>
                         'This Facebook account already map with other user.')));
             }
         } else {
             die(json_encode(array('status' => 'error', 'message' => 'No FacebookId provided.')));
         }
     }

     public function logout() {
         $this->Session->destroy();
         $this->redirect(array('controller' => 'home', 'action' => 'index'));
     }

     public function register() {
        $this->response->type('json');
        $username = $this->request->data('username');
        $email = $this->request->data('email');
        $check = $this->checkExistUsername($username, $email);
        $check = json_decode($check['data']);

        $resp['check'] = $check;
        if(isset($check->duplicate)){
            $resp['duplicate'] = $check->duplicate;
            $this->response->body(json_encode($resp));
            return $this->response;
        }else{
            $userData =
            [
                'username' => $username,
                'email' => $email,
                'pwd' => $this->request->data('pwd'),
                'status' => 'inactive',
                'registFrom' => 'form'
            ];
            $resp['user'] = $this->mCusApi->resource('User')->add($userData);
            $this->response->body(json_encode($resp));
            return $this->response;
        }
     }

     public function registFromSocial(){
        $this->response->type('json');

        $username = $this->request->data['username'];
        $email = $this->request->data['email'];
        $check = $this->checkExistUsername($username, $email);
        $check = json_decode($check['data']);
        $resp['check'] = $check;
        // if exist email => logged in, write user info to Session
        if(isset($check->duplicate) && $check->duplicate == 'email'){
            $user = $this->mCusApi->resource('User')->request('/getUserByEmail', ['data' => ['email' => $email]]);

            $decodeUser = json_decode($user['user']);
            $resp['user'] = $user;
            $resp['userStatus'] = $decodeUser->User->status;

            $this->Session->write('User.email', $decodeUser->User->email);
            if($decodeUser->User->fullname){
                $this->Session->write('User.fullname', $decodeUser->User->fullname);
            }else{
                $this->Session->write('User.fullname', $decodeUser->User->email);
            }
            $this->Session->write('User.id', $decodeUser->User->id);
            $this->Session->write('User.status', $decodeUser->User->status);
        }else{ // Reg new user
            $userData = [
                 'fullname' => $this->request->data['fullname'],
                 'username' => $username,
                 'email' => $email,
                 'facebook_id' => $this->request->data['FbID'],
                 'status' => 'active',
                 'registFrom' => $this->request->data['registFrom']
            ];
            $resp['user'] = $this->mCusApi->resource('User')->add($userData);
            // if add new user success => write user info to Session
            if($resp['user'] != 'false'){
                $decodeUser = json_decode($resp['user']['data']);
                $resp['userStatus'] = $decodeUser->User->status;
                $this->Session->write('User.email', $decodeUser->User->email);
                $this->Session->write('User.fullname', $decodeUser->User->fullname);
                $this->Session->write('User.id', $decodeUser->User->id);
                $this->Session->write('User.status', $decodeUser->User->status);
            }
        }
        $this->response->body(json_encode($resp));
        return $this->response;
     }

     public function login(){
        //$this->response->type('json');
        $username = $this->request->data['username'];
        $password = $this->request->data['password'];
        $userInfo = $this->mCusApi->resource('User')->request('/AuthLogin', ['data' => ['username' => $username, 'password' => $password]]);
        $userInfo = json_decode($userInfo['data']);
        $response = [];
        if($userInfo){
            $response['id'] = $userInfo->User->id;
            $response['username'] = $userInfo->User->username;
            $response['status'] = $userInfo->User->status;
            if($response['status'] == 'active' || $response['status'] == 'lock'){
                $this->Session->write('User.id', $userInfo->User->id);
                $this->Session->write('User.username', $userInfo->User->username);
                $this->Session->write('User.status', $userInfo->User->status);
            }
        }
        $this->response->body(json_encode($response));
        return $this->response;
     }

     public function reSendActiveEmail(){
        $this->response->type('json');
        $response = [];
        if(!isset($this->request->data['email'])){
            $response['message'] = 'Request email not found';
        }else{
            $email = $this->request->data['email'];
            $response = $this->mCusApi->resource('User')->request('/reSendActiveEmail', ['data' => ['email' => $email]]);
        }
        $this->response->body(json_encode($response));
        return $this->response;
     }

     public function forgotPassword(){
        $this->response->type('json');
        $response = [];
        if(!isset($this->request->data['email'])){
            $response['message'] = 'Request email not found';
        }else{
            $email = $this->request->data['email'];
            $response = $this->mCusApi->resource('User')->request('/forgotPassword', ['data' => ['email' => $email]]);
        }
        $this->response->body(json_encode($response));
        return $this->response;
     }

     public function resetpassword(){
        $this->render('ForgotPassword/index');
     }

     public function actionResetPassword(){
        $this->response->type('json');
        $response = [];
        $password = $this->request->data['password'];
        $token = $this->request->data['token'];
        $response = $this->mCusApi->resource('User')->request('/actionResetPassword', ['data' => ['password' => $password, 'token' => $token]]);

        $this->response->body(json_encode($response));
        return $this->response;
     }

     private function checkExistUsername($username = '', $email = ''){
        if($username){
            $data = ['username' => $username, 'email' => $email];
            $response = $this->mCusApi->resource('User')->request('/checkExistUsername', ['data' => $data]);
        }else{
            $response = false;
        }
        return $response;
     }

    public function activation()
    {
        $this->response->type('json');
        $resp = 'Access denied! Empty token';
        if (isset($this->params['pass'])) {
            $token = $this->params['pass'];
            $resp = $this->mCusApi->resource('User')->request('/activation', ['data' => ['token' => $token]]);
        }
        $resp = json_decode($resp['data']);
        if (isset($resp->User->modified)) {
            //die("Your email has been update successfully. From now on you can login with your new email.<br/>");
            $this->Session->setFlash('Your email has been update successfully. From now on you can login with your new email', 'default', array(), 'success');
            $this->redirect(['controller' => 'home', 'action' => 'index']);
        } else {
            die('Invalid token!');
        }

        $this->response->body(json_encode($resp));
        return $this->response;
    }
 }

