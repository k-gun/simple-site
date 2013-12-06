<?php include(__DIR__.'/Boot.php'); ?>

<?php ss_admin_getHead(); ?>

<?php ss_admin_getAside(); ?>

<?php
$table = ss_mysql_table('menu');

// Simply insert action
if (isset($_POST['submit'])) {
    $sort        = ss_filter_postValue('sort', SS_FILTER_TYPE_INT);
    $position    = ss_filter_postValue('position');
    $link_text   = ss_filter_postValue('link_text');
    $link_href   = ss_filter_postValue('link_href');
    ss_mysql_insert($table, array(
        'sort' => $sort,
        'position' => $position,
        'link_text' => $link_text,
        'link_href' => $link_href,
    ));
    redirect('/ss-admin/menu-list');
}

// Get max sort value
$max_sort = 0;
$max_sort_get = ss_mysql_get("SELECT Max(sort) AS sort FROM $table LIMIT1");
if (isset($max_sort_get->sort)) {
    $max_sort = intval($max_sort_get->sort) + 1;
}
?>

<div class="ss-admin-page-content">

    <div class="ss-admin-subhead">
        <h3>Add new menu item</h3>
    </div>

    <form method="post" action="" class="ss-admin-form">
        <div>Text:<br> <input type="text" name="link_text" size="50"></div>
        <div>Link:<br> <input type="text" name="link_href" size="50"></div>
        <div>Sort:<br> <input type="text" name="sort" size="6" value="<?=$max_sort?>"></div>
        <div>Position:<br>
            <select name="position">
                <option value="head">head</option>
                <option value="foot">foot</option>
            </select></div>

        <div class="ss-admin-form-submit"><input type="submit" name="submit" value="Submit"></div>
    </form>

</div>

<?php ss_admin_getFoot(); ?>