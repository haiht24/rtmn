<?php

class Store extends AppModel {

    public $cacheQueries = true;
    public $hasMany = [
        'Property' => [
            'className' => 'Property',
            'foreignKey' => 'foreign_key_left',
            'conditions' => ['Property.key' => 'store'],
            'fields' => ['foreign_key_right']
        ]
    ];
//    public $hasAndBelongsToMany = array('Category');
    public $jsonFields = array('categories_id');
//    public function afterFind($results = array(), $primary = false)
//    {
//        $results = parent::afterFind($results, $primary);
//        foreach ($results as $key => &$val) {
//            //get all locations
//            $val['related_stores'] = [];
//            if (!empty($val['Store']['related_id'])) {
//                $val['related_stores'] = $this->find('all',
//                    ['conditions' => ['id' => $val['Store']['related_id']],
//                        'fields' => ['id', 'name']]);
//            }
//        }
//        return $results;
//    }

    public function afterSave($created, $options) {
        if ($created) {
            $keyword = $this->getToken(6);
            $Property = ClassRegistry::init('Property');
            $Property->save([
                'foreign_key_left' => $this->data['Store']['id'],
                'foreign_key_right' => $keyword,
                'key' => 'store'
            ]);
        }
    }
}