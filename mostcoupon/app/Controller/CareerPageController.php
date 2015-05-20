<?php
 class CareerPageController extends AppController {
     public $uses = ['Careers'];
     public function index() {
         $docs = $this->mCusApi->resource('static_pages')->request('/getByDocKey', ['data' => ['doc_key' =>
             'careers']]);
         $this->set('docs', $docs['docs']);
     }
 }
