<?php

class Database {

    /**
     * Our database connection
     * @access private
     * @var mysql connection resource
     */
    private $db;

    /**
     * Constructor sets up database
     */
    public function __construct() {

        $mycnf = parse_ini_file("../my.cnf");

        $this->db = new mysqli($mycnf['host'], $mycnf['user'], $mycnf['password'], $mycnf['database']);
        if ($this->db->connect_errno) {
            error_log("Connect failed: %s\n", $mysqli->connect_error);
        }
    }

    public function insert($table, $keys, $values) {
        
        $query = "INSERT INTO $table ($keys) VALUES ($values)";
        if ($this->db->query($query) === TRUE) {
            return $this->db->insert_id;
        } else {
            error_log("Database insert: " . $this->db->error . " " . $query);
            return 0;
        }
    }

    public function simple_select($table, $id = 0) {
        $query = "SELECT * FROM $table";
        if ($id > 0) {
            $query .= " WHERE id = $id";
        }
        if ($result = $this->db->query($query)) {
            $rows = array();
            while ($row = $result->fetch_array()) {
                $rows[] = $row;
            }
            return $rows;
        } else {
            error_log("Database simple select: " . $this->db->error . " " . $query);
            return FALSE;
        }
    }
    
    public function select($table, $cols, $where = "") {
        $query = "SELECT $cols FROM $table";
        if ($where !== "") {
            $query .= " WHERE $where";
        }
        if ($result = $this->db->query($query)) {
            $rows = array();
            while ($row = $result->fetch_array()) {
                $rows[] = $row;
            }
            return $rows;
        } else {
            error_log("Database select: " . $this->db->error . " " . $query);
            return FALSE;
        }
    }

    public function simple_update($table, $key, $value, $id) {
        $query = "UPDATE $table SET $key = $value WHERE id = $id";
        if ($this->db->query($query) === TRUE) {
            return TRUE;
        } else {
            error_log("Database simple update: " . $this->db->error . " " . $query);
            return FALSE;
        }
    }

    public function load_file($table, $file) {
        $query = "LOAD DATA INFILE '$file' INTO TABLE $table";
        if ($this->db->query($query) === TRUE) {
            return TRUE;
        } else {
            error_log("Database load file: " . $this->db->error . " " . $query);
            return FALSE;
        }
    }
    
    public function start_transaction() {
        $query = "START TRANSACTION";
        if ($this->db->query($query) === TRUE) {
            return TRUE;
        } else {
            error_log("Database start transaction: " . $this->db->error . " " . $query);
            return FALSE;
        }
    }
    
    public function commit() {
        $query = "COMMIT";
        if ($this->db->query($query) === TRUE) {
            return TRUE;
        } else {
            error_log("Database commit: " . $this->db->error . " " . $query);
            return FALSE;
        }
    }
}

?>
