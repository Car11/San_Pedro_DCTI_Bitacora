<?php
	if (!isset($_SESSION))
		session_start();
	if (!isset($_SESSION['TYPE'])) {
		$_SESSION['TYPE'] = "NULL";
	}
   
    if (!isset($_SESSION['NULLDETALLE'])) {
        $_SESSION['NULLDETALLE']="LISTO";
	}
    //
    $nulldetalle=$_SESSION['NULLDETALLE'];
    $type= $_SESSION['TYPE'];
    $cedula="";
	$detalle="";
    $nombre="";
    if (isset($_GET['id'])) {
        $cedula=$_GET['id'];
    }
	if (isset($_SESSION['DETALLE'])) {
        $detalle= $_SESSION['DETALLE'];
    }
    if (isset($_SESSION['NOMBREVISITANTE'])) {
        $nombre= $_SESSION['NOMBREVISITANTE'];
    }
    //salas
    include("php/sala.php");    
    $sala= new Sala();
    $salas=$sala->Disponibles();
    //login
    include("php/usuario.php");    
    /*for($i=0; $i<count($result); $i++){
        print($i.': '. $result[$i][1]   .'<br>' );
    }
    print ('total de registros: '. count($result));
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
        <h1>BITÁCORA DE ENTRADA & SALIDA</h1>        
        <div id="logo"><img src="img/logoice.png" height="75" > </div>
        <div id="fechahora"><span id="date"></span></div>
    </header>
    <div class="contenido" >
        <div class="login">    
            <form  name="Usuario" action="EnviaUsuario.php" method="POST">  
                <input type="text" id="username" class="input-field" name="username" placeholder="USUARIO" maxlength="20" /><br>
                <input type="password" id="password" class="input-field" name="password" placeholder="CONTRASEÑA" maxlength="20" />
                <input type="submit" value="Ingresar" id="login" />
            </form>      
        </div>
        <div id="mensaje">
            <span id="textomensaje"></span>  
        </div>      
        <div id="form">
            <h2>Cédula / Identificación</h2>
            <form name="datos" action="EnviaVisitante.php" method="POST">  
                <input type="text" maxlength="9" id="cedula" class="input-field" name="cedula" placeholder="0 0000 0000" title="Número decédula separado con CEROS"  onkeypress="return isNumber(event)"/>
                <h3>Motivo de la Visita</h3>
                <input type=text  class="textarea-field"  id = "detalle" name="detalle" placeholder="Descripción  /  Razón  /  #RFC" >                
                <!--<div class="sala">
                    <input type="text" id="sala" name="test" placeholder="SELECCIONE LA SALA" class="field" readonly="readonly" />
                    <ul class="list">
                       <?php
                        /*for($i=0; $i<count($salas); $i++){
                            print('<li>'.$salas[$i][1].'</li>');                            
                        }*/
                        ?>                        
                    </ul>
                </div>-->       	                
                <input type="submit" value="Enviar" id="enviar" />
            </form>
        </div>
        <div id="mensajes">
            <!--<div id="checkingreso"><img src="img/Check.png" height="50"  alt="logo"></div>
            <div id="salidaDetalle"><img src="img/detalle.png" height="50"  alt="logo"/></div>   
        </div>-->
    </div>
<script> 
    var cedula = '<?php print $cedula ?>';
    var type = '<?php print $type ?>';
    var nombre = '<?php print $nombre ?>';
    var nulldetalle = '<?php print $nulldetalle ?>';
    //
    //
    $("#cedula").focus();
    //si el detalle es nulo en entrada debe ser requerido
    if(nulldetalle=="NULL")
    {
        var detalle = '<?php print $detalle ?>';
        document.getElementById("cedula").value=cedula;
        document.getElementById("detalle").value=detalle;
        $("#mensaje").css("background-color", "firebrick"); 
      	$("#textomensaje").text("El campo: Motivo de la visita, es REQUERIDO, 8 caracteres.");
      	$("#mensaje").css("visibility", "visible"); 
    	$("#mensaje" ).slideDown( "slow" );
  		//
        $("#detalle").focus();        
    }
    else if(cedula == "END")
    {
        $("#checkingreso" ).hide();
        $("#salidaDetalle").hide();
        $("#textomensaje").text("GRACIAS POR SU VISITA!");
      	$("#mensaje").css("visibility", "visible"); 
        $("#mensaje").css("background-color", "60E800"); 
        $("#mensaje").css("color", "white"); 
    	$( "#mensaje" ).slideDown( "slow" ).delay(3000).slideUp("slow");
    } else if( cedula== "REGISTRO")    //REGISTRO DE NUEVO VISITANTE CORRECTO!
    {
        $("#checkingreso" ).hide();
        $("#salidaDetalle").hide();
        $("#textomensaje").text("Visitante Registrado... Bienvenido!");
      	$("#mensaje").css("visibility", "visible"); 
        $("#mensaje").css("background-color", "#60E800"); 
        $("#mensaje").css("color", "white"); 
        $( "#mensaje" ).slideDown( "slow" ).delay(3000).slideUp("slow");
        //
        $("#cedula").focus();
    } 
    else if(type == "IN")
    {   
        $("#textomensaje").text("Bienvenido, " + nombre );
      	$("#mensaje").css("visibility", "visible"); 
        $("#mensaje").css("background-color", "#60E800"); 
        $("#mensaje").css("color", "white"); 
    	$( "#mensaje" ).slideDown( "slow" ).delay(3000).slideUp("slow");
        //
    	$("#salidaDetalle").hide();
    	//mensaje bienvenida
        $( "#checkingreso" ).fadeIn("slow", function(){
             $( "#checkingreso" ).fadeOut(4000);
        });        
    }
    else if(type == "OUT")
    {
    	$( "#checkingreso" ).hide();
        $("#salidaDetalle").show(); 
        // MENSAJE editar detalle Y DAR OTRO CLIC ENVIAR
        $("#textomensaje").text("Edite o agregue el detalle de su visita.");
      	$("#mensaje").css("visibility", "visible"); 
        $("#mensaje").css("background-color", "#ff9800"); 
        $("#mensaje").css("color", "white"); 
    	$( "#mensaje" ).slideDown( "slow" );
        //
        var f = document.getElementById('salidaDetalle');
        setInterval(function() {
            f.style.display = (f.style.display == 'none' ? '' : 'none');
        }, 850); 
        //
        document.getElementById("detalle").value= '<?php  print $detalle  ?>' + '. ';
        document.getElementById("cedula").value=cedula;
        document.getElementById("cedula").readOnly = true;
        $("#detalle").fadeIn(1000);
        $("#detalle").focus();
        //
        $(".size").slideUp("slow");
    }else if(type == "NULL")
    {
    	//Oculta los iconos de mensajes
        $( "#checkingreso" ).hide();
        $("#salidaDetalle").hide();
        $("#mensaje").css("visibility", "hidden"); 
        
    }
    
    
(function($){
	$.fn.styleddropdown = function(){
		return this.each(function(){
			var obj = $(this)
			obj.find('.field').click(function() { //onclick event, 'list' fadein
			obj.find('.list').fadeIn(400);
			
			$(document).keyup(function(event) { //keypress event, fadeout on 'escape'
				if(event.keyCode == 27) {
				obj.find('.list').fadeOut(400);
				}
			});
			
			obj.find('.list').hover(function(){ },
				function(){
					$(this).fadeOut(400);
				});
			});
			
			obj.find('.list li').click(function() { //onclick event, change field value with selected 'list' item and fadeout 'list'
			obj.find('.field')
				.val($(this).html())
				.css({
					'background':'#fff',
					'color':'#333'
				});
			obj.find('.list').fadeOut(400);
			});
		});
	};
})(jQuery);
    
</script>
</body>
</html>







