<?php 

class Visitante{
	public $cedula;
	public $nombre;
	public $empresa;
	
	function __construct(){
        require_once("conexion.php");
        error_reporting(E_ALL);
        // Always in development, disabled in production
        ini_set('display_errors', 1);
    }
	
	function Existe(){
        //require_once("conexion.php");
        try {
            $sql='SELECT * FROM visitante where cedula=:cedula';
            $param= array(':cedula'=>$this->cedula);
            $result = DATA::Ejecutar($sql,$param);
            //
            if ( count($result) ) { 
                //si el visitante existe, 1. ingresa 2. sale.
                //Si la cedula tiene una entrada sin salida (NULL), registra la salida.
                $sql='SELECT * FROM bitacora where cedula=:cedula and salida IS NULL';
                $param= array(':cedula'=>$this->cedula);
                $result = DATA::Ejecutar($sql,$param);
                if ( count($result) ) { 
                    //es una salida, muestra campo detalle y luego guarda
                    //print "vis: ". $this->detalle."----";
                    if($this->detalle=="NULL"){
                        header('Location: index.php?ins=2&id='.$this->cedula);
                        exit;
                    }else{
                        //print "nooooo: ". $this->detalle;
                        //ya tiene detalle (puede ser espacio en blanco), realiza salida
                        $sql='UPDATE bitacora SET salida= :salida , detalle=:detalle WHERE cedula= :cedula and salida is NULL';
                        $param= array(':salida'=>date('Y-m-d H:i:s') , ':detalle'=>$this->detalle,  ':cedula'=>$this->cedula);
                        $result = DATA::Ejecutar($sql,$param);
                        //
                        header('Location: index.php?ins=1');
                        exit;
                    }                        
                }
                else //la cedula no esta ingresada, agrega entrada
                    $this->BitacoraEntrada();
                //foreach($result as $row) {
                //print_r($row);
                //}
                //registrar ingreso                
            } else {
                // si la cedula no está regisrada, debe mostrar formulario de ingreso
                header('Location: perfil.php?w=Visitante-Existe&id='.$this->cedula);
                exit;
            }
        }     
        catch(Exception $e) {
            header('Location: Error.html?id='.$e->getMessage());
            exit;
        }
    }
    
    function Agregar(){
        //require_once("conexion.php");
        try {
            $sql='INSERT INTO visitante (nombre, cedula, empresa)VALUES (:nombre, :cedula, :empresa)';
            $param= array(':nombre'=>$this->nombre,':cedula'=>$this->cedula,':empresa'=>$this->empresa);
            $result = DATA::Ejecutar($sql,$param);
            //Agrega la entrada
            $this->BitacoraEntrada();
        }     
        catch(Exception $e) {
            header('Location: Error.html?w=visitante-agregar&id='.$e->getMessage());
            exit;
        }
    }
    
    function BitacoraEntrada(){
        //require_once("conexion.php");
        try {
            $sql='INSERT INTO bitacora (cedula) VALUES (:cedula)';
            $param= array(':cedula'=>$this->cedula);
            $result = DATA::Ejecutar($sql,$param);
            //if ( count($result) ) 
            header('Location: index.php?ins=1');
            exit;
        }     
        catch(Exception $e) {
            header('Location: Error.html?w=visitante-bitacora&id='.$e->getMessage());
            exit;
        }
    }
}


?>