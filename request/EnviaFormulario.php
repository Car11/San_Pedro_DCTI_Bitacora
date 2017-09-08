<?php
    require_once("../class/Formulario.php");
    $formulario= new Formulario(); 

    if (isset($_POST['fechaingreso'])) {
        $formulario->fechaingreso=$_POST['fechaingreso'];
    }
    else header('Location: ../Error.php');
    
    if (isset($_POST['fechasalida'])) {
        $formulario->fechasalida=$_POST['fechasalida'];
    }
    else header('Location: ../Error.php');
    if (isset($_POST['selectsala'])) {              
        $formulario->nombresala=$_POST['selectsala'];
    } 
    else header('Location: ../Error.php');
    
    if (isset($_POST['placavehiculo'])) {
        $formulario->placavehiculo=$_POST['placavehiculo'];
    }
    else header('Location: ../Error.php');
    
    if (isset($_POST['detalleequipo'])) {
        $formulario->detalleequipo=$_POST['detalleequipo'];
    }
    else header('Location: ../Error.php');
    if (isset($_POST['motivovisita'])) {
        $formulario->motivovisita=$_POST['motivovisita'];
    }
    else header('Location: ../Error.php');
    
    if (isset($_POST['visitantearray'])) {  
        $formulario->visitante=$_POST['visitantearray'];
    }
    else header('Location: ../Error.php');
    if (isset($_POST['txtresponsable'])) {  
        $formulario->nombreresponsable=$_POST['txtresponsable'];
    }
    else header('Location: ../Error.php');
    if (isset($_POST['txttramitante'])) {  
        $formulario->nombretramitante=$_POST['txttramitante'];
    }
    else header('Location: ../Error.php');
    if (isset($_POST['txtautorizador'])) {  
        $formulario->nombreautorizador=$_POST['txtautorizador'];
    }
    else header('Location: ../Error.php');
    if (isset($_POST['estadoformulario'])) {  
        $formulario->estado=$_POST['estadoformulario'];
    }
    else header('Location: ../Error.php');
    if (isset($_POST['txtrfc'])) {  
        $formulario->rfc=$_POST['txtrfc'];
    }
    else header('Location: ../Error.php');

    if ($_POST['idformulario']=="nuevo") {  
        $formulario->AgregarFormulario();
    }
    else {
        $formulario->id=$_POST['idformulario'];
        $formulario->Modificar();
        
    } 
?>