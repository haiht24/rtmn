<?php
/**
 * This renderer is used to display custom Scout2GoFrontendExceptions also if debug == 0
 */
App::uses('ExceptionRenderer', 'Error');

class mCusFrontendExceptionRenderer extends ExceptionRenderer
{

    public function __construct(Exception $exception)
    {

        parent::__construct($exception);

        if ($exception instanceof mCusFrontendException) {
            if (isset($exception->viewVars)) {
                foreach ($exception->viewVars as $var => $val) {
                    $this->controller->set($var, $val);
                }
            }
            $this->controller->layout = 'default';
            $this->method = '_cakeError';
        }
    }

    public function notFound($error)
    {
        $this->controller->redirect(array('controller' => 'errors', 'action' => 'error404'));
    }

    public function badRequest($error)
    {

    }

    public function forbidden($error)
    {

    }

    public function methodNotAllowed($error)
    {

    }

    public function internalError($error)
    {

    }

    public function notImplemented($error)
    {

    }

    public function missingController($error)
    {
        $this->notFound($error);
    }

    public function missingAction($error)
    {
        $this->notFound($error);
    }

    public function missingView($error)
    {
        $this->notFound($error);
    }

    public function missingLayout($error)
    {
        $this->internalError($error);
    }

    public function missingHelper($error)
    {
        $this->internalError($error);
    }

    public function missingBehavior($error)
    {
        $this->internalError($error);
    }

    public function missingComponent($error)
    {
        $this->internalError($error);
    }

    public function missingTask($error)
    {
        $this->internalError($error);
    }

    public function missingShell($error)
    {
        $this->internalError($error);
    }

    public function missingShellMethod($error)
    {
        $this->internalError($error);
    }

    public function missingDatabase($error)
    {
        $this->internalError($error);
    }

    public function missingConnection($error)
    {
        $this->internalError($error);
    }

    public function missingTable($error)
    {
        $this->internalError($error);
    }

    public function privateAction($error)
    {
        $this->internalError($error);
    }
}

?>