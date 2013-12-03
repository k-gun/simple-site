<?php
// Defines
define('SS_THEME_FILE_404',  '_404.phtml');
define('SS_THEME_FILE_HEAD', '_head.phtml');
define('SS_THEME_FILE_BODY', '_body.phtml');
define('SS_THEME_FILE_FOOT', '_foot.phtml');
// Current theme root
define('SS_THEME_CURRENT', cfg('site.theme'));

function ss_theme_getSite() {
    // Maybe some stuff later...
    ss_theme_getHead();
    ss_theme_getBody();
    ss_theme_getFoot();
}

function ss_theme_getHead() {
    include_once sprintf('%s/%s/%s', SS_ROOT_THEME, SS_THEME_CURRENT, SS_THEME_FILE_HEAD);
}

function ss_theme_getBody() {
    include_once sprintf('%s/%s/%s', SS_ROOT_THEME, SS_THEME_CURRENT, SS_THEME_FILE_BODY);
}

function ss_theme_getFoot() {
    include_once sprintf('%s/%s/%s', SS_ROOT_THEME, SS_THEME_CURRENT, SS_THEME_FILE_FOOT);
}

function ss_theme_setPageTitle($title) {
    $GLOBALS['ss.site.title'] = trim($title);
}

function ss_theme_getPageTitle() {
    if (isset($GLOBALS['ss.site.title'])) {
        return $GLOBALS['ss.site.title'];
    }
    return cfg('site.title');
}

function ss_theme_setPageDescription($description) {
    $GLOBALS['ss.site.description'] = $description;
}

function ss_theme_getPageDescription() {
    if (isset($GLOBALS['ss.site.description'])) {
        return $GLOBALS['ss.site.description'];
    }
    return cfg('site.description');
}