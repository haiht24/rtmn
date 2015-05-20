<?php

App::uses('AppHelper', 'View/Helper');

class TnHelper extends AppHelper {
    function underscore2Camelcase($str) {
        // Split string in words.
        $words = explode('_', strtolower($str));

        $return = '';
        foreach ($words as $word) {
            $return .= ucfirst(trim($word));
        }

        return $return;
    }
    function slug($str) {
        // trim the string
        $str = strtolower(trim($str));
        // replace all non valid characters and spaces with an underscore
        $str = preg_replace('/[^a-z0-9-]/', '_', $str);
        $str = preg_replace('/-+/', "_", $str);
        return $str;
    }
}