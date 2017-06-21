<?php 
if (!isset($_SESSION))
    session_start();
//
include("class/sesion.php");
$sesion = new sesion();
if(!$sesion->estadoLogin()){
    header("location:index.php");
    exit;
}
//
include("class/Visitante.php");
$visitante= new Visitante();
$data= $visitante->ConsultaBitacora();
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Bitácora de Ingreso DCTI San Pedro</title>
    
    
    
    <link href="css/estilo.css" rel="stylesheet"/>        
    <!-- CONSULTA BITACORA -->
     
    <script src="js/jquery.js" type="text/jscript"></script>
    <link rel="stylesheet" type="text/css" href="css/datatables.css">
 	<script type="text/javascript" charset="utf8" src="js/datatables.js"></script>

</head>
<body> 
	<header>
	<h1>BITÁCORA DE INGRESO</h1>
        <div id="logo"><img src="img/logoice.png" height="75" > </div>
        <div id="fechahora"><span id="date"></span></div>
	</header>
	<div>    
	<script>
		$(document).ready( function () {
	    $('#bitacora').DataTable();
	} );
	</script>

	<?php 
	print "<table id='bitacora'class='display'>";
	print "<thead>";
	print "<tr>";
	print "<th bgcolor='gray'>Cedula</th>";
	print "<th bgcolor='gray'>Entrada</th>";
	print "<th bgcolor='gray'>Salida</th>";
	print "<th bgcolor='gray'>Detalle</th>";
	print "</tr>";
	print "</thead>";	
	
	print "<tbody>";
	for($i=1; $i<count($data); $i++){
	       print "<tr>";
		   print "<td>".$data[$i][2]."</td>";
		   print "<td>".$data[$i][3]."</td>";
		   print "<td>".$data[$i][4]."</td>";
		   print "<td>".$data[$i][5]."</td>";
	       print "</tr>";
	}
	print "</tbody>";
	print "</table>";
	?>
			
	<footer></footer>
	</div>
</body>
</html>









