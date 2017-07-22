<?php
    if (!isset($_SESSION))
		session_start();        
    // Instancia de la clase
    require_once("class/Visitante.php");    
    $visitante= new Visitante();
    $id="";
    if (isset($_GET['MOD'])) {
        $id=$_GET['MOD'];
        $visitante->Cargar($id);
    } 
    
?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Control de Acceso</title>
    <!-- CSS -->
    <link href="css/estilo.css" rel="stylesheet"/>        
    <link href="skins/minimal/red.css" rel="stylesheet">

    <!-- JS  -->    
    <script src="js/jquery.js"></script>
    <script src="js/validaciones.js" languaje="javascript" type="text/javascript"></script>
    <script src="js/funciones-Visitante.js" languaje="javascript" type="text/javascript"></script>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="./jquery.icheck.min.js"></script>

    <script>
        
    </script>
</head>

<body>
    <header>
        <h1>Control de Acceso - Centros de Datos Corporativos</h1>        
        <div id="logo"><img src="img/logoice.png" height="75"> </div>
        <div id="fechahora"><span id="date"></span></div>
    </header>
    
    <aside>
    </aside>

    <section>
        
    </section>

    <aside>
    </aside>

</body>
</html>

<script>
    
$(document).ready(function(){
  $('input').iCheck({
    checkboxClass: 'icheckbox_minimal',
    radioClass: 'iradio_minimal',
    increaseArea: '20%' // optional
  });
});
</script>
