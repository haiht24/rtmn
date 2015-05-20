<?php

App::uses('AppController', 'Controller');

class DealsController extends AppController
{
    public function index()
    {
        $options = $this->Deal->buildOptions($this->params->query);
        $options['order'][] = 'Deal.created DESC';

//         if (!empty($this->request->query['search'])) {
//             $options['conditions'][] = 'Category.name like "%'. $this->request->query['search'] .'%"';
//         }

        if (!empty($this->request->query['categoryId'])) {
            $options['conditions'][] = "Deal.categories_id like '%" . $this->request->query['categoryId'] . "%'";
        }
        if (!empty($this->request->query['other_id'])) {
            $options['conditions'][] = "Deal.id <> '" . $this->request->query['other_id'] . "'";
        }
        if (!empty($this->request->query['expire_date_greater_null'])) {
            $options['conditions']['OR'] = [
                'Deal.expire_date' => null,
                'DATE(Deal.expire_date) >= ' => date('Y/m/d')
            ];
        }
        if (!empty($this->request->query['expire_date_greater'])) {
            $options['conditions'][] = [
                'DATE(Deal.expire_date) >= ' => date('Y/m/d')
            ];
        }
        if (!empty($this->request->query['expired_date'])) {
            $options['conditions'][] = [
                'DATE(Deal.expire_date) < ' => date('Y/m/d')
            ];
        }
        if (!empty($this->request->query['unbindModel'])) {
            $this->Deal->unbindModel(
                $this->request->query['unbindModel']
            );
        }
        if (!empty($this->request->query['unbindAllExcept'])) {
            $this->Deal->unbindAllExcept($this->request->query['unbindAllExcept']);
        }
        if (!empty($this->request->query['unbindAll'])) {
            $this->Deal->unbindAll();
        }
        $deals = $this->Deal->find('all', $options);

        $count = 0;
        if (!empty($this->request->query['count'])) {
            if (!empty($options['limit'])) {
                unset($options['limit']);
            }
            $count = $this->Deal->find('count', $options);
        }

        $this->set(array(
            'deals' => $deals,
            'count' => $count,
            '_serialize' => array('deals', 'count')
        ));
    }

    public function view($id)
    {
        $this->set(array(
            'deal' => $this->Deal->find('first',
                [
                    'conditions' => ['Deal.id' => $id],
//                    'fields' => ['Deal.id', 'Deal.title', 'Deal.description', 'Deal.currency', 'Deal.discount_price', 'Deal.discount_percent', 'Deal.origin_price', 'Deal.produc_url', 'Deal.deal_image']
                ]),
            '_serialize' => ['deal']
        ));
    }

    public function add()
    {
        $data = $this->request->data;
        if (!empty($data)) {
            $this->set(array(
                'status' => $this->Deal->save($data),
                'data' => $data,
                '_serialize' => array('data')));
        } else {
            $this->set(array(
                'status' => false,
                'data' => '',
                '_serialize' => array('data')));
        }
    }

    public function saveall(){
        $this->set(array(
            'deal' => $this->Deal->updateAll(["publish_date" => "'".date('Y-m-d H:i:s')."'"]),
            '_serialize' => array('deal')
        ));
    }
}
