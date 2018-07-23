<?php 
ini_set('display_errors', 0);
if (!isset($_SESSION))
    session_start();

if(isset($_POST["action"])){
    $visitante= new Visitante();
    switch($_POST["action"]){
        case "Excluye":
            $visitante->ConsultaVisitante();
            break;
        case "ValidaCedulaUnica":
            $visitante->cedula= $_POST["cedula"];
            $visitante->nombre= $_POST["nombre"];
            $visitante->ValidaCedulaUnica();
            break;
        case "CargarTodos":
            echo json_encode($visitante->CargarTodos());
            break;
        case "Cargar": // carga visitante por cedula
            echo json_encode($visitante->Cargar($_POST["cedula"]));
            break;
        case "CargarID": // carga visitante por ID
            if(isset($_POST["idvisitante"])){
                $visitante->ID= $_POST["idvisitante"];
                echo json_encode($visitante->CargarID());
            }
            break;
        case "Insertar":
            $visitante->cedula= $_POST["cedula"];
            $visitante->nombre= $_POST["nombre"];
            $visitante->empresa= $_POST["empresa"];
            $visitante->permisoanual= $_POST["permiso"];
            $visitante->Agregar();
            break;
        case "Modificar":
            $visitante->ID= $_POST["idvisitante"];
            $visitante->cedula= $_POST["cedula"];
            $visitante->nombre= $_POST["nombre"];
            $visitante->empresa= $_POST["empresa"];
            $visitante->permisoanual= $_POST["permiso"];
            $visitante->Modificar();
            break;
        case "Eliminar":
            $visitante->ID= $_POST["idvisitante"];            
            $visitante->Eliminar();
            break;
        case "Webnoc_proximo":
            echo json_encode($visitante->Webnoc_proximo());
            break;
        case "Webnoc_ensitio":
            echo json_encode($visitante->Webnoc_ensitio());
            break;
    }
    
}

class Visitante{
    public $ID;
	public $cedula;
	public $nombre;
	public $empresa;
    public $permisoanual=0;
    public $visitante;
    public $visitanteexcluido;

	function __construct(){
        require_once("Conexion.php");
        require_once("Log.php");
    }

    //
    // Funciones de validacion de identificacion y formulario de visitante.
    //
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
            log::AddD('FATAL', 'Ha ocurrido un error al realizar la Entrada del Visitante', $e->getMessage());
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
                FROM tarjeta t inner join bitacora b on b.idtarjeta= t.id
                WHERE t.consecutivo=:consecutivo AND estado=1 
                order by b.fechacreacion desc limit 1";
            $param= array(':consecutivo'=>$this->cedula);   // cedula en este caso es el idtarjeta que viaja por POST
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
                    $_SESSION["idvisitante"]=$data[0]['idvisitante'];
                }   
                else {
                    // la tarjeta no esta en uso.
                    $_SESSION['estado']= "TARJETANULL";                
                }      
            }
            else {
                // la tarjeta no esta en uso.
                $_SESSION['estado']= "TARJETANULL";        
            }
            header('Location: ../index.php');
            exit;      
        }
        catch(Exception $e) {
            log::AddD('FATAL', 'Ha ocurrido un error al realizar la Entrada del Visitante', $e->getMessage());
            header('Location: ../Error.php?w=validarIDTarjeta');
            exit;
        }     
    }

    // Estado del formulario por Visitante.
    function ValidaEstadoFormulario(){
        try{
            include_once('Formulario.php');
            $formulario= new Formulario();
            if($formulario->ConsultaVisitantePorFormulario($this->ID))
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
                    order by fechacreacion desc limit 1 ";
                $param= array(':idvisitante'=>$this->ID, ':idformulario'=>$formulario->id);
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
                    else {
                        // Nueva entrada.
                        if(strtotime($fechaanticipada->format('Y-m-d H:i:s')) <=  time() && time() <= strtotime($formulario->fechasalida))
                            $_SESSION['bitacora'] = "NUEVO"; // Nuevo id de Bitacora 
                        else {
                            // la entrada no es en la fecha/hora correcta.
                            if(!$this::ValidarPermisoAnual())                        
                                $_SESSION['estado']='3';
                            return false;
                        }
                    }
                }
                else 
                {
                    // Primera entrada.
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
            log::AddD('FATAL', 'Ha ocurrido un error al realizar la Entrada del Visitante', $e->getMessage());
            header('Location: ../Error.php?w=validarFormulario');
            exit;
        }                  
    }

    function ValidarPermisoAnual(){
        try{
            $this::Cargar($this->cedula);
            if ($this->permisoanual=="1") {  
                /***** falta codigo para seleccionar el centro de datos y la sala 
                unset($_SESSION['estado']);
                $_SESSION['link']="true";                    
                header('Location: ../InfoVisita.php?id='. $this->cedula);
                exit;
                */
                // temporalmente envia estado = 4 (sin formulario) hasta desarrollar el datacenter.
                $_SESSION['estado']='3';
                
            } else return false;
        }
        catch(Exception $e) {
            unset($_SESSION['estado']);
            log::AddD('FATAL', 'Ha ocurrido un error al realizar la Entrada del Visitante', $e->getMessage());
            header('Location: ../Error.php?w=ValidarIdPermisoAnual');
            exit;
        }
    }

    function ValidaIdVisitante(){
        try{            
            if (count($this->Cargar($this->cedula))) {
                // Inicia la sesion para la cedula ingresada.
                $_SESSION["idvisitante"]=$this->ID;
                return true;
            }
            else {
                // return false;
                // No existe el visitante en base de datos, muestra nuevo perfil.
                unset($_SESSION['idvisitante']);
                unset($_SESSION['estado']);
                $_SESSION['link']="true";
                header('Location: ../NuevoPerfil.php?id='.$this->cedula);
                exit;
            }
        }
        catch(Exception $e) {
            unset($_SESSION['estado']);
            log::AddD('FATAL', 'Ha ocurrido un error al realizar la Entrada del Visitante', $e->getMessage());
            header('Location: ../Error.php?w=validarIDVisitante');
            exit;
        }
    }    

    function ValidaCedulaUnica(){
        try{
            $sql = "SELECT id
                    FROM visitante
                    where cedula=:cedula and nombre<> :nombre";
            $param= array(':cedula'=>$this->cedula, ':nombre'=>$this->nombre);
            $data = DATA::Ejecutar($sql,$param);      
            if ($data) {                       
                echo "invalida";
            } else echo "valida";
        }
        catch(Exception $e) {
            log::AddD('FATAL', 'Ha ocurrido un error al realizar la Entrada del Visitante', $e->getMessage());
        }
    }
    
    //
    // Funciones de Mantenimiento.
    //
    function Agregar(){
        try {
            $sql="INSERT INTO visitante (nombre, cedula, empresa, permisoanual) VALUES (upper(:nombre), :cedula, upper(:empresa), :permisoanual);";
            $param= array(':nombre'=>$this->nombre,':cedula'=>$this->cedula,':empresa'=>$this->empresa, ':permisoanual'=>$this->permisoanual=="true"?1:0);
            $data = DATA::Ejecutar($sql,$param,true);
            if($data)
            {
                //ID ingresado
                $this->getID();
                $_SESSION['idvisitante']= $this->ID;
                return true;
            }
            else var_dump(http_response_code(500)); // error
        }     
        catch(Exception $e) {
            log::AddD('FATAL', 'Ha ocurrido un error al realizar la Entrada del Visitante', $e->getMessage());
            header('Location: ../Error.php?w=conectar&id='.$e->getMessage());
            exit;
        }
    }

    function getID(){
        try{
            $sql="SELECT id FROM visitante ORDER BY fechacreacion DESC LIMIT 1";
            $data= DATA::Ejecutar($sql);
            $this->ID= $data[0]['id'];
        }
        catch(Exception $e){

        }
    }

    function Modificar(){
        try {
            $sql="UPDATE visitante
                SET  nombre= upper(:nombre), cedula= :cedula, empresa= upper(:empresa) , permisoanual= :permisoanual
                WHERE ID=:ID";
            $param= array(':nombre'=>$this->nombre,':cedula'=>$this->cedula,':empresa'=>$this->empresa, 'permisoanual'=>$this->permisoanual=="true"?1:0, ':ID'=>$this->ID);
            $data = DATA::Ejecutar($sql,$param,true);
            if($data)
                return true;
            else var_dump(http_response_code(500)); // error
        }     
        catch(Exception $e) {
            log::AddD('FATAL', 'Ha ocurrido un error al realizar la Entrada del Visitante', $e->getMessage());
            header('Location: ../Error.php?w=conectar&id='.$e->getMessage());
            exit;
        }
    }    

    // Carga visitante por cedula.
    function Cargar($cedula){
        try {
            $sql="SELECT id,cedula,nombre,empresa,permisoanual 
                FROM visitante 
                where cedula=:cedula";
            $param= array(':cedula'=>$cedula);
            $data= DATA::Ejecutar($sql,$param);
            //
            if(count($data)){
                $this->ID= $data[0]['id'];
                $this->cedula= $data[0]['cedula'];
                $this->nombre= $data[0]['nombre'];
                $this->empresa= $data[0]['empresa'];
                $this->permisoanual= $data[0]['permisoanual'];
            }            
            //            
            return $data;            
        }
        catch(Exception $e) {
            log::AddD('FATAL', 'Ha ocurrido un error al realizar la Entrada del Visitante', $e->getMessage());
            var_dump(http_response_code(500)); // error ajax
        }
    }

    // Carga visitante por ID (UUID)
    function CargarID(){
        try {
            $sql="SELECT id,cedula,nombre,empresa,permisoanual 
                FROM visitante 
                where id=:ID";
            $param= array(':ID'=>$this->ID);
            $data= DATA::Ejecutar($sql,$param);
            //
            if(count($data)){
                $this->ID= $data[0]['id'];
                $this->cedula= $data[0]['cedula'];
                $this->nombre= $data[0]['nombre'];
                $this->empresa= $data[0]['empresa'];
                $this->permisoanual= $data[0]['permisoanual'];
            }
            //            
            return $data;
        }
        catch(Exception $e) {
            log::AddD('FATAL', 'Ha ocurrido un error al realizar la Entrada del Visitante', $e->getMessage());
            var_dump(http_response_code(500)); // error ajax
        }
    }

    function ValidarEliminar(){
        try{
            $sql="SELECT ID
                FROM visitanteporformulario F  
                WHERE F.idvisitante= :ID 
                UNION 
                SELECT ID
                FROM  bitacora B 
                WHERE B.idvisitante= :ID";
            $param= array(':ID'=>$this->ID);
            $data= DATA::Ejecutar($sql, $param);
            if(count($data))
                return true;
            else return false;
        }
        catch(Exception $e){
            log::AddD('FATAL', 'Ha ocurrido un error al realizar la Entrada del Visitante', $e->getMessage());
            var_dump(http_response_code(500)); // error ajax
        }
    }

    function Eliminar(){
        try {
            if($this->ValidarEliminar()){
                echo "Registro en uso";
                return false;
            }                
            $sql='DELETE FROM visitante 
            WHERE id= :ID';
            $param= array(':ID'=>$this->ID);
            $data= DATA::Ejecutar($sql, $param, true);
            if($data)
                return true;
            else var_dump(http_response_code(500)); // error 
        }
        catch(Exception $e) {            
            log::AddD('FATAL', 'Ha ocurrido un error al realizar la Entrada del Visitante', $e->getMessage());
            var_dump(http_response_code(500)); // error ajax
        }
    }

    function CargarTodos(){
        try {
            $sql='SELECT id, cedula, nombre, empresa, permisoanual 
            FROM visitante 
            ORDER BY cedula';
            $data= DATA::Ejecutar($sql);
            if($data)
                return $data;
            else var_dump(http_response_code(500)); // error
        }
        catch(Exception $e) {        
            log::AddD('FATAL', 'Ha ocurrido un error al realizar la Entrada del Visitante', $e->getMessage());    
            header('Location: ../Error.php?w=conectar&id=');
            exit;
        }
    }
    
    //
    // Consulta lista de visitantes que no pertenece a un formulario especifico.
    //

    function ConsultaVisitante()
    {
        try {
            if (empty($_POST['visitanteexcluido'])) {
                $sql="SELECT id,cedula,nombre,empresa FROM visitante";
                $result = DATA::Ejecutar($sql);
            }
            else{
                
                $sql="SELECT id,cedula,nombre,empresa FROM visitante  WHERE NOT FIND_IN_SET(id,:EXCLUSION)";
                $param= array(':EXCLUSION'=>$_POST['visitanteexcluido']);
                $result = DATA::Ejecutar($sql,$param);  
            }
            //
            echo json_encode($result);
        } catch (Exception $e) {
            log::AddD('FATAL', 'Ha ocurrido un error al realizar la Entrada del Visitante', $e->getMessage());
            header('Location: ../Error.php?w=visitante-bitacora&id=');
            exit;
        }
    } 

    // consulta para mostrar información en la web NOC en pantalla
    function Webnoc_proximo(){
        try {
            $sql='SELECT v.id as idvisitante ,f.id as idformulario, f.consecutivo, fechaingreso,nombre, cedula, empresa, motivovisita, rfc
                from formulario f inner join visitanteporformulario vf on vf.idformulario=f.id
                inner join visitante v on v.id=vf.idvisitante
                where fechaingreso>now() and f.idestado=1
                order by fechaingreso desc ';
            $data= DATA::Ejecutar($sql);
            if($data)
                return $data;
            else {
                //log::Add('ERROR', 'Ha ocurrido un error al INFO WEB NOC - Proximo');
                //var_dump(http_response_code(500)); // error
            }        
        } catch (Exception $e) {
            log::AddD('ERROR', 'Ha ocurrido un error al INFO WEB NOC - Proximo', $e->getMessage());
            var_dump(http_response_code(500)); // error ajax
            exit;
        }
    }

    // consulta para mostrar información en la web NOC en pantalla
    function Webnoc_ensitio(){
        try {
            $sql='SELECT v.id as idvisitante ,  f.id as idformulario, f.consecutivo, b.entrada , t.consecutivo as tarjeta ,  cedula, nombre , empresa, motivovisita, rfc
            from bitacora b inner join formulario f on f.id=b.idformulario
                inner join visitante v on v.id=b.idvisitante    
                inner join tarjeta t on t.id=b.idtarjeta
            where entrada is not null and salida is null 
            ORDER BY tarjeta asc';
            $data= DATA::Ejecutar($sql);
            if($data)
                return $data;
            else {
                //log::Add('ERROR', 'Ha ocurrido un error al INFO WEB NOC - en sitio');
                //var_dump(http_response_code(500)); // error
            }     
        
        } catch (Exception $e) {
            log::AddD('ERROR', 'Ha ocurrido un error al INFO WEB NOC - en sitio', $e->getMessage());
            var_dump(http_response_code(500)); // error ajax
            exit;
        }
    }



}
?>