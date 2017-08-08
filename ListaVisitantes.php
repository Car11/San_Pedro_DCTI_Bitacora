<?php
if (!isset($_SESSION)) {
    session_start();
}
// Sesion de usuario
require_once("class/sesion.php");
$sesion = new sesion();
if (!$sesion->estado) {
    $_SESSION['url']= explode('/', $_SERVER['REQUEST_URI'])[2];
    header('Location: login.php');
    exit;
}
// visitante
require_once("class/Visitante.php");
$visitante= new Visitante();
$data= $visitante->CargarTodos();

?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Control de Acceso</title>
   <!-- CSS -->    
    <link rel="stylesheet" type="text/css" href="css/datatables.css">
    <link href="css/formulario.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/sweetalert2.css">
    <link href="css/estilo.css" type="text/css" rel="stylesheet"/>
    <!-- JS  -->
    <script src="js/jquery.js" type="text/jscript"></script>
    <script type="text/javascript" charset="utf8" src="js/datatables.js"></script>
    <script src="js/sweetalert2.js"></script>
    <script src="js/funciones-Visitante.js" languaje="javascript" type="text/javascript"></script> 
    <script src="js/Validaciones.js" languaje="javascript" type="text/javascript"></script> 
    
</head>

<body> 
    <header>
        <h1>LISTA DE VISITANTES</h1>        
        <div id="logo"><img src="img/logoice.png" height="75" > </div>
    </header>
    <div id="mensajetop">
        <span id="textomensaje"></span>
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
                    <input type="button" id="btnnuevo" class="cbp-mc-submit" value="Nuevo" onclick="Nuevo()";>      
                </div>
                <div id="atraslista">
                    <input type="button" id="btnatras" class="cbp-mc-submit" value="Atrás" onclick="location.href='MenuAdmin.php'";>   
                </div>
            </div>

            <div id="lista">
               <br><br><br>
                <?php
                    print "<table id='tblLista'>";
                    print "<thead>";
                    print "<tr>";
                    print "<th style='display:none;'>ID</th>";
                    print "<th>CEDULA</th>";
                    print "<th>NOMBRE</th>";
                    print "<th>EMPRESA</th>";
                    print "<th>PERMISO ANUAL</th>";
                    print "<th>MODIFICAR</th>";
                    print "<th>ELIMINAR</th>";
                    print "</tr>";
                    print "</thead>";
                    print "<tbody id='tableBody'>";
                    for ($i=0; $i<count($data); $i++) {
                        print "<tr>";
                        print "<td style='display:none;'>".$data[$i][0]."</td>";
                        print "<td>".$data[$i][1]."</td>";
                        print "<td>".$data[$i][2]."</td>";
                        print "<td>".$data[$i][3]."</td>";
                        print "<td>".$data[$i][4]."</td>";
                        print "<td><img id=imgdelete src=img/file_mod.png class=modificar></td>";
                        print "<td><img id=imgdelete src=img/file_delete.png class=eliminar></td>";
                        print "</tr>";
                    }
                    print "</tbody>";
                    print "</table>";
                ?>
            </div>
        </section>

        <aside> 
        </aside>

    <!-- MODAL FORMULARIO -->
    <div class="modal" id="modal-index">
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
                            <input autofocus required type="text"  id="cedula" 
                                value= "<?php if ($visitante->cedula!=null) print $visitante->cedula;  ?>" 
                                class="input-field" name="cedula" placeholder="0 0000 0000" title="Número de cédula separado con CEROS"  onkeypress="return isNumber(event)"/>
                        </label>
                        <label for="empresa"><span class="campoperfil">Empresa / Dependencia <span class="required">*</span></span>
                            <input required type="text"   style="text-transform:uppercase" 
                                value= "<?php if ($visitante->empresa!=null) print $visitante->empresa; ?>" 
                                class="input-field" name="empresa" value="" id="empresa"/>
                        </label>
                        <label for="nombre"><span class="campoperfil">Nombre Completo <span class="required">*</span></span>
                            <input  required type="text" class="input-field" name="nombre" style="text-transform:uppercase" 
                                value= "<?php if ($visitante->nombre!=null) print $visitante->nombre; ?>" id="nombre"/>
                        </label>
                        <label for="permiso"><span class="campoperfil">Tiene permiso de Ingreso Anual?</span>
                            <input type="checkbox" name="permiso" id="permiso" class="input-field" >
                        </label>

                        <nav class="btnfrm">
                            <ul>
                                <li><button type="button" class="nbtn" onclick="Guardar()" >Guardar</button></li>
                                <li><button type="button" class="nbtn" onclick="Cerrar()" >Cerrar</button></li>
                            </ul>
                        </nav>

                    </form>
                    
                </div>
            </div>    
            
            <div class="modal-footer">
            </div>

        </div>
    </div>      
    <!-- FIN MODAL -->

    </div>    
    
    </body>
</html>


