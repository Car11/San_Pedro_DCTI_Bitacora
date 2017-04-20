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
	
    function Conectar(){       
        $sql= sprintf("SELECT codigo FROM USUARIO where CODIGO= '%s'  AND CLAVE= '%s' ", $this->username , $this->password);
        $result = DATA::EjecutarSQL($sql);
        if ( odbc_num_rows($result) ) { 
            header('Location: ConsultaBitacora.php');
            exit;
        } else 
        {   
            print('<br>Resutado no <br>');
            exit;
        }
    }
}
?>