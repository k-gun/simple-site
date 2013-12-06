<?php
// Change it to your local server ip
define('SS_LOCAL', ($_SERVER['SERVER_ADDR'] == '127.0.1.1'));


if (SS_LOCAL) {
    // Settings: local
    cfg('db.mysql', array(
        'host' => 'localhost',
        'name' => 'simple_site',
        'user' => 'root',
        'pass' => '11111111',
        'charset' => 'utf8',
        'timezone' => '+00:00',
        'table_prefix' => 'ss_',
    ));
} else {
    // Settings: remote
    cfg('db.mysql', array());
}
