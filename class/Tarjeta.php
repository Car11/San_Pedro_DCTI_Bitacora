<?php 
if (!isset($_SESSION))
    session_start();
class Tarjeta{
    public $id;
    public $idsala;
    public $nombresala;
    public $estado;

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
            from visitanteporformulario
            where idvisitante= :idvisitante and idformulario= :idformulario
            order by id desc limit 1";
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
}

?>