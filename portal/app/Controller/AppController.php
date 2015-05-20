<?php

App::uses('Controller', 'Controller');

class AppController extends Controller
{
    public $layout = 'admin';

    public $components = [
        'Session',
        'Cookie',
        'RequestHandler',
        'Permission',
        'Sanction.Permit' => array('path' => 'Auth', 'check' => 'User.Id')
    ];

    public $helpers = [
        'Html',
        'Form',
        'Country',
        'Session',
        'Time'
    ];

    public function beforeFilter()
    {
        $this->initCookies();
        $this->setTimezone();
        $this->_setUser();
    }
    
    private function _setUser() {
        $user = $this->Permission->user();
        $this->CurrentUser = $user;
        $this->set('user', $this->CurrentUser);
    }

    private function setTimezone()
    {
//        if (!empty($this->Auth->user('time_zone'))) {
//            Configure::write('Config.timezone', $this->Auth->user('time_zone'));
//        } else {
            Configure::write('Config.timezone', 'UTC');
//        }
    }

    private function initCookies()
    {
        $this->Cookie->name = 'MCUS';
        $this->Cookie->key = '4beea1300a0bd99b7e8dc64b59a99aba';
    }

    public function appError(Exception $exception)
    {
        try {
            $this->response->statusCode($exception->getCode());
        } catch (Exception $e) {
            $this->response->statusCode(500);
        }

        $template = Inflector::underscore(str_replace('Exception', '', get_class($exception)));

        $this->set('error', $exception);
        try {
            $this->renderError($template);
        } catch (Exception $e) {
            $this->renderError('error');
        }
    }

    private function renderError($template)
    {
        $this->layout = 'error';
        $this->viewPath = 'Errors';
        $this->render($template);
        $this->afterFilter();
        $this->response->send();
    }
}
