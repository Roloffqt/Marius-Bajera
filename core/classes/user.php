<?php

/**
 *  Auther : Heinz HEKA
 *  Tech college aalborg
 *  Modifyed by Mads Roloff
 */

class user
{

    private $db;
    public $iUserID = -1;
    public $vcImage;
    public $vcUserName;
    public $vcPassword;
    public $vcFirstName;
    public $vcLastName;
    public $iOrgID;
    public $vcAddress;
    public $iZip;
    public $vcCity;
    public $vcEmail;
    public $vcPhone1;
    public $vcPhone2;
    public $vcPhone3;
    public $daCreated;
    public $iDeleted;
    public $errormsg;


    public $arrFormElms = array();
    public $arrLabels = array();
    public $arrValues = array();
    public $arrGroups = array();
    public $unset = array();


    /* User Role Constants */
    public $sysadmin;
    public $admin;
    public $extranet;
    public $newsletter;

    public function __construct()
    {
        global $db;
        $this->db = $db;
        // $this->CreateTable();
        $this->Table = "user";

        $this->arrFormElm = array(
            "iUserID" => array("label" => "", "type" => "hidden", "placeholder" => "", "required" => "data-requried='1'", "dbname" => "iUserID", "Filter" => FILTER_SANITIZE_NUMBER_INT),
            "vcFirstName" => array("label" => "First Name", "type" => "text", "placeholder" => "First name", "required" => "data-requried='1'", "dbname" => "vcFirstName", "Filter" => FILTER_SANITIZE_STRING),
            "vcLastName" => array("label" => "Last Name", "type" => "text", "placeholder" => "Last name", "required" => "data-requried='1'", "dbname" => "vcLastName", "Filter" => FILTER_SANITIZE_STRING),
            "vcUserName" => array("label" => "user name", "type" => "text", "placeholder" => "Username", "required" => "data-requried='1'", "dbname" => "vcUserName", "Filter" => FILTER_SANITIZE_STRING),
            "vcPassword" => array("label" => "password", "type" => "password", "placeholder" => "Password", "required" => "data-requried='1'", "dbname" => "vcPassword", "Filter" => FILTER_SANITIZE_STRING),
            "vcAddress" => array("label" => "Adresse", "type" => "text", "placeholder" => "Address", "required" => "data-requried='1'", "dbname" => "vcAddress", "Filter" => FILTER_SANITIZE_STRING),
            "iZip" => array("label" => "Zip code", "type" => "text", "placeholder" => "Zip Code", "required" => "data-requried='1'", "dbname" => "iZip", "Filter" => FILTER_SANITIZE_NUMBER_INT),
            "vcCity" => array("label" => "City", "type" => "text", "placeholder" => "City", "required" => "data-requried='1'", "dbname" => "vcCity", "Filter" => FILTER_SANITIZE_STRING),
            "vcEmail" => array("label" => "Email", "type" => "email", "placeholder" => "Email", "required" => "data-requried='1'", "dbname" => "vcEmail", "Filter" => FILTER_SANITIZE_STRING),
            "vcPhone1" => array("label" => "Phone Number", "type" => "tel", "placeholder" => "Phone Number", "required" => "data-requried='1'", "dbname" => "vcPhone1", "Filter" => FILTER_SANITIZE_NUMBER_INT),
            "vcPhone2" => array("label" => "Phone Number", "type" => "tel", "placeholder" => "Phone Number", "required" => "data-requried='1'", "dbname" => "vcPhone2", "Filter" => FILTER_SANITIZE_NUMBER_INT),
            "vcImage" => array("label" => "", "type" => "hidden", "placeholder" => "vcImage1", "required" => "data-requried='1'", "dbname" => "vcImage1", "Filter" => FILTER_SANITIZE_STRING),
        );
    }

    public function GetSelect()
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0";
        return $this->db->_fetch_array($sql, array());
    }

    public function GetList()
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0";
        //Shows Column names from "user"
        $sqlLabels = "SHOW FULL COLUMNS FROM $this->Table";
        $this->arrLabels = $this->db->_fetch_array($sqlLabels, array());
        $this->arrValues = $this->db->_fetch_array($sql, array());

        //UNSET = Columns to avoid in LIST
        $this->unset = array(
            "iUserID",
            "vcPassword",
            "vcPassword2",
            "iUserRole",
            "vcPhone2",
            "vcPhone3",
            "iOrgID",
            "daCreated",
            "iSuspended",
            "iDeleted",
            "vcCity",
            "iZip",
            "iIsActive"
        );
    }

    public function getDetails($iUserID)
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND IUserID = ?";
        //Shows Column names from "user"
        $sqlLabels = "SHOW FULL COLUMNS FROM $this->Table";
        $this->arrLabels = $this->db->_fetch_array($sqlLabels, array());
        $this->arrValues = $this->db->_fetch_array($sql, array($iUserID));

        //UNSET = Columns to avoid in LIST
        $this->unset = array(
            "iUserID",
            "vcPassword",
            "vcPassword2",
            "iUserRole",
            "vcPhone2",
            "vcPhone3",
            "iOrgID",
            "daCreated",
            "iSuspended",
            "iDeleted",
            "vcCity",
            "iZip",
            "iIsActive"
        );
    }


    public function getUser($iUserID)
    {
        $this->iUserID = $iUserID;
        $sql = "SELECT u.*, o.vcOrgName FROM $this->Table u LEFT JOIN org o ON u.iOrgID = o.iOrgID WHERE iUserID = ? AND u.iDeleted = 0";
        $row = $this->db->_fetch_array($sql, array($this->iUserID));
        foreach ($row[0] as $key => $value) {
            $this->$key = $value;
        }

        $this->arrGroups = $this->getgrouprelations();

        foreach ($this->arrGroups as $value) {
            $role = strtolower($value["vcRoleName"]);
            $this->$role = 1;
        }
    }

    public function getgrouprelations()
    {
        $params = array($this->iUserID);
        $strSelect = "SELECT g.iGroupID, g.vcGroupName, g.vcRoleName FROM usergroup g, usergrouprel x WHERE x.iUserID = ? AND x.iGroupID = g.iGroupID AND g.iDeleted = 0;";
        return $this->db->_fetch_array($strSelect, $params);
    }


    public
    function UpdateUser($iUserID)
    {
        //unsets IUserID from the array
        unset($this->arrFormElm["iUserID"]);

        //MAkes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {
            $f[$key] = filter_input(INPUT_POST, $key, $value["Filter"]);
        }
        $f["vcPassword"] = $this->hashpw($f["vcPassword"]);

        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $params = array_values($f);

        //iUserID = IUserID
        $this->iUserID = $iUserID;

        //awesome SQL that makes an string that looks like this
        //"UPDATE user SET vcFirstName = ?, vcLastName = ?, vcUserName = ?, vcPassword = ?, vcAddress = ?, iZip = ?, vcCity = ?, vcEmail = ?, vcPhone1 = ?, vcPhone2 = ? WHERE iUserID = 12"
        echo $sql = "UPDATE $this->Table SET " . implode(array_keys($this->arrFormElm), " = ?, ") . " = ? WHERE iUserID = $this->iUserID";
        return $this->db->_query($sql, $params);
    }

    public
    function DeleteUser($iUserID)
    {
        $this->iUserID = $iUserID;
        //SQL that sets iDeleted to 1
        $sql = "UPDATE $this->Table SET iDeleted = 1 WHERE iUserID = ?";
        return $this->db->_query($sql, array($iUserID));
    }

    Public
    function CreateUser($iUserID)
    {

        //unsets IUserID from the array
        unset($this->arrFormElm["iUserID"]);
        unset($this->arrFormElm["daCreated"]);

        //Makes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {

            $f[$key] = filter_input(INPUT_POST, $key, $value["Filter"]);

        }
        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $f["vcPassword"] = $this->hashpw($f["vcPassword"]);
        $check = 'SELECT * FROM ' . $this->Table . ' WHERE vcEmail = "' . $f["vcEmail"] . '"';


        $params = array_values($f);
        $doublecheck = $this->db->_fetch_array($check);
        //iUserID = IUserID
        $this->iUserID = $iUserID;
        showme($doublecheck);

            //awesome SQL that makes an string that looks like this
            //"UPDATE user SET vcFirstName = ?, vcLastName = ?, vcUserName = ?, vcPassword = ?, vcAddress = ?, iZip = ?, vcCity = ?, vcEmail = ?, vcPhone1 = ?, vcPhone2 = ? WHERE iUserID = 12"
          echo  $sql = "INSERT INTO $this->Table SET " . implode(array_keys($this->arrFormElm), " = ?, ") . " = ?, daCreated = " . time();
          showme($params);
          $this->db->_query($sql, $params);

            $uid = $this->db->_getinsertid();

            $gparams = array($uid, 3);
            showme($gparams);
          echo  $strInsert = "INSERT INTO usergrouprel(iUserID, iGroupID) VALUES(?,?)";

            $this->db->_query($strInsert, $gparams);
        }


    public function hashpw($password)
    {
        $salt = "Py!n0{}@0SZX7XJ1M@BXs#8LI%%v/M]k";

        $password = "$salt+!$password$salt!$salt";

        return hash("sha256", $password);
    }

    /* public
     function CreateTable()
     {
         $sql = "CREATE TABLE IF NOT EXISTS `user` (
   `iUserID` bigint(20) NOT NULL AUTO_INCREMENT,
   `vcUserName` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Brugernavn',
   `vcPassword` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Kodeord',
   `vcFirstName` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Navn',
   `vcLastName` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Efternavn',
   `vcAddress` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Adresse',
   `iZip` mediumint(10) NOT NULL COMMENT 'Postnummer',
   `vcCity` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'By',
   `vcEmail` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Email',
   `vcPhone1` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Telefon 1',
   `vcPhone2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Telefon 2',
   `vcPhone3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Telefon 3',
   `iOrgID` bigint(20) NOT NULL DEFAULT '1' COMMENT 'Organisation',
   `daCreated` bigint(20) NOT NULL COMMENT 'Oprettet',
   `iSuspended` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Suspenderet',
   `iDeleted` tinyint(4) NOT NULL DEFAULT '0',
   PRIMARY KEY (`iUserID`)
 ) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 ";
         // $this->CreateInput();

         $this->db->_query($sql);


         $sql = " CREATE TABLE IF NOT EXISTS `usersession` (
     `vcSessionID` VARCHAR(32) NOT NULL DEFAULT '',
               `iUserID` BIGINT(20) NOT NULL DEFAULT '0',
               `iIpAddress` VARCHAR(24) NOT NULL DEFAULT '',
               `iIsLoggedIn` TINYINT(1) NOT NULL DEFAULT '0',
               `daLoginCreated` BIGINT(20) NOT NULL DEFAULT '0',
               `daLastAction` BIGINT(20) NOT NULL DEFAULT '0')
               COLLATE = 'utf8_general_ci'ENGINE = MyISAM";
         $this->db->_query($sql);
     }

     public function CreateInput()
     {
         $sql = "INSERT IGNORE INTO `user` (`iUserID`, `vcUserName`, `vcPassword`, `vcFirstName`, `vcLastName`, `vcAddress`, `iZip`, `vcCity`, `vcEmail`, `vcPhone1`, `vcPhone2`, `vcPhone3`, `iOrgID`, `daCreated`, `iSuspended`, `iDeleted`)
                 VALUES (1, 'Admin' , 'admin', 'Mads', 'Roloff', 'Blåkildevej 58', 9220, 'Aalborg', 'Mads@admin.com', '22950302', '22950302', '22950302', 1, 1502347709, 0, 1)";
         $this->db->_query($sql);

     }
     */
}
