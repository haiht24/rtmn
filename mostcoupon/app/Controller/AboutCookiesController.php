<?php
 class AboutCookiesController extends AppController {
     public $uses = ['AboutCookies'];
     public function index() {
         $docs = $this->mCusApi->resource('static_pages')->request('/getByDocKey', ['data' => ['doc_key' =>
             'aboutCookies']]);
         $this->set('docs', $docs['docs']);
     }
 }
