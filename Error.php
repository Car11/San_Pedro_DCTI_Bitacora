<?php   
    if (!isset($_SESSION))
		session_start();
    $errmsg="";
    if (isset($_SESSION['errmsg'])) {
        $errmsg=$_SESSION['errmsg'];
    } else $errmsg="Error No documentado.";
?>
<html>

<head>
    <meta charset="UTF-8">
    <title>Bitácora de Ingreso DCTI San Pedro</title>
    <link href="css/estilo.css" rel="stylesheet" />
    <script src="js/jquery.js"></script>
    <script src="js/funciones.js" languaje="javascript" type="text/javascript"></script>
    <script>
        function onVuelve() {
            location.href = "index.php";
        }
    </script>
</head>

<body>
    <header>
        <h1>BITÁCORA DE INGRESO</h1>
        <div id="logo"><img src="img/logoice.png" height="75"> </div>
        <div id="fechahora"><span id="date"></span></div>
    </header>
    <aside>
    </aside>
    <section>
        <h1>ERROR</h1>
        <h3>Detalle del error: <?php print ($errmsg); ?></h3>
        <button type="button" onclick="onVuelve()">Volver </button>
    </section>
    <aside>
    </aside>
</body>

</html>