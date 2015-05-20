<?php

App::uses('CakeTime', 'Utility');

class PermissionComponent extends Component {

    public $components = array('Session');
    public $controller = null;
    public $User = null;
    public $Permission = null;
    public $base = 'Permission';

    function startup(&$controller){
        $this->controller = $controller;
    }

    public function user() {
        if (empty($this->User)) {
            $this->User = ClassRegistry::init('User');
        }
        if (empty($this->Permission)) {
            $this->Permission = ClassRegistry::init('Permission');
        }
        if ($userId = $this->Session->read('Auth.User.Id')) {
            $result = [];
            $user  = $this->User->findById($userId);
            if (!empty($user)) {
                $result['user'] = $user['user'];
                $result['permissions'] = $this->Permission->find('list', [
                    'fields' => ['key', 'value'],
                    'conditions' => ['role_type' => $result['user']['role']]
                ]);
                return $result;
            } else {
                return [];
            }
        } else {
            return [];
        }
    }
}