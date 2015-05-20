<?php
/**
 * Created by PhpStorm.
 * User: Phuong
 * Date: 3/11/2015
 * Time: 11:29 AM
 */
App::uses('AppController', 'Controller');
class EventsController extends AppController{
    public function index(){
        $options = $this->Event->buildOptions($this->params->query);

        $options['order'] = 'Event.created DESC';
        $events = $this->Event->find('all', $options);

//        if (!empty($options['limit'])) {
//            unset($options['limit']);
//        }
//
//        $count =  $this->Event->find('count', $options);

        $this->set(array(
            'events' => $events,
//            'count' => $count,
            '_serialize' => array('events', 'count')
        ));
    }

    public function view($id)
    {
        $this->set(array(
            'event' => $this->Event->findById($id),
            '_serialize' => array('event')
        ));
    }
}