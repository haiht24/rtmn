<?php
 class AboutUsController extends AppController {
     public $uses = ['AboutUs'];
     public function index() {
         $docs = $this->mCusApi->resource('static_pages')->request('/index');
         $stores = $this->mCusApi->resource('Store')->query(array('limit' => 15, 'status' => 'published', 'show_in_homepage' => 1));

         $this->set('docs', $docs['docs']);
         $this->set('stores', $stores);
     }
 }
