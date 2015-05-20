<?php
 App::uses('AppController', 'Controller');
 class ContactUsesController extends AppController {
     var $uses = ['ContactUs', 'StaticPage'];
     public function index() {

     }
     public function save() {
         //$this->autoRender = false;
         $data = [
            'name' => $this->getParam('name'),
            'email' => $this->getParam('email'),
            'subject' => $this->getParam('subject'),
            'keywords' => $this->getParam('keywords'),
            'message' => $this->getParam('message'),
            'sendtime' => $this->getParam('sendtime')
         ];

         $resp = $this->ContactUs->save($data);
         $this->set([
            'data' => json_encode($resp),
            '_serialize' => ['data']
         ]);
     }
 }
