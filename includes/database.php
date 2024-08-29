<?php


class MySqli_DB {

    private $con;
    public $query_id;

    function __construct() {
        $this->db_connect();
    }

    // Open database connection
    public function db_connect() {
        // Initialize MySQLi with SSL support
        $this->con = mysqli_init();
        $ssl_ca = '/home/site/wwwroot/ca-cert.pem'; // Ensure this path is correct
        $this->con->ssl_set(null, null, $ssl_ca, null, null);

        // Establish a secure connection
        if (!$this->con->real_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, 3306, null, MYSQLI_CLIENT_SSL)) {
            die("Database connection failed: " . mysqli_connect_error());
        }
    }

    // Close database connection
    public function db_disconnect() {
        if (isset($this->con)) {
            mysqli_close($this->con);
            unset($this->con);
        }
    }

    // Execute a database query
    public function query($sql) {
        if (trim($sql != "")) {
            $this->query_id = $this->con->query($sql);
        }
        if (!$this->query_id) {
            // Development mode error display
            die("Error on this query: <pre>" . $sql . "</pre>");
            // For production mode, replace with a generic error message
            // die("Error executing query");
        }
        return $this->query_id;
    }

    // Fetch data as an array
    public function fetch_array($statement) {
        return mysqli_fetch_array($statement);
    }

    // Fetch data as an object
    public function fetch_object($statement) {
        return mysqli_fetch_object($statement);
    }

    // Fetch data as an associative array
    public function fetch_assoc($statement) {
        return mysqli_fetch_assoc($statement);
    }

    // Get number of rows from the result set
    public function num_rows($statement) {
        return mysqli_num_rows($statement);
    }

    // Get the last inserted ID
    public function insert_id() {
        return mysqli_insert_id($this->con);
    }

    // Get the number of affected rows
    public function affected_rows() {
        return mysqli_affected_rows($this->con);
    }

    // Escape special characters in a string for SQL
    public function escape($str) {
        return $this->con->real_escape_string($str);
    }

    // Loop through query results
    public function while_loop($loop) {
        global $db;
        $results = array();
        while ($result = $this->fetch_array($loop)) {
            $results[] = $result;
        }
        return $results;
    }
}

$db = new MySqli_DB();
?>
