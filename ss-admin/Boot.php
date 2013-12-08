<?php
include_once(__DIR__ .'/../Boot.php');

$uri = basename(get_uri());
if (!is_user() && $uri != 'login' && $uri != 'login.php') {
    redirect('/ss-admin/login?return=%s', ss_filter(get_uri(), true));
}

load_function('ss_admin');