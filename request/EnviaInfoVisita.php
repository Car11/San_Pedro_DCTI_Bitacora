<?php
    if (!isset($_SESSION))
	   session_start();
    include("../class/formulario.php");
    $formulario= new formulario();
    //
    $idvisitante="";
    if (isset($_POST['cedula'])) {
        $idvisitante=$_POST['cedula'];
    }
    else {
        $_SESSION['errmsg']= "No post cedula.";
        header('Location: ../Error.php');
        exit;
    }
    if (isset($_POST['detalle'])) {
        $formulario->motivovisita=$_POST['detalle'];
    }
    else {
        $_SESSION['errmsg']= "No post detalle.";
        header('Location: ../Error.php');
        exit; 
    }
    //if (isset($_POST['sala'])) {        
    if (isset($_POST['sala'])) {
        $formulario->idsala=$_POST['sala'];
    }
    else {
        $_SESSION['errmsg']= "No post sala.";
        header('Location: ../Error.php');
        exit;
    }
    // agrega informacion de visita al formulario de ingreso y envia correo a Operación y espera respuesta
    $formulario->AgregarTemporal($idvisitante);
?>
