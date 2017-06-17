<?php 
//session_start();
class usuario{
	public $username;
	public $password;
	
	function __construct(){
        require_once("conexion.php");
        //error_reporting(E_ALL);
        // Always in development, disabled in production
        //ini_set('display_errors', 1);
    }
	
    function Validar(){    
        $sql='SELECT codigo FROM USUARIO where CODIGO=:codigo AND CLAVE=:clave';
        $param= array(':codigo'=>$this->username, ':clave'=>$this->password);        
        $result = DATA::EjecutarSQL($sql,$param);
        if (count($result) ) {
            return true;
        }else {        
            return false;           
        }        
    }
}
?>