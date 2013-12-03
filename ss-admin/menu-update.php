<?php include(__DIR__.'/Boot.php'); ?>

<?php ss_admin_getHead(); ?>

<?php ss_admin_getAside(); ?>

<?php
$id = ss_filter_getValue('id', SS_FILTER_TYPE_INT);
$table = ss_mysql_table('menu');

if ($id && isset($_POST['submit'])) {
    $link_text = ss_filter_postValue('link_text');
    $link_href = ss_filter_postValue('link_href');
    $sort = ss_filter_postValue('sort', SS_FILTER_TYPE_INT);
    $position = ss_filter_postValue('position');
    ss_mysql_update($table, array(
        'link_text' => $link_text,
        'link_href' => $link_href,
        'sort' => $sort,
        'position' => $position,
    ), 'id = %d', $id, 1);
}

$menu = ss_mysql_get("SELECT * FROM $table WHERE id = %d", $id);
// pre($menu,1);
?>

<div class="ss-admin-page-content">

    <div class="ss-admin-subhead">
        <h3>Edit menu</h3>
    </div>

    <?php if (!no($menu)): ?>

    <form method="post" action="" class="ss-admin-item-update">
        <input type="hidden" name="id" value="<?=$menu->id?>">
        <div>Text:<br> <input type="text" name="link_text" value="<?=$menu->link_text?>" size="50"></div>
        <div>Link:<br> <input type="text" name="link_href" value="<?=$menu->link_href?>" size="50"></div>
        <div>Sort:<br> <input type="text" name="sort" value="<?=$menu->sort?>" size="5"></div>
        <div>Position:<br>
            <select name="position">
                <option value="head"<?=ss_html_selected($menu->position, 'head')?>>head</option>
                <option value="foot"<?=ss_html_selected($menu->position, 'foot')?>>foot</option>
            </select></div>
        <div><input type="submit" name="submit" value="Submit"></div>
    </form>

    <?php else: ?>

    <br>Menu not found!

    <?php endif; ?>

</div>

<?php ss_admin_getFoot(); ?>