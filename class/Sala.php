<?php

if(isset($_POST["action"])){
    if($_POST["action"]=="Cargar"){
        $Sala= new Sala();
        $Sala->Disponibles();
    }
    if($_POST["action"]=="CargarporDataCenter"){
        $Sala= new Sala();
        $Sala->DisponiblesDataCenter();
    }
}

class Sala{
    function __construct(){   
    require_once("Conexion.php");
    //error_reporting(E_ALL);
    //Always in development, disabled in production
    //ini_set('display_errors', 1);
    }

    function Disponibles(){
        try {
            $sql='SELECT id,nombre FROM sala WHERE iddatacenter = :iddatacenter order by nombre asc';
            $param= array(':iddatacenter'=>$_POST["iddatacenter"]);  
            $data = DATA::Ejecutar($sql,$param);
            if (count($data)) {
                $this->id= $data[0]['id'];
                $this->nombre= $data[0]['nombre'];
            }
            echo json_encode($data);
        }     
        catch(Exception $e) {
            header('Location: ../Error.php');
            exit;
        }
    }

    function DisponiblesDataCenter(){
        try {
            $sql='SELECT id,nombre FROM sala WHERE iddatacenter = (SELECT id FROM datacenter WHERE nombre=:nombredatacenter) order by nombre asc';
            $param= array(':nombredatacenter'=>$_POST["nombredatacenter"]);  
            $data = DATA::Ejecutar($sql,$param);
            if (count($data)) {
                $this->id= $data[0]['id'];
                $this->nombre= $data[0]['nombre'];
            }
            echo json_encode($data);
        }     
        catch(Exception $e) {
            header('Location: ../Error.php');
            exit;
        }
    }

}
    


?>