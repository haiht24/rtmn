<?php
 class AppTermsController extends AppController {
     public $uses = ['AppTerms'];
     public function index() {
         $docs = $this->mCusApi->resource('static_pages')->request('/getByDocKey', ['data' => ['doc_key' =>
             'appTerms']]);
         $this->set('docs', $docs['docs']);
     }
 }
