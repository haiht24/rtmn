<?php
App::uses('AppController', 'Controller');
App::uses('File', 'Utility');

class RtmnController extends AppController
{
    public $uses = ['RtmnUrls'];

    public function index(){
        // $this->autoRender = false;
    }

    public function index_2(){
        $this->view = 'index_2';
    }

    public function start(){
        $this->autoRender = false;
        App::import('Vendor', 'SimpleHtmlDom', 'simple_html_dom.php');

        $target = $this->request->data['target'];
        $send = $this->request->data['send'];
        $send = $this->processDataFromSource($send, $target);

        $html = new simple_html_dom();
        $html->load($send);

        $botDetected = $html->find('textarea[name="recaptcha_challenge_field"]', 0) ? true : false;
        if($botDetected){
            return json_encode('error');
        }

        $response = [];
        $store = [];
        $arrCoupons = [];
        $rtmnHome = 'www.retailmenot.com';
        // Store information
        $rtmn_url = $this->request->data['rtmn_url'];
        $store['rtmn_url'] = $this->request->data['rtmn_url'];
        $store['url'] = explode('/', $rtmn_url)[count(explode('/', $rtmn_url)) - 1];
        $store['name'] = $html->find('.site-title', 0)->innertext;
        $store['logo'] = $html->find('a[class="logo-wrapper"] img', 0)->src;
        $store['verifiedCoupons'] = $html->find('span[class="content-quality-signal--verified"]', 0)
        ? trim($html->find('span[class="content-quality-signal--verified"]', 0)->innertext) : '';
        $store['averageSavings'] = $html->find('span[class="content-quality-signal--average-savings"]', 0)
        ? trim($html->find('span[class="content-quality-signal--average-savings"]', 0)->innertext) : '';
        //taxonomy
        $store['taxonomy'] = $html->find('div[class="taxonomy"]', 0)
        ? trim(strip_tags($html->find('div[class="taxonomy"]', 0)->innertext)) : '';

        $recommended = [];
        foreach ($html->find('div[class="recommended"] a') as $rc) {
            array_push($recommended, $rtmnHome . $rc->href);
        }
        $store['recommended'] = $recommended;

        $similar = [];
        foreach ($html->find('div[class="related"] a') as $rc) {
            array_push($similar, $rtmnHome . $rc->href);
        }
        $store['similar'] = $similar;

        $popular = [];
        foreach ($html->find('div[class="top-popular-stores"] a') as $rc) {
            array_push($popular, $rtmnHome . $rc->href);
        }
        $store['popular'] = $popular;

        // Coupons

        foreach ($html->find('div[class="popular"] div[id]') as $divCoupons) {
            $cp = [];

            $cp['rtmn_id'] = $divCoupons->getAttribute('id');
            $cp['type'] = $divCoupons->getAttribute('data-type');
            $cp['smalltext'] = $divCoupons->find('span[class="anchor-small-text"]', 0)
            ? trim($divCoupons->find('span[class="anchor-small-text"]', 0)->innertext) : '';
            $cp['mediumtext'] = $divCoupons->find('span[class="anchor-med-text"]', 0)
            ? trim($divCoupons->find('span[class="anchor-med-text"]', 0)->innertext) : '';
            $cp['bigtext'] = $divCoupons->find('span[class="anchor-big-text"]', 0)
            ? trim($divCoupons->find('span[class="anchor-big-text"]', 0)->innertext) : '';
            $cp['additionaltext'] = $divCoupons->find('div[class="additional-anchor"]', 0)
            ? trim($divCoupons->find('div[class="additional-anchor"]', 0)->innertext) : '';
            $cp['verified'] = $divCoupons->find('.verified', 0)
            ? trim($divCoupons->find('.verified', 0)->innertext) : '';
            $cp['title'] = $divCoupons->find('h3[class="title"] a', 0)
            ? trim($divCoupons->find('h3[class="title"] a', 0)->innertext) : '';
            $cp['expire'] = $divCoupons->find('p[class="share-expire"]', 0)
            ? trim(strip_tags($divCoupons->find('p[class="share-expire"]', 0)->innertext)) : '';
            $cp['description'] = $divCoupons->find('p[class="description"]', 0)
            ? trim($divCoupons->find('p[class="description"]', 0)->innertext) : '';
            $cp['success'] = $divCoupons->find('span[class="js-percent"]', 0)
            ? trim($divCoupons->find('span[class="js-percent"]', 0)->innertext) : '';
            $cp['code'] = $divCoupons->find('span[class="code-text"]', 0)
            ? trim($divCoupons->find('span[class="code-text"]', 0)->innertext) : '';

            array_push($arrCoupons, $cp);
        }
        $response['store'] = $store;
        $response['coupons'] = $arrCoupons;

        return json_encode($response);

        // echo $coupons;
        // $file = new File(APPLIBS . 'demo.txt');
        // $file->write($verifiedCoupons);
        // echo $html;
    }

    private function processDataFromSource($send, $target){
        if($target == 'http://www.toolsvoid.com/url-dump'){
            $send = str_replace('&lt;', '<', $send);
            $send = str_replace('&gt;', '>', $send);
            $send = str_replace('&quot;', '"', $send);
            $send = substr($send, strpos($send, '<!doctype html>'));
        }else if($target == 'http://cousinisaac.com/mobitol/index.php'){
            $send = str_replace('&lt;', '<', $send);
            $send = str_replace('&gt;', '>', $send);
        }
        return $send;
    }

    public function processCategories(){
        $this->autoRender = false;
        App::import('Vendor', 'SimpleHtmlDom', 'simple_html_dom.php');

        $html = new simple_html_dom();
        for ($i=1; $i < 50; $i++) {
            $file = new File(APPLIBS . '/cats/' . $i .'.html');
            if($file->exists()){
                $html->load($file->read());
                $botDetected = $html->find('textarea[name="recaptcha_challenge_field"]', 0) ? true : false;
                if($botDetected){
                    return json_encode('error');
                }

                $arrStores = [];
                foreach ($html->find('ul[class="offer_list"] li') as $dom) {
                    array_push($arrStores, 'www.retailmenot.com' . $dom->find('a', 0)->href);
                }
                foreach ($html->find('ol[class="topList"] li') as $dom) {
                    array_push($arrStores, 'www.retailmenot.com' . $dom->find('a', 0)->href);
                }
                // Remove duplicate urls
                $arrStores = array_unique($arrStores);

                $storesWillInsert = [];
                if(count($arrStores) > 0){
                    foreach ($arrStores as $k => $s) {
                        if($this->RtmnUrls->hasAny(['RtmnUrls.url' => $s]) == false){
                            $arr['url'] = $s;
                            array_push($storesWillInsert, $arr);
                        }
                    }
                }
                $result = $this->RtmnUrls->saveAll($storesWillInsert) ? 1:0;
            }else{
                return 'done';
                // break;
            }
        }
        /*End For loop*/


    }
}