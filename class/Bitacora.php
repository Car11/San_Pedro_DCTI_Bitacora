<?php
if (!isset($_SESSION))
    session_start();
$bitacora= new Bitacora();
if (isset($_POST['idvisitante'])) { 
    $bitacora->idvisitante=$_POST['idvisitante'];    
    //
    if(isset($_SESSION['bitacora']))
        $bitacora->id=$_SESSION['bitacora'];
    if(isset($_POST['cedula']))
        $bitacora->cedula=$_POST['cedula'];
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
    }  
}

    
/**********************************************  CLASS  **************************************************/
class Bitacora{
    public $id;
    public $idvisitante;
    public $cedula;
    public $idformulario;
    public $idtarjeta;
    public $entrada;
    public $salida;
    
    function __construct(){
        require_once("Conexion.php");  
        require_once("Email.php");  
        require_once("Log.php");  
    }
    
    function Entrada(){
        try {            
            $sql = "INSERT INTO bitacora (idvisitante, idformulario, entrada, idtarjeta)
                    VALUES (:idvisitante, :idformulario, now(), :idtarjeta)";
            $param= array(':idvisitante'=>$this->idvisitante, 
                ':idformulario'=>$this->idformulario,
                ':idtarjeta'=>$this->idtarjeta);
            $data = DATA::Ejecutar($sql,$param, true);
            if($data){
                // Cambia el estado de la tarjeta asignada = 1.
                $sql='update tarjeta set estado=1 where id=:idtarjeta';
                $param= array(':idtarjeta'=>$this->idtarjeta);
                $data = DATA::Ejecutar($sql,$param,true);
                if($data)
                {    
                    email::Enviar($this->cedula, $this->idformulario , "Control de Acceso CDC", "NOTIFICACION DE INGRESO", $this->idtarjeta);                  
                    echo "Bienvenid@ !!!"; 
                }
                else {
                    log::Add('ERROR', 'Ha ocurrido un error al realizar la Asignacion de Tarjeta del Visitante. IDvisitante: ' . $this->idvisitante  );
                    var_dump(http_response_code(500)); // error ajax
                }
            }
            else {
                log::Add('ERROR', 'Ha ocurrido un error al realizar la Entrada del Visitante. IDvisitante: ' . $this->idvisitante  );
                var_dump(http_response_code(500)); // error ajax
            }
            // elimina variables de sesion.
            if(isset($_SESSION['estado']))
                unset($_SESSION['estado']);
            if(isset($_SESSION['idformulario']))
                unset($_SESSION['idformulario']);
            if(isset($_SESSION['idvisitante']))
                unset($_SESSION['idvisitante']);
            if(isset($_SESSION['link']))
                unset($_SESSION['link']);
            if(isset($_SESSION['bitacora']))
                unset($_SESSION['bitacora']);
        }     
        catch(Exception $e) {
            log::AddD('FATAL', 'Ha ocurrido un error al realizar la Entrada del Visitante', $e->getMessage());
            var_dump(http_response_code(500)); // error ajax            
        }
    }

	function Salida(){
        try {            
           	date_default_timezone_set('America/Costa_Rica');
	        $sql="UPDATE bitacora 
                SET salida= :salida
                WHERE id = :id";
	        $param= array(':salida'=>date('Y-m-d H:i:s',time()), ':id'=>$this->id);
	        $data = DATA::Ejecutar($sql,$param,true);
	        if($data){
                // Cambia el estado de la tarjeta = 0.                
                $sql='update tarjeta set estado=0 where id=:idtarjeta';
                $param= array(':idtarjeta'=>$this->idtarjeta);
                $data = DATA::Ejecutar($sql,$param,true);     
                if($data){    
                    email::Enviar($this->cedula, $this->idformulario , "Control de Acceso CDC", "NOTIFICACION DE SALIDA", $this->idtarjeta);           
                    echo "Salida Completa";
                }
                else {
                    log::Add('ERROR', 'Ha ocurrido un error al liberar la tarjeta del Visitante. IDvisitante: ' . $this->idvisitante  );
                    var_dump(http_response_code(500)); // error ajax
                }
            }
            else {
                log::Add('ERROR', 'Ha ocurrido un error al realizar la Salida del Visitante. IDvisitante: ' . $this->idvisitante  );
                var_dump(http_response_code(500)); // error ajax
            }
            // elimina variables de sesion.
            if(isset($_SESSION['estado']))
                unset($_SESSION['estado']);
            if(isset($_SESSION['idformulario']))
                unset($_SESSION['idformulario']);
            if(isset($_SESSION['idvisitante']))
                unset($_SESSION['idvisitante']);
            if(isset($_SESSION['link']))
                unset($_SESSION['link']);
            if(isset($_SESSION['bitacora']))
                unset($_SESSION['bitacora']);
        }     
        catch(Exception $e) {
            log::AddD('FATAL', 'Ha ocurrido un error al realizar la Entrada del Visitante', $e->getMessage());
            var_dump(http_response_code(500)); // error ajax
        }
    }
    
    function Consulta(){
        try {
			$sql = "SELECT DISTINCT
                id,
                (SELECT consecutivo FROM formulario WHERE id=idformulario),
                (SELECT cedula from visitante WHERE id = idvisitante),
                (SELECT nombre from visitante WHERE id = idvisitante),
                entrada, 
                salida,
                (SELECT (SELECT nombre FROM sala WHERE id=idsala) FROM formulario WHERE id=idformulario),
                (SELECT consecutivo FROM tarjeta WHERE id=idtarjeta),
                (SELECT estado FROM tarjeta WHERE id=idtarjeta) 
                
                FROM bitacora";
			$data = DATA::Ejecutar($sql);
            if($data)
			    return $data;	
            else {
                log::Add('ERROR', 'Ha ocurrido un error al Consultar la bitacora.');
                // muestra mensaje (ajax o html) del error.
                $_SESSION['errmsg']= 'Problemas de Consulta';
                header('Location: ../Error.php');
                exit;
            }		
		}catch(Exception $e) {
            require_once("Log.php");  
            log::AddD('FATAL', 'Ha ocurrido al Consultar la bitacora.', $e->getMessage());
            $_SESSION['errmsg']= 'Problemas de Consulta';
            header('Location: ../Error.php');
            exit;
        }
    }


}
?>