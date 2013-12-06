<?php include(__DIR__.'/Boot.php'); ?>

<?php ss_admin_getHead(); ?>

<?php ss_admin_getAside(); ?>

<?php
if (isset($_POST['item'])) {
    $id = ss_admin_item_insert($_POST['item']);
    redirect('/ss-admin/item-update?id=%d&success=1&action=inserted', $id);
}
?>

<link href="<?=ss_admin_asset('editor.css')?>" rel="stylesheet">
<script src="<?=ss_admin_asset('editor.js')?>"></script>

<div class="ss-admin-page-content">

    <div class="ss-admin-subhead">
        <h3>Add new item</h3>
    </div>

    <form method="post" action="<?=get_uri()?>" class="ss-admin-item-form">
        <div><input type="text" name="item[title]" id="itemTitle" placeholder="Title" class="ss-admin-item-form-text"></div>
        <div class="ss-admin-editor-buttons fixed">
            <?php include('editor_buttons.php'); ?>
        </div>
        <div>
            <iframe src="about:blank" id="editor" name="editor" frameborder="0"></iframe>
            <textarea name="item[content]" id="itemContent" rows="15" class="ss-admin-item-form-text"></textarea>
        </div>
        <div><select name="item[status]">
                <?php print ss_html_selectOption(cfg('item.status'), SS_ITEM_STATUS_DRAFT, true); ?>
             </select>
             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
             <label>Allow comments: <input type="checkbox" name="item[allow_comment]" value="1" checked></label>
        </div>

        <div class="ss-admin-item-form-submit">
            <span class="floatr"></span>
            <input type="submit" name="submit" value="Submit">
        </div>

        <input type="hidden" name="item[draft_token]" value="<?=token(32)?>">
    </form>

</div>

<script src="<?=ss_admin_asset('editor-autosave.js')?>"></script>

<?php ss_admin_getFoot(); ?>