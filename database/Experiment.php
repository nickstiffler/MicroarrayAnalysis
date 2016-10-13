<?php

require_once('Database.php');

class Experiment {
    private $db;

    
    public function __construct() {

        $this->db = new Database();

    }
    
    public function newExperiment($name, $user, $comments) {
       return $this->db->insert("experiments", "name, user, comments, date, num_rows", "'$name', '$user', '$comments', NOW(), 0");
    }
    
    public function addHeader($id, $header) {
        return $this->db->simple_update("experiments", "header", "'$header'", $id);
    }
    
    public function addRow($id, $key, $value, $row) {
        
        //$row = $this->db->simple_select("experiments", $id);
        
        //$this->db->simple_update("experiments", "num_rows", $row[0]['num_rows']++, $id);
        //$value = $this->db->real_escape_string($value);
        $this->db->insert("exp_rows", "exp_id, col, data, exp_row", "$id, '$key', '$value', $row");
    }
    
    public function loadRows($id, $rows) {
        file_put_contents('/tmp/gpr.tsv', $rows);
        $this->db->load_file("exp_rows", "/tmp/grp.tsv");
    }
    
    public function setNumRows($rows, $id) {
        $this->db->simple_update("experiments", "num_rows", $rows, $id);
    }
    
    public function getExperiments() {
        return $this->db->simple_select("experiments");
    }
    
    public function getExperiment($id) {
        return $this->db->simple_select("experiments", $id);
    }
    
    public function getRows($id) {
        
    }
    
    public function startTransaction() {
        $this->db->start_transaction();
    }
    
    public function commit() {
        $this->db->commit();
    }
    
    public function getProjectExps($proj_id) {
        $exps = $this->db->simple_select("proj_exps", $proj_id);
        
        $experiments = array();
        foreach($exps AS $exp) {
            $experiments[] = $exp;
        }
        return $experiments;
    }
}
?>
