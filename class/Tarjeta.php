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
    public $consecutivo;
    public $idsala;
    public $nombresala;
    public $estado;

    function __construct()
    {
        require_once("conexion.php");
        require_once("log.php");
    }

    function Asignar(){
        $sql="SELECT id , consecutivo
            from tarjeta 
            where estado=0 and idsala= :idsala
            order by consecutivo asc limit 1";
        $param= array(':idsala'=>$this->idsala);
        $data = DATA::Ejecutar($sql,$param);
        if (count($data)) {
            // tarjeta disponible.
            $this->id = $data[0]['id'];                              
            $this->consecutivo = $data[0]['consecutivo'];     
        } else {
            // Tarjeta no disponible
            $this->id = -1;
        }
    }

    function CargaTarjetaAsignada($idvisitante , $idformulario){
        $sql="SELECT idtarjeta , consecutivo 
            from bitacora b inner join tarjeta t on t.id=b.idtarjeta
            where idvisitante= :idvisitante and idformulario= :idformulario
            order by fechacreacion desc limit 1";
        $param= array(':idvisitante'=>$idvisitante, ':idformulario'=>$idformulario);
        $data = DATA::Ejecutar($sql,$param);
        if (count($data)) {
            // tarjeta disponible.
            $this->id = $data[0]['idtarjeta'];                              
            $this->consecutivo = $data[0]['consecutivo'];                              
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