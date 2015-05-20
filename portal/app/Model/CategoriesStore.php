<?php
App::uses('AppModel', 'Model');
/**
 * Account Model
 *
 */
class CategoriesStore extends AppModel {
    public $alias = 'categoriesStore';
    public $useTable = 'categories_stores';
    var $belongsTo = [
//        'category' =>   [
//            'className' => 'Category',
//            'foreignKey' => 'category_id'
//        ],
        'store' => [
            'className' => 'Store',
            'foreignKey' => 'id'
        ]
    ];
}
