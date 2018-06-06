<?php
/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 03-04-2017
 */
 if (is_file($_SERVER["DOCUMENT_ROOT"] . "/cms/assets/incl/init.php")) {
     require_once($_SERVER["DOCUMENT_ROOT"] . "/cms/assets/incl/init.php");
 } else {
     require_once($_SERVER["DOCUMENT_ROOT"] . "/cms/assets/incl/init.php");
 }


 sysBackendHeader();
if (!$auth->user->sysadmin || $auth->user->admin) {
    ?>

    <body class="container">

    <div class="col-lg-4">
        <h2>Login</h2>
        <form id="loginform" method="POST" data-validate autocomplete="off">

            <div class="form-group">
                <label for="username" class=""> Username</label>
                <input type="Email" class="form-control" data-requried="1" id="username" autocapitalize="none" name="username" placeholder="Indtast Email" value="">
            </div>

            <div class="form-group">
                <label for="password" class=""> Password</label>
                <input type="password" data-requried="1" class="form-control" id="password" name="password" placeholder="password" value="">
            </div>

            <div class="btn-group">
                <button type="submit" value="login" class="login-button"><i class="fa fa-chevron-right"></i></button>
            </div>

        </form>
    </div>
    <?php
} else {
    header("location: /cms/index.php");
}
