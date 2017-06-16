<?php
    if (!isset($_SESSION))
	   session_start();
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
    if (isset($_POST['empresa'])) {
        $visitante->empresa=$_POST['empresa'];
    }
    else {
        $_SESSION['errmsg']= "No post empresa.";
        header('Location: Error.php');
        exit; 
    }
    if (isset($_POST['nombre'])) {
        $visitante->nombre=$_POST['nombre'];
    }
    else {
        $_SESSION['errmsg']= "No post nombre.";
        header('Location: Error.php');
        exit;
    }
    //Agrega el nuevo perfil y pide datos de ingreso, para notificación a OP.
    $data = $visitante->Agregar();
    if ( count($data) ) { 
        //Muestra pagina de ingreso se informacion de visita
         header('Location: InfoVisita.php?id='. $visitante->cedula);
        exit;
    } //else {print "no data";exit;}
?>