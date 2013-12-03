<?php
function ss_mysql_load() {
    $cfg = cfg('db.mysql');
    // Defines: Used in ORMLike
    define('DB_HOST',         $cfg['host']);
    define('DB_NAME',         $cfg['name']);
    define('DB_USER',         $cfg['user']);
    define('DB_PASS',         $cfg['pass']);
    define('DB_CHARSET',      $cfg['charset']);
    define('DB_TIMEZONE',     $cfg['timezone']);
    define('DB_TABLE_PREFIX', $cfg['table_prefix']);

    load_class('ORMLike/ORMLikeHelper');
    load_class('ORMLike/ORMLikeException');
    load_class('ORMLike/ORMLikeSql');
    load_class('ORMLike/ORMLikeDatabaseAbstract');
    load_class('ORMLike/ORMLikeDatabase');
    load_class('ORMLike/ORMLikeEntity');
    load_class('ORMLike/ORMLike');
}

// Load MySQL files
ss_mysql_load();

function ss_mysql_init() {
    $db = ss_get('db.mysql');
    if (!$db) {
        $db = ORMLikeDatabase::init();
        ss_set('db.mysql', $db);
    }

    // Reset error
    ss_set('db.mysql.error', null);

    return $db;
}

// For db expr's...
function ss_mysql_sql($sql) {
    $db = ss_mysql_init();
    return $db->sql($sql);
}

function ss_mysql_prepare($sql, $params = array()) {
    $db = ss_mysql_init();
    try {
        return $db->prepare($sql, $params);
    } catch (ORMLikeException $e) {
        ss_mysql_setFail($e->getMessage());
        return false;
    }
}

function ss_mysql_escape($input, $type = null) {
    $db = ss_mysql_init();
    return $db->escape($input, $type);
}

function ss_mysql_escapeIdentifier($input) {
    $db = ss_mysql_init();
    return $db->escapeIdentifier($input);
}

function ss_mysql_query($query, $params = array()) {
    $db = ss_mysql_init();
    try {
        return $db->query($query, $params);
    } catch (ORMLikeException $e) {
        ss_mysql_setFail($e->getMessage());
        return false;
    }
}

function ss_mysql_get($query, $params = array(), $fetchType = ORMLikeDatabase::FETCH_OBJECT, $fetchClass = null) {
    $db = ss_mysql_init();
    $rs = null;
    try {
        return $db->get($query, $params, $fetchType, $fetchClass);
    } catch (ORMLikeException $e) {
        ss_mysql_setFail($e->getMessage());
        return false;
    }
}

function ss_mysql_getAll($query, $params = array(), $fetchType = ORMLikeDatabase::FETCH_OBJECT, $fetchClass = null) {
    $db = ss_mysql_init();
    try {
        return $db->getAll($query, $params, $fetchType, $fetchClass);
    } catch (ORMLikeException $e) {
        ss_mysql_setFail($e->getMessage());
        return false;
    }
}

function ss_mysql_select($table, $fields, $where = '1=1', $params = array(), $limit = null) {
    $fields = implode(', ', ss_mysql_escapeIdentifier(explode(',', $fields)));
    $limit  = $limit ? ('LIMIT '. $limit) : '';
    $query  = sprintf(
        'SELECT %s FROM %s WHERE %s %s', $fields, ss_mysql_escapeIdentifier($table), ss_mysql_prepare($where, $params), $limit);
    return ss_mysql_get($query);
}

function ss_mysql_insert($table, Array $data = array()) {
    $db = ss_mysql_init();
    try {
        return $db->insert($table, $data);
    } catch (ORMLikeException $e) {
        ss_mysql_setFail($e->getMessage());
        return false;
    }
}

function ss_mysql_update($table, Array $data = array(), $where = '1=1', $params = array(), $limit = null) {
    $db = ss_mysql_init();
    try {
        return $db->update($table, $data, $where, $params, $limit);
    } catch (ORMLikeException $e) {
        ss_mysql_setFail($e->getMessage());
        return false;
    }
}

function ss_mysql_delete($table, $where = '1=1', $params = array(), $limit = null) {
    $db = ss_mysql_init();
    try {
        return $db->delete($table, $where, $params, $limit);
    } catch (ORMLikeException $e) {
        ss_mysql_setFail($e->getMessage());
        return false;
    }
}

function ss_mysql_count($table, $where = null) {
    try {
        $get = ss_mysql_get("SELECT COUNT(*) c FROM $table ". ($where ? "WHERE $where" : ""));
        return intval($get->c);
    } catch (ORMLikeException $e) {
        ss_mysql_setFail($e->getMessage());
        return null;
    }
}

function ss_mysql_insertId()     { $db = ss_mysql_init(); return $db->insertId; }
function ss_mysql_rowsCount()    { $db = ss_mysql_init(); return $db->numRows; }
function ss_mysql_rowsAffected() { $db = ss_mysql_init(); return $db->affectedRows; }

function ss_mysql_setFail($error) {
    if (SS_LOCAL) {
        print $error;
    }
    ss_set('db.mysql.error', trim($error));
}

function ss_mysql_getFail() {
    return ss_get('db.mysql.error');
}

function ss_mysql_fail($get = false) {
    $error = db_get_error();
    return $get ? $error : !empty($error);
}

function ss_mysql_table($table) {
    return DB_TABLE_PREFIX . trim($table);
}