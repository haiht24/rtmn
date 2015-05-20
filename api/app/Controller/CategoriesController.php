<?php

App::uses('AppController', 'Controller');

class CategoriesController extends AppController
{

    public function index()
    {
        $options = $this->Category->buildOptions($this->params->query);
        $options['conditions'] = ['Category.status' => 'published'];
//            $options['joins'] = [
//                [
//                    'table' => 'stores',
//                    'alias' => 'Store',
//                    'type' => 'INNER',
//                    'conditions' => [
//                        'Store.status' => 'published',
//                        "Store.categories_id LIKE '%'|| Category.id  ||'%'"
//                    ]
//                ]
//            ];
        if (!empty($this->request->query['store_count >'])) {
            $options['conditions'][] = 'Category.store_count > 0';
        }
        if (!empty($this->request->query['unbindModel'])) {
            $this->Category->unbindModel(
                $this->request->query['unbindModel']
            );
        }
        if (!empty($this->request->query['unbindAll'])) {
            $this->Category->unbindAll();
        }
        if (!empty($this->request->query['alias'])) {
            $options['conditions'][] = "Category.alias = '" . $this->request->query['alias'] . "'";
            $categories = $this->Category->find('first', $options);
        } else
            $categories = $this->Category->find('all', $options);

        $this->set(array(
            'categories' => $categories,
            '_serialize' => array('categories')
        ));
    }

    public function view($id)
    {
        $this->set(array(
            'category' => $this->Category->findById($id),
            '_serialize' => array('category')
        ));
    }

}