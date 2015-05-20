<?php
 class PrivacyPolicyController extends AppController {
     public $uses = ['PrivacyPolicy'];
     public function index() {
         $docs = $this->mCusApi->resource('static_pages')->request('/getByDocKey', ['data' => ['doc_key' =>
             'privacy']]);
         $stores = $this->mCusApi->resource('Store')->query(array('limit' => 15, 'status' => 'published', 'show_in_homepage' => 1));
         $this->set('docs', $docs['docs']);
         $this->set('stores', $stores);
     }
 }
