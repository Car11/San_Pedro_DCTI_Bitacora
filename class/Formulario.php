<?php
//ob_start();
if (!isset($_SESSION)) {
    session_start();
}

if(isset($_POST["action"])){

    if($_POST["action"]=="Consultarporvisitante"){
        $formulario= new formulario();
        $formulario->ConsultarporVisitante();
    }
    if($_POST["action"]=="RecargaTabla"){
        $formulario= new formulario();
        $formulario->RecargaTabla();
    }
    if($_POST["action"]=="CargaIDFormulario"){
        $formulario= new formulario();
        $formulario->CargaIDFormulario();
    }
    if($_POST["action"]=="CargarTabla"){
        $formulario= new formulario();
        $formulario->CargarTabla();
    }
    if($_POST["action"]=="Insertar"){
        $formulario= new formulario();
        $formulario->AgregarFormularioAJAX();
    }
    if($_POST["action"]=="Modificar"){
        $formulario= new formulario();
        $formulario->ModificarAJAX();
    }
    if($_POST["action"]=="CargaMOD"){
        $formulario= new formulario();
        $formulario->Carga();
    }
}

class Formulario
{
    public $id;
    public $consecutivo;
    public $fechaingreso;
    public $fechasalida;
    public $fechasolicitud;
    public $idsala;
    public $nombresala;
    public $placavehiculo;
    public $detalleequipo;
    public $motivovisita;
    public $visitante;
    public $idtramitante;
    public $nombretramitante;
    public $idautorizador;
    public $nombreautorizador;
    public $idresponsable;
    public $nombreresponsable;
    public $nombreestado;
    public $estado;
    public $rfc;
    public $iddatacenter;
        
    function __construct()
    {
        require_once("Conexion.php");
        require_once("Log.php");
        //error_reporting(E_ALL);
        // Always in development, disabled in production
        //ini_set('display_errors', 1);
    }
    //*****************
    //Agrega formulario
    function AgregarFormulario()
    {
        try {
            
            $sql="INSERT INTO formulario(fechaingreso,idsala,fechasalida,placavehiculo,detalleequipo,motivovisita,idresponsable,idautorizador,idtramitante,idestado,rfc)
                VALUES (:fechaingreso,(SELECT id FROM sala WHERE nombre= :nombresala),:fechasalida,:placavehiculo,
                :detalleequipo,:motivovisita,(SELECT id FROM responsable WHERE nombre= :nombreresponsable),
                (SELECT id FROM usuario WHERE nombre= :nombreautorizador),(SELECT id FROM usuario WHERE nombre= :nombretramitante),:estado,:rfc)";
            $param= array(':fechaingreso'=>$this->fechaingreso,
                          ':nombresala'=>$this->nombresala,
                          ':fechasalida'=>$this->fechasalida,
                          ':placavehiculo'=>$this->placavehiculo,
                          ':detalleequipo'=>$this->detalleequipo,
                          ':motivovisita'=>$this->motivovisita,
                          ':nombreresponsable'=>$this->nombreresponsable,
                          ':nombreautorizador'=>$this->nombreautorizador,
                          ':nombretramitante'=>$this->nombretramitante,
                          ':estado'=>$this->estado,
                          ':rfc'=>$this->rfc);
            $result = DATA::Ejecutar($sql, $param);
            //Consultar el Maximo ID insertado
            $maxid="SELECT id FROM formulario ORDER BY consecutivo DESC LIMIT 0,1";
            
            //Captura el id del formulario
            $idformulario =DATA::Ejecutar($maxid);
            //Convierte el string en un arreglo
            $visitantearray = explode(",", $this->visitante);
            //Calcula la longitud del arreglo de visistantes 
            $longitud = count($visitantearray);
            //Recorre el arreglo e inserta cada item en la tabla intermedia
            for ($i=0; $i<$longitud; $i++) {
                $sql='INSERT INTO visitanteporformulario(idvisitante,idformulario) VALUES ((SELECT id from visitante WHERE cedula=:cedula),:idformulario)';
                $param= array(':cedula'=>$visitantearray[$i],':idformulario'=>$idformulario[0][0]);
                $result = DATA::Ejecutar($sql, $param);
            }
            header('Location:../ListaFormulario.php');
            exit;
        } catch (Exception $e) {
            header('Location: ../Error.php?w=visitante-agregar&id='.$e->getMessage());
            exit;
        }
    }

    //Agrega formulario
    function AgregarFormularioAJAX()
    {
        try {
            
            $sql="INSERT INTO formulario(fechaingreso,idsala,fechasalida,placavehiculo,detalleequipo,motivovisita,idresponsable,idautorizador,idtramitante,idestado,rfc)
                VALUES (:fechaingreso,:idsala,:fechasalida,:placavehiculo,
                :detalleequipo,:motivovisita,:idresponsable,
                (SELECT id FROM usuario WHERE nombre= :nombreautorizador),(SELECT id FROM usuario WHERE nombre= :nombretramitante),:estado,:rfc)";
            $param= array(':fechaingreso'=>$_POST["fechaingreso"],
                            ':idsala'=>$_POST["idsala"],
                            ':fechasalida'=>$_POST["fechasalida"],
                            ':placavehiculo'=>$_POST["placavehiculo"],
                            ':detalleequipo'=>$_POST["detalleequipo"],
                            ':motivovisita'=>$_POST["motivovisita"],
                            ':idresponsable'=>$_POST["idresponsable"],
                            ':nombreautorizador'=>$_POST["nombreautorizador"],
                            ':nombretramitante'=>$_POST["nombretramitante"],
                            ':estado'=>$_POST["estado"],
                            ':rfc'=>$_POST["rfc"]);

                            $result = DATA::Ejecutar($sql, $param);
            //Consultar el Maximo ID insertado
            $maxid="SELECT id FROM formulario ORDER BY consecutivo DESC LIMIT 0,1";
            
            //Captura el id del formulario
            $idformulario =DATA::Ejecutar($maxid);
            //Convierte el string en un arreglo
            $visitantearray = explode(",", $_POST["visitante"]);
            //Calcula la longitud del arreglo de visistantes 
            $longitud = count($visitantearray);
            //Recorre el arreglo e inserta cada item en la tabla intermedia
            for ($i=0; $i<$longitud; $i++) {
                $sql='INSERT INTO visitanteporformulario(idvisitante,idformulario) VALUES ((SELECT id from visitante WHERE cedula=:cedula),:idformulario)';
                $param= array(':cedula'=>$visitantearray[$i],':idformulario'=>$idformulario[0][0]);
                $result = DATA::Ejecutar($sql, $param);
            }
            header('Location:../ListaFormulario.php');
            exit;
        } catch (Exception $e) {
            header('Location: ../Error.php?w=visitante-agregar&id='.$e->getMessage());
            exit;
        }
    }
    
    //*********************
    //Modifca el formulario
    function Modificar()
    {
        try {
            $sql="UPDATE formulario SET fechaingreso=:fechaingreso,fechasalida=:fechasalida,idtramitante=(SELECT id FROM usuario WHERE nombre= :nombretramitante),
            idautorizador=(SELECT id FROM usuario WHERE nombre= :nombreautorizador),idresponsable=(SELECT id FROM responsable WHERE nombre= :nombreresponsable),placavehiculo=:placavehiculo,
            detalleequipo=:detalleequipo,motivovisita=:motivovisita,idestado=:estado,idsala=(SELECT id FROM sala WHERE nombre= :nombresala),rfc=:rfc WHERE id=:id;";
            $param= array(':fechaingreso'=>$this->fechaingreso,
                          ':fechasalida'=>$this->fechasalida,
                          ':nombretramitante'=>$this->nombretramitante,
                          ':nombreautorizador'=>$this->nombreautorizador,
                          ':nombreresponsable'=>$this->nombreresponsable,
                          ':placavehiculo'=>$this->placavehiculo,
                          ':detalleequipo'=>$this->detalleequipo,
                          ':motivovisita'=>$this->motivovisita,
                          ':estado'=>$this->estado,
                          ':nombresala'=>$this->nombresala,
                          ':rfc'=>$this->rfc,
                          ':id'=>$this->id);
            $result = DATA::Ejecutar($sql, $param);

            //Convierte el string en un arreglo
            $visitantearray = explode(",", $this->visitante);

            //Elimina los registros segun el arreglo de visitantes
            $sql="DELETE FROM visitanteporformulario WHERE NOT FIND_IN_SET((SELECT cedula from visitante WHERE id=idvisitante),:EXCLUSION) 
            AND idformulario=:id";
            $param= array(':EXCLUSION'=>$this->visitante,
            ':id'=>$this->id);

            $result = DATA::Ejecutar($sql, $param);
            
            $longitud = count($visitantearray);

            // formulario temporal, vacia la variable para llenarla con los id de los visitantes.
            if(isset( $_SESSION['TEMP']))
                $_SESSION['TEMP']="";

            //Recorre el arreglo e inserta cada item en la tabla intermedia
            for ($i=0; $i<$longitud; $i++) {
                // formulario temporal, agrega los idvisitante.
                if(isset( $_SESSION['TEMP'])){
                    $_SESSION['TEMP'] = $_SESSION['TEMP'] . $visitantearray[$i] . '-' . $this->estado . ',';
                }
                
                //Si no existe Inserta
                $existe="SELECT id FROM visitanteporformulario  WHERE idvisitante = (SELECT id FROM visitante WHERE cedula=:cedula) AND idformulario = (SELECT id FROM formulario WHERE id=:id)";
                $parametro= array(':cedula'=>$visitantearray[$i],':id'=>$this->id);
                $resultadoexiste= DATA::Ejecutar($existe, $parametro);

                if(count($resultadoexiste)==0){
                    $sql="INSERT INTO visitanteporformulario(idvisitante,idformulario) VALUES((SELECT id FROM visitante WHERE cedula=:cedula),(SELECT id FROM formulario WHERE id=:id))";
                    $param= array(':cedula'=>$visitantearray[$i],':id'=>$this->id);
                    $result = DATA::Ejecutar($sql, $param);
                }
            }       
            header('Location:../ListaFormulario.php');           
            exit;
        } catch (Exception $e) {
            header('Location: ../Error.php?w=visitante-agregar&id='.$e->getMessage());
            exit;
        }
    }

    //*********************
    //Modifca el formulario
    function ModificarAJAX()
    {
        try {
            $sql="UPDATE formulario SET fechaingreso=:fechaingreso,fechasalida=:fechasalida,idtramitante=(SELECT id FROM usuario WHERE nombre= :nombretramitante),
            idautorizador=(SELECT id FROM usuario WHERE nombre= :nombreautorizador),idresponsable=:idresponsable,placavehiculo=:placavehiculo,
            detalleequipo=:detalleequipo,motivovisita=:motivovisita,idestado=:estado,idsala=:idsala,rfc=:rfc WHERE id=:id;";
            $param= array(':fechaingreso'=>$_POST["fechaingreso"],
                            ':idsala'=>$_POST["idsala"],
                            ':fechasalida'=>$_POST["fechasalida"],
                            ':placavehiculo'=>$_POST["placavehiculo"],
                            ':detalleequipo'=>$_POST["detalleequipo"],
                            ':motivovisita'=>$_POST["motivovisita"],
                            ':idresponsable'=>$_POST["idresponsable"],
                            ':nombreautorizador'=>$_POST["nombreautorizador"],
                            ':nombretramitante'=>$_POST["nombretramitante"],
                            ':estado'=>$_POST["estado"],
                            ':rfc'=>$_POST["rfc"],
                            ':id'=>$_POST["id"]);
            $result = DATA::Ejecutar($sql, $param);

            //Convierte el string en un arreglo
            $visitantearray = explode(",", $_POST["visitante"]);

            //Elimina los registros segun el arreglo de visitantes
            $sql="DELETE FROM visitanteporformulario WHERE NOT FIND_IN_SET((SELECT cedula from visitante WHERE id=idvisitante),:EXCLUSION) 
            AND idformulario=:id";
            $param= array(':EXCLUSION'=>$_POST["visitante"],':id'=>$_POST["id"]);

            $result = DATA::Ejecutar($sql, $param);
            
            $longitud = count($visitantearray);

            // formulario temporal, vacia la variable para llenarla con los id de los visitantes.
            if(isset( $_SESSION['TEMP']))
                $_SESSION['TEMP']="";

            //Recorre el arreglo e inserta cada item en la tabla intermedia
            for ($i=0; $i<$longitud; $i++) {
                // formulario temporal, agrega los idvisitante.
                if(isset( $_SESSION['TEMP'])){
                    $_SESSION['TEMP'] = $_SESSION['TEMP'] . $visitantearray[$i] . '-' . $this->estado . ',';
                }
                
                //Si no existe Inserta
                $existe="SELECT id FROM visitanteporformulario  WHERE idvisitante = (SELECT id FROM visitante WHERE cedula=:cedula) AND idformulario = (SELECT id FROM formulario WHERE id=:id)";
                $parametro= array(':cedula'=>$visitantearray[$i],':id'=>$_POST["id"]);
                $resultadoexiste= DATA::Ejecutar($existe, $parametro);

                if(count($resultadoexiste)==0){
                    $sql="INSERT INTO visitanteporformulario(idvisitante,idformulario) VALUES((SELECT id FROM visitante WHERE cedula=:cedula),(SELECT id FROM formulario WHERE id=:id))";
                    $param= array(':cedula'=>$visitantearray[$i],':id'=>$_POST["id"]);
                    $result = DATA::Ejecutar($sql, $param);
                }
            }       
            header('Location:../ListaFormulario.php');           
            exit;
        } catch (Exception $e) {
            header('Location: ../Error.php?w=visitante-agregar&id='.$e->getMessage());
            exit;
        }
    }
    
    //Consulta formulario para llenar tabla
    function ConsultaFormulario()
    {
        try {
            $sql = "SELECT id,fechasolicitud,motivovisita,(SELECT nombre FROM estado WHERE id=idestado),fechaingreso,fechasalida,(SELECT nombre FROM usuario WHERE id=idtramitante),
            (SELECT nombre FROM usuario WHERE id=idautorizador),idresponsable,(SELECT nombre from sala WHERE id=idsala),placavehiculo,detalleequipo,rfc
            FROM formulario ORDER BY id DESC;";
            $result = DATA::Ejecutar($sql);
            return $result;
        } catch (Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }

    function ConsultaVisitantePorFormulario($idvisitante)
    {
        try{
            $sql="SELECT f.id as ID , consecutivo ,f.fechaingreso , f.fechasalida , f.idestado  as estado
                FROM formulario f inner join visitanteporformulario vf on f.id=vf.idformulario 
                where vf.idvisitante= :idvisitante
                order by f.FECHASOLICITUD desc limit 1 ";
            $param= array(':idvisitante'=>$idvisitante);
            $data = DATA::Ejecutar($sql,$param);
            if (count($data)) {  
                $this->id= $data[0]['ID'];
                $this->consecutivo= $data[0]['consecutivo'];
                $this->fechaingreso= $data[0]['fechaingreso'];
                $this->fechasalida= $data[0]['fechasalida'];                
                $this->estado= $data[0]['estado'];
                return true;
            }
            else{
                return false;
            }
        }catch (Exception $e) {
            header('Location: ../Error.php?w=formulario');
            exit;
        }

    }

    //***************************************
    // Carga formulario USANDO EL consecutivo
    function Cargar()
    {
        try {
            $sql = "SELECT id,fechasolicitud,idestado,motivovisita, 
                DATE_FORMAT(fechaingreso, '%Y-%m-%dT%H:%i') as fechaingreso,
                DATE_FORMAT(fechasalida, '%Y-%m-%dT%H:%i') as fechasalida,
                (SELECT nombre from usuario u inner join formulario f on f.idtramitante=u.id and f.id =:id)as nombretramitante,
                (SELECT nombre from usuario u inner join formulario f on f.idautorizador=u.id and f.id =:id) as nombreautorizador,
                (SELECT nombre from responsable r inner join formulario f on f.idresponsable=r.id and f.id =:id) as nombreresponsable,
                (SELECT sa.nombre FROM sala sa inner join formulario f on sa.id=f.idsala and f.id =:id) as nombresala,
                placavehiculo,detalleequipo, rfc, consecutivo, idsala, idresponsable
            FROM formulario WHERE id = :id;";

            $param= array(':id'=>$_POST["MOD"]);
            $data = DATA::Ejecutar($sql, $param);
            //
            if (count($data)) {
                $this->consecutivo= $data[0]['consecutivo'];
                $this->fechasolicitud= $data[0]['fechasolicitud'];
                $this->estado= $data[0]['idestado'];
                $this->motivovisita= $data[0]['motivovisita'];
                $this->fechaingreso= $data[0]['fechaingreso'];
                $this->fechasalida= $data[0]['fechasalida'];
                $this->nombretramitante= $data[0]['nombretramitante'];
                $this->nombreautorizador= $data[0]['nombreautorizador'];
                $this->nombreresponsable= $data[0]['nombreresponsable'];
                $this->nombresala= $data[0]['nombresala'];
                $this->placavehiculo= $data[0]['placavehiculo'];
                $this->detalleequipo= $data[0]['detalleequipo'];
                $this->rfc= $data[0]['rfc'];
                $this->id= $data[0]['id'];
                $this->idsala= $data[0]['idsala'];
                $this->idresponsable= $data[0]['idresponsable'];
            }
            //
            return $data;
        } catch (Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }

    // Carga formulario USANDO EL id
    // function CargarID()
    // {
    //     try {
    //         $sql = "SELECT id, consecutivo,fechasolicitud,idestado,motivovisita, 
    //             DATE_FORMAT(fechaingreso, '%Y-%m-%dT%H:%i') as fechaingreso,
    //             DATE_FORMAT(fechasalida, '%Y-%m-%dT%H:%i') as fechasalida,
    //             (SELECT nombre from usuario u inner join formulario f on f.idtramitante=u.id where f.id=:id)as nombretramitante,
    //             (SELECT nombre from usuario u inner join formulario f on f.idautorizador=u.id where f.id=:id) as nombreautorizador,
    //             (SELECT nombre from responsable r inner join formulario f on f.idresponsable=r.id where f.id=:id) as nombreresponsable,
    //             (SELECT sa.nombre FROM sala sa inner join formulario f on sa.id=f.idsala where f.id=:id) as nombresala,
    //             idsala, placavehiculo,detalleequipo, rfc
    //         FROM formulario WHERE id = :id";

    //         $param= array(':id'=>$this->id);
    //         $data = DATA::Ejecutar($sql, $param);
    //         //
    //         if (count($data)) {
    //             $this->consecutivo= $data[0]['consecutivo'];
    //             $this->fechasolicitud= $data[0]['fechasolicitud'];
    //             $this->estado= $data[0]['idestado'];
    //             $this->motivovisita= $data[0]['motivovisita'];
    //             $this->fechaingreso= $data[0]['fechaingreso'];
    //             $this->fechasalida= $data[0]['fechasalida'];
    //             $this->nombretramitante= $data[0]['nombretramitante'];
    //             $this->nombreautorizador= $data[0]['nombreautorizador'];
    //             $this->nombreresponsable= $data[0]['nombreresponsable'];
    //             $this->idsala= $data[0]['idsala'];
    //             $this->nombresala= $data[0]['nombresala'];
    //             $this->placavehiculo= $data[0]['placavehiculo'];
    //             $this->detalleequipo= $data[0]['detalleequipo'];
    //             $this->rfc= $data[0]['rfc'];
    //             $this->id= $data[0]['id'];
    //         }
    //         //
    //         return $data;
    //     } catch (Exception $e) {
    //         header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
    //         exit;
    //     }
    // }

    //*********************************************************
    //Carga los visitantes en la tabla principal del formulario
    function CargaVisitanteporFormulario()
    {
        try {
            //Adquiere el id de formulario con base al consecutivo
            /*$sql_id="SELECT id FROM formulario WHERE id=:id";
            $id= array(':id'=>$this->id);
            $idformulario = DATA::Ejecutar($sql_id, $id);*/
            //Selecciona los visitantes con base al id del formulario
            $sql="SELECT DISTINCT v.cedula,v.nombre,v.empresa from visitante v inner join visitanteporformulario vpf 
            on v.id=vpf.idvisitante and vpf.idformulario=:id";
            $param= array(':id'=>$this->id);
            $result= DATA::Ejecutar($sql,$param);           
            return $result;
        } catch (Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }
    
     function getID(){
        try{
            $sql="SELECT ID FROM formulario ORDER BY FECHASOLICITUD DESC LIMIT 1";
            $data= DATA::Ejecutar($sql);
            $this->id= $data[0]['ID'];
        }
        catch(Exception $e){

        }
    }

    function AgregarTemporal($idvisitante)
    {
        try {
            //agrega infomación del formulario temporal
            $sql="insert into formulario (FECHAINGRESO,FECHASALIDA,FECHASOLICITUD,IDSALA, MOTIVOVISITA, IDTRAMITANTE) 
                VALUES (NOW(),DATE_ADD(NOW(), INTERVAL 1 DAY), NOW(), (SELECT sa.ID FROM sala sa WHERE NOMBRE= :nombresala), :motivovisita, 
                (SELECT u.id FROM usuario u where u.usuario=:usuario)) ";
            $param= array(
                ':nombresala'=>$this->nombresala,
                ':motivovisita'=>$this->motivovisita,
                ':usuario'=>$_SESSION['username']
             );
            $data= DATA::Ejecutar($sql, $param, true);
            if ($data) {
                 //busca id de formulario agregado
                 $this->getID();
                 //agrega visitantes
                 $sql='insert into visitanteporformulario(idvisitante , idformulario) VALUES(:idvisitante,:idformulario)';
                 $param= array(':idvisitante'=>$idvisitante,':idformulario'=>$this->id);
                 $data=  DATA::Ejecutar($sql, $param);
                 include_once("Email.php");
                 email::Enviar($idvisitante, $this->id, "Formulario de Ingreso Pendiente", "formulario DE INGRESO PENDIENTE");
                 // elimina sesion link para evitar redirect a paginas anteriores.
                 unset($_SESSION['link']);
                 $_SESSION['estado']='pendiente';
                 header('Location: ../index.php');
                 exit;
            } else {
                  $_SESSION['errmsg']= 'Formulario no registrado, comunicarse con operaciones TI';
                header('Location: ../Error.php');
            }
        } catch (Exception $e) {
            $_SESSION['errmsg']= $e->getMessage();
            header('Location: ../Error.php');
            exit;
        }
    }


    function ConsultarporVisitante(){
        try {
            $sql = "SELECT DISTINCT f.consecutivo,f.fechasolicitud,(SELECT nombre FROM estado WHERE id=f.idestado) as estado,f.motivovisita,f.rfc
            FROM formulario f INNER JOIN  visitanteporformulario vxf ON f.id = vxf.idformulario INNER JOIN visitante v ON v.id=vxf.idvisitante 
            and (v.cedula like '%". $_POST["busqueda"] ."%' or v.nombre like '%". $_POST["busqueda"] ."%') ORDER BY consecutivo DESC;";

            $data = DATA::Ejecutar($sql);
            if (count($data)) {
                $this->fechasolicitud= $data[0]['fechasolicitud'];
                $this->estado= $data[0]['estado'];
                $this->motivovisita= $data[0]['motivovisita'];
                $this->rfc= $data[0]['rfc'];
            }
            echo json_encode($data);	 
        } catch (Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }    
    }

    function RecargaTabla(){
        try {
            $sql = "SELECT id,consecutivo,fechasolicitud,(SELECT nombre FROM estado WHERE id=idestado) as estado,motivovisita,rfc FROM formulario";
            $data = DATA::Ejecutar($sql);
            if (count($data)) {
                $this->fechasolicitud= $data[0]['fechasolicitud'];
                $this->estado= $data[0]['estado'];
                $this->motivovisita= $data[0]['motivovisita'];
                $this->rfc= $data[0]['rfc'];
            }
            echo json_encode($data);	 
        } catch (Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }

    function CargaIDFormulario(){
        try {
            $sql = "SELECT id FROM formulario WHERE consecutivo=:consecutivo";
            $param= array(':consecutivo'=>$_POST["consecutivo"]);
            $data = DATA::Ejecutar($sql,$param);
            if (count($data)) {
                $this->id= $data[0]['id'];
            }
            echo json_encode($data);	 
        } catch (Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }    
    }

    function CargarTabla(){
        try {
            $sql = "SELECT cedula,nombre,empresa FROM formulario WHERE id=:id";
            $param = array(':id'=>$_POST["id"]);
            $data = DATA::Ejecutar($sql,$param);
            if (count($data)) {
                $this->fechasolicitud= $data[0]['fechasolicitud'];
                $this->estado= $data[0]['estado'];
                $this->motivovisita= $data[0]['motivovisita'];
                $this->rfc= $data[0]['rfc'];
            }
            echo json_encode($data);	 
        } catch (Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }
}
