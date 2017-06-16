<?php 
include("php/Formulario.php");
$formulario= new Formulario();
$data= $formulario->ConsultaFormulario();
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Bitácora de Ingreso DCTI San Pedro</title>
    
            
    <!-- CONSULTA FORMULARIO -->
    <link href="css/estilo.css" rel="stylesheet"/>
    <link href="css/divs.css" rel="stylesheet"/>
    <script src="js/jquery.js" type="text/jscript"></script>
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.css">
 	<script type="text/javascript" charset="utf8" src="DataTables/datatables.js"></script>

</head>
<body> 
	<header>
	<h1>FORMULARIOS DE INGRESO</h1>
        <div id="logo"><img src="img/logoice.png" height="75" > </div>
	</header>
    <div id="general">
        <div id="izq"></div>
        <div id="centro">
                <div id="titulocentro">
                    <H3 id="etiqueta">Lista Formularios</H3>
                </div>
        	<?php 
            print "<table id='formulario'class='display'>";
            print "<thead>";
            print "<tr>";
            print "<th>ID</th>";
            print "<th>FECHA SOLICITUD</th>";
            print "<th>FECHA INGRESO</th>";
            print "<th>FECHA SALIDA</th>";
            print "<th>ESTADO</th>";
            print "<th>MOTIVO</th>";
            print "</tr>";
            print "</thead>";	

            print "<tbody>";
            for($i=0; $i<count($data); $i++){
                   print "<tr>";
                   print "<td>".$data[$i][0]."</td>";
                   print "<td>".$data[$i][1]."</td>";
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
        <div id="der"></div>
    </div>	
	<!--Da la apariencia del css datatable-->
	<script>
		$(document).ready( function () {
	    $('#formulario').DataTable();
	} );
	</script>	
</body>
</html>








