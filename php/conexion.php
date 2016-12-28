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
                self::$conn = new PDO('mysql:host='. $config['host'] .';dbname='.$config['dbname'].';charset=utf8',           $config['username'],          $config['password']); 
                //self::$conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //print "dbname: ".$config['dbname'];
                return self::$conn;
            }
        } catch (PDOException $e) {
            //print "Error!: " . $e->getMessage() . "<br/>";
            header('Location: Error.html?w=conectar&id='.$e->getMessage());
            exit;
            //return false;
            //die();
        }
    }
    
    public static function Ejecutar($sql, $param=NULL) {
        try{
            //conecta a BD
            DATA::Conectar();
            $st=DATA::$conn->prepare($sql);
            $st->execute($param);
            //
            return  $st->fetchAll();    
        } catch (Exception $e) {
            header('Location: Error.html?w=ejecutar&id='.$e->getMessage());
            exit;
            //return false;
            //die();
        }
    }
    
	private static function Close(){
		mysqli_close(self::$conn);	
		print "Cerrar la conexion en forma exitosa<br>";
		
	}
}
?>