<?php 
if (!isset($_SESSION)) 
    session_start();
// Sesion de usuario
require_once("class/sesion.php");
$sesion = new sesion();
if (!$sesion->estado){
    $_SESSION['url']= explode('/',$_SERVER['REQUEST_URI'])[2];
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
    <link href="css/estilo.css" type="text/css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="css/datatables.css">
    <link href="css/formulario.css" rel="stylesheet"/>
    <!-- JS  -->
    <script src="js/jquery.js" type="text/jscript"></script>
    <script type="text/javascript" charset="utf8" src="js/datatables.js"></script>
    <script src="js/funciones-Visitante.js" languaje="javascript" type="text/javascript"></script> 
</head>

<body> 
    <header>
        <h1>LISTA DE VISITANTES</h1>        
        <div id="logo"><img src="img/logoice.png" height="75" > </div>
	</header>

    <div id="general">
        <aside> 
        </aside>

        <section>
           <div id="superiornavegacion">
                <div id="nuevo">
                    <input type="button" id="btnnuevo" class="cbp-mc-submit" value="Nuevo" onclick="location.href='Visitante.php'";>      
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
                    print "<th>CEDULA</th>";
                    print "<th>NOMBRE</th>";
                    print "<th>EMPRESA</th>";
                    print "<th>PERMISO ANUAL</th>";
                    print "<th>MODIFICAR</th>";    
                    print "</tr>";
                    print "</thead>";	
                    print "<tbody>";
                    for($i=0; $i<count($data); $i++){
                        print "<tr>";
                            print "<td>".$data[$i][0]."</td>";
                            print "<td>".$data[$i][1]."</td>";
                            print "<td>".$data[$i][2]."</td>";
                            print "<td>".$data[$i][3]."</td>";
                            print "<td><img id=imgdelete src=img/file_mod.png class=modificar></td>";
                        print "</tr>";
                    }
                    print "</tbody>";
                    print "</table>";
                ?>
            </div>
        </section>

        <aside> 
        </aside>

    </div>    
    
    </body>
</html>


