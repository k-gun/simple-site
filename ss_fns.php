<?php
function no($var) {
    if (!$var || !isset($var) || empty($var)) {
        return true;
    }
    if ($var instanceof stdClass) {
        foreach ($var as $v) {
            return false;
        }
        return true;
    }
    return false;
}

// Loaders
function load_class($filename) {
    if (($pos = strrpos($filename, '.php')) !== false) {
        $filename = substr($filename, 0, $pos);
    }
    include_once(sprintf('%s/class/%s.php', SS_ROOT_LIBRARY, $filename));
}
function load_function($filename) {
    if (($pos = strrpos($filename, '.php')) !== false) {
        $filename = substr($filename, 0, $pos);
    }
    include_once(sprintf('%s/function/%s.php', SS_ROOT_LIBRARY, $filename));
}

// Converters
function to_array($data) {
    if (!no($data)) {
        if (is_iterable($data)) {
            foreach ($data as $key => $val) {
                if (is_object($val)) {
                    $data->{$key} = to_array($val);
                }
            }
        }
        return (array) $data;
    }
}
function to_object($data) {
    if (!no($data)) {
        if (is_iterable($data)) {
            foreach ($data as $key => $val) {
                if (is_array($val)) {
                    $data[$key] = to_object($val);
                }
            }
        }
        return (object) $data;
    }
}

// @tmp
function slug($text, $lc = true) {
    $text = preg_replace(array('~[^a-z0-9-]~i', '~-+~'), '-', trim($text));
    $text = trim($text, '-');
    return !$lc ? $text : strtolower($text);
}

function token($length = 40, $extra = null) {
    $token = ($extra === null)
        ? hash('sha512', uniqid(mt_rand(), true) . microtime())
        : hash('sha512', uniqid(mt_rand(), true) . microtime() . $extra);
    if ($length) {
        $token = substr($token, 0, $length);
    }
    return $token;
}

function redirect() {
    $args = func_get_args();
    if (count($args) == 1) {
        $location = $args[0];
    } else {
        $location = vsprintf(array_shift($args), $args);
    }
    if ($location == '' || $location == '/') {
        $return   = isset($_GET['return']) ? urldecode($_GET['return']) : null;
        $location = $return ? $return : '/';
    }
    header('Location: '. $location);
    exit;
}

// Getter functions
function get_array_value($array, $key, $defval = null) {
    return isset($array[$key]) ? $array[$key] : $defval;
}
// Bunlara gerek var mi?
function get_get_value($key, $defval = null) {
    return get_array_value($_GET, $key, $defval);
}
function get_post_value($key, $defval = null) {
    return get_array_value($_POST, $key, $defval);
}

function get_uri($remove_query = true) {
    $uri = urldecode($_SERVER['REQUEST_URI']);
    if ($remove_query && ($pos = strpos($uri, '?')) !== false) {
        $uri = substr($uri, 0, $pos);
    }
    return $uri;
}

function get_image_extension($mime) {
    // array_search?
    foreach (cfg('media.image.mimeTypes') as $key => $val) {
        if ($key == $mime) {
            return $val;
        }
    }
}

function get_options($options) {
    if (is_string($options)) {
        $options = qry_parse($options);
    }
    return $options;
}

// Query string functions
function qry_parse($qry) {
    $result = array();
    if (!no($qry)) {
        $params = explode('&', $qry);
        foreach ($params as $param) {
            list($key, $val) = explode('=', $param, 2);
            switch ($val) {
                case is_numeric($val):
                    $val = intval($val);
                    break;
                case 'true':
                case 'false':
                    $val = ($val == 'true') ? true : false;
                    break;
            }
            $result[trim($key)] = $val;
        }
    }
    return $result;
}

// Checker functions
function is_user() {
    return isset($_SESSION['user']);
}
function is_admin() {
    return isset($_SESSION['user']) && ($_SESSION['user']['rank'] == SS_USER_RANK_ADMIN);
}
function is_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
function is_iterable($value) {
    return (is_array($value) || $value instanceof Traversable || $value instanceof stdClass);
}
function is_mobile_browser() {
    return ss_browser_checkMobile();
}



// @debug
function pre($s, $e = false) {
    printf('<pre>%s</pre>', print_r($s, true));
    if ($e) exit;
}
function prn($s, $e = false) {
    printf("%s\n", print_r($s, true));
    if ($e) exit;
}