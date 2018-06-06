<?php

/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 19-06-2017
 */
class news
{
    private $db;
    public $iNewsID = -1;
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
        $this->Table = "news";

        $this->iNewsID = $this->id = isset($_GET['iNewsID']) ? $_GET['iNewsID'] : 0;
      if($this->iNewsID > 0){

              $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iNewsID = " . $this->iNewsID;
              $valuedata = $this->db->_fetch_array($sql);

              $this->arrFormElm = array(
                "iNewsID" => array("label" => "", "type" => "hidden", "placeholder" => "", "required" => "data-requried='1'", "dbname" => "iNewsID" ,"Filter" => FILTER_SANITIZE_NUMBER_INT, "value" => $this->iNewsID),
                "vcNewsTitel" => array("label" => "works Titel", "type" => "text", "placeholder" => "works Titel", "required" => "data-requried='1'", "dbname" => "vcNewsTitel", "Filter" => FILTER_SANITIZE_STRING, "value" => $valuedata[0]["vcNewsTitel"]),
                "txNewsDescription" => array("label" => "Kort Beskrivelse", "type" => "textarea", "placeholder" => "Kort Beskrivelse", "required" => "data-requried='1'", "dbname" => "txNewsDescription", "Filter" => FILTER_DEFAULT, "value" => $valuedata[0]["txNewsDescription"]),
                "vcImage" => array("label" => "", "type" => "hidden", "placeholder" => "vcImage1", "required" => "data-requried='1'", "dbname" => "vcImage1", "Filter" => FILTER_SANITIZE_STRING, "value" => $valuedata[0]["vcImage"]),

              );
      }else{
        $this->arrFormElm = array(
            "iNewsID" => array("label" => "", "type" => "hidden", "placeholder" => "", "required" => "data-requried='1'", "dbname" => "iNewsID" ,"Filter" => FILTER_SANITIZE_NUMBER_INT, "value" => $this->iNewsID),
            "vcNewsTitel" => array("label" => "works Titel", "type" => "text", "placeholder" => "works Titel", "required" => "data-requried='1'", "dbname" => "vcNewsTitel", "Filter" => FILTER_SANITIZE_STRING, "value" => ""),
            "txNewsDescription" => array("label" => "Kort Beskrivelse", "type" => "textarea", "placeholder" => "Kort Beskrivelse", "required" => "data-requried='1'", "dbname" => "txNewsDescription", "Filter" => FILTER_DEFAULT, "value" => ""),
            "vcImage" => array("label" => "", "type" => "hidden", "placeholder" => "vcImage", "required" => "data-requried='1'", "dbname" => "vcImage", "Filter" => FILTER_SANITIZE_STRING, "value" => ""),

        );
      }
    }

    public function GetItem($iNewsID)
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iNewsID = " . $iNewsID;
        return $this->db->_fetch_array($sql);
    }

    public function GetSelect($limit = 100)
    {
      $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 ORDER BY daCreated DESC LIMIT $limit ";
        return $this->db->_fetch_array($sql, array());
    }

    public function GetList()
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0";
        //Shows Column names from "News"
        $sqlLabels = "SHOW FULL COLUMNS FROM $this->Table";
        $this->arrLabels = $this->db->_fetch_array($sqlLabels, array());
        $this->arrValues = $this->db->_fetch_array($sql, array());

        //UNSET = Columns to avoid in LIST
        $this->unset = array(
            "iNewsID",
            "daCreated",
            "iSuspended",
            "iDeleted",

        );
    }

    public function getDetails($id)
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iNewsID = ?";
        //Shows Column names from "$this->table"
        $sqlLabels = "SHOW FULL COLUMNS FROM $this->Table";
        $this->arrLabels = $this->db->_fetch_array($sqlLabels, array());
        $this->arrValues = $this->db->_fetch_array($sql, array($id));

        //UNSET = Columns to avoid in LIST EDIT FOR DIFFRENT USE!
        $this->unset = array(
            "iNewsID",
            "daCreated",
            "iSuspended",
            "iDeleted",
        );
    }

    /**
     * Get News
     * change $this->"class for use"
     * Used for edit mode
     */
    public function getNews($id)
    {
        $this->iNewsID = $id;
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND $id = ?";
        $row = $this->db->_fetch_array($sql, array($id));
        foreach ($row as $key => $value) {
            $this->$key = $value;
        }
        return $row;
    }

    public function FooterNews($limit)
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 ORDER BY daCreated DESC LIMIT $limit";
        $row = $this->db->_fetch_array($sql, array());
        foreach ($row[0] as $key => $value) {
            $this->$key = $value;
        }
    }

    public function UpdateNews($iNewsID)
    {
        //unsets INewsID from the array
        unset($this->arrFormElm["iNewsID"]);

        //MAkes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {
            $f[$key] = filter_input(INPUT_POST, $key, $value["Filter"]);
        }
        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $params = array_values($f);

        //iNewsID = INewsID
        $this->iNewsID = $iNewsID;

        //awesome SQL that makes an string that looks like this
        //"UPDATE News SET vcTitle = ?, vcLastName = ?, vcNewsName = ?, vcPassword = ?, vcAddress = ?, iZip = ?, vcCity = ?, vcEmail = ?, vcPhone1 = ?, vcPhone2 = ? WHERE iNewsID = 12"
        echo $sql = "UPDATE $this->Table SET " . implode(array_keys($this->arrFormElm), " = ?, ") . " = ? WHERE iNewsID = $this->iNewsID";
        return $this->db->_query($sql, $params);
    }

    public function DeleteNews($iNewsID)
    {
        $this->iNewsID = $iNewsID;
        //SQL that sets iDeleted to 1
        $sql = "UPDATE $this->Table SET iDeleted = 1 WHERE iNewsID = ?";
        return $this->db->_query($sql, array($iNewsID));
    }

    Public function CreateNews($iNewsID)
    {

        //unsets INewsID from the array
        unset($this->arrFormElm["iNewsID"]);
        unset($this->arrFormElm["daCreated"]);
        $f = array();
        //Makes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {
            $f[$key] = isset($_POST[$key]) ? $_POST[$key] : NULL;
            $f[$key] = filter_var($f[$key], $value["Filter"]);
        }


        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $params = array_values($f);

        //iNewsID = INewsID
        $this->iNewsID = $iNewsID;

        //awesome SQL that makes an string that looks like this
        //"UPDATE News SET vcTitle = ?, vcLastName = ?, vcNewsName = ?, vcPassword = ?, vcAddress = ?, iZip = ?, vcCity = ?, vcEmail = ?, vcPhone1 = ?, vcPhone2 = ? WHERE iNewsID = 12"
        echo $sql = "INSERT INTO $this->Table SET " . implode(array_keys($this->arrFormElm), " = ?, ") . " = ?, daCreated = " . time();
        return $this->db->_query($sql, $params);
    }
}
