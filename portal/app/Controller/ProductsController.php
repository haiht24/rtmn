<?php
App::uses('AppController', 'Controller');
App::uses('GeoIP', 'Lib');
App::uses('ConnectionManager', 'Model');

class ProductsController extends AppController
{
    public $uses = ['Category', 'Store', 'Coupon', 'Deal', 'CategoriesStore', 'User', 'Draft', 'Event', 'Country', 'Vendor',
        'WpTerm', 'WpTermTaxonomy', 'WpUser', 'WpPost', 'WpPostmeta', 'WpTermRelationship'];

    public function beforeFilter()
    {
//        date_default_timezone_set(Configure::read('reCaptcha.public_key'));
        parent::beforeFilter();
    }

    private function _getTimeZone()
    {
        $ip = $this->request->clientIp(false);
        $geoRequest = GeoIP::lookup($ip);
        if (isset($geoRequest['time_zone'])) {
            return $geoRequest['time_zone'];
        }
        return 'UTC';
    }

    public function index()
    {
//        $this->Category->unbindAllExcept(['father']);
        $categories = $this->Category->find('all');
        $parents = [];
        $listCate = [];
        if (!empty($categories)) {
            $parents = from($categories)->where(function ($v) {
                return empty($v['category']['parent_id']);
            })->orderBy('$v["category"]["name"]')->select('$v')->toList();
            foreach ($parents as &$parent) {
                $parent['category']['sub_category'] = from($categories)->where('$v["category"]["parent_id"]==' . '"' . $parent['category']['id'] . '"')->orderBy('$v["category"]["name"]')->select('$v')->toList();
            }
        }
        $this->User->unbindAll();
        $this->set('events', $this->Event->find('all',
            ['conditions' => ['event.status' => 'published'],
                'fields' => ['event.id', 'event.name'],
                'order' => ['event.name']
            ]));
        $this->set('users', $this->User->find('all', [
            'fields' => ['user.id', 'user.fullname', 'user.email'],
            'conditions' => [
                "status" => 'active'
            ]
        ]));

        $this->set('categories', $parents);
        $this->set('listCategories', from($categories)->orderBy('$v["category"]["name"]')->select('$v')->toList());
        $this->set('countries', $this->Country->find('all', [
            'fields' => ['id', 'countrycode', 'countryname'],
            'order' => ['ishot DESC', 'countryname']
        ]));

        $this->set('breadcrumbs', ['Home', 'MostCoupon', 'Content Management']);
        $this->set('timeZone', $this->_getTimeZone());
    }

    public function events()
    {
        $events = $this->Event->find('all');
        $this->set('events', $events);
        $this->User->unbindAll();
        $this->set('users', $this->User->find('all', ['fields' => ['user.id', 'user.fullname']]));
        $this->set('breadcrumbs', ['Home', 'MostCoupon', 'Events']);
    }

    public function categories()
    {
        $this->Category->unbindAllExcept(['father']);
        $categories = $this->Category->find('all');
        $parents = [];
        $listCate = [];
        if (!empty($categories)) {
            $parents = from($categories)->where(function ($v) {
                return empty($v['category']['parent_id']);
            })->select('$v')->toList();
            foreach ($parents as &$parent) {
                $parent['category']['sub_category'] = from($categories)->where('$v["category"]["parent_id"]==' . '"' . $parent['category']['id'] . '"')->select('$v')->toList();
            }
        }
        $this->User->unbindAll();
        $this->set('users', $this->User->find('all', ['fields' => ['user.id', 'user.fullname']]));
        $this->set('categories', $parents);
        $this->set('listCategories', $categories);
    }

    public function stores()
    {
        $this->Category->unbindAllExcept('father');
        $categories = $this->Category->find('all', ['conditions' => ['category.status' => 'published']]);

        $parents = [];
        if (!empty($categories)) {
            $parents = from($categories)->where(function ($v) {
                return empty($v['category']['parent_id']);
            })->orderBy('$v["category"]["name"]')->select('$v')->toList();
            foreach ($parents as &$parent) {
                $parent['category']['sub_category'] = from($categories)->where('$v["category"]["parent_id"]==' . '"' . $parent['category']['id'] . '"')->orderBy('$v["category"]["name"]')->select('$v')->toList();
            }
        }
        $this->User->unbindAll();
        $this->set('users', $this->User->find('all', ['fields' => ['user.id', 'user.fullname']]));
        $this->set('categories', $parents);
    }

    public function coupons()
    {
        $this->Category->unbindAllExcept('father');
        $categories = $this->Category->find('all', ['fields' => ['category.id', 'category.name', 'category.parent_id'],
            'conditions' => ['category.status' => 'published']]);
        $listCategories = [];
        if (!empty($categories)) {
            $listCategories = from($categories)->orderBy('$v["category"]["name"]')->select('$v')->toList();
        }
        $this->User->unbindAll();
        $this->set('users', $this->User->find('all', ['fields' => ['user.id', 'user.fullname']]));
        $this->set('categories', $listCategories);
    }


    public function saveCategory()
    {
        $this->response->type('json');
        if ($this->request->is('post') && !empty($this->request->data)) {
//            $this->Category->unbindAllExcept('father');
            $response = ['status' => false, 'message' => null, 'category' => []];
            if (empty($this->request->data['id'])) {
                $this->request->data['user_id'] = $this->CurrentUser['user']['id'];
            }
            if ($this->request->data['status']) {
                if ($this->request->data['status'] == 'published') {
                    $this->request->data['publish_date'] = date('Y-m-d H:i:s');
                }
            }
            $response['date'] = date('Y-m-d H:i:s');
            $category = $this->Category->save($this->request->data);
            if (!$category) {
                $response['message'] = $this->Category->validationErrors;
            } else {
                $response['status'] = true;
//                $this->Category->unbindAllExcept('father');
                $response['category'] = $this->Category->findById($category['category']['id']);
            }
            $this->response->body(json_encode($response));
        }
        return $this->response;
    }


    public function deleteCategory($id)
    {
        $this->response->type('json');
        $response['status'] = true;
        if ($this->Category->hasStores($id)) {
            $response['status'] = 'error';
            $response['msg'] = 'This category cannot be deleted as it has Store';
        } elseif ($this->Category->hasDeals($id)) {
            $response['status'] = 'error';
            $response['msg'] = 'This category cannot be deleted as it has Deal';
        } elseif ($this->Category->hasCoupons($id)) {
            $response['status'] = 'error';
            $response['msg'] = 'This category cannot be deleted as it has Coupon';
        } else $this->Category->delete(array('category.id' => $id));
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function deleteCategories()
    {
        $response = [];
        if ($this->request->is('post') && !empty($this->request->data)) {
            if (!empty($this->request->data['ids'])) {
                if (count($this->request->data['ids']) > 1) {
                    $this->Category->deleteAll(array('category.id in' => $this->request->data['ids']), false);
                } else $this->Category->delete(array('category.id' => $this->request->data['ids'][0]));
                $response = ['status' => true, 'message' => null];
            }
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function changeStatusCategory()
    {
        if ($this->request->is('post')) {
            $this->Category->unbindAll();
            if (!empty($this->request->data['pk']) && !empty($this->request->data['value'])) {
                $findItem = $this->Category->findById($this->request->data['pk']);
                if (!empty($findItem)) {
                    $data = ['id' => $this->request->data['pk'],
                        'status' => $this->request->data['value']];
                    if ($this->request->data['value'] == 'published') {
                        $data['publish_date'] = date('Y-m-d H:i:s');
                    }
                    $response['save'] = $this->Category->save($data);
                }
            }
        }
        $this->response->type('json');
        $this->response->statusCode(200);
        $response = ['status' => 'category', 'msg' => $this->request->data['pk']];
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function deleteStore($id)
    {
        $this->response->type('json');
        $response['status'] = true;
        if ($this->Store->hasDeals($id)) {
            $response['status'] = 'error';
            $response['msg'] = 'This store cannot be deleted as it has Deal';
        } elseif ($this->Store->hasCoupons($id)) {
            $response['status'] = 'error';
            $response['msg'] = 'This store cannot be deleted as it has Coupon';
        } else $this->Store->delete(['store.id' => $id]);

        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function deleteStores()
    {
        $response = [];
        if ($this->request->is('post') && !empty($this->request->data)) {
            if (!empty($this->request->data['ids'])) {
                if (count($this->request->data['ids']) > 1) {
                    $this->Store->deleteAll(array('store.id in' => $this->request->data['ids']), false);
                } else $this->Store->delete(array('store.id' => $this->request->data['ids'][0]));

                $response = ['status' => true, 'message' => null];
            }
        }
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function changeStatusStore()
    {
        if ($this->request->is('post') && !empty($this->request->data)) {
            if (!empty($this->request->data['pk']) && !empty($this->request->data['value'])) {
                $findItem = $this->Store->findById($this->request->data['pk']);
                if (!empty($findItem)) {
                    $data = array('id' => $this->request->data['pk'], 'status' => $this->request->data['value']);
                    if ($this->request->data['value'] == 'published') {
                        $data['publish_date'] = date('Y-m-d H:i:s');
                    }
                    $this->Store->save($data);
                }
            }
        }
        $this->response->type('json');
        $this->response->statusCode(200);
        $response = ['status' => 'store', 'msg' => $this->request->data['pk']];
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function queryCategoriesStores()
    {
        $this->response->type('json');
        $options = [];
        if (!empty($this->params->query['store_id'])) {
            $options['conditions'] = ['categoriesStore.store_id' => $this->params->query['store_id']];
        }
        if (!empty($this->params->query['category_id'])) {
            $options['conditions'] = ['categoriesStore.category_id' => $this->params->query['category_id']];
        }
        $categoriesStores = $this->CategoriesStore->find('all', $options);
        $count = $this->CategoriesStore->find('count', array_merge($options, [
            'limit' => false
        ]));
        $response = ['categoriesStores' => $categoriesStores, 'count' => $count];
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function saveStore()
    {
        $this->response->type('json');
        if ($this->request->is('post') && !empty($this->request->data)) {
            $response = ['status' => false, 'message' => null, 'store' => []];
            $data = $this->request->data;
            if (empty($data['id'])) {
                $data['user_id'] = $this->CurrentUser['user']['id'];
            }
            if ($data['status']) {
                if ($data['status'] == 'published') {
                    $data['publish_date'] = date('Y-m-d H:i:s');
                }
            }
            $store = $this->Store->save($data);
            if (!$store) {
                $response['message'] = $this->Store->validationErrors;
            } else {
//                if (!empty($this->request->data['categories_id'])) {
//                    $this->CategoriesStore->unbindAll();
//                    $this->CategoriesStore->deleteAll(['categoriesStore.store_id' => $store['store']['id']]);
//                    $dataCateStores = [];
//                    foreach ($this->request->data['categories_id'] as $categoryId) {
//                        $dataCateStores[] = ['category_id' => $categoryId, 'store_id' => $store['store']['id']];
//                    }
//                    $this->CategoriesStore->saveAll($dataCateStores);
//                }
                if (!empty($this->request->data['vendors'])) {
                    $dataVendors = $this->request->data['vendors'];
                    if (!empty($data['id'])) {
                        $this->Vendor->deleteAll(['parent_id' => $store['store']['id']]);
                    } else {
                        for ($i = 0; $i < sizeof($dataVendors); $i++) {
                            $dataVendors[$i]['parent_id'] = $store['store']['id'];
                        }
                    }
                    $this->Vendor->saveAll($dataVendors);
                }
                $response['status'] = true;
                $response['store'] = $this->Store->find('first', [
                    'conditions' => [
                        'store.id' => $store['store']['id']
                    ],
                ]);
            }
            $this->response->body(json_encode($response));
        }
        return $this->response;
    }

    public function queryStore()
    {
        $limit = 10000;
        $offset = 0;
        if (isset($this->request->query['limit'])) {
            $limit = $this->request->query['limit'];
        }
        if (isset($this->request->query['offset'])) {
            $offset = $this->request->query['offset'];
        }
        $options = [
            'limit' => $limit,
            'offset' => $offset
        ];
        $options['conditions'] = [];
        if (!empty($this->params->query['filter_name'])) {
            $options['conditions'][] =
                [
                    'OR' => [
                        'LOWER(store.name) LIKE' => '%' . strtolower($this->params->query['filter_name']) . '%',
                        'LOWER(store.alias) LIKE' => '%' . strtolower($this->params->query['filter_name']) . '%',
                        'LOWER(store.store_url) LIKE' => '%' . strtolower($this->params->query['filter_name']) . '%'
                    ]];
        }
        if (!empty($this->params->query['user_id'])) {
            $options['conditions'][] = ['store.user_id' => $this->params->query['user_id']];
        }

        if (!empty($this->params->query['created'])) {
            $options['conditions'][] = ['store.created LIKE' => '%' . $this->params->query['created'] . '%'];
        }

        if (!empty($this->params->query['created_from'])) {
            $options['conditions'][] = ['Date(store.created) >= ' => $this->params->query['created_from']];
        }

        if (!empty($this->params->query['created_to'])) {
            $options['conditions'][] = ['Date(store.created) <=' => $this->params->query['created_to']];
        }

        if (!empty($this->params->query['publish_date'])) {
            $options['conditions'][] = ['store.publish_date LIKE' => '%' . $this->params->query['publish_date'] . '%'];
        }

        if (!empty($this->params->query['status'])) {
            $options['conditions'][] = ['store.status LIKE' => '%' . $this->params->query['status'] . '%'];
        }

        if (!empty($this->params->query['fields'])) {
            $options['fields'] = ['store.id', 'store.name', 'store.categories_id'];
        }
        if (!empty($this->params->query['id'])) {
            $options['conditions'][] = ['store.id' => $this->params->query['id']];
        }
        if (!empty($this->params->query['categories_id'])) {//get to bind store when selected in coupon
            $this->Store->unbindAll();
            $options['joins'] = [[ // join surveys
                'table' => 'categories_stores',
                'alias' => 'categoriesStore',
                'type' => 'INNER',
                'conditions' => ['categoriesStore.store_id = store.id', 'categoriesStore.category_id LIKE' => '%' . $this->params->query['categories_id'] . '%']
            ]];
            $options['conditions'][] = ['store.status' => 'published'];
        }
        $sortBy = 'DESC';
        $sortField = 'created';
        if (!empty($this->params->query['sort_field'])) {
            $sortField = $this->params->query['sort_field'];
            if (!empty($this->params->query['sort_by'])) {
                $sortBy = $this->params->query['sort_by'];
            }
        }
        $options['order'] = 'store.' . $sortField . ' ' . $sortBy;

        $stores = $this->Store->find('all', $options);
        $this->Store->unbindAll();
        $count = $this->Store->find('count', array_merge($options, [
            'limit' => false
        ]));
        $response = ['stores' => $stores, 'count' => $count];
        $this->response->statusCode(200);
        $this->response->type('json');
        $this->response->disableCache();
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function checkExistsStore()
    {
        $this->response->type('json');
        if ($this->request->is('post') && !empty($this->request->data)) {
            $name = null;
            if (!empty($this->request->data['name'])) {
                $name = $this->request->data['name'];
            }
            $couponURL = null;
            if (!empty($this->request->data['alias'])) {
                $couponURL = $this->request->data['alias'];
            }
            $storeURL = null;
            if (!empty($this->request->data['store_url'])) {
                $storeURL = $this->request->data['store_url'];
            }
            $id = null;
            $storeOld = null;
            if (!empty($this->request->data['id'])) {
                $id = $this->request->data['id'];
                $storeOld = $this->Store->findById($id);
            }
            $response = ['existName' => false, 'existNameStore' => [], 'existCouponURl' => false, 'existCouponURlStore' => false, 'listStoreURL' => [], 'existStoreURL' => false];
            if (!empty($name)) {
                $store = $this->Store->findByName($name);
                if (!empty($store)) {
                    if (!empty($storeOld) && $storeOld['store']['name'] != $store['store']['name']) {
                        $response['existName'] = true;
                        $response['existNameStore'] = $store;
                    }
                    if (empty($storeOld)) {
                        $response['existName'] = true;
                        $response['existNameStore'] = $store;
                    }
                }
            }
            if (!empty($couponURL)) {
                $options = [
                    'limit' => 10,
                    'offset' => 0
                ];
                $options['conditions'] = [];
                $options['conditions'][] = ['store.alias LIKE' => $couponURL];
                $options['fields'] = ['store.id', 'store.name', 'store.alias'];
                $store = $this->Store->find('all', $options);
                if (!empty($store)) {
                    if (!empty($storeOld) && (count($store) > 1 || $storeOld['store']['alias'] != $store[0]['store']['alias'])) {
                        $response['existCouponURl'] = true;
                        $response['existCouponURlStore'] = $store;
                    }
                    if (empty($storeOld)) {
                        $response['existCouponURl'] = true;
                        $response['existCouponURlStore'] = $store;
                    }
                }
            }
            if (!empty($storeURL)) {
                $options = [
                    'limit' => 10,
                    'offset' => 0
                ];
                $options['conditions'] = [];
                $options['conditions'][] = ['store.store_url LIKE' => $storeURL . '%'];
                $options['fields'] = ['store.id', 'store.name', 'store.store_url'];
                $store = $this->Store->find('all', $options);
                if (!empty($store)) {
                    if (!empty($storeOld) && (count($store) > 1 || $storeOld['store']['store_url'] != $store[0]['store']['store_url'])) {
                        $response['existStoreURL'] = true;
                        $response['listStoreURL'] = $store;
                    }
                    if (empty($storeOld)) {
                        $response['existStoreURL'] = true;
                        $response['listStoreURL'] = $store;
                    }
                }
            }
            $this->response->body(json_encode($response));
        }

        return $this->response;
    }

    public function deleteCoupon($id)
    {
        $this->response->type('json');
        $this->Coupon->delete(array('coupon.id' => $id));
        $response = ['status' => true, 'message' => null];
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function changeStatusCoupon()
    {
        if ($this->request->is('post') && !empty($this->request->data)) {
            if (!empty($this->request->data['pk']) && !empty($this->request->data['value'])) {
                $findItem = $this->Coupon->findById($this->request->data['pk']);
                if (!empty($findItem)) {
                    $data = array('id' => $this->request->data['pk'], 'status' => $this->request->data['value']);
                    if ($this->request->data['value'] == 'published') {
                        $data['publish_date'] = date('Y-m-d H:i:s');
                    }
                    $this->Coupon->save($data);
                }
            }
        }
        $this->response->type('json');
        $this->response->statusCode(200);
        $response = ['status' => 'coupon', 'msg' => $this->request->data['pk']];
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function deleteCoupons()
    {
        $response = [];
        if ($this->request->is('post') && !empty($this->request->data)) {
            if (!empty($this->request->data['ids'])) {
                if (count($this->request->data['ids']) > 1) {
                    $this->Coupon->deleteAll(array('coupon.id in' => $this->request->data['ids']), false);
                } else $this->Coupon->delete(array('coupon.id' => $this->request->data['ids'][0]));
                $response = ['status' => true, 'message' => null];
            }
        }
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function queryCoupon()
    {
        $this->response->type('json');
        $limit = 10000;
        $offset = 0;
        if (isset($this->request->query['limit'])) {
            $limit = $this->request->query['limit'];
        }
        if (isset($this->request->query['offset'])) {
            $offset = $this->request->query['offset'];
        }
        $options = [
            'limit' => $limit,
            'offset' => $offset
        ];
        $options['conditions'] = [];
        if (!empty($this->params->query['filter_name'])) {
            $options['conditions'][] =
                [
                    'OR' => [
                        'LOWER(coupon.title_store) LIKE' => '%' . strtolower($this->params->query['filter_name']) . '%',
                        'LOWER(coupon.title_category) LIKE' => '%' . strtolower($this->params->query['filter_name']) . '%',
                        'LOWER(coupon.title_event) LIKE' => '%' . strtolower($this->params->query['filter_name']) . '%',
                        'LOWER(coupon.title_top_coupon) LIKE' => '%' . strtolower($this->params->query['filter_name']) . '%',
                        'LOWER(coupon.title_related_coupon) LIKE' => '%' . strtolower($this->params->query['filter_name']) . '%',
                        'LOWER(coupon.coupon_type) LIKE' => '%' . strtolower($this->params->query['filter_name']) . '%'
                    ]];
        }
        if (!empty($this->params->query['user_id'])) {
            $options['conditions'][] = ['coupon.user_id' => $this->params->query['user_id']];
        }

        if (!empty($this->params->query['created'])) {
            $options['conditions'][] = ['coupon.created LIKE' => '%' . $this->params->query['created'] . '%'];
        }

        if (!empty($this->params->query['publish_date'])) {
            $options['conditions'][] = ['coupon.publish_date LIKE' => '%' . $this->params->query['publish_date'] . '%'];
        }

        if (!empty($this->params->query['created_from'])) {
            $options['conditions'][] = ['Date(coupon.created) >= ' => $this->params->query['created_from']];
        }

        if (!empty($this->params->query['created_to'])) {
            $options['conditions'][] = ['Date(coupon.created) <=' => $this->params->query['created_to']];
        }

        if (!empty($this->params->query['status'])) {
            $options['conditions'][] = ['coupon.status LIKE' => '%' . $this->params->query['status'] . '%'];
        }

        $sortBy = 'DESC';
        $sortField = 'created';
        if (!empty($this->params->query['sort_field'])) {
            $sortField = $this->params->query['sort_field'];
            if (!empty($this->params->query['sort_by'])) {
                $sortBy = $this->params->query['sort_by'];
            }
        }
        $options['order'] = 'coupon.' . $sortField . ' ' . $sortBy;
        $stores = $this->Coupon->find('all', $options);
        $count = $this->Coupon->find('count', array_merge($options, [
            'limit' => false
        ]));
        $response = ['coupons' => $stores, 'count' => $count];
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function addCoupon()
    {
        $this->response->type('json');
        if ($this->request->is('post') && !empty($this->request->data)) {
            $response = ['status' => false, 'message' => null, 'coupon' => []];
            if (empty($this->request->data['id'])) {
                $this->request->data['user_id'] = $this->CurrentUser['user']['id'];
            }
            if ($this->request->data['status']) {
                if ($this->request->data['status'] == 'published') {
                    $this->request->data['publish_date'] = date('Y-m-d H:i:s');
                }
            }
            if (empty($this->request->data['event_id']) || $this->request->data['event_id'] == '') {
                $this->request->data['event_id'] = null;
            }
            $coupon = $this->Coupon->save($this->request->data);
            if ($coupon) {
                if (!empty($this->request->data['vendors'])) {
                    $dataVendors = $this->request->data['vendors'];
                    if (!empty($this->request->data['id'])) {
                        $this->Vendor->deleteAll(['parent_id' => $coupon['coupon']['id']]);
                    } else {
                        for ($i = 0; $i < sizeof($dataVendors); $i++) {
                            $dataVendors[$i]['parent_id'] = $coupon['coupon']['id'];
                        }
                    }
                    $this->Vendor->saveAll($dataVendors);
                }
                $response['status'] = true;
                $response['coupon'] = $this->Coupon->findById($coupon['coupon']['id']);
            } else {
                $response['message'] = $this->Coupon->validationErrors;
            }
            $this->response->body(json_encode($response));
        }
        return $this->response;
    }

    public function queryrac()
    {
        $this->response->type('json');
        $options = [];
        $options['conditions'] = [];
        $options['conditions'][] = ['draft.user_id' => $this->CurrentUser['user']['id']];
        $options['conditions'][] = 'draft.left_id is null';
        if (!empty($this->params->query['type'])) {
            $options['conditions'][] = ['draft.type' => $this->params->query['type']];
        }
        $options['order'] = 'draft.created DESC';
        $draft = $this->Draft->find('first', $options);
        $this->response->body(json_encode($draft));
        return $this->response;
    }

    public function deleterac()
    {
        $this->response->type('json');
        $options = [];
        $options['conditions'] = [];
        $options['conditions'][] = ['draft.user_id' => $this->CurrentUser['user']['id']];
        $options['conditions'][] = 'draft.left_id is null';
        if (!empty($this->params->query['type'])) {
            $options['conditions'][] = ['draft.type' => $this->params->query['type']];
        }
        $options['order'] = 'draft.created DESC';
        $drafts = $this->Draft->find('all', $options);
        if (!empty($drafts)) {
            $ids = from($drafts)->select('$v["draft"]["id"]')->toList();
            $this->Draft->deleteAll(["draft.id" => $ids]);
        }
        $this->response->body(json_encode(count($drafts)));
        return $this->response;
    }

    public function addrac()
    {
        $this->response->type('json');
        if ($this->request->is('post') && !empty($this->request->data)) {
            $response = ['status' => false, 'message' => null, 'draft' => []];
            if (empty($this->request->data['id'])) {
                $this->request->data['user_id'] = $this->CurrentUser['user']['id'];
            }
            $draft = $this->Draft->save($this->request->data);
            if (!$draft) {
                $response['message'] = $this->Draft->validationErrors;
            } else {
                $response['status'] = true;
                $response['draft'] = $draft['draft'];
            }
            $this->response->body(json_encode($response));
        }
        return $this->response;
    }

    public function suggestStore()
    {
        $this->response->type('json');
        $filterName = strtolower($this->params->query['filter_name']);
        $arrStores = [];
        if (strpos($filterName, '-') >= 0 || strpos($filterName, ' ') >= 0 || strpos($filterName, '_') >= 0) {
            $arrFilterName0 = split('-', $filterName);
            $arrFilterName1 = split(' ', $filterName);
            $arrFilterName2 = split('_', $filterName);
            $arr = array_merge($arrFilterName0, $arrFilterName1, $arrFilterName2);
            $arr = array_unique($arr);

            foreach ($arr as $a) {
                $arrStores = array_merge($arrStores, $this->getStores($a));
                $arrStores = array_unique($arrStores, SORT_REGULAR);
            }
        }

        $stores = $arrStores;
        $response = ['stores' => $stores];
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function getStores($filterName)
    {
        $options['conditions'] = [];
        $options['conditions'][] = [
            'OR' => [
                'LOWER(store.name) LIKE' => '%' . $filterName . '%',
                'LOWER(store.alias) LIKE' => '%' . $filterName . '%',
                'LOWER(store.store_url) LIKE' => '%' . $filterName . '%'
            ]];
        $stores = $this->Store->find('all', $options);
        return $stores;
    }

    public function checkExistsEvent()
    {
        $this->response->type('json');
        if ($this->request->is('post') && !empty($this->request->data)) {
            $name = $this->request->data['name'];
            $id = null;
            $old = null;
            if (!empty($this->request->data['id'])) {
                $id = $this->request->data['id'];
                $old = $this->Event->findById($id);
            }
            $response = ['existName' => false];
            $findItem = $this->Event->findByName($name);
            if (!empty($findItem)) {
                if (!empty($old) && $old['event']['name'] != $findItem['event']['name']) {
                    $response['existName'] = true;
                }
                if (empty($old)) {
                    $response['existName'] = true;
                }
            }
            $this->response->body(json_encode($response));
        }
        return $this->response;
    }

    public function saveEvent()
    {
        $this->response->type('json');
        if ($this->request->is('post') && !empty($this->request->data)) {
            $response = ['status' => false, 'message' => null, 'event' => []];
            if (empty($this->request->data['id'])) {
                $this->request->data['user_id'] = $this->CurrentUser['user']['id'];
            }
            if (!empty($this->request->data['id']) && !empty($this->request->data['status'])
                && $this->request->data['status'] == 'published'
            ) {
                $findItem = $this->Event->findById($this->request->data['id']);
                if (!empty($findItem) && $findItem['event']['status'] != 'published') {
                    $this->request->data['publish_date'] = date('Y-m-d H:i:s');
                }
            }
            $event = $this->Event->save($this->request->data);
            if (!$event) {
                $response['message'] = $this->Event->validationErrors;
            } else {
                $response['status'] = true;
                $response['event'] = $this->Event->findById($event['event']['id']);
            }
            $this->response->body(json_encode($response));
        }
        return $this->response;
    }

    public function deleteEvent($id)
    {
        $this->response->type('json');
        $this->Event->delete(array('event.id' => $id));
        $response = ['status' => true, 'message' => null];
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function checkExistsCate()
    {
        $this->response->type('json');
        if ($this->request->is('post') && !empty($this->request->data)) {
            $this->Category->unbindAllExcept('father');
            $name = $this->request->data['name'];
            $id = null;
            $old = null;
            if (!empty($this->request->data['id'])) {
                $id = $this->request->data['id'];
                $old = $this->Category->findById($id);
            }
            $response = ['existName' => false];
            $findItem = $this->Category->findByName($name);
            if (!empty($findItem)) {
                if (!empty($old) && $old['category']['name'] != $findItem['category']['name']) {
                    $response['existName'] = true;
                }
                if (empty($old)) {
                    $response['existName'] = true;
                }
            }
            $this->response->body(json_encode($response));
        }
        return $this->response;
    }

    public function setCurrentPage()
    {
        $this->response->type('json');
        $resp = [];
        $currentPage = $this->request->data('currentPage');
        $type = $this->request->data('type');
        // save Session here
        $this->Session->write($type . '.currentPage', $currentPage);
        $resp[$type . '.currentPage'] = $this->Session->read($type . '.currentPage');

        $this->response->body(json_encode($resp));
        return $this->response;
    }

    public function deleteProductSessions()
    {
        $this->response->type('json');
        $resp = [];
        $type = $this->request->data('type');
        $resp['result'] = $this->Session->delete($type . '.currentPage');
        $this->response->body(json_encode($resp));
        return $this->response;
    }

    public function updateGoCode()
    {
        $this->Store->unbinAll();
        $this->Coupon->unbinAll();
        $this->Deal->unbinAll();
        $stores = $this->Store->find('all');
        foreach ($stores as $store) {
            $this->Store->save($store);
        }
        $coupons = $this->Coupon->find('all');
        foreach ($coupons as $coupon) {
            $this->Coupon->save($coupon);
        }
        $deals = $this->Deal->find('all');
        foreach ($deals as $deal) {
            $this->Deal->save($deal);
        }
        $this->response->type('json');
        $resp['status'] = true;
        $this->response->body(json_encode($resp));
        return $this->response;
    }

    public function pullData()
    {
        $dbName = $this->request->query['dbname'];
        $limit = intval($this->request->query['limit']);
        $offset = intval($this->request->query['offset']);

        $this->WpPost->useDbConfig = $dbName;
        $stores = $this->WpPost->find('all', [
            'joins' => [
                [
                    'table' => 'wp_users',
                    'alias' => 'WpUser',
                    'type' => 'INNER',
                    'conditions' => [
                        'WpPost.post_author = WpUser.ID'
                    ]
                ]
            ],
            'conditions' => [
                "post_type = 'store'",
                'WpPost.post_status' => ['publish', 'pending', 'draft', 'trash']
            ],
            'fields' => ['WpPost.id', 'WpPost.post_author as user_id', 'WpPost.post_title as name', 'WpPost.guid as alias', 'WpPost.post_content as description', 'WpPost.post_status as status', 'WpPost.post_date as created', 'WpPost.post_modified as modified',
                'WpUser.user_email'],
            'limit' => $limit,
            'offset' => $offset
        ]);
        $resp['count'] = sizeof($stores);
        if ($this->Session->check('allCountWPStores' . $dbName)) {

        } else {
            $allCount = $this->WpPost->find('count',
                [
                    'conditions' => [
                        "post_type = 'store'",
                        'WpPost.post_status' => ['publish', 'pending', 'draft', 'trash']
                    ],
                    'fields' => ['WpPost.id']
                ]);
            $allCount = ($allCount == 0) ? 1 : $allCount;
            $this->Session->write('allCountWPStores' . $dbName, $allCount);
        }
        $resp['total'] = $this->Session->read('allCountWPStores' . $dbName);
        $allpostmetas = Cache::read('WpPostmeta' . $dbName);
        if (!$allpostmetas) {
            $this->WpPostmeta->useDbConfig = $dbName;
            $postmetas = $this->WpPostmeta->find('all', [
                'conditions' => [
                    'AND' => [
                        'OR' => [
                            "meta_key = 'aff_url_store_metadata'",
                            "meta_key = 'custom_keyword_metadata'",
                            "meta_key = 'img_store_social_metadata'",
                            "meta_key = 'logo_metadata'",
                            "meta_key = 'partner_metadata'",
                            "meta_key = 'url_store_metadata'",
                        ]
                    ]
                ],
                'fields' => ['WpPostmeta.post_id', 'WpPostmeta.meta_value', 'WpPostmeta.meta_key'],
                'order' => 'meta_key'
            ]);
            $allpostmetas = [];
            foreach ($postmetas as $post) {
                $allpostmetas[$post['WpPostmeta']['post_id']][] = $post;
            }
            Cache::write('WpPostmeta' . $dbName, $allpostmetas);
        }

        $allcategories = Cache::read('WpCategories' . $dbName);
        if (!$allcategories) {
            $this->WpTermRelationship->useDbConfig = $dbName;
            $categories_old = $this->WpTermRelationship->find('all', [
                'joins' => [
                    [
                        'table' => 'wp_terms',
                        'alias' => 'WpTerm',
                        'type' => 'INNER',
                        'conditions' => [
                            'WpTermRelationship.term_taxonomy_id = WpTerm.term_id'
                        ]
                    ]
                ],
//                'conditions' => [
//                    "WpTermRelationship.object_id" => $store['WpPost']['id']
//                ],
                'fields' => ["WpTermRelationship.object_id", 'WpTerm.slug']
            ]);
            $allcategories = [];
            foreach ($categories_old as $cate) {
                $allcategories[$cate['WpTermRelationship']['object_id']][] = $cate['WpTerm']['slug'];
            }
            Cache::write('WpCategories' . $dbName, $allcategories);
        }

        if (!$this->Session->check('WpUsers')) {
            $this->User->unbindAll();
            $allUsersTmp = $this->User->find('all', [
                'fields' => ["user.email", 'user.id']
            ]);
            $allUsers = [];
            foreach ($allUsersTmp as $user) {
                $allUsers[$user['user']['email']] = $user;
            }
            $this->Session->write('WpUsers', $allUsers);
        }
        $allUsers = $this->Session->read('WpUsers' . $dbName);

        $hotStoreIds = ['89303', '89358', '92300', '89951', '89960', '92811', '90318', '4010', '90342', '69966', '90361', '1717', '90440', '90455', '90437', '90395', '90397', '90281', '90267', '89954', '90232', '90221', '90200', '90213', '90178', '90527', '90745', '90925', '90982', '91032', '91056', '91123', '91178', '83706', '17035', '16677', '72624', '78090', '81854', '86973', '88276', '90762', '90732', '90768', '90702', '90667', '90636', '90618', '90607', '90568', '90594', '90515', '90512', '90494', '90128', '90119', '90100', '90091', '90083', '89957', '89948', '89945', '89942', '89939', '89934', '89928', '89907', '89911', '89914', '89917', '89921', '89904', '89899', '89895', '89890', '89818', '89801', '89763', '89730', '89713', '89681', '89670', '89657', '89600', '89573', '89507', '89469', '89431', '89392', '89368', '13091', '14200', '91744', '87670', '8753', '66707', '70026', '14641', '92286', '92317', '92336', '92394', '92442', '92458', '92468', '84418', '1662', '92504', '92530', '47660', '1750', '92544', '92572', '92579', '92591', '92607', '92623', '92634', '92645', '80988', '91037', '92756', '92772', '92780', '70038', '73312', '72164', '3007', '85885', '2830', '87525', '92838', '92877', '92886', '92910', '92917', '92935', '92947', '92951', '92958', '2441', '92969', '92986', '92995', '3026', '93004', '93024', '93036', '93046', '72191', '80346', '93079', '81188', '81349', '93093', '81408', '48598', '93111', '93130', '93153', '2403', '84957', '93159', '88973', '93163', '88199', '93361', '93447', '93366', '93431', '93440', '93464', '93471', '93451', '93477', '93488', '93483', '93495', '74258', '93520', '93527', '93534', '93510', '2533', '93551', '93546', '93564', '93562', '93570', '84144', '93579', '93592', '93585', '93625', '93635', '93637', '93642', '93659', '93656', '93664', '93669', '93671', '93674', '54916', '93678', '93680', '93685', '93691', '93693', '93699', '93703', '94306', '94950', '16356', '143992', '217336', '218719'];
        $storesSave = [];
        foreach ($stores as $store) {
            $this->Store->unbindAll();
            $store_old = $this->Store->find('first', [
                'conditions' => [
                    ($dbName == 'dvold') ? 'dv_id' : 'wp_id' => $store['WpPost']['id'],
                ],
                'fields' => ['id']
            ]);
            if (!empty($store_old)) continue;

            if ($dbName == 'dvold') {
                $this->Store->unbindAll();
                $store_old = $this->Store->find('first', [
                    'conditions' => ['LOWER(store.name)' => strtolower($store['WpPost']['name'])],
                    'fields' => ['id']
                ]);
                if (!empty($store_old['store']['id'])) {
                    $this->Store->save([
                        'id' => $store_old['store']['id'],
                        'dv_id' => $store['WpPost']['id'],
//                        ($dbName == 'dvold') ? 'dv_id' : 'wp_id' => $store['WpPost']['id'],
                    ]);
                    continue;
                }
            }

            $this->Store->create();

            $postmetas = !empty($allpostmetas[$store['WpPost']['id']]) ? $allpostmetas[$store['WpPost']['id']] : [];

            $affiliate_url = null;
            $logo = null;
            $social_image = null;
            $store_url = null;
            $custom_keywords = null;
            $best_store = 0;

            foreach ($postmetas as $postmeta) {
                switch ($postmeta['WpPostmeta']['meta_key']) {
                    case "logo_metadata":
                        $logo = $postmeta['WpPostmeta']['meta_value'];
                        break;
                    case "aff_url_store_metadata":
                        $affiliate_url = $postmeta['WpPostmeta']['meta_value'];
                        break;
                    case "custom_keyword_metadata":
                        $custom_keywords = $postmeta['WpPostmeta']['meta_value'];
                        break;
                    case "img_store_social_metadata":
                        $social_image = $postmeta['WpPostmeta']['meta_value'];
                        break;
                    case "partner_metadata":
                        $best_store = $postmeta['WpPostmeta']['meta_value'];
                        break;
                    case "url_store_metadata":
                        $store_url = $postmeta['WpPostmeta']['meta_value'];
                        break;
                }
            }

            $categories_id = [];
            if (!empty($allcategories[$store['WpPost']['id']]) && sizeof($allcategories[$store['WpPost']['id']]) > 0) {
                $this->Category->unbindAll();
                $catego = $this->Category->find('all', [
                    'conditions' => [
                        "category.alias" => $allcategories[$store['WpPost']['id']]
                    ],
                    'fields' => ['id']
                ]);

                foreach ($catego as $cate) {
                    $categories_id[] = $cate['category']['id'];
                }
            }

            if (!empty($allUsers[$store['WpUser']['user_email']])) {
                $store_user = $allUsers[$store['WpUser']['user_email']];
            } else $store_user = $allUsers['admin@mccorp.com'];

            if (empty($custom_keywords)) {
                $custom_keywords = ($dbName == 'mcold') ? 'Coupon Codes' : 'Voucher Codes';
            }

            if ($store['WpPost']['status'] == 'publish') {
                $status = 'published';
            } elseif ($store['WpPost']['status'] == 'draft') {
                $status = 'pending';
            } else $status = $store['WpPost']['status'];

            if (empty($logo)) $logo = 'https://s3-us-west-2.amazonaws.com/dev.mostcoupon.com/5551b4170cc040c7937b044a61af48f5';
            if (empty($social_image)) $social_image = 'https://s3-us-west-2.amazonaws.com/dev.mostcoupon.com/5551b4170cc040c7937b044a61af48f5';

            $dataSave = [
                'name' => $store['WpPost']['name'],
                'logo' => $logo,
                'social_image' => $social_image,
                'store_url' => $store_url,
                'alias' => null,
                'affiliate_url' => $affiliate_url,
                'description' => $store['WpPost']['description'],
                'custom_keywords' => $custom_keywords,
                'categories_id' => $categories_id,
                'best_store' => $best_store,
                'show_in_homepage' => $best_store,
                'status' => $status,
                'created' => $store['WpPost']['created'],
                'modified' => $store['WpPost']['modified'],
                'user_id' => !empty($store_user['user']['id']) ? $store_user['user']['id'] : $this->CurrentUser['user']['id'],
                'publish_date' => date("Y-m-d H:i:s")
            ];
            if ($dbName == 'mcold') {
                $dataSave['wp_id'] = $store['WpPost']['id'];
                if (in_array($dataSave['wp_id'], $hotStoreIds)) {
                    $dataSave['best_store'] = 1;
                    $dataSave['show_in_homepage'] = 1;
                }
                if (strpos($dataSave['store_url'], '.uk') !== false) {
                    $dataSave['countries_code'] = ['GB'];
                } else $dataSave['countries_code'] = ['US'];

            } else {
                $dataSave['dv_id'] = $store['WpPost']['id'];
                $dataSave['countries_code'] = ['GB'];
                $dataSave['status'] = 'pending';
            }
            $storesSave[] = $dataSave;
        }
        $this->Store->create();
        $this->Store->saveAll($storesSave);
        $this->response->type('json');
        $resp['status'] = true;
        $this->response->body(json_encode($resp));
        return $this->response;
    }

    public function pullCoupons()
    {
//        ini_set('max_execution_time', '100000000');
//        set_time_limit(0);
//        ini_set("memory_limit", "-1M");

        $dbName = $this->request->query['dbname'];
        $limit = intval($this->request->query['limit']);
        $offset = intval($this->request->query['offset']);

        if ($this->Session->check('allCountWPCoupons' . $dbName)) {

        } else {
            $this->WpPost->useDbConfig = $dbName;
            $allCount = $this->WpPost->find('count',
                [
                    'joins' => [
                        [
                            'table' => 'wp_postmeta',
                            'alias' => 'WpPostmeta',
                            'type' => 'INNER',
                            'conditions' => [
                                'WpPost.id = WpPostmeta.post_id'
                            ]
                        ]
                    ],
                    'conditions' => [
                        "post_type = 'coupon'",
                        'WpPost.post_status' => ['publish', 'pending', 'draft', 'trash'],
                        "meta_key = 'store_coupon_metadata'",
                        "NOT" => ["WpPostmeta.meta_value" => null]
                    ],
                    'fields' => ['WpPost.id', 'WpPost.post_author as user_id', 'WpPost.post_title as name', 'WpPost.guid as alias', 'WpPost.post_content as description', 'WpPost.post_status as status', 'WpPost.post_date as created', 'WpPost.post_modified as modified',
                        'WpPostmeta.meta_value'],
                ]);
            $allCount = ($allCount == 0) ? 1 : $allCount;
            $this->Session->write('allCountWPCoupons' . $dbName, $allCount);
            $resp['countCoupons'] = true;
        }
        $resp['total'] = $this->Session->read('allCountWPCoupons' . $dbName);

        $this->Coupon->unbindAll();
        $count_coupon_ids = $this->Coupon->find('count', [
            'fields' => ['id'],
            'conditions' => [
                'Not' => [($dbName == 'mcold') ? "wp_id" : "dv_id" => null]
            ]
        ]);
        if ($offset + $limit < $count_coupon_ids) {
            $this->response->type('json');
            $resp['count'] = $count_coupon_ids - $offset;
            $resp['status'] = true;

            $resp['count_coupon'] = $count_coupon_ids;
            $this->response->body(json_encode($resp));
            return $this->response;
        }

        $this->WpPost->useDbConfig = $dbName;
        $coupons = $this->WpPost->find('all',
            [
                'joins' => [
                    [
                        'table' => 'wp_postmeta',
                        'alias' => 'WpPostmeta',
                        'type' => 'INNER',
                        'conditions' => [
                            'WpPost.id = WpPostmeta.post_id'
                        ]
                    ],
                    [
                        'table' => 'wp_users',
                        'alias' => 'WpUser',
                        'type' => 'INNER',
                        'conditions' => [
                            'WpPost.post_author = WpUser.ID'
                        ]
                    ]
                ],
                'conditions' => [
                    "post_type = 'coupon'",
                    'WpPost.post_status' => ['publish', 'pending', 'draft', 'trash'],
                    "meta_key = 'store_coupon_metadata'",
                    "NOT" => ["WpPostmeta.meta_value" => null]
                ],
                'fields' => ['WpPost.id', 'WpPost.post_author as user_id', 'WpPost.post_title as name', 'WpPost.guid as alias', 'WpPost.post_content as description', 'WpPost.post_status as status', 'WpPost.post_date as created', 'WpPost.post_modified as modified',
                    'WpPostmeta.meta_value',
                    'WpUser.user_email'],
                'order' => ['WpPost.id'],
                'limit' => $limit,
                'offset' => $offset
            ]);
        $resp['count'] = sizeof($coupons);

        if (!$this->Session->check('WpUsers')) {
            $this->User->unbindAll();
            $allUsersTmp = $this->User->find('all', [
                'fields' => ["user.email", 'user.id']
            ]);
            $allUsers = [];
            foreach ($allUsersTmp as $user) {
                $allUsers[$user['user']['email']] = $user;
            }
            $this->Session->write('WpUsers', $allUsers);
        }
        $allUsers = $this->Session->read('WpUsers' . $dbName);

        $resp['couponSaved'] = 0;
        $arrDataSave = [];
        foreach ($coupons as $coupon) {
//            $this->Coupon->unbindAll();
//            $coupon_old = $this->Coupon->find('first', [
//                'conditions' => [
//                    ($dbName == 'dvold') ? 'dv_id' : 'wp_id' => $coupon['WpPost']['id'],
//                ],
//                'fields' => ['id']
//            ]);
//            if (!empty($coupon_old)) continue;

            $store_id = $coupon['WpPostmeta']['meta_value'];
            $this->Store->unbindAll();
            $coupon_store = $this->Store->find('first', [
                'conditions' => [
                    ($dbName == 'mcold') ? "store.wp_id" : "store.dv_id" => $store_id
                ],
                'fields' => ['store.id', 'store.categories_id']
            ]);
            if (empty($coupon_store['store']['id'])) continue;
//            $postmetas = !empty($allpostmetas[$coupon['WpPost']['id']]) ? $allpostmetas[$coupon['WpPost']['id']] : [];
            $this->WpPostmeta->useDbConfig = $dbName;
            $postmetas = $this->WpPostmeta->find('all', [
                'conditions' => [
                    'AND' => [
                        'WpPostmeta.post_id' => $coupon['WpPost']['id'],
                        'OR' => [
                            "meta_key = 'coupon_image_metadata'",
                            "meta_key = 'img_social_coupon_metadata'",
                            "meta_key = 'product_link_metadata'",
                            "meta_key = 'exclusive_metadata'",
                            "meta_key = 'coupon_type_metadata'",
                            "meta_key = 'coupon_code_metadata'",
                            "meta_key = 'coupon_discount_metadata'",
                            "meta_key = 'expire_date_metadata'",
                            "meta_key = 'store_coupon_metadata'",
                            "meta_key = 'active_metadata'",
                        ]
                    ]
                ],
                'fields' => ['WpPostmeta.post_id', 'WpPostmeta.meta_value', 'WpPostmeta.meta_key'],
                'order' => 'meta_key'
            ]);
            $coupon_image = null;
            $social_image = null;
            $product_link = null;
            $exclusive = 0;
            $coupon_type = null;
            $coupon_code = null;
            $currency = null;
            $discount = 0;
            $expire_date = null;

            foreach ($postmetas as $postmeta) {
                switch ($postmeta['WpPostmeta']['meta_key']) {
                    case "coupon_image_metadata":
                        $coupon_image = $postmeta['WpPostmeta']['meta_value'];
                        break;
                    case "img_social_coupon_metadata":
                        $social_image = $postmeta['WpPostmeta']['meta_value'];
                        break;
                    case "product_link_metadata":
                        $product_link = $postmeta['WpPostmeta']['meta_value'];
                        break;
                    case "exclusive_metadata":
                        $exclusive = $postmeta['WpPostmeta']['meta_value'];
                        break;
                    case "coupon_type_metadata":
                        if ($postmeta['WpPostmeta']['meta_value'] == 'code') {
                            $coupon_type = 'Coupon Code';
                        } elseif ($postmeta['WpPostmeta']['meta_value'] == 'free_shipping') {
                            $coupon_type = 'Free Shipping';
                        } else $coupon_type = 'Great Offer';
                        break;
                    case "coupon_code_metadata":
                        $coupon_code = $postmeta['WpPostmeta']['meta_value'];
                        break;
                    case "coupon_discount_metadata":
                        $discount = $postmeta['WpPostmeta']['meta_value'];
                        $currency = $this->strContain($discount);
                        $discount = preg_replace('/(\w+) /i', '', $discount);
                        $discount = str_replace(['%', '$', '£', '¥', '€'], '', $discount);
                        break;
                    case "expire_date_metadata":
                        $expire_date = trim($postmeta['WpPostmeta']['meta_value']);
                        break;
                }
            }

            $checkValidateExpire = false;
            if ($this->validateDate($expire_date, 'Y/m/d')) {
                $checkValidateExpire = true;
            } elseif ($this->validateDate($expire_date, 'Y/m/j')) {
                $checkValidateExpire = true;
            } elseif ($this->validateDate($expire_date, 'Y/n/d')) {
                $checkValidateExpire = true;
            } elseif ($this->validateDate($expire_date, 'Y/n/j')) {
                $checkValidateExpire = true;
            } elseif ($expire_date == '' || $expire_date == null) {
                $checkValidateExpire = true;
                $expire_date = null;
            } else $expire_date = date('Y/m/d', strtotime('2222/02/22'));

            if (!empty($allUsers[$coupon['WpUser']['user_email']])) $coupon_user = $allUsers[$coupon['WpUser']['user_email']];
//            $this->Event->unbindAll();
//            $coupon_event = $this->Event->find('first', [
//                'conditions' => [
//                    "event.wp_id" => $store_id
//                ],
//                'fields' => ['event.id']
//            ]);

            if ($coupon['WpPost']['status'] == 'publish') {
                $status = 'published';
            } elseif ($coupon['WpPost']['status'] == 'draft') {
                $status = 'pending';
            } else $status = $coupon['WpPost']['status'];

            $dataSave = [
                'title_store' => $coupon['WpPost']['name'],
                'description_store' => $coupon['WpPost']['description'],
                'coupon_image' => $coupon_image,
                'social_image' => $social_image,
                'product_link' => $product_link,
                'exclusive' => $exclusive,
                'coupon_type' => $coupon_type,
                'coupon_code' => $coupon_code,
                'currency' => $currency,
                'discount' => $discount,
                'expire_date' => $expire_date,
                'status' => $status,
                'user_id' => !empty($coupon_user) ? $coupon_user['user']['id'] : $this->CurrentUser['user']['id'],
                'store_id' => !empty($coupon_store) ? $coupon_store['store']['id'] : null,
                'wp_store_id' => $store_id,
                'categories_id' => (!empty($coupon_store['store']['categories_id'])) ? $coupon_store['store']['categories_id'] : null,
                'created' => $coupon['WpPost']['created'],
                'modified' => $coupon['WpPost']['modified'],
                'publish_date' => date("Y-m-d H:i:s")
            ];
            if ($dbName == 'mcold') {
                $dataSave['wp_id'] = $coupon['WpPost']['id'];
            } else {
                $dataSave['dv_id'] = $coupon['WpPost']['id'];
            }

//            if ($status == 'published' && !empty($coupon_code)) {
//                $this->Coupon->unbindAll();
//                $coupon_old = $this->Coupon->find('first', [
//                    'conditions' => [
//                        'store_id' => $coupon_store['store']['id'],
//                        'coupon_code' => $coupon_code,
//                        'status' => 'published'
//                    ],
//                    'fields' => ['id']
//                ]);
//                if (!empty($coupon_old['coupon']['id'])) {
//                    $resp['duplicate_coupons']++;
//
//                    if ($dbName == 'dvold') {
//                        $this->Coupon->save([
//                            'id' => $coupon_old['coupon']['id'],
//                            'title_related_coupon' => $coupon['WpPost']['name'],
//                            'description_related_coupon' => $coupon['WpPost']['description'],
//                        ]);
//                    } else {
//                        $dataSave['status'] = 'duplicate';
//                        $this->Coupon->save($dataSave);
//                    }
//                    continue;
//                }
//            }
            $resp['couponSaved']++;
            $arrDataSave[] = $dataSave;
        }
        $this->Coupon->create();
        $this->Coupon->saveAll($arrDataSave);
        $this->response->type('json');
        $resp['status'] = true;
        $this->response->body(json_encode($resp));
        return $this->response;
    }

    public function clearData()
    {
        $db = ConnectionManager::getDataSource('default');
        $db->rawQuery("SELECT truncate_tables('postgres');");
//        $db->rawQuery("SELECT truncate_tables_any_user();");

        $this->Session->delete('allCountWPStores' . 'mcold');
        $this->Session->delete('allCountWPStores' . 'dvold');
        $this->Session->delete('allCountWPCoupons' . 'mcold');
        $this->Session->delete('allCountWPCoupons' . 'dvold');
        $this->Session->delete('WpUsers');

        $dbName = $this->request->query['dbname'];

        Cache::delete('WpPostmeta' . $dbName);
        Cache::delete('WpPostmetaCoupon' . $dbName);
        Cache::delete('WpCategories' . $dbName);
        Cache::delete('WpUsers' . $dbName);

        $this->WpUser->useDbConfig = $dbName;
        $users = $this->WpUser->find('all');
        foreach ($users as $user) {
            $this->User->create();
            $this->User->save([
                'fullname' => (!empty($user['WpUser']['display_name'])) ? $user['WpUser']['display_name'] : $user['WpUser']['user_login'],
                'username' => $user['WpUser']['user_login'],
                'email' => $user['WpUser']['user_email'],
                'status' => ($user['WpUser']['user_status'] == 0) ? 'active' : 'inactive',
                'wp_id' => $user['WpUser']['ID']
            ]);
        }

        $this->WpTerm->useDbConfig = $dbName;
        $categories = $this->WpTerm->find('all',
            [
                'joins' => [
                    [
                        'table' => 'wp_term_taxonomy',
                        'alias' => 'WpTermTaxonomy',
                        'type' => 'INNER',
                        'conditions' => [
                            'WpTermTaxonomy.term_id = WpTerm.term_id'
                        ]
                    ]
                ],
                'conditions' => [
                    "WpTermTaxonomy.taxonomy = 'store_category'"
                ],
                'fields' => ['WpTerm.term_id as id', 'WpTerm.name as name', 'WpTerm.slug as slug', 'WpTermTaxonomy.description as description', 'WpTermTaxonomy.parent as parent_id'],
            ]);
        foreach ($categories as $cate) {
            $this->Category->create();
            $this->Category->save([
                'name' => html_entity_decode($cate['WpTerm']['name'], ENT_QUOTES, 'UTF-8'),
                'alias' => $cate['WpTerm']['slug'],
                'description' => $cate['WpTermTaxonomy']['description'],
                'user_id' => $this->CurrentUser['user']['id'],
                'wp_id' => $cate['WpTerm']['id'],
                'status' => 'published',
                'publish_date' => date("Y-m-d H:i:s")
            ]);
        }
        $events = $this->WpTerm->find('all',
            [
                'joins' => [
                    [
                        'table' => 'wp_term_taxonomy',
                        'alias' => 'WpTermTaxonomy',
                        'type' => 'INNER',
                        'conditions' => [
                            'WpTermTaxonomy.term_id = WpTerm.term_id'
                        ]
                    ]
                ],
                'conditions' => [
                    "WpTermTaxonomy.taxonomy = 'event'"
                ],
                'fields' => ['WpTerm.term_id as id', 'WpTerm.name as name', 'WpTerm.slug as slug', 'WpTermTaxonomy.description as description', 'WpTermTaxonomy.parent as parent_id'],
            ]);
        foreach ($events as $event) {
            $this->Event->create();
            $this->Event->save([
                'name' => $event['WpTerm']['name'],
                'description' => $event['WpTermTaxonomy']['description'],
                'user_id' => $this->CurrentUser['user']['id'],
                'wp_id' => $event['WpTerm']['id'],
                'status' => 'published'
            ]);
        }
        $resp['status'] = true;
        $this->response->type('json');
        $this->response->body(json_encode($resp));
        return $this->response;
    }

    public function getCountries()
    {
        $q = $this->request->query['q'];
        $page = $this->request->query['page'];
        $countries = $this->Country->find('all', [
            'conditions' => [
                'OR' => [
                    "Lower(countrycode) like '%" . strtolower($q) . "%'",
                    "Lower(countryname) like '%" . strtolower($q) . "%'"
                ]
            ],
            'fields' => ['id', 'countrycode', 'countryname'],
            'limit' => 30,
            'offset' => ($page - 1) * 30,
            'order' => 'ishot DESC, countryname'
        ]);
        $listCountries = [];
        for ($i = 0; $i < sizeof($countries); $i++) {
            $listCountries[$i]['id'] = $countries[$i]['Country']['countrycode'];
            $listCountries[$i]['name'] = $countries[$i]['Country']['countryname'];
        }
        $resp['items'] = $listCountries;
        $this->response->type('json');
        $this->response->body(json_encode($resp));
        return $this->response;
    }

    public function getCountriesSelected()
    {
        $ids = explode(',', $this->request->query['id']);
        $countries = $this->Country->find('all', [
            'conditions' => [
                'countrycode' => $ids
            ],
            'fields' => ['id', 'countrycode', 'countryname'],
            'order' => 'ishot DESC, countryname'
        ]);
        $listCountries = [];
        for ($i = 0; $i < sizeof($countries); $i++) {
            $listCountries[$i]['id'] = $countries[$i]['Country']['countrycode'];
            $listCountries[$i]['name'] = $countries[$i]['Country']['countryname'];
        }
        $resp['items'] = $listCountries;
        $this->response->type('json');
        $this->response->body(json_encode($resp));
        return $this->response;
    }


    public function getPercentPull()
    {
        $tableName = $this->request->data('table');
        $percent = 0;
        if ($tableName == 'store') {
            $wp_stores = $this->WpPost->find('count',
                [
                    'conditions' => [
                        "post_type = 'store'",
                        'WpPost.post_status' => ['publish', 'pending', 'trash']
                    ],
                    'fields' => ['WpPost.id', 'WpPost.post_author as user_id', 'WpPost.post_title as name', 'WpPost.guid as alias', 'WpPost.post_content as description', 'WpPost.post_status as status', 'WpPost.post_date as created', 'WpPost.post_modified as modified'],
                ]);
            $this->Store->unbindAll();
            $stores = $this->Store->find('count');
            $percent = round($stores / $wp_stores, 2) * 100;
        } else {
            $wp_coupons = $this->WpPost->find('all',
                [
                    'joins' => [
                        [
                            'table' => 'wp_postmeta',
                            'alias' => 'WpPostmeta',
                            'type' => 'INNER',
                            'conditions' => [
                                'WpPost.id = WpPostmeta.post_id'
                            ]
                        ]
                    ],
                    'conditions' => [
                        "post_type = 'coupon'",
                        'WpPost.post_status' => ['publish', 'pending', 'trash'],
                        "meta_key = 'store_coupon_metadata'",
                    ],
                    'fields' => ['WpPost.id', 'WpPost.post_author as user_id', 'WpPost.post_title as name', 'WpPost.guid as alias', 'WpPost.post_content as description', 'WpPost.post_status as status', 'WpPost.post_date as created', 'WpPost.post_modified as modified',
                        'WpPostmeta.meta_value'],
                ]);
            $this->Coupon->unbindAll();
            $coupons = $this->Coupon->find('count');
            $percent = round($coupons / $wp_coupons, 2) * 100;
        }
        $this->response->type('json');
        $resp['percent'] = $percent;
        $this->response->body(json_encode($resp));
        return $this->response;
    }

    private function strContain($str, array $arr = ['%', '$', '£', '¥', '€'])
    {
        foreach ($arr as $a) {
            if (stripos($str, $a) !== false) return $a;
        }
        return '$';
    }

    function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

}