<?php
ob_start(); // Needed so there isn't a line return from the session
session_start();

require_once("../database/View.php");
require_once 'Spreadsheet/Excel/Writer.php';

$filters = array();
if (isset($_SESSION['filters'])) {
    error_log("filters" . print_r($_SESSION['filters'], TRUE));
    $filters = $_SESSION['filters'];
}

$main = array();
$control = array();
if (isset($_SESSION['status'])) {
    foreach ($_SESSION['status'] as $exp => $status) {

        if ($status == "main") {
            $main[] = $exp;
        } else if ($status == "control") {
            $control[] = $exp;
        }
    }
}
$view = new View($filters, $main, $control);
if (isset($_GET['excel'])) {
    //getExcel();
	getTSV();
} else {

    getResults();
}

function getResults() {
    global $view;
    $outputs = array();
    if (isset($_SESSION['outputs'])) {
        $outputs = $_SESSION['outputs'];
    }
    $normalize = 1;
    if (isset($_SESSION['normalize'])) {
        $normalize = $_SESSION['normalize'];
    }
    $ttest = 1;
    if (isset($_SESSION['ttest'])) {
        $ttest = $_SESSION['ttest'];
    }
    $results = $view->getResult($outputs, FALSE, FALSE); //$normalize, $ttest));
    
    echo json_encode($results);
}

function getTSV() {
	global $view;
header('Content-type: application/xls');

header('Content-Disposition: attachment; filename="test.xls"');

    $outputs = $_SESSION['outputs'];
    
    $results = $view->getResult($outputs, FALSE, FALSE); //$_SESSION['normalize'], $_SESSION['ttest']);
    
	echo "Name\tCount\tGenome Position";
	for ($i = 0; $i < count($outputs); $i++) {
		echo "\t" . $outputs[$i]['stat'] . " of " . $outputs[$i]['outputs'];
	}
	echo "\n";
	
    
    // The actual data
    for ($i = 0; $i < count($results); $i++) {
	echo $results[$i]['Name'] . "\t" . $results[$i]['Count'] . "\t" . $results[$i]['Genome Position'];
        for ($j = 0; $j < count($outputs); $j++) {
		echo "\t" . $results[$i][ucfirst($outputs[$j]['stat']) . " of " . $outputs[$j]['outputs']];
        }
	echo "\n";
    }

}

function getExcel() {
    global $view;
    $outputs = $_SESSION['outputs'];
    
    $results = $view->getResult($outputs, FALSE, FALSE); //$_SESSION['normalize'], $_SESSION['ttest']);
    $workbook = new Spreadsheet_Excel_Writer();

    // sending HTTP headers
    ob_end_clean(); // Needed so there isn't a line return from the session
    $workbook->send('test.xls');

    // Creating a worksheet
    $worksheet = & $workbook->addWorksheet('My first worksheet');

    $worksheet->write(0, 0, 'Name');
    $worksheet->write(0, 1, 'Count');
    for ($i = 0; $i < count($outputs); $i++) {
        $worksheet->write(0, $i + 2, $outputs[$i]['stat'] . " of " . $outputs[$i]['outputs']);
    }
    // The actual data
    for ($i = 0; $i < count($results); $i++) {
        $worksheet->write($i + 1, 0, $results[$i]['Name']);
        $worksheet->write($i + 1, 1, $results[$i]['Count']);
        for ($j = 0; $j < count($outputs); $j++) {
            $worksheet->write($i + 1, $j + 2, $results[$i][ucfirst($outputs[$j]['stat']) . " of " . $outputs[$j]['outputs']]);
        }
    }


    // Let's send the file
    $workbook->close();
}

?>
