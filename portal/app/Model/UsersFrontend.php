<?php

class UsersFrontend extends AppModel {
    public $useTable = 'users';

    function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
    }

    public $validate =
    [
        'email' => [
//            'rule1' =>
//            [
//                'rule' => ['email', true],
//                'message' => 'Please supply a valid email address',
//                'required' => 'create'
//            ],
            'rule2' =>
            [
                'rule' => 'isUnique',
                'message' => 'This email already exists'
            ],
        ],
        'username' =>
        [
            'rule1' =>
            [
                'rule' => 'isUnique',
                'message' => 'This username already exists'
            ],
//            'rule2' =>
//            [
//                'rule' => ['maxLength', 45],
//                'message' => 'Username cannot be longer than 45 characters.'
//            ],
//            'rule3' =>
//            [
//                'rule' => 'alphaNumeric',
//                'message' => 'Letters and numbers only'
//            ]
        ],
        'fullname' =>
        [
            'rule' => ['maxLength', 45],
            'message' => 'Fullname cannot be longer than 45 characters.'
        ],
//        'password' =>
//        [
//            'rule'       => ['between', 5, 45],
//            'message'    => 'Password should be at least 5 characters long',
//            'required'   => 'create',
//            'allowEmpty' => false
//        ],
        'status' =>
        [
            'rule' => ['inList', ['active', 'inactive', 'lock']],
            'message' => 'Invalid status',
        ],
    ]
    ;

    protected static function _generateSalt($length = 22) {
        $salt = str_replace(
            ['+', '='], '.', base64_encode(sha1(uniqid(Configure::read('Security.salt'), true), true))
        );
        return substr($salt, 0, $length);
    }

    protected function _encrypt($password, $salt) {
        return sha1(sha1(sha1($password)) . $salt);
    }

    //add timezone if not have in creat new user.
    public function beforeValidate($options = []) {
        parent::beforeValidate($options);
    }

    public function beforeFind($queryData) {
        if (isset($queryData['conditions']['password'])) {
            $queryData['conditions']['password = SHA1(CONCAT(SHA1(SHA1(?)),user.salt))'] = $queryData['conditions']['password'];
            unset($queryData['conditions']['password']);
        }
        return $queryData;
    }

    public function beforeSave($options = []) {
        $resutl = parent::beforeSave($options);
        // encrypt
        if (!empty($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['salt'] = $this->_generateSalt();
            $this->data[$this->alias]['password'] = $this->_encrypt($this->data[$this->alias]['password'], $this->data[$this->alias]['salt']);
        }
        return $resutl;
    }

}
