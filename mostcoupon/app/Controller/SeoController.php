<?php

class SeoController extends AppController {

    public $helpers = array('Cache');

    public $cacheAction = array('sitemap' => 50000);

    public function robots() {
        $this->autoRender = false;
        if(Configure::read('debug')) {
            echo "User-agent: *  \n";
            echo "Disallow: /";
        } else {
            echo "User-agent: * \n";
            echo "Allow: / \n";
            echo "Disallow: /wp-login.php \n";
            echo "Disallow: /wp-admin/ \n";
            echo "Disallow: /wp-content/ \n";
            echo "Disallow: /wp-includes/ \n";
        }
    }

    public function sitemap() {
        $this->autoRender = false;
        if($this->RequestHandler->ext == 'xml') {
            $result = Cache::read('sitemap', '_seo_');
            if(!$result) {
                //get from database and show.
                $this->set('shortkeys', $shortkeys);//
                $response = $this->render();
                Cache::write('sitemap', $response, '_seo_');
                return $response;
            }
            return $result;
        }
    }
}
?>
