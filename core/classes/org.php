<?php

/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 30-01-2017
 */
class org
{

    private $db;
    public $iOrgID = -1;
    public $vcImage;
    public $vcOrgName;
    public $vcAddress;
    public $iZip;
    public $vcCity;
    public $vcEmail;
    public $vcPhone1;
    public $vcPhone2;

    public $daCreated;
    public $iDeleted;

    public $arrFormElms = array();
    public $arrLabels = array();
    public $arrValues = array();
    public $arrGroups = array();
    public $unset = array();


    public function __construct()
    {

        global $db;
        $this->db = $db;
        //$this->CreateTable();
        $this->Table = "org";

        $this->arrFormElm = array(
            "iOrgID" => array("label" => "", "type" => "hidden", "placeholder" => "", "required" => "data-requried='1'", "dbname" => "iUserID", "Filter" => FILTER_SANITIZE_NUMBER_INT),
            "vcOrgName" => array("label" => "First Name", "type" => "text", "placeholder" => "First name", "required" => "data-requried='1'", "dbname" => "vcFirstName", "Filter" => FILTER_SANITIZE_STRING),
            "vcAddress" => array("label" => "Last Name", "type" => "text", "placeholder" => "Last name", "required" => "data-requried='1'", "dbname" => "vcLastName", "Filter" => FILTER_SANITIZE_STRING),
            "iZip" => array("label" => "Zip code", "type" => "text", "placeholder" => "Zip Code", "required" => "data-requried='1'", "dbname" => "iZip", "Filter" => FILTER_SANITIZE_NUMBER_INT),
            "vcCity" => array("label" => "City", "type" => "text", "placeholder" => "City", "required" => "data-requried='1'", "dbname" => "vcCity", "Filter" => FILTER_SANITIZE_STRING),
            "vcCountry" => array("label" => "Land", "type" => "text", "placeholder" => "Land", "required" => "data-requried='1'", "dbname" => "vcCountry", "Filter" => FILTER_SANITIZE_STRING),
            "vcEmail" => array("label" => "Email", "type" => "email", "placeholder" => "Email", "required" => "data-requried='1'", "dbname" => "vcEmail", "Filter" => FILTER_SANITIZE_STRING),
            "vcPhone1" => array("label" => "Phone Number", "type" => "tel", "placeholder" => "Phone Number", "required" => "data-requried='1'", "dbname" => "vcPhone1", "Filter" => FILTER_SANITIZE_NUMBER_INT),
            "daCreated" => array("label" => "", "type" => "hidden", "placeholder" => "", "required" => "data-requried='1'", "dbname" => "daCreated", "Filter" => FILTER_SANITIZE_NUMBER_INT),
        );
    }

    /**
     * @param $iItemID
     * get a single record
     */

    public function GetSelect()
    {
        $sql = "select * from $this->Table WHERE iDeleted = 0";
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
        $this->unset = array();
    }

    public function getDetails($iOrgID)
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iOrgID = ?";
        //Shows Column names from "user"
        $sqlLabels = "SHOW FULL COLUMNS FROM $this->Table";
        $this->arrLabels = $this->db->_fetch_array($sqlLabels, array());
        $this->arrValues = $this->db->_fetch_array($sql, array($iOrgID));

        //UNSET = Columns to avoid in LIST
        $this->unset = array();
    }


    public
    function UpdateUser($iOrgID)
    {
        //unsets IUserID from the array
        unset($this->arrFormElm["iOrgID"]);

        //MAkes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {
            $f[$key] = filter_input(INPUT_POST, $key, $value["Filter"]);
        }
        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $params = array_values($f);

        //iUserID = IUserID
        $this->iOrgID = $iOrgID;

        //awesome SQL that makes an string that looks like this
        //"UPDATE user SET vcFirstName = ?, vcLastName = ?, vcUserName = ?, vcPassword = ?, vcAddress = ?, iZip = ?, vcCity = ?, vcEmail = ?, vcPhone1 = ?, vcPhone2 = ? WHERE iUserID = 12"
        echo $sql = "UPDATE $this->Table SET " . implode(array_keys($this->arrFormElm), " = ?, ") . " = ? WHERE iOrgID = $this->iOrgID";
        return $this->db->_query($sql, $params);
    }

    public
    function DeleteUser($iOrgID)
    {
        $this->iOrgID = $iOrgID;
        //SQL that sets iDeleted to 1
        $sql = "UPDATE $this->Table SET iDeleted = 1 WHERE iOrgID = ?";
        return $this->db->_query($sql, array($iOrgID));
    }

    Public
    function CreateUser($iOrgID)
    {
        //unsets IUserID from the array
        unset($this->arrFormElm["iOrgID"]);
        unset($this->arrFormElm["daCreated"]);

        //Makes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {
            $f[$key] = filter_input(INPUT_POST, $key, $value["Filter"]);
        }
        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $params = array_values($f);

        //iUserID = IUserID
        $this->iOrgID = $iOrgID;

        //awesome SQL that makes an string that looks like this
        //"UPDATE user SET vcFirstName = ?, vcLastName = ?, vcUserName = ?, vcPassword = ?, vcAddress = ?, iZip = ?, vcCity = ?, vcEmail = ?, vcPhone1 = ?, vcPhone2 = ? WHERE iUserID = 12"
        $sql = "INSERT INTO $this->Table SET " . implode(array_keys($this->arrFormElm), " = ?, ") . " = ?, daCreated = " . time();
        return $this->db->_query($sql, $params);
    }

    /*  public function CreateTable()
      {
          $sql = "CREATE TABLE IF NOT EXISTS `org` (
                  `iOrgID` bigint(20) NOT NULL AUTO_INCREMENT,
                  `vcOrgName` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Navn',
                  `vcAddress` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Adresse',
                  `iZip` mediumint(10) NOT NULL COMMENT 'Post nummer',
                  `txDescription` text CHARACTER SET utf8 NOT NULL COMMENT 'Beskrivelse',
                  `vcCity` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'By',
                  `vcEmail` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'Email',
                  `vcPhone1` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Telefon',
                  `daCreated` bigint(20) NOT NULL COMMENT 'Oprettet',
                  `iDeleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Slettet',
                  `vcCountry` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Land',
                  PRIMARY KEY (`iOrgID`)
                  ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";


          $this->db->_query($sql);
          $this->CreateInput();
      }

      public function CreateInput()
      {
          $sql = "INSERT IGNORE INTO `org` (`iOrgID`, `vcOrgName`, `vcAddress`, `iZip`, `txDescription`, `vcCity`, `vcEmail`, `vcPhone1`, `daCreated`, `iDeleted`, `vcCountry`) VALUES
                  (1, 'TestFirma', 'Blåkildevej 58', 9220, 'TestFirma of the year!', 'Aalborg', 'roloffqt@gmail.com', '+4522950302', 1487144446, 0, 'Danmark');";
          $this->db->_query($sql);

      }*/
}
