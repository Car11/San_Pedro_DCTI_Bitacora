<?php 

class DATA {

	public static $conn;
    private static $connSql;
    private static $config="";

	private static function ConfiguracionIni(){
        require_once('Globals.php');
        if (file_exists('../../ini/config.ini')) {
            self::$config = parse_ini_file('../../ini/config.ini',true); 
        } 
        else if (file_exists('../ini/config.ini')) {
            self::$config = parse_ini_file('../ini/config.ini',true); 
        }         
    }  

    private static function Conectar(){
        try {
            self::ConfiguracionIni();
            if(!isset(self::$conn)) {                                
                self::$conn = new PDO('mysql:host='. self::$config[Globals::app]['host'] .';dbname=' . self::$config[Globals::app]['dbname'].';charset=utf8', self::$config[Globals::app]['username'],   self::$config[Globals::app]['password']); 
                return self::$conn;
            }
        } catch (PDOException $e) {
            header('Location: ../Error.php?w=conectar&id='.$e->getMessage());
            exit;
        }
    }
    
    public static function ConectarSQL(){
        try {           
            if(!isset(self::$connSql)) {
                $config = parse_ini_file('../ini/config.ini'); 
                self::$connSql = new PDO("odbc:sqlserver", 'dbaadmin', 'dbaadmin'); 
                return self::$connSql;
            }
        } catch (PDOException $e) {
            print('<br>'. $e);exit;
            header('Location: ../Error.php?w=conectar&id='.$e->getMessage());
            exit;
        }
    }    

    // Ejecuta consulta SQL, $op = true envía los datos en 'crudo', $op=false envía los datos en arreglo (fetch).
    public static function Ejecutar($sql, $param=NULL, $op=false) {
        try{
            //conecta a BD
            self::Conectar();
            $st=self::$conn->prepare($sql);
            self::$conn->beginTransaction(); 
            if($st->execute($param))
            {
                self::$conn->commit(); 
                if(!$op)
                    return  $st->fetchAll();
                else return $st;    
            } else return false;
            
        } catch (Exception $e) {
            self::$conn->rollback(); 
            header('Location: ../Error.php?w=ejecutar&id='.$e->getMessage());
            exit;
        }
    }
    
    public static function EjecutarSQL($sql, $param=NULL, $op=false) {
        try{
            //conecta a BD
            self::ConectarSQL();    
            $st=self::$connSql->prepare($sql);
            self::$conn->beginTransaction(); 
            if($st->execute($param)){
                self::$conn->commit(); 
                if(!$op)
                    return  $st->fetchAll();
                else return $st;    
            } else return false;
        } catch (Exception $e) {
            self::$conn->rollback(); 
            header('Location: ../Error.php?w=ejecutar&id='.$e->getMessage());
            exit;
        }
    }
    
	private static function Close(){
		mysqli_close(self::$conn);			
	}

    public static function getLastID(){
        return self::$conn->lastInsertId( );
    }
}
?>
