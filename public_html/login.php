<?php

    require_once(filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/assets/incl/init.php");

sysHeader();
?>
    <div class="nav-bg section-space-small">
        <?php
        require_once(DOCROOT . "assets/incl/nav.php";
        ?>
    </div>

<?php if (!$auth->checkSession()) { ?>
    <article class="container section-space">
        <section class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <h2 class="text-center">Login</h2>
                <form id="loginform" method="post" autocomplete="off">
                    <h3 style="text-align: center;"><?php echo $auth->strErrMessage; ?></h3>
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
                <p class="text-center"><a style="margin-top:20px;" href="register.php">Register Bruger</a></p>
            </div>
        </section>
    </article>
    <?php
} else {
    header("location: Marius/public_html/cms/admin/user.php");
}
sysFooter();
