<?php 
    include("php/Visitante.php");
    $visitante= new Visitante();
    //
    if (isset($_POST['cedula'])) { 
        $visitante->cedula=$_POST['cedula'];        
    }
    else {
        $_SESSION['errmsg']= "No post cedula.";
        header('Location: Error.php');
        exit;
    }
    if (isset($_POST['detalle'])) {
        $visitante->detalle=$_POST['detalle'];    
    }
    if (isset($_POST['sala'])) {
        $visitante->sala=$_POST['sala'];
    }
    //
    $visitante->ValidaID();
?>