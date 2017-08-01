<?php
    if (!isset($_SESSION))
	   session_start();
    require_once("../class/formulario.php");
    $formulario= new formulario();
    //    
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
        $formulario->nombresala=$_POST['sala'];
    }
    else {
        $_SESSION['errmsg']= "No post sala.";
        header('Location: ../Error.php');
        exit;
    }
    // agrega informacion de visita al formulario de ingreso y envia correo a Operación y espera respuesta
    $formulario->AgregarTemporal($_SESSION['idvisitante']);
?>
