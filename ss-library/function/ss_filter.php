<?php
// Defines
define('SS_FILTER_TYPE_INT',          'int');
define('SS_FILTER_TYPE_INTEGER',      'integer');
define('SS_FILTER_TYPE_BOOL',         'bool');
define('SS_FILTER_TYPE_FLOAT',        'float');
define('SS_FILTER_TYPE_DOUBLE',       'double');
define('SS_FILTER_TYPE_STRING',       'string');
define('SS_FILTER_TYPE_NUM',          'num');
define('SS_FILTER_TYPE_NUMERIC',      'numeric');
define('SS_FILTER_TYPE_HEX',          'hex');
define('SS_FILTER_TYPE_HEXADECIMAL',  'hexadecimal');

function ss_filter($input, $encode = false, $type = SS_FILTER_TYPE_STRING) {
    // Trim first
    $input = trim($input);

    $nullable = false;
    // Swap args (type as nullable)
    if (is_bool($type)) {
        $nullable = $type;
    }
    // Swap args (encode as type, SS_FILTER_*)
    if (!is_bool($encode)) {
        $type = $encode;
    }

    // Return NULL
    if ($nullable && $input === '') {
        return null;
    }

    // Return by type
    switch ($type) {
        case SS_FILTER_TYPE_INT:
        case SS_FILTER_TYPE_INTEGER:
            $input = (int) $input;
            break;
        case SS_FILTER_TYPE_FLOAT:
        case SS_FILTER_TYPE_DOUBLE:
            $input = sprintf('%F', $input);
            break;
        case SS_FILTER_TYPE_BOOL:
            $input = (bool) $input;
            break;
        case SS_FILTER_TYPE_NUM:
        case SS_FILTER_TYPE_NUMERIC:
            $input = preg_replace('~[^0-9]~', '', $input);
            break;
        case SS_FILTER_TYPE_HEX:
        case SS_FILTER_TYPE_HEXADECIMAL:
            $input = preg_replace('~[^0-9A-F]~i', '', $input);
            break;
        case SS_FILTER_TYPE_STRING:
        default:
            $input = str_ireplace(array("\0", '%00', "\x1a", '%1a'), '', $input);
            if ($encode) {
                $input = ss_filter_htmlEncode($input);
            }
            break;
    }

    return $input;
}

function ss_filter_arrayValue($array, $key, $encode = false, $type = SS_FILTER_TYPE_STRING) {
    $input =@ $array[$key];
    return ss_filter($input, $encode, $type);
}

function ss_filter_getValue($key, $encode = false, $type = SS_FILTER_TYPE_STRING) {
    return ss_filter_arrayValue($_GET, $key, $encode, $type);
}

function ss_filter_postValue($key, $encode = false, $type = SS_FILTER_TYPE_STRING) {
    return ss_filter_arrayValue($_POST, $key, $encode, $type);
}

function ss_filter_htmlEncode($input) {
    static $r1, $r2;
    if ($r1 === null) {
        $r1 = array('\''   , '"'    , '\\'   , '<'   , '>'   );
        $r2 = array('&#39;', '&#34;', '&#92;', '&lt;', '&gt;');
    }
    return str_ireplace($r1, $r2, $input);
}

function ss_filter_htmlDecode($input) {
    static $r1, $r2;
    if ($r1 === null) {
        $r1 = array('&#39;', '&#34;', '&#92;', '&lt;', '&gt;', '&#039;', '&#034;', '&#092;', '&amp;');
        $r2 = array('\''   , '"'    , '\\'   , '<'   , '>'   , '\''    , '"'     , '\\'    , '&'    );
    }
    return str_ireplace($r1, $r2, $input);
}