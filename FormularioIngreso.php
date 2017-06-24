<?php 
if (!isset($_SESSION)) 
    session_start();

//include("class/sesion.php");
//$sesion = new sesion();
/*
if(!$sesion->estadoLogin()){
    header("location:login.php");
    exit;
}*/
//
include("class/Visitante.php");
$visitante= new Visitante();
$data= $visitante->FormularioIngresoConsultaVisitante();

//Cargar Datos en Formulario Ingreso para Modificar
include("class/Formulario.php");
$id="";
if (isset($_GET['ID'])) {
    $id=$_GET['ID'];
}
//
$formulario = new Formulario();
$formulario->id=$id;
$formdata= $formulario->Cargar();
//print_r ($formdata[0][1]);
//$fechasolicitud = new DateTime($formdata[0][1]);

include("class/sala.php");    
    $sala= new Sala();
    $salas=$sala->Disponibles();
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Bitácora de Ingreso DCTI San Pedro</title>       
    <!-- CSS -->
    <link href="css/estilo.css" rel="stylesheet"/>        
    <link rel="stylesheet" type="text/css" href="css/datatables.css">
    <link href="css/divs.css" rel="stylesheet"/>
    <!-- JS  -->
    <script src="js/jquery.js" type="text/jscript"></script>
 	<script type="text/javascript" charset="utf8" src="js/datatables.js"></script>
</head>
<body> 
    <header>
	<h1>FORMULARIO INGRESO</h1>
    <div id="logo"><img src="img/logoice.png" height="75" > </div>
	</header>
    <div id="general">
        <form class="cbp-mc-form" method="POST" action="request/EnviaFormulario.php" onSubmit="EnviaVisitante()">
            <div id="izq"> 
                <div class="sala">
                    <input type="text" id="sala" name="sala" placeholder="SELECCIONE LA SALA" class="field" readonly="readonly" />
                    <ul class="list">
                        <?php
                            for($i=0; $i<count($salas); $i++){
                                print('<li>'.$salas[$i][1].'</li>');                            
                            }
                        ?>
                    </ul>
                </div>
   
            </div>
            <div id="centro">
                <div id="titulocentro">                    
                    <H3 id="etiqueta">TRAMITE VISITANTE</H3>                                       
                </div>
               <div id="ingreso">
                    <label for="idsala">Seleccione la Sala</label>
                    <input type="text" id="idsala" name="idsala" value="<?php if (isset($_GET['ID'])) {print $formdata[0][9];}?>">
                    
                    <label for="fechaingreso">Fecha y hora Ingreso</label>
                    <input type="datetime-local" id="fechaingreso" name="fechaingreso">
                </div>
                <div id="salida">
                    <label for="fechasolicitud">Fecha y hora Solicitud</label>
                    <input type="datetime-local" id="fechasolicitud" name="fechasolicitud" 
                    value="">                    
                    
                    <label for="fechasalida">Fecha y hora Salida</label>
                    <input type="datetime-local" id="fechasalida" name="fechasalida">
                </div>
                <div id="tablavisitante">
                    <!-- CREA EL TABLE QUE CARGA LOS VISITANTES AL FORMULARIO-->
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
                            <!-- CREA EL TABLE DEL MODAL PARA SELECIONAR VISITANTES -->
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
                        <input type="text" id="placavehiculo" name="placavehiculo" value="<?php if (isset($_GET['ID'])) {print $formdata[0][10];}?>">
                        <label for="detalleequipo">Detalle Equipo</label>
                        <input type="text" id="detalleequipo" name="detalleequipo" value="<?php if (isset($_GET['ID'])) {print $formdata[0][11];}?>">
                    </div>
                    <div id="salida">
                        <label>Motivo Visita</label>
                        <textarea id="motivovisita" name="motivovisita" placeholder="Prueba">
                        <?php if (isset($_GET['ID'])) {print $formdata[0][3];}?>
                        </textarea>                
                    </div>    
                </div>
                
                <div id="abajo">
                    <div id="botonenviaform">
                    <input class="cbp-mc-submit" type="submit" value="Enviar Formulario de Ingreso">
                    <input id=visitantearray name=visitantearray type=hidden></div>
                    <div id="estadosform">
                    <form>
                        <input type="radio" name="estadoformulario" value="0" checked>Pendiente
                      <input type="radio" name="estadoformulario" value="1">Aprobado
                      <input type="radio" name="estadoformulario" value="2">Denegado
                    </form>     
                    </div>
                    <div id="numeroform">
                        <h4>#Formulario</h4>
                        <label id="formnum"></br><?php if (isset($_GET['ID'])) {echo $formdata[0][0];}?></label></div>  
                </div>
                
                
            </div>
            <div id="der">
                
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
        // OBTIENE EL CSS PARA LOS TABLES
        $(document).ready( function () {
            $('#bitacora').DataTable();
            $('#tblvisitantes').DataTable();
            
        } );
        /*$(document).ready( function () {
            $('#tblvisitantes').DataTable();
        } );*/
        
        function NumFormulario(){
            if (isset($_GET['ID'])) {
                document.getElementById("formnum").className = '';    
            }else{
                document.getElementById("formnum").className = 'hidden';    
            }
        }
        
// **** SELECION DE LAS LINEAS DEL MODAL **********************/            
        var jVisitante=[];             
        $('#bitacora tr').on('click', function(){        
            $(this).toggleClass('selected');
            var data={
                "id":$(this).find('td:first').html(),
                "nombre":$(this).find('td:nth-child(2)').html(),
                "empresa":$(this).find('td:nth-child(3)').html()
            };
            var result = $.grep(jVisitante, function(e){  return e.id== data.id; });
            if (result.length  == 0) { // El visitante no esta en la lista
                jVisitante.push(data); 
                var tr="<tr>";
                var td1="<td>"+jVisitante[jVisitante.length-1].id +"</td>";
                var td2="<td>"+jVisitante[jVisitante.length-1].nombre +"</td>";
                var td3="<td>"+jVisitante[jVisitante.length-1].empresa +"</td>";
                var td4="<td><img id=imgdelete src=img/file_delete.png class=borrar></td></tr>";
                $("#tblvisitantes").append(tr+td1+td2+td3+td4); 
            }
            else { // El visitante esta en la lista y debe borrarse
                var i = jVisitante.findIndex(x => x.id === data.id);
                jVisitante.splice(i,1);
                document.getElementById("tblvisitantes").deleteRow(i+1);
            }   
        });
//***** CONCATENA EL ARREGLO EN UN STRING, LO ASIGNA A UN TAG HIDDEN PARA PASAR POR POST ***/
        function EnviaVisitante() {
            for (var index = 0; index < jVisitante.length; index++) {
                var element = jVisitante[index].id;
            document.getElementById("visitantearray").value += element + ",";    
            }          
        }
        
//***** BORRA FILA DE UN TABLE AL SELECCIONAR EL BOTÓN Y LO QUITA DEL ARREGLO *********/       
        $(document).on('click', '.borrar', function (event) {
            
            event.preventDefault();
            var i = $(this).closest('tr').index();
            $(this).closest('tr').remove();
            jVisitante.splice(i-1,1);
            
        });     
//***** COMBO SALAS *********/
        (function ($) {
        $.fn.styleddropdown = function () {
            return this.each(function () {
                var obj = $(this)
                obj.find('.field').click(function () { //onclick event, 'list' fadein
                    obj.find('.list').fadeIn(400);

                    $(document).keyup(function (event) { //keypress event, fadeout on 'escape'
                        if (event.keyCode == 27) {
                            obj.find('.list').fadeOut(400);
                        }
                    });

                    obj.find('.list').hover(function () {},
                        function () {
                            $(this).fadeOut(400);
                        });
                });

                obj.find('.list li').click(function () { //onclick event, change field value with selected 'list' item and fadeout 'list'
                    obj.find('.field')
                        .val($(this).html())
                        .css({
                            'background': '#fff',
                            'color': '#333'
                        });
                    obj.find('.list').fadeOut(400);
                });
            });
        };
})(jQuery);
  
    </script>
    
</body>
</html>









