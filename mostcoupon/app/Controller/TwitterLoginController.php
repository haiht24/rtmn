<?php
 App::import('Vendor', 'OAuth/OAuthClient');

 class TwitterLoginController extends AppController {
     public function index() {

     }

     public function login() {
         $client = $this->createClient();
         $requestToken = $client->getRequestToken('https://api.twitter.com/oauth/request_token', 'http://' .
             //$_SERVER['HTTP_HOST'] . '/TwitterLogin/callback');
             'localhost/MostCoupon/mostcoupon' . '/TwitterLogin/callback');

         if ($requestToken) {
             $this->Session->write('twitter_request_token', $requestToken);
             $this->redirect('https://api.twitter.com/oauth/authorize?oauth_token=' . $requestToken->key);
         } else {
             // an error occured when obtaining a request token
         }
     }

     public function callback() {
         $requestToken = $this->Session->read('twitter_request_token');
         $client = $this->createClient();
         $accessToken = $client->getAccessToken('https://api.twitter.com/oauth/access_token', $requestToken);

         if ($accessToken) {
             $resp = $client->post($accessToken->key, $accessToken->secret,
                 'https://api.twitter.com/1.1/statuses/update.json', array('status' => 'hello world!'));
                 echo '<pre>';var_dump($resp);echo '</pre>'; die();
         }
     }

     private function createClient() {
         return new OAuthClient('sVGgOoxjtn4lsfnSkdIJATQTX',
             '1tbKVeWkbAc59CgzCSfQNTNdcN0L3wSBXJaHGbj8GZHw7tLGUm');
     }
 }
