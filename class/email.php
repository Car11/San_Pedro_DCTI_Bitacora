<?php
class email{

    //public $visitante;

     static function Enviar($idvisitante, $idformulario, $asunto, $mensajeEncabezado, $idtarjeta="NULL"){
        // smtpapl.correo.ice
        // puerto 25
        // ip 10.149.20.26
        // ICETEL\OperTI
        // Clave: Icetel2017
        // Buzón: OperacionesTI@ice.go.cr
       try{
            //consulta datos del visitante
            require_once("Visitante.php");        
            $visitante= new Visitante();
            $visitante->Cargar($idvisitante);     
            //
            if (count($visitante)){ 
                require_once("Formulario.php");        
                $formulario= new Formulario();
                $formulario->id=$idformulario;
                $formulario->Cargar();    
                //
                ini_set('SMTP','smtpapl.correo.ice');
                $to = "ZZT OFICINA PROCESAMIENTO <ofproc1@ice.go.cr>";
                //$to= "cchaconc@ice.go.cr";   
                $from = "operTI@ice.go.cr";
                //
                $mensaje = "<h2><i>".$mensajeEncabezado."<i><h2>";
                $mensaje .= '<html><body>';
                $mensaje .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
                $mensaje .= "<tr style='background: #eee;'><td><strong>ID:</strong> </td><td>". $idvisitante ."</td></tr>";
                $mensaje .= "<tr><td><strong>Nombre:</strong> </td><td>" .  $visitante->nombre  . "</td></tr>";
                $mensaje .= "<tr><td><strong>Empresa:</strong> </td><td>" . $visitante->empresa . "</td></tr>";
                $mensaje .= "<tr><td><strong>Detalle:</strong> </td><td>" . $formulario->motivovisita . "</td></tr>";
                $mensaje .= "<tr><td><strong>Link:</strong> </td><td>" . "http://10.149.20.26:8000//control_acceso_cdc/formularioingreso.php?ID=" . $idformulario . "</td></tr>";
                if($idtarjeta!="NULL")
                    $mensaje .= "<tr><td><strong>Tarjeta:</strong> </td><td>"  . $idtarjeta . "</td></tr>";
                $mensaje .= "</table>";
                $mensaje .= "</body></html>";
                //
                $headers = "MIME-Version: 1.0\r\n"; 
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                $headers .= "From: ".$from."\r\n"; 
                //
                mail($to, $asunto, $mensaje,$headers);      
            }            
        }     
        catch(Exception $e) {
            $_SESSION['errmsg']= $e->getMessage() . " Notificar a Operaciones";
            header('Location: ../Error.php');
            exit;
        }
    }
    
}
?>