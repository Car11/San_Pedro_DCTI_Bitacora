<?php 

class Visitante{
	public $cedula;
	public $nombre;
	public $empresa;
	
	function __construct(){
        require_once("conexion.php");
    }
	
	function Existe(){
        //require_once("conexion.php");
        try {
            $sql='SELECT * FROM visitante where cedula=:cedula';
            $param= array(':cedula'=>$this->cedula);
            $result = DATA::Ejecutar($sql,$param);
            //
            if ( count($result) ) { 
                //foreach($result as $row) {
                //print_r($row);
                //}
                //registrar ingreso
                $this->Bitacora();
            } else {
                // si la cedula no está regisrada, debe mostrar formulario de ingreso
                header('Location: perfil.php?id='.$this->cedula);
                exit;
            }
        }     
        catch(Exception $e) {
            header('Location: Error.html?id='.$e->getMessage());
        }
    }
    
    function Agregar(){
        //require_once("conexion.php");
        try {
            $sql='INSERT INTO visitante (nombre, cedula, empresa)VALUES (:nombre, :cedula, :empresa)';
            $param= array(':nombre'=>$this->nombre,':cedula'=>$this->cedula,':empresa'=>$this->empresa);
            $result = DATA::Ejecutar($sql,$param);
            //Agrega la entrada
            $this->Bitacora();
        }     
        catch(Exception $e) {
            header('Location: Error.html?id='.$e->getMessage());
        }
    }
    
    function Bitacora(){
        //require_once("conexion.php");
        try {
            $sql='INSERT INTO bitacora (cedula) VALUES (:cedula)';
            $param= array(':cedula'=>$this->cedula);
            $result = DATA::Ejecutar($sql,$param);
            //if ( count($result) ) 
            header('Location: index.php?ins=1');
            //else header('Location: index.php?ins=0');
        }     
        catch(Exception $e) {
            header('Location: Error.html?id='.$e->getMessage());
        }
    }
}


?>