<?php
/**
 * Created by PhpStorm.
 * User: Phuong
 * Date: 3/12/2015
 * Time: 10:06 AM
 */

class CategoriesStores extends AppModel{
    public $useTable = 'categories_stores';
    public $belongsTo = [
        'Category' => [
            'className' => 'Category',
            'foreignKey' => 'id'
        ],
        'Store' => [
            'className' => 'Store',
            'foreignKey' => 'id'
        ]
    ];
}