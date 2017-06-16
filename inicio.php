<?php
if (!isset($_SESSION)) {
    session_start();
}
//
include("php/sesion.php");
$sesion = new sesion();
//
$cedula="";
//$detalle="";
//$nombre="";
if (isset($_GET['id'])) {
    $cedula=$_GET['id'];
}
    /*if (isset($_SESSION['DETALLE'])) {
        $detalle= $_SESSION['DETALLE'];
    }
    if (isset($_SESSION['NOMBREVISITANTE'])) {
        $nombre= $_SESSION['NOMBREVISITANTE'];
    }*/
    //salas
    include("php/sala.php");
    $sala= new Sala();
    $salas=$sala->Disponibles();
    //login
    include("php/usuario.php");
?><html>

<head>
    <meta charset="UTF-8">
    <title>Bitácora de Ingreso DCTI San Pedro</title>
    <link href="css/estilo.css" rel="stylesheet" />
    <script src="js/jquery.js" type="text/jscript"></script>
    <script src="js/validaciones.js" languaje="javascript" type="text/javascript"></script>
    <script src="js/funciones.js" languaje="javascript" type="text/javascript"></script>
    <script>
        CapturaMensajeFormulario();
    </script>
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
            <form name="datos" id="datos" action="EnviaVisitante.php" method="POST">
                <input type="text" autofocus id="cedula" maxlength="20" class="input-field" name="cedula" placeholder="" title="Número de cédula separado con CEROS" onkeypress="return isNumber(event)" />
                <input type="submit" value="Enviar" id="enviar" />
            </form>
        </div>
    </section>

    <aside>
        <div id="avisoFormulario" name="avisoFormulario">
            <!--ID DEL VISITANTE ACEPTADO EN EL FORMULARIO-->
        </div>
    </aside>

</body>

</html>