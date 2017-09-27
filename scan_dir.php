<?php
//get all svg files from directory
$dir = "C:\wamp64\www\printjob\Fleuriste";
$dh  = opendir($dir);

while (false !== ($filename = readdir($dh)))
{
    if ($filename == '.' or $filename == '..')
        continue;
    else (
        $files[] = $filename);
}
sort($files);
for ($i = 0; $i <  count($files); $i++) {
    $key = key($files);
    $val = $files[$key];

        if ($val <> ' ') {
       // echo $val;

        //Verifier les noms de fichier svg

        //if (strpos(file_get_contents("name.txt"), $val) !== false) {

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
                            $path_parts=pathinfo($val);
                            echo $path_parts['filename'] . " : " . $id . " <br> ";

                            // verify nomenclature of filename
                            $ex   = $path_parts['filename'];
                            if( strpos(file_get_contents("name.txt"),$ex) !== false) {
                                echo 'nomenclature respecter !';
                            }
                            else{
                                echo 'fichier:'.$ex.' '.'est en erreur';
                            }


                            if ($child->nodeType === 1 && ($id === "color1" || $id === "color2" || $id === "color3" || $id === "color4")) {
                                processChildren($id, $child->childNodes, $arrayTest[$id]);
                            }
                        }
                    }
                }
            }

            // Suppression des styles CSS d'Illustrator
            // Cette partie n'est pas forcément utile pour la vérification des calques

            $styleTags = $document->getElementsByTagName('style');

            foreach ($styleTags as $styleTag) {
                $styleTag->parentNode->removeChild($styleTag);
            }
        }
        next($files);
    //}
    //else{
       // echo 'Fichier:'.$val. 'est en erreur';
  // }
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