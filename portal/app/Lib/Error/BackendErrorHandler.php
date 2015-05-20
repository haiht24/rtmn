<?php

App::uses('ErrorHandler', 'Error');
App::uses('CakeEmail', 'Network/Email');

class BackendErrorHandler extends ErrorHandler {

    /**
     * Overwrite exception handler to send emails in production mode for non user level exceptions
     */
	public static function handleException(Exception $exception) {
		if (Configure::read('debug') == 0 &&  !($exception instanceof mCusBackendException)) {
		    static::reportError(self::_getMessage($exception));
		    $exception = new BadRequestException();
		}		
		parent::handleException($exception);
        if($exception instanceof mCusBackendException) {
            CakeLog::write(LOG_WARNING, self::_getMessage($exception));
        }
	}

	/**
	 * Overwrite error handler to throw a fatal error exception on every error
	 */
	public static function handleError($code, $description, $file = null, $line = null, $context = null) {
	    if (error_reporting() === 0) {
			return false;
		}
		return static::handleFatalError($code, $description, $file, $line);
	}
	
	
}
