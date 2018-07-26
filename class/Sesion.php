<?php
 if(isset($_POST["accion"])){
	$sesion= new Sesion();
	$sesion->Logout();
 }
	

class Sesion{
	public $estado=false;
	public $username;
	public $rol;
	
	function __construct(){
        if (!isset($_SESSION))
		  session_start();
		$this->Verifica();
		/*if($this->login){
            return true;
		} else {
            return false;
		}*/
	}
	
	private function Verifica(){
		if(isset($_SESSION["username"])){
			$this->username = $_SESSION["username"];            
			$this->rol = $_SESSION["rol"];            
			$this->estado = true;            
		} else {
			unset($this->username);
			unset($this->rol);
			$this->estado = false;            
		}
	}
	
	public function Inicio($username, $rol){
        $this->username = $_SESSION["username"] = $username;
		$this->rol = $_SESSION["rol"] = $rol;
        $this->estado = true;	
	}
	
	public function Logout(){
		unset($_SESSION["username"]);
		unset($this->username);
		unset($_SESSION["rol"]);
		unset($this->rol);
		$this->estado = false;
	}
}

?>
