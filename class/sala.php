<?php
class Sala{
    function __construct(){   
    require_once("Conexion.php");
    //error_reporting(E_ALL);
    //Always in development, disabled in production
    //ini_set('display_errors', 1);
    }

    function Disponibles(){
        try {
            $sql='SELECT ID, NOMBRE FROM sala order by nombre asc';
            $result = DATA::Ejecutar($sql);
            return $result;
        }     
        catch(Exception $e) {
            header('Location: ../Error.php');
            exit;
        }
    }

}
    


?>