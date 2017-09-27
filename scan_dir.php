<?php
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
    $key=key($files);
    $val=$files[$key];

    if ($val<> ' ') {
        $document = new DOMDocument();
        $document->load($dir.DIRECTORY_SEPARATOR.$val);
        $svgElement = $document->getElementsByTagName( "g" )->item(0);
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
                        echo $val.":".$id." <br> ";
                        //echo $id;

                        if ($child->nodeType === 1 && ($id === "color1" || $id === "color2" || $id === "color3" || $id === "color4")) {
                            processChildren($id, $child->childNodes,$arrayTest[$id] );
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