<?php 

App::uses('FormHelper', 'View/Helper');

class I18nFormHelper extends FormHelper {

    /**
     * Automatically add language to urls
     */
    public function url($url = null, $full = false) {
        if(is_array($url) && !isset($url['language'])) {
            $url['language'] = Configure::read('Config.language');
        }
        return h(Router::url($url, $full));
    }

}

?>