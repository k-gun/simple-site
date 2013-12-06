<?php include(__DIR__.'/Boot.php'); ?>

<?php ss_admin_getHead(); ?>

<?php ss_admin_getAside(); ?>

<?php
// Delete comment
if ($_get_delete = ss_filter_getValue('delete', SS_FILTER_TYPE_INT)) {
    ss_admin_comment_delete($_get_delete);
    redirect('/ss-admin/comment-list');
}
// Set comment status
if (($_get_id = ss_filter_getValue('id', SS_FILTER_TYPE_INT)) &&
    ($_get_status = ss_filter_getValue('set-status'))) {
    ss_admin_comment_setStatus($_get_id, $_get_status);
}
// Get comments
$comments = ss_admin_comment_getAll();

// Pager
$pager = ss_get('pager');
?>

<div class="ss-admin-page-content">

    <div class="ss-admin-subhead">

        <h3>Comments <?php
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
            <td><select class="ss-admin-list-status" data-id="<?=$comment->id?>">
                    <option value="">-change status-</option>
                    <option value="<?=SS_COMMENT_STATUS_WAITING?>"<?=ss_html_selected($comment->status, SS_COMMENT_STATUS_WAITING)?>><?=SS_COMMENT_STATUS_WAITING?></option>
                    <option value="<?=SS_COMMENT_STATUS_PUBLISHED?>"<?=ss_html_selected($comment->status, SS_COMMENT_STATUS_PUBLISHED)?>><?=SS_COMMENT_STATUS_PUBLISHED?></option>
                </select>
            </td>
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

    <div class="ss-admin-pager">
        <div class="floatr">
            <b>Status</b>:&nbsp;
                <a href="<?=ss_admin_link('comment-list?status=all')?>">All</a> &middot;
                <a href="<?=ss_admin_link('comment-list')?>">None</a> &middot;
                <a href="<?=ss_admin_link('comment-list?status=%s', SS_COMMENT_STATUS_WAITING)?>"><?=ucfirst(SS_COMMENT_STATUS_WAITING)?></a> &middot;
                <a href="<?=ss_admin_link('comment-list?status=%s', SS_COMMENT_STATUS_PUBLISHED)?>"><?=ucfirst(SS_COMMENT_STATUS_PUBLISHED)?></a>
        </div>
        <?php if (ss_has('pager')): ?>
        <div class="pager">
            <b>Page</b>: <?php print $pager->generate(); ?>
        </div>
    <?php endif; ?>
    </div>

</div><!-- .ss-admin-page-content -->

<script>
mii.onReady(function($){
    $.dom(".ss-admin-list-status").on("change", function(){
        var id = this.getAttribute("data-id");
        var value = this.value;
        var status = "<?=ss_filter_getValue('status')?>";
        if (value) {
           redirect("/ss-admin/comment-list?status=%s&set-status=%s&id=%s", status, value, id);
        }
    })
});
</script>

<?php ss_admin_getFoot(); ?>