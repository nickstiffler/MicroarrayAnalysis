<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of View
 *
 * @author stiffler
 */
require_once('Database.php');
require_once("../statistics.php");

class View {

    private $db;
    private $main;
    private $control;
    private $result;

    public function __construct($filters, $main, $control = NULL) {
        $this->db = new Database();
        $this->main = array();
        $this->control = array();
// Combine all replicates into a single table
        foreach ($main AS $gpr) {
            $names = $this->db->select("exp_rows", "*", "exp_id = $gpr AND col = 'Name'");

            foreach ($names AS $row) {

                $name = $row['data'];


                if (!isset($this->main[$name])) {
                    $this->main[$name] = array();
                    $this->main[$name]['count'] = 0;
                }

                $rows2 = $this->db->select("exp_rows", "*", "exp_id = $gpr AND exp_row = " . $row['exp_row']);
                $data = array();
                //$filtered = FALSE;
                foreach ($rows2 AS $row2) {

                    //  if (!isset($this->main[$name][$row2['col']])) {
                    //    $this->main[$name][$row2['col']] = array();
                    // $this->main[$name][$row2['col']]['filtered'] = FALSE;
                    //  }
			if($row2['col'] == "Gene Name") {
				$this->main[$name]['Genome Position'] = $row2['data'];
			}

                    foreach ($filters AS $filter) {
                        if ($row2['col'] == $filter['filter']) {
                            // error_log(print_r($row2, TRUE));
                            // If it fails any filter, skip this replicate
                            if (!$this->passes_filter($filter, $row2)) {

                                $data = NULL;
                                break 2;
                            }
                        }
                    }
                    if (!isset($data[$row2['col']])) {
                        $data[$row2['col']] = array();
                    }
                    $data[$row2['col']] = $row2['data'];
                }
                if ($data != NULL) {
                    foreach ($data AS $col => $value) {
                        if (!isset($this->main[$name][$col])) {
                            $this->main[$name][$col] = array();
                        }
                        $this->main[$name][$col][] = $value;
                    }
                    $this->main[$name]['count']++;
                }
            }
        }

        foreach ($control AS $gpr) {
            $names = $this->db->select("exp_rows", "*", "exp_id = $gpr AND col = 'Name'");
            foreach ($names AS $row) {

                $name = $row['data'];


                if (!isset($this->control[$name])) {
                    $this->control[$name] = array();
                    $this->control[$name]['count'] = 0;
                }

                $rows2 = $this->db->select("exp_rows", "*", "exp_id = $gpr AND exp_row = " . $row['exp_row']);
                $data = array();
                //$filtered = FALSE;
                foreach ($rows2 AS $row2) {

                    //  if (!isset($this->main[$name][$row2['col']])) {
                    //    $this->main[$name][$row2['col']] = array();
                    // $this->main[$name][$row2['col']]['filtered'] = FALSE;
                    //  }


                    foreach ($filters AS $filter) {
                        if ($row2['col'] == $filter['filter']) {
                            // error_log(print_r($row2, TRUE));
                            // If it fails any filter, skip this replicate
                            if (!$this->passes_filter($filter, $row2)) {

                                $data = NULL;
                                break 2;
                            }
                        }
                    }
                    if (!isset($data[$row2['col']])) {
                        $data[$row2['col']] = array();
                    }
                    $data[$row2['col']] = $row2['data'];
                }
                if ($data != NULL) {
                    foreach ($data AS $col => $value) {
                        if (!isset($this->control[$name][$col])) {
                            $this->control[$name][$col] = array();
                        }
                        $this->control[$name][$col][] = $value;
                    }
                    $this->control[$name]['count']++;
                }
            }
        }
    }

    public function normalize($col) {
// First, find median of all results in column
        $medians = array();
        $count = 0;
        foreach ($this->main AS $name => $row) {

            for ($i = 0; $i < count($this->main[$name][$col]); $i++) {
                $this->main[$name][$col][$i] = log($this->main[$name][$col][$i], 2);
            }
            $medians[] = median($this->main[$name][$col]);
        }

        $median = median($medians);

        foreach ($this->main AS $name => $row) {
            $diff = median($this->main[$name][$col]) - $median;
            for ($i = 0; $i < count($this->main[$name][$col]); $i++) {
                $this->main[$name][$col][$i] = $this->main[$name][$col][$i] - $diff;
            }
        }
    }

    public function ttest($col) {
        foreach ($this->main AS $name => $row) {
            $this->main[$name][$col]["ttest_$col"] = TUTest($this->main[$name][$col], $this->control[$name][$col]);
        }
    }

    public function getResult($outputs, $normalize = TRUE, $ttest = TRUE) {


        $result = array();

        /*
          foreach ($outputs AS $ouput) {
          if ($normalize) {
          $this->normalize($output['outputs']);
          }
          if ($ttest) {
          $this->ttest($output['outputs']);
          }
          }
         */


        foreach ($this->main AS $name => $row) {
            $temp = array();
            foreach ($outputs AS $output) {

                $temp[ucfirst($output['stat']) . " of " . $output['outputs']] = $this->getOutput($row, $output);
            }
// if ($ttest) {
//    $temp['TTest of ' . $output['outputs']] = $row['ttest_' . $output['outputs']];
//}
            $temp['Name'] = $name;
	    $temp['Genome Position'] = $row['Genome Position'];
            // error_log(print_r($row, TRUE));
            $temp['Count'] = $row['count'];
            if (isset($this->control[$name])) {
                foreach ($outputs AS $output) {

                    $temp["Control " . ucfirst($output['stat']) . " of " . $output['outputs']] = $this->getOutput($this->control[$name], $output);
                }

                $temp['Control Count'] = $this->control[$name]['count'];
            }

            $result[] = $temp;
        }



        return $result;
    }

    function getOutput($row, $output) {
        if (count($row[$output['outputs']]) == 0) {
            return "";
        }

        switch ($output['stat']) {
            case "mean":
                return mean($row[$output['outputs']]);
            case "median":
                return median($row[$output['outputs']]);
            case "std_dev":
                return std_dev($row[$output['outputs']]);
            case "var":
                return variance($row[$output['outputs']]);
            case "min":
                return min($row[$output['outputs']]);
            case "max":
                return max($row[$output['outputs']]);
            case "none":
                return implode(", ", array_unique($row[$output['outputs']]));
        }
        return 0;
    }

    function passes_filter($filter, $row) {

        //error_log("Testing " . $filter['filter'] ." " . $row['data'] . " " . $filter['relationship'] . " " . $filter['filter_value']);
        switch ($filter['relationship']) {
            case ">":
                if ($row['data'] > $filter['filter_value']) {
                    return TRUE;
                } else {
                    return FALSE;
                }

            case "<":
                if ($row['data'] < $filter['filter_value']) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            case "=":
                if ($row['data'] == $filter['filter_value']) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            case ">=":
                if ($row['data'] >= $filter['filter_value']) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            case "<=":
                if ($row['data'] <= $filter['filter_value']) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            case "!=":
                if ($row['data'] != $filter['filter_value']) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            default:
                return FALSE;
        }
    }

}

?>
