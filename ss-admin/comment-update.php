<?php include(__DIR__.'/Boot.php'); ?>

<?php ss_admin_getHead(); ?>

<?php ss_admin_getAside(); ?>

<?php
$id = ss_filter_getValue('id', SS_FILTER_TYPE_INT);
// Update comment
if (isset($_POST['comment'])) {
    ss_admin_comment_update($id, $_POST['comment']);
    redirect('/ss-admin/comment-update?id=%d&success=1', $id);
}
// Get comment
$comment = ss_admin_comment_get($id);
if (no($comment)) redirect('/ss-admin/comment-list');
?>

<div class="ss-admin-page-content">

<?php if (isset($_GET['success'])): ?>
<div class="success">Comment has been updated successfully.</div>
<script>
setTimeout(function(){
    var success = mii.dom(".success");
    success.animate({marginTop:-30, opacity:0}, 350, function(){
        success.remove();
    });
}, 5000);
</script>
<?php endif; ?>

    <div class="ss-admin-subhead">
        <h3>Edit comment</h3>
    </div>

    <form method="post" action="<?=get_uri(!1)?>" class="ss-admin-form">
        <div><input type="text" name="comment[author_name]" size="40" value="<?=$comment->author_name?>"></div>
        <div><input type="text" name="comment[author_email]" size="40" value="<?=$comment->author_email?>"></div>
        <div><textarea name="comment[content]" rows="15" class="ss-admin-form-text"><?=$comment->content?></textarea></div>
        <div><select name="comment[status]">
                <option value="">-change status-</option>
                <option value="<?=SS_COMMENT_STATUS_WAITING?>"<?=ss_html_selected($comment->status, SS_COMMENT_STATUS_WAITING)?>><?=SS_COMMENT_STATUS_WAITING?></option>
                <option value="<?=SS_COMMENT_STATUS_PUBLISHED?>"<?=ss_html_selected($comment->status, SS_COMMENT_STATUS_PUBLISHED)?>><?=SS_COMMENT_STATUS_PUBLISHED?></option>
            </select>
        </div>

        <div class="ss-admin-form-submit">
            <input type="submit" name="submit" value="Submit">
            &nbsp; | &nbsp;
            <a href="<?=ss_admin_link('comment-list?delete=%d', $comment->id)?>" class="red" confirm="#Delete comment?">Delete</a>
        </div>
    </form>

</div>

<?php ss_admin_getFoot(); ?>