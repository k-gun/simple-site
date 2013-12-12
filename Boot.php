<?php
// Gzip!!!
if (!ob_start('ob_gzhandler')) {
    ob_start();
}

// Session
if (!session_id()) {
    session_name('ss');
    session_start();
}

// Base defines
define('SS_HTTP', 'http://'. $_SERVER['SERVER_NAME']);
define('SS_ROOT', __DIR__);
define('SS_ROOT_ADMIN', SS_ROOT .'/ss-admin');
define('SS_ROOT_THEME', SS_ROOT .'/ss-theme');
define('SS_ROOT_LIBRARY', SS_ROOT .'/ss-library');

// SS stack
$GLOBALS['ss'] = array();

// Include base configs
include_once(SS_ROOT .'/ss_cfg.php');
include_once(SS_ROOT .'/ss_cfg_user.php');
// Include base functions
include_once(SS_ROOT .'/ss_fns.php');

// Encoding stuff
mb_http_output($cfg['site.defaultEncoding']);
mb_internal_encoding($cfg['site.defaultEncoding']);
// Timezone stuff
date_default_timezone_set($cfg['site.defaultTimezone']);

// Load functions
load_function('ss');
load_function('ss_mysql');
load_function('ss_user');
load_function('ss_intl');
load_function('ss_html');
load_function('ss_route');
load_function('ss_media');
load_function('ss_item');
load_function('ss_comment');
load_function('ss_menu');
load_function('ss_theme');
load_function('ss_filter');
load_function('ss_browser');

// Register autoload.
spl_autoload_register('load_class');

// Sent content type
header('Content-Type: text/html; charset='. $cfg['site.defaultEncoding']);
// Remove expose php if exists
header_remove("X-Powered-By");