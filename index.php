<?php
if (!isset($_SESSION)) 
    session_start();
// Sesion de usuario
include("class/Sesion.php");
$sesion = new Sesion();
if (!$sesion->estado){
    $_SESSION['url']= explode('/',$_SERVER['REQUEST_URI'])[2];
    header('Location: Login.php');
    exit;
}
// POST
$estado="NULL";
if (isset($_GET['estado']))
{
    // abre modal de busqueda de visitantes.
    $estado= $_GET['estado'];
    unset($_SESSION['estado']);       
}
if (isset($_SESSION['estado'])) {
    $estado=$_SESSION['estado'];
    // elimina el estado para posteriores re-envios de la pagina (F5).
    unset($_SESSION['estado']);       
}
else {
    unset($_SESSION['idformulario']);
    unset($_SESSION['idvisitante']);
    unset($_SESSION['link']);
    unset($_SESSION['bitacora']);
}
// Inicia Busqueda de visitante por nombre Completo.
$visitantes=[]; // arreglo de visitantes.
$visitante="NULL";
$formulario="NULL";
$tarjeta="NULL";
if ($estado=="buscar"){
    require_once("class/Visitante.php");
    $visitante= new Visitante();
    $visitantes= $visitante->CargarTodos();
}
// Busca información del formulario para desplegar en pantalla.
if (isset($_SESSION['idformulario'])) {     
    // Carga info del formulario.
    include("class/Formulario.php");
    $formulario= new Formulario();
    $formulario->id=$_SESSION['idformulario'];
    $formulario->CargarID();    
    // Carga info tarjeta
    include("class/Tarjeta.php");
    $tarjeta= new tarjeta();
    if($estado!="fin"){
        // Carga tarjeta asiganada si es un ingreso, no salida 
        // $tarjeta->nombresala= $formulario->nombresala;
        $tarjeta->idsala= $formulario->idsala;
        $tarjeta->Asignar();
    }
    else {
        // Carga la tarjeta asigana al visitante. (fin) 
        $tarjeta->CargaTarjetaAsignada($_SESSION['idvisitante'] , $formulario->id);
    }
    // Carga Info VISITANTE
    require_once("class/Visitante.php");
    $visitante= new Visitante();
    $visitante->ID= $_SESSION['idvisitante'];
    $visitante->CargarID($_SESSION['idvisitante']);
}

?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Control de Accesos</title>
    
    <script src="js/jquery.js" type="text/jscript"></script>
    <script src="js/jquery-ui.js" type="text/jscript"></script>
    <script type="text/javascript" charset="utf8" src="js/datatables.js"></script>
    

    <script src="js/Validaciones.js" languaje="javascript" type="text/javascript"></script>
    <script src="js/Funciones.js" languaje="javascript" type="text/javascript"></script>
    
    <link href="css/Estilo.css" rel="stylesheet" />
    <link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"  rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/datatables.css">

</head>

<script>
    this.onShowLogin= function () {
        var login= '<?php print $sesion->estado; ?>';
        if(login)
        {
            // valida el rol del usuario para mostrar el menu, el index o el formulario.
            location.href= 'MenuAdmin.php';
        }
    };  
</script>

<body oncopy="return false" oncut="return false" onpaste="return false">
    <header>
        <h1>Control de Acceso - Centros de Datos Corporativos</h1>        
        <div id="logo"><img src="img/Logoice.png" height="75" onclick="onShowLogin()" > </div>  
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
    
    <aside>
        
    </aside>

    <section>
        <div class="dialog-message" title="Tarjeta">
            <p id="texto-mensaje">
                Está realizando una salida de tarjeta?
            </p>
        </div>

        <div id="form">
            <h2>Cédula / Identificación</h2>
            <form name="datos" id="datos" action="request/EnviaVisitante.php" method="POST">
                <input type="text" autofocus id="cedula" maxlength="20" class="input-field" name="cedula" placeholder="" title="Número de cédula separado con CEROS" onkeypress="return isNumber(event)" />
                <input type="submit" class="nbtn_blue" value="Consultar" id="enviar" />
            </form>
        </div>
    </section>

    <aside>
        <div  id="IDsformulario" >
            <!--ID DEL VISITANTE ACEPTADO EN EL formulario-->
        </div>
        <div id= "mensajespersonales"  >
            <!--MENSAJES DE OPERACIONES-->            
        </div>
        
    </aside>

    <!-- MODAL formulario -->
    <div class="modal" id="modal-index">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Información del Formulario</h2>
                <input readonly  id="consecutivo" name="consecutivo" class="input-field-readonly" value= "<?php if($formulario!="NULL") print $formulario->consecutivo; ?>"  >
                <input  type="hidden"  id="idformulario" name="idformulario" class="input-field-readonly" value= "<?php if($formulario!="NULL") print $formulario->id; ?>"  >
                <input  type="hidden"  id="idvisitante" name="idvisitante" class="input-field-readonly" value= "<?php if($visitante!="NULL") print $visitante->ID; ?>"  >
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <form name="datos-modal" id="datos-modal" action="class/Bitacora.php" method="POST">
                    <div class='modal-izq'>
                        <h3>Cédula</h3>
                        <input type="text" readonly id='modal-cedula' name="modal-cedula" class="input-field" value= "<?php if($formulario!="NULL") echo $visitante->cedula; ?>" >
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
                        <input readonly id='numtarjeta' name='numtarjeta' class='input-field-readonly' value= "<?php if($tarjeta!="NULL") { if($tarjeta->id==-1) print 'NO DISPONIBLE'; else print $tarjeta->consecutivo; } ?>" >
                        <input type="hidden" id='idtarjeta' name='idtarjeta' class='input-field-readonly' value= "<?php if($tarjeta!="NULL") { if($tarjeta->id==-1) print 'NO DISPONIBLE'; else print $tarjeta->id; } ?>" >
                        <h3>Autoriza</h3>
                        <input type="text" readonly id='idautorizador' name='idautorizador' class='input-field' value= "<?php if($formulario!="NULL") print $formulario->nombreautorizador; ?>" >
                        <h3>Fecha de la solicitud</h3>
                        <input id='fechasolicitud'readonly name='fechasolicitud' class='input-field' value= "<?php if($formulario!="NULL") print $formulario->fechasolicitud; ?>" >
                    </div>
                    <nav class="btnfrm">
                        <ul>
                            <li><button type="button" class="nbtn_blue" value="entrada" id="btncontinuar" >Entrada</button></li>
                            <li><button type="button" class="nbtn_red" value="salida" id="btnsalida" >Salida</button></li>
                            <!--<li><button type="button"  onclick="onCancelar()" id="btnvolver" >Volver</button> </li>-->
                        </ul>
                    </nav>
                     
                </form> 
            </div>
            
            <div class="modal-footer">
            <br>
            </div>
        </div>
    </div>      
    <!-- FIN MODAL -->

     <!-- MODAL VISITANTE -->
    <div id="Modal-Visitante" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" >&times;</span>
                <h2>Búsqueda de Visitantes</h2>
            </div>
            <div class="modal-body">
                <!-- CREA EL TABLE DEL MODAL PARA SELECIONAR VISITANTES -->
                <?php 
                print "<table id='tblvisitante-buscar' class='display'>";
                print "<thead>";
                print "<tr>";
                print "<th>Cedula</th>";
                print "<th>Nombre</th>";
                print "<th>Empresa</th>";
                print "</tr>";
                print "</thead>";	
                print "<tbody>";
                for($i=0; $i<count($visitantes); $i++){
                        print "<tr>";
                        print "<td>".$visitantes[$i][1]."</td>";
                        print "<td>".$visitantes[$i][2]."</td>";
                        print "<td>".$visitantes[$i][3]."</td>";
                        print "</tr>";
                }
                print "</tbody>";
                print "</table>";
                ?> 
            </div>
            <div class="modal-footer">
            <br>
            </div>
        </div>
    </div>
    <!--FINAL MODAL VISITANTE-->


</body>

</html>
<script>
    $( document ).ready(function() {
        // captura mensajes en línea de estado de formularios temporales.
        CapturaMensajeFormulario();
        // Captura estados del formulario. estado del formulario. Id del formulario
        MensajeriaHtml('<?php print $estado; ?>', '<?php if($formulario!="NULL") print $formulario->consecutivo; else print "NULL" ?>');  
    });
    
</script>
