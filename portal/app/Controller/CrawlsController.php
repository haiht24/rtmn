<?php
 use Aws\Common\Aws;
 use Aws\S3\Enum\CannedAcl;
 class CrawlsController extends AppController {
     public $helper = array(
         'Html',
         'Form',
         'Javascript',
         'Ajax');
     var $components = array('RequestHandler');

     public function index() {
         //$this->layout = 'default';
     }
     public function crawl($url = null) {
         $this->autoRender = false;
         if ($this->request->is('post')) {
             $url = $this->request->data['vl'];
             $parentClass = $this->request->data['cl'];
             $attName = $this->request->data['attName'];
             $newFields = $this->request->data['newFields'];

             if ($url) {
                 App::import('Vendor', 'SimpleHtmlDom', 'simple_html_dom.php');
                 $html = file_get_html($url);
                 $arrTotal = array();
                 $arrAtt = array();
                 $arrResultNewField = array();
                 $DomParent = $html->find($parentClass);
                 foreach ($html->find($parentClass) as $DomParent) {
                     // if get Href attribute
                     if ($attName == 'href') {
                         foreach ($DomParent->find('a') as $a) {
                             array_push($arrAtt, $a->href);
                         }
                     }

                     if ($newFields[0] != 'empty') {
                         foreach ($newFields as $n) {
                             $str_n = explode('|', $n);
                             $n = trim($str_n[0]);
                             foreach ($DomParent->find($n) as $nf) {
                                 array_push($arrResultNewField, $nf->innertext . "|" . $str_n[1]);
                             }
                         }
                     }

                 }
                 $arrTotal['att'] = $arrAtt;
                 $arrTotal['resultNewField'] = $arrResultNewField;
                 echo json_encode($arrTotal);
             } else {
                 $arrTotal['err'] = 'Enter URL';
                 echo json_encode($arr);
             }
         }
     }
     public function crawlCategory() {
         $this->autoRender = false;
         if ($this->request->is('post')) {
             $url = $this->request->data['url'];
             $parentClass = $this->request->data['parentClass'];
             $homePage = $this->request->data['homePage'];

             $arrCat = array();
             $arrResult = array();
             if ($url) {
                 App::import('Vendor', 'SimpleHtmlDom', 'simple_html_dom.php');
                 $html = file_get_html($url);
                 $DomParent = $html->find($parentClass);
                 foreach ($DomParent as $dom) {
                     foreach ($dom->find('a') as $a) {
                         if ($a->plaintext != '') {
                             array_push($arrCat, $a->plaintext . "|" . $homePage . $a->href);
                         }
                     }
                 }
             }
             $arrResult['cat'] = $arrCat;
             echo json_encode($arrResult);
         }
     }
     public function crawlStore() {
         $this->autoRender = false;
         if ($this->request->is('post')) {
             $url = $this->request->data['url'];
             $parentClass = $this->request->data['parentClass'];
             $classStoreName = $this->request->data['classStoreName'];
             $classStoreUrl = $this->request->data['classStoreUrl'];
             $homePage = $this->request->data['homePage'];

             $arrStoreName = array();
             $arrResult = array();
             if ($url) {
                 App::import('Vendor', 'SimpleHtmlDom', 'simple_html_dom.php');
                 $html = file_get_html($url);
                 $DomParent = $html->find($parentClass);
                 if ($classStoreName) {
                     foreach ($DomParent as $dom) {
                         if (!$classStoreUrl) {
                             foreach ($dom->find($classStoreName) as $name) {
                                 //array_push($arrStoreName, $name->innertext);
                                 array_push($arrStoreName, trim($name->plaintext));
                             }
                         } else {
                             foreach ($dom->find($classStoreName) as $name) {
                                 $str = trim($name->plaintext) . " | " . $homePage . $name->href;
                                 array_push($arrStoreName, $str);
                             }
                         }
                     }
                 }
             }
             echo json_encode($arrStoreName);
         }
     }
     public function saveCrawlConfig() {
         $this->autoRender = false;
         if ($this->request->is('post')) {
             $arr = array();
             $dbName = $this->request->data['dbName'];
             $type = $this->request->data['type'];
             $arr['dbName'] = $dbName;
             $arr['type'] = $type;
             $arr['URL'] = $this->request->data['URL'];
             $arr['homePage'] = $this->request->data['homePage'];
             $arr['parentClass'] = $this->request->data['parentClass'];
             $arr['clStoreName'] = $this->request->data['clStoreName'];
             $arr['clStoreURL'] = $this->request->data['clStoreURL'];
             $arr['clStoreDesc'] = $this->request->data['clStoreDesc'];
             $arr['clStoreHome'] = $this->request->data['clStoreHome'];
             $arr['clStoreLogo'] = $this->request->data['clStoreLogo'];
             $arr['clBreadcrumb'] = $this->request->data['clBreadcrumb'];
             $arr['clCpParent'] = $this->request->data['clCpParent'];
             $arr['clCpTitle'] = $this->request->data['clCpTitle'];
             $arr['clCpCode'] = $this->request->data['clCpCode'];
             $arr['clCpDesc'] = $this->request->data['clCpDesc'];
             $arr['clCpExpire'] = $this->request->data['clCpExpire'];

             //First Load the Utility Class
             App::uses('Xml', 'Utility');
             $value = array('crawlConfig' => array('database' => array(array(
                             'name' => $this->request->data['dbName'],
                             'type' => $this->request->data['type'],
                             'url' => $this->request->data['URL'],
                             'homePage' => $this->request->data['homePage'],
                             'parentClass' => $this->request->data['parentClass'],
                             'clStoreName' => $this->request->data['clStoreName'],
                             'clStoreURL' => $this->request->data['clStoreURL'],
                             'clStoreDesc' => $this->request->data['clStoreDesc'],
                             'clStoreHome' => $this->request->data['clStoreHome'],
                             'clStoreLogo' => $this->request->data['clStoreLogo'],
                             'clBreadcrumb' => $this->request->data['clBreadcrumb'],
                             'clCpParent' => $this->request->data['clCpParent'],
                             'clCpTitle' => $this->request->data['clCpTitle'],
                             'clCpCode' => $this->request->data['clCpCode'],
                             'clCpDesc' => $this->request->data['clCpDesc'],
                             'clCpExpire' => $this->request->data['clCpExpire'],
                             ))));
             // TEST UPLOAD
             $checkExist = $this->Crawl->query("SELECT database_name FROM crawl WHERE database_name = '{$dbName}' AND type = '{$type}'");
             $xml = Xml::build($value);
             $xml = $xml->asXML();
             // Get return XML Config URL
             $config = $this->_uploadToS3($xml, 'xml');
             //$config = "https://s3-us-west-2.amazonaws.com/dev.mostcoupon.com/5487aa1fd3c84ed78cbb255261af48f5";
             if ($config) {
                 if (count($checkExist) > 0) {
                     $this->Crawl->query("UPDATE crawl SET config = '{$config}' WHERE database_name = '{$dbName}' AND type = '{$type}'");
                 } else {
                     $this->Crawl->query("INSERT IGNORE INTO crawl VALUES('{$arr['dbName']}', '{$config}', '{$type}')");
                 }
             }
             echo $config;
         }
     }
     public function loadCrawlConfig() {
         $this->autoRender = false;
         if ($this->request->is('post')) {
             $dbName = $this->request->data['dbName'];
             $type = $this->request->data['type'];
             $rs = $this->Crawl->query("SELECT config FROM crawl WHERE database_name = '{$dbName}' AND type = '{$type}'");
             if (count($rs) > 0) {
                 $config = $rs[0]['crawl']['config'];
                 $config = simplexml_load_file($config);
                 $config = Xml::toArray($config);
                 echo json_encode($config);
             }
         }

     }
     private function _uploadToS3($content, $contentType) {
         $s3 = Aws::factory(Configure::read('AWS.S3'))->get('s3');
         $return = $s3->putObject(['Bucket' => Configure::read('AWS.S3.bucket'), 'Key' => str_replace('-', '',
             string::uuid()), 'Body' => $content, 'ACL' => CannedAcl::PUBLIC_READ, 'ContentType' => $contentType]);
         return $return['ObjectURL'];
     }
 }
?>
