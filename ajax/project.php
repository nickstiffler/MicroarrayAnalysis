<?php

require_once('../database/Project.php');

$proj = new Project();


if(isset($_GET['name'])) {
    echo newProject();
} elseif(isset($_GET['projects'])) {
    echo json_encode(getProjects());
}

function newProject() {
    global $proj;
    return $proj->newProject($_GET['name'], $_GET['user'], $_GET['comments']);
}

function getProjects() {
    global $proj;
    return $proj->getProjects();
}

?>
