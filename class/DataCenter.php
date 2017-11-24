<?php

if (!isset($_SESSION)) {
    session_start();
}

if(isset($_POST["action"])){
    if($_POST["action"]=="SeleccionarDataCenter"){
            $DataCenter= new DataCenter();
            $DataCenter->SeleccionarDataCenter();
    }
    if($_POST["action"]=="Default"){
            $DataCenter= new DataCenter();
            $DataCenter->Default();
    }
}

class DataCenter{
    function __construct(){   
    require_once("Conexion.php");
    
    }

    function SeleccionarDataCenter(){
        try {
            $sql='SELECT id,nombre FROM datacenter order by nombre asc';         
            $data = DATA::Ejecutar($sql);
            if (count($data)) {
                $this->id= $data[0]['id'];
                $this->nombre= $data[0]['nombre'];
            }
            echo json_encode($data);			
        }catch(Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }

    function Default(){
        try {
            $sql="SELECT id,nombre FROM datacenter WHERE nombre = 'SAN PEDRO'";         
            $data = DATA::Ejecutar($sql);
            if (count($data)) {
                $this->id= $data[0]['id'];
                $this->nombre= $data[0]['nombre'];
            }
            echo json_encode($data);			
        }catch(Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }

}
    


?>