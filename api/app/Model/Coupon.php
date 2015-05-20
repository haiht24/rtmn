<?php

class Coupon extends AppModel
{
    public $cacheQueries = true;
    public $jsonFields = array('categories_id');
    public $belongsTo = [
        'Store' => [
            'className' => 'Store',
            'foreignKey' => 'store_id',
            'fields' => ['categories_id', 'countries_code', 'name', 'Store.id', 'alias', 'store_url','logo','social_image']
        ],
        'User' => [
            'className' => 'User',
            'foreignKey' => 'user_id',
            'fields' => ['User.id', 'fullname', 'username']
        ],
        'Event'
    ];
    public $hasOne = [
        'Property' => [
            'className' => 'Property',
            'foreignKey' => 'foreign_key_left',
            'conditions' => ['Property.key' => 'coupon'],
            'fields' => ['foreign_key_right']
        ],
    ];
    public $hasMany = [
        'Like' => [
            'className' => 'Like',
            'foreignKey' => 'object_id',
            'fields' => ['Like.id', 'value','Like.user_id'],
            'counterCache' => true,
        ],
        'Comments' => [
            'className' => 'Comment',
            'foreignKey' => 'coupon_id',
            'counterCache' => true,
            'fields' => ['Comments.id', 'Comments.user_id', 'Comments.content','Comments.created'],
            'order' => ['Comments.created DESC'],
            'limit' => 10,
        ]
    ];
//    public function afterFind($results = array(), $primary = false)
//    {
//        $results = parent::afterFind($results, $primary);
//        $Comment = ClassRegistry::init('Comment');
//        foreach ($results as $key => &$val) {
//            if (!empty($val['Coupon']['id'])) {
//                $val['Comments'] = $Comment->find('all', [
//                    'conditions' => ['coupon_id' => $val['Coupon']['id']],
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
                'foreign_key_left' => $this->data['Coupon']['id'],
                'foreign_key_right' => $keyword,
                'key' => 'coupon'
            ]);
        }
    }
}