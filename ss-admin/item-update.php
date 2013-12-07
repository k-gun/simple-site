<?php include(__DIR__.'/Boot.php'); ?>

<?php ss_admin_getHead(); ?>

<?php ss_admin_getAside(); ?>

<?php
$id = ss_filter_getValue('id', SS_FILTER_TYPE_INT);
// Update item
if (isset($_POST['item'])) {
    ss_admin_item_update($id, $_POST['item']);
    redirect('/ss-admin/item-update?id=%d&success=1&action=updated', $id);
}
// Get item
$item = ss_admin_item_get($id);
if (no($item)) redirect('/ss-admin/item-list');
?>

<link href="<?=ss_admin_asset('editor.css')?>" rel="stylesheet">
<script src="<?=ss_admin_asset('editor.js')?>"></script>

<div class="ss-admin-page-content">

<?php if (isset($_GET['success'])): ?>
<div class="success">Item has been <?=ss_filter_getValue('action', true)?> successfully.</div>
<script>
setTimeout(function(){
    var success = mii.dom(".success");
    success.animate({marginTop:-30, opacity:0}, 350, function(){
        success.remove();
    });
}, 5000);
</script>
<?php endif; ?>

    <div class="ss-admin-page-title">
        <a href="<?=the_item_link($item)?>" class="floatr" target="_blank">View on site</a>
        <h3>Edit item</h3>
    </div>

    <form method="post" action="<?=get_uri(!1)?>" class="ss-admin-form">
        <div><input type="text" name="item[title]" id="itemTitle" placeholder="Title" class="ss-admin-form-text" value="<?=ss_filter($item->title)?>"></div>
        <div class="ss-admin-editor-buttons fixed">
            <?php include('editor_buttons.php'); ?>
        </div>
        <div>
            <iframe src="about:blank" id="editor" name="editor" frameborder="0"></iframe>
            <textarea name="item[content]" id="itemContent" rows="15" class="ss-admin-form-text"><?=$item->content?></textarea>
        </div>
        <div><select name="item[status]">
                <?php print ss_html_selectOption(cfg('item.status'), $item->status, true); ?>
             </select>
             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
             <label>Allow comments: <input type="checkbox" name="item[allow_comment]" value="1"<?=ss_html_checked($item->allow_comment,1)?>></label>
        </div>

        <div class="ss-admin-form-submit">
            <span class="floatr"></span>
            <input type="submit" name="submit" value="Submit">
        </div>

        <input type="hidden" name="item[draft_token]" value="<?=$item->draft_token?>">
    </form>

</div>

<script>
mii.onReady(function($){
    __ss.editor.setDocumentBodyContent($.dom("#itemContent").getValue());
});
</script>

<script src="<?=ss_admin_asset('editor-autosave.js')?>"></script>

<?php ss_admin_getFoot(); ?>