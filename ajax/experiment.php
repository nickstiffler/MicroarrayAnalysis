<?php

require_once("../database/Experiment.php");
$exp = new Experiment();

if (isset($_POST['data'])) {
    echo newExperiment();
} else if (isset($_GET['exps'])) {
    echo getExperiments();
} else {
    echo 0;
}

function getExperiments() {
    global $exp;
    return json_encode($exp->getExperiments());
}

function newExperiment() {
    global $exp;
    
    $success = array("success" => 1);
    if (!isset($_POST['data'])) {
        $success = array("success" => 0);
        return json_encode($success);
    }

    $data = $_POST['data'];

    //$fileName = $_POST['filename'];

    
    $name = "";
    $user = "";
    $comments = "";
    if (isset($_POST['name'])) {
        $name = $_POST['name'];
    }
    if (isset($_POST['user'])) {
        $user = $_POST['user'];
    }
    if (isset($_POST['comments'])) {
        $comments = $_POST['comments'];
    }
    
    $id = $exp->newExperiment($name, $user, $comments);

    // Parse header
    $gpr = explode("\n", $data);

    $header = "";
    $columns;
    while ($row = array_shift($gpr)) {
        if (strncmp($row, "\"Block\"", 7) === 0) {
            $exp->addHeader($id, $header);
            // Parse column headers
            $columns = str_getcsv($row, "\t");
            break;
        } else {
            $header .= $row . "\n";
        }
    }

   $rows = 1;
    
    $exp->setNumRows(count($gpr), $id);
   // foreach($gpr AS $row) {
    //    $row[count($row) - 1] = $rows++;
   // }
    //$rows = implode("\n", $gpr);
   // $exp->loadRows($id, $rows);
    //error_log(count($gpr));
    $exp->startTransaction();
    
    while ($row = array_shift($gpr)) {
        
        $values = explode("\t", $row);
        if (count($values) > count($columns)) {
            array_pop($values);
        }
        $combined = array_combine($columns, $values);
        $rows++;
        foreach ($combined AS $key => $value) {
            $exp->addRow($id, $key, $value, $rows);
        }
    }
    $exp->commit();






    //error_log(strlen($data) . " $fileName");
    //$serverFile = time() . $fileName;
    //$fp = fopen('/tmp/' . $serverFile, 'w'); //Prepends timestamp to prevent overwriting
    //fwrite($fp, $data);
    //fclose($fp);
    //$returnData = array("serverFile" => $serverFile);
    //
    
    return json_encode($success);
}

?>
