<?php 
    include("../class/usuario.php");
    include("../class/sesion.php");
    $usuario= new usuario();
    $sesion = new sesion();
    //
    if(isset($_POST["username"]) && isset($_POST["password"])){
        $usuario->usuario=$_POST['username'];        
        $usuario->contrasena=$_POST['password'];
        if($usuario->Validar())
        {
            $sesion->Inicio($usuario->usuario, $usuario->idrol);
            if(isset($_SESSION['url'])){
                header('Location: ../'. $_SESSION['url']); 
                unset($_SESSION['url']);
                exit;
            }
            else {
                header('Location: ../index.php'); 
                exit;
            }
        }
        else //usuario denegado
        {
            $sesion->Fin();
            header('Location: ../login.php?ID=invalid');
            exit;
        }
    }
    else {
		header('Location: ../login.php?ID=error');
		exit;
    }
?>