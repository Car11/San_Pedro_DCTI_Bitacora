<?php 
    //print "ok vv1";
    include("php/Visitante.php");
    $v= new Visitante();
    if (isset($_POST['cedula'])) { 
        $v->cedula=$_POST['cedula'];        
    }
    else {
        header('Location: Error.html?id=NoPOST_CEDULA');
        exit;
    }
    //
    if (isset($_POST['detalle'])) {
        $v->detalle=$_POST['detalle'];
    }
    //
    $v->Existe();
?>