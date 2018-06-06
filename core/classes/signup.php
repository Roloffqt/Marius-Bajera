<?php

/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 31-08-2017
 */
class signup
{
    private $db;

    function __construct()
    {
        $this->Table = "signup";

        global $db;
        $this->db = $db;
        $this->CreateTable();

    }


    public function MakeSignup($iUserID, $iEventID)
    {
        //Creates Signup/signup in database
        $params = array($iUserID, $iEventID, $_POST["signups"], $_POST["note"], time());
        $uparams = array($_POST["signups"], $_POST["note"]);
        $sql = "INSERT INTO $this->Table (iUserID, iEventID, iSignups, vcNote, daCreated) VALUES(?,?,?,?,?)
                        ON DUPLICATE KEY UPDATE    
                        iSignUps = " . $uparams[0] . " , vcNote = '" . $uparams[1] . "'";
        $this->db->_query($sql, $params);
    }

    public function GetSum()
    {
        //Gets the sum of iSignups! which means all entries are put togeather
        $sql = "SELECT sum(iSignUps) as total FROM $this->Table";
        return $this->db->_fetch_value($sql);
    }

    public function GetDetails($iUserID)
    {
        //Find where iUserID is = to the user trying to book and gets the note they wrote aswell to make it updateable instead of inserting
        $sql = "SELECT DISTINCT iUserID, vcNote, iSignUps FROM $this->Table WHERE iUserID = " . $iUserID;
        return $this->db->_fetch_array($sql);
    }

    public function GetMaxNum($iUserID)
    {
        //gets the the IsignUps from the current user
        $sql = "SELECT iSignUps FROM $this->Table WHERE iUserID =" . $iUserID;
        return $this->db->_fetch_value($sql);
    }

    public function Delete($iUserID)
    {
        //deletes.
        $sql = "DELETE FROM $this->Table WHERE iUserID = " . $iUserID;
        $this->db->_query($sql);
    }

    public function CreateTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `signup` (
        `iSignupID` bigint(20) NOT NULL AUTO_INCREMENT,
        `iUserID` bigint(20) NOT NULL DEFAULT '0',
        `iEventID` bigint(20) NOT NULL DEFAULT '0',
        `iSignUps` bigint(20) NOT NULL DEFAULT '0',
        `vcNote` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `daCreated` bigint(20) NOT NULL DEFAULT '0',
        PRIMARY KEY (`iSignupID`),
        UNIQUE KEY `iUserID` (`iUserID`)
)       ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $this->db->_query($sql);
    }
}
