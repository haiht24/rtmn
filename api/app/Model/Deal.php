<?php

class Deal extends AppModel
{
    public $cacheQueries = true;
    public $actsAs = array('Containable');
    public $belongsTo = [
        'Store' => [
            'className' => 'Store',
            'foreignKey' => 'store_id',
            'fields' => ['categories_id', 'countries_code', 'name', 'Store.id', 'alias', 'store_url']
        ],
        'User' => [
            'className' => 'User',
            'foreignKey' => 'user_id',
            'fields' => ['User.id', 'fullname', 'username']
        ]
    ];
    public $hasMany = [
        'Like' => [
            'className' => 'Like',
            'foreignKey' => 'object_id',
            'fields' => ['Like.id', 'value','Like.user_id'],
            'counterCache' => true,
        ],
        'Comment' => [
            'className' => 'Comment',
            'foreignKey' => 'deal_id',
            'counterCache' => true,
            'fields' => ['Comment.id', 'Comment.user_id', 'Comment.content','Comment.created'],
            'order' => ['Comment.created DESC'],
            'limit' => 10,
        ]
    ];
    public $jsonFields = array('categories_id');
    public $hasOne = [
        'Property' => [
            'className' => 'Property',
            'foreignKey' => 'foreign_key_left',
            'conditions' => array('Property.key' => 'deal'),
            'fields' => ['foreign_key_right']
        ]
    ];
//    public function afterFind($results = array(), $primary = false)
//    {
//        $results = parent::afterFind($results, $primary);
//        $Comment = ClassRegistry::init('Comment');
//        foreach ($results as $key => &$val) {
//            if (!empty($val['Deal']['id'])) {
//                $val['Comments'] = $Comment->find('all', [
//                    'conditions' => ['deal_id' => $val['Deal']['id']],
//                    'limit' => 10,
//                    'order' => 'Comment.created DESC']);
//            }
//        }
//        return $results;
//    }

    public function afterSave($created, $options) {
        if ($created) {
            $keyword = $this->getToken(6);
            $Property = ClassRegistry::init('Property');
            $Property->save([
                'foreign_key_left' => $this->data['Deal']['id'],
                'foreign_key_right' => $keyword,
                'key' => 'deal'
            ]);
        }
    }
}