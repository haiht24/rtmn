<?php
App::uses('AppController', 'Controller');
App::uses('SeoLibs', 'Seo');

class HomeController extends AppController
{
    public function index()
    {
        $categories = $this->mCusApi->resource('Category')->query([
            'limit' => 10,
            'status' => 'published',
            'store_count >' => 0,
            'fields' => ['Category.id','Category.name','Category.store_count', 'Category.icon'],
            'unbindAll' => true
        ]);
        $this->set('categories', $categories['categories']);

        $hotdeals = $this->mCusApi->resource('Deal')->query([
            'limit' => 8,
            'hot_deal' => 1,
            'status' => 'published',
            'unbindAllExcept' => ['Property'],
            'fields' => ['id', 'title', 'description', 'currency', 'discount_price', 'discount_percent', 'origin_price', 'produc_url', 'deal_image', 'Property.foreign_key_right']
        ]);
        $this->set('hotdeals', $hotdeals['deals']);

        $latestDeals = $this->mCusApi->resource('Deal')->query([
            'limit' => 8,
            'status' => 'published',
            'unbindAllExcept' => ['Property'],
            'fields' => ['id', 'title', 'description', 'currency', 'discount_price', 'discount_percent', 'origin_price', 'produc_url', 'deal_image', 'Property.foreign_key_right']
        ]);
        $this->set('latestDeals', $latestDeals['deals']);

        $stores = $this->mCusApi->resource('Store')->query([
            'limit' => 15,
            'status' => 'published',
            'show_in_homepage' => 1,
            'unbindAllExcept' => [],
            'unbindAll' => true,
            'fields' => ['id', 'name', 'alias', 'logo', 'custom_keywords']
        ]);
        $this->set('stores', $stores['stores']);

        $this->set('totalCoupons', "9,149");
        /**
         * SEO Config
         */
        $seoLibs = new SeoLibs;
        $SeoConfig = $this->mCusApi->resource('options')->request('/index');
        if ($SeoConfig) {
            $rs = [];
            foreach ($SeoConfig['option'] as $s) {
                if ($s['Option']['option_name'] == 'seo_homeTitle') {
                    $settingHomeTitle = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_homeMetaDesc') {
                    $settingHomeMetaDesc = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_homeMetaKeyword') {
                    $settingHomeMetaKeyword = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_siteName') {
                    $siteName = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_siteDescription') {
                    $siteDesc = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_disableHomeNoIndex') {
                    $disableNoIndex = $s['Option']['option_value'];
                }
            }
            if (isset($disableNoIndex)) {
                $rs['disableNoindex'] = $disableNoIndex;
            }

            if (isset($settingHomeTitle)) {
                $settingHomeTitle = $seoLibs->seoConvert($settingHomeTitle, $siteName, $siteDesc);
                $rs['title'] = $settingHomeTitle;
            }
            if (isset($settingHomeMetaDesc)) {
                $settingHomeMetaDesc = $seoLibs->seoConvert($settingHomeMetaDesc, $siteName, $siteDesc);
                $rs['desc'] = $settingHomeMetaDesc;
            }
            if (isset($settingHomeMetaKeyword)) {
                $settingHomeMetaKeyword = $seoLibs->seoConvert($settingHomeMetaKeyword, $siteName, $siteDesc);
                $rs['keyword'] = $settingHomeMetaKeyword;
            }
            $this->set('seoConfig', $rs);
        }
        // Ads
        $ads = $this->mCusApi->resource('properties')->request('/getAds');
        $this->set('ads', $ads);
    }

    public function getCategories()
    {
        $limit = 0;
        $offset = 0;
        if (isset($this->request->query['limit'])) {
            $limit = $this->request->query['limit'];
        }
        if (isset($this->request->query['offset'])) {
            $offset = $this->request->query['offset'];
        }
        $categories = $this->mCusApi->resource('Category')->query(array('limit' => $limit, 'offset' => $offset, 'status' => 'published'));
        $response = ['categories' => $categories['categories'], 'count' => sizeof($categories['categories'])];
        $this->response->statusCode(200);
        $this->response->type('json');
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function loadDealsByCategory()
    {

        if (!empty($this->params->query['id'])) {
            $categoryId = $this->params->query['id'];
            $hotdeals = $this->mCusApi->resource('Deal')->query(array(
                'limit' => 8,
                'hot_deal' => 1,
                'status' => 'published',
                'categoryId' => $categoryId));

            $latestDeals = $this->mCusApi->resource('Deal')->query(array('limit' => 8, 'status' => 'published',
                'categoryId' => $categoryId));

            $response = ['status' => true, 'hotdeals' => $hotdeals['deals'], 'latestDeals' => $latestDeals['deals']];

        } else $response = ['status' => false];
        $this->response->statusCode(200);
        $this->response->type('json');
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function contactUs()
    {

    }

    public function aboutUs()
    {

    }

    public function showText()
    {

    }

    public function howToUseCode()
    {

    }

    public function error()
    {
        $this->layout = 'error';
        $this->render('404');
    }

    public function landing()
    {

    }

    public function survey()
    {

    }

    public function saveall()
    {
//        $stores = $this->mCusApi->resource('Store')->request('/saveall/');
//        $coupons = $this->mCusApi->resource('Coupon')->request('/saveall/');
//        $deals = $this->mCusApi->resource('Deal')->request('/saveall/');
    }
}
