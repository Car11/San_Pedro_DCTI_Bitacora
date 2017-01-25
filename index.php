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
    </header>
    <article>
        <div class="contenido" >
            <div id="error">Formato de cedula: 9 digitos sin guiones ni espacios</div>
            <h2>Número de cédula</h2>
            <div id="form">
                <!--<form  action="EnviaVisitante.php" method="POST">  
                    <div id="divcedula">     
                        <input type="text" maxlength="9" id="cedula" class="input-field" name="cedula" placeholder="0 0000 0000" title="Número de cédula separado con CEROS"   onkeypress="return isNumber(event)"/>
                    </div>	            	
                    <div id="divdetalle">
                        <h3>Detalle de la Visita</h3>
                        <textarea type="text" class="textarea-field"  id = "detalle" name="detalle" placeholder="Descripción  /  #RFC" ></textarea>	                

                        <input type="submit" value="Enviar" id="enviar" />
                    </div>
                </form> -->   
            </div>
            <div id="mensajes">
                <!--<div id="checkingreso"><img src="img/Check.png" height="50"  alt="logo"></div>
                <div id="salidaDetalle">
                    <img src="img/detalle.png" height="50"  alt="logo"  style="margin:0px"/> 
                </div>    -->
            </div>
        </div>
    </article>
<script>
 	
  
</script>
</body>
</html>







