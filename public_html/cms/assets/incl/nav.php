<?php
/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 19-06-2017
 */ ?>


<?php if ($auth->user->sysadmin || $auth->user->admin) { ?>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand" href="/Marius/public_html/cms/index.php">Roloff-cms</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="/public_html/cms/admin/imageUpload.php?mode=list">Upload</a></li>
                    <li><a href="/public_html/cms/admin/user.php?mode=list">User</a></li>
                    <li><a href="/public_html/cms/admin/usergroup.php?mode=list">User Groups</a></li>
                    <li><a href="/public_html/cms/admin/shopcategory.php?mode=list">Categorys</a></li>
                    <li><a href="/public_html/cms/admin/works.php?mode=list">Works</a></li>
                    <li><a href="/public_html/cms/admin/news.php?mode=list">news</a></li>
                    <li><a href="/public_html/cms/admin/About.php?mode=list">about</a></li>
                </ul>


            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

<?php  }
