<!-- CONSULTA FORMULARIO -->
<?php 
include("class/Formulario.php");
$formulario= new Formulario();
$data= $formulario->ConsultaFormulario();
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Bitácora de Ingreso DCTI San Pedro</title>
    <!-- CSS -->
    <link href="css/estilo.css" rel="stylesheet"/>
    <link href="css/divs.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="css/datatables.css">
    <!-- JS  -->
    <script src="js/jquery.js" type="text/jscript"></script>
    <script type="text/javascript" charset="utf8" src="js/datatables.js"></script>
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
        	<div id="botonnew">
                <input type="button" onclick="location.href='FormularioIngreso.php' " value="Nuevo" name="boton"/>    
        	</div>
            <div id="listavisitante">

           <?php 
            print "<table id='listaformulario'class='display'>";
            print "<thead>";
            print "<tr>";
            print "<th>ID</th>";
            print "<th>FECHA SOLICITUD</th>";
            print "<th>ESTADO</th>";
            print "<th>MOTIVO</th>";
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
            <footer></footer>          
        </div>
        <div id="der"></div>
    </div>	
	
	<script>
        
//***** Da la apariencia del css datatable
        $(document).ready( function () {
	    $('#listaformulario').DataTable();
	    } );
        
         
//***** AL DAR CLICK EN MODIFICAR, CARGAR LOS DATOS DE LA FILA EN UN ARREGLO Y ABRIR           ******* FORMULARIOINGRESO.PHP Y CARGAR LOS DATOS DEL ARREGLO.
        var jVisitante=[]; 
        $(document).on('click', '.modificar', function (event) {
            event.preventDefault();
            //Cargar Arreglo
            
            var children = $("tr td")[0].innerHTML;
            alert(children);
            
            //Direccionar a FormularioIngreso
            
            
        });   
        
        
	</script>	
</body>
</html>







