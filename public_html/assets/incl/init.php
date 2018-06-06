<?php
/*
* Author @ Mads Roloff - Roloff-design
*/

//display errors set to zero to disable;
ini_set("display_errors", 0);

//set timezone;
date_default_timezone_set("Europe/Copenhagen");

//define constants;

//define("DOCROOT", filter_input(INPUT_SERVER, "DOCUMENT_ROOT", FILTER_SANITIZE_STRING) . "/"); // uno euro/




define("DOCROOT", filter_input(INPUT_SERVER, "DOCUMENT_ROOT", FILTER_SANITIZE_STRING) . "/public_html/"); // school server and one.com
define("SUBDOCROOT", substr(DOCROOT, 0, strrpos(DOCROOT, "/public_html")));

define("COREPATH", substr(SUBDOCROOT, 0, strrpos(SUBDOCROOT, "/public_html")) . "/core/");
define("HOSTPATH", 0); // 1 IF UPLOAD TO SCHOOL SERVER OR ONE, 0 IF UPLOAD TO UNO EURO;

//EasyPHP
//define("DOCROOT", filter_input(INPUT_SERVER, "DOCUMENT_ROOT", FILTER_SANITIZE_STRING));
//define("COREPATH", substr(DOCROOT, 0, strrpos(DOCROOT, "/")) . "/core/");

//Functions
require_once(SUBDOCROOT.COREPATH . 'functions.php');
/*ClassLoader*/
require_once(SUBDOCROOT.COREPATH .  'classes/ClassLoader.php');
$Classloader = new ClassLoader();
//$db = new db();
$db = new dbconf();
$setup = new Setup();

$imageSel = new image();

$auth = new auth();
$auth->iShowLoginForm = 0;
$auth->authentificate();
