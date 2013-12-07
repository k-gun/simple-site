<?php include(__DIR__.'/Boot.php'); ?>

<?php ss_admin_getHead(); ?>

<?php ss_admin_getAside(); ?>

<?php
// Delete image
if (($_get_delete = ss_filter_getValue('delete', SS_FILTER_TYPE_INT))) {
    ss_media_image_delete($_get_delete);
}

// Get images
$images = ss_media_image_getAll();
?>

<div class="ss-admin-page-content">

    <div class="ss-admin-page-title">
        <h3>Media</h3>
    </div>

    <?php if (!no($images)): ?>

    <table class="ss-admin-list">

        <tr>
            <th width="100">&nbsp;</th>
            <th>Sizes</th>
            <th width="1">&nbsp;</th>
        </tr>

        <?php foreach($images as $image): ?>
        <tr>
            <td><img src="<?=$image->crop['src']?>" width="70" height="70" class="image-crop"></td>
            <td><div class="image-meta">
                    <div><a href="<?=$image->crop['src']?>" target="_blank"><?=ss_admin_hiliteImageSizes($image->crop['src'])?></a> (crop)</div>
                    <div><a href="<?=$image->orig['src']?>" target="_blank"><?=ss_admin_hiliteImageSizes($image->orig['src'])?></a> (original)</div>
                    <?php foreach ($image->data->dims as $dim): ?>
                    <div><a href="<?=$dim['src']?>" target="_blank"><?=ss_admin_hiliteImageSizes($dim['src'])?></a></div>
                    <?php endforeach; ?>
                </div></td>
            <td nowrap>
                &nbsp;
                <a href="?delete=<?=$image->id?>" class="red" confirm="#Delete? (This action will not remove images in items)">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

    <hr>
    <div class="pager b">
        <b>Page</b>: <?php print ss_get('media.pager')->generate(); ?>
    </div>

    <?php else: ?>

    <br>No images found.

    <?php endif; ?>

</div>

<style>
.image-meta * {
    font: 10px monospace;
} .image-meta a span {
    color: #333;
} .image-meta a:hover span {
    color: inherit;
} .image-meta b {
    font-size: 12px;
}
</style>

<?php ss_admin_getFoot(); ?>