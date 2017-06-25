<?php
    include("../class/Formulario.php");
    $v= new Formulario(); 

    if (isset($_POST['fechaingreso'])) {
        $v->fechaingreso=$_POST['fechaingreso'];
    }
    else header('Location: ../Error.php');
    
    if (isset($_POST['fechasalida'])) {
        $v->fechasalida=$_POST['fechasalida'];
    }
    else header('Location: ../Error.php');

    if (isset($_POST['fechasolicitud'])) {
        $v->fechasolicitud=$_POST['fechasolicitud'];
    }
    else header('Location: ../Error.php');
    
    if (isset($_POST['sala'])) {              
        $v->nombresala=$_POST['sala'];
    } 
    else header('Location: ../Error.php');
    
    if (isset($_POST['placavehiculo'])) {
        $v->placavehiculo=$_POST['placavehiculo'];
    }
    else header('Location: ../Error.php');
    
    if (isset($_POST['detalleequipo'])) {
        $v->detalleequipo=$_POST['detalleequipo'];
    }
    else header('Location: ../Error.php');

    if (isset($_POST['motivovisita'])) {
        $v->motivovisita=$_POST['motivovisita'];
    }
    else header('Location: ../Error.php');
    
    if (isset($_POST['visitantearray'])) {  
        $v->visitante=$_POST['visitantearray'];
    }
    else header('Location: ../Error.php');



    $v->AgregarFormulario();
    
?>