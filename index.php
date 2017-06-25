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
$idformulario="";
$nombre="";
$msg="NULL";
if (isset($_GET['idformulario'])) { 
    $idformulario=$_GET['idformulario'];
}
if (isset($_GET['msg'])) {
    $msg=$_GET['msg'];
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
            location.href= 'index.php';
        }
        //else{
            //muestra login
        //    location.href= 'login.php';
        //}
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

</body>

</html>
<script>
    // captura mensajes en línea de estado de formularios temporales.
    CapturaMensajeFormulario();
    // Captura estados del formulario. msg o estado del formulario. Id del formulario
    MensajeriaHtml('<?php print $msg; ?>', '<?php print $idformulario; ?>');
</script>
