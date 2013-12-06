<?php include(__DIR__.'/Boot.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Simple Site | Admin</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?=ss_admin_asset('font-awesome/css/font-awesome.min.css')?>">
    <link rel="stylesheet" href="<?=ss_admin_asset('reset.css')?>">
    <link rel="stylesheet" href="<?=ss_admin_asset('admin.css')?>">
    <script src="<?=ss_admin_asset('mii/mii-all.php')?>"></script>
    <script src="<?=ss_admin_asset('admin.js')?>"></script>
</head>
<body>

<form method="post" action="" enctype="multipart/form-data" class="file-form fixed">
    <span class="file-span">
        <a href="#">Select a file</a>
        <input type="file" name="file" id="file">
    </span>
    <span class="loading-span">
        <div>No file selected.</div>
    </span>
</form>

<?php
if (!empty($_FILES) && !empty($_FILES['file'])) {
    $data = ss_media_image_upload($_FILES['file']);
    if (!empty($data)) {
        ss_media_image_insert($data);
    }
}

$images = ss_media_image_getAll();
// pre($images);
?>

<div class="images">
    <h6 class="">Images</h6>

<?php if (!no($images)): ?>
    <ul>
        <?php foreach ($images as $image): ?>
        <li class="fixed">
            <div class="floatr">
                <button class="image-insert-button" data-id="<?=$image->id?>">insert</button>
            </div>
            <img src="<?=$image->crop['src']?>" width="92" height="92" class="image-crop">
            <div class="image-meta">
                <div><input type="checkbox" data-id="<?=$image->id?>" data-src="<?=$image->crop['src']?>"> <a href="<?=$image->crop['src']?>" target="_blank"><?=ss_admin_hiliteImageSizes($image->crop['src'])?></a> (crop)</div>
                <div><input type="checkbox" data-id="<?=$image->id?>" data-src="<?=$image->orig['src']?>"> <a href="<?=$image->orig['src']?>" target="_blank"><?=ss_admin_hiliteImageSizes($image->orig['src'])?></a> (original)</div>
                <?php foreach ($image->data->dims as $dim): ?>
                <div><input type="checkbox" data-id="<?=$image->id?>" data-src="<?=$dim['src']?>"> <a href="<?=$dim['src']?>" target="_blank"><?=ss_admin_hiliteImageSizes($dim['src'])?></a></div>
                <?php endforeach; ?>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>

    <hr>
    <div class="pager b">
        <b>Page</b>: <?php print ss_get('media.pager')->generate(); ?>
    </div>

<?php else: ?>
    No images.
<?php endif; ?>

</div>


<script>
mii.onReady(function($){
    var loading = $.dom(".loading-span div");
    $.dom("#file").on("change", function(){
        if (!this.value) return;
        loading.setText(this.value);
        loading.animate({width: 600}, 1000);
        var t = setTimeout(function(){
            $.dom(".file-form")[0].submit();
        }, 1000);
    });

    var checkboxes = $.dom(".image-meta input[type='checkbox']");
    checkboxes.on("click", function(){
        var id = this.getAttribute("data-id");
        checkboxes.forEach(function(el){
            if (el.getAttribute("data-id") !== id) {
                el.checked = false;
            }
        });
    });

    $.dom(".image-insert-button").on("click", function(){
        var id = this.getAttribute("data-id");
        var src = [];
        checkboxes.forEach(function(el){
            if (el.getAttribute("data-id") === id && el.checked) {
                src.push(el.getAttribute("data-src"));
            }
        });

        if ($.isEmpty(src)) {
            return alert("Please select at least one image!");
        }

        var s;
        while (s = src.shift()) {
            top.__ss.editor.insertImage(s);
        }

        setTimeout(function(){
            var modal = new top.__ss.modal();
            modal.destroy();
        }, 100);
    });
});

</script>

<style>
.images {
    margin-top: 22px;
} .images h6 {
    font-size: 12px;
    margin-bottom: 12px;
    padding-bottom: 3px;
    border-bottom: 1px solid #aaa;
} .images ul li {
    display: table;
    width: 99.5%;
    margin-bottom: 12px;
    border-bottom: 1px solid #eee;
} .images ul li:last-child {
    border-bottom: 0;
} .images ul li .image-insert-button {
    visibility: hidden;
} .images ul li:hover .image-insert-button {
    visibility: visible;
} .images .image-crop {
    float: left;
    margin: 0 12px 12px 0;
} .images .image-meta {
    padding-left: 100px;
} .images .image-meta div {
    margin-bottom: 3px;
} .images .image-meta * {
    font: 10px monospace;
} .images .image-meta a span {
    color: #333;
} .images .image-meta a:hover span {
    color: inherit;
} .images .image-meta b {
    font-size: 12px;
}

.file-form {
    display: block;
    position: relative;
    line-height: 12px;
} #file {
    border: 1px solid;
    opacity: 0; filter: alpha(opacity=0);
    position: absolute;
    top: 0; left: 0;
    z-index: 1;
    cursor: pointer;
} .file-span {
    float: left;
    display: inline-block;
    position: relative;
    padding: 3px 6px;
    overflow: hidden;
    cursor: pointer;
    font-weight: bold;
    text-align: center;
    background-color: #e0e0e0;
    border-radius: 4px;
    margin-right: 12px;
} .file-span * {
    color: #454545;
    cursor: pointer;
} .loading-span {
    float: left;
    display: inline-block;
    position: relative;
} .loading-span div {
    position: absolute;
    background-color: #d7e7f4;
    border-radius: 4px;
    padding: 3px 6px;
    white-space: nowrap;
}
</style>

</body>
</html>