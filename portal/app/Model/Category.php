<?php
App::uses('AppModel', 'Model');

class Category extends AppModel
{
    public $alias = 'category';
    public $useTable = 'categories';
    public $cacheQueries = true;

    public $hasOne = [
        'father' => [
            'className' => 'Father',
            'foreignKey' => false,
            'conditions' => ['category.parent_id = father.id', 'NOT' => ['category.parent_id' => null]]
        ]];
    public $belongsTo = [
        'author' => [
            'className' => 'User',
            'foreignKey' => 'user_id',
            'fields' => ['author.id', 'fullname', 'email', 'username']
        ]
    ];
//    public $hasMany = [
//        'categoriesStores' => [
//            'className' => 'categoriesStore',
//            'dependent' => true
//        ]
//    ];

    public function afterFind($results = array(), $primary = false)
    {
        $results = parent::afterFind($results, $primary);
        foreach ($results as $key => &$val) {
            if (!empty($val[$this->alias]['store_count']) && $val[$this->alias]['store_count'] == 0) {
                $store = ClassRegistry::init('Store');
                $store->unbindAll();
                $store_count = $store->find('count', [
                    'conditions' => ["store.categories_id like '%" . $val[$this->alias]['id'] . "%'"],
                    'fields' => ['store.id']
                ]);
                $val[$this->alias]['store_count'] = $store_count;
            } elseif (empty($val[$this->alias]['store_count'])) {
                $store = ClassRegistry::init('Store');
                $store->unbindAll();
                $store_count = $store->find('count', [
                    'conditions' => ["store.categories_id like '%" . $val[$this->alias]['id'] . "%'"],
                    'fields' => ['store.id']
                ]);
                $val[$this->alias]['store_count'] = $store_count;
                $this->save(['id' => $val[$this->alias]['id'], 'store_count' => $store_count]);
            }
        }
        return $results;
    }

    public function beforeSave($options = array())
    {
        if (empty($this->data['category']['alias']) && !empty($this->data['category']['name'])) {
            $this->data['category']['alias'] = Inflector::slug(
                $this->data['category']['name'], '-'
            );
        }
        return true;
    }

    function hasStores($category_id)
    {
        $Store = ClassRegistry::init('Store');
        $Store->unbindAll();
        $count = $Store->find("count", [
            "conditions" => ["categories_id like '%" . $category_id . "%'"]
        ]);
        if ($count == 0) {
            return false;
        } else {
            return true;
        }
    }

    function hasDeals($category_id)
    {
        $Deal = ClassRegistry::init('Deal');
        $Deal->unbindAll();
        $count = $Deal->find("count", [
            "conditions" => ["categories_id like '%" . $category_id . "%'"]
        ]);
        if ($count == 0) {
            return false;
        } else {
            return true;
        }
    }

    function hasCoupons($category_id)
    {
        $Coupon = ClassRegistry::init('Coupon');
        $Coupon->unbindAll();
        $count = $Coupon->find("count", [
            "conditions" => ["categories_id like '%" . $category_id . "%'"]
        ]);
        if ($count == 0) {
            return false;
        } else {
            return true;
        }
    }
}