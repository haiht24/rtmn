<?php
App::uses('AppModel', 'Model');

class Store extends AppModel
{
    public $alias = 'store';
    public $useTable = 'stores';
    public $cacheQueries = true;
    public $jsonFields = array('categories_id', 'related_id', 'countries_code');

    public $validate = array(
        'name' => array(
            'rule' => 'isUnique',
            'message' => 'This name already exists'
        )
    );

    public $belongsTo = [
        'author' => [
            'className' => 'User',
            'foreignKey' => 'user_id',
            'fields' => ['author.id', 'fullname', 'email', 'username']
        ]
    ];
    public $hasMany = [
        'vendors' => [
            'className' => 'Vendor',
            'foreignKey' => 'parent_id',
            'conditions' => ['table_name' => 'store'],
            'fields' => ['countrycode', 'description', 'affiliate_url', 'store_url', 'custom_keywords', 'best_store', 'show_in_homepage']
        ]
    ];

    public function afterFind($results = array(), $primary = false)
    {
        $results = parent::afterFind($results, $primary);
        foreach ($results as $key => &$val) {
            if (!empty($val[$this->alias]['categories_id'])) {
                $categories_id = $val[$this->alias]['categories_id'];
                $cateClass = ClassRegistry::init('Category');
                $cateClass->unbindAll();
                $val[$this->alias]['categories'] = $cateClass->find('all',
                    ['conditions' => ['category.id' => $categories_id],
                        'fields' => ['category.id', 'category.name']]);
            }
            //get all locations
            $val[$this->alias]['locations'] = [];
            if (!empty($val[$this->alias]['related_id'])) {
                $val[$this->alias]['locations'] = $this->find('all',
                    ['conditions' => ['store.id' => $val[$this->alias]['related_id']],
                        'fields' => ['store.id', 'store.name']]);
            }
//            if (!empty($val[$this->alias]['coupon_count'])) {
//                if ($val[$this->alias]['coupon_count'] == 0) {
//                    $coupon = ClassRegistry::init('Coupon');
//                    $coupon->unbindAll();
//                    $val[$this->alias]['coupons_count'] = $coupon->find('count', [
//                        'conditions' => ['store_id' => $val[$this->alias]['id']],
//                        'fields' => ['id']
//                    ]);
//                    if ($val[$this->alias]['coupons_count'] > 0)
//                        $this->save(['id' => $val[$this->alias]['id'], 'coupon_count' => $val[$this->alias]['coupons_count']]);
//                }
//            }
//            if (!empty($val[$this->alias]['deal_count'])) {
//                if ($val[$this->alias]['deal_count'] == 0) {
//                    $deal = ClassRegistry::init('Deal');
//                    $deal->unbindAll();
//                    $val[$this->alias]['deals_count'] = $deal->find('count', [
//                        'conditions' => ['store_id' => $val[$this->alias]['id']],
//                        'fields' => ['id']
//                    ]);
//                    if ($val[$this->alias]['deals_count'] > 0)
//                        $this->save(['id' => $val[$this->alias]['id'], 'deal_count' => $val[$this->alias]['deals_count']]);
//                }
//            }
        }
        return $results;
    }

    public function beforeSave($options = array())
    {
        if (empty($this->data['store']['alias']) && !empty($this->data['store']['name'])) {
            $this->data['store']['alias'] = Inflector::slug(
                $this->data['store']['name'], '-'
            );
        }
        return true;
    }

    public function afterSave($created, $options)
    {
        $keyword = $this->getToken(6);
        $Property = ClassRegistry::init('Property');
        $find = $Property->find('all', ['conditions' => [
            'foreign_key_left' => $this->data['store']['id'],
            'key' => 'store'
        ]]);
        if (empty($find)) {
            $Property->create();
            $Property->save([
                'foreign_key_left' => $this->data['store']['id'],
                'foreign_key_right' => $keyword,
                'key' => 'store'
            ]);
        }
    }

    function hasDeals($store_id)
    {
        $Deal = ClassRegistry::init('Deal');
        $Deal->unbindAll();
        $count = $Deal->find("count", [
            "conditions" => ["store_id" => $store_id]
        ]);
        if ($count == 0) {
            return false;
        } else {
            return true;
        }
    }

    function hasCoupons($store_id)
    {
        $Coupon = ClassRegistry::init('Coupon');
        $Coupon->unbindAll();
        $count = $Coupon->find("count", [
            "conditions" => ["store_id" => $store_id]
        ]);
        if ($count == 0) {
            return false;
        } else {
            return true;
        }
    }
}