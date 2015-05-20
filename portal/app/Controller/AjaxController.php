<?php
App::uses('AppController', 'Controller');
class AjaxController extends AppController {
    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = false;
    }

    public function notify_mail() {

    }

    public function notify_list() {

    }

    public function notify_task() {

    }

}