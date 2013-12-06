
<div class="ss-container">

    <div class="ss-item fixed">

<?php
$item = ss_data();
if (!no($item)):
    // $item_images = the_item_images($item);
    // $item_images_first = get_array_value($item_images, 0);
?>

        <h1 class="ss-item-title">
            <?php print the_item_title($item); ?>
        </h1>

        <div class="ss-item-content">
            <?php print the_item_content($item); ?>
        </div>

        <?php if (the_item_isCommenAllowed($item)): ?>
        <div class="ss-item-comments">
            <?php include('comment_form.php'); ?>
        </div>
        <?php endif; ?>


<?php else: ?>

    Content not found.

<?php endif; ?>

    </div>

</div>
