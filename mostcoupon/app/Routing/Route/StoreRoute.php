<?php 

class StoreRoute extends CakeRoute {

    public function parse($url) {
        $result = parent::parse($url);
        
        if($result === false || !isset($result['store'])) {
            return false;
        }
        
        if(in_array($result['store'], $this->getUnallowedStores())) {
            return false;
        }      
       
        return $result;
    }
    
 	
    public static function getUnallowedStores(){
    	
    	$controllers = array_map('strtolower', str_replace('Controller', '', App::objects('Controller')));
    	$controllers = array_merge(Configure::read('UnallowedStores'), $controllers);
    	
    	return $controllers;
    	
    }

}

?>