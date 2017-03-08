<?php   
    $nuevaCedula="";
    $detalle="";
    if (isset($_GET['id'])) {
        $nuevaCedula=$_GET['id'];
    }
    if (!isset($_SESSION))
		session_start();
    if (isset($_SESSION['DETALLE'])) {
        $detalle= $_SESSION['DETALLE'];
    }
    $_SESSION['NUEVOVISITANTE']="SI";
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bitácora de Ingreso DCTI San Pedro</title>
    <link href="css/estilo.css" rel="stylesheet"/>
    <script src="js/jquery.js"></script>
    <script src="js/funciones.js" languaje="javascript" type="text/javascript"></script>
</head>
<body>
    <header>
        <h1>BITÁCORA DE INGRESO</h1>  
        <div id="logo"><img src="img/logoice.png" height="75" > </div>
        <div id="fechahora"><span id="date"></span></div>      
    </header>
        <div class="contenido">
            <div id="mensaje">
                <span id="textomensaje"></span>  
            </div>   
            <div id="PerfilHeader">
            	<h1>Formulario de Nuevo Visitante</h1>
            	<!--<h2><i>Por favor ingrese sus datos</i></h2>-->
            </div>              
            <form class="perfil" method="POST" action="EnviaPerfil.php">
                <label for="cedula"><span class="campoperfil">Cédula <span class="required">*</span></span>
                    <input type="text" maxlength="9" id="cedula" value= "<?php print $nuevaCedula ?>" class="input-field" name="cedula" placeholder="0-0000-0000" title="Número de cédula separado con CEROS" autofocus  onkeypress="return isNumber(event)"/>
                </label>                
                <label for="empresa"><span class="campoperfil">Empresa <span class="required">*</span></span><input type="text" class="input-field" name="empresa" value="" id="empresa"/></label>
                <label for="nombre"><span class="campoperfil">Nombre<span class="required">*</span></span><input type="text" class="input-field" name="nombre" value="" id="nombre"/></label>
                <div>
                    <input type="submit" value="Enviar" id="enviarPerfil" />
                </div>
            </form>
        </div>
</body>
</html>


