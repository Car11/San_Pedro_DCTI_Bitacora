<?php 
    include("php/usuario.php");
    include("php/sesion.php");
    $usuario= new usuario();
    $sesion = new sesion();
    //
    if(isset($_POST["username"]) && isset($_POST["password"])){
        $usuario->username=$_POST['username'];        
        $usuario->password=$_POST['password'];
        if($usuario->Validar())
        {
            $sesion->inicioLogin($usuario->username);
            header('Location: MenuAdmin.php'); 
            exit;
        }
        else //usuario denegado
        {
            $sesion->finLogin();
            header('Location: login.php?ID=invalid');
            exit;
        }
    }
    else {
		header("location:inicio.php?ID=NOUP");
		exit;
    }
    //
    
?>