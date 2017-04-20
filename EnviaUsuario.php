<?php 
    include("php/usuario.php");
    $usuario= new usuario();
    //
    if (isset($_POST['username'])) { 
        $usuario->username=$_POST['username'];        
    }
    //
    if (isset($_POST['password'])) {
        $usuario->password=$_POST['password'];
    }
    //
    $usuario->Conectar();
?>