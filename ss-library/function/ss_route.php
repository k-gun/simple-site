<?php
function ss_route() {
    if (empty($GLOBALS['ss']['route'])
        // && isset($_SERVER['REDIRECT_STATUS'])
    ) {
        $uri = get_uri();
        $params = array();
        foreach (cfg('routes') as $name => $route) {
            foreach ($route['patterns'] as $pattern) {
                $test = preg_match($pattern, $uri, $matches);
                if ($test && !empty($matches)) {
                    $params[':name']   = $name;
                    $params[':file']   = sprintf('%s/%s/%s', SS_ROOT_THEME, cfg('site.theme'), $route['file']);
                    $params[':uri']    = get_uri(false);
                    $params[':query']  = $_SERVER['QUERY_STRING'];
                    parse_str($_SERVER['QUERY_STRING'], $params[':query_params']);
                    // Filter params
                    foreach ($matches as $key => $val) {
                        if (is_string($key)) {
                            $params[$key] = $val;
                        }
                    }
                    // Break parent loop
                    break 2;
                }
            }
        }
        $GLOBALS['ss']['route'] = $params;
    }
    return $GLOBALS['ss']['route'];
}

function ss_routeParam($key, $defval = null) {
    return isset($GLOBALS['ss']['route'][$key])
        ? $GLOBALS['ss']['route'][$key] : $defval;
}
