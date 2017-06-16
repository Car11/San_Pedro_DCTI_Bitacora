<?php
    include("php/Formulario.php");
    $v= new Formulario(); 

    /*
    if (isset($_POST['idtramitador'])) {
        $v->idtramitador=$_POST['idtramitador'];
    }
    else header('Location: Error.html');
    
    if (isset($_POST['idautorizador'])) {
        $v->idautorizador=$_POST['idautorizador'];
    }
    else header('Location: Error.html');
    if (isset($_POST['idresponsable'])) {
        $v->idresponsable=$_POST['idresponsable'];
    }
    else header('Location: Error.html');*/

    if (isset($_POST['fechaingreso'])) {
        $v->fechaingreso=$_POST['fechaingreso'];
    }
    else header('Location: MenuAdmin.php');
    
    if (isset($_POST['fechasalida'])) {
        $v->fechasalida=$_POST['fechasalida'];
    }
    else header('Location: Error.html');

    if (isset($_POST['fechasolicitud'])) {
        $v->fechasolicitud=$_POST['fechasolicitud'];
    }
    else header('Location: Error.html');
    if (isset($_POST['idsala'])) {              
        $v->idsala=$_POST['idsala'];
    }
    else header('Location:Error.html');
    if (isset($_POST['placavehiculo'])) {
        $v->placavehiculo=$_POST['placavehiculo'];
    }
    else header('Location: Error.html');
    if (isset($_POST['detalleequipo'])) {
        $v->detalleequipo=$_POST['detalleequipo'];
    }
    else header('Location: Error.html');
    if (isset($_POST['motivovisita'])) {
        $v->motivovisita=$_POST['motivovisita'];
    }
    else header('Location: Error.html');
    if (isset($_POST['visitantearray'])) {  
        $v->visitante=$_POST['visitantearray'];
    }
    else header('Location: Error.html');

    if (!isset($_SESSION))
		session_start();

    $v->AgregarFormulario();
    
?>