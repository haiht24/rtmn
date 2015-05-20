<?php

App::uses('AppController', 'Controller');

class PagesController extends AppController
{

    public function home()
    {
        // the landing page should be handled by the CMS on live and stage
        // for local development we redirect to the login page
        $this->redirect(
            [
                'controller' => 'users',
                'action' => 'login'
            ]
        );
    }

    public function display()
    {
        $path = func_get_args();

        $count = count($path);
        if (!$count) {
            return $this->redirect('/');
        }
        $page = $subpage = $title_for_layout = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        if (!empty($path[$count - 1])) {
            $title_for_layout = Inflector::humanize($path[$count - 1]);
        }
        $this->set(compact('page', 'subpage', 'title_for_layout'));

        try {
            $this->render(implode('/', $path));
        } catch (MissingViewException $e) {
            if (Configure::read('debug')) {
                throw $e;
            }
            throw new NotFoundException();
        }
    }

    public function templates()
    {
        $this->layout = 'ajax';
        $path = func_get_args();
        if (count($path) < 2) { // we need at least a controller part and a view part
            throw new NotFoundException();
        }
        // check if we have to load a template from a plugin
        $plugin = false;
        if (in_array($path[0], array_map(['Inflector', 'underscore'], App::objects('plugins')))) {
            $plugin = Inflector::camelize($path[0]);
            array_shift($path);
        }
        $path[0] = Inflector::camelize($path[0]);
        array_splice($path, 1, 0, 'Ajax');
        try {
            $this->render(($plugin ? $plugin . '.' : '/') . implode('/', $path));
        } catch (MissingViewException $e) {
            if (Configure::read('debug')) {
                throw $e;
            }
            throw new NotFoundException();
        }
    }

    public function notImplemented($uri)
    {
        throw new PageNotFoundException($uri);
    }
}