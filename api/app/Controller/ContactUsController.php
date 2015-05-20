<?php
 App::uses('AppController', 'Controller');
 class ContactUsController extends AppController {
     var $uses = ['ContactUs'];
     public function index() {
     }
     public function save() {
         $data = [
            'name' => $this->getParam('name'),
            'email' => $this->getParam('email'),
            'subject' => $this->getParam('subject'),
            'keywords' => $this->getParam('keywords'),
            'message' => $this->getParam('message')
         ];
         $this->set(array('data' => $data, '_serialize' => array('data')));
     }
 }
