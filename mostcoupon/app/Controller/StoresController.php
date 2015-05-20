<?php
App::uses('SeoLibs', 'Seo');
//Configure::config('default', new PhpReader());
App::uses('mcus', 'Config');

class StoresController extends AppController
{

    public function index()
    {

    }

    public function details($url)
    {
        $details = $this->mCusApi->resource('Store')->get(str_replace('-coupons', '', $url));
        if (sizeof($details['store']) == 0) {
            throw new NotFoundException('Store not found');
        }
        $this->set('store', $details['store']);

        $storeId = $details['store']['Store']['id'];
        $this->set('storeId', $storeId);
        foreach ($details['store']['Store']['categories_id'] as $cateId) {
            $cate_store = $this->mCusApi->resource('Category')->get($cateId);
            if (!empty($cate_store['category'])) {
                $this->set('cate_store', $cate_store['category']);
                break;
            }
        }

        $hotStores = $this->mCusApi->resource('Store')->query([
            'status' => 'published',
            'best_store' => 1,
            'limit' => 4,
            'unbindAll' => true,
            'fields' => ['id', 'name', 'alias', 'logo', 'custom_keywords', 'description']
        ]);
        $this->set('hotStores', $hotStores['stores']);

        $storesCoupon = $this->mCusApi->resource('Store')->query([
            'status' => 'published',
            'best_store' => 1,
            'limit' => 10,
            'unbindAll' => true,
            'fields' => ['name', 'alias', 'logo', 'custom_keywords']
        ]);
        $this->set('storesCoupon', $storesCoupon['stores']);

        $hotDeals = $this->mCusApi->resource('Deal')->query([
            'limit' => 4,
            'hot_deal' => 1,
//            'store_id' => $storeId,
            'status' => 'published',
            'expire_date_greater_null' => true,
            'order' => ['Deal.hot_deal DESC'],
            'unbindAllExcept' => ['Property'],
            'fields' => ['deal_image', 'title', 'id', 'origin_price', 'currency', 'discount_price']
        ]);
        $this->set('hotDeals', $hotDeals['deals']);

        $coupons = $this->mCusApi->resource('Coupon')->query([
            'status' => 'published',
            'store_id' => $storeId,
            'expire_date_greater_null' => true,
            'limit' => 10,
            'order' => ['Coupon.sticky DESC'],
            'unbindAllExcept' => ['User', 'Property', 'Event', 'Like'],
            'count' => true,
            'fields' => ['id', 'coupon_type', 'discount', 'currency', 'event_id', 'exclusive', 'verified', 'title_store', 'description_store', 'expire_date',
                'User.fullname', 'User.id',
                'Event.name',
                'Property.foreign_key_right']
        ]);

        $this->set('coupons', $coupons);

        $deals = $this->mCusApi->resource('Deal')->query([
            'status' => 'published',
            'store_id' => $storeId,
            'limit' => 12,
            'expire_date_greater' => true,
            'order' => ['Deal.hot_deal DESC'],
            'unbindAllExcept' => ['Property'],
            'count' => true,
            'fields' => ['id', 'title', 'description', 'currency', 'discount_price', 'discount_percent', 'origin_price', 'produc_url', 'deal_image', 'Property.foreign_key_right']
        ]);
        $this->set('deals', $deals);

        $related_coupons = Cache::read('relatedCoupons' . $details['store']['Store']['id']);
        if (!$related_coupons) {
            $related_stores = $this->mCusApi->resource('Store')->query([
                'status' => 'published',
                'categoryId' => $details['store']['Store']['categories_id'][0],
                'limit' => 20,
                'findList' => true,
                'unbindAllExcept' => [],
                'fields' => ['Store.id'],
                'order' => ['best_store DESC']
            ]);
            $related_coupons = [];
            foreach (array_keys($related_stores['stores']) as $store_id) {
                $related_coupon = $this->mCusApi->resource('Coupon')->query([
                    'status' => 'published',
                    'store_id' => $store_id,
                    'limit' => 1,
                    'unbindAllExcept' => ['User', 'Property', 'Like', 'Store', 'Event'],
                    'count' => true,
                    'order' => ['Coupon.sticky DESC'],
                    'fields' => ['id', 'coupon_type', 'discount', 'currency', 'event_id', 'exclusive', 'verified', 'title_store', 'description_store', 'expire_date',
                        'Store.name', 'Store.id', 'Store.alias', 'Store.logo',
                        'User.fullname', 'User.id',
                        'Event.name',
                        'Property.foreign_key_right']
                ])['coupons'];
                if (!empty($related_coupon))
                    $related_coupons[] = $related_coupon[0];
                if (sizeof($related_coupons) == 10) break;
            }
            Cache::write('relatedCoupons' . $details['store']['Store']['id'], $related_coupons);
        }

        $this->set('relatedCoupons', $related_coupons);

        $expiredDeals = $this->mCusApi->resource('Deal')->query([
            'limit' => 4,
            'store_id' => $storeId,
            'expired_date' => true,
            'status' => 'published',
            'unbindAllExcept' => ['Property'],
            'count' => true,
            'fields' => ['id', 'title', 'description', 'Property.foreign_key_right']
        ]);
        $expiredCoupons = $this->mCusApi->resource('Coupon')->query([
            'status' => 'published',
            'store_id' => $storeId,
            'expired_date' => true,
            'limit' => 4,
            'unbindAllExcept' => ['Property'],
            'count' => true,
            'fields' => ['id', 'title_store', 'description_store', 'Property.foreign_key_right']
        ]);
//        if (sizeof($expiredDeals['deals']) < 4) {
//            $expiredCoupons = $this->mCusApi->resource('Coupon')->query([
//                'status' => 'published',
//                'store_id' => $storeId,
//                'expired_date' => true,
//                'limit' => 8 - sizeof($expiredDeals['deals']),
//                'unbindAllExcept' => ['Property'],
//                'count' => true,
//                'fields' => ['id', 'title_store', 'description_store', 'Property.foreign_key_right']
//            ]);
//        } elseif (sizeof($expiredCoupons['coupons']) < 4) {
//            $expiredDeals = $this->mCusApi->resource('Deal')->query([
//                'limit' => 8 - sizeof($expiredCoupons['coupons']),
//                'store_id' => $storeId,
//                'expired_date' => true,
//                'status' => 'published',
//                'unbindAllExcept' => ['Property'],
//                'count' => true,
//                'fields' => ['id', 'title', 'description', 'Property.foreign_key_right']
//            ]);
//        }
        $this->set('expiredDeals', $expiredDeals['deals']);
        $this->set('expiredCoupons', $expiredCoupons['coupons']);
        $this->set('totalExpiredDeals', $expiredDeals['count']);
        $this->set('totalExpiredCoupons', $expiredCoupons['count']);
        /**
         * SEO Config
         */
        $seoLibs = new SeoLibs;
        $SeoConfig = $this->mCusApi->resource('options')->request('/index');
        if ($SeoConfig) {
            $rs = [];
            $title = '';
            $metaDescription = '';
            $metaKeyword = '';
            $siteName = '';
            $siteDesc = '';
            $storeHeaderH1 = '';
            $storeHeaderP = '';
            $disableNoindex = '';
            $seo_defaultStoreTitle = '';
            $seo_defaultStoreMetaDescription = '';
            $seo_defaultStoreMetaKeyword = '';
            $seo_defaultH1Store = '';
            $seo_defaultPStore = '';
            foreach ($SeoConfig['option'] as $s) {
                if ($s['Option']['option_name'] == 'seo_storeTitle') {
                    $title = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_storeDesc') {
                    $metaDescription = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_storeKeyword') {
                    $metaKeyword = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_siteName') {
                    $siteName = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_siteDescription') {
                    $siteDesc = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_storeH1') {
                    $storeHeaderH1 = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_storeP') {
                    $storeHeaderP = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_disableStoreNoIndex') {
                    $disableNoindex = $s['Option']['option_value'];
                }

                if ($s['Option']['option_name'] == 'seo_defaultStoreTitle') {
                    $seo_defaultStoreTitle = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_defaultStoreMetaDescription') {
                    $seo_defaultStoreMetaDescription = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_defaultStoreMetaKeyword') {
                    $seo_defaultStoreMetaKeyword = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_defaultH1Store') {
                    $seo_defaultH1Store = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_defaultPStore') {
                    $seo_defaultPStore = $s['Option']['option_value'];
                }
            }
            if (isset($disableNoindex)) {
                $rs['disableNoindex'] = $disableNoindex;
            }

            $topCoupon = $this->mCusApi->resource('Coupon')->query(['status' => 'published', 'store_id' => $storeId,
                'sticky' => 'hot', 'limit' => 1]);
            $storeName = $details['store']['Store']['name'];
            $couponTitle = '';
            $couponDiscount = '';
            if ($topCoupon['count'] > 0) {
                $couponTitle = $topCoupon['coupons'][0]['Coupon']['title_store'];
                $couponDiscount = $topCoupon['coupons'][0]['Coupon']['discount'];
            } else {
                $lastestCoupon = $this->mCusApi->resource('Coupon')->query(['status' => 'published', 'store_id' => $storeId,
                    'limit' => 1]);
                if ($lastestCoupon['count'] > 0) {
                    $couponTitle = $lastestCoupon['coupons'][0]['Coupon']['title_store'];
                    $couponDiscount = $lastestCoupon['coupons'][0]['Coupon']['discount'];
                } else {
                    $couponTitle = '';
                    $couponDiscount = '';
//                     $title = $seo_defaultStoreTitle;
//                     $metaDescription = $seo_defaultStoreMetaDescription;
//                     $metaKeyword = $seo_defaultStoreMetaKeyword;
//                     $storeHeaderH1 = $seo_defaultH1Store;
//                     $storeHeaderP = $seo_defaultPStore;
                }
            }

            if (isset($title)) {
                if (!$couponDiscount) {
                    $rs['title'] = $seoLibs->seoConvert($seo_defaultStoreTitle, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount);
                } else {
                    $rs['title'] = $seoLibs->seoConvert($title, $siteName, $siteDesc, $storeName, $couponTitle, $couponDiscount);
                }
            }
            if (isset($metaDescription)) {
                if (!$couponDiscount) {
                    $rs['desc'] = $seoLibs->seoConvert($seo_defaultStoreMetaDescription, $siteName, $siteDesc, $storeName,
                        $couponTitle, $couponDiscount);
                } else {
                    $rs['desc'] = $seoLibs->seoConvert($metaDescription, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount);
                }
            }
            if (isset($metaKeyword)) {
                if (!$couponDiscount) {
                    $rs['keyword'] = $seoLibs->seoConvert($seo_defaultStoreMetaKeyword, $siteName, $siteDesc, $storeName,
                        $couponTitle, $couponDiscount);
                } else {
                    $rs['keyword'] = $seoLibs->seoConvert($metaKeyword, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount);
                }
            }
            if (isset($storeHeaderH1)) {
                if (!$couponDiscount) {
                    $rs['storeHeaderH1'] = $seoLibs->seoConvert($seo_defaultH1Store, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount);
                } else {
                    $rs['storeHeaderH1'] = $seoLibs->seoConvert($storeHeaderH1, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount);
                }
            }
            if (isset($storeHeaderP)) {
                if (!$couponDiscount) {
                    $rs['storeHeaderP'] = $seoLibs->seoConvert($seo_defaultPStore, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount);
                } else {
                    $rs['storeHeaderP'] = $seoLibs->seoConvert($storeHeaderP, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount);
                }
            }
            $this->set('seoConfig', $rs);
            if (isset($siteName)) {
                $this->set('siteName', $siteName);
            }
        }
    }

    public function listStoreToSubmit()
    {
        if (!empty($this->params->query['q'])) {
            if ($this->Session->check('User.id')) {
                $stores = $this->mCusApi->resource('Store')->query([
                    'status' => 'published',
                    'userId' => $this->Session->read('User.id'),
                    'search' => $this->params->query['q'],
                    'unbindAll' => true,
                    'unbindModel' => ['hasMany' => ['Property'],
                        'hasAndBelongsToMany' => ['Category']]
                ]);
            } else {
                $stores = $this->mCusApi->resource('Store')->query([
                    'status' => 'published',
                    'search' => $this->params->query['q'],
                    'unbindAll' => true,
                    'unbindModel' => ['hasMany' => ['Property'],
                        'hasAndBelongsToMany' => ['Category']]
                ]);
            }
            $listStores = $stores['stores'];
            for ($i = 0; $i < sizeof($listStores); $i++) {
                unset($listStores[$i]['Category']);
                $listStores[$i]['id'] = $listStores[$i]['Store']['id'];
                $listStores[$i]['name'] = $listStores[$i]['Store']['name'];
                $listStores[$i]['store_url'] = $listStores[$i]['Store']['store_url'];
            }
            $response = ['items' => $listStores];
        } else $response = ['items' => []];
        $this->response->statusCode(200);
        $this->response->type('json');
        $this->response->body(json_encode($response));
        return $this->response;
    }

//    public function addStore()
//    {
//        if ($this->request->is('post') && $this->request->is('ajax')) {
//            $data = $this->request->data;
//            $store = $this->mCusApi->resource('Store')->add(['name' => $data['store_url'], 'store_url' => $data['store_url'], 'status' => 'pending']);
//            if (sizeof($store['store'])) {
//                $response = ['id' => $store['store']['id'], 'name' => $data['store_url'], 'store_url' => $data['store_url']];
//            } else $response = [];
//        } else $response = [];
//        $this->response->statusCode(200);
//        $this->response->type('json');
//        $this->response->body(json_encode($response));
//        return $this->response;
//    }
    public function getMoreExpired()
    {
        $storeId = '';
        $limit = 0;
        $offset_coupons = 0;
        $offset_deals = 0;
        if (isset($this->request->query['store_id'])) {
            $storeId = $this->request->query['store_id'];
        }
        if (isset($this->request->query['limit'])) {
            $limit = $this->request->query['limit'];
        }
        if (isset($this->request->query['offset_coupons'])) {
            $offset_coupons = $this->request->query['offset_coupons'];
        }
        if (isset($this->request->query['offset_deals'])) {
            $offset_deals = $this->request->query['offset_deals'];
        }
        $expiredDeals = $this->mCusApi->resource('Deal')->query([
            'limit' => $limit / 2,
            'offset' => $offset_deals,
            'store_id' => $storeId,
            "expire_date < '" . date("Y/m/d") . "'",
            'status' => 'published'
        ]);
        $expiredCoupons = $this->mCusApi->resource('Coupon')->query([
            'status' => 'published',
            'store_id' => $storeId,
            "expire_date < '" . date("Y/m/d") . "'",
            'limit' => $limit / 2,
            'offset' => $offset_coupons
        ]);
        if (sizeof($expiredDeals['deals']) < $limit / 2) {
            $expiredCoupons = $this->mCusApi->resource('Coupon')->query([
                'status' => 'published',
                'store_id' => $storeId,
                "expire_date < '" . date("Y/m/d") . "'",
                'limit' => $limit - sizeof($expiredDeals['deals']),
                'offset' => $offset_coupons
            ]);
        } elseif (sizeof($expiredCoupons['coupons']) < 4) {
            $expiredDeals = $this->mCusApi->resource('Deal')->query([
                'limit' => $limit - sizeof($expiredCoupons['coupons']),
                'offset' => $offset_deals,
                'store_id' => $storeId,
                "expire_date < '" . date("Y/m/d") . "'",
                'status' => 'published'
            ]);
        }
        $this->set('expiredDeals', $expiredDeals['deals']);
        $this->set('expiredCoupons', $expiredCoupons['coupons']);
        $this->set('totalExpiredDeals', $expiredDeals['count']);
        $this->set('totalExpiredCoupons', $expiredCoupons['count']);
        $response = ['coupons' => $expiredCoupons['coupons'],
            'coupons_count' => sizeof($expiredCoupons['count']),
            'deals' => $expiredDeals['deals'],
            'deals_count' => sizeof($expiredDeals['count'])];
        $this->response->statusCode(200);
        $this->response->type('json');
        $this->response->body(json_encode($response));
        return $this->response;
    }

}
