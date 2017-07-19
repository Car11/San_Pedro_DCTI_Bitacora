<?php 
if (!isset($_SESSION))
    session_start();

if(isset($_POST["action"])){
    if($_POST["action"]=="Excluye"){
            $visitante= new Visitante();
        $visitante->ConsultaVisitante();
    }
}
    


class Visitante{
	public $cedula;//id
	public $nombre;
	public $empresa;
    public $permisoanual;
    public $visitante;
    public $visitanteexcluido;

	function __construct(){
        require_once("conexion.php");
    }
    function ValidaID2(){
        try{
            if($this::ValidaIdVisitante()){
                $this::ValidaEstadoFormulario();
                // muestra resultado del estado del formulario (session) en index.
                header('Location: ../index.php');
                exit;  
            } 
        }
        catch(Exception $e) {
            header('Location: ../Error.php?w=validarID');
            exit;
        }

    }

    function ValidaEstadoFormulario(){
        try{
            include_once('Formulario.php');
            $formulario= new Formulario();
            if($formulario->ConsultaVisitantePorFormulario($this->cedula))
            {
                // Valida fechas correctas.
                // flexibilidad de hora de entrada, 1h antes.
                $_SESSION['idformulario']= $formulario->id;
                $_SESSION['estado'] = $formulario->estado;
                $fechaanticipada  = new DateTime($formulario->fechaingreso);
                date_sub($fechaanticipada ,  date_interval_create_from_date_string('1 hour') );
                if(strtotime($fechaanticipada->format('Y-m-d H:i:s')) <=  time() && time() <= strtotime($formulario->fechasalida))
                {
                    // return true;           
                    // busca si es una salida.
                    $sql= "SELECT f.estado
                        FROM visitanteporformulario vf inner join formulario f on f.id=vf.idformulario
                        where vf.idvisitante=:idvisitante and f.id=:idformulario and salida is null and entrada is not null";
                    $param= array(':idvisitante'=>$this->cedula, ':idformulario'=>$formulario->id);
                    $data = DATA::Ejecutar($sql,$param);      
                    if (count($data)) { 
                        // Es salida.                                   
                        $_SESSION['estado']='fin';
                    }
                }
                else {
                    // tiempo expirado, estado = 3.          
                    $_SESSION['estado']='3';
                    //return false;
                }                
            }else {
                // return false;
                // NO tiene formulario.
                // Muestra pagina de ingreso de informacion de visita si es un visitante en la lista anual, sino, muestra denegado.
                $this::Cargar($this->cedula);
                if ($this->permisoanual=="1") {  
                    $_SESSION['link']="true";                    
                    header('Location: ../InfoVisita.php?id='. $this->cedula);
                    exit;
                }
                else {
                    // Visitante sin formulario y no en lista anual.
                    $_SESSION['estado']='4';
                    //return false;
                }
            }
        }
        catch(Exception $e) {
            header('Location: ../Error.php?w=validarFormulario');
            exit;
        }                  
    }

    function ValidaIdVisitante(){
        try{
            // Inicia la sesion para la cedula ingresada.
            $_SESSION["cedula"]=$this->cedula;
            $sql='SELECT * FROM visitante where cedula=:cedula';
            $param= array(':cedula'=>$this->cedula);
            $data = DATA::Ejecutar($sql,$param);
            if (count($data)) {
                 return true;
            }
            else {
                // return false;
                // No existe el visitante en base de datos, muestra nuevo perfil.
                $_SESSION['link']="true";
                header('Location: ../nuevoperfil.php?id='.$this->cedula);
                exit;
            }
        }
        catch(Exception $e) {
            header('Location: ../Error.php?w=validarIDVisitante');
            exit;
        }
    }

    function ValidaID(){
        try{
            $_SESSION["cedula"]=$this->cedula;
            $sql='SELECT * FROM visitante where cedula=:cedula';
            $param= array(':cedula'=>$this->cedula);
            $data = DATA::Ejecutar($sql,$param);
            if (count($data)) { //la ID existe en bd
                $this->nombre=$data[0]['NOMBRE']; // nombre del visitante.
                // Valida si el visitante está saliendo.
                $sql= "SELECT idformulario
                    FROM visitanteporformulario 
                    where idvisitante= :idvisitante and salida is null and entrada is not null";
                $param= array(':idvisitante'=>$this->cedula);
                $data = DATA::Ejecutar($sql,$param);                
                if (count($data)) {                                        
                    // existe un visitante ingresado en bitacora. se debe hacer la SALIDA.
                    $idformulario = $data[0]['idformulario'];
                    $_SESSION['estado']='fin';
                    $_SESSION['idformulario']=$idformulario;
                    header('Location: ../index.php');
                    exit;
                } 
                //si el visitante no ha ingresado, Valida formulario. ENTRADA.
                $sql="SELECT f.id as ID , f.fechaingreso , f.fechasalida, f.idsala , f.estado, f.motivovisita, f.detalleequipo, f.placavehiculo, f.idautorizador 
                    FROM formulario f inner join visitanteporformulario vf on f.id=vf.idformulario 
                    where vf.idvisitante= :idvisitante and entrada is null
                      order by f.id desc limit 1 ";
                $param= array(':idvisitante'=>$this->cedula);
                $data = DATA::Ejecutar($sql,$param);
                if (count($data)) {                    
                    $idformulario = $data[0]['ID'];
                    $estado = $data[0]['estado'];                    
                    $_SESSION['idformulario']=$idformulario;
                    //  valida si el estado del formulario.
                    $_SESSION['estado']=$estado;
                    if($estado=="1"){
                        // formulario aceptado = 1.
                        // valida fecha y hora de ingreso.                        
                        $fechaingreso= $data[0]['fechaingreso'];
                        $fechasalida= $data[0]['fechasalida'];
                        $idsala = $data[0]['idsala'];
                        // flexibilidad de hora de entrada, 1h antes.
                        $fechaanticipada  = new DateTime($fechaingreso);
                        date_sub($fechaanticipada ,  date_interval_create_from_date_string('1 hour') );
                        //  Tiempo expirado. = 3
                        if(strtotime($fechaanticipada->format('Y-m-d H:i:s')) >  time() || time() > strtotime($fechasalida))
                            $_SESSION['estado']='3';
                    }     
                    //             
                    header('Location: ../index.php');
                    exit;                            
                } else {
                    // el visitante existe pero no tiene formulario.
                    // Muestra pagina de ingreso de informacion de visita si es un visitante en la lista anual, sino, muestra denegado.
                    $this::Cargar($this->cedula);
                    if ($this->permisoanual=="1") {  
                        $_SESSION['link']="true";                    
                        header('Location: ../InfoVisita.php?id='. $this->cedula);
                        exit;
                    }
                    else {
                        // Visitante sin formulario y no en lista anual.
                        $_SESSION['estado']='4';
                        header('Location: ../index.php');
                        exit;   
                    }
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
            $data = DATA::Ejecutar($sql,$param,true);
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
            //
            $this->cedula= $data[0]['CEDULA'];
            $this->nombre= $data[0]['NOMBRE'];
            $this->empresa= $data[0]['EMPRESA'];
            $this->permisoanual= $data[0]['PERMISOANUAL'];
            //
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

    function ConsultaVisitante()
    {
        try {
            if (empty($_POST['visitanteexcluido'])) {
                $sql="SELECT CEDULA,NOMBRE,EMPRESA FROM visitante";
                $result = DATA::Ejecutar($sql);
            }
            else{
                
                $sql="SELECT CEDULA,NOMBRE,EMPRESA FROM visitante  WHERE NOT FIND_IN_SET(CEDULA,:EXCLUSION)";
                $param= array(':EXCLUSION'=>$_POST['visitanteexcluido']);
                $result = DATA::Ejecutar($sql,$param);  
            }

            echo json_encode($result);
        } catch (Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    } 
    

}
?>