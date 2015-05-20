<?php
use Aws\Common\Aws;
use Aws\S3\Enum\CannedAcl;
class AdsController extends AppController {
   public $uses = ['Property'];
   public function index() {
    $ads = $this->Property->find('all', ['conditions' => ['key LIKE' => 'ad_%']]);
    $this->set('ads', $ads);
}
public function save(){
    $this->response->type('json');
    $pos = $this->request->data('pos');
    $image = $this->request->data('image');
    $url = $this->request->data('des');
    $resp['rs'] = $this->Property->save(['key' => $pos, 'foreign_key_left' => $image, 'foreign_key_right' => $url]);
    $this->response->body(json_encode($resp));
    return $this->response;
}
public function delete(){
    $this->response->type('json');
    $resp = [];
    $id = $this->request->data('id');
    $resp['status'] = $this->Property->delete($id);
    $this->response->body(json_encode($resp));
    return $this->response;
}
}