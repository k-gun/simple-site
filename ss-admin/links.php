<?php include(__DIR__.'/Boot.php'); ?>

<?php ss_admin_getHead(); ?>

<?php ss_admin_getAside(); ?>

<div class="ss-admin-page-content">

    <div class="ss-admin-page-title">
        <h3>Item link structure</h3>
    </div>

    <?php
        // $links = cfg('routes');
        // pre($links);
        // [0] => ~^/(?\d+)\.item$~i
        // [1] => ~^/item[/\-](?\d+)(\.html|)$~i
        // [2] => ~^/item[/\-](?\d+)[/\-](?[a-z0-9-]+)(\.html|)$~i
        // [3] => ~^/(?[a-z0-9-]+)[/\-](?\d+)\.(item|html)$~i
        // [4] => ~^/item/(?[a-z0-9-]+)(\.html|)$~i
    ?>
    <ul class="ss-admin-item-links">
        <li>/123.item</li>
        <li>/item/123</li>
        <li>/item/123.html</li>
        <li>/title/123.html</li>
        <li>/title/123.item</li>
        <li>/item/title.html (<span class="gray">default</span>)</li>
        <li>/item/123/title.html</li>
    </ul>

</div>

<style>
.ss-admin-item-links {
    list-style-type: inherit;
    list-style-position: inside;
} .ss-admin-item-links * {
    font: 12px monospace;
    line-height: 2em;
}
</style>

<?php ss_admin_getFoot(); ?>