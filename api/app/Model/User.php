<?php

class User extends AppModel {
    public $useTable = 'users';
    public $useDbConfig = 'default';

    function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
    }

    public $order = 'User.created DESC';

    public $validate = array(
        'email' => array(
//            'rule1' => array(
//                'rule' => array('email', true),
//                'message' => 'Please supply a valid email address',
//                'required' => 'create'
//            ),
            'rule2' => array(
                'rule' => 'isUnique',
                'message' => 'This email already exists'
            ),
        ),
//        'username' => array(
//            'rule1' => array(
//                'rule' => 'isUnique',
//                'message' => 'This username already exists'
//            ),
//            'rule2' => array(
//                'rule' => array('maxLength', 45),
//                'message' => 'Username cannot be longer than 45 characters.'
//            ),
//            'rule3' => array(
//                'rule'     => 'alphaNumeric',
//                'message'  => 'Letters and numbers only'
//            )
//        ),
        'fullname' => array(
            'rule' => array('maxLength', 45),
            'message' => 'Fullname cannot be longer than 45 characters.'
        ),
        /* 'password' => array(
        'rule'       => array('between', 5, 45),
        'message'    => 'Password should be at least 5 characters long',
        'required'   => 'create',
        'allowEmpty' => false
        ), */
        'status' => array(
            'rule' => array('inList', array('active', 'inactive', 'deleting')),
            'message' => 'Invalid status',
        ),
    );

    protected static function _generateSalt($length = 22) {
        $salt = str_replace(
            array('+', '='), '.', base64_encode(sha1(uniqid(Configure::read('Security.salt'), true), true))
        );
        return substr($salt, 0, $length);
    }

    protected function _encrypt($password, $salt) {
        return sha1(sha1(sha1($password)) . $salt);
    }

    //add timezone if not have in creat new user.
    public function beforeValidate($options = array()) {
        parent::beforeValidate($options);
    }

    public function beforeFind($queryData) {
        if (isset($queryData['conditions']['User.password'])) {
            $queryData['conditions']['User.password = SHA1(CONCAT(SHA1(SHA1(?)),"User".salt))'] = $queryData['conditions']['User.password'];
            unset($queryData['conditions']['User.password']);
        }
        return $queryData;
    }

    public function beforeSave($options = array()) {
        $result = parent::beforeSave($options);
        // encrypt
        if (!empty($this->data[$this->alias]['password'])) {
            if (!isset($this->data[$this->alias]['salt'])) {
                $this->data[$this->alias]['salt'] = $this->_generateSalt();
            }
            $this->data[$this->alias]['password'] = $this->_encrypt($this->data[$this->alias]['password'], $this->data[$this->alias]['salt']);
        }
        return $result;
    }

    public function generatePassword ($length = 8){
        // inicializa variables
        $password = "";
        $i = 0;
        $possible = "0123456789bcdfghjkmnpqrstvwxyz";

        // agrega random
        while ($i < $length){
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

            if (!strstr($password, $char)) {
                $password .= $char;
                $i++;
            }
        }
        return $password;
    }

    public function generateToken(){
        $token = md5(uniqid(mt_rand(), true));
        return $token;
    }
}
