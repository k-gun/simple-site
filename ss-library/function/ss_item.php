<?php
// Defines
define('SS_ITEM_STATUS_WAITING',    'waiting');
define('SS_ITEM_STATUS_PUBLISHED',  'published');
define('SS_ITEM_STATUS_DELETED',    'deleted');
define('SS_ITEM_STATUS_DRAFT',      'draft');

function ss_item_get($opts = null) {
    $opts = get_options($opts);

    $where = '';
    $route = ss_route();
    if (isset($route['id'])) {
        $where = ss_mysql_prepare('AND id = %d', $route['id']);
    } elseif (isset($route['slug'])) {
        $where = ss_mysql_prepare('AND title_slug = %s', strtolower($route['slug']));
    }

    $table = ss_mysql_table('item');
    return ss_mysql_get("SELECT * FROM $table WHERE status = %s $where", array(SS_ITEM_STATUS_PUBLISHED));
}

function ss_item_getAll($opts = null, $limit = array(0,10)) {
    $opts = get_options($opts);

    $table = ss_mysql_table('item');
    return ss_mysql_get("SELECT * FROM $table WHERE status = %s", array(SS_ITEM_STATUS_PUBLISHED));
}

function ss_item_getBySearch($opts = null, $q, $limit = array(0,10)) {
    $opts = get_options($opts);

    $route = ss_route();
    $q = get_get_value('q');
    if (!$q) {
        return ss_item_getAll($limit);
    }
}

// @not in use
function ss_item_getLinks($opts = null) {
    $opts = get_options($opts);

    $order_by = 'title_slug ASC';
    if ($opt_order = get_array_value($opts, 'order')) {
        list($order_field, $order_direction) = explode(',', $opt_order, 2);
        $order_by = sprintf('%s %s', ss_mysql_escapeIdentifier($order_field), strtoupper($order_direction));
    }

    $limit = '';
    if ($opt_limit = get_array_value($opts, 'limit')) {
        $limit = ss_mysql_prepare("LIMIT %d", $opt_limit);
    }

    $table = ss_mysql_table('item');
    $links = ss_mysql_getAll("SELECT * FROM $table ORDER BY $order_by $limit");
    if (!empty($links)) {
        $links = array_map('the_item_link', $links);
    }

    return $links;
}

function ss_item_formatLink($item, $format = null) {
        if (!$format) {
            $format = $GLOBALS['cfg']['site.linkFormats']['item'];
        }
        $item = to_array($item);
        static $keys;
        if ($keys === null) {
            $keys = array_map(function($k){
                return '{'. $k .'}';
            }, array_keys($item));
        }
        $link = str_replace($keys, $item, $format);
        return $link;
}

function ss_item_applyCallback($input, Closure $callback = null) {
    if ($callback !== null && is_callable($callback)) {
        $input = $callback($input);
    }
    return $input;
}

// Printer functions
function the_item_link($item, Closure $callback = null) {
    $item = to_array($item);
    $item_link = ss_item_formatLink($item);
    return ss_item_applyCallback($item_link, $callback);
}

function the_item_title($item, Closure $callback = null) {
    $item = to_array($item);
    return ss_item_applyCallback($item['title'], $callback);
}

function the_item_titleSlug($item, Closure $callback = null) {
    $item = to_array($item);
    return ss_item_applyCallback($item['title_slug']);
}

function the_item_content($item, Closure $callback = null, $opts = null) {
    $opts = get_options($opts);
    $item = to_array($item);
    if (isset($opts['strip'])) {
        $item['content'] = strip_tags($item['content']);
    }
    if (!isset($opts['nobr'])) {
        $item['content'] = nl2br($item['content']);
    }
    return ss_item_applyCallback($item['content']);
}

function the_item_dateTime($item, $format = null) {
    if (!$format) $format = 'Y-m-d H:i:s';
    $item = to_array($item);
    return date($format, $item['date_time']);
}

function the_item_contentSub($item, Closure $callback = null, $opts = null, $sub = 100) {
    $opts = get_options($opts);
    $opts['nobr'] = true;
    $opts['strip'] = true;
    $content = the_item_content($item, $callback, $opts);
    $content = mb_substr($content, 0, $sub);
    if (($pos = mb_strrpos($content, ' ')) !== false) {
        $content = mb_substr($content, 0, $pos);
    }
    return $content;
}

function the_item_images($item, Closure $callback = null) {
    $item = to_array($item);
    $item_images = array();
    preg_match_all('~(<img.*?/?>)~i', $item['content'], $matches1);
    if (isset($matches1[1]) && !empty($matches1[1])) {
        foreach ($matches1[1] as $i => $image) {
            preg_match_all('~([\w]+)=["]([^"]*)~i', $image, $matches2);
            $item_images[$i]['source'] = $image;
            $item_images[$i]['attributes'] = array_combine($matches2[1], $matches2[2]);
        }
    }
    return ss_item_applyCallback($item_images);
}

function the_item_isCommenAllowed($item) {
    $item = to_array($item);
    return (bool) $item['allow_comment'];
}