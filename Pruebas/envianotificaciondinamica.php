<?php
//creating Event stream 
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
//
if (isset($_GET['msg'])) {
    $msg=strip_tags($_GET['msg']);
}   

function sendMsg($msg)
{
    echo "data: $msg" . PHP_EOL;
    flush();
}

if (!empty($msg)) {
    $id = uniqid();
    $msg =  $id .  '-' . $msg;
    $fp = fopen("../_chat.txt", 'a');
    fwrite($fp, $msg . PHP_EOL);
    fclose($fp);
}

if (file_exists("../_chat.txt") && filesize("../_chat.txt") > 0) {
    $arrhtml=file("../_chat.txt");
    $html=$arrhtml[0];
}
if(isset($html))
    sendMsg($html);
