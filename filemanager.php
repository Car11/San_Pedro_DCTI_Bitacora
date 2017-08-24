<?php
    // Sesion de usuario
    include("class/Sesion.php");
    $sesion = new Sesion();
    if (!$sesion->estado){
        header('Location: Login.php');
        exit;
    }
    //
    if(file_exists("_chat.txt")){
        $lines = file('_chat.txt'); 
        //$last = sizeof($lines) - 1 ; 
        //unset($lines[$last]); 
        array_shift($lines);
        //
        $fp = fopen('_chat.txt', 'w'); 
        fwrite($fp, implode('', $lines)); 
        fclose($fp); 
    }
    
?>