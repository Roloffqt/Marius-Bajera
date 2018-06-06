<?php

/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 30-01-2017
 */
class shopproduct
{


    private $db;
    public $iProductID;
    public $vcShortDesc;
    public $vcProductNumber;
    public $iPrice;
    public $iOfferPrice;
    public $iStock;
    public $iWeight;
    public $vcLink;
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
        $this->Table = "shopproduct";

        $this->arrFormElm = array(
            "iProductID" => array("label" => "", "type" => "hidden", "placeholder" => "", "required" => "data-requried='1'", "dbname" => "iProductID", "Filter" => FILTER_SANITIZE_NUMBER_INT),
            "vcTitle" => array("label" => "Product Navn", "type" => "text", "placeholder" => "vcTitle", "required" => "data-requried='1'", "dbname" => "vcTitle", "Filter" => FILTER_SANITIZE_STRING),
            "txDesc" => array("label" => "Desc", "type" => "textarea", "placeholder" => "txDesc", "required" => "data-requried='1'", "dbname" => "txDesc", "Filter" => FILTER_SANITIZE_STRING),
            "vcLink" => array("label" => "vcLink", "type" => "text", "placeholder" => "vcLink", "required" => "data-requried='1'", "dbname" => "vcLink", "Filter" => FILTER_SANITIZE_STRING),
            "txSpecfications" => array("label" => "txSpecfications", "type" => "textarea", "placeholder" => "txSpecfications", "required" => "data-requried='1'", "dbname" => "txSpecfications", "Filter" => FILTER_SANITIZE_STRING),
            "vcImage" => array("label" => "", "type" => "hidden", "placeholder" => "vcImage1", "required" => "data-requried='1'", "dbname" => "vcImage1", "Filter" => FILTER_SANITIZE_STRING),

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
            "iProductID",
            "txDesc",
            "vcImage",
            "daCreated",
            "iDeleted",
            "vcImage",
            "iIsActive",
            "vcLink",
            "txSpecfications",
            "txDescShort",


        );
    }

    public function getCategory($iCategoryID)
    {
        $sql = "SELECT * from shopcatprodrel sr INNER JOIN $this->Table sp ON sr.iProductID = sp.iProductID WHERE iCategoryID = $iCategoryID";
        return $this->db->_fetch_array($sql);
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
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iProductID = ?";
        //Shows Column names from "$this->table"
        $sqlLabels = "SHOW FULL COLUMNS FROM $this->Table";
        $this->arrLabels = $this->db->_fetch_array($sqlLabels, array());
        $this->arrValues = $this->db->_fetch_array($sql, array($id));

        //UNSET = Columns to avoid in LIST EDIT FOR DIFFRENT USE!
        $this->unset = array(
            "iProductID",
            "daCreated",
            "iSuspended",
            "iDeleted",
            "iIsActive",
            "vcImage",

        );

    }


    /**
     * GetCategory
     * change $this->"class for use"
     * $id = ID of class used
     * Used for edit mode
     */
    public function getProduct($iProductID)
    {
        $this->iProductID = $iProductID;
        $sql = "SELECT * FROM $this->Table WHERE iProductID = ? AND iDeleted = 0";
        $row = $this->db->_fetch_array($sql, array($this->iProductID));
        foreach ($row[0] as $key => $value) {
            $this->$key = $value;
        }
        $this->arrGroups = $this->getcatrelations();

    }


    public function getcatrelations()
    {
        $params = array($this->iParentID);
        $strSelect = "SELECT P.iProductID, P.vcTitle FROM shopproduct P, shopcatprodrel x WHERE x.iCategoryID = ? AND x.iProductID = P.iProductID AND P.iDeleted = 0;";
        return $this->db->_fetch_array($strSelect, $params);
    }

    public function GetSelect()
    {
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0";
        return $this->db->_fetch_array($sql, array());
    }

    /*public function getProduct($id)
    {
        $this->iProductID = $id;
        $sql = "SELECT * FROM $this->Table WHERE iDeleted = 0 AND iProductID = ?";
        $row = $this->db->_fetch_array($sql, array($id));
        foreach ($row[0] as $key => $value) {
            $this->$key = $value;
        }
        $this->arrGroups = $this->getcatrelations();
    }
*/
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
        unset($this->arrFormElm["iProductID"]);
        unset($this->arrFormElm["daCreated"]);

        //Makes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {
            $f[$key] = filter_input(INPUT_POST, $key, $value["Filter"]);
        }

        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $params = array_values($f);

        //Current class = ID
        $this->iProductID = $id;

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
        unset($this->arrFormElm["iProductID"]);

        //MAkes a foreach that gets "filter" from $arrFormElm
        foreach ($this->arrFormElm as $key => $value) {
            $f[$key] = filter_input(INPUT_POST, $key, $value["Filter"]);
        }
        //uses the value of the "filter"'s and takes the value from the $_POST and inserts them into an array ordered by the "FIlTER"
        $params = array_values($f);

        //iCategoryID = iCategoryID
        $this->iProductID = $id;

        //awesome SQL that makes an string that looks like this
        //"UPDATE Event SET vcTitle = ?, vcLastName = ?, vcEventName = ?, vcPassword = ?, vcAddress = ?, iZip = ?, vcCity = ?, vcEmail = ?, vcPhone1 = ?, vcPhone2 = ? WHERE iCategoryID = 12"
        echo $sql = "UPDATE $this->Table SET " . implode(array_keys($this->arrFormElm), " = ?, ") . " = ? WHERE iProductID = $this->iProductID";
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
        $this->iProductID = $id;
        //SQL that sets iDeleted to 1
        $sql = "UPDATE $this->Table SET iDeleted = 1 WHERE iProductID = ?";
        return $this->db->_query($sql, array($id));
    }

    /**-
     * @param int $limit limit is the limit of display
     * frontend for displaying a certain amont
     * @return array
     */
    public function getAllLimit($limit = 2)
    {
        $strSelect = "SELECT * FROM " . $this->Table . " WHERE iDeleted = 0 LIMIT $limit";
        return $this->db->_fetch_array($strSelect);
    }


    //maybe delete form here and down

    protected function getSingleItem($iItemID)
    {
        $params = array($iItemID);
        $row = array();
        $strSelect = "SELECT * FROM " . $this->Table . " WHERE iProductID = ?";
        if ($row = $this->db->_fetch_array($strSelect, $params)) {
            $row = call_user_func_array('array_merge', $row);
            foreach ($this->arrColumns as $key => $value) {
                if (isset($row[$key])) {
                    $this->arrColumns[$key]["Value"] = $row[$key];
                }
            }

            //if ($this->useTopics) {
            //  $topic = new topic;
            //$topics = $topic->listtopicrel($iItemID, $this->module);
            //$row["topics"] = implode("<br>", array_column($topics, "vcTopicName"));
            // }
        }
        return $row;
    }

    /**
     * @param $iItemID
     * get a single record
     */
    public function getItem($iItemID)
    {
        $this->arrValues = $this->getSingleItem($iItemID);

        $this->arrValues["arrGroups"] = $this->getgrouprelations();

        foreach ($this->arrValues as $key => $value) {
            $this->$key = $value;
        }

    }

    public function getgrouprelations()
    {
        $params = array($this->arrValues["iProductID"]);
        $strSelect = "SELECT c.iCategoryID, c.vcTitle 
                      FROM shopcategory c 
                      JOIN shopcatprodrel x 
                      ON x.iCategoryID = c.iCategoryID 
                      WHERE x.iProductID = ? AND c.iDeleted = 0";
        return $this->db->_fetch_array($strSelect, $params);
    }

    public function CreateTable()
    {

        $sql = "CREATE TABLE IF NOT EXISTS `shopproduct` (
                      `iProductID` bigint(50) NOT NULL AUTO_INCREMENT,
                      `vcProductNumber` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Produkt Nummer',
                      `vcTitle` varchar(50) COLLATE utf8_bin NOT NULL COMMENT 'Titel',
                      `txDescShort` text COLLATE utf8_bin NOT NULL COMMENT 'Kort Beskrivelse',
                      `vcLink` text COLLATE utf8_bin COMMENT 'Link til varen',
                      `txDesc` text COLLATE utf8_bin NOT NULL COMMENT 'Lang Beskrivelse',
                      `txSpecfications` text COLLATE utf8_bin NOT NULL COMMENT 'Speficiationer',
                      `vcImage` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Billede 1',
                      `iPrice` double(16,2) NOT NULL COMMENT 'Pris',
                      `iOfferPrice` double(16,2) DEFAULT NULL COMMENT 'Tilbuds Pris',
                      `iStock` int(11) NOT NULL DEFAULT '2' COMMENT 'Lagerbeholdning',
                      `iWeight` int(11) DEFAULT NULL COMMENT 'Vægt',
                      `daCreated` bigint(20) NOT NULL COMMENT 'Oprettet',
                      `iIsActive` tinyint(2) NOT NULL DEFAULT '1' COMMENT 'Aktiv',
                      `iDeleted` tinyint(2) NOT NULL DEFAULT '0',
                      `iSortNum` int(11) NOT NULL DEFAULT '0' COMMENT 'Sorterings nummer',
                      PRIMARY KEY (`iProductID`)
                    ) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
        $this->db->_query($sql);
    }


}



