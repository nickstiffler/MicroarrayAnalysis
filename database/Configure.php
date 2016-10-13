<?php

require_once('Database.php');
/**
 * Database funtions for the configure page
 *
 * @author stiffler
 */
class Configure {
    private $db;

    
    public function __construct() {

        $this->db = new Database();

    }
    
    public function getColumns() {
        return $this->db->select("exp_rows", "DISTINCT(col)");
    }
    
    public function getProjectExps() {//$proj) {
        return $this->db->simple_select("experiments");
    }
    
}

?>
