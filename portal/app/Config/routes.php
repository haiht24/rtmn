<?php
    /**
    * Routes configuration
    *
    * In this file, you set up routes to your controllers and their actions.
    * Routes are very important mechanism that allows you to freely connect
    * different urls to chosen controllers and their actions (functions).
    *
    * PHP 5
    *
    * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
    * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
    *
    * Licensed under The MIT License
    * Redistributions of files must retain the above copyright notice.
    *
    * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
    * @link          http://cakephp.org CakePHP(tm) Project
    * @package       app.Config
    * @since         CakePHP(tm) v 0.2.9
    * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
    */
    /**
    * Here, we are connecting '/' (base path) to controller called 'Pages',
    * its action called 'display', and we pass a param to select the view file
    * to use (in this case, /app/View/Pages/home.ctp)...
    */

    if (file_exists(WWW_ROOT.'.maintenance')) {
        Router::connect('/*', ['controller' => 'pages', 'action' => 'maintenance']);
    } else {
        Router::connect('/upload/**', ['controller' => 'upload', 'action' => 'index']);
        Router::connect('/css/**', ['controller' => 'less', 'action' => 'index']);
        Router::connect('/proxy/**', ['controller' => 'proxy', 'action' => 'index']);
        Router::connect('/robots', ['controller' => 'seo', 'action' => 'robots']);
        Router::connect('/sitemap', ['controller' => 'seo', 'action' => 'sitemap']);

        /**
        * Map controller that dont need translations
        */
        foreach (['session'] as $controller) {
            Router::connect('/'.$controller.'/:action/*', ['controller' => $controller,]);
        }

        /**
        * Route to home
        */
        Router::connect('/', ['controller' => 'home', 'action' => 'index']);

        /* Connect templates */
        Router::connect('/templates/*', ['controller' => 'pages', 'action' => 'templates']);
        /* Connect Content Management */
        Router::connect("/contents", ['controller' => 'products', 'action' => 'index']);
        /**
        * Map customized routes
        */

        Router::connect("/activation/:token",
            ['controller' => 'users', 'action' => 'activation'],
            ['pass' => ['token']]
        );


        /**
        * Default routes
        */
        Router::connect('/:controller', ['action' => 'index']);
        Router::connect('/:controller/:action/*');





        /**
        * Load all plugin routes.  See the CakePlugin documentation on
        * how to customize the loading of plugin routes.
        */
        CakePlugin::routes();

        /**
        * Load the CakePHP default routes. Remove this if you do not want to use
        * the built-in default routes.
        */
        require CAKE . 'Config' . DS . 'routes.php';

        Router::parseExtensions();
    }

