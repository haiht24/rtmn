<?php
App::uses('AppController', 'Controller');

class DealsController extends AppController {
    public $uses = ['Category', 'Store', 'Coupon', 'Deal', 'CategoriesStore', 'User'];
    public function index() {
        $this->Category->unbindAllExcept('father');
        $categories = $this->Category->find('all', ['fields' => ['category.id', 'category.name', 'category.parent_id'],
                                                    'conditions' => ['category.status' => 'published']]);
        if(!empty($categories)) {
            $listCategories = from($categories)->orderBy('$v["category"]["name"]')->select('$v')->toList();
        }
        $this->User->unbindAll();
        $this->set('users',$this->User->find('all',['fields' => ['user.id', 'user.fullname']]));
        $this->set('categories',$listCategories);
    }
    
    public function queryDeal() {
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
                            'LOWER(deal.title) LIKE' => '%'.strtolower($this->params->query['filter_name']).'%',
                            'LOWER(deal.status) LIKE' => '%'.strtolower($this->params->query['filter_name']).'%'
                        ]];
        }
        
        if (!empty($this->params->query['user_id'])) {
            $options['conditions'][] = ['deal.user_id' => $this->params->query['user_id']];
        }
        
        if (!empty($this->params->query['created'])) {
            $options['conditions'][] = ['deal.created LIKE' => '%'.$this->params->query['created'].'%'];
        }
        
        if (!empty($this->params->query['created_from'])) {
            $options['conditions'][] = ['Date(deal.created) >= ' => $this->params->query['created_from']];
        }
        
        if (!empty($this->params->query['created_to'])) {
            $options['conditions'][] = ['Date(deal.created) <=' => $this->params->query['created_to']];
        }
        
        if (!empty($this->params->query['publish_date'])) {
            $options['conditions'][] = ['deal.publish_date LIKE' => '%'.$this->params->query['publish_date'].'%'];
        }
        
        if (!empty($this->params->query['start_date'])) {
            $options['conditions'][] = ['deal.start_date LIKE' => '%'.$this->params->query['start_date'].'%'];
        }
        
        if (!empty($this->params->query['expire_date'])) {
            $options['conditions'][] = ['deal.expire_date LIKE' => '%'.$this->params->query['expire_date'].'%'];
        }
        
        if (!empty($this->params->query['status'])) {
            $options['conditions'][] = ['deal.status LIKE' => '%'.$this->params->query['status'].'%'];
        }
        $sortBy = 'DESC';
        $sortField = 'created';
        if (!empty($this->params->query['sort_field'])) {
            $sortField = $this->params->query['sort_field'];
            if (!empty($this->params->query['sort_by'])) {
                $sortBy = $this->params->query['sort_by'];
            }
        }
        $options['order'] = 'deal.'.$sortField.' '.$sortBy;
        $deals = $this->Deal->find('all',$options);
        $count = $this->Deal->find('count', array_merge($options, [
            'limit' => false
        ]));
        $response = ['deals' => $deals, 'count' => $count];
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function deleteDeal($id){
        $this->response->type('json');
        $this->Deal->delete(array('deal.id' => $id));
        $response = ['status' => true, 'message' => null];
        $this->response->body(json_encode($response));
        return $this->response;
    }
    
    public function saveDeal() {
        $this->response->type('json');
        if ($this->request->is('post') && !empty($this->request->data)) {
            $response = ['status' => false, 'message' => null, 'deal' => []];
            if (empty($this->request->data['id'])) {
                $this->request->data['user_id'] =$this->CurrentUser['user']['id'];
            }
            if (isset($this->request->data['status'])) {
                if ($this->request->data['status'] == 'published') {
                    $this->request->data['publish_date'] = date('Y-m-d H:i:s');
                }
            }

            if (empty($this->request->data['start_date'])) {
                $this->request->data['start_date'] = date('Y-m-d H:i:s');
            }
            $deal = $this->Deal->save($this->request->data);
            if(!$deal) {
                $response['message'] = $this->Deal->validationErrors;
            } else {
                $response['status'] = true;
                $response['deal'] = $deal;//$this->Deal->findById($deal['deal']['id']);
            }
            $this->response->body(json_encode($response));
        }
        return $this->response;
    }

    public function changeStatusDeal() {
        if ($this->request->is('post') && !empty($this->request->data)) {
            if (!empty($this->request->data['pk']) && !empty($this->request->data['value'])) {
                $findItem = $this->Deal->findById($this->request->data['pk']);
                if (!empty($findItem)) {
                    $data = array('id' => $this->request->data['pk'], 'status' => $this->request->data['value']);
                    if ($this->request->data['value'] == 'published') {
                        $data['publish_date'] = date('Y-m-d H:i:s');
                    }
                    $this->Deal->save($data);
                }
            }
        }
        $this->response->type('json');
        $this->response->statusCode(200);
        $response = ['status' => 'deal', 'msg' => $this->request->data['pk']];
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function deleteDeals()
    {
        $response = [];
        if ($this->request->is('post') && !empty($this->request->data)) {
            if (!empty($this->request->data['ids'])) {
                if (count($this->request->data['ids']) > 1) {
                    $this->Deal->deleteAll(array('deal.id in' => $this->request->data['ids']), false);
                } else $this->Deal->delete(array('deal.id' => $this->request->data['ids'][0]));
                $response = ['status' => true, 'message' => null];
            }
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
        return $this->response;
    }
}