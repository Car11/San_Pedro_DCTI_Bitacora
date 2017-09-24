<?php 
if (!isset($_SESSION))
    session_start();

if(isset($_POST["action"])){
    $cal= new Cal();
    switch($_POST["action"]){
        case "Cargar":
        echo json_encode($cal->CargarTodos());
        break;
    }
    
}

class Cal{
    public $id;
    public $operador;
    

	function __construct(){
        require_once("Conexion.php");
        require_once("Log.php");
    }
    
    //
    // Métodos.
    //
   
    function CargarTodos(){
        try {
            $sql="SELECT o.nombre, inicial, dia, mes, hora , ann , d.nombre as datacenter , a.actividad , t.idhorario
                FROM turno t INNER JOIN operador o on o.id=t.idoperador
                    inner join horario h on h.id=t.idhorario
                    inner join datacenter d on d.id=t.iddatacenter
                    inner join actividad a on a.id=t.idactividad
                where dia= day(DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY))
                GROUP BY HORA, nombre";
            $data= DATA::Ejecutar($sql);
            if($data)
                return $data;
        }
        catch(Exception $e) {        
            log::AddD('ERROR', 'Ha ocurrido un error al INFO WEB NOC - en sitio', $e->getMessage());
            var_dump(http_response_code(500)); // error ajax
        }
    }
    
}
?>