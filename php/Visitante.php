<?php 
if (!isset($_SESSION))
    session_start();
class Visitante{
	public $cedula;
	public $nombre;
	public $empresa;
	
	function __construct(){
        require_once("conexion.php");
        error_reporting(E_ALL);
        // Always in development, disabled in production
        ini_set('display_errors', 1);
    }
    
    function ValidaID(){
        try{
            $sql='SELECT * FROM visitante where cedula=:cedula';
            $param= array(':cedula'=>$this->cedula);
            $data = DATA::Ejecutar($sql,$param);
            if (count($data)) { //la ID existe en bd
                $this->nombre=$data[0]['NOMBRE'];
                //Valida formulario de Ingreso
                // ...
            }
            else { //la ID no existe en bd, muestra nuevo perfil
                header('Location: nuevoperfil.php?id='.$this->cedula);
                exit;
            }
        }
        catch(Exception $e) {
            $_SESSION['errmsg']= $e->getMessage();
            header('Location: Error.php');
            exit;
        }
    }
    
    function Agregar(){
        try {
            $sql='INSERT INTO visitante (nombre, cedula, empresa) VALUES (:nombre, :cedula, :empresa)';
            $param= array(':nombre'=>$this->nombre,':cedula'=>$this->cedula,':empresa'=>$this->empresa);
            $data = DATA::Ejecutar($sql,$param);
            if($data)
                return true;
            else return false;
        }     
        catch(Exception $e) {
            $_SESSION['errmsg']= $e->getMessage();
            header('Location: Error.php');
            exit;
        }
    }
    
    function Cargar($ID){
        try {
            $sql='SELECT * FROM visitante where cedula=:cedula';
            $param= array(':cedula'=>$ID);
            $data= DATA::Ejecutar($sql,$param);
            return $data;
        }
        catch(Exception $e) {
            $_SESSION['errmsg']= $e->getMessage();
            header('Location: Error.php');
            exit;
        }
    }
    
    function CargarTodos(){
        try {
            $sql='SELECT cedula, nombre, empresa FROM visitante ORDER BY cedula';
            $data= DATA::Ejecutar($sql);
            return $data;
        }
        catch(Exception $e) {
            $_SESSION['errmsg']= $e->getMessage();
            header('Location: Error.php');
            exit;
        }
    }
}
?>