<?php
if (!isset($_SESSION)) {
    session_start();
}
// Sesion de usuario
require_once("class/Sesion.php");
$sesion = new Sesion();
if (!$sesion->estado) {
    $_SESSION['url']= explode('/', $_SERVER['REQUEST_URI'])[2];
    header('Location: Login.php');
    exit;
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Control de Acceso</title>
   <!-- CSS -->    
    
    <link rel="stylesheet" type="text/css" href="css/datatables.css">
    <!--<link rel="stylesheet" href="css/formulario.css"> -->
    <link href="css/Estilo.css?version=2.0" rel="stylesheet" />
    <link href="css/Estilo.css" type="text/css" rel="stylesheet"/>
    
    <!-- JS  -->
    <script src="js/jquery.js" type="text/jscript"></script>
    <script type="text/javascript" charset="utf8" src="js/datatables.js"></script>
    <script src="js/sweetalert2.js"></script>
    <script src="js/FuncionesVisitante.js" languaje="javascript" type="text/javascript"></script> 
    <script src="js/Validaciones.js" languaje="javascript" type="text/javascript"></script> 
    
</head>

<body> 
    <header>
        <h1>LISTA DE VISITANTES</h1>        
        <div id="logo"><img src="img/Logoice.png" height="75" > </div>
        <div id="signin">
            <span>Usuario: 
                <?php
                if ($sesion->estado) {
                    print $_SESSION['username'];
                } 
                ?>
            </span>
        </div>
    </header>
    <div id="mensajetop_display">
        <div id="mensajetop">
            <span id="textomensaje"></span>
        </div>
    </div>

    <div id="general">
        <aside> 
        </aside>

        <section>
            <div class="dialog-message" title="Visitante">
                <p id="texto-mensaje">
                    Desea Eliminar el Perfil?
                </p>
            </div>
            <div id="superiornavegacion">
                <div id="nuevo">
                    <input type="button" id="btnnuevo" class="nbtn_blue-sp-c" value="Nuevo" onclick="Nuevo()";>      
                </div>                
                <div id="atras">
                    <input type="button" id="btnatras" class="nbtn_gray-sp-c" value="Atrás" onclick="location.href='MenuAdmin.php'";>   
                </div>
            </div>

            <div id="lista">
            </div>
        </section>

        <aside> 
        </aside>

    <!-- MODAL formulario -->
    <div class="modal" id="modal-index" >
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Información del Visitante</h2>                
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <div id="form">
                    <!-- <h1>Nuevo Visitante</h1> -->

                    <form name="perfil" id='perfil' method="POST" >
                        <label for="cedula"><span class="campoperfil">Cédula / Identificación <span class="required">*</span></span>
                            <input autofocus type="text"  id="cedula"                                 
                                class="input-field" name="cedula" placeholder="0 0000 0000" title="Número de cédula separado con CEROS"  onkeypress="return isNumber(event)" required >
                        </label>
                        <label for="empresa"><span class="campoperfil">Empresa / Dependencia <span class="required">*</span></span>
                            <input type="text"   style="text-transform:uppercase"                                 
                                class="input-field" name="empresa" value="" id="empresa" required >
                        </label>
                        <label for="nombre"><span class="campoperfil">Nombre Completo <span class="required">*</span></span>
                            <input  required type="text" class="input-field" name="nombre" style="text-transform:uppercase" id="nombre"/>
                        </label>
                        <label for="permiso"><span class="campoperfil">Tiene permiso de Ingreso Anual?</span>
                            <br>
                            <input type="checkbox" name="permiso" id="permiso" class="input-field" >
                        </label>

                        <nav class="btnfrm">
                            <ul>
                                <li><button type="button" class="nbtn_blue" onclick="Guardar()" >Guardar</button></li>
                                <li><button type="button" class="nbtn_gray" onclick="Cerrar()" >Cerrar</button></li>
                            </ul>
                        </nav>

                    </form>
                    
                </div>
            </div>    
            
            <div class="modal-footer">
                <br>
            </div>

        </div>
    </div>      
    <!-- FIN MODAL -->

    </div>    
    
    </body>
</html>




