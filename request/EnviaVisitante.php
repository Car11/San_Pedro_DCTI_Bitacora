<?php 
    require_once("../class/Visitante.php");
    $visitante= new Visitante();
    //
    if (isset($_POST['cedula'])) { 
        $visitante->cedula=$_POST['cedula'];        
    }
    else {
        header('Location: ../Error.php');
        exit;
    }
    $visitante->ValidaID();
?>