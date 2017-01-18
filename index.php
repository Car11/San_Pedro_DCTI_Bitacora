<?php
	if (!isset($_SESSION))
		session_start();
	if (!isset($_SESSION['TYPE'])) {
		$_SESSION['TYPE'] = "NULL";
	}
	$type= $_SESSION['TYPE'];
    $cedula="";
	$detalle="";
    if (isset($_GET['id'])) {
        $cedula=$_GET['id'];
    }
	if (isset($_SESSION['DETALLE'])) {
        $detalle= $_SESSION['DETALLE'];
    }
	/*print $type."<br>";
	print $cedula."<br>";
	print $detalle."<br>";*/
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
    var cedula = '<?php print $cedula ?>';
    var type = '<?php print $type ?>';
    $("#cedula").focus();
    //
    if(type == "NULL")
    {
    	//Oculta los iconos de mensajes
        $( "#ingreso" ).hide();
        $("#salidaDetalle").hide();
    }
    else if(type == "IN")
    {    	
    	$("#salidaDetalle").hide();
    	//mensaje bienvenida
        $( "#ingreso" ).fadeIn("slow", function(){
             $( "#ingreso" ).fadeOut(4000);
        });        
    }
    else if(type == "OUT")
    {
    	$( "#ingreso" ).hide();
        $("#salidaDetalle").show(); // MENSAJE editar detalle Y DAR OTRO CLIC ENVIAR
        var f = document.getElementById('salidaDetalle');
        setInterval(function() {
            f.style.display = (f.style.display == 'none' ? '' : 'none');
        }, 850); 
        //
        document.getElementById("detalle").value= '<?php  print $detalle ?>';
        document.getElementById("cedula").value=cedula;
        //document.getElementById("cedula").readOnly = true;
        $("#detalle").fadeIn(1000);
        $("#detalle").focus();
    }
</script>
</body>
</html>







