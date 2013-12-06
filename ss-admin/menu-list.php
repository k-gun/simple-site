<?php include(__DIR__.'/Boot.php'); ?>

<?php ss_admin_getHead(); ?>

<?php ss_admin_getAside(); ?>

<?php
$table = ss_mysql_table('menu');

if ($delete = ss_filter_getValue('delete', SS_FILTER_TYPE_INT)) {
    ss_mysql_delete($table, 'id = %d', $delete);
    redirect('/ss-admin/menu-list');
}

// $count = ss_mysql_count($table);
// $pager = new Pager($count);
// $menus = ss_mysql_getAll("SELECT * FROM $table ORDER BY sort ASC LIMIT %d,%d", array($pager->start, $pager->stop));
$menus = ss_mysql_getAll("SELECT * FROM $table ORDER BY sort ASC");
?>

<div class="ss-admin-page-content">

    <div class="ss-admin-subhead">
        <h3>Menus</h3>
    </div>

    <?php if (!no($menus)): ?>

    <table class="ss-admin-list">

        <tr>
            <th>Text</th>
            <th>Link</th>
            <th>Sort</th>
            <th>Position</th>
            <th width="1">&nbsp;</th>
        </tr>

        <?php foreach($menus as $menu): ?>
        <tr>
            <td><?=$menu->link_text?></td>
            <td><?=$menu->link_href?></td>
            <td><?=$menu->sort?></td>
            <td><?=$menu->position?></td>
            <td nowrap>
                &nbsp;
                <a href="<?=$menu->link_href?>">View</a> -
                <a href="<?=ss_admin_link('menu-update?id=%d', $menu->id)?>">Edit</a> -
                <a href="?delete=<?=$menu->id?>" onclick="return confirm('Delete?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

    <?php else: ?>

    <br>No content found.

    <?php endif; ?>

</div>

<?php ss_admin_getFoot(); ?>