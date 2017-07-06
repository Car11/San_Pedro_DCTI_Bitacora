<?php 
if (!isset($_SESSION))
    session_start();

class Visitante{
	public $cedula;//id
	public $nombre;
	public $empresa;
    public $permisoanual;

	function __construct(){
        require_once("conexion.php");
    }
    function ValidaID(){
        try{
            if($this::ValidaIdVisitante()){
                $this::ValidaEstadoFormulario();
                // muestra resultado del estado del formulario (session) en index.
                header('Location: ../index.php');
                exit;  
            } 
        }
        catch(Exception $e) {
            unset($_SESSION['estado']);
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
                //
                $fechaanticipada  = new DateTime($formulario->fechaingreso);
                date_sub($fechaanticipada ,  date_interval_create_from_date_string('1 hour') );
                if(strtotime($fechaanticipada->format('Y-m-d H:i:s')) <=  time() && time() <= strtotime($formulario->fechasalida))
                {
                    // return true;           
                    // busca si es una salida o entrada.
                    $sql= "SELECT vf.id, vf.entrada , vf.salida 
                        FROM visitanteporformulario vf inner join formulario f on f.id=vf.idformulario
                        where vf.idvisitante=:idvisitante and f.id=:idformulario
                        order by vf.id desc limit 1 ";
                    $param= array(':idvisitante'=>$this->cedula, ':idformulario'=>$formulario->id);
                    $data = DATA::Ejecutar($sql,$param);      
                    if (count($data)) {                                 
                        $entrada= $data[0]['entrada'];
                        $salida= $data[0]['salida'];
                        if($entrada===NULL  and $salida===NULL)
                        {
                            // es primer entrada. 
                            $_SESSION['bitacora']=$data[0]['id'];
                            
                        }
                        else if($entrada!=NULL and $salida==NULL)
                        {
                            // es primer salida.
                            $_SESSION['estado']='fin';
                            $_SESSION['bitacora']=$data[0]['id'];
                        }
                        else 
                        {
                            // nueva entrada.
                            $_SESSION['bitacora'] = "NUEVO";
                        }
                    }
                    else {
                        // error.
                        unset($_SESSION['estado']);
                        header('Location: ../Error.php?w=validarVisitanteFormulario');
                        exit;
                    }
                }
                else {
                    // no hay formulario para la fecha.
                    // Si no hay permiso anual, tiempo expirado, estado = 3.          
                    if(!$this::ValidarPermisoAnual())                        
                        $_SESSION['estado']='3';
                    //return false;
                }                
            }else {
                // return false;
                // NO tiene formulario.
                // Muestra pagina de ingreso de informacion de visita si es un visitante en la lista anual, sino, muestra denegado.                
                 if(!$this::ValidarPermisoAnual()) {
                    // Visitante sin formulario y no en lista anual.
                    $_SESSION['estado']='4';
                    //return false;
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

     
    

}
?>