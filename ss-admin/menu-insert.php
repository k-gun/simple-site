<?php include(__DIR__.'/Boot.php'); ?>

<?php ss_admin_getHead(); ?>

<?php ss_admin_getAside(); ?>

<?php
$table = ss_mysql_table('menu');

if (isset($_POST['submit'])) {
    $link_text = ss_filter_postValue('link_text');
    $link_href = ss_filter_postValue('link_href');
    $sort = ss_filter_postValue('sort', SS_FILTER_TYPE_INT);
    $position = ss_filter_postValue('position');
    ss_mysql_insert($table, array(
        'link_text' => $link_text,
        'link_href' => $link_href,
        'sort' => $sort,
        'position' => $position,
    ));
    redirect('/ss-admin/menu-list');
}
?>

<div class="ss-admin-page-content">

    <div class="ss-admin-subhead">
        <h3>Add new menu item</h3>
    </div>

    <form method="post" action="" class="ss-admin-item-form">
        <div>Text:<br> <input type="text" name="link_text" size="50"></div>
        <div>Link:<br> <input type="text" name="link_href" size="50"></div>
        <div>Sort:<br> <input type="text" name="sort" size="5"></div>
        <div>Position:<br>
            <select name="position">
                <option value="head">head</option>
                <option value="foot">foot</option>
            </select></div>

        <div class="ss-admin-item-form-submit"><input type="submit" name="submit" value="Submit"></div>
    </form>

</div>

<?php ss_admin_getFoot(); ?>