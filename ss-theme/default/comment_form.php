<?php
if (isset($_POST['comment'])) {
    $insert_id = ss_comment_insert($_POST['comment']);
    redirect('%s?success=%d#success', get_uri(), !empty($insert_id));
}

$comments = ss_comment_getAll($item->id);
?>

<?php if (!no($comments)): ?>
<ul>
    <?php foreach ($comments as $comment): ?>
    <li>
        <div class="ss-comment-meta">
            <b><?=$comment->author_name?></b> &middot; <abbr><?=the_comment_dateTime($comment)?></abbr>
        </div>
        <div class="ss-comment-content">
            <?=the_comment_content($comment)?></p>
        </div>
    </li>
<?php endforeach; ?>
</ul>
<?php else: ?>

    No comments yet.

<?php endif; ?>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<br>
<div class="success" id="succcess">
    Your comment added succesfully. After admin's approval, it will be displayed here.
</div>
<?php endif; ?>

<form method="post" action="<?=get_uri()?>" class="ss-comment-form">
    <input type="hidden" name="comment[item_id]" value="<?=$item->id?>">
    <div class="ss-comment-form-caption">Drop a line...</div>
    <p><input type="text" name="comment[author_name]" placeholder="Your name?"></p>
    <p><input type="text" name="comment[author_email]" placeholder="Your email? (will not be displayed)"></p>
    <p><textarea name="comment[content]" placeholder="Your comment..." rows="5"></textarea></p>
    <p class="small i">All comments will be approved by admin.</p>
    <p><input type="submit" name="submit" value="Submit"></p>
</form>
