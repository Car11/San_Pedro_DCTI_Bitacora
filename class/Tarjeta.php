<?php 
if (!isset($_SESSION))
    session_start();
class Tarjeta{
    public $id;
    public $consecutivo;
    public $idsala;
    public $nombresala;
    public $estado;

    function Asignar(){
        $sql="SELECT id , consecutivo
            from tarjeta 
            where estado=0 and idsala=(select id from sala where nombre= :nombresala) 
            order by consecutivo asc limit 1";
        $param= array(':nombresala'=>$this->nombresala);
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
}

?>