<?php

/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 17-05-2017
 */
class image
{

    private $db;
    public $iImageID = -1;
    public $vcImage;
    public $vcImagePath;
    public $vcTitle;
    public $daCreated;
    public $iSuspended;
    public $iDeleted;

    public $arrFormElms = array();
    public $arrLabels = array();
    public $arrColumns = array();
    public $arrValues = array();
    public $unset = array();

    public function __construct()
    {
        global $db;
        $this->db = $db;
        // $this->CreateTable();
        $this->Table = "image";

        $this->arrFormElm = array(
            "iImageID" => array("label" => "", "type" => "hidden", "placeholder" => "", "required" => "data-requried='1'", "dbname" => "iImageID", "Filter" => FILTER_SANITIZE_NUMBER_INT),
            "vcTitle" => array("label" => "Billede Titel", "type" => "text", "placeholder" => "Billede Titel", "required" => "data-requried='1'", "dbname" => "vcTitle", "Filter" => FILTER_SANITIZE_NUMBER_INT),
            "vcImagePath" => array("label" => "Billede Titel", "type" => "select", "placeholder" => "Billede Titel", "required" => "data-requried='1'", "dbname" => "vcImagePath", "Filter" => FILTER_SANITIZE_NUMBER_INT),

            //Unneeded fields
            //"iSuspended" => array(),
            //"iDeleted" => array(),
            //"iOrgID" => array("label" => "", "type" => "hidden", "placeholder" => "",),
            //"vcImage" => array("label" => "Image", "shpwn", "type" => "text", "require" => "", "placeholder" => "Pick a image",),

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
        //Shows Column names from "image"
        $sqlLabels = "SHOW FULL COLUMNS FROM $this->Table";
        $this->arrLabels = $this->db->_fetch_array($sqlLabels, array());
        $this->arrValues = $this->db->_fetch_array($sql, array());

        //UNSET = Columns to avoid in LIST
        $this->unset = array(
            "iImageID",
            "iDeleted",
            "daCreated",
            "IisActive",

        );
    }

    public function getDetails($iImageID)
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iImageID = ?";
        //Shows Column names from "image"
        $sqlLabels = "SHOW FULL COLUMNS FROM $this->Table";
        $this->arrLabels = $this->db->_fetch_array($sqlLabels, array());
        $this->arrValues = $this->db->_fetch_array($sql, array($iImageID));

        //UNSET = Columns to avoid in LIST
        $this->unset = array(
            "iImageID",
            "iDeleted",
            "IisActive",

        );
    }

    public function getimage($iImageID)
    {
        $this->iImageID = $iImageID;
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iImageID = ?";
        $row = $this->db->_fetch_array($sql, array($this->iImageID));
        foreach ($row[0] as $key => $value) {
            $this->$key = $value;
        }
    }

    public function Updateimage($iImageID)
    {
        //unsets iImageID from the array
        unset($this->arrFormElm["iImageID"]);

        //MAkes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {
            $f[$key] = filter_input(INPUT_POST, $key, $value["Filter"]);
        }
        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $params = array_values($f);

        //iImageID = iImageID
        $this->iImageID = $iImageID;

        //awesome SQL that makes an string that looks like this
        //"UPDATE image SET vcFirstName = ?, vcLastName = ?, vcimageName = ?, vcPassword = ?, vcAddress = ?, iZip = ?, vcCity = ?, vcEmail = ?, vcPhone1 = ?, vcPhone2 = ? WHERE iImageID = 12"
        echo $sql = "UPDATE $this->Table SET " . implode(array_keys($this->arrFormElm), " = ?, ") . " = ? WHERE iImageID = $this->iImageID";
        return $this->db->_query($sql, $params);
    }

    public function Deleteimage($iImageID)
    {
        $this->iImageID = $iImageID;
        //SQL that sets iDeleted to 1
        $sql = "UPDATE $this->Table SET iDeleted = 1 WHERE iImageID = ?";
        return $this->db->_query($sql, array($iImageID));
    }

    public function Saveimage()
    {

    }

    Public function Createimage($iImageID)
    {
        //unsets iImageID from the array
        unset($this->arrFormElm["iImageID"]);
        unset($this->arrFormElm["daCreated"]);

        //Makes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {
            $f[$key] = filter_input(INPUT_POST, $key, $value["Filter"]);
        }

        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $params = array_values($f);

        //iImageID = iImageID
        $this->iImageID = $iImageID;

        //awesome SQL that makes an string that looks like this
        //"UPDATE image SET vcFirstName = ?, vcLastName = ?, vcimageName = ?, vcPassword = ?, vcAddress = ?, iZip = ?, vcCity = ?, vcEmail = ?, vcPhone1 = ?, vcPhone2 = ? WHERE iImageID = 12"
        $sql = "INSERT INTO $this->Table SET " . implode(array_keys($this->arrFormElm), " = ?, ") . " = ?, daCreated = " . time();
        return $this->db->_query($sql, $params);
    }


    /*   public function CreateTable()
       {
           $sql = "CREATE TABLE IF NOT EXISTS `image` (
               `iImageID` bigint(20) NOT NULL AUTO_INCREMENT,
               `vcTitle` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '0' COMMENT 'Billede Titel',
               `vcImagePath` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '0' COMMENT 'BilledeSti',
               `daCreated` bigint(20) NOT NULL DEFAULT '0' COMMENT 'Oprettet',
               `iDeleted` bigint(20) DEFAULT '0',
               PRIMARY KEY (`iImageID`)
             ) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
           $this->db->_query($sql);


           $sql = " CREATE TABLE IF NOT EXISTS `imagesession` (
       `vcSessionID` VARCHAR(32) NOT NULL DEFAULT '',
                 `iImageID` BIGINT(20) NOT NULL DEFAULT '0',
                 `iIpAddress` VARCHAR(24) NOT NULL DEFAULT '',
                 `iIsLoggedIn` TINYINT(1) NOT NULL DEFAULT '0',
                 `daLoginCreated` BIGINT(20) NOT NULL DEFAULT '0',
                 `daLastAction` BIGINT(20) NOT NULL DEFAULT '0')
                 COLLATE = 'utf8_general_ci'ENGINE = MyISAM";

           $this->db->_query($sql);
       }
   */
}