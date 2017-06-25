<?php 
//session_start();
class usuario{
	public $usuario;
	public $contrasena;
    public $idrol;
	
	function __construct(){
        require_once("conexion.php");
    }
	
    function Validar(){    
        $sql='SELECT USUARIO, IDROL FROM USUARIO where CONTRASENA=:contrasena  AND USUARIO=:usuario';
        $param= array(':usuario'=>$this->usuario, ':contrasena'=>$this->contrasena);        
        $data = DATA::Ejecutar($sql,$param);
        if (count($data) ) {
            $this->idrol= $data[0]['IDROL'];
            return true;
        }else {        
            return false;           
        }        
    }
}
?>