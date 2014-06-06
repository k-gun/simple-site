<?php
ob_start();
header('Content-Type: text/javascript');

// tmp
$files = array('qwery.min', 'mii', 'mii.ext', 'mii.array', 'mii.object', 'mii.event', 'mii.ajax', 'mii.animate', 'mii.dom');
// $files = ['mii-all.js'];
foreach ($files as $file) {
    $file .= '.js';
    $js = file_get_contents('/var/www/dev/mii/'. $file);
    print("//*** $file\n");
    print("$js");
    if (substr($js, -1) != "\n") {
        print("\n");
    }
    print("//*** $file end\n");
    print("\n\n");
}