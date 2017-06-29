<?php 
if (!isset($_SESSION))
    session_start();

class Formulario{
    public $id;
    public $fechaingreso;
	public $fechasalida;
	public $fechasolicitud;
    public $idsala;
    public $nombresala;
    public $placavehiculo;
    public $detalleequipo;
    public $motivovisita;
    public $visitante;
    public $idtramitante;
    public $nombretramitante;
    public $idautorizador;
    public $nombreautorizador;
    public $idresponsable;
    public $nombreresponsable;
    public $estado;
    	
	function __construct(){
        require_once("conexion.php");
        //error_reporting(E_ALL);
        // Always in development, disabled in production
        //ini_set('display_errors', 1);
    }
	    
    //Agrega formulario 
    function AgregarFormulario(){
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
    function ConsultaFormulario(){
        try {
			$sql = "SELECT id,fechasolicitud,estado,motivovisita,fechaingreso,fechasalida,idtramitante,
            idautorizador,idresponsable,idsala,placavehiculo,detalleequipo
            FROM formulario";
			$result = DATA::Ejecutar($sql);
			return $result;			
		}catch(Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }		 	
    } 
    
    function Cargar(){
        try {
			$sql = "SELECT id,fechasolicitud,estado,motivovisita,fechaingreso,fechasalida,(
                SELECT nombre from usuario u inner join formulario f on f.idtramitante=u.id
                where f.id=:identificador)as nombretramitante , (
                SELECT nombre from usuario u inner join formulario f on f.idautorizador=u.id
                where f.id=:identificador) as nombreautorizador, (
                SELECT nombre from responsable r inner join formulario f on f.idresponsable=r.id
                where f.id=:identificador) as nombreresponsable,(
                SELECT sa.nombre FROM sala sa inner join formulario fo on sa.id=fo.idsala 
                where fo.id=:identificador) as nombresala ,
            placavehiculo,detalleequipo
            FROM formulario WHERE id = :identificador";            
            $param= array(':identificador'=>$this->id);            
            $data = DATA::Ejecutar($sql,$param);
            //
            $this->fechasolicitud= $data[0]['fechasolicitud'];
            $this->estado= $data[0]['estado'];
            $this->motivovisita= $data[0]['motivovisita'];
            $this->fechaingreso= $data[0]['fechaingreso'];
            $this->fechasalida= $data[0]['fechasalida'];
            $this->nombretramitante= $data[0]['nombretramitante'];
            $this->nombreautorizador= $data[0]['nombreautorizador'];
            $this->nombreresponsable= $data[0]['nombreresponsable'];
            $this->nombresala= $data[0]['nombresala'];
            $this->placavehiculo= $data[0]['placavehiculo'];
            $this->detalleequipo= $data[0]['detalleequipo'];
			//
            return $data;		
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
            $mensaje .= "<tr><td><strong>Detalle:</strong> </td><td>" . $this->motivovisita . "</td></tr>";
            $mensaje .= "<tr><td><strong>Link:</strong> </td><td>" . "http://10.149.20.26:8000//san_pedro_dcti_bitacora/formularioingreso.php?ID=" . $this->id . "</td></tr>";
            $mensaje .= "</table>";
            $mensaje .= "</body></html>";
            //
            $headers = "MIME-Version: 1.0\r\n"; 
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "From: ".$from."\r\n"; 
            //
            mail($to, $asunto, $mensaje,$headers);      
                       
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
            $sql="insert into FORMULARIO (FECHAINGRESO,FECHASALIDA,FECHASOLICITUD,IDSALA, MOTIVOVISITA, IDTRAMITANTE) 
                VALUES (NOW(),DATE_ADD(NOW(), INTERVAL 1 DAY), NOW(), (SELECT sa.ID FROM SALA sa WHERE NOMBRE= :nombresala), :motivovisita, 
                (SELECT u.id FROM usuario u where u.usuario=:usuario)) ";
            $param= array(
                ':nombresala'=>$this->nombresala,
                ':motivovisita'=>$this->motivovisita,
                ':usuario'=>$_SESSION['username']
            );
            $data= DATA::Ejecutar($sql,$param,true);            
            if($data)
            {
                //busca id de formulario agregado
                $sql='SELECT LAST_INSERT_ID() as ID';
                $data= DATA::Ejecutar($sql);
                $this->id =$data[0]['ID'];
                //agrega visitantes
                $sql='insert into VISITANTEPORFORMULARIO(idvisitante,idformulario) VALUES(:idvisitante,:idformulario)';
                $param= array(':idvisitante'=>$visitante,':idformulario'=>$this->id);
                $data=  DATA::Ejecutar($sql,$param);       
                $this->EnviareMail($visitante);
                // elimina sesion link para evitar redirect a paginas anteriores.
                unset($_SESSION['link']);              
                header('Location: ../index.php?msg=pendiente');
                exit;
            }        
            else {
                $_SESSION['errmsg']= 'Formulario no registrado, comunicarse con operaciones TI';
            header('Location: ../Error.php');
            }    
        }     
        catch(Exception $e) {
            $_SESSION['errmsg']= $e->getMessage();
            header('Location: ../Error.php');
            exit;
        }
    }

}


?>