<?php
    $ingreso="0";
    if (isset($_GET['ins'])) {
        $ingreso=$_GET['ins'];
    }
    $cedula="";
    if (isset($_GET['id'])) {
        $cedula=$_GET['id'];
    }
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bitácora de Ingreso DCTI San Pedro</title>
    <link href="css/estilo.css" rel="stylesheet"/>
    <script src="js/jquery.js" type="text/jscript"></script>
    <script src="js/funciones.js" languaje="javascript" type="text/javascript"></script>
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
                <input type="text" maxlength="9" id="cedula" class="input-field" name="cedula" placeholder="0 0000 0000" title="Número de cédula separado con CEROS"   onkeypress="return isNumber(event)"/>
                <div class="detalle">
                    <h3>Detalle de la Visita</h3>
                    <textarea type="text" class="textarea-field"  id = "detalle" name="detalle" placeholder="Descripción  /  #RFC" ></textarea>
                </div>
                <div>
                    <input type="submit" value="Enviar" id="enviar" />
                </div>
            </form>
        </div>
         <div id="ingreso"><img src="img/Check.png" height="50"  alt="logo" /> </div>
        <div id="salidaDetalle"><img src="img/detalle.png" height="50"  alt="logo" /> </div>
    </article>
<script>
    var id = '<?php print $ingreso ?>';
    var cedula = '<?php print $cedula ?>';
    $("#cedula").focus();
    //alert(id);
    if(id=="0"){
        $( "#ingreso" ).hide();
        $("#salidaDetalle").hide();
        //$(".detalle").hide();        
    }
    else if (id=="1"){
        //$(".detalle").hide();
        $("#salidaDetalle").hide();
        $( "#ingreso" ).fadeIn("slow", function(){
             $( "#ingreso" ).fadeOut(4000);
        });
    }
    else {
        //alert(id);
        $( "#ingreso" ).hide();
        $("#salidaDetalle").show();
        var f = document.getElementById('salidaDetalle');
        setInterval(function() {
            f.style.display = (f.style.display == 'none' ? '' : 'none');
        }, 850); 
        //
        document.getElementById("detalle").value="";
        document.getElementById("cedula").value=cedula;
        document.getElementById("cedula").readOnly = true;
        $("#detalle").fadeIn(1000);
        $("#detalle").focus();
    }
</script>
</body>
</html>







