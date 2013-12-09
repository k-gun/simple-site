<?php
// Defines
define('SS_USER_RANK_ADMIN',   'admin');
define('SS_USER_RANK_EDITOR',  'editor');
define('SS_USER_RANK_USER',    'user');

function ss_user_login() {
    $username = ss_filter_postValue('ss-username');
    $password = ss_filter_postValue('ss-password');
    if ($username && $password) {
        $table = ss_mysql_table('user');
        $user  = ss_mysql_get("SELECT * FROM $table WHERE username = %s", array($username));
        if ($user) {
            $sspass = new SSPass();
            if ($sspass->validate($password, $user->password)) {
                // Remove password
                unset($user->password);
                // Register user to session
                $_SESSION['user'] = (array) $user;
                // Update last login
                ss_mysql_update($table, array(
                    'log_date' => ss_mysql_sql('Unix_Timestamp()')
                ), 'id = %d', $user->id);
                // Send to wherever
                redirect();
            }
        }
    }
}

function ss_user_logout() {
    $_SESSION = array();
    session_destroy();
}