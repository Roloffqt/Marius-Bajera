<?php
/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 23-06-2017
 */ ?>
<?php
require_once filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/assets/incl/init.php";

echo $sql = "INSERT INTO newsletter(vcEmail) VALUES (?)";
$db->_query($sql, $_POST);

header("location: /index.php?message=Du er nu tilmeldt nyhedsbrev");


?>

