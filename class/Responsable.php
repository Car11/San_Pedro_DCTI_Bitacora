<?php 
if (!isset($_SESSION))
    session_start();

if(isset($_POST["action"])){
    if($_POST["action"]=="Eliminar"){
        $responsable= new Responsable();
        $responsable->Elimina();
    }
    if($_POST["action"]=="Insertar"){
        $responsable= new Responsable();
        $responsable->Inserta();
    }
    if($_POST["action"]=="Modificar"){
        $responsable= new Responsable();
        $responsable->Modifica();
    }
    if($_POST["action"]=="Cargar"){
        $responsable= new Responsable();
        $responsable->Cargar();
    }
}

class Responsable{

    public $id;
    public $nombre;
	public $cedula;
	public $empresa;
    	
	function __construct(){
        require_once("Conexion.php");
        //error_reporting(E_ALL);
        // Always in development, disabled in production
        //ini_set('display_errors', 1);
    }
	    
    //Modifica Responsable 
    function Modifica(){
        try {                    
            $sql="UPDATE responsable SET nombre=:nombre,cedula=:cedula,empresa=:empresa WHERE id=:idresponsable";
            $param= array(':idresponsable'=>$_POST["idresponsable"],
                          ':nombre'=>$_POST["nombre"],
                          ':cedula'=>$_POST["cedula"],
                          ':empresa'=>$_POST["empresa"]);            
            $result = DATA::Ejecutar($sql,$param);
            header("location:../ResponsableMantenimiento.php");
            exit;
        }     
        catch(Exception $e) {
            header('Location: ../Error.php?w=visitante-agregar&id='.$e->getMessage());
            exit;
        }
    }

    //Inserta Responsable 
    function Inserta(){
        try {                    
            $sql="INSERT INTO responsable(nombre,cedula,empresa) VALUES (:nombre,:cedula,:empresa)";
            $param= array(':nombre'=>$_POST["nombre"],
                          ':cedula'=>$_POST["cedula"],
                          ':empresa'=>$_POST["empresa"]);            
            $result = DATA::Ejecutar($sql,$param);
            header("location:../ResponsableMantenimiento.php");
            exit;
        }     
        catch(Exception $e) {
            header('Location: ../Error.php?w=visitante-agregar&id='.$e->getMessage());
            exit;
        }
    }
    
    //Carga la lista de responsables 
    function Cargar(){
        try {
			$sql = "SELECT id,nombre,cedula,empresa FROM responsable WHERE id=:idresponsable";
			$param= array(':idresponsable'=>$_POST["idresponsable"]);            
            $result = DATA::Ejecutar($sql,$param);
			echo json_encode($result);			
		}catch(Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }		 	
    }    
    
    //Consulta formulario para llenar tabla 
    function Consulta(){
        try {
			$sql = "SELECT id,nombre,cedula,empresa FROM responsable";
			$result = DATA::Ejecutar($sql);
			return $result;			
		}catch(Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }		 	
    } 

    //Elimina responsable de acuerdo al ID

    function Elimina(){
        try {
			$sql="DELETE FROM responsable WHERE id=:idresponsable";
            $param= array(':idresponsable'=>$_POST['idresponsable']);            
            DATA::Ejecutar($sql,$param);

            $sql = "SELECT count(id) FROM formulario WHERE idresponsable=:idresponsable";
            $param= array(':idresponsable'=>$_POST['idresponsable']);            
            $result = DATA::Ejecutar($sql,$param);
			echo json_encode($result);			
		}catch(Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }		 	

    }
    
    //Carga la lista de resposanbles
    function Carga(){
        try {
			$sql = "SELECT id,nombre,cedula,empresa FROM responsable WHERE id = :identificador";
            
            $param= array(':identificador'=>$this->id);            
            $result = DATA::Ejecutar($sql,$param);
            
			return $result;			
		}catch(Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }		 	
	} 

    function ExisteResponsable(){
        try {
			$sql = "SELECT count(id) FROM formulario WHERE idresponsable=:idresponsable";
            $param= array(':idresponsable'=>$_POST['idresponsable']);            
            $result = DATA::Ejecutar($sql,$param); 
			echo json_encode($result);			
		}catch(Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }

}
?>