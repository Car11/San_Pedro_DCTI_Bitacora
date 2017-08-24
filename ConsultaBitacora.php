<?php 
if (!isset($_SESSION))
    session_start();
//
// Sesion de usuario
include("class/Sesion.php");
$sesion = new Sesion();
if (!$sesion->estado){
	$_SESSION['url']= explode('/',$_SERVER['REQUEST_URI'])[2];
    header('Location: Login.php');
    exit;
}
//
include("class/bitacora.php");
$bitacora= new bitacora();
$listabitacora= $bitacora->Consulta();
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Bitácora de Ingreso DCTI San Pedro</title>
    <!-- CSS -->
    <link rel="stylesheet" href="css/Estilo.css"     type="text/css"/>        
    <link rel="stylesheet" href="css/datatables.css" type="text/css"/>
    <link rel="stylesheet" href="css/Formulario.css" type="text/css"/>
    <link rel="stylesheet" href="css/sweetalert2.css" type="text/css"/>
    <!-- JS  -->
    <script src="js/jquery.js" type="text/jscript"></script>
 	<script src="js/datatables.js" type="text/javascript" charset="utf8"></script>
    <script src="js/Validaciones.js" languaje="javascript" type="text/javascript"></script> 
    <script src="js/sweetalert2.js"></script>

</head>
<body> 
	<header>
	<h1>BITÁCORA</h1>
	    <div id="logo"><img src="img/Logoice.png" height="75" > </div>
	</header>
    <div id="general">
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
            <div id="listavisitante">
               </br>
					<?php 
                    print "<table id='tblbitacora'>";
					print "<thead>";
					print "<tr>";
					print "<th>Formulario</th>";
					print "<th>Cédula</th>";
                    print "<th>Nombre</th>";
					print "<th>Entrada</th>";
					print "<th>Salida</th>";
			        print "<th>Locación</th>";		
                    print "<th>Tarjeta</th>";
                    print "<th>Estado</th>";
                    
					print "</tr>";
					print "</thead>";	
					
					print "<tbody>";
					for($i=0; $i<count($listabitacora); $i++){
						print "<tr>";
						print "<td>".$listabitacora[$i][1]."</td>";
						print "<td>".$listabitacora[$i][2]."</td>";
						print "<td>".$listabitacora[$i][3]."</td>";
						print "<td>".$listabitacora[$i][4]."</td>";
						print "<td>".$listabitacora[$i][5]."</td>";
						print "<td>".$listabitacora[$i][6]."</td>";
                        print "<td>".$listabitacora[$i][7]."</td>";
                        if($listabitacora[$i][8]==1)
                            print "<td>USO</td>";    
                        else
                            print "<td>LIBRE</td>";
						print "</tr>";
					}
					print "</tbody>";
					print "</table>";
					?>
                </div>
                <footer></footer>  
        </div>
        <div id="derecha">
  
        </div>
    </div>  


	<script>
		$(document).ready( function () {
	    $('#tblbitacora').DataTable();
	} );
	</script>

	
</body>
</html>









