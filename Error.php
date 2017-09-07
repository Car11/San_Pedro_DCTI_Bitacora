<?php   
    if (!isset($_SESSION))
        session_start();
    include_once('class/Globals.php');
    $errmsg="";
    if (isset($_SESSION['errmsg'])) {
        $errmsg=$_SESSION['errmsg'];
    } else $errmsg="Error No documentado.";
?>
<html>

<head>
    <meta charset="UTF-8">
    <title>Control de Accesos</title>
    <link href="css/Estilo.css?v=<?php echo Globals::cssversion; ?>" rel="stylesheet" />
    <script src="js/jquery.js"></script>
    <script src="js/Funciones.js" languaje="javascript" type="text/javascript"></script>
    <script>
        function onVuelve() {
            location.href = "index.php";
        }
    </script>
</head>

<body>
    <header>
        <h1>Control de Acceso - Centros de Datos Corporativos</h1>        
        <div id="logo"><img src="img/Logoice.png" height="75"> </div>
        <div id="fechahora"><span id="date"></span></div>
    </header>
    <aside>
    </aside>
    <section>
        <div id="form">
            <h1>ERROR</h1>
            <h3>Detalle del error: <?php print ($errmsg); ?></h3>
            <button type="button" class="nbtn_blue" onclick="onVuelve()">Volver </button>
            <br>
        </div>        
    </section>
    <aside>
    </aside>
</body>

</html>