<?php

/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 31-08-2017
 */
class setup
{
    private $db;

    function __construct()
    {
        global $db;
        $this->db = $db;
        $this->CreateTable();
    }

    public function CreateTable()
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
                ) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

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

        $sql = "CREATE TABLE IF NOT EXISTS `usergroup` (
                `iGroupID` bigint(20) NOT NULL AUTO_INCREMENT,
                `vcGroupName` varchar(255) NOT NULL COMMENT 'Gruppenavn',
                `txDesc` text COMMENT 'Beskrivelse',
                `vcRoleName` varchar(20) DEFAULT NULL COMMENT 'Rollenavn',
                `daCreated` bigint(20) NOT NULL DEFAULT '0' COMMENT 'Oprettet',
                `iDeleted` tinyint(1) NOT NULL DEFAULT '0',
                `iIsActive` tinyint(4) NOT NULL DEFAULT '1',
                PRIMARY KEY (`iGroupID`)
                 ) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;";
        $this->db->_query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS `usergrouprel` (
                `iRelID` bigint(20) NOT NULL AUTO_INCREMENT,
                `iUserID` bigint(20) NOT NULL,
                `iGroupID` bigint(20) NOT NULL,
                PRIMARY KEY (`iRelID`)
                ) ENGINE=MyISAM AUTO_INCREMENT=150 DEFAULT CHARSET=latin1;";
        $this->db->_query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS `org` (
                `iOrgID` bigint(20) NOT NULL AUTO_INCREMENT,
                `vcOrgName` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Navn',
                `vcAddress` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Adresse',
                `iZip` mediumint(10) NOT NULL COMMENT 'Post nummer',
                `txDesc` text CHARACTER SET utf8 NOT NULL COMMENT 'Beskrivelse',
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
  /*        $sql = "INSERT IGNORE INTO `usergroup` (`iGroupID`, `vcGroupName`, `txDesc`, `vcRoleName`, `daCreated`, `iDeleted`, `iIsActive`) VALUES
       (1, 'Systen Admin', 'am i working?', 'sysadmin', 0, 0, 1),
       (2, 'Adminstrator', 'Groups for adminstratoer \\Gives access to CMS system', 'admin', 0, 0, 1),
       (3, 'Extranet', 'Group for extranet users', 'extranet', 0, 0, 1),
       (4, 'Nyhedsbrev', 'Group for newsletter users', 'newsletter', 0, 0, 1);";
          $this->db->_query($sql);
        $sql = "INSERT INTO `user` (`iUserID`, `vcUserName`, `vcPassword`, `vcFirstName`, `vcLastName`, `vcAddress`, `iZip`, `vcCity`, `vcEmail`, `vcPhone1`, `vcPhone2`, `vcPhone3`, `iOrgID`, `daCreated`, `iSuspended`, `iDeleted`)
           VALUES (1, 'Admin' ,'" . $this->hashpw('admin') . "', 'Mads', 'Roloff', 'Blåkildevej 58', 9220, 'Aalborg', 'Mads@admin.com', '22950302', '22950302', '22950302', 1, 1502347709, 0, 0)";
        $this->db->_query($sql);
        $sql = "INSERT IGNORE INTO `org` (`iOrgID`, `vcOrgName`, `vcAddress`, `iZip`, `vcCity`, `vcEmail`, `vcPhone1`, `daCreated`, `iDeleted`, `vcCountry`) VALUES
               (1, 'TestFirma', 'Blåkildevej 58', 9220, 'Aalborg', 'roloffqt@gmail.com', '+4522950302', 1487144446, 0, 'Danmark');";
        $this->db->_query($sql);
        $params = array(1, 1);
          $sql = "INSERT IGNORE INTO `usergrouprel`(`iUserID`,`iGroupID`)VALUES(?,?)";
        $this->db->_query($sql, $params);
*/
    }

    public function hashpw($password)
    {
        $salt = "Py!n0{}@0SZX7XJ1M@BXs#8LI%%v/M]k";

        $password = "$salt+!$password$salt!$salt";

        return hash("sha256", $password);
    }


}
