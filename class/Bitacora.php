<?php
if (!isset($_SESSION))
    session_start();
$bitacora= new Bitacora();
if (isset($_POST['cedula'])) { 
    $bitacora->idvisitante=$_POST['cedula'];    
    //
    if(isset($_SESSION['bitacora']))
        $bitacora->id=$_SESSION['bitacora'];
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
    public $idformulario;
    public $idtarjeta;
    public $entrada;
    public $salida;
    
    function __construct(){
        require_once("conexion.php");  
        require_once("email.php");  
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
                    email::Enviar($this->idvisitante, $this->idformulario , "Control de Acceso CDC", "NOTIFICACION DE INGRESO", $this->idtarjeta);           
                    echo "Bienvenid@ "; 
                }
                else echo "Ha ocurrido un problema, comunicarse con Operaciones TI";
            }
            else echo "Ha ocurrido un problema, comunicarse con Operaciones TI";
            // elimina variables de sesion.
            if(isset($_SESSION['estado']))
                unset($_SESSION['estado']);
            if(isset($_SESSION['idformulario']))
                unset($_SESSION['idformulario']);
            if(isset($_SESSION['cedula']))
                unset($_SESSION['cedula']);
            if(isset($_SESSION['link']))
                unset($_SESSION['link']);
            if(isset($_SESSION['bitacora']))
                unset($_SESSION['bitacora']);
        }     
        catch(Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
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
                    email::Enviar($this->idvisitante, $this->idformulario , "Control de Acceso CDC", "NOTIFICACION DE SALIDA", $this->idtarjeta);           
                    echo "Salida Completa";
                }
                else echo "Ha ocurrido un problema, comunicarse con Operaciones TI";
            }
            else echo "Ha ocurrido un problema, comunicarse con Operaciones TI";
            // elimina variables de sesion.
            if(isset($_SESSION['estado']))
                unset($_SESSION['estado']);
            if(isset($_SESSION['idformulario']))
                unset($_SESSION['idformulario']);
            if(isset($_SESSION['cedula']))
                unset($_SESSION['cedula']);
            if(isset($_SESSION['link']))
                unset($_SESSION['link']);
            if(isset($_SESSION['bitacora']))
                unset($_SESSION['bitacora']);
        }     
        catch(Exception $e) {
            header('Location: Error.html?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }
    
    function Consulta(){
        try {
			$sql = "SELECT id,idformulario,(SELECT cedula from visitante WHERE id = idvisitante),(SELECT nombre from visitante WHERE id = idvisitante),entrada, salida,(SELECT (SELECT nombre FROM sala WHERE id=idsala) FROM formulario WHERE id=idformulario),idtarjeta,(SELECT estado FROM tarjeta WHERE id=idtarjeta) FROM bitacora";
			$result = DATA::Ejecutar($sql);
			return $result;			
		}catch(Exception $e) {
            header('Location: Error.html?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }


}
?>