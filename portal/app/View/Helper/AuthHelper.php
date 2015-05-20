<?php

App::uses('AppHelper', 'View/Helper');
App::uses('Auth', 'Lib');

class AuthHelper extends AppHelper
{
    public $helpers = ['Session'];

    public function user($field = null) {
        if ($this->Session->check('Auth.User.Id')) {
            return Auth::Instance()->user($field);
        } else {
            return false;
        }
    }

    public function account($field = null) {
        if ($this->Session->check('Auth.User.Id')) {
            return Auth::Instance()->account($field);
        } else {
            return false;
        }
    }

    public function permission($key = null) {
        if ($this->Session->check('Auth.User.Id')) {
            return Auth::Instance()->permission($key);
        } else {
            return false;
        }
    }

    public function is($what) {
        if ($this->Session->check('Auth.User.Id')) {
            return Auth::Instance()->is($what);
        } else {
            return false;
        }
    }
}