<?php

/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 19-06-2017
 */
class works
{
    private $db;
    public $iWorkID = -1;
    public $vcImage;
    public $vcWorksTitel;
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
        $this->Table = "works";

  $this->iWorkID = $this->id = isset($_GET['iWorkID']) ? $_GET['iWorkID'] : 0;
if($this->iWorkID > 0){

        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iWorkID = " . $this->iWorkID;
        $valuedata = $this->db->_fetch_array($sql);

        $this->arrFormElm = array(
            "iWorkID" => array("label" => "", "type" => "hidden", "placeholder" => "", "required" => "data-requried='1'", "dbname" => "iWorkID" ,"Filter" => FILTER_SANITIZE_NUMBER_INT, "value" => $valuedata[0]["iWorkID"]),
            "vcWorkTitel" => array("label" => "works Titel", "type" => "text", "placeholder" => "works Titel", "required" => "data-requried='1'", "dbname" => "vcWorkTitel", "Filter" => FILTER_SANITIZE_STRING, "value" => $valuedata[0]["vcWorkTitel"]),
            "txWorkDescprition" => array("label" => "Kort Beskrivelse", "type" => "textarea", "placeholder" => "Kort Beskrivelse", "required" => "data-requried='1'", "dbname" => "txWorkDescprition", "Filter" => FILTER_DEFAULT, "value" => $valuedata[0]["txWorkDescprition"]),
            "vcImage" => array("label" => "", "type" => "hidden", "placeholder" => "vcImage1", "required" => "data-requried='1'", "dbname" => "vcImage1", "Filter" => FILTER_SANITIZE_STRING, "value" =>$valuedata[0]["vcImage"]),

        );
}else{
  $this->arrFormElm = array(
      "iWorkID" => array("label" => "", "type" => "hidden", "placeholder" => "", "required" => "data-requried='1'", "dbname" => "iWorkID" ,"Filter" => FILTER_SANITIZE_NUMBER_INT, "value" => $this->iWorkID),
      "vcWorkTitel" => array("label" => "works Titel", "type" => "text", "placeholder" => "works Titel", "required" => "data-requried='1'", "dbname" => "vcWorkTitel", "Filter" => FILTER_SANITIZE_STRING, "value" => ""),
      "txWorkDescprition" => array("label" => "Kort Beskrivelse", "type" => "textarea", "placeholder" => "Kort Beskrivelse", "required" => "data-requried='1'", "dbname" => "txWorkDescprition", "Filter" => FILTER_DEFAULT, "value" => ""),
      "vcImage" => array("label" => "", "type" => "hidden", "placeholder" => "vcImage1", "required" => "data-requried='1'", "dbname" => "vcImage1", "Filter" => FILTER_SANITIZE_STRING, "value" => ""),

  );
}



    }
    public function getCategory($iCategoryID)
    {
    $sql = "SELECT * from $this->Table sp INNER JOIN shopcatprodrel sr ON sr.iWorkID = sp.iWorkID WHERE sr.iCategoryID = $iCategoryID";

        return $this->db->_fetch_array($sql);
    }




    public function GetItem($iWorkID)
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iWorkID = " . $iWorkID;
        return $this->db->_fetch_array($sql);
    }

    public function GetSelect()
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0";
        return $this->db->_fetch_array($sql, array());
    }

    public function GetList()
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0";
        //Shows Column names from "works"
        $sqlLabels = "SHOW FULL COLUMNS FROM $this->Table";
        $this->arrLabels = $this->db->_fetch_array($sqlLabels, array());
        $this->arrValues = $this->db->_fetch_array($sql, array());

        //UNSET = Columns to avoid in LIST
        $this->unset = array(
            "iWorkID",
            "daCreated",
            "iSuspended",
            "iDeleted",

        );
    }

    public function getDetails($id)
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iWorkID = ?";
        //Shows Column names from "$this->table"
        $sqlLabels = "SHOW FULL COLUMNS FROM $this->Table";
        $this->arrLabels = $this->db->_fetch_array($sqlLabels, array());
        $this->arrValues = $this->db->_fetch_array($sql, array($id));

        //UNSET = Columns to avoid in LIST EDIT FOR DIFFRENT USE!
        $this->unset = array(
            "iWorkID",
            "daCreated",
            "iSuspended",
            "iDeleted",
        );
    }

    /**
     * Get works
     * change $this->"class for use"
     * Used for edit mode
     */
    public function getworks($id)
    {
        $this->iWorkID = $id;
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND $id = ?";
        $row = $this->db->_fetch_array($sql, array($id));
        foreach ($row as $key => $value) {
            $this->$key = $value;
        }
        return $row;
    }

    public function Footerworks($limit)
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 ORDER BY daCreated DESC LIMIT $limit";
        $row = $this->db->_fetch_array($sql, array());
        foreach ($row[0] as $key => $value) {
            $this->$key = $value;
        }
    }

    public function Updateworks($iWorkID)
    {
        //unsets iWorkID from the array
        unset($this->arrFormElm["iWorkID"]);

        //MAkes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {
            $f[$key] = filter_input(INPUT_POST, $key, $value["Filter"]);
        }
        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $params = array_values($f);

        //iWorkID = iWorkID
        $this->iWorkID = $iWorkID;

        //awesome SQL that makes an string that looks like this
        //"UPDATE works SET vcTitle = ?, vcLastName = ?, vcworksName = ?, vcPassword = ?, vcAddress = ?, iZip = ?, vcCity = ?, vcEmail = ?, vcPhone1 = ?, vcPhone2 = ? WHERE iWorkID = 12"
        echo $sql = "UPDATE $this->Table SET " . implode(array_keys($this->arrFormElm), " = ?, ") . " = ? WHERE iWorkID = $this->iWorkID";
        return $this->db->_query($sql, $params);
    }

    public function Deleteworks($iWorkID)
    {
        $this->iWorkID = $iWorkID;
        //SQL that sets iDeleted to 1
        $sql = "UPDATE $this->Table SET iDeleted = 1 WHERE iWorkID = ?";
        return $this->db->_query($sql, array($iWorkID));
    }

    Public function Createworks($iWorkID)
    {

        //unsets iWorkID from the array
        unset($this->arrFormElm["iWorkID"]);
        unset($this->arrFormElm["daCreated"]);
        $f = array();
        //Makes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {
            $f[$key] = isset($_POST[$key]) ? $_POST[$key] : NULL;
            $f[$key] = filter_var($f[$key], $value["Filter"]);
        }


        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $params = array_values($f);

        //iWorkID = iWorkID
        $this->iWorkID = $iWorkID;

        //awesome SQL that makes an string that looks like this
        //"UPDATE works SET vcTitle = ?, vcLastName = ?, vcworksName = ?, vcPassword = ?, vcAddress = ?, iZip = ?, vcCity = ?, vcEmail = ?, vcPhone1 = ?, vcPhone2 = ? WHERE iWorkID = 12"
        echo $sql = "INSERT INTO $this->Table SET " . implode(array_keys($this->arrFormElm), " = ?, ") . " = ?, daCreated = " . time();
        return $this->db->_query($sql, $params);
    }
}
