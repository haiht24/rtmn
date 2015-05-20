<?php
 class StaticPagesController extends AppController {
     public $helper = array(
         'Html',
         'Form',
         'Javascript',
         'Ajax');
     var $components = array('RequestHandler');
     public $uses = ['StaticPage'];
     public function index() {
         $docs = $this->StaticPage->find('all');
         $this->set('docs', $docs);
     }
     public function calculate() {
         return $this->Store->find('count');
     }
     public function update() {
         $this->autoRender = false;
         if ($this->request->is('post')) {
             $postdata = file_get_contents("php://input");
             $request = json_decode($postdata);

             $key = $request->key;
             $value = $request->value;
             if ($key == 'skills') {
                 if (isset($value->skill_1)) {
                     $this->addToDB('skill_1', $value->skill_1);
                 }
                 if (isset($value->skill_2)) {
                     $this->addToDB('skill_2', $value->skill_2);
                 }
                 if (isset($value->skill_3)) {
                     $this->addToDB('skill_3', $value->skill_3);
                 }
                 if (isset($value->skill_4)) {
                     $this->addToDB('skill_4', $value->skill_4);
                 }
             } else {
                 $this->addToDB($key, $value);
             }
         }
     }
     private function addToDB($key, $value) {
         $countExistField = $this->StaticPage->find('count', ['conditions' => ['StaticPage.doc_key' => "$key"]]);
         if ($countExistField > 0) {
             // Update if existed field
             return $this->StaticPage->updateAll(array("doc_value" => "'$value'"), array("doc_key" => "$key"));
         } else {
             // Add new field
             $this->StaticPage->set(['doc_key' => "$key", 'doc_value' => "$value"]);
             return $this->StaticPage->save();
         }
     }
     public function downloadApp() {
         $docs = $this->StaticPage->find('all', [
                'conditions' => ['StaticPage.doc_key' => 'downloadApp']
            ]);
         $this->set('docs', $docs);
         $this->render('download_app');
     }
     public function pressCentre() {
         $docs = $this->StaticPage->find('all', [
                'conditions' => ['StaticPage.doc_key' => 'pressCentre']
            ]);
         $this->set('docs', $docs);
         $this->render('press_centre');
     }
     public function careers(){
     	 $docs = $this->StaticPage->find('all', [
                'conditions' => ['StaticPage.doc_key' => 'careers']
            ]);
         $this->set('docs', $docs);
         $this->render('careers');
     }
     public function help(){
     	 $docs = $this->StaticPage->find('all', [
                'conditions' => ['StaticPage.doc_key' => 'help']
            ]);
         $this->set('docs', $docs);
         $this->render('help');
     }
     public function terms(){
     	 $docs = $this->StaticPage->find('all', [
                'conditions' => ['StaticPage.doc_key' => 'terms']
            ]);
         $this->set('docs', $docs);
         $this->render('terms');
     }
     public function privacy(){
     	 $docs = $this->StaticPage->find('all', [
                'conditions' => ['StaticPage.doc_key' => 'privacy']
            ]);
         $this->set('docs', $docs);
         $this->render('privacy');
     }
     public function appTerms(){
     	 $docs = $this->StaticPage->find('all', [
                'conditions' => ['StaticPage.doc_key' => 'appTerms']
            ]);
         $this->set('docs', $docs);
         $this->render('app_terms');
     }
     public function competitionTerms(){
     	 $docs = $this->StaticPage->find('all', [
                'conditions' => ['StaticPage.doc_key' => 'competitionTerms']
            ]);
         $this->set('docs', $docs);
         $this->render('competition_terms');
     }
     public function directAdv(){
     	 $docs = $this->StaticPage->find('all', [
                'conditions' => ['StaticPage.doc_key' => 'directAdv']
            ]);
         $this->set('docs', $docs);
         $this->render('direct_adv');
     }
     public function contactUs(){
     	 $docs = $this->StaticPage->find('all', [
                'conditions' => ['StaticPage.doc_key' => 'contactUs']
            ]);
         $this->set('docs', $docs);
         $this->render('contact_us');
     }
     public function aboutCookies(){
         $docs = $this->StaticPage->find('all', [
                'conditions' => ['StaticPage.doc_key' => 'aboutCookies']
            ]);
         $this->set('docs', $docs);
         $this->render('about_cookies');
     }
 }
