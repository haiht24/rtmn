<?php
 class CompetitionTermsController extends AppController {
     public $uses = ['CompetitionTerms'];
     public function index() {
         $docs = $this->mCusApi->resource('static_pages')->request('/getByDocKey', ['data' => ['doc_key' =>
             'competitionTerms']]);
         $this->set('docs', $docs['docs']);
     }
 }
