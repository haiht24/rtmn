<?php
App::uses('AppModel', 'Model');

class Event extends AppModel
{
    public $alias = 'event';
    public $useTable = 'events';
    public $cacheQueries = true;

    public $validate = array(
        'name' => array(
            'rule'    => 'isUnique',
            'message' => 'This name already exists'
        )
    );

    public $hasOne = array(
        'author' => array(
            'className' => 'User',
            'foreignKey' => false,
            'conditions' => array('event.user_id = author.id')
        )
    );
}