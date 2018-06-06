<?php
session_start();
/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 13-09-2017
 */

/**
 * If else statement by Glen Jensen
 */
if (is_file($_SERVER["DOCUMENT_ROOT"] . "/assets/incl/init.php")) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/assets/incl/init.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/assets/incl/init.php");
}
require_once($_SERVER["DOCUMENT_ROOT"] . "/assets/incl/header.php");
/**
 * Sets $_GET["message"] = null for the Signup for newsletter notifcation, since thats not made in ajax
 */

?>

<body class="container">
<!-- Menu move to PHP file and test -->
<section class="row">
<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/assets/incl/nav.php");
$page = setMode();
$media = setPage();
switch ($page) {
        default:
require_once($_SERVER["DOCUMENT_ROOT"] . "/landingpage.php");
        break;
        case 'index':
require_once($_SERVER["DOCUMENT_ROOT"] . "/landingpage.php");
        break;
        case 'about':
require_once($_SERVER["DOCUMENT_ROOT"] . "/about.php");
        break;
        case 'works':
              require_once($_SERVER["DOCUMENT_ROOT"] . "/works.php");
        break;
  case 'details':
              require_once($_SERVER["DOCUMENT_ROOT"] . "/works.php");
        break;
        case 'contact':
require_once($_SERVER["DOCUMENT_ROOT"] . "/contact.php");
        break;
}
?>
</section>
<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/assets/incl/footer.php");
 ?>
<!-- landing page move to seprat file -->
