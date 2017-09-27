<?php

//$xml=simplexml_load_file("C:\wamp64\www\printjob\geometry.svg") or die("Error: Cannot create object");
//print_r($xml);

$document = new DOMDocument();
//$document->loadXML("C:\wamp64\www\printjob\geometry.svg");
$document->load("C:\wamp64\www\printjob\Fleuriste\85_Geometre-expert_P1_Logo_12.svg");


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
                echo $id;

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