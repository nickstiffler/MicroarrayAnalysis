<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('Database.php');
/**
 * Description of Configure
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
