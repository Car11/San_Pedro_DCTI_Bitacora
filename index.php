<?php
    $ingreso="0";
    /*if (isset($_GET['ins'])) {
        $ingreso=$_GET['ins'];
    }*/
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bitácora de Ingreso DCTI San Pedro</title>
    <link href="css/estilo.css" rel="stylesheet"/>
    <script src="js/jquery.js" type="text/jscript"></script>
    <script src="js/funciones.js" languaje="javascript" type="text/javascript"></script>
<script type="text/javascript">
    var id = '<?php print $ingreso ?>';
    if(id='1')
        {            
            //$("#ingreso").css("visibility", "visible");
            //$("#ingreso").slideUp( 300 ).delay( 800 ).fadeIn( 400 );
            //$("#ingreso").fadeOut(10000,function(){
            //    $(this).css({"display":"block","visibility":"visible"});
            
            $("#ingreso").fadeIn(1000);
            $("#ingreso").fadeOut(1000);
            alert(id);
        }
    else 
        {
            $("#ingreso").fadeOut(1000);
            alert(id);
        }
    
</script>
</head>
<body>
    <header>
        <h1>BITÁCORA DE INGRESO</h1>
    </header>
    <article>
        <div class="contenido" >
            <div id="error">Formato de cedula: 9 digitos sin guiones ni espacios</div>
            <h2>Número de cédula</h2>
            <form  action="EnviaVisitante.php" method="POST">
                <input type="text" maxlength="9" id="cedula" class="input-field" name="cedula" placeholder="0-0000-0000" title="Número de cédula separado con CEROS" autofocus  onkeypress="return isNumber(event)"/>
                <div class="detalle">
                    <h3>Detalle</h3>
                    <textarea type="text" class="textarea-field" name="detalle" title="Detalle del trabajo realizado"> </textarea>
                </div>
                <div>
                    <input type="submit" value="Enviar" id="enviar" />
                </div>
            </form>
        </div>
         <div id="ingreso"><img src="img/Check.png" height="50"  alt="logo" /> </div>
    </article>
</body>
</html>







