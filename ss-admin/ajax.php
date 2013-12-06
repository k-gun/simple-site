<?php include(__DIR__.'/Boot.php');

$do =@ $_GET['do'];

if ($do == 'item-save-draft') {
    $item = get_post_value('item');
    $item_title           = ss_filter_arrayValue($item, 'title', true);
    $item_title_slug      = slug($item_title);
    $item_content         = ss_filter_arrayValue($item, 'content');
    $item_status          = ss_filter_arrayValue($item, 'status');
    $item_allow_comment   = isset($item['allow_comment']);
    $item_draft_token     = ss_filter_arrayValue($item, 'draft_token', true);
    if (no($item_draft_token)) {
        return;
    }
    if (!in_array($item_status, cfg('item.status'))) {
        return;
    }

    $table = ss_mysql_table('item');

    // Check for same title slug
    $count = ss_mysql_count($table, "draft_token != '$item_draft_token' AND (title_slug = '$item_title_slug' OR title_slug REGEXP '^$item_title_slug-([0-9]*)$')");
    if ($count) {
        $item_title_slug = sprintf('%s-%s', $item_title_slug, $count);
    }

    /* ss_mysql_query("INSERT INTO $table (id, title, title_slug, content, status, date_time, allow_comment) VALUES((SELECT id + 1 FROM (SELECT IF(Max(id), Max(id), 0) AS id FROM $table LIMIT 1) AS tmp), :title, :title_slug, :content, :status, :date_time, :allow_comment) ON DUPLICATE KEY UPDATE title = VALUES(title), title_slug = VALUES(title_slug), content = VALUES(content), status = VALUES(status), date_time = VALUES(date_time), allow_comment = VALUES(allow_comment)", array(
        ':title'           => $item_title,
        ':title_slug'      => $item_title_slug,
        ':content'         => $item_content,
        ':status'          => $item_status,
        ':date_time'       => ss_mysql_sql('Unix_Timestamp()'),
        ':allow_comment'   => $item_allow_comment,
    )); */

    ss_mysql_query("INSERT INTO $table (title, title_slug, content, status, date_time, allow_comment, draft_token) VALUES(:title, :title_slug, :content, :status, :date_time, :allow_comment, :draft_token) ON DUPLICATE KEY UPDATE title = VALUES(title), title_slug = VALUES(title_slug), content = VALUES(content), status = VALUES(status), date_time = VALUES(date_time), allow_comment = VALUES(allow_comment)", array(
        'title'           => $item_title,
        'title_slug'      => $item_title_slug,
        'content'         => $item_content,
        'status'          => $item_status,
        'date_time'       => ss_mysql_sql('Unix_Timestamp()'),
        'allow_comment'   => $item_allow_comment,
        'draft_token'     => $item_draft_token,
    ));
}