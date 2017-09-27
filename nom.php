<?php
$ex   = "80_Fleuriste _P1_Logo_1";

if( strpos(file_get_contents("name.txt"),$ex) !== false) {
    echo $ex;
}
else{
    echo 'fichier:'.$ex.' '.'est en erreur';
}


?>

