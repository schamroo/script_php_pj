<?php

$source = $argv[1];
$destination = $argv[2];

$colors = array(
	'color1' => 'F0FF0F',
	'color2' => 'FF0000',
	'color3' => '00FF00',
	'color4' => '0000FF'
);

$opacities = array(
	'color1' => '1',
	'color2' => '0.25',
	'color3' => '0.5',
	'color4' => '0.75'
);

$viewBox = setSVGColors($source, $colors, $opacities, $destination);
echo $viewBox[2] . ' x ' . $viewBox[3] . PHP_EOL;

/**
 * Fonction utilisée pour changer les 4 couleurs principales d'un logo.
 * @param $source string le logo au format SVG
 * @param $color array le tableau de 4 couleurs à utiliser
 * @param $opacity array le tableau des 4 opacités à utiliser
 * @param $destination string la destination de sauvegarde
 * @return array contenant la taille du fichier
 */
function setSVGColors($source, $color, $opacity, $destination) {

	$document = new DOMDocument();
	$document->load($source);

	$svgElement = $document->getElementsByTagName('svg')->item(0);
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

	// Application des couleurs et opacités
	// Cette partie n'est pas forcément utile pour la vérification des calques
	foreach ($arrayTest as $id => $items) {
		list($r, $g, $b) = sscanf($color[$id], "%02x%02x%02x");
		$colorValue = "rgb($r, $g, $b)";

		foreach ($items as $item) {
			if ($item->hasAttribute('fill')) {
				$item->setAttribute('fill', $colorValue);
			} else {
				$attr = $document->createAttribute('fill');
				$attr->value = $colorValue;
				$item->appendChild($attr);
			}

			if($item->hasAttribute('fill-opacity')) {
				$item->setAttribute('fill-opacity', $opacity[$id]);
			} else {
				$attr = $document->createAttribute('fill-opacity');
				$attr->value = $opacity[$id];
				$item->appendChild($attr);
			}
		}
	}

	// Sauvegarde du fichier modifié (pas forcément utile pour la vérif)
	$document->save($destination);

	// On renvoie la taille de la zone de travail (pas utile pour la vérif)
	return $viewBox;
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
