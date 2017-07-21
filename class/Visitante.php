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

    function ValidaID(){
        try{
            if(strlen($this->cedula)<=2)
            {
                $this::ValidaIDTarjeta();
            }
            else { // es una cedula.
                if($this::ValidaIdVisitante()){
                    $this::ValidaEstadoFormulario();
                    // muestra resultado del estado del formulario (session) en index.
                    header('Location: ../index.php');
                    exit;  
                } 
            }            
        }
        catch(Exception $e) {
            unset($_SESSION['estado']);
            header('Location: ../Error.php?w=validarID');
            exit;
        }

    }

    function ValidaIDTarjeta(){
        try{
            // es una tarjeta. Aplica solo para salidas.
            // Busca la tarjeta en estado 1 y su visitante asignado (visitanteporformulario)
            // Si no hay, muestra mensaje que la tarjeta no está en uso.
            $sql= "SELECT b.id as idbitacora  , idformulario, idvisitante, entrada, salida, idtarjeta
                FROM TARJETA t inner join bitacora b on b.idtarjeta= t.id
                WHERE t.ID=:idtarjeta AND ESTADO=1 
                order by b.id desc limit 1";
            $param= array(':idtarjeta'=>$this->cedula);   // cedula en este caso es el idtarjeta que viaja por POST
            $data = DATA::Ejecutar($sql,$param);      
            if (count($data)) {      
                // Valida que es una salida.
                $entrada= $data[0]['entrada'];
                $salida= $data[0]['salida'];      
                if($entrada!=NULL and $salida==NULL)
                {
                    // La tarjeta está en uso.
                    $_SESSION['estado']='fin';
                    $_SESSION['bitacora']=$data[0]['idbitacora'];
                    $_SESSION['idformulario']=$data[0]['idformulario'];
                    $_SESSION["cedula"]=$data[0]['idvisitante'];
                }   
                else {
                    // la tarjeta no esta en uso.
                    $_SESSION['estado']= "TARJETANULL";
                    //print "la tarjeta no esta en uso.";exit;                        
                }      
            }
            else {
                // la tarjeta no esta en uso.
                $_SESSION['estado']= "TARJETANULL";
                //print "la tarjeta no esta en uso.";exit;                
            }
            header('Location: ../index.php');
            exit;      
        }
        catch(Exception $e) {
            header('Location: ../Error.php?w=validarIDTarjeta');
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
                //
                $fechaanticipada  = new DateTime($formulario->fechaingreso);
                date_sub($fechaanticipada ,  date_interval_create_from_date_string('1 hour') );
                // busca si es una salida o entrada.
                $sql = "SELECT id, entrada, salida, idtarjeta
                    FROM bitacora 
                    where idvisitante=:idvisitante and idformulario=:idformulario
                    order by id desc limit 1 ";
                $param= array(':idvisitante'=>$this->cedula, ':idformulario'=>$formulario->id);
                $data = DATA::Ejecutar($sql,$param);      
                if (count($data)) {                                 
                    $entrada= $data[0]['entrada'];
                    $salida= $data[0]['salida'];
                    //
                    if($entrada!=NULL and $salida==NULL)
                    {
                        // es salida.
                        $_SESSION['estado']='fin';
                        $_SESSION['bitacora']=$data[0]['id']; // id de Bitacora 
                    }
                }
                else 
                {
                    // nueva entrada.
                    if(strtotime($fechaanticipada->format('Y-m-d H:i:s')) <=  time() && time() <= strtotime($formulario->fechasalida))
                        $_SESSION['bitacora'] = "NUEVO"; // Nuevo id de Bitacora 
                    else {
                        // la entrada no es en la fecha/hora correcta.
                        if(!$this::ValidarPermisoAnual())                        
                            $_SESSION['estado']='3';
                        return false;
                    }
                }                    
            }else {
                // return false;
                // NO tiene formulario.
                // Muestra pagina de ingreso de informacion de visita si es un visitante en la lista anual, sino, muestra denegado.                
                 if(!$this::ValidarPermisoAnual()) {
                    // Visitante sin formulario y no en lista anual.
                    $_SESSION['estado']='4';
                }
            }
        }
        catch(Exception $e) {
            unset($_SESSION['estado']);
            header('Location: ../Error.php?w=validarFormulario');
            exit;
        }                  
    }

    function ValidarPermisoAnual(){
        try{
            $this::Cargar($this->cedula);
            if ($this->permisoanual=="1") {  
                unset($_SESSION['estado']);
                $_SESSION['link']="true";                    
                header('Location: ../InfoVisita.php?id='. $this->cedula);
                exit;
            } else return false;
        }
        catch(Exception $e) {
            unset($_SESSION['estado']);
            header('Location: ../Error.php?w=ValidarIdPermisoAnual');
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
                unset($_SESSION['cedula']);
                unset($_SESSION['estado']);
                $_SESSION['link']="true";
                header('Location: ../nuevoperfil.php?id='.$this->cedula);
                exit;
            }
        }
        catch(Exception $e) {
            unset($_SESSION['estado']);
            header('Location: ../Error.php?w=validarIDVisitante');
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
            $sql="SELECT CEDULA, NOMBRE, EMPRESA, PERMISOANUAL 
                FROM visitante 
                where cedula=:cedula";
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
            $sql='SELECT cedula, nombre, empresa, permisoanual FROM visitante ORDER BY cedula';
            $data= DATA::Ejecutar($sql);
            return $data;
        }
        catch(Exception $e) {            
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