<?php
// Defines
define('SS_COMMENT_STATUS_WAITING',    'waiting');
define('SS_COMMENT_STATUS_PUBLISHED',  'published');

function ss_comment_insert($comment) {
    $comment_item_id      = ss_filter_arrayValue($comment, 'item_id', SS_FILTER_TYPE_INT);
    $comment_content      = ss_filter_arrayValue($comment, 'content', true);
    $comment_author_name  = ss_filter_arrayValue($comment, 'author_name', true);
    $comment_author_email = ss_filter_arrayValue($comment, 'author_email', true);
    if (no($comment_item_id) || no($comment_content) || no($comment_author_name) || !is_email($comment_author_email)) {
        return;
    }
    ss_mysql_insert(ss_mysql_table('item_comment'), array(
        'item_id'      => $comment_item_id,
        'content'      => $comment_content,
        'status'       => SS_COMMENT_STATUS_WAITING,
        'author_name'  => $comment_author_name,
        'author_email' => $comment_author_email,
    ));
    return ss_mysql_insertId();
}

function ss_comment_getAll($item_id) {
    $table = ss_mysql_table('item_comment');
    $comments = ss_mysql_getAll("SELECT * FROM $table WHERE item_id = %d AND status = %s", array($item_id, SS_COMMENT_STATUS_PUBLISHED));
    return $comments;
}

function the_comment_content($comment) {
    $comment = to_array($comment);
    $comment['content'] = nl2br($comment['content'], false);
    return $comment['content'];
}

function the_comment_contentSub($comment, $sub = 100) {
    $comment = to_array($comment);
    if (($pos = mb_strrpos($comment['content'], ' ')) !== false) {
        $comment['content'] = mb_substr($comment['content'], 0, $pos);
    }
    return $comment['content'];
}

function the_comment_dateTime($comment, $format = null) {
    if (!$format) $format = 'Y-m-d H:i:s';
    $comment = to_array($comment);
    return date($format, $comment['date_time']);
}