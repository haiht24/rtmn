<?php
/**
 * Created by PhpStorm.
 * User: Phuong
 * Date: 4/4/2015
 * Time: 11:20 AM
 */
App::uses('AppController', 'Controller');
class LikesController extends AppController{
    public function add()
    {
        $data = $this->request->data;
        if (!empty($data)) {
            $response = [];
            $like = $this->Like->find('first',[
                'conditions' => ['user_id' => $data['user_id'], 'object_id' => $data['object_id']]
            ]);
            if ($like) {
                $this->Like->id = $like['Like']['id'];
                if ($data['value'] == 1) {
                    if ($like['Like']['value'] == 1) {
                        $data['value'] = 0;
                        $response = ['like' => $this->Like->save($data),
                            'command' => 'edit',
                            '_serialize' => ['like','command']];
                    }
                }else {
                    if (!empty($like)) {
                        if ($like['Like']['value'] == -1) {
                            $data['value'] = 0;
                            $response = ['like' => $this->Like->save($data),
                                'command' => 'edit',
                                '_serialize' => ['like','command']];
                        }
                    }
                }
            }else {
                $this->Like->create();
                $response = ['like' => $this->Like->save($data),
                    'command' => 'create',
                    '_serialize' => ['like','command']];
            }
            if (sizeof($response) == 0) {
                $response = ['like' => $this->Like->save($data),
                    'command' => 'edit',
                    '_serialize' => ['like','command']];
            }
            $this->set($response);
        } else {
            $this->set([
                'like' => false,
                'command' => '',
                '_serialize' => ['like','command']]);
        }
    }
}