<?php

App::uses('AppController', 'Controller');

class CategoryStoresController extends AppController
{
    public function index()
    {
        $options = $this->CategoriesStores->buildOptions($this->params->query);
        $options['order'] = 'CategoriesStores.created DESC';

        $coupons = $this->CategoriesStores->find('all', $options);

//        if (!empty($options['limit'])) {
//            unset($options['limit']);
//        }
//        $count = $this->CategoriesStores->find('count', $options);
        $this->set(array(
            'categoriesStores' => $coupons,
//            'count' => $count,
            '_serialize' => array('categoriesStores', 'count')
        ));
    }

    public function add()
    {
        $data = $this->request->data;
        if (!empty($data)) {
            $this->set([
                'status' => $this->Category->CategoriesStore->save($data),
                'data' => $data,
                '_serialize' => array('data')]
            );
        }else {
            $this->set([
                'status' => 'Failed',
                'data' => '',
                '_serialize' => array('data')]
            );
        }
    }

    public function view($id)
    {
        $this->set(array(
            'coupon' => $this->CategoriesStores->findById($id),
            '_serialize' => array('coupon')
        ));
    }
}