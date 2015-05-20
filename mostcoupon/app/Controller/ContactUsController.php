<?php
 App::import('Vendor', 'recaptchalib', ['file' => 'recaptchalib.php']);
 class ContactUsController extends AppController {
     public $uses = ['ContactUs', 'StaticPage'];

     public function index() {
         $docs = $this->mCusApi->resource('static_pages')->request('/getByDocKey',
             [
                'data' => ['doc_key' => 'contactUs']
             ]
         );
         $hotStores = $this->mCusApi->resource('Store')->query(array('status' => 'published', 'best_store' => 1, 'limit' => 4));
         $hotDeals = $this->mCusApi->resource('Deal')->query(array('limit' => 4, 'hot_deal' => 1, 'status' => 'published'));
         $stores = $this->mCusApi->resource('Store')->query(array('limit' => 15, 'status' => 'published', 'show_in_homepage' => 1));

         $this->set('docs', $docs['docs']);
         $this->set('hotStores', $hotStores);
         $this->set('hotDeals', $hotDeals);
         $this->set('stores', $stores);
         $this->set('public_key', Configure::read('reCaptcha.public_key'));
     }
     public function send() {
         $this->autoRender = false;
         $postdata = file_get_contents("php://input");
         $request = json_decode($postdata);


         //$privatekey = "6LcT5gATAAAAAGGrINKm5e56iTQ8HWLgnGwIbrvL";
         $privatekey = "6Lfr5QATAAAAABKo7b5rc4uteDEQzO_rtAniFLG8";
         $resp = recaptcha_check_answer (
         $privatekey, $_SERVER["REMOTE_ADDR"], $request->recaptcha_challenge_field, $request->recaptcha_response_field);

         if(!$resp->is_valid){
            echo json_encode(['success' => 0]);
         }
         else{
             $name = $request->name;
             $email = $request->email;
             $subject = $request->subject;
             $keywords = $request->keyword;
             $message = $request->message;
             $time = $request->time;

             $data = $this->mCusApi->resource('contact_us')->request('/save', ['data' =>
             [
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'keywords' => $keywords,
                'message' => $message,
                'sendtime' => $time
             ]]);
             echo json_encode(['success' => 1]);
         }
     }
 }
