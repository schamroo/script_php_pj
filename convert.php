<?php

$cmjn = convertRVBtoCMJN($argv[1]);

echo 'C '. $cmjn['C'] .' %' . PHP_EOL;
echo 'M '. $cmjn['M'] .' %' . PHP_EOL;
echo 'J '. $cmjn['J'] .' %' . PHP_EOL;
echo 'N '. $cmjn['N'] .' %' . PHP_EOL;

function convertRVBtoCMJN($hex) {
    // Conversion de l'hexa en 3 valeurs RVB comprise entre 0 et 1
    list($r, $g, $b) = sscanf($hex, "%02x%02x%02x");
    $r /= 255;
    $g /= 255;
    $b /= 255;

    // Conversion du RVB vers CMJ
    $C = 1 - $r;
    $M = 1 - $g;
    $J = 1 - $b;

    // Conversion du CMJ vers CMJN
    $N = min($C, $M, $J);
    //echo $N;

    $c = ceil(($C-$N)/(1-$N) * 100);
    $m = ceil(($M-$N)/(1-$N) * 100);
    $j = ceil(($J-$N)/(1-$N) * 100);
    $n = ceil($N * 100);

    return array(
        'C' => $c,
        'M' => $m,
        'J' => $j,
        'N' => $n,
    );
}
