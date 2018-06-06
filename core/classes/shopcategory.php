<?php

class shopcategory
{
    private $db;

    public $iCategoryID = -1;
    public $vcShortDesc;
    public $txLongDesc;
    public $vcTitle;
    public $iParentID = -1;
    public $vcImage;

    public $arrColumns = array();
    public $arrLabels = array();
    public $arrValues = array();
    public $unset = array();

    public function __construct()
    {
        global $db;
        $this->db = $db;
        $this->CreateTable();
        $this->Table = "shopcategory";

        $this->iCategoryID = $this->id = isset($_GET['iCategoryID']) ? $_GET['iCategoryID'] : 0;
      if($this->iCategoryID > 0){

              $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iWorkID = " . $this->iCategoryID;
              $valuedata = $this->db->_fetch_array($sql);

        $this->arrFormElm = array(
            "iCategoryID" => array("label" => "", "type" => "hidden", "placeholder" => "", "required" => "data-requried='1'", "dbname" => "iCategoryID", "Filter" => FILTER_SANITIZE_NUMBER_INT, "value" => $valuedata[0]["iCategoryID"]),
            "iParentID" => array("label" => "Kategory", "type" => "select", "placeholder" => "", "required" => "data-requried='1'", "dbname" => "iParentID", "Filter" => FILTER_SANITIZE_NUMBER_INT, "value" => $valuedata[0]["iParentID"]),
            "vcTitle" => array("label" => "Titel", "type" => "text", "placeholder" => "vcTitle", "required" => "data-requried='1'", "dbname" => "vcTitle", "Filter" => FILTER_SANITIZE_STRING, "value" => $valuedata[0]["vcTitle"]),
            "txDesc" => array("label" => "Long desc", "type" => "textarea", "placeholder" => "txDesc", "required" => "data-requried='1'", "dbname" => "txDesc", "Filter" => FILTER_SANITIZE_STRING, "value" => $valuedata[0]["txDesc"]),
            "vcImage" => array("label" => "", "type" => "hidden", "placeholder" => "Lang Beskrielvse", "required" => "data-requried='1'", "dbname" => "vcImage", "Filter" => FILTER_SANITIZE_STRING, "value" => $valuedata[0]["vcImage"]),
        );
}else{
  $this->arrFormElm = array(
      "iCategoryID" => array("label" => "", "type" => "hidden", "placeholder" => "", "required" => "data-requried='1'", "dbname" => "iCategoryID", "Filter" => FILTER_SANITIZE_NUMBER_INT, "value" => $this->iCategoryID),
      "iParentID" => array("label" => "Kategory", "type" => "select", "placeholder" => "", "required" => "data-requried='1'", "dbname" => "iParentID", "Filter" => FILTER_SANITIZE_NUMBER_INT, "value" => ""),
      "vcTitle" => array("label" => "Titel", "type" => "text", "placeholder" => "vcTitle", "required" => "data-requried='1'", "dbname" => "vcTitle", "Filter" => FILTER_SANITIZE_STRING, "value" => ""),
      "txDesc" => array("label" => "Long desc", "type" => "textarea", "placeholder" => "txDesc", "required" => "data-requried='1'", "dbname" => "txDesc", "Filter" => FILTER_SANITIZE_STRING, "value" => ""),
      "vcImage" => array("label" => "", "type" => "hidden", "placeholder" => "Lang Beskrielvse", "required" => "data-requried='1'", "dbname" => "vcImage", "Filter" => FILTER_SANITIZE_STRING, "value" => ""),
  );
}

    }

    /**
     * Selects all from $this->Table
     * used for making select boxes
     */
    public function GetSelect()
    {
        $sql = "select * from $this->Table WHERE iDeleted = 0";
        return $this->db->_fetch_array($sql, array());
    }


    public function GetItem($iCategoryID)
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iCategoryID = " . $iCategoryID;
        return $this->db->_fetch_array($sql);
    }

    public function getopts($iParentID, &$arrGroups = array(), $strPadding = "")
    {

        $params = array($iParentID);
        $strSelect = "SELECT iCategoryID, vcTitle FROM " . $this->Table . " " .
            "WHERE iParentID = ? " .
            "AND iDeleted = 0 " .
            "ORDER BY iSortNum";
        $row = $this->db->_fetch_array($strSelect, $params);
        foreach ($row as $key => $values) {
            $arrGroups[] = array($values["iCategoryID"], $values["vcTitle"], $strPadding);
            self::getopts($values["iCategoryID"], $arrGroups, $strPadding . "   &nbsp&nbsp&nbsp&nbsp");
        }
        return $arrGroups;
    }


    /**
     * GetDetails
     * change SQL "id" for class use"
     * $id = ID of class used
     * change Unset for custom use
     * used for details mode
     */
    public function getDetails($id)
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iCategoryID = ?";
        //Shows Column names from "$this->table"        $sqlLabels = "SHOW FULL COLUMNS FROM $this->Table";
        $this->arrLabels = $this->db->_fetch_array($sqlLabels, array());
        $this->arrValues = $this->db->_fetch_array($sql, array($id));

        //UNSET = Columns to avoid in LIST EDIT FOR DIFFRENT USE!
        $this->unset = array(
            "iCategoryID",
            "daCreated",
            "iSuspended",
            "iDeleted",
            "iIsActive",
            "vcImage",

        );
    }

    /**
     * GetList
     * Make unset diffrent for each class
     * Used for FrontpageList
     */
    public function GetList()
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0";
        //Shows Column names from "Event"
        $sqlLabels = "SHOW FULL COLUMNS FROM $this->Table";
        $this->arrLabels = $this->db->_fetch_array($sqlLabels, array());
        $this->arrValues = $this->db->_fetch_array($sql, array());

        //UNSET = Columns to avoid in LIST
        $this->unset = array(
            "iCategoryID",
            "daCreated",
            "iDeleted",
            "vcImage",
            "iParentID",
            "iIsActive"
        );
    }

    /**
     * GetCategory
     * change $this->"class for use"
     * $id = ID of class used
     * Used for edit mode
     */
    public function getCategory($id)
    {
        $this->iCategoryID = $id;
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iCategoryID = ?";
        $row = $this->db->_fetch_array($sql, array($id));
        foreach ($row[0] as $key => $value) {
            $this->$key = $value;
        }
    }


    /**
     * Createobj
     * change $this->arrFormElm "class for use"
     * change $this->"current class" = $id
     * $id = ID of class used
     * Used to Create a db entri
     */
    Public function CreateObj($id)
    {

        //unsets iCategoryID from the array
        unset($this->arrFormElm["iCategoryID"]);
        unset($this->arrFormElm["daCreated"]);

        //Makes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {
            $f[$key] = filter_input(INPUT_POST, $key, $value["Filter"]);
        }

        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $params = array_values($f);

        //Current class = ID
        $this->iCategoryID = $id;

        //awesome SQL that makes an string that looks like this
        //"UPDATE Event SET vcTitle = ?, vcLastName = ?, vcEventName = ?, vcPassword = ?, vcAddress = ?, iZip = ?, vcCity = ?, vcEmail = ?, vcPhone1 = ?, vcPhone2 = ? WHERE iCategoryID = 12"
        echo $sql = "INSERT INTO $this->Table SET " . implode(array_keys($this->arrFormElm), " = ?, ") . " = ?, daCreated = " . time();
        return $this->db->_query($sql, $params);
    }

    /**
     * Updateobj
     * change $this->arrFormElm "class for use"
     * change $this->"current class" = $id
     * $id = ID of class used
     * Used to update a db entrie
     */
    public function UpdateObj($id)
    {
        //unsets iCategoryID from the array
        unset($this->arrFormElm["iCategoryID"]);

        //MAkes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {
            $f[$key] = filter_input(INPUT_POST, $key, $value["Filter"]);
        }
        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $params = array_values($f);

        //iCategoryID = iCategoryID
        $this->iCategoryID = $id;

        //awesome SQL that makes an string that looks like this
        //"UPDATE Event SET vcTitle = ?, vcLastName = ?, vcEventName = ?, vcPassword = ?, vcAddress = ?, iZip = ?, vcCity = ?, vcEmail = ?, vcPhone1 = ?, vcPhone2 = ? WHERE iCategoryID = 12"
        echo $sql = "UPDATE $this->Table SET " . implode(array_keys($this->arrFormElm), " = ?, ") . " = ? WHERE iCategoryID = $this->iCategoryID";
        return $this->db->_query($sql, $params);
    }


    /**
     * $id = Class id
     * change $this->"Class id" to whatever class your using
     * change SQL from "Classid" -> new "Classid"
     * Deletes db entri by setting iDeleted = 1
     */
    public function delete($id)
    {
        $this->iCategoryID = $id;
        //SQL that sets iDeleted to 1
        $sql = "UPDATE $this->Table SET iDeleted = 1 WHERE iCategoryID = ?";
        return $this->db->_query($sql, array($id));
    }

    public function CreateTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `shopcategory` (
                  `iCategoryID` bigint(20) NOT NULL AUTO_INCREMENT,
                  `iParentID` bigint(20) NOT NULL DEFAULT '-1' COMMENT 'Over Kategory',
                  `vcTitle` varchar(50) COLLATE utf8_bin NOT NULL COMMENT 'Titel',
                  `txDesc` text COLLATE utf8_bin NOT NULL COMMENT 'Beskrivlse',
                  `vcImage` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Billede',
                  `daCreated` bigint(20) NOT NULL COMMENT 'Oprettet',
                  `iIsActive` tinyint(2) NOT NULL DEFAULT '1' COMMENT 'Aktiv',
                  `iDeleted` tinyint(2) NOT NULL DEFAULT '0',
                  `iSortNum` int(11) NOT NULL DEFAULT '0' COMMENT 'Sorterings nummer',
                  PRIMARY KEY(`iCategoryID`)
                  ) ENGINE = MyISAM AUTO_INCREMENT = 16 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;";
        $this->db->_query($sql);


        $sql = "CREATE TABLE IF NOT EXISTS `shopcatprodrel` (
              `iRelID` bigint(50) NOT NULL AUTO_INCREMENT,
              `iProductID` bigint(50) NOT NULL DEFAULT '0',
              `iCategoryID` bigint(50) NOT NULL DEFAULT '0',
              PRIMARY KEY(`iRelID`)
              ) ENGINE = MyISAM AUTO_INCREMENT = 11 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;";
        $this->db->_query($sql);
    }


}
