<?php
session_start();
require_once("../database/Configure.php");

$con = new Configure();

if(isset($_GET['proj_exps'])) {
    echo json_encode(getProjectExps());
} else if(isset($_POST['outputs'])) {
    $_SESSION['outputs'] = json_decode($_POST['outputs'], true);
    
    
    
    echo 1;
} else if(isset($_POST['filters'])) {
    $_SESSION['filters'] = json_decode($_POST['filters'], true);
    
    echo 1;
} else if(isset($_POST['status'])) {
    $_SESSION['status'] = $_POST['status'];
    
    echo 1;
} else {

    echo json_encode($con->getColumns());
    
}

function getProjectExps() {
    global $con;
   // if(!isset($_SESSION['project'])) {
     //   return json_encode(array());
   // }
    $exps = $con->getProjectExps();//$_SESSION['project']);
    
    return $exps;
}

function setSession() {
    if($_GET['session'] == "normalize") {
        if($_GET['normalize'] == 0) {
            $_SESSION['normalize'] = FALSE;
        } else {
            $_SESSION['normalize'] = TRUE;
        }
        
    } else if($_GET['session'] == "ttest") {
        if($_GET['ttest'] == 0) {
            $_SESSION['ttest'] = FALSE;
        } else {
            $_SESSION['ttest'] = TRUE;
        }
        
    } else if($_GET['session'] == "filters") {
        
    }
}

?>
