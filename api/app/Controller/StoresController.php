<?php

App::uses('AppController', 'Controller');

class StoresController extends AppController
{
    public function index()
    {
        $options = $this->Store->buildOptions($this->params->query);
        $options['order'] = ['Store.created DESC', 'Store.name'];
        if (!empty($this->request->query['search'])) {
            $options['conditions']['OR'] = ["LOWER(Store.name) like '%" . strtolower($this->request->query['search']) . "%'",
                "LOWER(Store.store_url) like '%" . strtolower($this->request->query['search']) . "%'"];
        }
        if (!empty($this->request->query['categoryId'])) {
            $options['conditions'][] = "Store.categories_id like '%" . $this->request->query['categoryId'] . "%'";
        }
        if (!empty($this->request->query['categoriesId'])) {
            $options['conditions']['OR'] = [
                "Store.categories_id like '%" . $this->request->query['categoriesId'] . "%'",
                $this->request->query['categoriesId'] . " LIKE '%' || Store.categories_id || '%'"
            ];
        }
        if (!empty($this->request->query['unbindModel'])) {
            $this->Store->unbindModel(
                $this->request->query['unbindModel']
            );
        }
        if (!empty($this->request->query['unbindAllExcept'])) {
            $this->Store->unbindAllExcept($this->request->query['unbindAllExcept']);
        }
        if (!empty($this->request->query['unbindAll'])) {
            $this->Store->unbindAll();
        }
        if (!empty($this->request->query['findList'])) {
            $stores = $this->Store->find('list', $options);
        }else {
            $stores = $this->Store->find('all', $options);
            if (!empty($this->request->query['userId'])) {
                $stores_user = $this->Store->find('all', [
                    'conditions' => [
                        'AND' => [
                            'OR' => [
                                "LOWER(Store.name) like '%" . strtolower($this->request->query['search']) . "%'",
                                "LOWER(Store.store_url) like '%" . strtolower($this->request->query['search']) . "%'"
                            ],
                            'Store.user_id' => $this->request->query['userId']
                        ]
                    ]
                ]);
                $stores = array_merge($stores, $stores_user);
            }
        }
        $this->set(array(
            'stores' => $stores,
            '_serialize' => array('stores')
        ));
    }

    public function view($alias)
    {
        $this->set(array(
            'store' => $this->Store->find('first', [
                'conditions' => [
                    'alias' => $alias,
                    'status' => 'published'
                ],
                'fields' => ['id', 'name', 'logo', 'social_image', 'store_url', 'alias', 'affiliate_url', 'description', 'custom_keywords', 'categories_id', 'tags', 'coupon_count', 'deal_count', 'publish_date']
            ]),
            '_serialize' => array('store')
        ));
    }

    public function add()
    {
        $data = $this->request->data;
        if (!empty($data)) {
            $this->Store->create();
            $this->Store->save($data);
            $this->set(array(
                'store' => ['id' => $this->Store->id, 'name' => $this->Store->name, 'store_url' => $this->Store->store_url],
                '_serialize' => array('store')
            ));
        } else $this->set(array(
            'store' => [],
            '_serialize' => array('store')
        ));
    }

    public function edit($id)
    {
        $data = $this->request->data;
        $store = $this->Store->findById($id);
        if ($store) {
            $categories = $store['Store']['categories_id'];
            if (!in_array($data['category_id'], $categories)) {
                $categories[] = $data['category_id'];
            }
            $this->Store->id = $id;
            $this->Store->save(['categories_id' => $categories]);
        }
        $this->set(array(
            'store' => $this->Store->findById($id),
            '_serialize' => array('store')
        ));
    }

    public function saveall()
    {
        $this->set(array(
            'store' => $this->Store->updateAll(["publish_date" => "'" . date('Y-m-d H:i:s') . "'"]),
            '_serialize' => array('store')
        ));
    }
}