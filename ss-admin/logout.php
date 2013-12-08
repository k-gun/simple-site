<?php include(__DIR__.'/Boot.php'); ?>

<?php
ss_user_logout();
redirect('/ss-admin/login');
?>