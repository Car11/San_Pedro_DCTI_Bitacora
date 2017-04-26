<?php
	if (!isset($_SESSION))
		session_start();
	if (!isset($_SESSION['TYPE'])) {
		$_SESSION['TYPE'] = "NULL";
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
            <li><a href="http://www.jochaho.com/wordpress/">Consulta Bitacora</a></li>

            <li><a href="#">Mantenimiento</a>
              <ul id="submenu">
                <li><a href="http://www.jochaho.com/wordpress/difference-between-svg-vs-canvas/">Responsables</a></li>
                <li><a href="http://www.jochaho.com/wordpress/new-features-in-html5/">Pool Tarjetas</a></li>
                <li><a href="http://www.jochaho.com/wordpress/creating-links-to-sections-within-a-webpage/">Visitantes</a></li>
                <li><a href="http://www.jochaho.com/wordpress/creating-links-to-sections-within-a-webpage/">Salas</a></li>
              </ul>
            </li>
          </ul>
                
        </nav>
    </div>
    


</body>
</html>







