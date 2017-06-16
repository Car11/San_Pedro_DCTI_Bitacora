<?php
    
    if(file_exists("_chat.txt")){
        $lines = file('_chat.txt'); 
        $last = sizeof($lines) - 1 ; 
        unset($lines[$last]); 
        //
        $fp = fopen('_chat.txt', 'w'); 
        fwrite($fp, implode('', $lines)); 
        fclose($fp); 
    }
    
?>