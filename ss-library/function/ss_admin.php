<?php
function ss_admin_isLoggedIn() {}
function ss_admin_logIn() {}
function ss_admin_logOut() {}

function ss_admin_getHead() { include(SS_ROOT_ADMIN .'/_head.php'); }
function ss_admin_getFoot() { include(SS_ROOT_ADMIN .'/_foot.php'); }
function ss_admin_getAside() { include(SS_ROOT_ADMIN .'/_aside.php'); }

function ss_admin_link() {
    $args = func_get_args();
    $link = sprintf('%s/ss-admin/%s', SS_HTTP, ltrim(array_shift($args), '/'));
    if (count($args)) {
        $link = vsprintf($link, $args);
    }
    return $link;
}
function ss_admin_asset($filename) {
    return ss_admin_link("/asset/$filename");
}
function ss_admin_image($filename) {
    return ss_admin_link("/image/$filename");
}

function ss_admin_getItem($id) {
    $table = ss_mysql_table('item');
    return ss_mysql_get("SELECT * FROM $table WHERE id = %d", $id);
}

function ss_admin_getItems() {
    $where = array('1=1');
    if ($ssq = ss_filter_getValue('ssq')) {
        // $where[] = ss_mysql_prepare('MATCH(title, content) AGAINST(%s IN BOOLEAN MODE)', "$ssq");
        $where[] = ss_mysql_prepare('(title LIKE %s OR content LIKE %s)', array("%$ssq%", "%$ssq%"));
    }
    $where = join(' AND ', $where);

    $table = ss_mysql_table('item');
    $count = ss_mysql_count($table, $where);
    if ($count) {
        $pager = new Pager($count);
        ss_set('pager', $pager);
        $sql = ss_mysql_prepare("SELECT * FROM $table WHERE $where ORDER BY id DESC LIMIT %d,%d", array($pager->start, $pager->stop));
        // pre($sql);
        return ss_mysql_getAll($sql);
    }
}