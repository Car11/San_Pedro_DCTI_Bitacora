<?php
if (!isset($_SESSION)) {
    session_start();
}
// Sesion de usuario
include("class/sesion.php");
$sesion = new sesion();
//login
include("class/usuario.php");
//GET
$id="";
$nombre="";
$msg="NULL";
if (isset($_GET['id'])) 
    $id=$_GET['id'];
if (isset($_GET['msg'])) 
    $msg=$_GET['msg'];
?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Bitácora de Ingreso DCTI San Pedro</title>
    <link href="css/estilo.css" rel="stylesheet" />
    <script src="js/jquery.js" type="text/jscript"></script>
    <script src="js/validaciones.js" languaje="javascript" type="text/javascript"></script>
    <script src="js/funciones.js" languaje="javascript" type="text/javascript"></script>
    
</head>

<body>
    <header>
        <h1>BITÁCORA DCTI</h1>
        <div id="logo"><img src="img/logoice.png" height="75" onclick="onShowLogin()"> </div>  
        <div id="fechahora"><span id="date"></span></div>
        <div id="signin">
            <span>Usuario: 
                <?php
                    if ($sesion->estadoLogin()==true) {
                        print $_SESSION['username'];
                    } else {
                        print "Seguridad";
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
    CapturaMensajeFormulario();
    MensajeriaHtml('<?php print $msg; ?>');
</script>