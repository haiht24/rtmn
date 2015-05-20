<?php
 class SeoController extends AppController {
     public $helpers = array('Cache');
     public $cacheAction = array('sitemap' => 50000);

     public $helper = ['Html', 'Form', 'Javascript', 'Ajax'];
     var $components = ['RequestHandler'];
     public $uses = ['Seo'];

     public function index() {
         $this->set('Seos', $this->Seo->find('all'));
     }

     public function getExistID($optionName) {
         $result = $this->Seo->find('all', ['conditions' => ['Seo.option_name' => "$optionName"]]);
         if (count($result) > 0) {
             return $result[0]['Seo']['option_id'];
         } else {
             return 0;
         }
     }

     public function saveStoreConfig() {
         $this->response->type('json');
         if ($this->request->is('post') && !empty($this->request->data)) {
             $data = [];
             $optionName = ['seo_storeTitle', 'seo_defaultStoreTitle', 'seo_storeDesc',
                 'seo_defaultStoreMetaDescription', 'seo_storeKeyword', 'seo_defaultStoreMetaKeyword', 'seo_storeH1',
                 'seo_defaultH1Store', 'seo_storeP', 'seo_defaultPStore', 'seo_disableStoreNoIndex'];
             foreach ($optionName as $o) {
                 $data = $this->beforeSave($data, $o);
             }
             $rs = $this->Seo->saveMany($data);
         }
         $response['data'] = $rs;
         $this->response->body(json_encode($response));
         return $this->response;
     }

     public function saveGeneral() {
         $this->response->type('json');
         if ($this->request->is('post') && !empty($this->request->data)) {
             $data = [];
             $optionName = ['seo_siteName', 'seo_siteDescription'];
             foreach ($optionName as $o) {
                 $data = $this->beforeSave($data, $o);
             }
             $rs = $this->Seo->saveMany($data);
         }
         $response['data'] = $rs;
         $this->response->body(json_encode($response));
         return $this->response;
     }

     public function saveHome() {
         $this->response->type('json');
         if ($this->request->is('post') && !empty($this->request->data)) {
             $data = [];
             $optionName = ['seo_homeTitle', 'seo_homeMetaDesc', 'seo_homeMetaKeyword', 'seo_disableHomeNoIndex'];
             foreach ($optionName as $o) {
                 $data = $this->beforeSave($data, $o);
             }
             $rs = $this->Seo->saveMany($data);
         }
         $response['data'] = $rs;
         $this->response->body(json_encode($response));
         return $this->response;
     }

     public function saveCate() {
         $this->response->type('json');
         if ($this->request->is('post') && !empty($this->request->data)) {
             $data = [];
             $optionName = ['seo_CatTitle', 'seo_CatDesc', 'seo_CatKeyword', 'seo_DisableCatNoIndex'];
             foreach ($optionName as $o) {
                 $data = $this->beforeSave($data, $o);
             }
             $this->Seo->create();
             $rs = $this->Seo->saveMany($data);
         }
         $response['data'] = $rs;
         $this->response->body(json_encode($response));
         return $this->response;
     }

     public function beforeSave($data, $keyword) {
         if (!isset($this->request->data[$keyword])) {
             return $data;
         }
         $arr = ['option_name' => $keyword, 'option_value' => $this->request->data[$keyword]];
         if ($this->getExistID($keyword)) {
             $arr['option_id'] = $this->getExistID($keyword);
         }
         array_push($data, $arr);
         return $data;
     }

     public function robots() {
         $this->autoRender = false;
         if (Configure::read('debug')) {
             echo "User-agent: *  \n";
             echo "Disallow: /";
         } else {
             echo "User-agent: * \n";
             echo "Allow: / \n";
             echo "Disallow: /wp-login.php \n";
             echo "Disallow: /wp-admin/ \n";
             echo "Disallow: /wp-content/ \n";
             echo "Disallow: /wp-includes/ \n";
         }
     }

     public function sitemap() {
         $this->autoRender = false;
         if ($this->RequestHandler->ext == 'xml') {
             $result = Cache::read('sitemap', '_seo_');
             if (!$result) {
                 //get from database and show.
                 $this->set('shortkeys', $shortkeys); //
                 $response = $this->render();
                 Cache::write('sitemap', $response, '_seo_');
                 return $response;
             }
             return $result;
         }
     }
 }
?>
