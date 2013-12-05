<?php
function ss_media_image_upload($image) {
    if (!isset($image['tmp_name']) || $image['error']) {
        return;
    }
    // /upload/2013/12/05/abcd1234_50x50.jpg
    list($y, $m, $d) = explode('-', date('Y-m-d'));
    $upload_path =  SS_ROOT . sprintf('/%s/%s/%s/%s/', trim(cfg('media.image.base'), '/'), $y, $m, $d);
    pre($upload_path);
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0755, true);
        chmod($upload_path, 0755);
    }

    $info =@ getimagesize($image['tmp_name']);
    if (empty($info) || !isset($info['mime'])
        || !array_key_exists($info['mime'], cfg('media.image.mimeTypes'))
        || $image['size'] > cfg('media.image.maxUploadSize')
    ) { return; }

    $img  = new SSImage($upload_path);
    $hash = $img->hash();
    $ext  = get_image_extension($info['mime']);

    $return = array();
    $return['ext']  = $ext;
    $return['hash'] = $hash;

    foreach (cfg('media.image.widths') as $width) {
        $image['type']   = $info['mime'];
        list($new_width, $new_height) = $img->calcDimsByWitdh($info, $width);
        $image['width']  = $new_width;
        $image['height'] = $new_height;
        $image['ext']    = $ext;
        $image['name']   = sprintf('%s_%sx%s.%s', $hash, $image['width'], $image['height'], $image['ext']);
        $image['width_original']  = $info[0];
        $image['height_original'] = $info[1];

        $img->load($image)
            ->resize($new_width, $new_height)
            ->save()->clean();

        $return['dims'][] = array($new_width, $new_height);
    }

    // Crop
    $crop_size = cfg('media.image.cropSize');
    $image['width']  = $crop_size;
    $image['height'] = $crop_size;
    $image['name'] = sprintf('%s_%sx%s.%s', $hash, $crop_size, $crop_size, $image['ext']);
    $img->load($image)
        ->crop($crop_size, $crop_size)
        ->save()->clean();

    $return['dims'][] = array($crop_size, $crop_size);

    return $return;
}