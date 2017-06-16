<?php 

class DATA {
	/*private static $host = "10.129.29.85";
	private static $usuario = "Admin";
	private static $clave = "12345";
	private static $db = "registroingreso";*/
	public static $conn;
    private static $connSql;
	
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
    
    public static function ConectarSQL(){
        try {           
            if(!isset(self::$connSql)) {
                $config = parse_ini_file('ini/config.ini'); 
                self::$connSql =  odbc_connect("Driver={SQL Server Native Client 10.0};Server=". $config['hostsql'] . ";Database=". $config['dbnamesql'].";", "dbaadmin","dbaadmin");
                return self::$connSql;   
            }
        } catch (PDOException $e) {
            print('<br>'. $e);exit;
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
    
    public static function EjecutarSQL($sql) {
        try{
            //conecta a BD
            DATA::ConectarSQL();     
            //echo $sql;
            $result= odbc_exec(self::$connSql, $sql);            
            //odbc_close(self::$connSql);
            //
            //echo '<br>:'. $result;
            //echo '<br> nombre: '. odbc_result($result, "codigo");
            /*while(odbc_fetch_row($result)){
                $name = odbc_result($result, 1);
                print("$name");
            }*/
            return $result;
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