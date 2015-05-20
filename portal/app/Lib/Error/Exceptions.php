<?php 

/**
 * Base class for all scout2go user level exceptions
 */
class mCusException extends CakeException {

    public function __construct($args, $code = 500) {
        parent::__construct($args, $code);
    }
};

class NotAllowedRequestException extends mCusException {

    public function __construct($msg = 'You are not allowed to execute this request') {
        parent::__construct($msg, 401);
    }
}

class MissingDataException extends mCusException {

    public function __construct($msg) {
        parent::__construct($msg);
    }
}

class InvalidDataException extends mCusException {

    public function __construct($validationErrors = null) {
        $message = 'Validation error';
        if(is_string($validationErrors)) {
            $message = $validationErrors;
        } else if(is_array($validationErrors)) {
            while (is_array($validationErrors)) {
                $validationErrors = array_pop($validationErrors);
            }
            $message = $validationErrors;
        }
        parent::__construct($message, 400);
    }
}

class IncorrectCredentialsException extends mCusException {

    public function __construct($message = 'Incorrect credentials supplied') {
        parent::__construct($message, 401);
    }
}

class InvalidIncentiveException extends mCusException {
    
    public function __construct() {
        parent::__construct('Invalid incentive');
    }
}




?>