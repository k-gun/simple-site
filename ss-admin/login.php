<?php include(__DIR__.'/Boot.php'); ?>

<?php ss_admin_getHead(); ?>

<?php
if (is_user()) {
    redirect('/ss-admin/');
}

if (isset($_POST['ss-username'])) {
    ss_user_login();
}
?>

<div class="ss-admin-login">
    <h3>Login</h3>
    <form method="post" action="<?=ss_filter(get_uri(false), true)?>">
        <p><input type="text" name="ss-username" size="20"></p>
        <p><input type="password" name="ss-password" size="20"></p>
        <p><input type="submit" value="Submit"></p>
    </form>
</div>

<?php ss_admin_getFoot(); ?>