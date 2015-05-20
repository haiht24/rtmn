<?php
 App::import('Controller', 'WpTermTaxonomy');
 class WpTermsController extends AppController {
     public function index() {
         $this->autoRender = false;
         $this->layout = 'default';
     }
     public function add() {
         $this->autoRender = false;
         $database = $this->request->data['database'];
         $this->WpTerm->useDbConfig = $database;
         $WpTermTaxonomy = new WpTermTaxonomyController;
         $WpTermTaxonomy->constructClasses();
         $this->WpTerm->create();
         // add to wp_terms
         $data = array('name' => trim($this->request->data['catName']), 'slug' => $this->WpTerm->toSlug($this->
                 request->data['catName']));
         if ($this->WpTerm->save($data)) {
             echo 'Added to wp_terms';
             $termId = $this->WpTerm->getInsertID();
             // add to wp_term_taxonomy
             $data = array('term_id' => $termId, 'taxonomy' => 'store_category');
             $WpTermTaxonomy->add($data, $database);
         } else {
             echo 'error';
         }
     }

 }
?>