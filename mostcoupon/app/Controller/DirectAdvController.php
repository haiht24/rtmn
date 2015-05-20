<?php
 class DirectAdvController extends AppController {
     public $uses = ['DirectAdv'];
     public function index() {
         $docs = $this->mCusApi->resource('static_pages')->request('/getByDocKey', ['data' => ['doc_key' =>
             'directAdv']]);
         $this->set('docs', $docs['docs']);
     }
 }
