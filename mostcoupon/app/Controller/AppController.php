<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');
App::uses('GeoIP', 'Lib');
App::import('Lib/Error', 'Exceptions', array('file' => 'Exceptions.php'));

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    // we have to set this to false because we are not using any datasource
    public $uses = null;
    public $layout = 'manage';
    public $components = array(
        'Session', 'Cookie', 'mCusApi', 'RequestHandler', 'Permission',
        'Sanction.Permit' => array('path' => 'Auth', 'check' => 'User.Id')
    );
    public $helpers = array(
        'Html',
        'Form', 'Text',
        'Session', 'Time', 'Ng', 'Text','Country'
    );

    public function beforeFilter() {
        // setup cookie
        $this->Cookie->name = 'mcus';
        $this->Cookie->key = '4beea1300a0bd99b7e8dc64b59a99aba';
        $this->_setUser();
        if ($this->Cookie->check('timeZone')) {

        } else {
            $this->Cookie->write('timeZone', $this->_getTimeZone());
        }
        $this->set('timeZone', $this->Cookie->read('timeZone'));
        Configure::write('Config.language', 'en');
        if ($this->Cookie->check('allCategories')) {
        } else {
            $categories = $this->mCusApi->resource('Category')->query([
                'status' => 'published',
                'fields' => ['Category.id', 'Category.name', 'Category.alias']
            ]);
            $this->Cookie->write('allCategories', $categories['categories']);
        }
        $this->set('allCategories', $this->Cookie->read('allCategories'));
        $this->set('public_key', Configure::read('reCaptcha.public_key'));
        if ($this->Cookie->check('events')) {
        } else {
            $events = $this->mCusApi->resource('Event')->query([
                'status' => 'published',
                'fields' => ['id', 'name']
            ]);
            $this->Cookie->write('events', $events['events']);
        }
        $this->set('events', $this->Cookie->read('events'));
    }

    private function _setUser() {
        $user = $this->Permission->user();
        $this->CurrentUser = $user;
        $this->set('user', $this->CurrentUser);
    }

    protected function _getTimeZone()
    {
        $ip = $this->request->clientIp(false);
        $geoRequest = GeoIP::lookup($ip);
        if (isset($geoRequest['time_zone'])) {
            return $geoRequest['time_zone'];
        }
        return 'UTC';
    }
}
