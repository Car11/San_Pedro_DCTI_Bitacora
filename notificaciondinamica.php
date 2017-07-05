<?php
//creating Event stream 
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
//
if(isset($_SESSION['TEMP']))
    unset($_SESSION['TEMP']);
//
if (isset($_GET['msg'])) {
    $msg=strip_tags($_GET['msg']);
}   

function sendMsg($msg)
{
    echo "data: $msg" . PHP_EOL;
    flush();
}

$nFile= "_chat.txt";

if (!empty($msg)) {
    $arraymsg = explode(",", $msg);
    for ($i=0; $i < count($arraymsg)-1; $i++) { 
        // id unica
        $idu = uniqid();
        $msg =  $idu .  '-' . $arraymsg[$i];
        $fp = fopen($nFile, 'a');
        fwrite($fp, $msg . PHP_EOL);
        fclose($fp);
    }
}

if (file_exists($nFile) && filesize($nFile) > 0) {
    $arrhtml=file($nFile);
    $html=$arrhtml[0];
}
if(isset($html))
    sendMsg($html);
