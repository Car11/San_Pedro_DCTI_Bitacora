<?php 
if (!isset($_SESSION))
    session_start();

if(isset($_POST["action"])){
    if($_POST["action"]=="RecargaPool"){
        $tarjeta= new tarjeta();
        $tarjeta->Consulta();
    }
}
class Tarjeta{
    public $id;
    public $idsala;
    public $nombresala;
    public $estado;

    function __construct()
    {
        require_once("conexion.php");
        require_once("log.php");
    }

    function Asignar(){
        $sql="SELECT id 
            from tarjeta 
            where estado=0 and idsala=(select id from sala where nombre= :nombresala) 
            order by id asc limit 1";
        $param= array(':nombresala'=>$this->nombresala);
        $data = DATA::Ejecutar($sql,$param);
        if (count($data)) {
            // tarjeta disponible.
            $this->id = $data[0]['id'];                              
        } else {
            // Tarjeta no disponible
            $this->id = -1;
        }
    }

    function CargaTarjetaAsignada($idvisitante , $idformulario){
        $sql="SELECT idtarjeta 
            from bitacora
            where idvisitante= :idvisitante and idformulario= :idformulario
            order by fechacreacion desc limit 1";
        $param= array(':idvisitante'=>$idvisitante, ':idformulario'=>$idformulario);
        $data = DATA::Ejecutar($sql,$param);
        if (count($data)) {
            // tarjeta disponible.
            $this->id = $data[0]['idtarjeta'];                              
        } else {
            // Tarjeta no disponible
            $this->id = -1;
        }
    }

    function Consulta(){
        try {
            $sql = "SELECT id,idsala,estado FROM tarjeta";
            $data = DATA::Ejecutar($sql);
            if (count($data)) {
                $this->id= $data[0]['id'];
                $this->idsala= $data[0]['idsala'];
                $this->estado= $data[0]['estado'];
            }
            echo json_encode($data);	 
        } catch (Exception $e) {
            header('Location: ../Error.php?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }           
    }
}

?>