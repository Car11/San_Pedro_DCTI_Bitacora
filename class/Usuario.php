<?php 
//session_start();
class Usuario{
	public $usuario;
	public $contrasena;
    public $idrol;
    public $nombre;
    public $email;
	
	function __construct(){
        require_once("Conexion.php");
        require_once("Log.php");
    }
	
    function Validar(){    
        $sql='SELECT usuario, idrol FROM usuario where contrasena=:contrasena  AND usuario=:usuario';
        $param= array(':usuario'=>$this->usuario, ':contrasena'=>$this->contrasena);        
        $data = DATA::Ejecutar($sql,$param);
        if (count($data) ) {
            $this->idrol= $data[0]['idrol'];
            log::Add('INFO', 'Inicio de sesión: '. $this->usuario);
            return true;
        }else {        
            return false;           
        }        
    }

    function BuscaRol(){    
        $sql='SELECT idrol FROM usuario where usuario=:usuario';
        $param= array(':usuario'=>$this->usuario);        
        $data = DATA::Ejecutar($sql,$param);
        //
        if (count($data) ) {
            $this->idrol= $data[0]['idrol'];
        }else {        
            // Rol tramitante (2) por defecto si no existe en BD. Registra nuevo usuario tipo 2= TRAMITANTE.
            $this::AddUser(); 
            $this->idrol= 2; 
        }        
    }

    function AddUser(){    
        $sql="INSERT INTO usuario (nombre, usuario, contrasena, idrol, email)
        VALUES (:nombre,:usuario, 'LDAP', '2' , :email)";
        $param= array(':nombre'=>utf8_encode($this->nombre), ':usuario'=>$this->usuario, ':email'=>$this->email);
        $data = DATA::Ejecutar($sql, $param);        
    }

    function ValidarUsuarioLDAP (){
        error_reporting(0);
        $adServer = "icetel.ice";
        $ldapport = 3268;
        $ldap = ldap_connect($adServer, $ldapport);        
        $ldapUser = $this->usuario;
        $ldapPasswd = $this->contrasena;
        $ldaprdn = 'icetel' . "\\" . $ldapUser;
        //ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        //ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
        $bind = @ldap_bind($ldap, $ldaprdn, $ldapPasswd);
        if ($bind) {
            $filter="(sAMAccountName=$ldapUser)";
            $result = ldap_search($ldap,"dc=icetel,dc=ice",$filter);
            //ldap_sort($ldap,$result,"sn");
            $info = ldap_get_entries($ldap, $result);
            for ($i=0; $i<$info["count"]; $i++)
            {
                if($info['count'] > 1)
                    break;
                //echo "<p>You are accessing <strong> ". $info[$i]["sn"][0] .", " . $info[$i]["givenname"][0] ."</strong><br /> (" . $info[$i]["samaccountname"][0] .")</p>\n";
                /*echo "<p>Accediendo como: <strong> ". $info[$i]["givenname"][0] ." " . $info[$i]["sn"][0] ."</strong>\n <br /> Usuario: <strong>" . $info[$i]["samaccountname"][0] ."</strong></p>\n";
                echo "<p>Correo: <strong> ". $info[$i]["mail"][0] . "</strong></p>\n";
                echo "<p>Telefono: <strong> ". $info[$i]["telephonenumber"][0] . "</strong></p>\n";               
                echo '<pre>';
                //var_dump($info);
                echo '</pre>';*/
                //$userDn = $info[$i]["distinguishedname"][0]; 
                $this->email= $info[$i]["mail"][0];
                $this->nombre = $info[$i]["sn"][0] . ' ' . $info[$i]["givenname"][0];
                //
                $this::BuscaRol();
                //log::Add('INFO', 'Inicio de sesión: '. $this->usuario);
                return true;  
            }
            @ldap_close($ldap);
        } else {
            log::Add('INFO', 'Inicio de sesión Inválida: '. $this->usuario);
            return false;  
        }
    }

    
    function Cargar(){    
        $sql='SELECT nombre, contrasena, idrol, email FROM usuario WHERE usuario=:usuario';
        $param= array(':usuario'=>$_SESSION['username']);        
        $data = DATA::Ejecutar($sql,$param);
        if (count($data) ) {
            $this->nombre= $data[0]['nombre'];
            $this->contrasena= $data[0]['contrasena'];
            $this->idrol= $data[0]['idrol'];
            $this->email= $data[0]['email'];
            return true;
        }else {        
            return false;           
        }        
    }

    function CargarTramitanteForm($idformulario){    
        $sql='SELECT idtramitante , email
            FROM formulario f inner join usuario u on u.id=f.idtramitante
            WHERE f.id=:idformulario';
        $param= array(':idformulario'=>$idformulario);        
        $data = DATA::Ejecutar($sql,$param);
        if (count($data) ) {
            $this->id= $data[0]['idtramitante'];
            $this->email= $data[0]['email'];
            return true;
        }else {        
            return false;           
        }        
    }
}
?>
