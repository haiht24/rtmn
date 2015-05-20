<?php

App::uses('CakeLog', 'Log');

class Auth {

    protected $data = [];

    // singleton
    private function __construct()
    {
        App::uses('UsersController', 'Controller');
        $controller = new UsersController();
        $this->data = $controller->sessionData();
        // TODO: check session first
//        if (!empty($this->data['user']) && !empty($this->data['user']['id'])) {
//            $user = ClassRegistry::init('UserProperty');
//            $this->data['user'] = array_merge($user->getAll($this->data['user']['id']), $this->data['user']);
//        }
//
//        if (!empty($this->data['account']) && !empty($this->data['account']['id'])) {
//            $account = ClassRegistry::init('AccountProperty');
//            $this->data['account'] = array_merge($account->getAll($this->data['account']['id']), $this->data['account']);
//        }
    }

    public static function Instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new Auth();
        }
        return $inst;
    }

    public function user($field = null)
    {
        if (empty($this->data['user'])) {
            return false;
        }
        if (is_null($field)) {
            return $this->data['user'];
        }
        return isset($this->data['user'][$field]) ? $this->data['user'][$field] : false;
    }

    public function permission($key = null)
    {
        if (empty($this->data['permissions'])) {
            return false;
        }
        if (is_null($key)) {
            return $this->data['permissions'];
        }
        return isset($this->data['permissions'][$key]) ? $this->data['permissions'][$key] : false;
    }

    public function is($what)
    {
        switch ($what) {
            case 'admin':
                return !empty($this->data['user']) && !empty($this->data['user']['group']) &&
                $this->data['user']['group'] == $what;
            case 'owner':
                return !empty($this->data['user']) && !empty($this->data['user']['id']) &&
                !empty($this->data['account']) && !empty($this->data['account']['owner_id']) &&
                $this->data['account']['owner_id'] == $this->data['user']['id'];
            case 'read':
                return !empty($this->data['privilege']) && $this->data['privilege'] == $what;
            case 'write':
                return !empty($this->data['privilege']) && $this->data['privilege'] == $what;
        }
        return false;
    }

}