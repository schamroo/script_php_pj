<?php
$dir = "C:\wamp64\www\printjob\Fleuriste";
$dh  = opendir($dir);
while (false !== ($filename = readdir($dh)))
{
    if ($filename == '.' or $filename == '..') continue;
    else(
    $files[] = $filename);
}
sort($files);
print_r($files);