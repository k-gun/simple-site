<?php
static $cfg;
$cfg = array();

$cfg['site.defaultLocale']   = 'en_US';
$cfg['site.defaultTimezone'] = 'UTC';
$cfg['site.defaultEncoding'] = 'utf-8';

$cfg['site.name'] = 'Simple Site';
$cfg['site.title'] = 'Simple Site';
$cfg['site.description'] = '';

$cfg['site.theme'] = 'default';
$cfg['site.linkFormats'] = array(
    'item' => '/item/{title_slug}.html',
);

$cfg['item.status'] = array('draft', 'waiting', 'published', 'deleted');

$cfg['routes'] = array(
    'home' => array(
        'file' => 'home.php',
        'patterns' => array('~^/$~'),
    ),
    'search' => array(
        'file' => 'search.php',
        'patterns' => array('~^/search?$~'),
    ),
    'item' => array(
        'file' => 'item.php',
        'patterns' => array(
            # /123.item
            '~^/(?<id>\d+)\.item$~i',
            # /item/123 & /item/123.html
            # /item-123 & /item-123.html
            '~^/item[/\-](?<id>\d+)(\.html|)$~i',
            # /item/123/lorem-ipsum & /item/123/lorem-ipsum.html
            # /item-123-lorem-ipsum & /item-123-lorem-ipsum.html
            '~^/item[/\-](?<id>\d+)[/\-](?<slug>[a-z0-9-]+)(\.html|)$~i',
            # /lorem-ipsum/123.item & /lorem-ipsum/123.html
            # /lorem-ipsum-123.item & /lorem-ipsum-123.html
            '~^/(?<slug>[a-z0-9-]+)[/\-](?<id>\d+)\.(item|html)$~i',
            # /item/lorem-ipsum.html
            '~^/item/(?<slug>[a-z0-9-]+)(\.html|)$~i',
        )
    )
);

$cfg['media.image.base'] = '/upload/image';
$cfg['media.image.widths'] = array(150, 300, 450, 750);
$cfg['media.image.cropSize'] = 100;
$cfg['media.image.mimeTypes'] = array(
    'image/gif'   => 'gif',
    'image/jpg'   => 'jpg',
    'image/jpeg'  => 'jpeg',
    'image/pjpeg' => 'jpeg',
    'image/png'   => 'png',
    'image/x-png' => 'png',
);
$cfg['media.image.maxUploadSize'] = 2097152; // 2MB

$GLOBALS['cfg'] = $cfg;

function cfg($key, $val = null) {
    // Get value
    if ($val === null) {
        return isset($GLOBALS['cfg'][$key])
            ? $GLOBALS['cfg'][$key] : null;
    }
    // Set value
    $GLOBALS['cfg'][$key] = $val;
}