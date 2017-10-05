<?php 
if (!isset($_SESSION))
    session_start();
//
include_once('class/Globals.php');
include_once("class/Sesion.php");

// Sesion de usuario
$sesion = new Sesion();
if (!$sesion->estado){
	$_SESSION['url']= explode('/',$_SERVER['REQUEST_URI'])[2];
    header('Location: Login.php');
    exit;
}
//
include("class/Bitacora.php");
$bitacora= new Bitacora();
$listabitacora= $bitacora->Consulta();
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Bitácora de Ingreso DCTI San Pedro</title>
    <!-- CSS -->
    <link href="css/Estilo.css?v=<?php echo Globals::cssversion; ?>" rel="stylesheet" /> 
    <link rel="stylesheet" href="css/datatables.css" type="text/css"/>
    <link rel="stylesheet" href="css/Formulario.css" type="text/css"/>
    <link rel="stylesheet" href="css/sweetalert2.css" type="text/css"/>
    <!-- JS  -->
    <script src="js/jquery.js" type="text/jscript"></script>
 	<script src="js/datatables.js" type="text/javascript" charset="utf8"></script>
    <script src="js/Validaciones.js" languaje="javascript" type="text/javascript"></script> 
    <script src="js/sweetalert2.js"></script>

    <!-- EXPORTAR ARCHIVOS  -->
    <script type="text/javascript" src="https://cdn.datatables.net/tabletools/2.2.4/js/dataTables.tableTools.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.print.min.js"></script>

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
	    $('#tblbitacora').DataTable({
                "order": [[ 3, "desc" ]],
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });
	} );
	</script>

	
</body>
</html>









