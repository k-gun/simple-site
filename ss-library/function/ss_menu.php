<?php
// Defines
define('SS_MENU_POSITION_HEAD', 'head');
define('SS_MENU_POSITION_FOOT', 'foot');

function ss_menu_getLinks($opts = null) {
    $opts = get_options($opts);

    $where = '';
    if ($opt_position = get_array_value($opts, 'position')) {
        if ($opt_position == SS_MENU_POSITION_HEAD) {
            $where = ss_mysql_prepare("AND position = %s", SS_MENU_POSITION_HEAD);
        } elseif ($opt_position == SS_MENU_POSITION_FOOT) {
            $where = ss_mysql_prepare("AND position = %s", SS_MENU_POSITION_FOOT);
        }
    }

    $limit = '';
    if ($opt_limit = get_array_value($opts, 'limit')) {
        $limit = ss_mysql_prepare("LIMIT %d", $opt_limit);
    }

    $table = ss_mysql_table('menu');
    $links = ss_mysql_getAll("SELECT * FROM $table WHERE 1=1 $where ORDER BY sort ASC $limit");

    return $links;
}
