<?php
if (!isset($_SESSION)) 
    session_start();
include_once('class/Globals.php');
?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Control de Accesos</title>
    <link rel="stylesheet" href="css/Estilo.css?v=<?php echo Globals::cssversion; ?>" />
    <script src="js/jquery.js" type="text/jscript"></script> 
    <script src="js/Webnoc.js" type="text/jscript"></script>                   
    <link href="css/Webnoc.css?v=<?php echo Globals::cssversion; ?>" rel="stylesheet" />

</head>
<body>
    <header>
        <h1>WEB-NOC</h1>        
        <div id="logo"><img src="img/Logoice.png" height="75" > </div>  
        <div id="fechahora"><span id="date"></span></div>        
    </header>

    <aside>        
    </aside>

    <section>
        <div id="superiornavegacion">                  
            <div id="atras">
                <input type="button" id="btnatras" class="nbtn_gray-sp-c" value="Atrás" onclick="location.href='MenuAdmin.php'";>   
            </div>
        </div>
        <h3>Próximas Visitas</h3>
        <div id='proximo'>            
        </div>
        <h3>En Sitio</h3>
        <div id='ensitio'>            
        </div>
        <!-- <h3>Sobretiempo</h3>
        <div id='sobretiempo'>            
        </div>-->
    </section>

    <aside>        
    </aside>
    

</body>

</html>
