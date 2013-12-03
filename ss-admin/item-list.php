<?php include(__DIR__.'/Boot.php'); ?>

<?php ss_admin_getHead(); ?>

<?php ss_admin_getAside(); ?>

<?php
$items = ss_admin_getItems();
$pager = ss_get('pager');
// pre($pager);
?>

<div class="ss-admin-page-content">

    <div class="ss-admin-subhead">

        <h3>Items</h3>

        <div class="floatr">
            <form action="" method="get">
                <input type="text" name="ssq" value="<?=ss_filter_getValue('ssq', true)?>">
                <input type="submit" value="Go!">
            </form>
        </div>
    </div>

    <?php if (!no($items)): ?>

    <table class="ss-admin-item-list">

        <tr>
            <th>&nbsp;</th>
            <th>Date</th>
            <th>Status</th>
            <th width="1">&nbsp;</th>
        </tr>

        <?php foreach($items as $item): ?>
        <tr>
            <td>
                <div class="ss-admin-item-list-title"><?=the_itemTitle($item)?></div>
                <div class="ss-admin-item-list-content"><?=the_itemContentSub($item, null, 'strip=1', 100)?></div>
            </td>
            <td><?=the_item_dateTime($item)?></td>
            <td><?=ucfirst($item->status)?></td>
            <td nowrap>
                &nbsp;
                <a href="<?=the_itemLink($item)?>">View</a> - <a href="<?=ss_admin_link('item-update?id=%d', $item->id)?>">Edit</a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

    <div class="ss-admin-pager">
        <div class="pager">
            <b>Page</b>: <?php print $pager->generate(); ?>
        </div>
    </div>

    <?php else: ?>

    <br>No content found.

    <?php endif; ?>

</div>

<?php ss_admin_getFoot(); ?>