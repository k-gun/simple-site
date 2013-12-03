<?php include(__DIR__.'/Boot.php'); ?>

<?php ss_admin_getHead(); ?>

<?php ss_admin_getAside(); ?>

<?php
if (isset($_POST['submit'])) {
    ss_admin_updateItem();
}

$id = ss_filter_getValue('id', SS_FILTER_TYPE_INT);
$item = ss_admin_getItem($id);
?>

<div class="ss-admin-page-content">

    <div class="ss-admin-subhead">
        <h3>Edit item</h3>
    </div>

    <?php if (!no($item)): ?>

    <form method="post" action="" class="ss-admin-item-update">
        <input type="hidden" name="item_id" value="<?=$item->id?>">
        <div><input type="text" name="item_title" value="<?=$item->title?>" class="ss-admin-item-update-text"></div>
        <div><textarea name="item_content" rows="15" class="ss-admin-item-update-text"><?=$item->content?></textarea></div>
        <div><input type="submit" name="submit" value="Submit"></div>
    </form>

    <?php else: ?>

    <br>Item not found!

    <?php endif; ?>

</div>

<?php ss_admin_getFoot(); ?>