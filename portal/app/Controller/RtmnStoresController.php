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
        /*Add store if not exist*/
        $rs = ['RtmnStores' => []];
        if($this->RtmnStores->hasAny(['RtmnStores.rtmn_url' => $store['rtmn_url']]) == false){
            $rs = $this->RtmnStores->save($store);
        }
        if($rs['RtmnStores']){
            // Add new url to rtmn_urls
            if($this->RtmnUrls->hasAny(['RtmnUrls.url' => $store['rtmn_url']]) == false){
                $this->RtmnUrls->save(
                    [
                        'RtmnUrls' =>
                        [
                            'url' => $store['rtmn_url'],
                            'rtmn_store_id' => $rs['RtmnStores']['id']
                        ]
                    ]
                );
            }
        }
        $addNewStoreStatus = count($rs) > 0 ? 1:0;
        // Add coupon
        $arrCoupons = $receiveData->coupons;
        if(count($arrCoupons) > 0){
            $newCoupons = [];
            $rtmn_store_id = '';
            if(count($rs['RtmnStores']) > 0){
                $rtmn_store_id = $rs['RtmnStores']['id'];
            }

            foreach ($arrCoupons as $c) {
                if($this->RtmnCoupons->hasAny(['RtmnCoupons.rtmn_id' => $c->rtmn_id]) == false){
                    $c->rtmn_store_id = $rtmn_store_id;
                    array_push($newCoupons, $c);
                }
            }
            // Add coupons of this store
            if(count($newCoupons) > 0){
                $addNewCouponStatus = $this->RtmnCoupons->saveAll($newCoupons) ? 1:0;
            }
        }
        $this->RtmnUrls->updateAll(['checked' => 1], ['RtmnUrls.url' => $store['rtmn_url']]);

        echo json_encode(
        [
            'urls' => $addNewStoreUrlStatus,
            'store' => $addNewStoreStatus,
            'coupon' => $addNewCouponStatus
        ]);
    }

    public function loadUrl(){
        $this->autoRender = false;
        $urls = $this->RtmnUrls->find('all',
            [
                'conditions' => ['RtmnUrls.checked' => 0]
            ]
        );
        return json_encode($urls);
    }

    public function getStoresFromCategory(){
        $this->autoRender = false;
        $cats =
        [
            'http://www.retailmenot.com/coupons/accessories',
            'http://www.retailmenot.com/coupons/auto',
            'http://www.retailmenot.com/coupons/baby',
            'http://www.retailmenot.com/coupons/beauty',
            'http://www.retailmenot.com/coupons/books',
            'http://www.retailmenot.com/coupons/clothing',
            'http://www.retailmenot.com/coupons/electronics',
            'http://www.retailmenot.com/coupons/flowers',
            'http://www.retailmenot.com/coupons/food',
            'http://www.retailmenot.com/coupons/furniture',
            'http://www.retailmenot.com/coupons/gifts',
            'http://www.retailmenot.com/coupons/health',
            'http://www.retailmenot.com/coupons/homeandgarden',
            'http://www.retailmenot.com/coupons/jewelry',
            'http://www.retailmenot.com/coupons/musicalinstruments',
            'http://www.retailmenot.com/coupons/officesupplies',
            'http://www.retailmenot.com/coupons/partysupplies',
            'http://www.retailmenot.com/coupons/pet',
            'http://www.retailmenot.com/coupons/photo',
            'http://www.retailmenot.com/coupons/services',
            'http://www.retailmenot.com/coupons/shoes',
            'http://www.retailmenot.com/coupons/sports',
            'http://www.retailmenot.com/coupons/toys',
            'http://www.retailmenot.com/coupons/travel',
        ];
        return json_encode($cats);
    }
}