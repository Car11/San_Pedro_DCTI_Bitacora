<?php 
session_start();
class Formulario{
	/* 
    public $idtramitador;
    public $idautorizador;
    public $idresponsable;
    */
    public $fechaingreso;
	public $fechasalida;
	public $fechasolicitud;
    public $idsala;
    public $placavehiculo;
    public $detalleequipo;
    public $motivovisita;
    public $visitante;
    	
	function __construct(){
        require_once("conexion.php");
        error_reporting(E_ALL);
        // Always in development, disabled in production
        ini_set('display_errors', 1);
    }
	    
    //Agrega formulario 
    function AgregarFormulario(){
        try {                    
            $sql='INSERT INTO formulario(fechaingreso,
                                        idsala, 
                                        fechasolicitud,
                                        fechasalida,
                                        placavehiculo,
                                        detalleequipo,
                                        motivovisita) 
                                        VALUES (:fechaingreso,
                                                :idsala,
                                                :fechasolicitud,
                                                :fechasalida,
                                                :placavehiculo,
                                                :detalleequipo,
                                                :motivovisita)';
            $param= array(':fechaingreso'=>$this->fechaingreso,
                          ':idsala'=>$this->idsala,
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
            
            print '<script language="javascript">alert("Formulario Insertado Correctamente!");</script>';
            header('Location:FormularioIngreso.php');
        }     
        catch(Exception $e) {
            header('Location: Error.html?w=visitante-agregar&id='.$e->getMessage());
            exit;
        }
    }
    
    //Consulta formulario para llenar tabla 
    function ConsultaFormulario(){
        try {
			require_once("conexion.php");
			$sql = "SELECT id,fechasolicitud,fechaingreso,fechasalida,estado,motivovisita FROM formulario";
			$result = DATA::Ejecutar($sql);
			return $result;			
		}catch(Exception $e) {
            header('Location: Error.html?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }		 	
	 }    

}


?>