<?php
// Defines
define('SS_INTL_DEFAULT_LOCALE',   cfg('site.defaultLocale'));
define('SS_INTL_DEFAULT_LANGUAGE', substr(SS_INTL_DEFAULT_LOCALE, 0, 2));

function ss_intl_getLanguage() {
    return SS_INTL_DEFAULT_LANGUAGE;
}