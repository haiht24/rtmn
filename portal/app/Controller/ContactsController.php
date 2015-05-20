<?php
 App::uses('AppController', 'Controller');
 App::uses('CakeEmail', 'Network/Email');
 class ContactsController extends AppController {
     public $helper = array(
         'Html',
         'Form',
         'Javascript',
         'Ajax');
     var $components = array('RequestHandler');

     public $uses = ['Contact'];
     public function index() {
         $this->set('contacts', $this->Contact->find('all'));
         $this->set('count', $this->Contact->find('count'));
     }
     public function delete($id) {
         $this->response->type('json');
         $this->Contact->delete(array('Contact.id' => $id));
         $response = ['status' => true, 'message' => null];
         $this->response->body(json_encode($response));
         return $this->response;
     }
     public function testSendMail() {
         $this->autoRender = false;
         $email = new CakeEmail('mcus');
         $resp = $email->template('default')->emailFormat('text')->from(array('info@5stars.vn' =>
                 'VietLancer'))->to('haiht369@gmail.com')->subject(__('mCus - activate your account'))->send();
         echo '<pre>';var_dump($resp);echo '</pre>'; die();
     }
 }
