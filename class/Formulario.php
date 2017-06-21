<?php 
if (!isset($_SESSION))
    session_start();
class Formulario{

    public $id;
    public $fechaingreso;
	public $fechasalida;
	public $fechasolicitud;
    public $idsala;
    public $placavehiculo;
    public $detalleequipo;
    public $motivovisita;
    public $visitante;
    public $idtramitador;
    public $idautorizador;
    public $idresponsable;
    	
	function __construct(){
        require_once("conexion.php");
        //error_reporting(E_ALL);
        // Always in development, disabled in production
        //ini_set('display_errors', 1);
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
            header('Location: ../Error.php?w=visitante-agregar&id='.$e->getMessage());
            exit;
        }
    }
    
    //Consulta formulario para llenar tabla 
    function ConsultaFormulario(){
        try {
			$sql = "SELECT id,fechasolicitud,estado,motivovisita,fechaingreso,fechasalida,idtramitador,
            idautorizador,idresponsable,idsala,placavehiculo,detalleequipo
            FROM formulario";
			$result = DATA::Ejecutar($sql);
			return $result;			
		}catch(Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }		 	
	 } 
    
    function EnviareMail($idvisitante){
        // smtpapl.correo.ice
        // puerto 25
        // ip 10.149.20.26
        // ICETEL\OperTI
        // Clave: Icetel2017
        // Buzón: OperacionesTI@ice.go.cr
        try{
            //consulta datos del visitante
            include("Visitante.php");        
            $visitante= new Visitante();
            $data= $visitante->Cargar($idvisitante);     
            //
            $nombre="";
            $empresa="";
            if (count($data)){ 
                $nombre= $data[0]['NOMBRE'];
                $empresa= $data[0]['EMPRESA'];
            }
            //
            ini_set('SMTP','smtpapl.correo.ice');
            //$to = "ZZT OFICINA PROCESAMIENTO <ofproc1@ice.go.cr>";
            $to= "cchaconc@ice.go.cr";   
            $from = "operTI@ice.go.cr";
            $asunto = "Formulario de Ingreso Pendiente";
            $mensaje = "<h2><i>FORMULARIO DE INGRESO<i><h2>";
            $mensaje .= '<html><body>';
            $mensaje .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
            $mensaje .= "<tr style='background: #eee;'><td><strong>ID:</strong> </td><td>". $idvisitante ."</td></tr>";
            $mensaje .= "<tr><td><strong>Nombre:</strong> </td><td>" .  $nombre  . "</td></tr>";
            $mensaje .= "<tr><td><strong>Empresa:</strong> </td><td>" . $empresa . "</td></tr>";
            $mensaje .= "<tr><td><strong>Detalle:</strong> </td><td>" . $this->detalle . "</td></tr>";
            $mensaje .= "<tr><td><strong>Link:</strong> </td><td>" . "http://10.149.20.26:8000//san_pedro_dcti_bitacora/formulario/tempform.php?ID=" . $this->id . "</td></tr>";
            $mensaje .= "</table>";
            $mensaje .= "</body></html>";
            //
            $headers = "MIME-Version: 1.0\r\n"; 
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "From: ".$from."\r\n"; 
            //
            //mail($to, $asunto, $mensaje,$headers);            
        }     
        catch(Exception $e) {
            $_SESSION['errmsg']= $e->getMessage() . " Notificar a Operaciones";
            header('Location: ../Error.php');
            exit;
        }
    }
    
    function AgregarTemporal($visitante){
        try {
            //agrega infomación del formulario temporal
            $sql='insert into FORMULARIO (FECHAINGRESO,FECHASALIDA,FECHASOLICITUD,IDSALA) VALUES (NOW(),DATE_ADD(NOW(), INTERVAL 1 DAY), NOW(), (SELECT sa.ID FROM SALA sa WHERE NOMBRE= :idsala)  )';
            $param= array(':idsala'=>$this->idsala);            
            $result = DATA::Ejecutar($sql,$param);
            //busca id de formulario agregado
            $sql='SELECT LAST_INSERT_ID() as ID';
            $data= DATA::Ejecutar($sql);
            $this->id =$data[0]['ID'];
            //agrega visitantes
            $sql='insert into VISITANTEPORFORMULARIO VALUES(:idvisitante,:idformulario)';
            $param= array(':idvisitante'=>$visitante,':idformulario'=>$this->id);
            $data=  DATA::Ejecutar($sql,$param);       
            $this->EnviareMail($visitante);
            // elimina sesion link para evitar redirect a paginas anteriores.
            unset($_SESSION['link']);  
            session_destroy();
            header('Location: ../index.php?id='.$visitante.'&msg=pendiente');
            exit;
        }     
        catch(Exception $e) {
            $_SESSION['errmsg']= $e->getMessage();
            header('Location: ../Error.php');
            exit;
        }
    }

}


?>