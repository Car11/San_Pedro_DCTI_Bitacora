<?php
if (!isset($_SESSION))
    session_start();
class Visitante{
    public $cedula;
    public $detalle;
    public $idsala;
    
    function __construct(){
        require_once("conexion.php");        
    }
    
    function BitacoraEntrada(){
        try {
            /*$sql='INSERT INTO bitacora (cedula,detalle, idsala) VALUES (:cedula, :detalle, 
                (SELECT sa.ID FROM SALA sa WHERE NOMBRE= :sala))';
            $param= array(':cedula'=>$this->cedula, ':detalle'=>$this->detalle, ':sala'=>$this->sala);
            $result = DATA::Ejecutar($sql,$param);
            //
            $this->EnviareMail("Ingreso");
            $nextpage = "../index.php";
            if ($_SESSION["NUEVOVISITANTE"]=="SI"){
                $nextpage= "index.php?id=REGISTRO";
                unset($_SESSION["NUEVOVISITANTE"]);
            }                
			$_SESSION["TYPE"] = "IN";
            header('Location: '. $nextpage);*/
            exit;
        }     
        catch(Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }

	/*function BitacoraSalida(){
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
    }*/
}
?>