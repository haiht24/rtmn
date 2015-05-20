<?php
App::uses('AppModel', 'Model');

class Deal extends AppModel
{
    public $alias = 'deal';
    public $useTable = 'deals';
    public $jsonFields = array('categories_id');
    public $cacheQueries = true;

//    public $hasOne = [
//        'Property' => [
//            'className' => 'Property',
//            'foreignKey' => 'foreign_key_left',
//            'conditions' => array('Property.key' => 'deal'),
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
        ]
    ];

    public $hasMany = [
        'vendors' => [
            'className' => 'Vendor',
            'foreignKey' => 'parent_id',
            'conditions' => ['table_name' => 'deal'],
            'fields' => ['countrycode', 'title', 'description']
        ]
    ];
//    public function afterFind($results = array(), $primary = false) {
//        $results = parent::afterFind($results, $primary);
//        return $results;
//    }

    public function afterSave($created, $options) {
        $keyword = $this->getToken(6);
        $Property = ClassRegistry::init('Property');
        $find = $Property->find('all', ['conditions' => [
            'foreign_key_left' => $this->data['deal']['id'],
            'key' => 'deal'
        ]]);
        if (empty($find)) {
            $Property->create();
            $Property->save([
                'foreign_key_left' => $this->data['deal']['id'],
                'foreign_key_right' => $keyword,
                'key' => 'deal'
            ]);
        }
    }
}