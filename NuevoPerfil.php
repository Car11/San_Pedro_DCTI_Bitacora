<?php   
    if (!isset($_SESSION))
		session_start();
    include("class/sesion.php");
    $sesion = new sesion();
    if(!$sesion->estadoLogin()){
        header("location:login.php");
        exit;
    }
    //valida el link
    if (!isset($_SESSION['link'])){
        header('Location: index.php');
        exit; 
    }
    $id="";
    if (isset($_GET['id'])) {
        $id=$_GET['id'];
    } else {        
        header('Location: Error.php');
        exit;  
    }
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
    <div id="mensajetop">
        <span id="textomensaje"></span>
    </div>
    <aside>
    </aside>
    <section>
        <div id="form">
            <h1>Datos del Visitante</h1>
            <form name="perfil" method="POST" action="request/EnviaNuevoPerfil.php">
                <label for="cedula"><span class="campoperfil">Cédula / Identificación <span class="required">*</span></span>
                    <input readonly type="text" maxlength="9" id="cedula" value= "<?php print $id ?>" class="input-field" name="cedula" placeholder="0 0000 0000" title="Número de cédula separado con CEROS"  onkeypress="return isNumber(event)"/>
                </label>
                <label for="empresa"><span class="campoperfil">Empresa / Dependencia <span class="required">*</span></span><input type="text"  autofocus style="text-transform:uppercase" class="input-field" name="empresa" value="" id="empresa"/></label>
                <label for="nombre"><span class="campoperfil">Nombre Completo <span class="required">*</span></span><input  type="text" class="input-field" name="nombre" value="" id="nombre"/></label>
                <nav class="btnfrm">
                    <ul>
                        <li><input type="submit" value="Continuar" id="EnviaNuevoPerfil" class="botonesform" /></li>
                        <li><button type="button" onclick="onVuelve()" >Volver</button></li>
                    </ul>
                </nav>
            </form>
        </div>        
    </section>
    <aside>
    </aside>
</body>

</html>