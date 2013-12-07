
<div class="ss-admin-aside">

    <div class="ss-admin-aside-box">
        <h3><i class="fa fa-edit"></i> Item</h3>
        <ul>
            <li><a href="<?=ss_admin_link('item-list')?>">List items</a></li>
            <!-- <li><a href="<?=ss_admin_link('item-list?status=%s', SS_ITEM_STATUS_DELETED)?>">List items (<?=SS_ITEM_STATUS_DELETED?>)</a></li> -->
            <li><a href="<?=ss_admin_link('item-insert')?>">Add new item</a></li>
        </ul>
    </div>

    <div class="ss-admin-aside-box">
        <h3><i class="fa fa-comments-o"></i> Comments</h3>
        <ul>
            <li><a href="<?=ss_admin_link('comment-list')?>">List comments</a></li>
        </ul>
    </div>

    <div class="ss-admin-aside-box">
        <h3><i class="fa fa-list"></i> Menu</h3>
        <ul>
            <li><a href="<?=ss_admin_link('menu-list')?>">List menus</a></li>
            <li><a href="<?=ss_admin_link('menu-insert')?>">Add new menu item</a></li>
        </ul>
    </div>

    <div class="ss-admin-aside-box">
        <h3><i class="fa fa-picture-o"></i> Media</h3>
        <ul>
            <li><a href="<?=ss_admin_link('media-list')?>">List media</a></li>
            <li><a href="#" action="add-new-media">Add new media</a></li>
        </ul>
    </div>

    <div class="ss-admin-aside-box">
        <h3><i class="fa fa-link"></i> Link</h3>
        <ul>
            <li><a href="<?=ss_admin_link('links')?>">View link structure</a></li>
        </ul>
    </div>

    <!-- <div class="ss-admin-aside-box">
        <h3><i class="fa fa-gear"></i> Options</h3>
        <ul>
            <li><a href="<?=ss_admin_link('options-list')?>">List options</a></li>
            <li><a href="<?=ss_admin_link('options-insert')?>">Add new option</a></li>
        </ul>
    </div> -->

</div><!-- .ss-admin-aside -->

<script>
mii.onReady(function($){
    $.dom("a[action='add-new-media']").on("click", function(){
        // Image modal
        var modal = new __ss.modal({width: 750, height: 450});
        modal.open("Insert Image", "", function(){
            var iframe = $.dom("iframe", {
                width: "100%",
                height: "98%",
                frameBorder: 0,
                src: "<?=ss_admin_link('media-image-iframe?insert-only=1')?>"
            });
            iframe.appendTo(modal.body);
        });
    });
});
</script>
