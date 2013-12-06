<?php
class SSImage
{
    protected $image;
    protected $img1, $img2;
    protected $uploadPath;

    public function __construct($uploadPath = null) {
        if ($uploadPath) {
            $this->uploadPath = $uploadPath;
        }
    }

    public function load($image) {
        $this->image = $image;
        return $this;
    }

    public function save($uploadPath = null, $jpegQuality = 80) {
        $this->_output($uploadPath, $jpegQuality);
        return $this;
    }

    public function resize($w, $h) {
        $this->img1 = imagecreatetruecolor($w, $h);
        $this->img2 = $this->_input();
        imagecopyresampled($this->img1, $this->img2, 0, 0, 0, 0, $w, $h, $this->image['width_original'], $this->image['height_original']);
        return $this;
    }

    /**
     * Crop image by crop sizes.
     */
    public function crop($wc, $wh) {
        // Do not crop original w&h dims
        if ($wc == $this->image['width_original'] &&
            $wh == $this->image['height_original']
        ) {
            return $this->resize($wc, $wh);
        }

        // get dimensions
        $w = $this->image['width_original'];
        $h = $this->image['height_original'];
        // crop half size of image
        $size = ($w > $h) ? $w : $h;
        $per = .5;
        $cw = $size * $per;
        $ch = $size * $per;
        // get top-left coordinates
        $x = ($w - $cw) / 2;
        $y = ($h - $ch) / 2;

        $this->img1 = imagecreatetruecolor($wc, $wh);
        $this->img2 = $this->_input();
        imagecopyresampled($this->img1, $this->img2, 0, 0, $x, $y, $wc, $wh, $cw, $ch);
        return $this;
    }

    /**
     * Generate a canvas by mime.
     */
    protected function _input() {
        switch($this->image['type']) {
            case 'image/gif':
                return imagecreatefromgif($this->image['tmp_name']);
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                return imagecreatefromjpeg($this->image['tmp_name']);
            case 'image/png':
            case 'image/x-png':
                return imagecreatefrompng($this->image['tmp_name']);
        }
    }

    /**
     * Generate image by mime.
     */
    protected function _output($uploadPath = null, $jpegQuality = 80) {
        if ($uploadPath === null) {
            $uploadPath = $this->uploadPath;
        }
        if (substr($uploadPath, -1) != '/') {
            $uploadPath = $uploadPath . '/';
        }
        switch($this->image['type']) {
            case 'image/gif':
                imagegif($this->img1, $uploadPath . $this->image['name']);
                break;
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                imagejpeg($this->img1, $uploadPath . $this->image['name'], $jpegQuality);
                break;
            case 'image/png':
            case 'image/x-png':
                imagealphablending($this->img1, false);
                imagesavealpha($this->img1, true);
                imagepng($this->img1, $uploadPath . $this->image['name']);
                break;
        }
    }

    public function clean() {
        imagedestroy($this->img1);
        imagedestroy($this->img2);
        $this->img1 = $this->img2 = null;
    }

    public function calcDimsByWitdh($info, $width) {
        $sizes = array();
        if($info[0] <= $width) {
            $sizes[0] = $info[0];
            $sizes[1] = $info[1];
        } else {
            $sizes[0] = $width;
            $sizes[1] = (int) (($width / $info[0]) * $info[1]);
        }
        return $sizes;
    }

    public function hash() {
        return hash('crc32b', uniqid(rand() . microtime(), true));
    }

    public function setUploadPath($uploadPath) {
        $this->uploadPath = $uploadPath;
    }
}
