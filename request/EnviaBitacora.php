<?php
    require_once("../class/Visitante.php");
    $bitacora= new Bitacora();
    if (isset($_POST['cedula'])) { 
        $bitacora->cedula=$_POST['cedula'];        
        if (isset($_POST['detalle'])) { 
            $bitacora->detalle=$_POST['detalle'];        
        }
        if (isset($_POST['idformulario'])) { 
            $bitacora->idformulario=$_POST['idformulario'];        
        }
        if (isset($_POST['idtarjeta'])) { 
            $bitacora->idtarjeta=$_POST['idtarjeta'];        
        }
    }
    else {
        error_log($e->getMessage());
        exit;
    }
    if($bitacora->Entrada())
    {
        header('Location: ../index.php');
        exit;
    }
    else {
        error_log($e->getMessage());
        exit;
    }
?>