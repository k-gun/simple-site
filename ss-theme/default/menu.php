<?php
$menus = ss_menu_getLinks();
// pre($menus,1);
?>

<ul>
    <li class="home"><a href="/">Home</a></li>
    <?php foreach ($menus as $menu): ?>
    <li><a href="<?=$menu->link_href?>"><?=$menu->link_text?></a></li>
    <?php endforeach; ?>
</ul>
