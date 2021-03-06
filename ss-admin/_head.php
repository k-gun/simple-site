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

<div class="ss-admin-head">

    <div class="ss-admin-container container fixed">

        <div class="ss-admin-head-logo">
            <a href="<?=ss_admin_link('/')?>">Home</a>
        </div>

        <div class="ss-admin-head-menu">
            <ul>
                <?php if (!is_user()): ?>
                <li><a href="<?=ss_admin_link('login')?>">Log in</a></li>
                <?php else: ?>
                <li><a href="<?=ss_admin_link('logout')?>">Log out</a></li>
                <?php endif; ?>
            </ul>
        </div>

    </div>

</div><!-- .ss-admin-head -->

<div class="ss-admin-body">
    <div class="ss-admin-container container fixed">
