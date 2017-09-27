/**
* Created by PhpStorm.
* User: schamroo
* Date: 9/27/2017
*/

<?php
$dir = "C:\wamp64\www\printjob\Fleuriste";

 echo processDir($dir, $files);
//Function to read files from directory
        function processDir($dir, $files)
        {
            while (false !== ($filename = readdir(opendir($dir)))) {
                if ($filename == '.' or $filename == '..')
                    continue;
                else (
                $files[] = $filename);
            }
            for ($i = 0; $i <  count($files); $i++) {
                $key = key($files);
                $val = $files[$key];
                if ($val <> ' ') {
                    echo $val;
                }
            }

            sort($files);
        }
