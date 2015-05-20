<?php
/**
 * Created by PhpStorm.
 * User: Phuong
 * Date: 4/4/2015
 * Time: 11:11 AM
 */

class LikesController extends AppController{

    public function submit(){
        $this->response->statusCode(200);
        $this->response->type('json');
        if ($this->request->is('post')) {
            $data = $this->request->data;
            if (!$this->Session->check('User.id')) {
                $response = ['status' => 'error',
                    'msg' => 'Please login!'];
                $this->response->body(json_encode($response));
                return $this->response;
            }
            $data['user_id'] = $this->Session->read('User.id');
            $like = $this->mCusApi->resource('Like')->request('/add', [
                'method' => 'POST',
                'data' => $data
            ]);
            $response = ['status' => 'success',
                'msg' => 'Like coupon successful',
                'like' => $like['like'],
                'cm' => $like['command']];
        }else
            $response = ['status' => 'error',
                'msg' => 'Like false'];
        $this->response->body(json_encode($response));
        return $this->response;
    }
}