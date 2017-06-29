<?php
if (!isset($_SESSION)) 
    session_start();
// Sesion de usuario
include("class/sesion.php");
$sesion = new sesion();
if (!$sesion->estado){
    header('Location: login.php');
    exit;
}
//GET
$idformulario="NULL";
$msg="NULL";
if (isset($_GET['idformulario'])) { 
    $idformulario=$_GET['idformulario'];
}
if (isset($_GET['msg'])) {
    $msg=$_GET['msg'];
}
// Busca información del formulario para desplegar en pantalla.
$formulario="NULL";
$tarjeta="NULL";
if($idformulario!="NULL")
{
    // Carga info del formulario.
    include("class/formulario.php");
    $formulario= new Formulario();
    $formulario->id= $idformulario;
    $formulario->Cargar();    
    // Carga info tarjeta
    include("class/tarjeta.php");
    $tarjeta= new tarjeta();
    if($msg!="fin"){
        // Carga tarjeta asiganada si es un ingreso, no salida (fin) 
        $tarjeta->nombresala= $formulario->nombresala;
        $tarjeta->Asignar();
    }
    else {
        // Carga la tarjeta asigana al visitante.
        $tarjeta->CargaTarjetaAsignada($_SESSION['cedula'] , $formulario->id);
    }
    // Carga Info VISITANTE
    include("class/Visitante.php");
    $visitante= new Visitante();
    $visitante->Cargar($_SESSION['cedula']);
}


?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Control de Accesos</title>
    <link href="css/estilo.css" rel="stylesheet" />
    <script src="js/jquery.js" type="text/jscript"></script>
    <script src="js/validaciones.js" languaje="javascript" type="text/javascript"></script>
    <script src="js/funciones.js" languaje="javascript" type="text/javascript"></script>
    
</head>

<script>
    this.onShowLogin= function () {
        var login= '<?php print $sesion->estado; ?>';
        if(login)
        {
            // valida el rol del usuario para mostrar el menu, el index o el formulario.
            location.href= 'menuAdmin.php';
        }
    };  
</script>

<body>
    <header>
        <h1>Control de Acceso - Centros de Datos Corporativos</h1>        
        <div id="logo"><img src="img/logoice.png" height="75" onclick="onShowLogin()" > </div>  
        <div id="fechahora"><span id="date"></span></div>
        <div id="signin">
            <span>Usuario: 
                <?php
                if ($sesion->estado) {
                    print $_SESSION['username'];
                } 
                ?>
            </span>
        </div>
    </header>
    
    <div id="mensajetop">
        <span id="textomensaje"></span>
    </div>
    
    <aside></aside>

    <section>
        <div id="form">
            <h2>Cédula / Identificación</h2>
            <form name="datos" id="datos" action="request/EnviaVisitante.php" method="POST">
                <input type="text" autofocus id="cedula" maxlength="20" class="input-field" name="cedula" placeholder="" title="Número de cédula separado con CEROS" onkeypress="return isNumber(event)" />
                <input type="submit" value="Enviar" id="enviar" />
            </form>
        </div>
    </section>

    <aside>
        <div  id="IDsformulario" >
            <!--ID DEL VISITANTE ACEPTADO EN EL FORMULARIO-->
        </div>
        <div id= "mensajespersonales"  >
            <!--MENSAJES DE OPERACIONES-->            
        </div>
        
    </aside>

    <!-- MODAL FORMULARIO -->
    <div class="modal" id="modal-index">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" id="closemodal">&times;</span>
                <h2>Información del Formulario</h2>
                <input readonly  id="idformulario" name="idformulario" class="input-field-readonly" value= "<?php if($formulario!="NULL") print $formulario->id; ?>"  >
                
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <form name="datos" id="datos" action="class/Bitacora.php" method="POST">
                    <div class='modal-izq'>
                        <h3>Cédula</h3>
                        <input type="text" readonly id='modal-cedula' name="cedula" class="input-field" value= "<?php if($formulario!="NULL") echo $_SESSION['cedula']; ?>" >
                        <h3>Nombre Completo</h3>
                        <input type="text" readonly id='nombre' name="nombre" class="input-field" value= "<?php if($formulario!="NULL") echo $visitante->nombre; ?>" >
                        <h3>Empresa/Dependencia</h3>
                        <input type="text" readonly id='empresa' name="empresa" class="input-field" value= "<?php if($formulario!="NULL") echo $visitante->empresa; ?>" >
                        <h3>Placa del Vehículo</h3>
                        <input type="text" readonly id='placavehiculo' name='placavehiculo' class='input-field' value= "<?php if($formulario!="NULL") print $formulario->placavehiculo; ?>" >
                        <h3>Detalle del equipo</h3>
                        <input type="text" readonly id='detalleequipo' name='detalleequipo' class='input-field' value= "<?php if($formulario!="NULL") print $formulario->detalleequipo; ?>" >
                    </div>
                    <div class='modal-der'>
                        <h3>Tarjeta</h3>
                        <input readonly id='idtarjeta' name='idtarjeta' class='input-field-readonly' value= "<?php if($tarjeta!="NULL") { if($tarjeta->id==-1) print 'NO DISPONIBLE'; else print $tarjeta->id; } ?>" >
                        <h3>Autoriza</h3>
                        <input type="text" readonly id='idautorizador' name='idautorizador' class='input-field' value= "<?php if($formulario!="NULL") print $formulario->nombreautorizador; ?>" >
                        <h3>Fecha de la solicitud</h3>
                        <input id='fechasolicitud'readonly name='fechasolicitud' class='input-field' value= "<?php if($formulario!="NULL") print $formulario->fechasolicitud; ?>" >
                    </div>
                    <nav class="btnfrm">
                        <ul>
                            <li><button type="button" value="entrada" id="btncontinuar" >Entrada</button></li>
                            <li><button type="button" value="salida" id="btnsalida" >Salida</button></li>
                            <li><button type="button"  onclick="onCancelar()" >Cancelar</button> </li>
                        </ul>
                    </nav>
                     
                </form> 
            </div>
            
            <div class="modal-footer">
            </div>
        </div>
    </div>                    
                    
</body>

</html>
<script>
    // captura mensajes en línea de estado de formularios temporales.
    CapturaMensajeFormulario();
    // Captura estados del formulario. msg o estado del formulario. Id del formulario
    MensajeriaHtml('<?php print $msg; ?>', '<?php print $idformulario; ?>');  
    

</script>
