<?php include(__DIR__.'/Boot.php'); ?>

<?php ss_admin_getHead(); ?>

<?php ss_admin_getAside(); ?>

<?php
if (isset($_POST['submit'])) {
    ss_admin_insertItem();
}
?>

<div class="ss-admin-page-content">

    <div class="ss-admin-subhead">
        <h3>Add new item</h3>
    </div>

    <form method="post" action="<?=get_uri()?>" class="ss-admin-item-update">
        <div><input type="text" name="item_title" placeholder="Title" class="ss-admin-item-update-text"></div>
        <div class="ss-admin-editor fixed">
            <i class="fa fa-bold" role="button" exec="format" onclick="_x('bold')" title="Bold"></i>
            <i class="fa fa-italic" role="button" exec="format" onclick="_x('italic')" title="Italic"></i>
            <i class="fa fa-underline" role="button" exec="format" onclick="_x('underline')" title="Underline"></i>
            <i class="fa fa-strikethrough" role="button" exec="format" onclick="_x('strikethrough')" title="Strike through"></i>
            <i class="fa fa-eraser" onclick="_x('removeformat')" title="Remove format(s)"></i>
            &nbsp;
            <span>
                <i class="fa fa-list-ol" role="button" exec="list" onclick="_x('insertorderedlist')" title="Insert ordered list"></i>
                <i class="fa fa-list-ul" role="button" exec="list" onclick="_x('insertunorderedlist')" title="Insert unordered list"></i>
            </span>
            &nbsp;
            <span>
                <i class="fa fa-align-justify" role="button" exec="justify" onclick="_x('justifyfull')" title="Justify full"></i>
                <i class="fa fa-align-left" role="button" exec="justify" onclick="_x('justifyleft')" title="Justify left"></i>
                <i class="fa fa-align-center" role="button" exec="justify" onclick="_x('justifycenter')" title="Justify center"></i>
                <i class="fa fa-align-right" role="button" exec="justify" onclick="_x('justifyright')" title="Justify right"></i>
            </span>
            &nbsp;
            <i class="fa fa-picture-o" onclick="" title="Insert image"></i>
        </div>
        <div>
            <iframe src="about:blank" id="editor" name="editor" frameborder="0"></iframe>
            <textarea name="item_content" id="item_content" rows="15" placeholder="Content" class="ss-admin-item-update-text"></textarea>
        </div>
        <div><input type="submit" name="submit" value="Submit"></div>
    </form>

</div>

<link href="<?=ss_admin_asset('editor.css')?>" rel="stylesheet">
<script src="<?=ss_admin_asset('editor.js')?>"></script>

<?php ss_admin_getFoot(); ?>