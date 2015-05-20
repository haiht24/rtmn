<?php
/**
 * Created by PhpStorm.
 * User: Phuong
 * Date: 3/13/2015
 * Time: 9:22 AM
 */

class ErrorsController extends AppController {
    public $name = 'Errors';

    public function beforeFilter() {
        parent::beforeFilter();
//        $this->Auth->allow('error404');
    }

    public function error404()
    {
//        $this->layout = 'error';
        $stores = $this->mCusApi->resource('Store')->query(array(
            'limit' => 6,
            'status' => 'published',
            'show_in_homepage' => 1));
        $this->set('stores', $stores['stores']);
    }
}