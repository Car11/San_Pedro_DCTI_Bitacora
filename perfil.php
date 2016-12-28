<?php   
    $nuevaCedula="";
    if (isset($_GET['id'])) {
        $nuevaCedula=$_GET['id'];
    }
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
    </header>
    <article>
        <div class="contenido">
            <div id="error">Formato de cedula: 9 digitos sin guiones ni espacios</div>           
            <form class="perfil" method="POST" action="EnviaPerfil.php">
                <label for="cedula"><span>Cédula <span class="required">*</span></span>
                    <input type="text" maxlength="9" id="cedula" value= "<?php print $nuevaCedula ?>" class="input-field" name="cedula" placeholder="0-0000-0000" title="Número de cédula separado con CEROS" autofocus  onkeypress="return isNumber(event)"/>
                </label>                
                <label for="empresa"><span>Empresa <span class="required">*</span></span><input type="text" class="input-field" name="empresa" value="" id="empresa"/></label>
                <label for="nombre"><span>Nombre <span class="required">*</span></span><input type="text" class="input-field" name="nombre" value="" id="nombre"/></label>
                <div>
                    <input type="submit" value="Enviar" id="enviarPerfil" />
                </div>
            </form>
        </div>
         <div id="ingreso">Ingreso</div>
    </article>
</body>
</html>


