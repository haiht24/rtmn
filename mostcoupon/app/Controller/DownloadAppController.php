<?php
 class DownloadAppController extends AppController {
     public $uses = ['DownloadApp'];
     public function index() {
         $docs = $this->mCusApi->resource('static_pages')->request('/getByDocKey', ['data' => ['doc_key' =>
             'downloadApp']]);
         $this->set('docs', $docs['docs']);
     }
 }
