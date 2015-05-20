<?php
/**
 * Created by PhpStorm.
 * User: Phuong
 * Date: 4/1/2015
 * Time: 2:05 PM
 */
App::uses('AppController', 'Controller');

class GoController extends AppController
{

    public function index($id)
    {
        if (strlen($id) == 6) {
            $properties = $this->mCusApi->resource('Property')->query(['foreign_key_right' => $id]);
            if (sizeof($properties) > 0) {
                $obj = $properties['properties'][0];
                if ($obj['Property']['key'] == 'store') {
                    $object = $this->mCusApi->resource('Store')->query(['id' => $obj['Property']['foreign_key_left']]);
                    $store = $object['stores'][0]['Store'];
                    if (!empty($store['affiliate_url'])) {
                        $product_url = $store['affiliate_url'];
                    } else $product_url = $store['store_url'];
                } elseif ($obj['Property']['key'] == 'deal') {
                    $object = $this->mCusApi->resource('Deal')->get($obj['Property']['foreign_key_left']);
                    $deal = $object['deal']['Deal'];
                    if (!empty($deal['produc_url'])) {
                        $product_url = $deal['produc_url'];
                    } else {
                        $store = $object['deal']['Store'];
                        if (!empty($store['affiliate_url'])) {
                            $product_url = $store['affiliate_url'];
                        } else $product_url = $store['store_url'];
                    }
                } else {
                    $object = $this->mCusApi->resource('Coupon')->get($obj['Property']['foreign_key_left']);
                    $coupon = $object['coupon']['Coupon'];
                    if (!empty($coupon['product_link'])) {
                        $product_url = $coupon['product_link'];
                    } else {
                        $store = $object['coupon']['Store'];
                        if (!empty($store['affiliate_url'])) {
                            $product_url = $store['affiliate_url'];
                        } else $product_url = $store['store_url'];
                    }
                }
                $this->redirect($product_url);
            }
        }
    }
}