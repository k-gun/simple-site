<?php
function ss_html_checked($a, $b, $strict = false) {
    if (isset($a) && isset($b)) {
        $true = $strict ? ($a === $b) : ($a == $b);
        if ($true) {
            return ' checked';
        }
    }
}

function ss_html_selected($a, $b, $strict = false) {
    if (isset($a) && isset($b)) {
        $true = $strict ? ($a === $b) : ($a == $b);
        if ($true) {
            return ' selected';
        }
    }
}

function ss_html_selectOption(Array $data, $cur = null, $merge = false, Callable $fn = null) {
    if (is_callable($fn)) {
        $data = $fn($data);
    }

    if ($merge) {
        $_data = array();
        foreach ($data as $d) {
            $_data[$d] = $d;
        }
        $data =& $_data;
        unset($_data);
    }

    $options = '';
    foreach ($data as $key => $val) {
        $selected = ss_html_selected($key, $cur);
        $options .= "<option value=\"$key\"$selected>$val</option>";
    }
    return $options;
}