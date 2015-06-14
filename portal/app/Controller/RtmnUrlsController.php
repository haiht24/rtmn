<?php
App::uses('AppController', 'Controller');

class RtmnUrlsController extends AppController
{
    public $uses = ['RtmnUrls'];

    public function add() {
        $this->autoRender = false;
        $receiveData = $this->request->data('rs');
        $receiveData = json_decode($receiveData);

        /*$store = [];
        $store['rtmn_url'] = $receiveData->store->rtmn_url;
        $store['url'] = $receiveData->store->url;
        $store['name'] = $receiveData->store->name;

        $store['logo'] = $receiveData->store->logo;
        $store['verifiedCoupons'] = $receiveData->store->verifiedCoupons;
        $store['averageSavings'] = $receiveData->store->averageSavings;
        $store['taxonomy'] = $receiveData->store->taxonomy;
        // $store['recommended'] = $receiveData->store->recommended;
        // $store['similar'] = $receiveData->store->similar;
        // $store['popular'] = $receiveData->store->popular;

        $rs = $this->RtmnStores->save($store);
        if(count($rs['RtmnStores']) > 0){
            return 1;
        }else{
            return 0;
        }*/
    }
}