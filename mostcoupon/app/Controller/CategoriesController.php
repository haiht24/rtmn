<?php
App::uses('SeoLibs', 'Seo');

class CategoriesController extends AppController
{

    public function index()
    {
        $categories = $this->mCusApi->resource('Category')->query(array('status' => 'publish','limit' => 20));

        $this->set('categories', $categories['categories']);
    }

    public function details($alias)
    {
        if (!isset($alias)) throw new NotFoundException('Category not found');;
        $category = $this->mCusApi->resource('Category')->query(array('status' => 'publish', 'alias' => $alias));
        if (empty($category)) throw new NotFoundException('Category not found');
        $this->set('category', $category['categories']);
        $bestStores = $this->mCusApi->resource('Store')->query([
            'status' => 'published',
            'best_store' => 1,
            'limit' => 4
        ]);
        $this->set('bestStores', $bestStores['stores']);
        $hotdeals = $this->mCusApi->resource('Deal')->query(array(
            'limit' => 4,
            'hot_deal' => 1,
//            'categoryId' => $category['categories']['Category']['id'],
            'expire_date_greater_null' => true,
            'status' => 'published'));
        $this->set('hotDeals', $hotdeals['deals']);

        $categories = $this->mCusApi->resource('Category')->query(array('status' => 'published'));
        $this->set('allCategories', $categories['categories']);
        $this->set('public_key', Configure::read('reCaptcha.public_key'));
        $events = $this->mCusApi->resource('Event')->query(array('status' => 'published'));
        $this->set('events', $events['events']);

        $deals = $this->mCusApi->resource('Deal')->query(array(
            'status' => 'published',
            'categoryId' => $category['categories']['Category']['id'],
            'limit' => 8,
            'expire_date_greater' => true,
            'order' => ['Deal.hot_deal DESC']));
        $this->set('deals', $deals);

        $coupons = $this->mCusApi->resource('Coupon')->query([
            'status' => 'published',
            'categoryId' => $category['categories']['Category']['id'],
            'expire_date_greater_null' => true,
            'limit' => 20,
            'order' => ['Coupon.sticky DESC']
        ]);
        $this->set('coupons', $coupons);

        $storesCoupon = $this->mCusApi->resource('Store')->query([
            'status' => 'published',
            'best_store' => 1,
            'limit' => 10
        ]);
        $this->set('storesCoupon', $storesCoupon['stores']);

        /**
         * SEO Config
         */
        $seoLibs = new SeoLibs;
        $SeoConfig = $this->mCusApi->resource('options')->request('/index');
        if ($SeoConfig) {
            $rs = [];
            foreach ($SeoConfig['option'] as $s) {
                if ($s['Option']['option_name'] == 'seo_CatTitle') {
                    $title = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_CatDesc') {
                    $metaDescription = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_CatKeyword') {
                    $metaKeyword = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_siteName') {
                    $siteName = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_siteDescription') {
                    $siteDesc = $s['Option']['option_value'];
                }
                if ($s['Option']['option_name'] == 'seo_DisableCatNoIndex') {
                    $disableNoindex = $s['Option']['option_value'];
                }
            }
            if (isset($disableNoindex)) {
                $rs['disableNoindex'] = $disableNoindex;
            }

            if (isset($title)) {
                $title = $seoLibs->seoConvert($title, $siteName, $siteDesc, $category['categories']['Category']['name']);
                $rs['title'] = $title;
            }
            if (isset($metaDescription)) {
                $metaDescription = $seoLibs->seoConvert($metaDescription, $siteName, $siteDesc, $category['categories']['Category']['name']);
                $rs['desc'] = $metaDescription;
            }
            if (isset($metaKeyword)) {
                $metaKeyword = $seoLibs->seoConvert($metaKeyword, $siteName, $siteDesc, $category['categories']['Category']['name']);
                $rs['keyword'] = $metaKeyword;
            }
            $this->set('seoConfig', $rs);
        }
    }

}
