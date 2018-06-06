<?php
if (is_file($_SERVER["DOCUMENT_ROOT"] . "/public_html/assets/incl/init.php")) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public_html/assets/incl/init.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public_html/assets/incl/init.php");
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/header.php");





if ($auth->user->sysadmin || $auth->user->admin) {
require_once (DOCROOT . "/cms/assets/incl/nav.php");
?>

<div class="">
    <div class="container">
        <div class="col-lg-12">
            <h1 class="text-center">Velkommen til Roloff-CMS <br><strong><?php echo $auth->user->vcUserName; ?></strong></h1>
            <hr style="background-color: black; height: 1px;">
            <ul class="text-center nav custom-nav">
                <li><a href="/Marius/public_html/cms/admin/imageUpload.php?mode=list">Upload</a></li>
                <li><a href="/Marius/public_html/cms/admin/user.php?mode=list">User</a></li>
                <li><a href="/Marius/public_html/cms/admin/usergroup.php?mode=list">User Groups</a></li>
            </ul>
            <hr style="background-color: black; height: 1px;">
        </div>
        <?php
        }
        if (!$auth->checkSession()) {
            ?>


            <div class="col-lg-6 col-lg-offset-3" style="margin-top:5%;">
                <h2 class="text-center">Login</h2>
                <form id="loginform" method="post" autocomplete="off">

                    <div class="form-group">
                        <label for="username"> Username</label>
                        <input type="text" class="form-control" id="username" autocapitalize="none" name="username" placeholder="Indtast brugernavn" value="">
                    </div>

                    <div class="form-group">
                        <label for="password"> Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="password" value="">
                    </div>

                    <div class="btn-group col-lg-12">
                        <button type="submit" value="login" style="width: 100%;" class="btn btn-success login-button">Log ind</button>
                    </div>

                </form>
            </div>
            <?php
        }

        require_once($_SERVER["DOCUMENT_ROOT"] . "/Marius/public_html/cms/assets/incl/footer.php");

        ?>
