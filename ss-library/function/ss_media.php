<?php
// Defines
define('SS_MEDIA_TYPE_IMAGE', 'image');
define('SS_MEDIA_TYPE_VIDEO', 'video');
define('SS_MEDIA_TYPE_AUDIO', 'audio');

function ss_media_image_upload($image) {
    if (!isset($image['tmp_name']) || $image['error']) {
        return;
    }
    // /upload/2013/12/05/abcd1234_50x50.jpg
    list($y, $m, $d) = explode('-', date('Y-m-d'));
    $image_base  = trim(cfg('media.image.base'), '/');
    $image_path  = "/$image_base/$y/$m/$d";
    $upload_path = SS_ROOT . "/$image_path/";
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

    $data = array();
    $data['ext']  = $ext;
    $data['hash'] = $hash;
    $data['path'] = $image_path;

    $uploaded_dims = array();
    foreach (cfg('media.image.widths') as $width) {
        $image['type']   = $info['mime'];
        list($new_width, $new_height) = $img->calcDimsByWitdh($info, $width);

        // Do note owerwrite...
        if (in_array($new_width, $uploaded_dims)) {
            continue;
        }
        $uploaded_dims[] = $new_width;

        $image['width']  = $new_width;
        $image['height'] = $new_height;
        $image['ext']    = $ext;
        $image['name']   = sprintf('%s_%sx%s.%s', $hash, $image['width'], $image['height'], $image['ext']);
        $image['width_original']  = $info[0];
        $image['height_original'] = $info[1];

        $img->load($image)
            ->resize($new_width, $new_height)
            ->save()->clean();

        $data['dims'][] = array($new_width, $new_height);
    }

    // Crop
    $crop_size = cfg('media.image.cropSize');
    $image['width']  = $crop_size;
    $image['height'] = $crop_size;
    $image['name']   = sprintf('%s_%sx%s_crop.%s', $hash, $crop_size, $crop_size, $image['ext']);
    $img->load($image)
        ->crop($crop_size, $crop_size)
        ->save()->clean();
    $data['crop'] = array($crop_size, $crop_size);

    // Original
    $original_width  = $info[0];
    $original_height = $info[1];
    $image['name']   = sprintf('%s_%sx%s_orig.%s', $hash, $original_width, $original_height, $image['ext']);
    $src_image       = $image['tmp_name'];
    $dst_image       = $upload_path . $image['name'];
    if (@move_uploaded_file($src_image, $dst_image)) {
        $data['orig'] = array($original_width, $original_height);
    }

    return $data;
}

// db stuff
function ss_media_image_getFullPath(array $dims, array $data, $extra = null) {
    if ($extra) {
        return sprintf('%s/%s_%dx%d_%s.%s', $data['path'], $data['hash'], $dims[0], $dims[1], $extra, $data['ext']);
    }
    return sprintf('%s/%s_%dx%d.%s', $data['path'], $data['hash'], $dims[0], $dims[1], $data['ext']);
}

function ss_media_image_getAll($opts = null) {
    $opts = get_options($opts);
    $where = array('1=1');
    if (isset($opts['id'])) {
        $where[] = ss_mysql_prepare('id = %d', $opts['id']);
    }
    $where  = join(' AND ', $where);
    $table  = ss_mysql_table('media');
    $count  = ss_mysql_count($table);
    $pager  = new Pager($count, 5);
    // Store pager to use later
    ss_set('media.pager', $pager);

    $images = ss_mysql_getAll("SELECT * FROM $table WHERE $where ORDER BY id DESC LIMIT %d,%d", array($pager->start, $pager->stop));

    // Return raw json
    if (isset($opts['json'])) {
        return $images;
    }

    // Prepare images to use
    if (!empty($images)) {
        foreach ($images as $i => $image) {
            $data =@ json_decode($image->data, true);
            if (isset($data['dims']) && !empty($data['dims'])) {
                // Set dims src
                foreach ($data['dims'] as $j => $dims) {
                    $data['dims'][$j]['src'] = ss_media_image_getFullPath($dims, $data);
                }
                // Set crop src
                $data['crop']['src'] = ss_media_image_getFullPath(array($data['crop'][0], $data['crop'][1]), $data, 'crop');
                // Set orig src
                $data['orig']['src'] = ss_media_image_getFullPath(array($data['orig'][0], $data['orig'][1]), $data, 'orig');
            }
            $images[$i]->crop = $data['crop'];
            $images[$i]->orig = $data['orig'];
            // Remove these
            unset($data['crop'], $data['orig']);
            // Set image data
            $images[$i]->data = (object) $data;
        }
    }

    return $images;
}

function ss_media_image_insert($data) {
    if (!empty($data)) {
        $data = str_replace('\\/', '/', json_encode($data));
        ss_mysql_insert(ss_mysql_table('media'), array(
            'type' => SS_MEDIA_TYPE_IMAGE,
            'data' => $data,
        ));
        return ss_mysql_insertId();
    }
}

// @tmp
function _remove_images() {
    $images = glob('/var/www/simple-site/upload/2013/12/05/*');
    foreach ($images as $image) {
        @unlink($image);
    }
}
// _remove_images();