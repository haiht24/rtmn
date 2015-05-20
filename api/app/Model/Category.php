<?php

class Category extends AppModel
{
    public $cacheQueries = true;
//    public $hasAndBelongsToMany = array('Store');
//    public $hasMany = 'CategoriesStore';

//    public function afterFind($results = array(), $primary = false)
//    {
//        $results = parent::afterFind($results, $primary);
//        foreach ($results as $key => &$val) {
//            $val['store_count'] = 0;
//            if (!empty($val['Store'])) {
//                $val['store_count'] = sizeof($val['Store']);
//            }
//        }
//    }
}