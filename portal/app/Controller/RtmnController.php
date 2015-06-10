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

        $send = $this->request->data['send'];

        $send = str_replace("'", "", $send);
        $send = substr($send, 312);

        $html = new simple_html_dom();
        // $html->load($send);

        // $e = $html->find('h2',0);
        // echo $e->innertext;

        echo $html;
    }
}