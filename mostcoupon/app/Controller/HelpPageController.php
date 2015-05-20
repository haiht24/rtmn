<?php
 class HelpPageController extends AppController {
     public $uses = ['Help'];
     public function index() {
         $docs = $this->mCusApi->resource('static_pages')->request('/getByDocKey', ['data' => ['doc_key' =>
             'help']]);
         $this->set('docs', $docs['docs']);
     }
 }
