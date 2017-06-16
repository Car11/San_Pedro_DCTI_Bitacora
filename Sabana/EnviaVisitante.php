<?php 
    include("Visitante.php");
    $visitante= new Visitante();
    $visitante->sala="SABANA";
    if (isset($_POST['cedula'])) { 
        $visitante->cedula=$_POST['cedula'];        
    }
    else {
        header('Location: Error.html?id=NoPOST_CEDULA');
        exit;
    }
    //
    if (isset($_POST['detalle'])) {
        $visitante->detalle=$_POST['detalle'];
    }
    //
    $visitante->Existe();
?>