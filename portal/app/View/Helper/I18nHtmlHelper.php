<?php 

App::uses('HtmlHelper', 'View/Helper');

class I18nHtmlHelper extends HtmlHelper {

    /**
     * Automatically add language to urls
     */
    public function url($url = null, $full = false) {
//        if(is_array($url)) {
//            if(!isset($url['language'])) {
//                $url['language'] = Configure::read('Config.language');
//            }
//            if(!in_array($url['language'], Configure::read('allowedLanguages'))) {
//                $url['language'] = 'en';
//            }
//        }
        return parent::url($url, $full);
    }
    
    public function linkToLanguage($lang) {
        $url = $this->request->url;
        if(preg_match('/^[a-z]{2}($|\/.*)/', $url)){
            $url = $lang . substr($url, 2);
        }
        return $this->link($lang, '/' . $url);
    }
   
   public function imageUrl($url = null) {
       $result = parent::assetUrl($url, array('pathPrefix' => Configure::read('App.imageBaseUrl')));
       // if end with / we remove the timestamp
       if (substr_compare($url, '/', -1, 1) === 0) {
           $result = preg_replace('/\?.*/', '', $result);
       }
       return $result;
   }

}

?>