<?php   
    if (!isset($_SESSION))
		session_start();
    //valida el link
    if (!isset($_SESSION['link'])){
        header('Location: index.php');
        exit; 
    }
    //
    unset($_SESSION['estado']);
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
    <title>Control de Accesos</title>
    <link href="css/estilo.css" rel="stylesheet" />
    <script src="js/jquery.js"></script>
    <script src="js/validaciones.js" languaje="javascript" type="text/javascript"></script>
    <script src="js/funciones.js" languaje="javascript" type="text/javascript"></script>
    <script src="js/jquery-ui.js" type="text/jscript"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script>
        function onVuelve(dir="index.php") {
            location.href = dir; 
        }
    </script>
</head>

<body>
    <header>
        <h1>Control de Acceso - Centros de Datos Corporativos</h1>        
        <div id="logo"><img src="img/logoice.png" height="75"> </div>
        <div id="fechahora"><span id="date"></span></div>
    </header>
    <div id="mensajetop">
        <span id="textomensaje"></span>
    </div>
    <aside>
    </aside>
    <section>   
         <div class="dialog-message" title="Nuevo Perfil">
            <p id="texto-mensaje">
                La identificación <b>"<?php print $id ?>"</b> NO está registrada en el sistema.
            </p>
        </div>

        <div id="form">
            <h1>Nuevo Visitante</h1>
            <form name="perfil" method="POST" action="request/EnviaNuevoPerfil.php">
                <label for="cedula"><span class="campoperfil">Cédula / Identificación <span class="required">*</span></span>
                    <input readonly type="text" id="cedula" value= "<?php print $id ?>" class="input-field" name="cedula" placeholder="0 0000 0000" title="Número de cédula separado con CEROS"  onkeypress="return isNumber(event)"/>
                </label>
                <label for="empresa"><span class="campoperfil">Empresa / Dependencia <span class="required">*</span></span><input type="text"  autofocus style="text-transform:uppercase" class="input-field" name="empresa" value="" id="empresa"/></label>
                <label for="nombre"><span class="campoperfil">Nombre Completo <span class="required">*</span></span><input  type="text" class="input-field" name="nombre" value="" id="nombre"/></label>
                <nav class="btnfrm">
                    <ul>
                        <li><input type="submit" class="nbtn" value="Continuar" id="EnviaNuevoPerfil" class="botonesform" /></li>
                        <li><button type="button" class="nbtn" onclick="onVuelve()" >Volver</button></li>
                    </ul>
                </nav>
            </form>
        </div>        
    </section>

    <aside>
    </aside>

</body>

</html>
<script>
// pregunta es nuevo visitante.
 $( ".dialog-message" ).dialog({
        modal: true,
        closeOnEscape: false,
        open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); },
        buttons: {
            Nuevo: function() {
                $( this ).dialog( "close" );         
                return true;
            },            
            Buscar: function(){
                $( this ).dialog( "close" );
                // llama a ventana para buscar identificaciones por NOMBRE COMPLETO.
                onVuelve("index.php?estado=buscar");
                return false;
            },
            Volver: function() {
                $( this ).dialog( "close" );   
                onVuelve();
                return false;
            }
        }
    });
</script>