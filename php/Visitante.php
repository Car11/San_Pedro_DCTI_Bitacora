<?php 
session_start();
class Visitante{
	public $cedula;
	public $nombre;
	public $empresa;
    public $responsable;
	
	function __construct(){
        require_once("conexion.php");
        error_reporting(E_ALL);
        // Always in development, disabled in production
        ini_set('display_errors', 0);
    }
	
	function Existe(){
        try {
            $sql='SELECT * FROM visitante where cedula=:cedula';
            $param= array(':cedula'=>$this->cedula);
            $result = DATA::Ejecutar($sql,$param);
            $_SESSION["NOMBREVISITANTE"]= $result[0]['NOMBRE'];
            //
            if (count($result)) { 
                //si el visitante existe, 1. ingresa 2. sale.
                //Si la cedula tiene una entrada sin salida (NULL), registra la salida.
                $sql='SELECT * FROM bitacora where cedula=:cedula and salida IS NULL';
                $param= array(':cedula'=>$this->cedula);
                $result = DATA::Ejecutar($sql,$param);
				//
			    if ( count($result) ) { 
                    //es una salida, trae campo detalle y luego guarda                    
                    if (!isset($_SESSION['id'.$this->cedula])) {
							$_SESSION['id'.$this->cedula] = "IN";
					}	
                    if($_SESSION['id'.$this->cedula]=="IN"){ 
                        $_SESSION["TYPE"]="OUT";
						$_SESSION['id'.$this->cedula] = "OUT";
						if($this->detalle=="" && $result[0]['DETALLE']=="")
                            $_SESSION["DETALLE"]="";
                        else if($this->detalle=="")
							$_SESSION["DETALLE"]= $result[0]['DETALLE'];
						else if ($result[0]['DETALLE']=="")
                            $_SESSION["DETALLE"]= $this->detalle;
                        else $_SESSION["DETALLE"]= $result[0]['DETALLE'] . ". " . $this->detalle ;
                    	header('Location: index.php?id='.$this->cedula);
                    	exit;
                    }else{
                    	unset($_SESSION['id'.$this->cedula]);	
                        $this->BitacoraSalida();
                    }                        
                }
                else //la cedula no esta ingresada en bitacora, agrega entrada
                {
					$this->BitacoraEntrada();
                }                        
            } else {
                // si la cedula no está regisrada, debe mostrar formulario de ingreso
                $_SESSION["DETALLE"]= $this->detalle;
                header('Location: perfil.php?id='.$this->cedula);
                exit;
            }
        }     
        catch(Exception $e) {
            header('Location: Error.html?id='.$e->getMessage());
            exit;
        }
    }
    
    function Agregar(){
        try {
            $sql='INSERT INTO visitante (nombre, cedula, empresa)VALUES (:nombre, :cedula, :empresa)';
            $param= array(':nombre'=>$this->nombre,':cedula'=>$this->cedula,':empresa'=>$this->empresa);
            $result = DATA::Ejecutar($sql,$param);
            //Agrega la entrada
            $this->BitacoraEntrada();
        }     
        catch(Exception $e) {
            header('Location: Error.html?w=visitante-agregar&id='.$e->getMessage());
            exit;
        }
    }
    
    function BitacoraEntrada(){
        try {
            $sql='INSERT INTO bitacora (cedula,detalle) VALUES (:cedula, :detalle)';
            $param= array(':cedula'=>$this->cedula, ':detalle'=>$this->detalle);
            $result = DATA::Ejecutar($sql,$param);
            //
            $this->EnviareMail("Ingreso");
            $nextpage = "index.php";
            if ($_SESSION["NUEVOVISITANTE"]=="SI"){
                $nextpage= "index.php?id=REGISTRO";
                unset($_SESSION["NUEVOVISITANTE"]);
            }                
			$_SESSION["TYPE"] = "IN";
            header('Location: '. $nextpage);
            exit;
        }     
        catch(Exception $e) {
            header('Location: Error.html?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }

	 function BitacoraSalida(){
        try {
           	date_default_timezone_set('America/Costa_Rica');
	        $sql='UPDATE bitacora SET salida= :salida , detalle=:detalle WHERE cedula= :cedula and salida is NULL';
	        $param= array(':salida'=>date('Y-m-d H:i:s',time()) , ':detalle'=>$this->detalle,  ':cedula'=>$this->cedula);
	        $result = DATA::Ejecutar($sql,$param);
	        //
            $this->EnviareMail("Salida");
	        $_SESSION["TYPE"] = "NULL";
	        header('Location: index.php?id=END');
	        exit;
        }     
        catch(Exception $e) {
            header('Location: Error.html?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }
    
     function ConsultaBitacora(){
        try {
			require_once("conexion.php");
			$sql = "SELECT * FROM bitacora";
			$result = DATA::Ejecutar($sql);
			return $result;			
		}catch(Exception $e) {
            header('Location: Error.html?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }	
	 	
	 }
    
    function EnviareMail($tipoingreso){
        // smtpapl.correo.ice
        // puerto 25
        // ip 10.149.20.26
        // ICETEL\OperTI
        // Clave: Icetel2017
        // Buzón: OperacionesTI@ice.go.cr
        //consulta datos del visitante
        $sql='SELECT * FROM visitante where cedula=:cedula';
        $param= array(':cedula'=>$this->cedula);
        $result = DATA::Ejecutar($sql,$param);
        //
        if (count($result)){ 
            $this->nombre= $result[0]['NOMBRE'];
            $this->empresa= $result[0]['EMPRESA'];
            //$this->responsable= $result[0]['RESPONSABLE'];
        }
        //
        ini_set('SMTP','smtpapl.correo.ice');
        $to = "ZZT OFICINA PROCESAMIENTO <ofproc1@ice.go.cr>";
        //$to= "cchaconc@ice.go.cr";   
        $from = "operTI@ice.go.cr";
		$asunto = "Informe de Bitácora DCTI";
        $mensaje = "<h2><i>Notificación de $tipoingreso<i><h2>";
		$mensaje .= '<html><body>';
        $mensaje .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
        $mensaje .= "<tr style='background: #eee;'><td><strong>ID:</strong> </td><td>". $this->cedula ."</td></tr>";
        $mensaje .= "<tr><td><strong>Nombre:</strong> </td><td>" .  $this->nombre  . "</td></tr>";
        $mensaje .= "<tr><td><strong>Empresa:</strong> </td><td>" . $this->empresa . "</td></tr>";
        $mensaje .= "<tr><td><strong>Detalle:</strong> </td><td>" . $this->detalle . "</td></tr>";
        $mensaje .= "<tr><td><strong>Responsable DCTI:</strong> </td><td>" . "" . "</td></tr>";
        $mensaje .= "</table>";
        $mensaje .= "</body></html>";
        //
		$headers = "MIME-Version: 1.0\r\n"; 
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		$headers .= "From: ".$from."\r\n"; 
		//
		mail($to, $asunto, $mensaje,$headers);
    }
}


?>