<?php
 class SeoLibs {

     public function replaceKeyword($str, $key, $replaceValue) {
         if (strpos($str, $key) >= 0) {
             $str = str_replace($key, $replaceValue, $str);
         }
         return $str;
     }

     public function seoConvert($str, $siteName, $siteDesc, $title = '', $cpTitle = '', $cpDiscount = '') {
         $str = $this->replaceKeyword($str, '%%sitename%%', $siteName);
         $str = $this->replaceKeyword($str, '%%currentmonth%%', date('F'));
         $str = $this->replaceKeyword($str, '%%currentyear%%', date('Y'));
         $str = $this->replaceKeyword($str, '%%sitedesc%%', $siteDesc);
         $str = $this->replaceKeyword($str, '%%sep%%', '-');
         $str = $this->replaceKeyword($str, '%%title%%', $title);
         $str = $this->replaceKeyword($str, '%%StickyCouponTitle%%', $cpTitle);
         $str = $this->replaceKeyword($str, '%%StickyCouponDiscountValue%%', $cpDiscount);
         return $str;
     }

 }
