<?php

/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 19-06-2017
 */
class about
{
    private $db;
    public $iAboutID = -1;
    public $vcImage;
    public $vcTitle;
    public $vcShortDesc;
    public $txLongDesc;

    public $daExpireDate;
    public $daStartDate;
    public $vcPlace;
    public $iDonate;
    public $vcDate;
    public $daCreated;
    public $iSuspended;
    public $iDeleted;
    Public $vcImagePath;

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
        $this->Table = "about";


          $this->iAboutID = $this->id = isset($_GET['iAboutID']) ? $_GET['iAboutID'] : 0;
        if($this->iAboutID > 0){

                $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iAboutID = " . $this->iAboutID;
                $valuedata = $this->db->_fetch_array($sql);
                $this->arrFormElm = array(
                    "iAboutID" => array("label" => "", "type" => "hidden", "placeholder" => "", "required" => "data-requried='1'", "dbname" => "iAboutID" ,"Filter" => FILTER_SANITIZE_NUMBER_INT, "value" => $this->iAboutID),
                    "vcAboutTitel" => array("label" => "Abouts Titel", "type" => "text", "placeholder" => "Abouts Titel", "required" => "data-requried='1'", "dbname" => "vcAboutTitel", "Filter" => FILTER_SANITIZE_STRING, "value" => $valuedata[0]["vcAboutTitel"]),
                    "txAboutDescprition" => array("label" => "Autobiography", "type" => "textarea", "placeholder" => "Autobiography", "required" => "data-requried='1'", "dbname" => "txAboutDescprition", "Filter" =>  FILTER_SANITIZE_STRING, "value" => $valuedata[0]["txAboutDescprition"]),
                    "vcEducation" => array("label" => "Education", "type" => "text", "placeholder" => "Education", "required" => "data-requried='1'", "dbname" => "vcEducation", "Filter" =>  FILTER_SANITIZE_STRING, "value" => $valuedata[0]["vcEducation"]),
                    "vcName" => array("label" => "Artist Name", "type" => "text", "placeholder" => "Artist Name", "required" => "data-requried='1'", "dbname" => "vcName", "Filter" => FILTER_SANITIZE_STRING, "value" => $valuedata[0]["vcName"]),
                    "iDateofbirth" => array("label" => "Date of birth", "type" => "text", "placeholder" => "Date of birth", "required" => "data-requried='1'", "dbname" => "iDateofbirth", "Filter" => FILTER_SANITIZE_NUMBER_INT, "value" => $valuedata[0]["iDateofbirth"]),
                    "vcImage" => array("label" => "", "type" => "hidden", "placeholder" => "vcImage1", "required" => "data-requried='1'", "dbname" => "vcImage1", "Filter" => FILTER_SANITIZE_STRING, "value" => $valuedata[0]["vcImage"]),

                );
        }else{
          $this->arrFormElm = array(
                "iAboutID" => array("label" => "", "type" => "hidden", "placeholder" => "", "required" => "data-requried='1'", "dbname" => "iAboutID" ,"Filter" => FILTER_SANITIZE_NUMBER_INT, "value" => $this->iAboutID),
                "vcAboutTitel" => array("label" => "Abouts Titel", "type" => "text", "placeholder" => "Abouts Titel", "required" => "data-requried='1'", "dbname" => "vcAboutTitel", "Filter" => FILTER_SANITIZE_STRING, "value" => ""),
                "txAboutDescprition" => array("label" => "autobiography", "type" => "textarea", "placeholder" => "autobiography", "required" => "data-requried='1'", "dbname" => "txAboutDescprition", "Filter" =>  FILTER_SANITIZE_STRING, "value" => ""),
                "vcEducation" => array("label" => "Education", "type" => "text", "placeholder" => "Education", "required" => "data-requried='1'", "dbname" => "vcEducation", "Filter" =>  FILTER_SANITIZE_STRING, "value" => ""),
                "vcName" => array("label" => "Artist Name", "type" => "text", "placeholder" => "Artist Name", "required" => "data-requried='1'", "dbname" => "vcName", "Filter" => FILTER_SANITIZE_STRING, "value" => ""),
                "iDateofbirth" => array("label" => "Date of birth", "type" => "text", "placeholder" => "Date of birth", "required" => "data-requried='1'", "dbname" => "iDateofbirth", "Filter" => FILTER_SANITIZE_NUMBER_INT, "value" => ""),
                "vcImage" => array("label" => "", "type" => "hidden", "placeholder" => "vcImage1", "required" => "data-requried='1'", "dbname" => "vcImage1", "Filter" => FILTER_SANITIZE_STRING, "value" => ""),

          );
        }
    }

    public function GetItem($iAboutID)
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iaboutID = " . $iAboutID;
        return $this->db->_fetch_array($sql);
    }

    public function GetSelect($limit = 100)
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 LIMIT $limit";
        return $this->db->_fetch_array($sql, array());
    }

    public function GetList()
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0";
        //Shows Column names from "about"
        $sqlLabels = "SHOW FULL COLUMNS FROM $this->Table";
        $this->arrLabels = $this->db->_fetch_array($sqlLabels, array());
        $this->arrValues = $this->db->_fetch_array($sql, array());

        //UNSET = Columns to avoid in LIST
        $this->unset = array(
            "iAboutID",

            "daCreated",
            "iSuspended",
            "iDeleted",

        );
    }

    public function getDetails($id)
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iaboutID = ?";
        //Shows Column names from "$this->table"
        $sqlLabels = "SHOW FULL COLUMNS FROM $this->Table";
        $this->arrLabels = $this->db->_fetch_array($sqlLabels, array());
        $this->arrValues = $this->db->_fetch_array($sql, array($id));

        //UNSET = Columns to avoid in LIST EDIT FOR DIFFRENT USE!
        $this->unset = array(
            "iaboutID",
            "daCreated",
            "iSuspended",
            "iDeleted",
        );
    }

    /**
     * Get about
     * change $this->"class for use"
     * Used for edit mode
     */
    public function getabout($id)
    {
        $this->iaboutID = $id;
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND $id = ?";
        $row = $this->db->_fetch_array($sql, array($id));
        foreach ($row as $key => $value) {
            $this->$key = $value;
        }
        return $row;
    }

    public function Footerabout($limit)
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 ORDER BY daCreated DESC LIMIT $limit";
        $row = $this->db->_fetch_array($sql, array());
        foreach ($row[0] as $key => $value) {
            $this->$key = $value;
        }
    }

    public function Updateabout($iAboutID)
    {
        //unsets iaboutID from the array
        unset($this->arrFormElm["iaboutID"]);

        //MAkes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {
            $f[$key] = filter_input(INPUT_POST, $key, $value["Filter"]);
        }
        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $params = array_values($f);

        //iaboutID = iaboutID
        $this->iaboutID = $iAboutID;

        //awesome SQL that makes an string that looks like this
        //"UPDATE about SET vcTitle = ?, vcLastName = ?, vcaboutName = ?, vcPassword = ?, vcAddress = ?, iZip = ?, vcCity = ?, vcEmail = ?, vcPhone1 = ?, vcPhone2 = ? WHERE iaboutID = 12"
        echo $sql = "UPDATE $this->Table SET " . implode(array_keys($this->arrFormElm), " = ?, ") . " = ? WHERE iaboutID = $this->iaboutID";
        return $this->db->_query($sql, $params);
    }

    public function Deleteabout($iAboutID)
    {
        $this->iaboutID = $iAboutID;
        //SQL that sets iDeleted to 1
        $sql = "UPDATE $this->Table SET iDeleted = 1 WHERE iaboutID = ?";
        return $this->db->_query($sql, array($iAboutID));
    }

    Public function Createabout($iAboutID)
    {

        //unsets iaboutID from the array
        unset($this->arrFormElm["iaboutID"]);
        unset($this->arrFormElm["daCreated"]);
        $f = array();
        //Makes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {
            $f[$key] = isset($_POST[$key]) ? $_POST[$key] : NULL;
            $f[$key] = filter_var($f[$key], $value["Filter"]);
        }


        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $params = array_values($f);

        //iaboutID = iaboutID
        $this->iaboutID = $iAboutID;

        //awesome SQL that makes an string that looks like this
        //"UPDATE about SET vcTitle = ?, vcLastName = ?, vcaboutName = ?, vcPassword = ?, vcAddress = ?, iZip = ?, vcCity = ?, vcEmail = ?, vcPhone1 = ?, vcPhone2 = ? WHERE iaboutID = 12"
        echo $sql = "INSERT INTO $this->Table SET " . implode(array_keys($this->arrFormElm), " = ?, ") . " = ?, daCreated = " . time();
        return $this->db->_query($sql, $params);
    }
}
