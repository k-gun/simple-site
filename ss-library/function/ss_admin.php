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

function ss_admin_item_get($id) {
    $table = ss_mysql_table('item');
    return ss_mysql_get("SELECT * FROM $table WHERE 1=1 AND id = %d", $id);
}

function ss_admin_item_getAll() {
    $where = array('1=1');

    $where['status'] = ss_mysql_prepare('status != %s', SS_ITEM_STATUS_DELETED);

    $item_status = ss_filter_getValue('status');
    if ($item_status == SS_ITEM_STATUS_WAITING ||
        $item_status == SS_ITEM_STATUS_PUBLISHED ||
        $item_status == SS_ITEM_STATUS_DELETED
    ) {
        $where['status'] = ss_mysql_prepare('status = %s', $item_status);
    } elseif ($item_status == 'all') {
        unset($where['status']);
    }

    if ($ssq = ss_filter_getValue('ssq')) {
        $where[] = ss_mysql_prepare('(title LIKE %s OR content LIKE %s)', array("%$ssq%", "%$ssq%"));
    }
    $where = join(' AND ', $where);
    // pre($where);

    $table = ss_mysql_table('item');
    $count = ss_mysql_count($table, $where);
    if ($count) {
        $pager = new Pager($count);
        ss_set('pager', $pager);
        return ss_mysql_getAll("SELECT * FROM $table WHERE $where ORDER BY id DESC LIMIT %d,%d", array($pager->start, $pager->stop));
    }
}

function ss_admin_item_insert($item) {
    $item_title           = ss_filter_arrayValue($item, 'title', true);
    $item_title_slug      = slug($item_title);
    $item_content         = ss_filter_arrayValue($item, 'content');
    $item_status          = ss_filter_arrayValue($item, 'status');
    $item_allow_comment   = ss_filter_arrayValue($item, 'allow_comment', SS_FILTER_TYPE_INT, false);
    if (no($item_title) || no($item_content)) {
        return;
    }
    if (!in_array($item_status, cfg('item.status'))) {
        return;
    }

    $table = ss_mysql_table('item');

    // Check for same title slug
    $count = ss_mysql_count($table, "title_slug = '$item_title_slug' OR title_slug REGEXP '^$item_title_slug-([0-9]*)$'");
    if ($count) {
        $item_title_slug = sprintf('%s-%s', $item_title_slug, $count);
    }

    ss_mysql_insert($table, array(
        'title'           => $item_title,
        'title_slug'      => $item_title_slug,
        'content'         => $item_content,
        'status'          => $item_status,
        'date_time'       => ss_mysql_sql('Unix_Timestamp()'),
        'allow_comment'   => $item_allow_comment,
    ));

    return ss_mysql_insertId();
}

function ss_admin_item_update($id, $item) {
    $item_title           = ss_filter_arrayValue($item, 'title', true);
    $item_title_slug      = slug($item_title);
    $item_content         = ss_filter_arrayValue($item, 'content');
    $item_status          = ss_filter_arrayValue($item, 'status');
    $item_allow_comment   = ss_filter_arrayValue($item, 'allow_comment', SS_FILTER_TYPE_INT, false);
    if (no($item_title) || no($item_content)) {
        return;
    }
    if (!$id || !in_array($item_status, cfg('item.status'))) {
        return;
    }

    $table = ss_mysql_table('item');

    // Check for same title slug
    $count = ss_mysql_count($table, "(title_slug = '$item_title_slug' OR title_slug REGEXP '^$item_title_slug-([0-9]*)$') AND id != $id");
    if ($count) {
        $item_title_slug = sprintf('%s-%s', $item_title_slug, $count);
    }

    ss_mysql_update($table, array(
        'title'           => $item_title,
        'title_slug'      => $item_title_slug,
        'content'         => $item_content,
        'status'          => $item_status,
        'allow_comment'   => $item_allow_comment,
    ), 'id = %d', $id, 1);
}

function ss_admin_item_delete($id) {
    $delete = ss_mysql_delete(ss_mysql_table('item'), 'id = %d', $id, 1);
    if (ss_mysql_rowsAffected()) {
        ss_mysql_delete(ss_mysql_table('item_comment'), 'item_id = %d', $id);
    }
}

function ss_admin_item_setStatus($id, $status) {
    if (in_array($status, cfg('item.status'))) {
        ss_mysql_update(ss_mysql_table('item'), array(
            'status' => $status,
        ), 'id = %d', $id, 1);
    }
}