<?php
    include("php/Visitante.php");
    $v= new Visitante(); 
    if (isset($_POST['cedula'])) {
        $v->cedula=$_POST['cedula'];
    }
    else header('Location: Error.html');
    if (isset($_POST['empresa'])) {
        $v->empresa=$_POST['empresa'];
    }
    else header('Location: Error.html');
    if (isset($_POST['nombre'])) {
        $v->nombre=$_POST['nombre'];
    }
    else header('Location: Error.html');
    if (isset($_POST['detalle'])) {
        $v->detalle=$_POST['detalle'];
    }        
    $v->Agregar();
?>