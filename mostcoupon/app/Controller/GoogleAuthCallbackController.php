<?php
 class GoogleAuthCallbackController extends AppController {
     public $uses = ['GoogleAuthCallback'];
     public function index() {
        $this->autoRender = false;
        echo 'This is Controller Google Auth Callback';
     }
 }
?>