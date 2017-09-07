<?php
if (!isset($_SESSION))
  session_start();
include('class/Globals.php');
// Sesion de usuario
include_once("class/Sesion.php");
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
    <title>Menu Administrador</title>
    <link href="css/Estilo.css?v= <?php echo Globals::cssversion; ?>" rel="stylesheet" />
    <link href="css/dropdownmenu.css" rel="stylesheet"/>
    <script src="js/jquery.js" type="text/jscript"></script>
    <script src="js/Funciones.js" languaje="javascript" type="text/javascript"></script>
</head>
<body>
    <header>
        <h1>MENU ADMINISTRADOR</h1>        
        <div id="logo"><img src="img/Logoice.png" height="75" > </div>
        <div id="fechahora"><span id="date"></span></div>
    </header>
    <div class="log">
        
        <nav class="dropdownmenu">
          <ul>
            <li><a href="ConsultaBitacora.php">Consulta Bitacora</a></li>

            <li><a href="#">Mantenimiento</a>
              <ul id="submenu">
                <li><a href="ListaFormulario.php">Formulario</a></li>
                  <li><a href="ResponsableMantenimiento.php">Responsables</a></li>
                <li><a href="PoolTarjeta.php">Pool Tarjetas</a></li>
                <li><a href="ListaVisitantes.php">Visitantes</a></li>
                <li><a href="">Salas</a></li>
              </ul>
            </li>
          </ul>
                
        </nav>
    </div>
    
</body>
</html>







