<?php include(__DIR__.'/Boot.php'); ?>

<?php ss_admin_getHead(); ?>

<?php ss_admin_getAside(); ?>

<?php
if ($delete = ss_filter_getValue('delete', SS_FILTER_TYPE_INT)) {
    ss_admin_item_delete($delete);
    redirect('/ss-admin/item-list?status=%s', SS_ITEM_STATUS_DELETED);
}

if (($_get_id = get_get_value('id', SS_FILTER_TYPE_INT)) &&
    ($_get_status = ss_filter_getValue('set-status'))) {
    ss_admin_item_setStatus($_get_id, $_get_status);
}

$items = ss_admin_item_getAll();
$pager = ss_get('pager');
?>

<div class="ss-admin-page-content">

    <div class="ss-admin-subhead">

        <h3>Items <?php
            $_get_status = ss_filter_getValue('status');
            if ($_get_status) printf('(<span class="gray">%s</span>)', $_get_status);
        ?></h3>

        <div class="floatr">
            <form action="" method="get">
                <input type="hidden" name="status" value="all">
                <input type="text" name="ssq" value="<?=ss_filter_getValue('ssq', true)?>">
                <input type="submit" value="Go!">
            </form>
        </div>
    </div>

    <?php if (!no($items)): ?>

    <table class="ss-admin-item-list">

        <tr>
            <th width="300">&nbsp;</th>
            <th width="75">Date</th>
            <th width="1">Status</th>
            <th width="1">&nbsp;</th>
        </tr>

        <?php foreach($items as $item): ?>
        <tr>
            <td>
                <div class="ss-admin-item-list-title"><?=the_itemTitle($item)?></div>
                <div class="ss-admin-item-list-content"><?=the_itemContentSub($item, null, 'strip=1', 100)?></div>
            </td>
            <td><?=str_replace(' ', '<br>', the_item_dateTime($item))?></td>
            <td><select class="ss-admin-item-status" data-id="<?=$item->id?>">
                    <option value="">-change status-</option>
                    <?php print ss_html_selectOption(cfg('item.status'), $item->status, true); ?>
                </select></td>
            <td class="ss-admin-item-list-actions">
                <a href="<?=the_itemLink($item)?>">View</a> -
                <a href="<?=ss_admin_link('item-update?id=%d', $item->id)?>">Edit</a>
                <?php if ($item->status == SS_ITEM_STATUS_DELETED): ?>
                <br>
                <a href="<?=ss_admin_link('item-list?status=%s&delete=%d', SS_ITEM_STATUS_DELETED, $item->id)?>" onclick="return confirm('Delete the item permanently?')" class="red">Delete</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

    <?php else: ?>

    <br>No contents found.

    <?php endif; ?>

    <div class="ss-admin-pager">
        <div class="floatr">
            <b>Status</b>:&nbsp;
                <a href="<?=ss_admin_link('item-list')?>">None</a> &middot;
                <a href="<?=ss_admin_link('item-list?status=all')?>">All</a> &middot;
                <?php
                $status_links = array();
                foreach (cfg('item.status') as $status) {
                    $status_links[] = sprintf('<a href="/ss-admin/item-list?status=%s">%s</a>', $status, ucfirst($status));
                }
                print join(' &middot; ', $status_links);
                ?>
        </div>
        <?php if (ss_has('pager')): ?>
        <div class="pager">
            <b>Page</b>: <?php print $pager->generate(); ?>
        </div>
    <?php endif; ?>
    </div>

</div>

<script>
mii.onReady(function($){
    $.dom(".ss-admin-item-status").on("change", function(){
        var id = this.getAttribute("data-id");
        var value = this.value;
        var status = "<?=ss_filter_getValue('status')?>";
        if (value) {
           redirect("/ss-admin/item-list?status=%s&set-status=%s&id=%s", status, value, id);
        }
    })
});
</script>

<?php ss_admin_getFoot(); ?>