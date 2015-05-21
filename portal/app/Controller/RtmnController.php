<?php
App::uses('AppController', 'Controller');
class RtmnController extends AppController
{
    public function index(){
        // $this->autoRender = false;
    }

    public function start(){
        $this->autoRender = false;
        App::import('Vendor', 'SimpleHtmlDom', 'simple_html_dom.php');
        $request =
        [
            'http' =>
            [
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                    "User-Agent:MyAgent/1.0\r\n",
                'method' => 'POST',
                'content' => http_build_query(
                [
                    'url' => 'http://www.retailmenot.com/view/target.com',
                    'format' => 'on'
                ]),
            ]
        ];
        $context = stream_context_create($request);
        // $html = file_get_html('http://source.domania.net/cgi-bin/source.cgi', false, $context);
        // echo $html->find('textarea[name="code"]', 0)->innertext;
        // $content = $html->find('textarea[name="code"]', 0)->innertext;

        // $html = new simple_html_dom();
        // $html->load($content);

        // echo "<pre>";
        // var_dump($html);
        // echo $html;
        $url = 'http://dynupdate.no-ip.com/ip.php';
        $proxy = '127.0.0.1:8888';
        //$proxyauth = 'user:password';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        //curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);

        echo $curl_scraped_page;
    }
}