<?php
 App::uses('AppController', 'Controller');
 class StaticPagesController extends AppController {
     var $uses = ['StaticPage'];
     public function index() {
         $docs = $this->StaticPage->find('all');
         $this->set(array('docs' => $docs, '_serialize' => array('docs')));
     }
     public function getByDocKey() {
         $docs = $this->StaticPage->find('all', ['conditions' => ['StaticPage.doc_key' => $this->getParam('doc_key')]]);
         $this->set(array('docs' => $docs, '_serialize' => array('docs')));
     }
 }
