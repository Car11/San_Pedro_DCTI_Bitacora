<?php   
    if (!isset($_SESSION))
		session_start();
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
    <title>Control de Accesos</title>
    <link href="css/estilo.css" rel="stylesheet" />
    <script src="js/jquery.js"></script>
    <script src="js/validaciones.js" languaje="javascript" type="text/javascript"></script>
    <script src="js/funciones.js" languaje="javascript" type="text/javascript"></script>
    <script>
        function onVuelve() {
            "<?php if(isset($_SESSION['estado'])) unset($_SESSION['estado']); ?>";            
            location.href = "index.php";             
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
        <!-- Modal -->
        <div class="modal" id="modal-perfil">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close" id="closemodal">&times;</span>
                    <h2>Nuevo Perfil</h2>                
                </div>        
                <!-- Modal body -->
                <div class="modal-body">
                    <h2>La cédula <b>"<?php print $id ?>"</b> NO ha sido ingresada al sistema.</h2>
                    <br><br>
                    <h2>Nuevo: Agregar un nuevo visitante</h2>
                    <h2>Buscar: Buscar por Nombre al visitante</h2>
                    <form name="perfil-modal" id="perfil-modal" >                    
                        <nav class="btnfrm">
                            <ul>
                                <li><button type="button" class="btn" value="" id="" >Nuevo</button></li>
                                <li><button type="button" class="btn" value="" id="" >Buscar</button></li>
                            </ul>
                        </nav>
                        
                    </form> 
                </div>
            </div>
        </div>                    

        <div id="form">
            <h1>Nuevo Visitante</h1>
            <form name="perfil" method="POST" action="request/EnviaNuevoPerfil.php">
                <label for="cedula"><span class="campoperfil">Cédula / Identificación <span class="required">*</span></span>
                    <input readonly type="text" maxlength="9" id="cedula" value= "<?php print $id ?>" class="input-field" name="cedula" placeholder="0 0000 0000" title="Número de cédula separado con CEROS"  onkeypress="return isNumber(event)"/>
                </label>
                <label for="empresa"><span class="campoperfil">Empresa / Dependencia <span class="required">*</span></span><input type="text"  autofocus style="text-transform:uppercase" class="input-field" name="empresa" value="" id="empresa"/></label>
                <label for="nombre"><span class="campoperfil">Nombre Completo <span class="required">*</span></span><input  type="text" class="input-field" name="nombre" value="" id="nombre"/></label>
                <nav class="btnfrm">
                    <ul>
                        <li><input type="submit" class="btn" value="Continuar" id="EnviaNuevoPerfil" class="botonesform" /></li>
                        <li><button type="button" class="btn" onclick="onVuelve()" >Volver</button></li>
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
//modal = document.getElementById('modal-perfil'); 
//modal.style.display = "block";
//$("#btncontinuar").toggle("fadeIn");
</script>