<?php 
    require_once("../class/Usuario.php");
    require_once("../class/Sesion.php");
    require_once("../class/Log.php");

    $usuario= new Usuario();
    $sesion = new Sesion();
    //
    if(isset($_POST["username"]) && isset($_POST["password"])){
        $usuario->usuario=$_POST['username'];        
        $usuario->contrasena=$_POST['password'];
        // 
        if($usuario->ValidarUsuarioLDAP())
        {
            $sesion->Inicio($usuario->usuario, $usuario->idrol);
            if(isset($_SESSION['url'])){
                if ($sesion->rol=="2")
                    header('Location: ../ListaFormulario.php?username=' . $sesion->username); 
                else header('Location: ../'. $_SESSION['url']); 
                unset($_SESSION['url']);
                exit;
            }
            else {
                if ($sesion->rol=="2")
                    header('Location: ../ListaFormulario.php?username=' . $sesion->username); 
                else header('Location: ../index.php'); 
                exit;
            }
        }
        else //usuario denegado
        {
            $sesion->logout();
            header('Location: ../Login.php?ID=invalid');
            exit;
        }
    }
    else {
		header('Location: ../Login.php?ID=error');
		exit;
    }
?>