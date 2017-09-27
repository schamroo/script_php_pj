<?php
$dir=$argv[1];
//$dir = "C:\wamp64\www\printjob\Fleuriste";
$files = openFile($dir);
//Access Each file in folder
$document = new DOMDocument();
for ($i = 0; $i <  count($files); $i++) {
    $key = key($files);
    $val = $files[$key];
//Check if file is of format SVG
    $myArray = explode('.', $val);
    if ($myArray[1]!== 'svg'){
        echo "Fichier:".$val." pas au bon format".PHP_EOL;
    }
    else{
        //Verify that the name is according to the required nomenclature

        if( strpos(file_get_contents("C:\wamp64\www\printjob/name.txt"),$myArray[0]) !== false){
            // Verify the layers

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
                            echo $val . " : " . $id .PHP_EOL;
                            //echo $id;

                            if ($child->nodeType === 1 && ($id === "color1" || $id === "color2" || $id === "color3" || $id === "color4")) {
                                processChildren($id, $child->childNodes, $arrayTest[$id]);
                            }
                        }
                    }
                }
            }

        }
        else{
            echo "Fichier:".$val." Nomenclature non respecté".PHP_EOL;
        }
    }

    next($files);

}

//All function used
//Function to open directory and read files
function openFile($dir){
    $dh  = opendir($dir);
    while (false !== ($filename = readdir($dh)))
    {
        if ($filename == '.' or $filename == '..') continue;
        else(
        $files[] = $filename);
    }
    sort($files);
    return $files;}

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

?>