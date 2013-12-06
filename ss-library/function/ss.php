<?php
function ss_loadSite() {
    $data = array();
    // Route!
    $route = ss_route();
    // pre($route,1);

    // Prepare data
    if (isset($route[':name'])) {
        switch ($route[':name']) {
            case 'item':
                $data = ss_item_get();
                break;
            case 'search':
                $data = ss_item_getBySearch();
                break;
        }
    }
    // pre($data,1);

    // Set ss data
    ss_set('data', $data);
    // Load site...
    ss_theme_getSite($data);
    // Unset ss data, so no need anymore
    ss_set('data', null);
}

function ss_set($key, $val) {
    $GLOBALS['ss'][$key] = $val;
}

function ss_get($key, $defval = null) {
    // Return specifics
    switch ($key) {
        case 'lang':
        case 'language':      return ss_language();
        case 'encoding':      return ss_encoding();
        case 'title':         return ss_title();
        case 'description':   return ss_description();
    }
    // Return ss value
    return get_array_value($GLOBALS['ss'], $key);
}

function ss_has($key) {
    return isset($GLOBALS['ss'][$key]);
}

function ss_data() {
    return ss_get('data');
}

function ss_title() {
    return ss_theme_getPageTitle();
}
function ss_description() {
    return ss_theme_getPageDescription();
}
function ss_language() {
    return ss_intl_getLanguage();
}
function ss_encoding() {
    return cfg('site.defaultEncoding');
}

function ss_asset($filename) {
    return sprintf('%s/ss-theme/%s/asset/%s', SS_HTTP, cfg('site.theme'), $filename);
}

function ss_image($filename) {
    return sprintf('%s/ss-theme/%s/image/%s', SS_HTTP, cfg('site.theme'), $filename);
}