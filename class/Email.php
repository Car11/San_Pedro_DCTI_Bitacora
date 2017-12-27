<?php
class Email{

    //public $visitante;

    static function Enviar($idvisitante, $idformulario, $asunto, $mensajeEncabezado, $numTarjeta="NULL"){
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
                $strfrm= "http://operacionesTI/BitacoraCDC/FormularioIngreso.php?MOD=". $idformulario;
                $mensaje .= "<tr><td><strong>Link:</strong> </td><td> <a href=$strfrm>Formulario</a>  </td></tr>";
                if($numTarjeta!="NULL")
                    $mensaje .= "<tr><td><strong>Tarjeta:</strong> </td><td>"  . $numTarjeta . "</td></tr>";
                $mensaje .= "</table>";
                $mensaje .= "</body></html>";
                //
                $headers = "MIME-Version: 1.0\r\n"; 
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                $headers .= "From: ".$from."\r\n"; 
                //
                if(!mail($to, $asunto, $mensaje,$headers))
                {
                    require_once("Log.php");  
                    log::Add('ERROR', 'Ha ocurrido un error al realizar el envío de correo');
                }
            }            
        }     
        catch(Exception $e) {
            // no debe detener el proceso si no se envía el email.
            // log
            require_once("Log.php");  
            log::AddD('FATAL', 'Ha ocurrido un error al realizar el envío de correo', $e->getMessage());
        }
    }

    static function Formulario($idformulario,  $mensajeEncabezado, $estado){
        // smtpapl.correo.ice
        // puerto 25
        // ip 10.149.20.26
        // ICETEL\OperTI
        // Clave: Icetel2017
        // Buzón: OperacionesTI@ice.go.cr
       try{
            //info Formulario.
            require_once("Formulario.php");        
            $formulario= new Formulario();
            require_once("DataCenter.php");        
            $datacenter= new DataCenter();
            require_once("Usuario.php");        
            $usuario= new Usuario();
            //
            $formulario->id=$idformulario;
            $formulario->Cargar();    
            $datacenter->DataCenterporSala($formulario->idsala);
            // busca usuario tramitante          
            $usuario->CargarTramitanteForm($formulario->id);
            //
            $asunto= "Formulario <#$formulario->consecutivo> [$estado]";
            //email.
            ini_set('SMTP','smtpapl.correo.ice');
            $to = "ZZT OFICINA PROCESAMIENTO <ofproc1@ice.go.cr>; " . $usuario->email;
            //$to= $usuario->email;   
            $from = "operTI@ice.go.cr";
            // mensaje - encabezado
            $mensaje = "<h2><i>".$mensajeEncabezado."<i><h2>";
            $mensaje .= '<html><body>';
            $mensaje .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
            $mensaje .= "<tr style='background: #eee;'><td><strong>FORMULARIO:</strong> </td><td>". $formulario->consecutivo ."</td></tr>";
            $mensaje .= "<tr style='background: #eee;'><td><strong>ENTRADA:</strong> </td><td>". $formulario->fechaingreso ."</td></tr>";
            $mensaje .= "<tr style='background: #eee;'><td><strong>SALIDA:</strong> </td><td>". $formulario->fechasalida ."</td></tr>";
            $mensaje .= "<tr style='background: #eee;'><td><strong>SALA:</strong> </td><td>". $formulario->nombresala ."[". $datacenter->nombre  ."]</td></tr>";
            $mensaje .= "<tr style='background: #eee;'><td><strong>MOTIVO:</strong> </td><td>". $formulario->motivovisita ."</td></tr>";
            $mensaje .= "<tr style='background: #eee;'><td><strong>RFC:</strong> </td><td>". $formulario->rfc ."</td></tr>";
            $mensaje .= "<tr style='background: #eee;'><td><strong>TRAMITANTE:</strong> </td><td>". $usuario->nombre ."</td></tr>";            
            $strfrm= "http://operacionesTI/BitacoraCDC/FormularioIngreso.php?MOD=". $formulario->id;
            $mensaje .= "<tr><td><strong>Link:</strong> </td><td> <a href=$strfrm>Formulario</a>  </td></tr>";                        
            $mensaje .= "</table>";
            $mensaje .= "</body></html>";
            //
            $headers = "MIME-Version: 1.0\r\n"; 
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "From: ".$from."\r\n"; 
            //
            mail($to, $asunto, $mensaje,$headers);        
        }     
        catch(Exception $e) {
            // no debe detener el proceso si no se envía el email.
            // log
            require_once("Log.php");  
            log::AddD('FATAL', 'Ha ocurrido un error al realizar el envío de correo', $e->getMessage());
        }
    }
    
}
?>
