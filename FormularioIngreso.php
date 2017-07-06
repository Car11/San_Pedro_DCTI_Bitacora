<?php 
if (!isset($_SESSION)) 
    session_start();

// Sesion de usuario
include("class/sesion.php");
$sesion = new sesion();
if (!$sesion->estado){
    header('Location: login.php');
    exit;
}

//VISITANTE
include("class/Visitante.php");
$visitante= new Visitante();
$visitantes= $visitante->FormularioIngresoConsultaVisitante();

//FORMULARIO - Cargar Datos en Formulario Ingreso para Modificar
include("class/Formulario.php");
$formulario = new Formulario();
$estadoformulario=0;
$id=0;
if (isset($_GET['ID'])) {    
    $id=$_GET['ID'];
    // es formulario temporal
    $_SESSION['TEMP']=$id;
    $formulario->id=$id;
    //Carga la sala según el link
    $formdata= $formulario->Cargar();
    //Si hay un link carga el estado en el radio
    $estadoformulario= $formdata[0][2];
    $visitanteformulario=$formulario->CargaVisitanteporFormulario();
    //$visitanteformulario = json_encode($visitanteformulario);
    $largo=count($visitanteformulario);
}

//SALA 
include("class/sala.php");    
$sala= new Sala();
$salas=$sala->Disponibles();

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
	<h1>FORMULARIO DE INGRESO</h1>        
    <div id="logo"><img src="img/logoice.png" height="75" ></div>
	</header>
    <div id="general">
        <form class="cbp-mc-form" method="POST" action="request/EnviaFormulario.php" onSubmit="return EnviaVisitante()">       
        <div id="izquierda">
            <div id="superiorizq"></div>
            <div id="medioizq">
                <img id=imgflecha src=img/flecha-error.png class="imagenNO">
            </div>    
        </div>
        <div id="principal">
            <div id="superiornavegacion">
                <div id="nuevo">   
                </div>
                <div id="atras">
                    <input type="button" id="btnatras" class="cbp-mc-submit" value="Atrás" onclick="location.href='ListaFormulario.php'";>   
                </div>
                <div id="extra"></div>
            </div>
            <div id="superior">
                <div id="caja">
                    <div id="cajainput">
                        <label for="fechaingreso" class="labelformat">Fecha y hora Ingreso</label></br>
                        <input type="datetime-local" name="fechaingreso" class="inputformat" value="<?php if (isset($_GET['ID'])) {print $formdata[0][4];}?>" required/>
                    </div>
                    <div id="cajainput">
                        <label for="txtresponsable" class="labelformat">Seleccione el Responsable</label></br>
                        <input type="text" id="txtresponsable" name="txtresponsable" placeholder="CLICK" class="inputformat" readonly="readonly"
                        value="<?php if (isset($_GET['ID'])) {print $formdata[0][8];}?>" required/>  
                    </div>
                </div>
                <div id="caja">
                    <div id="cajainput">
                        <label for="fechasalida" class="labelformat">Fecha y hora Salida</label>
                        <input type="datetime-local" name="fechasalida" class="inputformat" value="<?php if (isset($_GET['ID'])) {print $formdata[0][5];}?>" required/> 
                    </div>
                    <div id="cajainput">
                        <label for="selectsala" class="labelformat">Seleccione la Sala</label></br>
                        <input type="text" id="selectsala" name="selectsala" placeholder="CLICK" class="inputformat" readonly="readonly"
                        value="<?php if (isset($_GET['ID'])) {print $formdata[0][9];}?>" required/> 
                    </div>
                </div>
                <div id="caja">
                    <div id="cajainput">
                        <label for="txttramitante" class="labelformat">Tramitante</label></br>
                        <input type="text" id="txttramitante" name="txttramitante" placeholder="" class="inputformat" readonly="readonly" 
                        value="<?php echo($usuario->nombre);?>"/>
                    </div>                   
                    <div id="cajainput">
                        <label for="txtautorizador" class="labelformat">Autorizador</label></br>
                        <input type="text" id="txtautorizador" name="txtautorizador" placeholder="" class="inputformat" readonly="readonly" 
                        value="<?php if ($rol==1) echo($usuario->nombre); ?>"/> 
                    </div>
                </div>
            </div>  
            <div id="medio">
                <div id="tabla">
                   <div id="distribuciontabla">
                        <div id="tablavisitante">
                            <!-- CREA EL TABLE QUE CARGA LOS VISITANTES AL FORMULARIO-->
                            <?php
                            print "<table id='tblvisitanteform'>";
                            print "<thead>";
                            print "<tr>";
                            print "<th id='titulocedula'>Cedula</th>";
                            print "<th id='titulonombre'>Nombre</th>";
                            print "<th id='tituloempresa'>Empresa</th>";
                            print "<th id='tituloeliminar'>Eliminar</th>";
                            print "</tr>";
                            print "</thead>";
                            print "</table>"; 
                            ?>
                        </div>
                    <div id="btnagregarvisitante">
                        <input type="button" id="myBtn" value="+">  
                    </div>
                   </div>
                   <div id="distribuciontabla2"></div>
                </div>
                <div id="etiquetas">
                    <div id="numeroformulario">
                        <div id="cajanumform">
                            <label class="labelformatnum">Formulario #</label>    
                        </div>
                        <div id="cajanumform2">
                            <input type="text" id="lblnumeroform" name="lblnumeroform" class="inputreadonly" 
                            value="<?php if (isset($_GET['ID'])) echo $formdata[0][0]; else echo "nuevo";?>"/>   
                        </div>
                        
                    </div>
                    <div id="estadoformulario">
                        <div id="estadosform">
                            <form id="formularioestados">
                                <input type="radio" class="radioformat" id="pendiente" name="estadoformulario" value="0" checked>
                                <label class="labelradioformat">Pendiente</label>
                                </br>
                                <input type="radio" class="radioformat" id="aprobado" name="estadoformulario" value="1">
                                <label class="labelradioformat">Aprobado</label>
                                </br>
                                <input type="radio" class="radioformat" id="denegado" name="estadoformulario" value="2">
                                <label class="labelradioformat">Denegado</label>
                            </form>     
                        </div>
                    </div>
                    <div id="submitformulario">
                        <input id="EnviaFormulario" class="cbp-mc-submit" type="submit" value="Enviar Formulario">
                        <input id="visitantearray" name="visitantearray" type="hidden">
                        <input id="visitantelargo" name="visitantelargo" type="hidden">
                    </div>
                </div>
            </div> 
            <div id="inferior">
                <div id="cajade3">
                    <div class="cajainput2">
                        <label for="placavehiculo" class="labelformat">Placas Vehículos</label>
                        <input type="text" id="placavehiculo" class="inputformat" name="placavehiculo" value="<?php if (isset($_GET['ID'])) {print $formdata[0][10];}?>" pattern="[\.,-_0-9áéíóúA-Za-z/\s/]*" maxlength="500" title="No se permiten caracteres especiales"/>
                    </div>      
                    <div class="cajainput2">
                        <label for="detalleequipo" class="labelformat">Detalle Equipo</label>
                        <input type="text" id="detalleequipo" class="inputformat" name="detalleequipo" value="<?php if (isset($_GET['ID'])) {print $formdata[0][11];}?>" pattern="[\.,-_0-9áéíóúA-Za-z/\s/]*" maxlength="500" title="No se permiten caracteres especiales"/>
                    </div>
                    <div class="cajainput2">
                        <label for="txtrfc" class="labelformat">RFC          :</label>
                        <input type="text" id="txtrfc" name="txtrfc" placeholder="" class="inputformat" value="<?php if (isset($_GET['ID'])) {print $formdata[0][12];}?>" pattern="[\.,-_0-9áéíóúA-Za-z/\s/]*" maxlength="10" title="No se permiten caracteres especiales"/>
                    </div>  
                </div>
                <div id="cajade3">
                    <label for="motivovisita" class="labelformat">Motivo Visita</label>
                    <input type="text" id="motivovisita" name="motivovisita" value="<?php if (isset($_GET['ID'])) echo $formdata[0][3];?>" required pattern="[\.,-_0-9áéíóúA-Za-z/\s/]*" minlength="8" maxlength="160" title="No se permiten caracteres especiales"/>
                </div>
            </div>  
        </div>
        <div id="derecha">
            
        </div>
    </form>
    </div>    
            
    <!-- MODAL VISITANTE -->
    <div id="ModalVisitante" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Seleccione los Visitantes a Autorizar</h2>
            </div>
            <div class="modal-body">
                <!-- CREA EL TABLE DEL MODAL PARA SELECIONAR VISITANTES -->
                <?php 
                print "<table id='tblvisitante'class='display'>";
                print "<thead>";
                print "<tr>";
                print "<th>Cedula</th>";
                print "<th>Nombre</th>";
                print "<th>Empresa</th>";
                print "</tr>";
                print "</thead>";	
                print "<tbody>";
                for($i=0; $i<count($visitantes); $i++){
                        print "<tr>";
                        print "<td>".$visitantes[$i][0]."</td>";
                        print "<td>".$visitantes[$i][1]."</td>";
                        print "<td>".$visitantes[$i][2]."</td>";
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
    <!--FINAL MODAL VISITANTE-->

    <!-- MODAL RESPONSABLE -->
    <div id="ModalResponsable" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Seleccione el Responsable</h2>
            </div>
            <div class="modal-body">
                <!-- CREA EL TABLE DEL MODAL PARA SELECIONAR RESPONSABLES -->
                <?php 
                print "<table id='tblresponsable'class='display'>";
                print "<thead>";
                print "<tr>";
                print "<th>ID</th>";
                print "<th>Nombre</th>";
                print "<th>Cedula</th>";
                print "<th>Empresa</th>";
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
            <div class="modal-footer">
            <br>
            </div>
        </div>   
        <!--FINAL MODAL RESPONSABLE-->
    </div>

    <!-- MODAL SALA -->
    <div id="ModalSala" class="modal">
        <!-- Modal content -->
        <div class="modal-content-sala">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Seleccione el Sala</h2>
            </div>
            <div class="modal-body">
                <!-- CREA EL TABLE DEL MODAL PARA SELECIONAR RESPONSABLES -->
                <?php 
                print "<table id='tblsala'class='display'>";
                print "<thead>";
                print "<tr>";
                print "<th>Locación</th>";
                print "</tr>";
                print "</thead>";	
                print "<tbody>";
                for($i=0; $i<count($salas); $i++){
                    print "<tr>";
                    print "<td>".$salas[$i][1]."</td>";
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
        <!--FINAL MODAL RESPONSABLE-->
    </div>
    
<script>
    //Se ejecuta al iniciar la pagina
    var x = "<?php echo $id;?>";      
    var jSala=[];
    var jResponsable=[];
    var jVisitante=[]; 
    // Obtiene el MODAL
    var modalVisitante = document.getElementById('ModalVisitante');    
    var modalResponsable = document.getElementById('ModalResponsable');     
    var modalSala = document.getElementById('ModalSala');
    // Botón que abre el MODAL
    var btn = document.getElementById("myBtn");
    var inputResponsable = document.getElementById("txtresponsable");
    var inputSala = document.getElementById("selectsala");
    // Obtiene el <span> que  cierra el MODAL
    var span = document.getElementsByClassName("close")[0];

    $(document).ready( function () {    
        if (x!=0){
            CargaVisitanteLink();
            EstadoFormulario();    
        }
        $('.sala').styleddropdown();
        // OBTIENE EL CSS PARA LOS TABLES
        $('#tblvisitante').DataTable();
        $('#tblresponsable').DataTable();          
        $('#tblsala').DataTable();
        MuestraEstados();
    } );
        
    // Evento click que abre el MODAL
    btn.onclick = function() {
        modalVisitante.style.display = "block";
    }
    inputResponsable.onclick = function() {
        modalResponsable.style.display = "block";
    }
    inputSala.onclick = function() {
        modalSala.style.display = "block";
    }
    // Cierra el MODAL en la X
    span.onclick = function() {
        modalResponsable.style.display = "none";
        modalVisitante.style.display = "none";
        modalSala.style.display = "none";
    }

    // Cierra el MODAL en cualquier parte de la ventana
    window.onclick = function(event) {
        if (event.target == modalVisitante) {
            modalVisitante.style.display = "none";
        }
        if (event.target == modalResponsable) {
            modalResponsable.style.display = "none";
        }
        if (event.target == modalSala) {
            modalSala.style.display = "none";
        }
    }

    //Oculta o Muestra el DIV de estados del formualario
    function MuestraEstados(){
        var rol = "<?php echo $rol ?>";
        if (rol==1) {$('#estadosform').show();}else{$('#estadosform').hide();}
    }

    //Muestra u Oculta el numero del Formulario 
    function NumFormulario(){
        if (isset($_GET['ID'])) {
            document.getElementById("formnum").className = '';    
        }else{
            document.getElementById("formnum").className = 'hidden';    
        }
    }

    //Maneja el evento checked del estado del radio button formulario
    function EstadoFormulario(){
        var estado = "<?php echo $estadoformulario; ?>";         
        if (estado==0) {
            document.getElementById("pendiente").checked = true;   
            document.getElementById("aprobado").checked = false;
            document.getElementById("denegado").checked = false; 
        }
        if(estado==1){
            document.getElementById("pendiente").checked = false;   
            document.getElementById("aprobado").checked = true;
            document.getElementById("denegado").checked = false; 
        }
        if(estado==2){
            document.getElementById("pendiente").checked = false;   
            document.getElementById("aprobado").checked = false;
            document.getElementById("denegado").checked = true; 
        }
    }

    //Carga el Visitante en la tabla tblvisitanteform
    function CargaVisitanteLink(){    

        var data={
            "id":"<?php if (isset($_GET['ID'])) echo $visitanteformulario[0][0]; ?>",
            "nombre":"<?php if (isset($_GET['ID'])) echo $visitanteformulario[0][1]; ?>",
            "empresa":"<?php if (isset($_GET['ID'])) echo $visitanteformulario[0][2]; ?>"
        };

        var result = $.grep(jVisitante, function(e){  return e.id== data.id; });
        if (result.length  == 0) { // El visitante no esta en la lista
            jVisitante.push(data); 
            var tb1="<tbody>";
            var tr="<tr class='fila'>";
            var td1="<td class='tdcolumna'>"+jVisitante[jVisitante.length-1].id +"</td>";
            var td2="<td class='tdcolumna'>"+jVisitante[jVisitante.length-1].nombre +"</td>";
            var td3="<td class='tdcolumna'>"+jVisitante[jVisitante.length-1].empresa +"</td>";
            var td4="<td class='tdcolumna'></td></tr>";
            var tb2="</tbody>";
            $("#tblvisitanteform").append(tb1+tr+td1+td2+td3+td4+tb2);    
        }
        else { // El visitante esta en la lista y debe borrarse
            var i = jVisitante.findIndex(x => x.id === data.id);
            jVisitante.splice(i,1);
        }
    }
    
    //SELECION DE LAS LINEAS DEL MODAL **********************/                        
    $('#tblvisitante tr').on('click', function(){        
        //$(this).toggleClass('selected');
                
        var data={
            "id":$(this).find('td:first').html(),
            "nombre":$(this).find('td:nth-child(2)').html(),
            "empresa":$(this).find('td:nth-child(3)').html()
        };
        var result = $.grep(jVisitante, function(e){  return e.id== data.id; });
        if (result.length  == 0) { // El visitante no esta en la lista
            jVisitante.push(data); 
            var tb1="<tbody>";
            var tr="<tr class='fila'>";
            var td1="<td>"+jVisitante[jVisitante.length-1].id +"</td>";
            var td2="<td>"+jVisitante[jVisitante.length-1].nombre +"</td>";
            var td3="<td>"+jVisitante[jVisitante.length-1].empresa +"</td>";
            var td4="<td><img id=imgdelete src=img/file_delete.png class=borrar></td></tr>";
            var tb2="</tbody>";
            $("#tblvisitanteform").append(tb1+tr+td1+td2+td3+td4+tb2); 
            $(this).css('display', 'none');
            $('#imgflecha').removeClass('imagen');
            $('#imgflecha').addClass('imagenNO');
        }
        /*else { // El visitante esta en la lista y debe borrarse
            var i = jVisitante.findIndex(x => x.id === data.id);
            jVisitante.splice(i,1);
            document.getElementById("tblvisitanteform").deleteRow(i+1);                 
        }*/
    });

    //CONCATENA EL ARREGLO EN UN STRING, LO ASIGNA A UN TAG HIDDEN PARA PASAR POR POST ***/
    function EnviaVisitante() {
        var x = document.getElementsByName("fechaingreso").value;
        if(x=="x"){
            alert("Debe de insertar una fecha de ingreso");
            return false;
        }
        
        for (var index = 0; index < jVisitante.length; index++) {
            var element = jVisitante[index].id;
            document.getElementById("visitantearray").value += element + ",";    
        }   
        //valida si se han agregado visitantes a la tabla
        if(jVisitante.length==0){
            //alert("Debe de insertar al menos un Visitante!");
            $('#imgflecha').addClass('imagen');
            return false;
        }
        alert("Formulario Creado!");    
        //$('#listaformulario').DataTable({"order": [[ 3, "desc" ]]});  
    }
    
    //BORRA FILA DE UN TABLE AL SELECCIONAR EL BOTÓN Y LO QUITA DEL ARREGLO *********/       
    $(document).on('click', '.borrar', function (event) {
        $('#tblvisitante tr').closest('tr').css('display', '');
        event.preventDefault();
        var i = $(this).closest('tr').index();
        $(this).closest('tr').remove();
        jVisitante.splice(i-1,1);
    });     

    //COMBO SALAS *********/
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

    //MODAL RESPONSABLES ********/
    $('#tblresponsable tr').on('click', function(){        
        $(this).toggleClass('selected');
        jResponsable.length = 0;
        $("#txtresponsable").val('');
        var data={
            "id":$(this).find('td:first').html(),
            "nombre":$(this).find('td:nth-child(2)').html(),
            "cedula":$(this).find('td:nth-child(3)').html(),
            "empresa":$(this).find('td:nth-child(4)').html()
        };
        jResponsable.push(data); 
        $("#txtresponsable").val(jResponsable[jResponsable.length-1].nombre);
        modalResponsable.style.display = "none";
        $(this).toggleClass('selected')=false;                        
    });

    //MODAL SALAS ********/
    $('#tblsala tr').on('click', function(){        
        $(this).toggleClass('selected');
        jSala.length = 0;
        $("#selectsala").val('');
        var data={
            "sala":$(this).find('td:first').html()
        };
        jSala.push(data); 
        $("#selectsala").val(jSala[jSala.length-1].sala);
        modalSala.style.display = "none";
        $(this).toggleClass('selected')=false;                        
    });
    
    //IMAGENES OCULTAR Y MOSTRAR
    
    
</script>
</body>
</html>