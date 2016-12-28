<?php 
    //print "ok vv1";
    include("php/Visitante.php");
    if (isset($_POST['cedula'])) {
        //print "ok vv2";
        $v= new Visitante(); 
        $v->cedula=$_POST['cedula'];
        $v->Existe();
    }
    else header('Location: Error.html');
    
?>