<?php
 class WpTerm extends AppModel {
//     public $useDbConfig = 'mcold';
     public $useTable = 'wp_terms';
     public function toSlug($string) {
         return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
     }


 }
