<?php
require_once "../Vendor/recaptcha2/recaptchalib.php";

class DealsController extends AppController
{

    public function index()
    {
        $hotDeals = $this->mCusApi->resource('Deal')->query([
            'hot_deal' => 1,
            'status' => 'published',
            'expire_date_greater_null' => true,
            'limit' => $this->Session->check('all_deals_hot') ? $this->Session->read('all_deals_hot') : 8,
            'order' => ['Deal.hot_deal DESC'],
            'unbindAllExcept' => ['Property'],
            'count' => true,
            'fields' => ['id', 'title', 'description', 'currency', 'discount_price', 'discount_percent', 'origin_price', 'produc_url', 'deal_image', 'Property.foreign_key_right']
        ]);
        $this->set('hotDeals', $hotDeals['deals']);

        $latestDeals = $this->mCusApi->resource('Deal')->query([
            'status' => 'published',
            'expire_date_greater_null' => true,
            'limit' => $this->Session->check('all_deals_latest') ? $this->Session->read('all_deals_latest') : 8,
            'order' => ['Deal.hot_deal DESC'],
            'unbindAllExcept' => ['Property'],
            'count' => true,
            'fields' => ['id', 'title', 'description', 'currency', 'discount_price', 'discount_percent', 'origin_price', 'produc_url', 'deal_image', 'Property.foreign_key_right']
        ]);
        $this->set('latestDeals', $latestDeals['deals']);
    }

    public function search()
    {

        $this->autoRender = false;

        if (!empty($this->request->data)) {
            $text = $this->request->data['text'];
            $deals = $this->mCusApi->resource('Deal')->query(array('limit' => 20, 'status' => 'published', 'search' => $text, 'expire_date_greater_null' => true));

            echo json_encode($deals['deals'], true);
        }
    }

    public function details($id)
    {
        $details = $this->mCusApi->resource('Deal')->get($id);
        if (sizeof($details['deal']) == 0) {
            throw new NotFoundException('Deal not found');
        }
        $this->set('deal', $details['deal']);
        $bestStores = $this->mCusApi->resource('Store')->query([
            'best_store' => 1,
            'status' => 'published',
            'limit' => 4,
            'unbindAll' => true,
            'fields' => ['id', 'name', 'alias', 'logo', 'custom_keywords', 'description']
        ]);

        $this->set('bestStores', $bestStores['stores']);

        $hotdeals = $this->mCusApi->resource('Deal')->query([
            'limit' => 5,
            'hot_deal' => 1,
            'status' => 'published',
            'expire_date_greater' => true,
            'order' => ['Deal.hot_deal DESC'],
            'unbindAllExcept' => ['Property'],
            'fields' => ['deal_image', 'title', 'id', 'origin_price', 'currency', 'discount_price']
        ]);
        $this->set('hotdeals', $hotdeals['deals']);

        $coupons = $this->mCusApi->resource('Coupon')->query([
            'status' => 'published',
            'store_id' => $details['deal']['Deal']['store_id'],
            'limit' => 3,
            'expire_date_greater_null' => true,
            'order' => ['Coupon.sticky DESC'],
            'unbindAllExcept' => ['User', 'Property', 'Event', 'Like'],
            'count' => true,
            'fields' => ['id', 'coupon_type', 'discount', 'currency', 'event_id', 'exclusive', 'verified', 'title_store', 'description_store', 'expire_date',
                'User.fullname', 'User.id',
                'Event.name',
                'Property.foreign_key_right']
        ]);
        $this->set('coupons', $coupons);
        if (!empty($details['deal']['Deal']['categories_id'])) {
            $deals = [];
            foreach ($details['deal']['Deal']['categories_id'] as $category_id) {
                $relatedDeal = $this->mCusApi->resource('Deal')->query([
                    'other_id' => $details['deal']['Deal']['id'],
                    'limit' => 8,
                    'status' => 'published',
                    'categoryId' => $category_id,
                    'expire_date_greater' => true,
                    'order' => ['Deal.hot_deal DESC'],
                    'unbindAllExcept' => ['Property'],
                    'count' => true,
                    'fields' => ['id', 'title', 'description', 'currency', 'discount_price', 'discount_percent', 'origin_price', 'produc_url', 'deal_image', 'Property.foreign_key_right']
                ])['deals'];
                $deals = array_merge($deals, $relatedDeal);
                if (sizeof($deals) >= 8) break;
            }
        } else
        $deals = $this->mCusApi->resource('Deal')->query([
            'other_id' => $details['deal']['Deal']['id'],
            'limit' => 8,
            'status' => 'published',
            'store_id' => $details['deal']['Deal']['store_id'],
            'expire_date_greater' => true,
            'order' => ['Deal.hot_deal DESC'],
            'unbindAllExcept' => ['Property'],
            'count' => true,
            'fields' => ['id', 'title', 'description', 'currency', 'discount_price', 'discount_percent', 'origin_price', 'produc_url', 'deal_image', 'Property.foreign_key_right']
        ])['deals'];
        $this->set('deals', $deals);

        $storesCoupon = $this->mCusApi->resource('Store')->query([
            'status' => 'published',
            'best_store' => 1,
            'limit' => 10,
            'unbindAll' => true,
            'fields' => ['name', 'alias', 'logo', 'custom_keywords']
        ]);
        $this->set('storesCoupon', $storesCoupon['stores']);
    }

    public function submitDeal()
    {
        $stores = $this->mCusApi->resource('Store')->query(array('limit' => 15, 'status' => 'published', 'show_in_homepage' => 1));
        $this->set('stores', $stores);
        $this->set('public_key', Configure::read('reCaptcha.public_key'));
        $categories = $this->mCusApi->resource('Category')->query(array('status' => 'published'));
        $this->set('allCategories', $categories['categories']);
        $events = $this->mCusApi->resource('Event')->query(array('status' => 'published'));
        $this->set('events', $events['events']);
        if ($this->request->is('post')) {
            $this->response->statusCode(200);
            $this->response->type('json');
            $secret_key = Configure::read('reCaptcha.secret_key');
            $reCaptcha = new ReCaptcha($secret_key);
            if ($_POST["g-recaptcha-response"]) {
                $resp = $reCaptcha->verifyResponse(
                    $_SERVER["REMOTE_ADDR"],
                    $_POST["g-recaptcha-response"]
                );
                if (!empty($_POST['category_id'])) {
                    if ($resp != null && $resp->success) {
                        $data = $this->request->data;
                        if (!filter_var($data['storeName'], FILTER_VALIDATE_URL) === false) {
                            $store = $this->mCusApi->resource('Store')->add([
                                'name' => $data['storeName'],
                                'store_url' => $data['storeName'],
                                'categories_id' => [$data['category_id']],
                                'user_id' => $this->Session->read('User.id'),
                                'status' => 'pending'
                            ]);
                            if (sizeof($store['store'])) {
                                $store_id = $store['store']['id'];
                            } else {
                                $response = ['status' => 'error',
                                    'msg' => 'Can not save this store!'];
                                $this->response->body(json_encode($response));
                                return $this->response;
                            }
                        } else {
                            $store_id = $data['storeName'];
                            $store = $this->mCusApi->resource('Store')->edit($store_id, [
                                'category_id' => $data['category_id']
                            ]);
                        }
                        if (isset($data['category_id'])) {
                            $this->mCusApi->resource('CategoryStore')->request('/add', [
                                'method' => 'POST',
                                'data' => [
                                    'store_id' => $store_id,
                                    'category_id' => $data['category_id']
                                ]
                            ]);
                        }
                        $this->mCusApi->resource('Deal')->add(
                            [
                                'store_id' => $store_id,
                                'categories_id' => [$data['category_id']],
                                'title' => $data['titleName'],
                                'description' => $data['description'],
                                'currency' => $data['currency_deal'],
                                'origin_price' => $data['origin_price'],
                                'discount_price' => $data['discount_price'],
                                'discount_percent' => $data['discount_percent'],
                                'produc_url' => $data['product_link'],
                                'user_id' => $this->Session->read('User.id'),
                                'start_date' => !empty($data['startDate']) ? date('Y/m/d', strtotime($data['startDate'])) : null,
                                'expire_date' => date('Y/m/d', strtotime($data['expireDate'])),
                                'deal_image' => !empty($data['image_url']) ? $data['image_url'] : '',
                                'status' => 'pending'
                            ]);
                        $response = ['status' => 'success',
                            'msg' => 'Add a deal successful!'];
                    } else {
                        $response = ['status' => 'error',
                            'msg' => 'Incorrect Captcha code!'];
                    }
                } else $response = ['status' => 'error',
                    'msg' => 'Please choice a Category!'];
            } else {
                $response = ['status' => 'error',
                    'msg' => 'Please enter Captcha code!'];
            }
            $this->response->body(json_encode($response));
            return $this->response;
        }
    }

    public function getCode($id)
    {
        $deal = $this->mCusApi->resource('Deal')->query(['id' => $id]);
        $this->set('deal', $deal['deals'][0]);
    }

    public function getMore()
    {
        $response = $this->mCusApi->resource('Deal')->query($this->request->query);
        if (isset($this->request->query['hot_deal'])) {
            $this->Session->write('all_deals_hot', intval($this->request->query['offset']) + sizeof($response['deals']));
        } else $this->Session->write('all_deals_latest', intval($this->request->query['offset']) + sizeof($response['deals']));
        $this->response->statusCode(200);
        $this->response->type('json');
        $this->response->body(json_encode($response));
        return $this->response;
    }
}
