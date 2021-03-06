<?php
/**
 *
 *  Auther : Heinz HEKA
 *  Tech college aalborg
 *  Modifyed by Mads Roloff
 *
 * Description of class auth
 * Used for session based login
 * Depends on user class roles
 * @property string $strUserName Username from POST var
 * @property string $strPassword Password from POST var
 * @property bool $iRemember Bool to set remember me cookie
 * @property bool $strRemoteAddr IP address
 * @property int $iTimeOutSeconds Number of session keep alive seconds
 * @property string $doLogout GET var with logout action
 * @property int $iUserID Users ID
 * @property object $user User Object
 *
 */
class auth
{
    public $strUserName;
    public $strPassword;
    public $iRemember;
    public $strRemoteAddr;
    public $strErrMessage;
    public $iTimeOutSeconds;
    public $doLogout;
    public $iUserID;
    public $user;
    public $loginWin;
    public $iShowLoginForm;
    public $errorMessage;


    /* Error Constants */
    const ERR_NOUSERFOUND = 1;
    const ERR_NOSESSIONFOUND = 2;
    const ERR_NOACCESS = 3;

    /**
     * Class Constructor
     * Set defaults
     * Sets strUserName, strPassword, iRemember & doLogout to watch POST & GET vars
     * Call method athentificate
     * @global type $db
     */
    public function __construct()
    {
        session_start();
        global $db;
        $this->db = $db;
        $this->strUserName = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
        $this->strPassword = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
        $this->iRemember = filter_input(INPUT_POST, "remember", FILTER_SANITIZE_STRING);
        $this->strRemoteAddr = filter_input(INPUT_SERVER, "REMOTE_ADDR", FILTER_VALIDATE_IP);
        $this->doLogout = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);
        $this->iTimeOutSeconds = 3600;
        $this->iUserID = 0;
        $this->iShowLoginForm = 1;
        $this->loginWin = "C:/wamp64/www/Marius/public_html/cms/assets/incl/login.php";
        $this->user = new user();
        $this->errorMessage = "";
    }

    /**
     * Method Authentificate
     * If GET["action"] = logout run method logout
     * If set strUserName & strPassword run method initUser
     * Else run method getSession
     */
    public function authentificate()
    {
        if ($this->doLogout === "logout") {
            $this->logout();
        }
        if ($this->strUserName && $this->strPassword) {
            $this->errorMessage = $this->initUser();
        } else {
            if (!$this->getSession()) {
                if ($this->iShowLoginForm) {
                    echo $this->loginform();
                }
            }
        }
    }

    /**
     * Method Initialize User
     * Selects user from username & password
     * If true - insert session into usersession and call user object
     * (User Obj sets roles)
     */

    private function initUser()
    {

        $params = array($this->strUserName, $this->hashpw($this->strPassword));

        $strSelectUser = "SELECT iUserID FROM user " .
            "WHERE vcEmail = ? " .
            "AND vcPassword =  ? " .
            "AND iSuspended = 0 " .
            "AND iDeleted = 0";


        if ($this->iUserID = $this->db->_fetch_value($strSelectUser, $params)) {
            $params = array(
                session_id(),
                $this->iUserID,
                $this->strRemoteAddr,
                1,
                time(),
                time()
            );


            $strInsertSession = "INSERT INTO usersession (" .
                "vcSessionID," .
                "iUserID," .
                "iIpAddress, " .
                "iIsLoggedIn, " .
                "daLoginCreated, " .
                "daLastAction) " .
                "VALUES(?,?,?,?,?,?)";
            $this->db->_query($strInsertSession, $params);
            $this->user->getUser($this->iUserID);
          header("Location: " . $_SERVER["PHP_SELF"]);
        } else {
            if ($this->iShowLoginForm) {
                echo $this->loginform(self::ERR_NOUSERFOUND);
            } else {
                $this->strErrMessage = $this->getError(self::ERR_NOUSERFOUND);
            }
        }
        if ($this->iRemember) {
            setcookie('vcEmail', $this->strUserName, time() + (86400 * 365), "/");
            setcookie('vcPassword', $this->strPassword, time() + (86400 * 365), "/");
        } else {
            setcookie('vcEmail', '', time() - 3600, "/");
            setcookie('vcPassword', '', time() - 3600, "/");
            unset($_COOKIE["vcEmail"]);
            unset($_COOKIE["vcPassword"]);
        }
    }

    /**
     * Method Get Session
     * Checks if db usersession has a session id matching value
     * If true check if session is outdates
     * If true - insert session into usersession and call user object
     * (User Obj sets roles)
     * @return int $iUserID Returns the users ID
     */
    private function getSession()
    {
        $params = array(session_id());
        $strSelectSession = "SELECT iUserID, daLastAction FROM usersession " .
            "WHERE vcSessionID = ? " .
            "AND iIsLoggedIn = 1";

        $row = $this->db->_fetch_array($strSelectSession, $params);

        if (count($row) > 0) {
            $row = call_user_func_array("array_merge", $row);
            if ($row["daLastAction"] > time() - ($this->iTimeOutSeconds)) {
                $this->iUserID = $row["iUserID"];
                $this->user->getUser($this->iUserID);
                $this->updateSession();
                return $this->iUserID;
            } else {
                $this->logout();
            }
        }
    }

    /**
     * Method Update Session
     * Updates daLastAction in the current session
     */
    private
    function updateSession()
    {
        $params = array(session_id());
        $strUpdate = "UPDATE usersession " .
            "SET daLastAction = UNIX_TIMESTAMP() " .
            "WHERE vcSessionID = ?";
        $this->db->_query($strUpdate, $params);
    }

    /**
     * Method Logout
     * Updates usersession iIsLoggedIn to false
     * Destroys current session and resets session id
     */
    private function logout()
    {
        $params = array(session_id());
        $strSessionUpdate = "UPDATE usersession SET iIsLoggedIn = 0 WHERE vcSessionID = ?";
        $this->db->_query($strSessionUpdate, $params);
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id();
    }

    /**
     * Method Login Form
     * Calls output buffer for rendering login form
     * Includes a clean php file with login form html and css
     * Get error messages and replaces error codes if any errors
     * @param int $errCode
     * @return string Returns full html of login window
     */
    public function loginform($errCode = 0)
    {
        ob_start();
        include_once $this->loginWin;
        $strBuffer = ob_get_clean();
        $strErrorMsg = self::getError($errCode);
        $strContent = str_replace("@ERRORMSG@", $strErrorMsg, $strBuffer);
        return $strContent;
    }

    /**
     * Method Check Session
     * Checks if db usersession has a session id matching value
     * @return bool Returns true or false
     */
    public
    function checkSession()
    {
        $params = array(session_id());
        $strSelectSession = "SELECT iUserID, daLastAction FROM usersession " .
            "WHERE vcSessionID = ? " .
            "AND iIsLoggedIn = 1";
        $row = $this->db->_fetch_array($strSelectSession, $params);
        if (count($row) > 0) {
            $row = call_user_func_array("array_merge", $row);
            if ($row["daLastAction"] > time() - ($this->iTimeOutSeconds)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    /**
     * Method getError
     * Switches error constants to a string message
     * @param int $int
     * @return string Returns a string with error message
     */
    private
    function getError($int)
    {
        switch ($int) {
            default:
                $strErr = '';
                break;
            case self::ERR_NOUSERFOUND:
                $strErr = "Brugernavn eller password er forkert!";
                break;
            case self::ERR_NOSESSIONFOUND:
                $strErr = "Bad Session!";
                break;
            case self::ERR_NOACCESS:
                $strErr = "Du har ikke rettigheder til at se dette indhold!";
                break;
        }
        return $strErr;
    }

    public function hashpw($password)
    {
        $salt = "Py!n0{}@0SZX7XJ1M@BXs#8LI%%v/M]k";

        $password = "$salt+!$password$salt!$salt";

        return hash("sha256", $password);
    }

}
