<?php
class Sala{
    function __construct(){   
    require_once("conexion.php");
    //error_reporting(E_ALL);
    //Always in development, disabled in production
    //ini_set('display_errors', 1);
    }

    function Disponibles(){
        try {
            $sql='SELECT ID, NOMBRE FROM controlaccesocdc_dbp.sala order by nombre asc';
            $result = DATA::Ejecutar($sql);
            return $result;
        }     
        catch(Exception $e) {
            header('Location: ../Error.php?w=sala&id='.$e->getMessage());
            exit;
        }
    }

}
    


?>