

<?php
App::uses('Helper', 'View');

class ColorHelper extends Helper {
/**
* Change the brightness of the passed in color
*
* $diff should be negative to go darker, positive to go lighter and
* is subtracted from the decimal (0-255) value of the color
*
* @param string $hex color to be modified
* @param string $diff amount to change the color
* @return string hex color
*/    
    public function hexColorMod($hex, $diff) {
        $rgb = str_split(trim($hex, '# '), 2);		 
        foreach ($rgb as &$hex) {
            $dec = hexdec($hex);
            if ($diff >= 0) {
                $dec += $diff;
            } else {
                $dec -= abs($diff);	
            }
            $dec = max(0, min(255, $dec));
            $hex = str_pad(dechex($dec), 2, '0', STR_PAD_LEFT);
        } 
        return '#'.implode($rgb);
    }
    public function hexToCss($hex, $opacity) {
        $hex = str_replace("#", "", $hex);
        if(strlen($hex) == 3) {
           $r = hexdec(substr($hex,0,1).substr($hex,0,1));
           $g = hexdec(substr($hex,1,1).substr($hex,1,1));
           $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
           $r = hexdec(substr($hex,0,2));
           $g = hexdec(substr($hex,2,2));
           $b = hexdec(substr($hex,4,2));
        }
        
        return "rgba(".$r.",".$g.",".$b.",".$opacity.")";
     }

     public function backgroundColorToCSS($hex, $opacity) {
         $argb = dechex($opacity * 255). str_replace("#", "", $hex);
         $rgba = $this->hexToCss($hex, $opacity);
         return "background-color:".$rgba. "!important;"
                 ."filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#" .$argb . ",endColorstr=#".$argb.") !important;";
     }
}
