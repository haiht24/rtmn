<?php
 class TermsPageController extends AppController {
     public $uses = ['TermsPage'];
     public function index() {
         $docs = $this->mCusApi->resource('static_pages')->request('/getByDocKey', ['data' => ['doc_key' =>
             'terms']]);
         $this->set('docs', $docs['docs']);
     }
 }
