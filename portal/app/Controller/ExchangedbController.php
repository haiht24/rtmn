<?php
use Aws\Common\Aws;
use Aws\S3\Enum\CannedAcl;

class ExchangedbController extends AppController
{
    public $uses = ['Category', 'Store', 'Coupon'];
    public function index() {
        ini_set('max_execution_time', '100000000');
        set_time_limit(0);
        ini_set("memory_limit", "-1M");
    }
    public function doneStore() {
        $this->response->type('json');
        $response = $this->getDoneStore();
        $this->response->body(json_encode($response));
        return $this->response;
    }
    private function getDoneStore() {
        $options = [];
        $options['conditions'] = [];
        $options['conditions'][] = "(store.logo LIKE '%" . Configure::read('AWS.S3.bucketBaseUrl') . "%' OR " .
                                    'store.logo is null OR '.
            "trim(store.logo) = '')";
        $options['conditions'][] = "(store.social_image LIKE '%" . Configure::read('AWS.S3.bucketBaseUrl') . "%' OR " .
            "store.social_image is null OR " .
            "trim(store.social_image) = '')";
                        
        $count = $this->Store->find('count', $options);
        $total = $this->Store->find('count');
        return ['countDone' => $count, 'total' => $total];
    }
    private function getTopNoneStore() {
        $options['conditions'] = [];
        $options['conditions'][] = "(store.logo NOT LIKE '%" . Configure::read('AWS.S3.bucketBaseUrl') . "%' and " .
                                    'store.logo is not null and '.
            "trim(store.logo) != '') OR " .
            "(store.social_image NOT LIKE '%" . Configure::read('AWS.S3.bucketBaseUrl') . "%' and " .
                                    'store.social_image is not null and '.
            "trim(store.social_image) != '')";
        $options['fields'] = ['store.id', 'store.name', 'store.logo', 'store.social_image', 'store.wp_id'];
        $options['order'] = 'store.wp_id DESC';
        return $this->Store->find('first',$options);
    }
    public function backupStore() {
        $this->response->type('json');
        $conn = ConnectionManager::getDataSource('default');
        for ($i = 0; $i < 300; $i++) {
            $store = $this->getTopNoneStore();
            
            if (empty($store)) {
                break;
            }
            if(!empty($store['store']['logo']) && (strpos($store['store']['logo'], Configure::read ( 'AWS.S3.bucketBaseUrl')) === FALSE)) {
                $logo = $this->uploadURL($store['store']['logo']);
                $sql = "update stores set logo = '" . $logo . "' WHERE logo LIKE '" . $store['store']['logo'] . "'";
                $conn->query($sql);
            }
            if(!empty($store['store']['social_image']) && (strpos($store['store']['social_image'], Configure::read ( 'AWS.S3.bucketBaseUrl')) === FALSE)) {
                $social = $this->uploadURL($store['store']['social_image']);
                $sql = "update stores set social_image = '" . $social . "' WHERE social_image LIKE '" . $store['store']['social_image'] . "'";
                $conn->query($sql);
            }
        }
        $response = $this->getDoneStore();;
        $this->response->body(json_encode($response));
        return $this->response;
    }
    public function doneCoupon() {
        $this->response->type('json');
        $response = $this->getDoneCoupon();
        $this->response->body(json_encode($response));
        return $this->response;
    }
    private function getDoneCoupon() {
        $options = [];
        $options['conditions'] = [];
        $options['conditions'][] = "(coupon.social_image LIKE '%" . Configure::read('AWS.S3.bucketBaseUrl') . "%' OR " .
                                    'coupon.social_image is null OR '.
            "trim(coupon.social_image) = '')";
        $options['conditions'][] = "(coupon.coupon_image LIKE '%" . Configure::read('AWS.S3.bucketBaseUrl') . "%' OR " .
                                    'coupon.coupon_image is null OR '.
            "trim(coupon.coupon_image) = '')";
        $countA = $this->Coupon->find('count', $options);
        $total = $this->Coupon->find('count');
        return ['countDone' => $countA, 'total' => $total];
    }
    private function getTopNoneCoupon() {
        $options['conditions'] = [];
        $options['conditions'][] = "(coupon.social_image NOT LIKE '%" . Configure::read('AWS.S3.bucketBaseUrl') . "%' and " .
                                    'coupon.social_image is not null and '.
            "trim(coupon.social_image) != '') OR " .
            "(coupon.coupon_image NOT LIKE '%" . Configure::read('AWS.S3.bucketBaseUrl') . "%' and " .
                                    'coupon.coupon_image is not null and '.
            "trim(coupon.coupon_image) != '')";
        $options['fields'] = ['coupon.id', 'coupon.social_image', 'coupon.coupon_image', 'coupon.wp_id'];
        $options['order'] = 'coupon.wp_id DESC';
        return $this->Coupon->find('first',$options);
    }
    public function backupCoupon() {
        $this->response->type('json');
        $conn = ConnectionManager::getDataSource('default');
        for($i=0; $i< 20; $i++) {
            $coupon = $this->getTopNoneCoupon();
            if (empty($coupon)) {
                break;
            }
            if(!empty($coupon['coupon']['coupon_image']) && (strpos($coupon['coupon']['coupon_image'], Configure::read ( 'AWS.S3.bucketBaseUrl')) === FALSE)) {
                $logo = $this->uploadURL($coupon['coupon']['coupon_image']);
                $sql = "update coupons set coupon_image = '" . $logo . "' WHERE coupon_image LIKE '" . $coupon['coupon']['coupon_image'] . "'";
                $conn->query($sql);
            }
            if(!empty($coupon['coupon']['social_image']) && (strpos($coupon['coupon']['social_image'], Configure::read ( 'AWS.S3.bucketBaseUrl')) === FALSE)) {
                $social = $this->uploadURL($coupon['coupon']['social_image']);
                $sql = "update coupons set social_image = '" . $social . "' WHERE social_image LIKE '" . $coupon['coupon']['social_image'] . "'";
                $conn->query($sql);
            }
        }
        $response = $this->getDoneCoupon();;
        $this->response->body(json_encode($response));
        return $this->response;
    }
    
    public function uploadURL($contentURL) {
        if((strpos($contentURL, 'https') === FALSE)) {} else {
            $contentURL = str_replace("https",'http',$contentURL);
        }
        $fileName = '';
        $listName = split('/', $contentURL);
        if (count($listName) > 1) {
            $fileName = $listName[count($listName) - 1];
        }
        if (!empty($fileName) && (strpos($fileName, Configure::read ( 'AWS.S3.bucketBaseUrl')) === FALSE)) {
            try {
                $ctx = stream_context_create(array(
                    'http' => array(
                        'timeout' => 20000,
                        'ignore_errors' => true
                    )
                ));
                $s3 = Aws::factory(Configure::read('AWS.S3'))->get('s3');
                $vowels = array(" ", "?", ".", "=");
                $return = $s3->putObject(array(
                    'Bucket' => Configure::read ( 'AWS.S3.bucket' ),
                    'Key'    => str_replace ( $vowels, '-', $fileName ),
                    'Body'   => @file_get_contents($contentURL, false, $ctx),
                    'ACL' => CannedAcl::PUBLIC_READ,
                    'ContentType' => 'image/jpeg'
                ));
                return $return ['ObjectURL'];
            } catch (Exception $e) {
                $this->log($e);
                $this->log($contentURL);
                return '';
            }
        }
        return $contentURL;
    }
}
?>