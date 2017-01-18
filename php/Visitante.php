<?php 
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
	
	function Existe(){
        try {
            $sql='SELECT * FROM visitante where cedula=:cedula';
            $param= array(':cedula'=>$this->cedula);
            $result = DATA::Ejecutar($sql,$param);
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
							//print 'siempre in';
							//$_SESSION[$this->cedula] = "OUT";
							//exit;
					}	
                    if($_SESSION['id'.$this->cedula]=="IN"){ 
                        $_SESSION["TYPE"]="OUT";
						$_SESSION['id'.$this->cedula] = "OUT";
						if($this->detalle!="")
							$_SESSION["DETALLE"]= $result[0]['DETALLE'] . "\n\n" . $this->detalle ;
						else $_SESSION["DETALLE"]= $result[0]['DETALLE'];
                    	header('Location: index.php?id='.$this->cedula);
                    	exit;
                    }else{
                    	unset($_SESSION['id'.$this->cedula]);	
						//session_destroy();
                        $this->BitacoraSalida();
                    }                        
                }
                else //la cedula no esta ingresada en bitacora, agrega entrada
                {
					$this->BitacoraEntrada();
                }                        
            } else {
                // si la cedula no está regisrada, debe mostrar formulario de ingreso
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
        //require_once("conexion.php");
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
        //require_once("conexion.php");
        try {
            $sql='INSERT INTO bitacora (cedula,detalle) VALUES (:cedula, :detalle)';
            $param= array(':cedula'=>$this->cedula, ':detalle'=>$this->detalle);
            $result = DATA::Ejecutar($sql,$param);
			//
			$_SESSION["TYPE"] = "IN";
            header('Location: index.php');
            exit;
        }     
        catch(Exception $e) {
            header('Location: Error.html?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }

	 function BitacoraSalida(){
        //require_once("conexion.php");
        try {
           	date_default_timezone_set('America/Costa_Rica');
	        $sql='UPDATE bitacora SET salida= :salida , detalle=:detalle WHERE cedula= :cedula and salida is NULL';
	        $param= array(':salida'=>date('Y-m-d H:i:s',time()) , ':detalle'=>$this->detalle,  ':cedula'=>$this->cedula);
	        $result = DATA::Ejecutar($sql,$param);
	        //
	        $_SESSION["TYPE"] = "NULL";
	        header('Location: index.php');
	        exit;
        }     
        catch(Exception $e) {
            header('Location: Error.html?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }
}


?>