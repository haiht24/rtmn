<?php
 App::import('Controller', 'WpPostmeta');
 class WpPostsController extends AppController {
     public function index() {
         $this->layout = 'default';
         //$this->autoRender = false;
         //$this->set('posts', $this->WpPost->find('all'));
     }
     public function addByJq() {
         $this->autoRender = false;
         $database = $this->request->data['database'];
         $this->WpPost->useDbConfig = $database;

         $WpPostmeta = new WpPostmetaController;
         $WpPostmeta->constructClasses();

         if ($this->request->is('post')) {
             $this->WpPost->create();
             $this->WpPost->set(array('post_status' => 'pending', 'post_type' => 'store'));
             // check if exists post_title
             $conditions = array('WpPost.post_title' => trim($this->request->data['post_title']));
             if ($this->WpPost->hasAny($conditions) == false) {
                 if ($this->WpPost->save($this->request->data)) {
                     $postId = $this->WpPost->getInsertID();
                     $data = array(
                         array(
                             'post_id' => $postId,
                             'meta_key' => 'class_coupon_parent_metadata',
                             'meta_value' => trim($this->request->data['classForCouponParent'])),
                         array(
                             'post_id' => $postId,
                             'meta_key' => 'class_coupon_title_metadata',
                             'meta_value' => trim($this->request->data['classForCouponTitle'])),
                         array(
                             'post_id' => $postId,
                             'meta_key' => 'class_coupon_code_metadata',
                             'meta_value' => trim($this->request->data['classForCouponCode'])),
                         array(
                             'post_id' => $postId,
                             'meta_key' => 'class_coupon_desc_metadata',
                             'meta_value' => trim($this->request->data['classForCouponDesc'])),
                         array(
                             'post_id' => $postId,
                             'meta_key' => 'class_coupon_expire_metadata',
                             'meta_value' => trim($this->request->data['classForCouponExpire'])),
                         array(
                             'post_id' => $postId,
                             'meta_key' => 'class_store_breadcrumb_metadata',
                             'meta_value' => trim($this->request->data['classForBreadcrumb'])),

                         array(
                             'post_id' => $postId,
                             'meta_key' => 'store_url_metadata',
                             'meta_value' => trim($this->request->data['url'])),
                         array(
                             'post_id' => $postId,
                             'meta_key' => 'class_store_homepage_metadata',
                             'meta_value' => trim($this->request->data['classForHomepage'])),
                         array(
                             'post_id' => $postId,
                             'meta_key' => 'class_store_logo_metadata',
                             'meta_value' => trim($this->request->data['classForLogo'])),
                         array(
                             'post_id' => $postId,
                             'meta_key' => 'class_store_desc_metadata',
                             'meta_value' => trim($this->request->data['classForDesc'])));
                     $WpPostmeta->add($data, $database);
                 } else {
                     echo 'error';
                 }
             }

         }
     }
     public function loadStores() {
         $this->autoRender = false;
         $database = $this->request->data['database'];
         $this->WpPost->useDbConfig = $database;
         $dataStores = $this->WpPost->query("SELECT ID,post_title FROM wp_posts WHERE post_type = 'store' AND post_status = 'pending'");
         $returnArr = array();
         foreach ($dataStores as $d) {
             $storeMeta = $this->WpPost->query("SELECT * FROM wp_postmeta WHERE post_id = " . $d['wp_posts']['ID'] .
                 " AND meta_key != '_edit_lock'");
             foreach ($storeMeta as $m) {
                 $d['wp_posts'][$m['wp_postmeta']['meta_key']] = $m['wp_postmeta']['meta_value'];
             }
             array_push($returnArr, $d['wp_posts']);
         }
         echo json_encode($returnArr);
     }
     public function getStoreInfo() {
         $this->autoRender = false;
         $database = $this->request->data['database'];
         $this->WpPost->useDbConfig = $database;
         // import lib
         App::import('Vendor', 'SimpleHtmlDom', 'simple_html_dom.php');
         // get data from request
         $storeId = $this->request->data['storeId'];
         $storeUrl = $this->request->data['storeUrl'];
         $classStoreLogo = $this->request->data['classStoreLogo'];
         $classStoreHomepage = $this->request->data['classStoreHomepage'];
         $classStoreDescription = $this->request->data['classStoreDescription'];
         $classStoreBreadcrumb = $this->request->data['classStoreBreadcrumb'];

         $classCouponContainer = $this->request->data['classCouponParent'];
         $classCouponContainer = explode('|', $classCouponContainer);
         $classCouponParent = $classCouponContainer[1];
         $classCouponContainer = $classCouponContainer[0];

         $classCouponTitle = $this->request->data['classCouponTitle'];
         $classCouponCode = $this->request->data['classCouponCode'];
         $classCouponDesc = $this->request->data['classCouponDescription'];
         $classCouponExpire = $this->request->data['classCouponExpire'];
         /**
          * GET STORE INFORMATION
          */
         // get source
         $html = file_get_html($storeUrl);
         // get parents categories of store
         if ($classStoreBreadcrumb) {
             foreach ($html->find($classStoreBreadcrumb) as $li) {
                 $catName = trim($li->plaintext);
                 $checkExistCatName = $this->checkCategoryName($catName, $database);
                 if ($checkExistCatName > 0) {
                    $this->selectCategories($checkExistCatName, $storeId, $database);
                 }
             }
         }
         // get logo image from attribute
         if ($classStoreLogo) {
             $logoAtt = '';
             if (strpos($classStoreLogo, '|')) {
                 $arrClassStoreLogo = explode('|', $classStoreLogo);
                 $classStoreLogo = $arrClassStoreLogo[0];
                 $logoAtt = $arrClassStoreLogo[1];
             }
             if (!$logoAtt) {
                 $logoUrl = $html->find($classStoreLogo, 0)->src;
             } else {
                 $logoUrl = $html->find($classStoreLogo, 0)->getAttribute($logoAtt);
             }
         }
         // get store home page
         $homePage = '';
         if ($classStoreHomepage) {
             $homePage = $html->find($classStoreHomepage, 0)->plaintext;
         }
         // get store description
         $storeDesc = '';
         if ($classStoreDescription) {
             $storeDesc = $html->find($classStoreDescription, 0)->plaintext;
         }
         // Update store description
         $data = array();
         $data['id'] = $storeId;
         if ($storeDesc) {
             $data['post_content'] = $storeDesc;
         }
         $this->WpPost->set($data);
         $this->WpPost->save();

         // update store metadata
         $WpPostmeta = new WpPostmetaController;

         $WpPostmeta->constructClasses();
         // check if not exist meta_key related to store id
         $data = array();
         if ($WpPostmeta->checkExistMeta($storeId, 'store_img_metadata', $database) == 0) {
             array_push($data, array(
                 'post_id' => $storeId,
                 'meta_key' => 'store_img_metadata',
                 'meta_value' => $logoUrl));
         }
         if ($WpPostmeta->checkExistMeta($storeId, 'store_homepage_metadata', $database) == 0) {
             array_push($data, array(
                 'post_id' => $storeId,
                 'meta_key' => 'store_homepage_metadata',
                 'meta_value' => $homePage));
         }
         if (count($data) > 0) {
             $WpPostmeta->add($data, $database);
         }
         /**
          * GET COUPON INFORMATION
          */
         if ($classCouponParent) {
             $objParent = $html->find($classCouponContainer, 0);
             $arrCoupons = array();
             $arrSingleCoupon = array();
             foreach ($objParent->find($classCouponParent) as $p) {
                 if ($classCouponTitle) {
                     // title
                     if (sizeof($p->find($classCouponTitle, 0)) > 0) {
                         $arrSingleCoupon['post_title'] = trim($p->find($classCouponTitle, 0)->plaintext);
                     } else {
                         $arrSingleCoupon['post_title'] = '';
                     }
                 }
                 if ($classCouponDesc) {
                     // description
                     if (sizeof($p->find($classCouponDesc, 0)) > 0) {
                         $arrSingleCoupon['post_content'] = trim($p->find($classCouponDesc, 0)->plaintext);
                     } else {
                         $arrSingleCoupon['post_content'] = '';
                     }
                 }
                 if ($classCouponCode) {
                     // code
                     if (sizeof($p->find($classCouponCode, 0)) > 0) {
                         $arrSingleCoupon['coupon_code_metadata'] = trim($p->find($classCouponCode, 0)->plaintext);
                     } else {
                         $arrSingleCoupon['coupon_code_metadata'] = '';
                     }
                 }
                 if ($classCouponExpire) {
                     // expire date
                     if (sizeof($p->find($classCouponExpire, 0)) > 0) {
                         $arrSingleCoupon['coupon_expire_date_metadata'] = trim($p->find($classCouponExpire, 0)->plaintext);
                     } else {
                         $arrSingleCoupon['coupon_expire_date_metadata'] = '';
                     }
                 }
                 array_push($arrCoupons, $arrSingleCoupon);
             }
             if (count($arrCoupons)) {
                 foreach ($arrCoupons as $c) {
                     // add new coupon to store
                     $this->WpPost->create();
                     $this->WpPost->set(array(
                         'post_title' => $c['post_title'],
                         'post_content' => $c['post_content'],
                         'post_status' => 'pending',
                         'post_type' => 'coupon'));
                     // check if exists post_title
                     $conditions = array('WpPost.post_title' => trim($c['post_title']));
                     if ($this->WpPost->hasAny($conditions) == false) {
                         if ($this->WpPost->save()) {
                             // add coupon metadata
                             $newCouponId = $this->WpPost->getInsertID();
                             $dataMetadata = array(
                                 array(
                                     'post_id' => $newCouponId,
                                     'meta_key' => 'coupon_code_metadata',
                                     'meta_value' => $c['coupon_code_metadata']),
                                 array(
                                     'post_id' => $newCouponId,
                                     'meta_key' => 'coupon_expire_date_metadata',
                                     'meta_value' => $c['coupon_expire_date_metadata']),
                                 array(
                                     'post_id' => $newCouponId,
                                     'meta_key' => 'store_id_metadata',
                                     'meta_value' => $storeId));
                             $WpPostmeta->add($dataMetadata, $database);
                         }
                     }
                 }
             }
             // Update getted coupons
         }
     }
     public function checkCategoryName($catName, $database) {
         $this->WpPost->useDbConfig = $database;
         $rs = $this->WpPost->query("select term_id from wp_terms where name='{$catName}'");
         if ($rs) {
             return $rs[0]['wp_terms']['term_id'];
         } else {
             return 0;
         }
     }
     public function selectCategories($termId, $storeId, $database) {
         $this->WpPost->useDbConfig = $database;
         $rs = $this->WpPost->query("SELECT term_taxonomy_id FROM wp_term_taxonomy WHERE term_id = {$termId}");
         if($rs){
            $termTaxId = $rs[0]['wp_term_taxonomy']['term_taxonomy_id'];
         }
         if($termTaxId){
            $this->WpPost->query("INSERT IGNORE INTO wp_term_relationships VALUES($storeId, $termTaxId, 0)");
         }
     }
 }
?>