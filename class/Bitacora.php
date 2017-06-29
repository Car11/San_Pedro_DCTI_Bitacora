<?php
if (!isset($_SESSION))
    session_start();
$bitacora= new Bitacora();
if (isset($_POST['cedula'])) { 
    $bitacora->idvisitante=$_POST['cedula'];    
    //    
    if (isset($_POST['motivovisita'])) { 
        $bitacora->motivovisita=$_POST['motivovisita'];        
    }
    if (isset($_POST['idformulario'])) { 
        $bitacora->idformulario=$_POST['idformulario'];        
    }
    if (isset($_POST['idtarjeta'])) { 
        $bitacora->idtarjeta=$_POST['idtarjeta'];        
    }
    if (isset($_POST['accion'])) {
        switch($_POST['accion']){
            case 'salida':
                $bitacora->Salida();
                break;
            case 'entrada':
                $bitacora->Entrada();
                break;
        }
    /*if($resultado)
    {
        header('Location: ../index.php');
        exit;
    }
    else {
        header('Location: ../Error.php');
        exit;
    }*/
    }  
}

    
/**********************************************  CLASS  *****************************************************************/
class Bitacora{
    public $idvisitante;
    public $motivovisita;
    public $idsala;
    public $idformulario;
    public $idtarjeta;
    public $entrada;
    public $salida;
    
    function __construct(){
        require_once("conexion.php");  
    }
    
    function Entrada(){
        try {
            $sql="UPDATE visitanteporformulario
                set entrada= now(), idtarjeta= :idtarjeta
                where idformulario= :idformulario and idvisitante= :idvisitante";
            $param= array(':idvisitante'=>$this->idvisitante, ':idformulario'=>$this->idformulario, ':idtarjeta'=>$this->idtarjeta);
            $data = DATA::Ejecutar($sql,$param, true);
            if($data){
                // Cambia el estado de la tarjeta asignada = 1.
                $sql='update tarjeta set estado=1 where id=:idtarjeta';
                $param= array(':idtarjeta'=>$this->idtarjeta);
                $data = DATA::Ejecutar($sql,$param,true);
                if($data)
                {
                    $this->EnviareMail("Ingreso");                    
                    echo "Bienvenid@ "; 
                }
                else echo "Ha ocurrido un problema, comunicarse con Operaciones TI";
            }
            else echo "Ha ocurrido un problema, comunicarse con Operaciones TI";
        }     
        catch(Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }

	function Salida(){
        try {
           	date_default_timezone_set('America/Costa_Rica');
	        $sql="UPDATE visitanteporformulario 
                SET salida= :salida
                WHERE idvisitante= :idvisitante and idformulario= :idformulario";
	        $param= array(':salida'=>date('Y-m-d H:i:s',time()), ':idvisitante'=>$this->idvisitante, 'idformulario'=>$this->idformulario);
	        $data = DATA::Ejecutar($sql,$param,true);
	        if($data){
                // Cambia el estado de la tarjeta = 0.                
                $sql='update tarjeta set estado=0 where id=:idtarjeta';
                $param= array(':idtarjeta'=>$this->idtarjeta);
                $data = DATA::Ejecutar($sql,$param,true);     
                if($data){
                    //Notificación
                    $this->EnviareMail("Salida");       
                    echo "Salida Completa";
                }
                else echo "Ha ocurrido un problema, comunicarse con Operaciones TI";
            }
            else echo "Ha ocurrido un problema, comunicarse con Operaciones TI";
        }     
        catch(Exception $e) {
            header('Location: Error.html?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }
    
    /* function Consulta(){
        try {
			require_once("conexion.php");
			$sql = "SELECT * FROM bitacora";
			$result = DATA::Ejecutar($sql);
			return $result;			
		}catch(Exception $e) {
            header('Location: Error.html?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }		 	
	 }*/
    
    function EnviareMail($tipoingreso){
        // smtpapl.correo.ice
        // puerto 25
        // ip 10.149.20.26
        // ICETEL\OperTI
        // Clave: Icetel2017
        // Buzón: OperacionesTI@ice.go.cr
        //consulta datos del visitante
        //$sql='SELECT * FROM visitante where cedula=:idvisitante';
        //$param= array(':idvisitante'=>$this->idvisitante);
        //$result = DATA::Ejecutar($sql,$param);
        //
        require_once("visitante.php");  
        $visitante = new Visitante();
        $visitante->cedula= $this->idvisitante;
        $visitante->Cargar($this->idvisitante);
        //
        ini_set('SMTP','smtpapl.correo.ice');
        $to = "ZZT OFICINA PROCESAMIENTO <ofproc1@ice.go.cr>";
        //$to= "cchaconc@ice.go.cr";   
        $from = "operTI@ice.go.cr";
		$asunto = "Informe de Bitácora DCTI";
        $mensaje = "<h2><i>Notificación de $tipoingreso<i><h2>";
		$mensaje .= '<html><body>';
        $mensaje .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
        $mensaje .= "<tr style='background: #eee;'><td><strong>ID:</strong> </td><td>". $visitante->cedula ."</td></tr>";
        $mensaje .= "<tr><td><strong>Nombre:</strong> </td><td>" .  $visitante->nombre  . "</td></tr>";
        $mensaje .= "<tr><td><strong>Empresa:</strong> </td><td>" . $visitante->empresa . "</td></tr>";
        $mensaje .= "<tr><td><strong>Motivo:</strong> </td><td>" . $this->motivovisita . "</td></tr>"; // motivo proviene del formulario.
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