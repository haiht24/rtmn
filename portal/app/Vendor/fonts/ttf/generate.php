<?php 

// overwrites font path for TCPDF
define('K_PATH_FONTS', dirname(__FILE__) . '/../');

require_once(dirname(__FILE__) . '/../../tecnick.com/tcpdf/tcpdf.php');

foreach (scandir(dirname(__FILE__)) as $file) {

    
    if(substr($file, -4) == '.ttf') {
        TCPDF_FONTS::addTTFfont($file);
    }
}

?>
