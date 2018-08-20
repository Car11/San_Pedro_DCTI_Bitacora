<?php
    require_once("../class/Responsable.php");
    $responsable= new Responsable(); 

    if (isset($_POST['txtnombre'])) {
        $responsable->nombre=$_POST['txtnombre'];
    }
    else error_log($e->getMessage());
    
    if (isset($_POST['txtcedula'])) {
        $responsable->cedula=$_POST['txtcedula'];
    }
    else error_log($e->getMessage());
    if (isset($_POST['txtempresa'])) {              
        $responsable->empresa=$_POST['txtempresa'];
    } 
    else error_log($e->getMessage());

    if (isset($_POST['idresponsable'])) {              
        $responsable->id=$_POST['idresponsable'];
        $responsable->Elimina();
        exit;
    } 
    else error_log($e->getMessage());
    
    $responsable->Inserta();
?>