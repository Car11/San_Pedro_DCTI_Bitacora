<?php
    require_once("../class/Formulario.php");
    $formulario= new Formulario(); 

    if (isset($_POST['fechaingreso'])) {
        $formulario->fechaingreso=$_POST['fechaingreso'];
    }
    else error_log($e->getMessage());
    
    if (isset($_POST['fechasalida'])) {
        $formulario->fechasalida=$_POST['fechasalida'];
    }
    else error_log($e->getMessage());
    if (isset($_POST['selectsala'])) {              
        $formulario->nombresala=$_POST['selectsala'];
    } 
    else error_log($e->getMessage());
    
    if (isset($_POST['placavehiculo'])) {
        $formulario->placavehiculo=$_POST['placavehiculo'];
    }
    else error_log($e->getMessage());
    
    if (isset($_POST['detalleequipo'])) {
        $formulario->detalleequipo=$_POST['detalleequipo'];
    }
    else error_log($e->getMessage());
    if (isset($_POST['motivovisita'])) {
        $formulario->motivovisita=$_POST['motivovisita'];
    }
    else error_log($e->getMessage());
    
    if (isset($_POST['visitantearray'])) {  
        $formulario->visitante=$_POST['visitantearray'];
    }
    else error_log($e->getMessage());
    if (isset($_POST['txtresponsable'])) {  
        $formulario->nombreresponsable=$_POST['txtresponsable'];
    }
    else error_log($e->getMessage());
    if (isset($_POST['txttramitante'])) {  
        $formulario->nombretramitante=$_POST['txttramitante'];
    }
    else error_log($e->getMessage());
    if (isset($_POST['txtautorizador'])) {  
        $formulario->nombreautorizador=$_POST['txtautorizador'];
    }
    else error_log($e->getMessage());
    if (isset($_POST['estadoformulario'])) {  
        $formulario->estado=$_POST['estadoformulario'];
    }
    else error_log($e->getMessage());
    if (isset($_POST['txtrfc'])) {  
        $formulario->rfc=$_POST['txtrfc'];
    }
    else error_log($e->getMessage());

    if ($_POST['idformulario']=="nuevo") {  
        $formulario->AgregarFormulario();
    }
    else {
        $formulario->id=$_POST['idformulario'];
        $formulario->Modificar();
        
    } 
?>