<?php
 class PressCentreController extends AppController {
     public $uses = ['PressCentre'];
     public function index() {
         $docs = $this->mCusApi->resource('static_pages')->request('/getByDocKey', ['data' => ['doc_key' =>
             'pressCentre']]);
         $this->set('docs', $docs['docs']);
     }
 }
