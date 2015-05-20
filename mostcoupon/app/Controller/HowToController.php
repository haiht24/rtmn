<?php
 class HowToController extends AppController {
     public $uses = ['HowTo'];
     public function index() {
         $docs = $this->mCusApi->resource('static_pages')->request('/getByDocKey', ['data' => ['doc_key' =>
             'howToGuides']]);
         $this->set('docs', $docs['docs']);
     }
 }
