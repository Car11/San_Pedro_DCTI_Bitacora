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
	print $detalle."<br>";
	exit;*/
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
        <div id="login">Admin</div>
    </header>
        <div class="contenido" >
            <div id="error">Formato de cedula: 9 digitos sin guiones ni espacios</div>           
            <div id="form">
                <h2>Número de cédula</h2>
                <form  action="EnviaVisitante.php" method="POST">  
                    <input type="text" maxlength="9" id="cedula" class="input-field" name="cedula" placeholder="0 0000 0000" title="Número de cédula separado con CEROS"   onkeypress="return isNumber(event)"/>
                    <h3>Detalle de la Visita</h3>
                    <textarea type="text" class="textarea-field"  id = "detalle" name="detalle" placeholder="Descripción  /  #RFC" ></textarea>	                
                    <input type="submit" value="Enviar" id="enviar" />
                </form>
            </div>
            <div id="mensajes">
                <div id="checkingreso"><img src="img/Check.png" height="50"  alt="logo"></div>
                <div id="salidaDetalle"><img src="img/detalle.png" height="50"  alt="logo"/></div>   
            </div>
        </div>    
<script> 	
    var cedula = '<?php print $cedula; ?>';
    var type = '<?php print $type; ?>'; 		
    //
    $("#cedula").focus();
    //
   if(type == "NULL")
    {
    	//Oculta los iconos de mensajes
        $( "#checkingreso" ).hide();
        $("#salidaDetalle").hide();
    }
    else if(type == "IN")
    {    	
    	$("#salidaDetalle").hide();
    	//mensaje bienvenida
        $( "#checkingreso" ).fadeIn("slow", function(){
             $( "#checkingreso" ).fadeOut(4000);
        });        
    }
    else if(type == "OUT")
    {
    	$( "#checkingreso" ).hide();
        $("#salidaDetalle").show(); // MENSAJE editar detalle Y DAR OTRO CLIC ENVIAR
        var f = document.getElementById('salidaDetalle');
        setInterval(function() {
            f.style.display = (f.style.display == 'none' ? '' : 'none');
        }, 850); 
        var tDetalle= '<?php print json_encode($detalle); ?>';
        tDetalle= tDetalle.replace(/"/g,'');
        document.getElementById("detalle").value= tDetalle;
        document.getElementById("cedula").value=cedula;
        document.getElementById("cedula").readOnly = true;
        $("#detalle").fadeIn(1000);
        $("#detalle").focus();
    }
</script>
</body>
</html>







