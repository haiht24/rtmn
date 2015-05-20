<?php
 class WpOptionsController extends AppController {
     var $uses = array('WpOption');
     public function index() {
         $this->autoRender = false;
         $this->layout = 'default';
     }
     public function add($database) {
         $this->autoRender = false;
         $this->WpOption->useDbConfig = $database;
         $this->WpOption->create();
         $this->WpOption->set(array('option_name' => $this->request->data['optionName'], 'option_value' => $this->request->data['optionValue'], 'autoload' => 'no'));
         if ($this->WpOption->save()) {
             echo 'Save option successful. option_id saved: ';
             echo $this->WpOption->getInsertID();
         } else {
             echo 'error';
         }
     }
 }
?>