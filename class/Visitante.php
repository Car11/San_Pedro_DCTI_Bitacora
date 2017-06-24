<?php 
if (!isset($_SESSION))
    session_start();
class Visitante{
	public $cedula;//id
	public $nombre;
	public $empresa;
	public $formulario;

	function __construct(){
        require_once("conexion.php");
        
        //error_reporting(E_ALL);
        // Always in development, disabled in production
        //ini_set('display_errors', 1);
    }
    
    function ValidaID(){
        try{
            $sql='SELECT * FROM visitante where cedula=:cedula';
            $param= array(':cedula'=>$this->cedula);
            $data = DATA::Ejecutar($sql,$param);
            if (count($data)) { //la ID existe en bd
                $this->nombre=$data[0]['NOMBRE'];
                //Valida formulario de Ingreso
                $sql="SELECT f.id as ID , f.fechaingreso , f.fechasalida, f.idsala , f.estado, f.motivovisita, f.detalleequipo, f.placavehiculo, f.idautorizador ".
                    " FROM formulario f inner join visitanteporformulario vf on f.id=vf.idformulario ".
                    " where vf.idvisitante= :idvisitante";
                $param= array(':idvisitante'=>$this->cedula);
                $data = DATA::Ejecutar($sql,$param);
                if (count($data)) {
                    // valida si el estado del formulario, debe mostrar información del formulario.
                    include("Formulario.php");
                    $formulario= new formulario();
                    $formulario->estado= $data[0]['estado'];
                    if($formulario->estado=="0"){
                        // formulario pendiente = 0.
                        header('Location: ../index.php?msg=0');
                        exit;
                    }if($formulario->estado=="1"){
                        // formulario aceptado = 1.
                        // valida fecha y hora de ingreso.
                        $formulario->id= $data[0]['ID'];
                        $formulario->fechaingreso= $data[0]['fechaingreso'];
                        $formulario->fechasalida= $data[0]['fechasalida'];
                        $formulario->idsala= $data[0]['idsala'];                        
                        $formulario->motivovisita= $data[0]['motivovisita'];
                        $formulario->placavehiculo= $data[0]['placavehiculo'];
                        $formulario->detalleequipo= $data[0]['detalleequipo'];
                        $formulario->idautorizador= $data[0]['idautorizador'];
                        // flexibilidad de hora de entrada, 1h antes.
                        $fingreso = new DateTime($formulario->fechaingreso);
                        $fechaanticipada = date_sub($fingreso, 
                            date_interval_create_from_date_string('1 hour') );
                        if(strtotime($fechaanticipada->format('Y-m-d H:i:s')) <=  time() && time()<= strtotime($formulario->fechasalida)){
                            // formulario correcto!
                            // busca #carnet y asocia con el id del visitante.
                            // ...
                            header('Location: ../index.php?msg=1');
                            exit;
                        } else // tiempo expirado
                        {
                            // Razón de porqué fue denegado: tiempo expirado.
                            header('Location: ../index.php?msg=2');
                            exit;
                        }                        
                    }else{
                        // formulario denegado = 2
                        // Razón de porqué fue denegado.
                        header('Location: ../index.php?msg=2');
                        exit;
                    }                   
                    
                } else {
                    // el visitante existe pero no tiene formulario.
                    //Muestra pagina de ingreso se informacion de visita
                    $_SESSION['link']="true";                    
                    header('Location: ../InfoVisita.php?id='. $this->cedula);
                    exit;
                }
            }
            else { //la ID no existe en bd, muestra nuevo perfil
                $_SESSION['link']="true";
                header('Location: ../nuevoperfil.php?id='.$this->cedula);
                exit;
            }
        }
        catch(Exception $e) {
            header('Location: ../Error.php?w=conectar&id');
            exit;
        }
    }
    
    function Agregar(){
        try {
            $sql='INSERT INTO visitante (nombre, cedula, empresa) VALUES (:nombre, :cedula, :empresa)';
            $param= array(':nombre'=>$this->nombre,':cedula'=>$this->cedula,':empresa'=>$this->empresa);
            $data = DATA::Ejecutar($sql,$param);
            if($data)
                return true;
            else return false;
        }     
        catch(Exception $e) {
            header('Location: ../Error.php?w=conectar&id='.$e->getMessage());
            exit;
        }
    }
    
    function Cargar($ID){
        try {
            $sql='SELECT * FROM visitante where cedula=:cedula';
            $param= array(':cedula'=>$ID);
            $data= DATA::Ejecutar($sql,$param);
            return $data;
        }
        catch(Exception $e) {
            header('Location: ../Error.php?w=conectar&id='.$e->getMessage());
            exit;
        }
    }
    
    function CargarTodos(){
        try {
            $sql='SELECT cedula, nombre, empresa FROM visitante ORDER BY cedula';
            $data= DATA::Ejecutar($sql);
            return $data;
        }
        catch(Exception $e) {            
            header('Location: ../Error.php?w=conectar&id='.$e->getMessage());
            exit;
        }
    }
    
    function ConsultaBitacora(){
        try {
           $sql = "SELECT * FROM bitacora";
           $result = DATA::Ejecutar($sql);
           return $result;                                 
        }catch(Exception $e) {
            header('Location: ../Error.php?w=conectar&id='.$e->getMessage());
            exit;
        }                                     
    }
    
    function FormularioIngresoConsultaVisitante(){
        try {
           $sql = "SELECT cedula, nombre, empresa FROM visitante";
           $result = DATA::Ejecutar($sql);
           return $result;                                 
        }catch(Exception $e) {
            header('Location: ../Error.php?w=conectar&id='.$e->getMessage());
            exit;
        }                                     
    }

    
    

}
?>