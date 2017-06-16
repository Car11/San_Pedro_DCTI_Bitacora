<?php   
    if (!isset($_SESSION))
		session_start();
    $id="";
    if (isset($_GET['id'])) {
        $id=$_GET['id'];
    } else {
        $_SESSION['errmsg']= "infoVisita.php: No GET ID";
        header('Location: Error.php');
        exit;   
    }  
    //salas
    include("php/sala.php");    
    $sala= new Sala();
    $salas=$sala->Disponibles();
?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Bitácora de Ingreso DCTI San Pedro</title>
    <link href="css/estilo.css" rel="stylesheet" />
    <script src="js/jquery.js"></script>
    <script src="js/validaciones.js" languaje="javascript" type="text/javascript"></script>
    <script src="js/funciones.js" languaje="javascript" type="text/javascript"></script>
    <script>
        function onVuelve() {
            location.href = "inicio.php";
            //
            <?php /*$_SESSION["TYPE"] = "NULL";*/ ?>
        }
    </script>
</head>

<body>
    <header>
        <h1>BITÁCORA DE INGRESO</h1>
        <div id="logo"><img src="img/logoice.png" height="75"> </div>
        <div id="fechahora"><span id="date"></span></div>
    </header>
    <div id="mensajetop">
        <span id="textomensaje"></span>
    </div>
    <aside>
    </aside>
    <section>
        <div id="form">
            <h1>Información de Ingreso</h1>
            <h3>Cédula / Identificación</h3>
            <form name="datos" action="EnviaInfoVisita.php" method="POST">
                <input readonly type="text" id="cedula" class="input-field" name="cedula" value="<?php print $id ?>" title="Número decédula separado con CEROS" onkeypress="return isNumber(event)" />
                <h3>Motivo de la Visita</h3>
                <input type=text autofocus class="textarea-field" id="detalle" name="detalle" placeholder="Descripción  /  Razón  /  #RFC">
                <div class="sala">
                    <input type="text" id="sala" name="sala" placeholder="SELECCIONE LA SALA" class="field" readonly="readonly" />
                    <ul class="list">
                        <?php
                            for($i=0; $i<count($salas); $i++){
                                print('<li>'.$salas[$i][1].'</li>');                            
                            }
                        ?>
                    </ul>
                </div>
                <nav class="btnfrm">
                    <ul>
                        <li><input type="submit" value="Continuar" id="EnviaInfoVisita" class="botonesform" /></li>
                        <li><button type="button" onclick="onVuelve()">Cancelar</button></li>
                    </ul>
                </nav>
            </form>
        </div>
    </section>
    <aside>
    </aside>
</body>
</html>