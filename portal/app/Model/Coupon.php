<?php
App::uses('AppModel', 'Model');

class Coupon extends AppModel
{
    public $alias = 'coupon';
    public $useTable = 'coupons';
    public $jsonFields = array('categories_id');
    public $cacheQueries = true;

//    public $hasOne = [
//        'Property' => [
//            'className' => 'Property',
//            'foreignKey' => 'foreign_key_left',
//            'conditions' => array('Property.key' => 'coupon'),
//            'fields' => ['foreign_key_right']
//        ]
//    ];

    public $belongsTo = [
        'author' => [
            'className' => 'User',
            'foreignKey' => 'user_id',
            'fields' => ['author.id', 'fullname', 'email', 'username']
        ],
        'store' => [
            'className' => 'Store',
            'foreignKey' => 'store_id',
            'counterCache' => true,
            'fields' => ['categories_id', 'countries_code', 'name', 'store.id']
        ],
        'event' => [
            'className' => 'Event',
            'foreignKey' => 'event_id',
            'conditions' => ['Not' => ['event_id' => null]],
            'fields' => ['event.id', 'event.name']
        ]
    ];

    public $hasMany = [
        'vendors' => [
            'className' => 'Vendor',
            'foreignKey' => 'parent_id',
            'conditions' => ['table_name' => 'coupon'],
            'fields' => ['countrycode', 'title', 'description', 'vendors.sticky', 'vendors.event_id', 'vendors.event_name']
        ]
    ];
//    public $hasOne = array(
//        'eventObj' => array(
//            'className' => 'Event',
//            'foreignKey' => false,
//            'conditions' => array('coupon.event_id = eventObj.id')  
//        ));

//    public function afterFind($results = array(), $primary = false)
//    {
//        $results = parent::afterFind($results, $primary);
//        return $results;
//    }

    public function afterSave($created, $options)
    {
        $keyword = $this->getToken(6);
        $Property = ClassRegistry::init('Property');
        $find = $Property->find('all', ['conditions' => [
            'foreign_key_left' => $this->data['coupon']['id'],
            'key' => 'coupon'
        ]]);
        if (empty($find)) {
            $Property->create();
            $Property->save([
                'foreign_key_left' => $this->data['coupon']['id'],
                'foreign_key_right' => $keyword,
                'key' => 'coupon'
            ]);
        }
    }
}