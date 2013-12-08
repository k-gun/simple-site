<?php include(__DIR__.'/Boot.php'); ?>

<?php ss_admin_getHead(); ?>

<?php ss_admin_getAside(); ?>

<div class="ss-admin-page-content">

    <?php // Get items
    $items = ss_admin_item_getAll();
    ?>

    <div class="ss-admin-page-title"><h3>Items</h3></div>

    <?php if (!no($items)): ?>

    <table class="ss-admin-list">

        <tr>
            <th width="300">&nbsp;</th>
            <th width="75">Date</th>
            <th width="1">Status</th>
            <th width="1">&nbsp;</th>
        </tr>

        <?php foreach($items as $item): ?>
        <tr>
            <td>
                <div class="ss-admin-list-title"><?=the_item_title($item)?></div>
                <div class="ss-admin-list-content"><?=the_item_contentSub($item, null, 'strip=1', 100)?></div>
            </td>
            <td class="ss-admin-list-datetime"><?=str_replace(' ', '<br>', the_item_dateTime($item))?></td>
            <td><?=$item->status?></td>
            <td class="ss-admin-list-actions">
                <a href="<?=the_item_link($item)?>">View</a> -
                <a href="<?=ss_admin_link('item-update?id=%d', $item->id)?>">Edit</a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

    <?php else: ?>

    <br>No items found.

    <?php endif; ?>

    <br><br>

    <?php // Get comments
    $comments = ss_admin_comment_getAll();
    ?>

    <div class="ss-admin-page-title"><h3>Comments</h3></div>

    <?php if (!no($comments)): ?>

    <table class="ss-admin-list">

        <tr>
            <th width="300">Content</th>
            <th width="75">Date</th>
            <th width="1">Status</th>
            <th width="1">&nbsp;</th>
        </tr>

        <?php foreach($comments as $comment): ?>
        <tr>
            <td>
                <div class="b"><a href="<?=ss_admin_link('item-update?id=%d', $comment->item_id)?>"><?=$comment->item_title?></a></div>
                <?=the_comment_contentSub($comment, 100)?> ...
            </td>
            <td class="ss-admin-list-datetime"><?=str_replace(' ', '<br>', the_item_dateTime($comment))?></td>
            <td><?=$comment->status?></td>
            <td class="ss-admin-list-actions">
                <a href="<?=ss_admin_link('comment-update?id=%d', $comment->id)?>">View / Edit</a> -
                <a href="<?=ss_admin_link('comment-list?delete=%d', $comment->id)?>" class="red" confirm="#Delete comment?">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

    <?php else: ?>

    <br>No contents found.

    <?php endif; ?>

</div>

<?php ss_admin_getFoot(); ?>