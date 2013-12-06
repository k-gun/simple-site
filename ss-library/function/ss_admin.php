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

function ss_admin_hiliteImageSizes($src) {
    return preg_replace('~(\d+)x(\d+)~i', '<span>\\1x\\2</span>', $src);
}

// Items
function ss_admin_item_get($id) {
    $table = ss_mysql_table('item');
    return ss_mysql_get("SELECT * FROM $table WHERE id = %d LIMIT 1", $id);
}

function ss_admin_item_getAll() {
    $where = array('1=1');
    $where['status'] = ss_mysql_prepare('status NOT IN (%s)', array(array(SS_ITEM_STATUS_DELETED, SS_ITEM_STATUS_DRAFT)));

    $item_status = ss_filter_getValue('status');
    if (in_array($item_status, cfg('item.status'))) {
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

// Comments
function ss_admin_comment_get($id) {
    $table = ss_mysql_table('item_comment');
    return ss_mysql_get("SELECT * FROM $table WHERE id = %d LIMIT 1", $id);
}

function ss_admin_comment_getAll() {
    $where = array('1=1');

    $item_status = ss_filter_getValue('status');
    if ($item_status == SS_COMMENT_STATUS_WAITING || $item_status == SS_COMMENT_STATUS_PUBLISHED) {
        $where['status'] = ss_mysql_prepare('status = %s', $item_status);
    } elseif ($item_status == 'all') {
        $where['status'] = ss_mysql_prepare('status IN (%s)', array(array(0,1)));
    }

    if ($ssq = ss_filter_getValue('ssq')) {
        $where[] = ss_mysql_prepare('content LIKE %s', array("%$ssq%"));
    }
    $where = join(' AND ', $where);
    // pre($where);

    $table = ss_mysql_table('item_comment');
    $count = ss_mysql_count($table, $where);
    if ($count) {
        $pager = new Pager($count);
        ss_set('pager', $pager);
        $table_item = ss_mysql_table('item');
        return ss_mysql_getAll("SELECT c.*, i.title item_title FROM $table c JOIN $table_item i ON i.id = c.item_id WHERE $where ORDER BY c.id DESC LIMIT %d,%d", array($pager->start, $pager->stop));
    }
}

function ss_admin_comment_update($id, $comment) {
    $comment_content      = ss_filter_arrayValue($comment, 'content', true);
    $comment_status       = ss_filter_arrayValue($comment, 'status', true);
    $comment_author_name  = ss_filter_arrayValue($comment, 'author_name', true);
    $comment_author_email = ss_filter_arrayValue($comment, 'author_email', true);
    if ($comment_status == SS_COMMENT_STATUS_WAITING || $comment_status == SS_COMMENT_STATUS_PUBLISHED) {
        ss_mysql_update(ss_mysql_table('item_comment'), array(
            'content'      => $comment_content,
            'status'       => $comment_status,
            'author_name'  => $comment_author_name,
            'author_email' => $comment_author_email,
        ), 'id = %d', $id, 1);
    }
}

function ss_admin_comment_delete($id) {
    ss_mysql_delete(ss_mysql_table('item_comment'), 'id = %d', $id, 1);
}

function ss_admin_comment_setStatus($id, $status) {
    if ($status == SS_COMMENT_STATUS_WAITING || $status == SS_COMMENT_STATUS_PUBLISHED) {
        ss_mysql_update(ss_mysql_table('item_comment'), array(
            'status' => $status,
        ), 'id = %d', $id, 1);
    }
}