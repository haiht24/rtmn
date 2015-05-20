<?php
/**
 * Created by PhpStorm.
 * User: Phuong
 * Date: 5/13/2015
 * Time: 4:50 PM
 */

class Vendor extends AppModel{
    public $belongsTo = [
        'store' => [
            'className' => 'Store',
            'foreignKey' => 'parent_id',
            'conditions' => ['table_name' => 'store'],
            'counterCache' => true,
        ],
        'coupon' => [
            'className' => 'Coupon',
            'foreignKey' => 'parent_id',
            'conditions' => ['table_name' => 'coupon'],
            'counterCache' => true,
        ]
    ];
}