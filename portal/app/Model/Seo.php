<?php
 App::uses('AppModel', 'Model');
 class Seo extends AppModel {
     public $alias = 'Seo';
     public $useTable = 'options';
     public $primaryKey = 'option_id';
 }
