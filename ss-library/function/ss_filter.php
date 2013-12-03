<?php
// Defines
define('SS_FILTER_TYPE_INT',    'int');
define('SS_FILTER_TYPE_BOOL',   'bool');
define('SS_FILTER_TYPE_FLOAT',  'float');
define('SS_FILTER_TYPE_DOUBLE', 'double');
define('SS_FILTER_TYPE_STRING', 'string');

function ss_filter($input, $encode = false, $type = SS_FILTER_TYPE_STRING) {
    // Swap args (encode as type)
    if (!is_bool($encode)) {
        $type = $encode;
    }

    if (($input = trim($input)) !== '') {
        switch ($type) {
            case SS_FILTER_TYPE_INT:
                $input = (int) $input; break;
            case SS_FILTER_TYPE_FLOAT:
            case SS_FILTER_TYPE_DOUBLE:
                $input = sprintf('%F', $input); break;
            case SS_FILTER_TYPE_BOOL:
                $input = (bool) $input; break;
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

    // NULL
    return null;
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