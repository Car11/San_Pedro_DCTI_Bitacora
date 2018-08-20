<?php
    if (!isset($_SESSION))
	   session_start();
    require_once("../class/Visitante.php");
    $visitante= new Visitante(); 
    //
    if (isset($_POST['cedula'])) {
        $visitante->cedula=$_POST['cedula'];
    }
    else {
        error_log($e->getMessage());
        exit;
    }
    if (isset($_POST['empresa'])) {
        $visitante->empresa=$_POST['empresa'];
    }
    else {
        error_log($e->getMessage());
        exit; 
    }
    if (isset($_POST['nombre'])) {
        $visitante->nombre=$_POST['nombre'];
    }
    else {
        error_log($e->getMessage());
        exit;
    }
    //Agrega el nuevo perfil y pide datos de ingreso, para notificación a OP.
    if ( $visitante->Agregar() )  { 
        //Muestra pagina de ingreso se informacion de visita
        header('Location: ../InfoVisita.php?id='. $visitante->cedula);
        exit;
    } 
?>