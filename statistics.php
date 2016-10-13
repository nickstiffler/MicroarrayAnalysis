

<?php

// Adapted from Numerical Recipes in C: The Art of Scientific Computing, Second Edition 
// William H. Press - Harvard-Smithsonian Center for Astrophysics 
// Saul A. Teukolsky - Department of Physics, Cornell University
// William T. Vetterling - Polaroid Corporation 
// Brian P. Flannery - EXXON Research and Engineering Company
// Equal variance

function TTest($data1, $data2) {

    $n1 = count($data1);
    $n2 = count($data2);

    if ($n1 == 0 || $n2 == 0)
        return 0;

    $avg1 = mean($data1);
    $avg2 = mean($data2);

    $var1 = variance($data1, $avg1);
    $var2 = variance($data2, $avg2);

    // Degrees of freedom
    $df = $n1 + $n2 - 2;
    $variance = (($n1 - 1) * $var1 + ($n2 - 1) * $var2) / $df;
    $t = ($avg1 - $avg2) / sqrt($variance * (1 / $n1 + 1 / $n2));

    return betaI(.5 * $df, .5, $df / ($df + $t * $t));
}

// Unequal variance
function TUTest($data1, $data2) {
    $n1 = count($data1);
    $n2 = count($data2);

    if ($n1 <= 1 || $n2 <= 1)
        return 0;

    $avg1 = mean($data1);
    $avg2 = mean($data2);

    $var1 = variance($data1, $avg1);
    $var2 = variance($data2, $avg2);

    $t = ($avg1 - $avg2) / sqrt($var1 / $n1 + $var2 / $n2);
    $dum = pow(($var1 / $n1 + $var2 / $n2), 2);
    $df = $dum / (pow(($var1 / $n1), 2) / ($n1 - 1) + pow(($var2 / $n2), 2) / ($n2 - 1));

    if (is_nan($df / ($df + $t * $t))) {
        print_r($data1);
        print_r($data2);
    }

    $betaI = betaI(.5 * $df, .5, $df / ($df + $t * $t));
    return $betaI;
}

// Calculate the Mean of this array
function mean($data) {
    $sum = array_sum($data);
    $count = count($data);
    if ($count == 0)
        return 0;
    else
        return ($sum / $count);
}

function variance($data, $avg) {
    $var = $ep = 0;
    $n = count($data);
    if ($n <= 1) {
        return 0;
    }
    for ($i = 0; $i < $n; $i++) {
        $s = $data[$i] - $avg;
        $ep += $s;
        $var += $s * $s;
    }

    return ($var - $ep * $ep / $n) / ($n - 1);
}

function betaI($a, $b, $x) {
    $bt = 0.0;
    if ($x < 0 || $x > 1)
        die("Bad x in routine betai: " . $x);

    if ($x != 0 && $x != 1) // Factors in front of the continued fraction. 
        $bt = exp(gammLN($a + $b) - gammLN($a) - gammLN($b) + $a * log($x) + $b * log(1.0 - $x));
    if ($x < ($a + 1.0) / ($a + $b + 2.0)) // Use continued fraction directly. 
        return $bt * betaCF($a, $b, $x) / $a;

    else // Use continued fraction after making the symmetry transformation.
        return 1.0 - $bt * betaCF($b, $a, 1.0 - $x) / $b;
}

/*

  Used by betai: Evaluates continued fraction for incomplete beta function by modi ed Lentz s method (§5.2).

 */

function betaCF($a, $b, $x) {

    $itmax = 100;
    $eps = 3.0e-7;
    $fpmin = 1.0e-30;


    $qab = $a + $b; // These q s will be used in factors that occur in the coe cients (6.4.6). 
    $qap = $a + 1.0;
    $qam = $a - 1.0;
    $c = 1.0; // First step of Lentz s method. 
    $d = 1.0 - $qab * $x / $qap;
    if (abs($d) < $fpmin)
        $d = $fpmin;
    $d = 1.0 / $d;
    $h = $d;
    for ($m = 1; $m <= $itmax; $m++) {

        $m2 = 2 * $m;
        $aa = $m * ($b - $m) * $x / (($qam + $m2) * ($a + $m2));
        $d = 1.0 + $aa * $d; // One step (the even one) of the recurrence. 
        if (abs($d) < $fpmin)
            $d = $fpmin;
        $c = 1.0 + $aa / $c;
        if (abs($c) < $fpmin)
            $c = $fpmin;
        $d = 1.0 / $d;
        $h *= $d * $c;
        $aa = -($a + $m) * ($qab + $m) * $x / (($a + $m2) * ($qap + $m2));
        $d = 1.0 + $aa * $d; // Next step of the recurrence (the odd one). 
        if (abs($d) < $fpmin)
            $d = $fpmin;
        $c = 1.0 + $aa / $c;
        if (abs($c) < $fpmin)
            $c = $fpmin;
        $d = 1.0 / $d;
        $del = $d * $c;
        $h *= $del;
        if (abs($del - 1.0) < $eps)
            return $h;
    }
    if ($m > $itmax)
        die("a or b too big, or MAXIT too small in betacf");

    return $h;
}

/*

  Returns the value ln[ gamma(xx)] for xx > 0.

 */

function gammLN($xx) {

    $cof = array(76.18009172947146, -86.50532032941677, 24.01409824083091,
        -1.231739572450155, 0.1208650973866179e-2, -0.5395239384953e-5);
    $y = $x = $xx;
    $tmp = $x + 5.5;
    $tmp -= ($x + .5) * log($tmp);
    $ser = 1.000000000190015;
    for ($i = 0; $i < 6; $i++)
        $ser += $cof[$i] / ++$y;

    return -$tmp + log(2.5066282746310005 * $ser / $x);
}

// Function to calculate square of value - mean
function sd_square($x, $mean) {
    return pow($x - $mean, 2);
}

// Function to calculate standard deviation (uses sd_square)   
function std_dev($array) {

// square root of sum of squares devided by N-1
    return sqrt(array_sum(array_map("sd_square", $array, array_fill(0, count($array), (array_sum($array) / count($array))))) / (count($array) - 1));
}

function median($data) {


    sort($data);

    $n = count($data);
    $h = intval($n / 2);

    if ($n % 2 == 0) {
        $median = ($data[$h] + $data[$h - 1]) / 2;
    } else {
        $median = $data[$h];
    }

    return $median;
}
?>
