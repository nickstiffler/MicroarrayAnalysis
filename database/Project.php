<?php
require_once('Database.php');

class Project {
    private $db;
    
    public function __construct() {

        $this->db = new Database();
    }
    
    public function newProject($name, $user, $comments) {

        if($this->db->insert("projects", "name, user, comments, date", "'$name', '$user', '$comments', NOW()")) {
            return 1;
        } else {
            return 0;
        }
    }
    
    public function getProjects() {
        return $this->db->simple_select("projects");
    }
}
?>
