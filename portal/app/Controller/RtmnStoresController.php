<?php
App::uses('AppController', 'Controller');

class RtmnStoresController extends AppController
{
    public $uses = ['RtmnStores', 'RtmnUrls', 'RtmnCoupons'];

    public function add() {
        $this->autoRender = false;
        $receiveData = $this->request->data('rs');
        $receiveData = json_decode($receiveData);

        $addNewStoreUrlStatus = 0;
        $addNewStoreStatus = 0;
        $addNewCouponStatus = 0;

        $store = [];
        $store['rtmn_url'] = $receiveData->store->rtmn_url;
        $store['url'] = $receiveData->store->url;
        $store['name'] = $receiveData->store->name;

        $store['logo'] = $receiveData->store->logo;
        $store['verifiedCoupons'] = $receiveData->store->verifiedCoupons;
        $store['averageSavings'] = $receiveData->store->averageSavings;
        $store['taxonomy'] = $receiveData->store->taxonomy;
        $storeUrls = array_merge($receiveData->store->recommended, $receiveData->store->similar, $receiveData->store->popular);

        $newStoreUrls = [];
        if(count($storeUrls) > 0){
            foreach ($storeUrls as $r) {
                // check exist url before insert
                if($this->RtmnUrls->hasAny(['RtmnUrls.url' => $r]) == false){
                    $arr = ['url' => $r];
                    array_push($newStoreUrls, $arr);
                }
            }
            // Add new store urls to table rtmn_urls
            $addNewStoreUrlStatus = $this->RtmnUrls->saveAll($newStoreUrls) ? 1:0;
        }
        // Add store
        $rs = $this->RtmnStores->save($store);
        $addNewStoreStatus = count($rs) > 0 ? 1:0;
        if(count($rs['RtmnStores']) > 0){
            $arrCoupons = $receiveData->coupons;
            if(count($arrCoupons) > 0){
                foreach ($arrCoupons as $c) {
                    $c->rtmn_store_id = $rs['RtmnStores']['id'];
                }
                // Add coupons of this store
                $addNewCouponStatus = $this->RtmnCoupons->saveAll($arrCoupons) ? 1:0;
            }
            // return 1;
        }else{
            // return 0;
        }
        echo json_encode(
        [
            'urls' => $addNewStoreUrlStatus,
            'store' => $addNewStoreStatus,
            'coupon' => $addNewCouponStatus
        ]);
    }
}