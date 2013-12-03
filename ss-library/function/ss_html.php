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

function ss_html_selectOption(Array $data, $cur = null, Callable $fn = null) {
    if (is_callable($fn)) {
        $data = $fn($data);
    }
    $options = '';
    foreach ($data as $key => $val) {
        $selected = ss_html_selected($key, $cur);
        $options .= "<option value=\"$key\"$selected>$val</option>";
    }
    return $options;
}