<?php
 class WpTermTaxonomyController extends AppController {
     var $uses = array('WpTermTaxonomy');
     public function index() {
         $this->autoRender = false;
         $this->layout = 'default';
     }
     public function add($data, $database) {
         $this->autoRender = false;
         $this->WpTermTaxonomy->useDbConfig = $database;
         $this->WpTermTaxonomy->create();
         if ($this->WpTermTaxonomy->save($data)) {
             echo 'Added to wp_term_taxonomy';
             echo $this->WpTermTaxonomy->getInsertID();
         } else {
             echo 'error';
         }
     }
 }
?>