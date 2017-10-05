<?php 
if (!isset($_SESSION))
    session_start();

if(isset($_POST["action"])){
    $cal= new Cal();
    switch($_POST["action"]){
        case "Load":
            echo json_encode($cal->CargarTodos());
            break;
        case "SetActivity":
            if(isset($_POST['obj']))
            {                
                $arrObj= $_POST['obj'];
                $cal->SetActivity($arrObj);                 
            }                       
            break;
        case "AddOperDay":
            if(isset($_POST['operini']))
            {                
                // $cal->AddOperDay();                 
                $cal->operador= $_POST['operini']; 
                $cal->day= $_POST['operday']; 
                $cal->turn= $_POST['operturn']; 
                $cal->AddOperDay();
            }                     
            break;
    }
    
}

class Cal{
    public $id; 
    public $operador;
    public $actividad;
    //
    public $operini;
    public $day;
    public $turn;


	function __construct(){
        require_once("Conexion.php");
        require_once("Log.php");
    }
    
    //
    // Métodos.
    //
   
    function CargarTodos(){
        try {
            $sql="SELECT t.id, o.nombre, inicial, dia, mes, hora , ann , d.nombre as datacenter , a.actividad , o.id  as idoperador, d.id as iddatacenter, t.idhorario as idhorario, a.id as idactividad
                FROM cal t INNER JOIN operador o on o.id=t.idoperador
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
            log::AddD('ERROR', 'Ha ocurrido un error CAL Cargar todos', $e->getMessage());
            var_dump(http_response_code(500)); // error ajax
        }
    }

    function SetActivity($actividades){
        try{            
            foreach ($actividades as $item) {
                $this->id=  $item["id"];
                $this->actividad= $item['actividad'];
                //
                $sql="UPDATE cal t
                SET t.idactividad=  (select id from actividad where actividad=:actividad) 
                WHERE t.id= :id";
                $param= array(':id'=>$this->id,':actividad'=>$this->actividad);
                $data = DATA::Ejecutar($sql,$param,true);    
            }                   
            echo json_encode($this->CargarTodos());
            //if($data)                
            //else var_dump(http_response_code(500)); // error
        }catch(Exception $e) {        
            log::AddD('ERROR', 'Ha ocurrido un error CAL SetActivity', $e->getMessage());
            var_dump(http_response_code(500)); // error ajax
        }
    }

    function AddOperDay(){
        try{            
            echo json_encode($this->CargarTodos());           
        }catch(Exception $e) {        
            log::AddD('ERROR', 'Ha ocurrido un error CAL AddOperDay', $e->getMessage());
            var_dump(http_response_code(500)); // error ajax
        }
    }
    
}
?>