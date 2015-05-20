<?php
App::uses('AppController', 'Controller');

class PropertiesController extends AppController
{

    var $uses = ['Property', 'Option'];

    public function index()
    {
        $options = $this->Property->buildOptions($this->params->query);

        $properties = $this->Property->find('all', $options);

//        if (!empty($options['limit'])) {
//            unset($options['limit']);
//        }
//        $count = $this->Property->find('count', $options);
        $this->set(
            [
            'properties' => $properties,
//            'count' => $count,
            '_serialize' => ['properties', 'count']
            ]);
    }

    public function add()
    {
        $uID = $this->getParam('uID');
        $meta_key = $this->getParam('meta_key');
        $meta_value = $this->getParam('meta_value');
        $resp = $this->Property->save(['user_id' => $uID, 'meta_key' => $meta_key, 'meta_value' => $meta_value]);
        $this->set(['data' => json_encode($resp), '_serialize' => ['data']]);
    }

    public function getAds(){
        $ads = $this->Property->find('all', ['conditions' => ['key LIKE' => 'ad_%']]);
        $this->set(['ads' => $ads, '_serialize' => ['ads']]);
    }
}
?>