<?php
    require_once("../class/Responsable.php");
    $responsable= new Responsable(); 

    if (isset($_POST['txtnombre'])) {
        $responsable->nombre=$_POST['txtnombre'];
    }
    else header('Location: ../Error.php');
    
    if (isset($_POST['txtcedula'])) {
        $responsable->cedula=$_POST['txtcedula'];
    }
    else header('Location: ../Error.php');
    if (isset($_POST['txtempresa'])) {              
        $responsable->empresa=$_POST['txtempresa'];
    } 
    else header('Location: ../Error.php');

    if (isset($_POST['idresponsable'])) {              
        $responsable->id=$_POST['idresponsable'];
        $responsable->Elimina();
        exit;
    } 
    else header('Location: ../Error.php');
    
    $responsable->Inserta();
?>