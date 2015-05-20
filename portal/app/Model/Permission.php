<?php
App::uses('AppModel', 'Model');

class Permission extends AppModel
{
    public $alias = 'permission';
    public $useTable = 'role_permissions';
}
