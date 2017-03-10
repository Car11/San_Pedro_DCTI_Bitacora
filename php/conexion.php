<?php 

class DATA {
	/*private static $host = "10.129.29.85";
	private static $usuario = "Admin";
	private static $clave = "12345";
	private static $db = "registroingreso";*/
	private static $conn;
	
	public function __construct(){
		
	}  
    
    public static function Conectar(){
        try {
            if(!isset(self::$conn)) {
                $config = parse_ini_file('ini/config.ini'); 
                self::$conn = new PDO('mysql:host='. $config['host'] .';dbname='.$config['dbname'].';charset=utf8', $config['username'],   $config['password']); 
                return self::$conn;
            }
        } catch (PDOException $e) {
            header('Location: Error.html?w=conectar&id='.$e->getMessage());
            exit;
        }
    }
    
    public static function Ejecutar($sql, $param=NULL, $op=false) {
        try{
            //conecta a BD
            DATA::Conectar();
            $st=DATA::$conn->prepare($sql);
            $st->execute($param);
            //
            if(!$op)
            	return  $st->fetchAll();
			else return $st;    
        } catch (Exception $e) {
            header('Location: Error.html?w=ejecutar&id='.$e->getMessage());
            exit;
        }
    }
    
	private static function Close(){
		mysqli_close(self::$conn);	
		print "Cerrar la conexion en forma exitosa<br>";
	}
}
?>