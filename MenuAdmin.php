<?php
  if (!isset($_SESSION))
    session_start();
  // Sesion de usuario
  include("class/sesion.php");
  $sesion = new sesion();
  if (!$sesion->estadoLogin())
  {
    header('Location: login.php');
    exit;
  }
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Menu Administrador</title>
    <link href="css/estilo.css" rel="stylesheet"/>
    <link href="css/dropdownmenu.css" rel="stylesheet"/>
    <script src="js/jquery.js" type="text/jscript"></script>
    <script src="js/funciones.js" languaje="javascript" type="text/javascript"></script>
</head>
<body>
    <header>
        <h1>MENU ADMINISTRADOR</h1>        
        <div id="logo"><img src="img/logoice.png" height="75" > </div>
        <div id="fechahora"><span id="date"></span></div>
    </header>
    
    
    <div class="log">
        
        <nav class="dropdownmenu">
          <ul>
            <li><a href="ConsultaBitacora.php">Consulta Bitacora</a></li>

            <li><a href="#">Mantenimiento</a>
              <ul id="submenu">
                <li><a href="FormularioIngreso.php">Formulario Ingreso</a></li>
                  <li><a href="">Responsables</a></li>
                <li><a href="">Pool Tarjetas</a></li>
                <li><a href="">Visitantes</a></li>
                <li><a href="">Salas</a></li>
              </ul>
            </li>
          </ul>
                
        </nav>
    </div>
    


</body>
</html>







