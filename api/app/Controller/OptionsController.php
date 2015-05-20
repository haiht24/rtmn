<?php
App::uses('AppController', 'Controller');

class OptionsController extends AppController {
	var $uses = ['Option'];
	public function index() {
		$option = $this->Option->find('all');
		$this->set(array('option' => $option, '_serialize' => array('option')));
	}
	public function getByOptionName() {
		$option = $this->Option->find('all', ['conditions' => ['Option.option_name' => $this->getParam('option_name')]]);
		$this->set(array('option' => $option, '_serialize' => array('option')));
	}	
}
