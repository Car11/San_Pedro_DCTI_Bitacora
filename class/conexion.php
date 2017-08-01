<?php 

class DATA {
	/*private static $host = "10.129.29.85";
	private static $usuario = "Admin";
	private static $clave = "12345";
	private static $db = "registroingreso";*/
	public static $conn;
    private static $connSql;
	//private static $lastID;

	/*public function __construct(){ca
		
	}  */

   
    public static function Conectar(){
        try {
            if(!isset(self::$conn)) {
                $config="";
                if (file_exists('../ini/config.ini')) {
                    $config = parse_ini_file('../ini/config.ini'); 
                    //printf('ini: '. $config['host']);exit;
                } 
                else if (file_exists('ini/config.ini')) {
                    $config = parse_ini_file('ini/config.ini'); 
                    //printf('ini: '. $config['host']);exit;
                } 
                //
                self::$conn = new PDO('mysql:host='. $config['host'] .';dbname='.$config['dbname'].';charset=utf8', $config['username'],   $config['password']); 
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
            $st->execute($param);
            self::$conn->commit(); 
            //
            if(!$op)
            	return  $st->fetchAll();
			else return $st;    
        } catch (Exception $e) {
            self::$conn->rollback(); 
            header('Location: ../Error.php?w=ejecutar&id='.$e->getMessage());
            exit;
        }
    }
    
    public static function EjecutarSQL($sql, $param=NULL) {
        try{
            //conecta a BD
            self::ConectarSQL();    
            $st=self::$connSql->prepare($sql);
             self::$conn->beginTransaction(); 
            $st->execute($param);
            self::$conn->commit(); 
            return $st->fetchAll();
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
