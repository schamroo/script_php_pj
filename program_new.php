<?php
//$dir=$argv[1];
$dir = "C:/wamp64/www/printjob/Fleuriste";
$files = openFile($dir);
//Access Each file in folder
for ($i = 0; $i <  count($files); $i++) {
    $key = key($files);
    $val = $files[$key];

    //remove extension from filename
    $path_parts = pathinfo($val);
    $name = $path_parts['filename'];

//Check if file is of format SVG
    $myArray = explode('.', $val);
    if ($myArray[1]!== 'svg'){

        echo "Fichier:".$name." pas au bon format".PHP_EOL;
    }
    else {
        //Verify that the name is according to the required nomenclature
        if (strpos(file_get_contents("C:/wamp64/www/printjob/name.txt"), $myArray[0]) == false) {

            //echo '***********************************************************'. PHP_EOL;
            echo "Fichier:" . $name . " Nomenclature non respecté" . PHP_EOL;
        }else{
            //echo '***********************************************************'. PHP_EOL;
            echo "Fichier:" . $name . " Nomenclature ok" . PHP_EOL;
        }
            // Verify the layers
            $document = new DOMDocument();
            $document->load($dir . DIRECTORY_SEPARATOR . $val);
            $svgElement = $document->getElementsByTagName("g")->item(0);
            $arrayTest = array(
                'color1' => array(),
                'color2' => array(),
                'color3' => array(),
                'color4' => array()
            );
            $viewBox = explode(" ", $svgElement->getAttribute('viewBox'));

            // On récupère les différents éléments de calques
            if ($svgElement->hasChildNodes()) {

                foreach ($svgElement->childNodes as $child) {

                    if ($child->hasAttributes()) {
                        $id = $child->attributes->getNamedItem('id');
                        if ($id != NULL) {
                            $id = $id->nodeValue;

                            echo $name . " : " . $id . PHP_EOL;

                            if ($child->nodeType === 1 && ($id === "color1" || $id === "color2" || $id === "color3" || $id === "color4")) {
                                processChildren($id, $child->childNodes, $arrayTest[$id]);
                            }
                        }
                    }
                }
            }

            //Si la couleur est unique est au format CMJN
        //$cmjn = convertRVBtoCMJN($argv[2]);
        $cmjn = convertRVBtoCMJN('833D5B');
        echo 'C '. $cmjn['C'] .' %' . PHP_EOL;
        echo 'M '. $cmjn['M'] .' %' . PHP_EOL;
        echo 'J '. $cmjn['J'] .' %' . PHP_EOL;
        echo 'N '. $cmjn['N'] .' %' . PHP_EOL;
        }
    next($files);
}

//All function used
//Function to open directory and read files
function openFile($dir){
    $dh  = opendir($dir);
    while (false !== ($filename = readdir($dh)))
    {
        if ($filename == '.' or $filename == '..')
            continue;
        else(
            $files[] = $filename );
    }
    sort($files);
    return $files;
}

function processChildren($id, $children, &$array) {

    foreach ($children as $child) {
        if ($child->nodeType == 1) {

            if ($child->tagName === "g") {
                processChildren($id, $child->childNodes, $array);
            } else {
                array_push($array, $child);
            }
        }
    }
}

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

?>
