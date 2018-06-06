<?php

/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 17-05-2017
 */
class usergroup
{

    private $db;
    public $iGroupID = -1;
    public $vcGroup;
    public $txDesc;
    public $txShortDesc;
    public $vcGroupName;
    public $vcRoleName;
    public $daCreated;
    public $iSuspended;
    public $iDeleted;
    public $vcShortDesc;

    public $arrFormElms = array();
    public $arrLabels = array();
    public $arrColumns = array();
    public $arrValues = array();
    public $unset = array();

    public function __construct()
    {
        global $db;
        $this->db = $db;
        //$this->CreateTable();
        $this->Table = "usergroup";

        $this->arrFormElm = array(
            "iGroupID" => array("label" => "", "type" => "hidden", "placeholder" => "", "required" => "data-requried='1'", "dbname" => "iGroupID", "Filter" => FILTER_SANITIZE_NUMBER_INT),
            "vcGroupName" => array("label" => "Group Titel", "type" => "text", "placeholder" => "Group Titel", "required" => "data-requried='1'", "dbname" => "vcGroupName", "Filter" => FILTER_SANITIZE_STRING),
            "txDesc" => array("label" => "txDesc", "type" => "textarea", "placeholder" => "txDesc", "required" => "data-requried='1'", "dbname" => "txDesc", "Filter" => FILTER_SANITIZE_STRING),
            "vcRoleName" => array("label" => "vcRoleName", "type" => "text", "placeholder" => "vcRoleName", "required" => "data-requried='1'", "dbname" => "vcRoleName", "Filter" => FILTER_SANITIZE_STRING),

            //Unneeded fields
            //"iSuspended" => array(),
            //"iDeleted" => array(),
            //"iOrgID" => array("label" => "", "type" => "hidden", "placeholder" => "",),
            //"vcGroup" => array("label" => "Group", "shpwn", "type" => "text", "require" => "", "placeholder" => "Pick a Group",),

        );

    }

    public function GetSelect()
    {
        $sql = "select * from $this->Table WHERE iDeleted = 0";
        return $this->db->_fetch_array($sql, array());
    }

    public function GetList()
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0";
        //Shows Column names from "Group"
        $sqlLabels = "SHOW FULL COLUMNS FROM $this->Table";
        $this->arrLabels = $this->db->_fetch_array($sqlLabels, array());
        $this->arrValues = $this->db->_fetch_array($sql, array());

        //UNSET = Columns to avoid in LIST
        $this->unset = array(
            "iGroupID",
            "iDeleted",
            "daCreated",
            "iIsActive"

        );
    }

    public function getDetails($iGroupID)
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iGroupID = ?";
        //Shows Column names from "Group"
        $sqlLabels = "SHOW FULL COLUMNS FROM $this->Table";
        $this->arrLabels = $this->db->_fetch_array($sqlLabels, array());
        $this->arrValues = $this->db->_fetch_array($sql, array($iGroupID));

        //UNSET = Columns to avoid in LIST
        $this->unset = array(
            "iGroupID",
            "iDeleted"

        );
    }

    public function getGroup($iGroupID)
    {
        $this->iGroupID = $iGroupID;
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iGroupID = ?";
        $row = $this->db->_fetch_array($sql, array($this->iGroupID));
        foreach ($row[0] as $key => $value) {
            $this->$key = $value;
        }
    }

    public function UpdateObj($iGroupID)
    {
        //unsets iGroupID from the array
        unset($this->arrFormElm["iGroupID"]);

        //MAkes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {
            $f[$key] = filter_input(INPUT_POST, $key, $value["Filter"]);
        }
        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $params = array_values($f);

        //iGroupID = iGroupID
        $this->iGroupID = $iGroupID;

        //awesome SQL that makes an string that looks like this
        //"UPDATE Group SET vcFirstName = ?, vcLastName = ?, vcGroupName = ?, vcPassword = ?, vcAddress = ?, iZip = ?, vcCity = ?, vcEmail = ?, vcPhone1 = ?, vcPhone2 = ? WHERE iGroupID = 12"
        echo $sql = "UPDATE $this->Table SET " . implode(array_keys($this->arrFormElm), " = ?, ") . " = ? WHERE iGroupID = $this->iGroupID";
        return $this->db->_query($sql, $params);
    }

    public function Delete($iGroupID)
    {
        $this->iGroupID = $iGroupID;
        //SQL that sets iDeleted to 1
        $sql = "UPDATE $this->Table SET iDeleted = 1 WHERE iGroupID = ?";
        return $this->db->_query($sql, array($iGroupID));
    }

    public function SaveGroup()
    {

    }

    Public function CreateObj($iGroupID)
    {
        //unsets iGroupID from the array
        unset($this->arrFormElm["iGroupID"]);
        unset($this->arrFormElm["daCreated"]);

        //Makes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {
            $f[$key] = filter_input(INPUT_POST, $key, $value["Filter"]);
        }

        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $params = array_values($f);

        //iGroupID = iGroupID
        $this->iGroupID = $iGroupID;

        //awesome SQL that makes an string that looks like this
        //"UPDATE Group SET vcFirstName = ?, vcLastName = ?, vcGroupName = ?, vcPassword = ?, vcAddress = ?, iZip = ?, vcCity = ?, vcEmail = ?, vcPhone1 = ?, vcPhone2 = ? WHERE iGroupID = 12"
        $sql = "INSERT INTO $this->Table SET " . implode(array_keys($this->arrFormElm), " = ?, ") . " = ?, daCreated = " . time();
        return $this->db->_query($sql, $params);
    }


    /* public function CreateTable()
     {
         $sql = "
 CREATE TABLE IF NOT EXISTS `usergroup` (
   `iGroupID` bigint(20) NOT NULL AUTO_INCREMENT,
   `vcGroupName` varchar(255) NOT NULL COMMENT 'Gruppenavn',
   `txDesc` text COMMENT 'Beskrivelse',
   `vcRoleName` varchar(20) DEFAULT NULL COMMENT 'Rollenavn',
   `daCreated` bigint(20) NOT NULL DEFAULT '0' COMMENT 'Oprettet',
   `iDeleted` tinyint(1) NOT NULL DEFAULT '0',
   `iIsActive` tinyint(4) NOT NULL DEFAULT '1',
   PRIMARY KEY (`iGroupID`)
 ) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
 ";
         // $this->CreateInput();
         $this->db->_query($sql);


         $sql = "
 CREATE TABLE IF NOT EXISTS `usergrouprel` (
   `iRelID` bigint(20) NOT NULL AUTO_INCREMENT,
   `iUserID` bigint(20) NOT NULL,
   `iGroupID` bigint(20) NOT NULL,
   PRIMARY KEY (`iRelID`)
 ) ENGINE=MyISAM AUTO_INCREMENT=150 DEFAULT CHARSET=latin1;
 ";

         $this->db->_query($sql);
     }

     public function CreateInput()
     {
         $sql = "INSERT IGNORE INTO `usergroup` (`iGroupID`, `vcGroupName`, `txDesc`, `vcRoleName`, `daCreated`, `iDeleted`, `iIsActive`) VALUES
      (1, 'Systen Admin', 'am i working?', 'sysadmin', 0, 0, 1),
      (2, 'Adminstrator', 'Groups for adminstratoer \\Gives access to CMS system', 'admin', 0, 0, 1),
      (3, 'Extranet', 'Group for extranet users', 'extranet', 0, 0, 1),
      (4, 'Nyhedsbrev', 'Group for newsletter users', 'newsletter', 0, 0, 1);";
         $this->db->_query($sql);
     }
 */
}