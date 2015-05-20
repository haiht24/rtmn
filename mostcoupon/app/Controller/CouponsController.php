<?php
require_once "../Vendor/recaptcha2/recaptchalib.php";

class CouponsController extends AppController
{

    public function index($type = null)
    {


    }

    public function topCoupon()
    {
        $hotStores = $this->mCusApi->resource('Store')->query(['status' => 'published', 'best_store' => 1, 'limit' => 10]);

        $hotdeals = $this->mCusApi->resource('Deal')->query(['limit' => 4, 'hot_deal' => 1, 'status' => 'published']);

        $coupons = $this->mCusApi->resource('Coupon')->query([
            'status' => 'published',
            'limit' => 20,
            'sticky' => 'top',
            'expire_date_greater_null' => true,
            'fields' => ['id', 'coupon_type', 'discount', 'currency', 'event_id', 'exclusive', 'verified', 'title_store', 'description_store', 'expire_date',
                'Store.name', 'Store.id', 'Store.alias',
                'User.fullname', 'User.id',
                'Event.name',
                'Property.foreign_key_right',
                'Like.id', 'Like.user_id', 'Like.object_id', 'Like.value']
        ]);

        $this->set('coupons', $coupons);

        $this->set('stores', $hotStores['stores']);
        $this->set('hotDeals', $hotdeals['deals']);


//        $categories = $this->mCusApi->resource('Category')->query(array('status' => 'published'));
//        $this->set('allCategories', $categories['categories']);
//        $this->set('public_key', Configure::read('reCaptcha.public_key'));
//        $events = $this->mCusApi->resource('Event')->query(array('status' => 'published'));
//        $this->set('events', $events['events']);

        $storesCoupon = $this->mCusApi->resource('Store')->query([
            'status' => 'published',
            'best_store' => 1,
            'limit' => 10
        ]);
        $this->set('storesCoupon', $storesCoupon['stores']);
    }

    public function submitCoupon()
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
                        if (!empty($data['category_id'])) {
                            $store = $this->mCusApi->resource('Store')->edit($store_id, [
                                'category_id' => $data['category_id']
                            ]);
                        }
                    }
                    if (!empty($data['category_id'])) {
                        $this->mCusApi->resource('CategoryStore')->request('/add', [
                            'method' => 'POST',
                            'data' => [
                                'store_id' => $store_id,
                                'category_id' => $data['category_id']
                            ]
                        ]);
                    }
                    if ($data['currency_coupon'] == '%' && $data['discount'] > 100) {
                        $date_discount = 100;
                    } else $date_discount = $data['discount'];
                    $saveCoupon = $this->mCusApi->resource('Coupon')->add(
                        [
                            'store_id' => $store_id,
                            'categories_id' => !empty($data['category_id']) ? [$data['category_id']] : [],
                            'title_store' => $data['titleName'],
                            'description_store' => $data['description'],
                            'coupon_type' => $data['coupon_type'],
                            'coupon_code' => $data['yourCode'],
                            'product_link' => $data['product_link'],
                            'currency' => $data['currency_coupon'],
                            'discount' => $date_discount,
                            'user_id' => $this->Session->read('User.id'),
                            'expire_date' => !empty($data['expireDate']) ? date('Y/m/d', strtotime($data['expireDate'])) : null,
                            'event_id' => !empty($data['event_id']) ? $data['event_id'] : null,
                            'coupon_image' => !empty($data['image_url']) ? $data['image_url'] : null,
                            'status' => 'pending'
                        ]);
                    $response = ['status' => 'success',
                        'msg' => 'Add a coupon successful!'];
                } else {
                    $response = ['status' => 'error',
                        'msg' => 'Incorrect Captcha code!'];
                }
            } else {
                $response = ['status' => 'error',
                    'msg' => 'Please enter Captcha code!'];
            }
            $this->response->body(json_encode($response));
            return $this->response;
        }
    }

    public function details($id)
    {

        $details = $this->mCusApi->resource('Coupon')->get($id);


    }

    public function getCode($id)
    {
        $coupon = $this->mCusApi->resource('Coupon')->get($id);
        $this->set('coupon', $coupon['coupon']);
    }

    public function sendInfo()
    {
        $this->response->statusCode(200);
        $this->response->type('json');
        if ($this->request->is('post') && $this->request->is('ajax')) {
            $data = $this->request->data;
            $response = $this->mCusApi->resource('Coupon')->request('/sendMail', ['data' => [
                'email' => $data['email'],
                'id' => $data['id']
            ]]);
            $response = ['status' => 'success',
                'msg' => 'Coupon Sent'];
        } else
            $response = ['status' => 'error',
                'msg' => 'Sending false'];
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function addComment()
    {
        $this->response->statusCode(200);
        $this->response->type('json');
        if ($this->request->is('post')) {
            $secret_key = Configure::read('reCaptcha.secret_key');
            $reCaptcha = new ReCaptcha($secret_key);
            $data = $this->request->data;
            if ($data["g-recaptcha-response"]) {
                $resp = $reCaptcha->verifyResponse(
                    $_SERVER["REMOTE_ADDR"],
                    $data["g-recaptcha-response"]
                );
                if ($resp != null && $resp->success) {
                    if (!$this->Session->check('User.id')) {
                        $response = ['status' => 'error',
                            'msg' => 'Please login!'];
                        $this->response->body(json_encode($response));
                        return $this->response;
                    }
                    $data['user_id'] = $this->Session->read('User.id');
                    $comment = $this->mCusApi->resource('Comment')->add($data);
                    $response = ['status' => 'success',
                        'msg' => 'Add a Comment successful',
                        'comment' => $comment['comment']];
                } else {
                    $response = ['status' => 'error',
                        'msg' => 'Captcha Incorrect!'];
                }
            } else $response = ['status' => 'error',
                'msg' => 'Please check Captcha!'];
        } else
            $response = ['status' => 'error',
                'msg' => 'Add false'];
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function addSaveoff()
    {
        $this->response->statusCode(200);
        $this->response->type('json');
        if ($this->request->is('post')) {
            if (!$this->Session->check('User.id')) {
                $response = ['status' => 'error',
                    'msg' => 'Please login!'];
                $this->response->body(json_encode($response));
                return $this->response;
            }
            $data = $this->request->data;
            $data['user_id'] = $this->Session->read('User.id');
            $comment = $this->mCusApi->resource('Comment')->add($data);
            $response = ['status' => 'success',
                'msg' => 'Add a Comment successful',
                'comment' => $comment['comment']];
        } else
            $response = ['status' => 'error',
                'msg' => 'Add false'];
        $this->response->body(json_encode($response));
        return $this->response;
    }
}
