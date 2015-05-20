<?php
 class WpPostmetaController extends AppController {
     var $uses = array('WpPostmeta');
     public function index() {
         $this->autoRender = false;
         $this->layout = 'default';
     }
     public function add($data, $database) {
         $this->autoRender = false;
         $this->WpPostmeta->useDbConfig = $database;
         $this->WpPostmeta->create();

         if ($this->WpPostmeta->saveMany($data, array('deep' => true))) {
             echo $this->WpPostmeta->getInsertID() . '|';
         } else {
             echo 'error';
         }
     }
     public function checkExistMeta($post_id, $meta_key, $database){
        $this->WpPostmeta->useDbConfig = $database;
     	$rs = $this->WpPostmeta->query("select * from wp_postmeta where post_id = {$post_id} and meta_key = '$meta_key'");
        return count($rs);
     }
 }
?>