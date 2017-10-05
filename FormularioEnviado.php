<?php 
if (!isset($_SESSION)) 
    session_start();
include_once('class/Globals.php');
if (isset($_GET['Message'])) {
    print '<script type="text/javascript">alert("' . $_GET['Message'] . '");</script>';
}
// Sesion de usuario
include("class/Sesion.php");
$sesion = new Sesion();
if (!$sesion->estado){
    $_SESSION['url']= explode('/',$_SERVER['REQUEST_URI'])[2];
    header('Location: Login.php');
    exit;
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Control de Acceso</title>
    <!-- CSS -->
    <link href="css/Estilo.css?v=<?php echo Globals::cssversion; ?>" rel="stylesheet" />
    <link href="css/Formulario.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="css/datatables.css">
    <!-- JS  -->
    <script src="js/jquery.js" type="text/jscript"></script>
    <script type="text/javascript" charset="utf8" src="js/datatables.js"></script>
    <script src="js/Validaciones.js" languaje="javascript" type="text/javascript"></script> 
</head>
<body onload="CargarEstiloTablas();"> 
    <header>
	<h1>CENTRO DE DATOS CORPORATIVO ICE</h1>        
    <div id="logo"><img src="img/Logoice.png" height="75" > </div>
    </header>
    <body>
        <div id="mensajetop_display">
            <div id="mensajetop">
                <span id="textomensaje"></span>
            </div>
        </div>
        <div id="general">
            <div id="izquierda">
                
                
            </div>
            <div id="principal">
                <div id="superiornavegacion">
                    <div id="supnuevo">
                        
                    </div>
                    <div id="supbusca">
                        <div id="izq_busqueda"></div>
                        <div id="cen_busqueda">
                            
                        </div>
                        <div id="der_busqueda"></div>
                    </div>
                    <div id="supatras">
                        
                    </div>
                </div>
                <div id="listavisitante">
                    <h1>FORMULARIO ENVIADO</h1>        
                    <h2>SERÁ NOTIFICADO VÍA CORREO DE SU APROBACIÓN</h2>
                    <h2>Muchas Gracias!!!</h2>
                </div>
                    <footer></footer>  
            </div>
            <div id="derecha">
    
            </div>
        </div>
    </body>
    <script>
    </script>
</html>    