<?php

$directory = 'unit60/';
if ($handle = opendir($directory)) { 
    while (false !== ($fileName = readdir($handle))) {     
        $newName = str_replace("UI_UNIT_ICONS","",$fileName);
        rename($directory . $fileName, $directory . $newName);
    }
    closedir($handle);
}