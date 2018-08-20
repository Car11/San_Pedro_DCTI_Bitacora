<?php
    if (!isset($_SESSION))
	   session_start();
    require_once("../class/Formulario.php");
    $formulario= new formulario();
    //    
    if (isset($_POST['detalle'])) {
        $formulario->motivovisita=$_POST['detalle'];
    }
    else {
        $_SESSION['errmsg']= "No post detalle.";
        error_log($e->getMessage());
        exit; 
    }
    //if (isset($_POST['sala'])) {        
    if (isset($_POST['sala'])) {
        $formulario->nombresala=$_POST['sala'];
    }
    else {
        $_SESSION['errmsg']= "No post sala.";
        error_log($e->getMessage());
        exit;
    }
    // agrega informacion de visita al formulario de ingreso y envia correo a Operación y espera respuesta
    $formulario->AgregarTemporal($_SESSION['idvisitante']);
?>
