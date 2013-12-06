<?php
ob_start();
header('Content-Type: text/javascript');

// tmp
$files = ['mii.js', 'mii.event.js', 'mii.animate.js', 'mii.dom.js', 'mii.ajax.js', 'qwery.min.js'];
// $files = ['mii-all.js'];
foreach ($files as $file) {
    $js = file_get_contents('/var/www/dev/mii/'. $file);
    print("//*** $file\n");
    print("$js");
    if (substr($js, -1) != "\n") {
        print("\n");
    }
    print("//*** $file end\n");
    print("\n\n");
}