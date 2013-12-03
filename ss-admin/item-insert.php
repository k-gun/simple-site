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
            <span class="floatr">
                <i class="fa fa-eraser" onclick="_x('removeformat')" title="Remove format(s)"></i>
            </span>
            <i class="fa fa-bold" role="button" exec="format" onclick="_x('bold')"></i>
            <i class="fa fa-italic" role="button" exec="format" onclick="_x('italic')"></i>
            <i class="fa fa-underline" role="button" exec="format" onclick="_x('underline')"></i>
            <i class="fa fa-strikethrough" role="button" exec="format" onclick="_x('strikethrough')"></i>
            <i class="fa fa-list-ol" role="button" onclick="_x('insertorderedlist')"></i>
            <i class="fa fa-list-ul" role="button" onclick="_x('insertunorderedlist')"></i>
            &nbsp;
            <span>
                <i class="fa fa-align-justify" role="button" exec="justify" onclick="_x('justifyfull')"></i>
                <i class="fa fa-align-left" role="button" exec="justify" onclick="_x('justifyleft')"></i>
                <i class="fa fa-align-center" role="button" exec="justify" onclick="_x('justifycenter')"></i>
                <i class="fa fa-align-right" role="button" exec="justify" onclick="_x('justifyright')"></i>
            </span>
            &nbsp;
            <i class="fa fa-picture-o" onclick=""></i>
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