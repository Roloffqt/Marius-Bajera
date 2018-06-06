<?php
/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 17-05-2017
 */ ?>
<?php
include_once "/assets/incl/init.php";
if ($auth->user->sysadmin || $auth->user->admin) {
    sysBackendHeader();
    ?>


    <?php
    sysBackendFooter();
} else {
    header("location: ../login.php");
}
?>
