<?php

/**
 * Description of Db Class
 *
 * Database connection and query handles with defined credentials
 * Credentials must be valued in the class constructor
 * Use method _query for insert, update and delete queries
 * Use method _fetch_array for row selecting
 *
 * Example of class initialize
 * $db = new db;
 *
 * Example for use of _query:
 * $params = array($title,$id);
 * $sql = "UPDATE table SET title = ? WHERE id = ?";
 *
 * Example for use of _fetch_array:
 * $params = array($value1,$value2);
 * $sql = "SELECT [*, fieldnames] FROM table WHERE fieldname = ? AND fieldname2 = ?";
 * $array = $db->_fetch_array($sql,$params);
 *
 * @author Heinz K, Nov 2016
 */
class db
{

    /* Setting properties */
    protected $db;
    protected $dbhost;
    protected $dbuser;
    protected $dbpassword;
    protected $dbname;
    private $sql;
    private $stmt;
    private $result;
    private $row = array();

    /**
     * Class constructor - sets db credentials
     */

    public function __construct()
    {
    }

    /**
     * DB Connection Error
     *
     * Writes error on connection fail
     */
    public function _connect_error()
    {
        echo "DB ERROR: " . mysqli_connect_error();
        exit();
    }

    /**
     * DB Method Statement Error
     *
     * Writes error on query fail
     */
    public function _error()
    {
        echo "STMT ERROR: " . $this->db->error;
        exit();
    }

    /**
     * DB Connect Method
     *
     * Establish a connection to a database
     */
    public function _connect()
    {
        @$this->db = new mysqli($this->dbhost, $this->dbuser, $this->dbpassword, $this->dbname);
        if (mysqli_connect_errno()) {
            $this->_connect_error();
        }
        mysqli_set_charset($this->db, "utf8");
    }

    /**
     * DB Query
     *
     * Send a SQL query with or without parametres
     *
     * @param string $sql
     * @param array $params
     * @param int $sanitize
     */
    public function _query($sql, $params = NULL, $sanitize = TRUE)
    {

        $this->sql = $this->_sanitize($sql, $sanitize);

        if (!$this->stmt = $this->db->prepare($this->sql)) {
            $this->_error();
            return false;
        }

        if (is_array($params)) {
            $this->_bindparam($params);
        }
        if (!$this->stmt->execute()) {
            $this->_error();
        }
        $this->stmt->reset();
        $this->stmt->close();
    }

    /**
     * DB Fetch Array
     *
     * Send a SQL select query with or without parametres
     * and returns an array with given values
     *
     * @param string $sql
     * @param array $params
     * @param int $sanitize
     * @return array Returns a dimensioned array with selected rows and fields
     */
    public function _fetch_array($sql, $bindparams = NULL, $sanitize = TRUE)
    {

        $this->sql = $this->_sanitize($sql, $sanitize);
        if (!$this->stmt = $this->db->prepare($this->sql)) {
            $this->_error();
        }
        if (!empty($bindparams)) {
            $this->_bindparam($bindparams);
        }

        if (!$this->stmt->execute()) {
            $this->_error();
        } else {
            $this->result = $this->stmt->get_result();
            return $this->row = $this->result->fetch_all(MYSQLI_ASSOC);

        }
        $this->stmt->free_result();
        $this->stmt->close();
    }

    /**
     * Binds paramteters to a prepared sql statement
     *
     * @param type $arrParams
     */
    protected
    function _bindparam($arrParams)
    {
        $params = array();
        $params[0] = "";

        foreach ($arrParams as $key => $value) {
            $params[0] .= $this->_gettype($value);
            array_push($params, $arrParams[$key]);
        }

        call_user_func_array(array($this->stmt, 'bind_param'), $this->_refval($params));
    }

    /**
     * Returns last inserted id
     * @return int
     */
    public function _getinsertid()
    {
        return $this->db->insert_id;
    }

    /**
     * Determines the datatype o a given value
     *
     * @param type $var
     * @return string
     */
    protected function _gettype($var)
    {
        switch (gettype($var)) {
            case 'NULL':
            case 'string':
                return 's';
                break;
            case 'boolean':
            case 'integer':
                return 'i';
                break;
            case 'blob':
                return 'b';
                break;
            case 'double':
                return 'd';
                break;
        }
        return '';
    }

    /**
     * Changes array values to referring values
     *
     * @param array $arr
     * @return array $refs
     */
    protected function _refval($arr)
    {
        if (strnatcmp(phpversion(), '5.3') >= 0) {
            $refs = array();
            foreach ($arr as $key => $value) {
                $refs[$key] =& $arr[$key];
            }
            return $refs;
        }

        return $arr;
    }

    /**
     * _sanitize
     *
     * Sanitizes a query string
     *
     * @param string $sql
     * @param int $sanitize
     * @return string
     */
    protected function _sanitize($sql, $sanitize)
    {
        $str_sanitized = ($sanitize) ? filter_var($sql, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : $sql;
        return $str_sanitized;
    }

    /**
     * _close
     *
     * Close a db connection
     */
    public function _close()
    {
        $this->db->close();
    }

    /* Method Fetch Value
//* Fetches a single value
//* @param string $sql
//* @param array $bindparams
//* @param bool $sanitize
//* @return var Returns a single value
//*/
    public function _fetch_value($sql, $bindparams = NULL, $sanitize = TRUE)
    {

        $this->sql = $this->_sanitize($sql, $sanitize);
        if (!$this->stmt = $this->db->prepare($this->sql)) {
            $this->_error();
        }

        if (is_array($bindparams)) {
            $this->_bindparam($bindparams);
        }
        $this->stmt->execute();
        $this->result = $this->stmt->get_result();
        $this->stmt->store_result();
        $this->row = $this->result->fetch_all();
        if (count($this->row) > 0) {
            $this->row = call_user_func_array("array_merge", $this->row);
            return reset($this->row);
        }
        $this->stmt->free_result();
        $this->stmt->close();
    }
}