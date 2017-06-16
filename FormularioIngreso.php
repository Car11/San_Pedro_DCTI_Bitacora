<?php 
include("php/Visitante.php");
$visitante= new Visitante();
$data= $visitante->FormularioIngresoConsultaVisitante();

include("php/sala.php");    
    $sala= new Sala();
    $salas=$sala->Disponibles();
?>

<html>
<head>
        <meta charset="UTF-8">
    <title>Bitácora de Ingreso DCTI San Pedro</title>   
    <link href="css/estilo.css" rel="stylesheet"/>        
    <!-- CONSULTA BITACORA -->
     
    <script src="js/jquery.js" type="text/jscript"></script>
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.css">
 	<script type="text/javascript" charset="utf8" src="DataTables/datatables.js"></script>
    <link href="css/divs.css" rel="stylesheet"/>
    

</head>
<body> 
    <header>
	<h1>FORMULARIO INGRESO</h1>
    <div id="logo"><img src="img/logoice.png" height="75" > </div>
	</header>
    <div id="general">
        <form class="cbp-mc-form" method="POST" action="EnviaFormulario.php" onSubmit="EnviaVisitante()">
            <div id="izq">    
            </div>
            <div id="centro">
                <div id="titulocentro">
                    <H3 id="etiqueta">TRAMITE VISITANTE</H3>
                </div>
               <div id="ingreso">
                    <label for="idsala">Seleccione la Sala</label>
                    <input type="text" id="idsala" name="idsala" value="">
                    
                    <label for="fechaingreso">Fecha y hora Ingreso</label>
                    <input type="datetime-local" id="fechaingreso" name="fechaingreso">
                </div>
                <div id="salida">
                    <label for="fechasolicitud">Fecha y hora Solicitud</label>
                    <input type="datetime-local" id="fechasolicitud" name="fechasolicitud">                    
                    
                    <label for="fechasalida">Fecha y hora Salida</label>
                    <input type="datetime-local" id="fechasalida" name="fechasalida">
                </div>
                <div id="tablavisitante">
                    <?php
                    print "<table border='1' id='tblvisitantes' class='display'>";
                    print "<tr>";
                    print "<th>ID</th>";
                    print "<th>Nombre</th>";
                    print "<th>Empresa</th>";
                    print "<th>Eliminar</th>";
                    print "</tr>";
                    print "</table>"; 
                    ?>
                </div>
                <div id="botonestabla">
                    <input type="button" id="myBtn" value="+">  
                    
                    <!-- The Modal -->
                    <div id="myModal" class="modal">
                    <!-- Modal content -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <span class="close">&times;</span>
                            <h2>Seleccione los Visitantes a Autorizar</h2>
                        </div>
                        <div class="modal-body">
                            <?php 
                                print "<table id='bitacora'class='display'>";
                                print "<thead>";
                                print "<tr>";
                                print "<th>Cedula</th>";
                                print "<th>Nombre</th>";
                                print "<th>Empresa</th>";
                                print "</tr>";
                                print "</thead>";	
                                print "<tbody>";
                                for($i=0; $i<count($data); $i++){
                                       print "<tr>";
                                       print "<td>".$data[$i][0]."</td>";
                                       print "<td>".$data[$i][1]."</td>";
                                       print "<td>".$data[$i][2]."</td>";
                                       print "</tr>";
                                }
                                print "</tbody>";
                                print "</table>";
                            ?> 
                        </div>
                        <div class="modal-footer">
                        <br>
                        </div>
                    </div>

                </div>
                <!-- END MODAL -->
                </div>
                <div id="infoextra">
                    <div id="ingreso">
                        <label for="placavehiculo">Placas Vehículos</label>
                        <input type="text" id="placavehiculo" name="placavehiculo">
                        <label for="detalleequipo">Detalle Equipo</label>
                        <input type="text" id="detalleequipo" name="detalleequipo">
                    </div>
                    <div id="salida">
                        <label>Motivo Visita</label>
                        <textarea id="motivovisita" name="motivovisita" placeholder="Prueba"></textarea>                
                    </div>    
                </div>
                
            </div>
            <div id="der">
                
            </div>
            
            <div id="abajo">
                <input id=visitantearray name=visitantearray type=hidden>
                <input class="cbp-mc-submit" type="submit" value="Enviar Formulario de Ingreso">
            </div>
        </form>
           
        
    </div>
    
    <script>
        // Get the modal
        var modal = document.getElementById('myModal');         
        // Get the button that opens the modal
        var btn = document.getElementById("myBtn");
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        
        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        
        $(document).ready( function () {
            $('#bitacora').DataTable();
        } );
        
        $(document).ready( function () {
            $('#tblvisitantes').DataTable();
        } );
        
// ************************* SELECION DE LAS LINEAS DEL MODAL **********************/
        /*Arreglo de Visitantes*/            
        var jVisitante=[];             
        $('#bitacora tr').on('click', function(){        
            $(this).toggleClass('selected');
            var data={
                "id":$(this).find('td:first').html(),
                "nombre":$(this).find('td:nth-child(2)').html(),
                "empresa":$(this).find('td:nth-child(3)').html()
            };
            var result = $.grep(jVisitante, function(e){  return e.id== data.id; });
            if (result.length  == 0) { // el visitante no esta en la lista
                jVisitante.push(data); 
                var tr="<tr>";
                var td1="<td>"+jVisitante[jVisitante.length-1].id +"</td>";
                var td2="<td>"+jVisitante[jVisitante.length-1].nombre +"</td>";
                var td3="<td>"+jVisitante[jVisitante.length-1].empresa +"</td>";
                var td4="<td><img id=imgdelete src=img/file_delete.png class=borrar></td></tr>";
                $("#tblvisitantes").append(tr+td1+td2+td3+td4); 
            }
            else { // el visitante esta en la lista y debe borrarse
                var i = jVisitante.findIndex(x => x.id === data.id);
                jVisitante.splice(i,1);
                document.getElementById("tblvisitantes").deleteRow(i+1);
            }   
        });
        
        function EnviaVisitante() {
            /*var visitantearray=jVisitante.toString();*/
            for (var index = 0; index < jVisitante.length; index++) {
                var element = jVisitante[index].id;
            document.getElementById("visitantearray").value += element + ",";    
            }          
        }
        
        $(document).on('click', '.borrar', function (event) {
            event.preventDefault();
            var i = $(this).closest('tr').index();
            $(this).closest('tr').remove();
            jVisitante.splice(i-1,1);
        });
        
    </script>

</body>
</html>









