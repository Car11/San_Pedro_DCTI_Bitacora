<?php
class Sesion{
	private $login=false;
	public $username;
	
	function __construct(){
        if (!isset($_SESSION))
		  session_start();
		$this->verificaLogin();
		if($this->login){
            return true;
		} else {
            return false;
		}
	}
	
	private function verificaLogin(){
		if(isset($_SESSION["username"])){
			$this->username = $_SESSION["username"];            
			$this->login = true;            
		} else {
			unset($this->username);
			$this->login = false;            
		}
	}
	
	public function inicioLogin($u){
        $this->username = $_SESSION["username"] = $u;
        $this->login = true;	
	}
	
	public function finLogin(){
		unset($_SESSION["username"]);
		unset($this->username);
		$this->login = false;
	}
	
	public function estadoLogin(){
		return $this->login;
	}
	
}
?>