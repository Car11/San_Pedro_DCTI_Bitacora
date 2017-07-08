<?php 
if (!isset($_SESSION)) 
    session_start();

// Sesion de usuario
include("class/sesion.php");
$sesion = new sesion();
if (!$sesion->estado){
    $_SESSION['url']= explode('/',$_SERVER['REQUEST_URI'])[2];
    header('Location: login.php');
    exit;
}

//RESPONSABLE
include("class/responsable.php");  
$responsable= new Responsable();
$responsables= $responsable->Consulta();

//USER AND ROL
include("class/usuario.php");  
$usuario = new usuario();
$usuario->Cargar();
$user= $_SESSION['username'];
$rol=$_SESSION['rol'];
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Control de Accesos</title>       
    <!-- CSS -->
    <link href="css/estilo.css" rel="stylesheet"/>        
    <link rel="stylesheet" type="text/css" href="css/datatables.css">
    <link href="css/formulario.css" rel="stylesheet"/>
    <!-- JS  -->
    <script src="js/jquery.js" type="text/jscript"></script>
 	<script type="text/javascript" charset="utf8" src="js/datatables.js"></script>
    <script src="js/validaciones.js" languaje="javascript" type="text/javascript"></script> 
</head>
<body> 
    <header>
	<h1>MANTENIMIENTO RESPONSABLES</h1>        
    <div id="logo"><img src="img/logoice.png" height="75" ></div>
	</header>
    <div id="general">
        <form class="cbp-mc-form" method="POST" action="request/EnviaFormulario.php" onSubmit="return EnviaVisitante()">       
        <div id="izquierda">
 
        </div>
        <div id="principal">
            <div id="superiornavegacion">
                <div id="nuevo">   
                </div>
                <div id="atraslista">
                    <input type="button" id="btnatras" class="cbp-mc-submit" value="Atrás"onclick="location.href='MenuAdmin.php'";>   
                </div>
            </div>
            <div id="formresposables">
                    <div id="cajainputrespsuperior">
                    <div id=""></div>
                    </div> 
                    <div id="cajainputresp">
                        <label for="txttramitante" class="labelformat">Nombre</label></br>
                        <input type="text" id="txtnombre" name="txtnombre" placeholder="" class="inputformat" value=""/>
                    </div>                   
                    <div id="cajainputresp">
                        <label for="txtautorizador" class="labelformat">Cedula</label></br>
                        <input type="text" id="txtcedula" name="txtcedula" placeholder="" class="inputformat" value=""/> 
                    </div>
                    <div id="cajainputresp">
                        <label for="txtautorizador" class="labelformat">Empresa</label></br>
                        <input type="text" id="txtempresa" name="txtempresa" placeholder="" class="inputformat" value=""/> 
                    </div>
                    <div id="cajainputresp">
                        <input id="EnviaResponsable" class="cbp-mc-submit" type="submit" value="Enviar Responsable">
                    </div>    
            </div>
            <div id="tablaresponsable">
                <!-- CREA EL TABLE QUE CARGA LOS VISITANTES AL FORMULARIO-->
                <?php
                print "<table id='tblresponsable'>";
                print "<thead>";
                print "<tr>";
                print "<th id='tituloid'>ID</th>";
                print "<th id='titulocedula'>Cedula</th>";
                print "<th id='titulonombre'>Nombre</th>";
                print "<th id='tituloempresa'>Empresa</th>";
                print "<th id='titulomodificar'>Modificar</th>";
                print "<th id='tituloeliminar'>Eliminar</th>";
                print "</tr>";
                print "</thead>";
                print "<tbody>";
                for($i=0; $i<count($responsables); $i++){
                    print "<tr>";
                    print "<td>".$responsables[$i][0]."</td>";
                    print "<td>".$responsables[$i][1]."</td>";
                    print "<td>".$responsables[$i][2]."</td>";
                    print "<td>".$responsables[$i][3]."</td>";
                    print "</tr>";
                }
                print "</tbody>";
                print "</table>"; 
                ?>
            </div>

        </div>
        <div id="derecha">
            
        </div>
    </form>
    </div>    
    
<script>
    $(document).ready( function () {             
        $('#tblresponsable').DataTable();   
    } );
    
    
    
</script>
</body>
</html>