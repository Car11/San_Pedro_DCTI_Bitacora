<?php 
if (!isset($_SESSION))
    session_start();
class Responsable{

    public $id;
    public $nombre;
	public $cedula;
	public $empresa;
    	
	function __construct(){
        require_once("conexion.php");
        //error_reporting(E_ALL);
        // Always in development, disabled in production
        //ini_set('display_errors', 1);
    }
	    
    //Agrega formulario 
    function Inserta(){
        try {                    
            $sql='INSERT INTO formulario(fechaingreso,idsala,fechasolicitud,fechasalida,placavehiculo,detalleequipo,motivovisita)'. 
                'VALUES (:fechaingreso,(SELECT sa.ID FROM SALA sa WHERE NOMBRE= :nombresala),:fechasolicitud,:fechasalida,:placavehiculo,'.
                ':detalleequipo,:motivovisita)';
            $param= array(':fechaingreso'=>$this->fechaingreso,
                          ':nombresala'=>$this->nombresala,
                          ':fechasolicitud'=>$this->fechasolicitud,
                          ':fechasalida'=>$this->fechasalida,
                          ':placavehiculo'=>$this->placavehiculo,
                          ':detalleequipo'=>$this->detalleequipo,
                          ':motivovisita'=>$this->motivovisita);            
            $result = DATA::Ejecutar($sql,$param);
            
            //Captura el id del formulario
            $idformulario = DATA::$conn->lastInsertId();
            //Convierte el string en un arreglo
            $visitantearray = explode(",",$this->visitante);            
            //Calcula la longitud del arreglo de visistantes
            $longitud = count($visitantearray);
            //Recorre el arreglo e inserta cada item en la tabla intermedia
            for($i=0; $i<$longitud; $i++){
                
                $sql='INSERT INTO visitanteporformulario(idvisitante,idformulario) VALUES (:idvisitante,:idformulario)';
                $param= array(':idvisitante'=>$visitantearray[$i],':idformulario'=>$idformulario); 
                $result = DATA::Ejecutar($sql,$param);
            }
            
            header('Location:../FormularioIngreso.php');
            exit;
        }     
        catch(Exception $e) {
            header('Location: ../Error.php?w=visitante-agregar&id='.$e->getMessage());
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

}
?>