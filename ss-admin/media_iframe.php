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
    <script src="<?=ss_admin_asset('modal.js')?>"></script>
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
    pre($_FILES);
    ss_media_image_upload($_FILES['file']);
}

$table  = ss_mysql_table('media');
$medias = ss_mysql_getAll("SELECT * FROM $table");
// pre($medias);
?>

<script>
mii.onReady(function($){
    var loading = $.dom(".loading-span div");
    $.dom("#file").on("change", function(){
        if (!this.value) return;
        loading.setText(this.value);
        loading.animate({width: 620}, 1000);
        var t = setTimeout(function(){
            $.dom(".file-form")[0].submit();
        }, 1000);
    });
});
</script>

<style>
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