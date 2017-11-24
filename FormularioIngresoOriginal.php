<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once('class/Globals.php');
// Sesion de usuario
require_once("class/Sesion.php");
$sesion = new Sesion();
if (!$sesion->estado) {
    $_SESSION['url']= explode('/', $_SERVER['REQUEST_URI'])[2];
    header('Location: Login.php');
    exit;
}

//formulario - Cargar Datos en Formulario Ingreso para Modificar
include("class/Formulario.php");
$formulario = new Formulario();
$estadoformulario=0;
$id=0;
$largo=0;
$visitanteformulario=0;
$btnmod=3;
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
    $largo=count($visitanteformulario);
}
if (isset($_GET['MOD'])) {
    $id=$_GET['MOD'];
    $formulario->id=$id;
    //Carga la sala según el link
    $formdata= $formulario->Cargar();
    //Si hay un link carga el estado en el radio
    $estadoformulario= $formdata[0][2];
    $visitanteformulario=$formulario->CargaVisitanteporFormulario();
    $largo=count($visitanteformulario);
    $btnmod=1;
}

//SALA 
//include("class/Sala.php");
//$sala= new Sala();
//$salas=$sala->Disponibles();

//RESPONSABLE
include("class/Responsable.php");
$responsable= new Responsable();
$responsables= $responsable->Consulta();


//USER AND ROL
include("class/Usuario.php");
$usuario = new Usuario();
$usuario->Cargar();
$user= $_SESSION['username'];
$rol=$_SESSION['rol'];


?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Control de Accesos</title>       
    <!-- CSS -->
    <link href="css/Estilo.css?v= <?php echo Globals::cssversion; ?>" rel="stylesheet" />  
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
    <h1>FORMULARIO DE INGRESO</h1>        
    <div id="logo"><img src="img/Logoice.png" height="75" ></div>
    </header>
    <div id="general">
        <form class="cbp-mc-form" method="POST" action="request/EnviaFormulario.php" onSubmit="return EnviaVisitante()">       
            <div id="izquierda">
                <div id="superiorizq">
                <label for="selectdatacenter" class="labelformat">Seleccione Data Center</label></br>
                            <input type="text" id="selectdatacenter" name="selectsaladatacenter" placeholder="CLICK" class="input-field-form" readonly="readonly"
                            value="<?php if (isset($_GET['ID'])||isset($_GET['MOD'])) {print $formdata[0][9];}?>" required/>  
                </div>
                <div id="medioizq">
                    <img id=imgflecha src=img/flecha-error.png class="imagenNO">
                </div>    
            </div>
            <div id="formularioenviado">
                <div id="mensajeform">
                <h1>FORMULARIO ENVIADO</h1>        
                <h2>SERÁ NOTIFICADO VÍA CORREO DE SU APROBACIÓN</h2>
                <h2>Muchas Gracias!!!</h2>
                </div>
                <div id="espacioform"></div>
            </div>
            <div id="principal">
                <div id="superiornavegacion">
                    <div id="nuevo">   
                    </div>
                    <div id="atras">
                        <input type="button" id="btnatras" class="cbp-mc-submit" value="Atrás">   
                    </div>
                    <div id="extra"></div>
                </div>
                <div id="superior">
                    <div id="caja">
                        <div class="cajainput">
                            <label for="fechaingreso" class="labelformat">Fecha y hora Ingreso</label></br>
                            <input type="datetime-local" id="fechaingreso" name="fechaingreso" class="input-field-form" 
                            value="<?php if (isset($_GET['ID'])||isset($_GET['MOD'])) {print $formdata[0][4];}?>" required/>
                        </div>
                        <div class="cajainput">
                            <label for="txtresponsable" class="labelformat">Seleccione el Responsable</label></br>
                            <input type="text" id="txtresponsable" name="txtresponsable" class="input-field-form" placeholder="CLICK" readonly="readonly"
                            value="<?php if (isset($_GET['ID'])||isset($_GET['MOD'])) {
                                print $formdata[0][8];}?>" required/>
                        </div>
                    </div>
                    <div id="caja">
                        <div class="cajainput">
                            <label for="fechasalida" class="labelformat">Fecha y hora Salida</label>
                            <input type="datetime-local" id="fechasalida" name="fechasalida" class="input-field-form" 
                            value="<?php if (isset($_GET['ID'])||isset($_GET['MOD'])) {print $formdata[0][5];}?>" required/> 
                        </div>
                        <div class="cajainput">
                            <label for="selectsala" class="labelformat">Seleccione la Sala</label></br>
                            <input type="text" id="selectsala" name="selectsala" placeholder="CLICK" class="input-field-form" readonly="readonly"
                            value="<?php if (isset($_GET['ID'])||isset($_GET['MOD'])) {
                                print $formdata[0][9];}?>" required/>
                        </div>
                    </div>
                    <div id="caja">
                        <div id="cajainput_tramitante">
                            <label for="txttramitante" class="labelformat">Tramitante</label></br>
                            <input type="text" id="txttramitante" name="txttramitante" class="input-field-form" readonly="readonly" 
                            value="<?php if (isset($_GET['ID'])||isset($_GET['MOD'])) print $formdata[0][6]; else echo($usuario->nombre);?>"/>
                        </div>                   
                        <div id="cajainput_autorizador">
                            <label id="lblautorizador" for="txtautorizador" class="labelformat">Autorizador</label></br>
                            <input type="text" id="txtautorizador" name="txtautorizador" class="input-field-form" readonly="readonly" 
                            value="<?php if (isset($_GET['ID'])||isset($_GET['MOD'])) { if($formdata[0][7]==null and $rol==1)echo($usuario->nombre); else print $formdata[0][7];} else { if ($rol==1) echo($usuario->nombre);} ?>" /> 

                        </div>
                    </div>
                </div>  
                <div id="medio">
                    <div id="tabla">
                       <div id="distribuciontabla">
                            <div id="tablavisitante">
                                <!-- CREA EL TABLE QUE CARGA LOS VISITANTES AL formulario-->
                                <?php
                                print "<table id='tblvisitanteform' class='display' cellspacing='0' width='100%' >";
                                print "<thead>";
                                print "<tr class='fila'>";
                                print "<th id='titulocedula'>Cedula</th>";
                                print "<th id='titulonombre'>Nombre</th>";
                                print "<th id='tituloempresa'>Empresa</th>";
                                print "<th id='tituloeliminar'>Eliminar</th>";
                                print "</tr>";
                                print "</thead>";
                                if (isset($_GET['ID'])||isset($_GET['MOD'])) {
                                    print "<tbody>";
                                    for ($i=0; $i<count($visitanteformulario); $i++) {
                                        print "<tr class='fila'>";
                                        print "<td>".$visitanteformulario[$i][0]."</td>";
                                        print "<td>".$visitanteformulario[$i][1]."</td>";
                                        print "<td>".$visitanteformulario[$i][2]."</td>";
                                        print "<td><img id=imgdelete src=img/file_delete.png class=borrar></td>";
                                        print "</tr>";
                                    }
                                    print "</tbody>";
                                }
                                print "</table>";
                                ?>
                            </div>
                        <div id="btnagregarvisitante">
                            <input type="button" id="btnagregavisitante" value="+">  
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
                                value="<?php if (isset($_GET['ID'])||isset($_GET['MOD'])) {
                                    echo $formdata[0][13];
    } else {
        echo "nuevo";
    }?>"/>   
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
                            <input type="button" id="btnInsertaFormulario" class="cbp-mc-submit" value="Insertar">
                            <input type="button" id="btnModificaFormulario" class="cbp-mc-submit" value="Modificar">
                            <input id="visitantearray" name="visitantearray" type="hidden">
                            <input id="visitantelargo" name="visitantelargo" type="hidden">
                            <input id="visitanteexcluido" name="visitanteexcluido" type="hidden" value="">
                            <input id="idformulario" name="idformulario" type="hidden" value="<?php if (isset($_GET['ID'])||isset($_GET['MOD'])) {
                                    echo $formdata[0][0];
    } else {
        echo "nuevo";
    }?>"
                        </div>
                    </div>
                </div> 
                <div id="inferior">
                    <div id="cajade3">

                        <div class="cajainput2">
                        <div class="positionlabel">
                            <label for="placavehiculo" class="labelformat">Placas Vehículos</label>
                            </div>
                            <div class="positioninput">
                            <input type="text" id="placavehiculo" class="input-field-form" name="placavehiculo" 
                            value="<?php if (isset($_GET['ID'])||isset($_GET['MOD'])) {
                                print $formdata[0][10];}?>" 
                            pattern="[\.,-_0-9áéíóúA-Za-z/\s/]*" maxlength="500" title="No se permiten caracteres especiales"/>
                            </div>
                        </div>      

                        <div class="cajainput2">
                        <div class="positionlabel">
                            <label for="detalleequipo" class="labelformat">Detalle Equipo</label>
                            </div>
                            <div class="positioninput">
                            <input type="text" id="detalleequipo" class="input-field-form" name="detalleequipo" 
                            value="<?php if (isset($_GET['ID'])||isset($_GET['MOD'])) {
                                print $formdata[0][11];}?>" 
                            pattern="[\.,-_0-9áéíóúA-Za-z/\s/]*" maxlength="500" title="No se permiten caracteres especiales"/>
                            </div>
                        </div>

                        <div class="cajainput2">
                            <div class="positionlabel">
                                <label for="txtrfc" class="labelformat">RFC</label>
                            </div>
                            <div class="positioninput">
                                <input type="text" id="txtrfc" name="txtrfc" placeholder="" class="input-field-form" 
                                value="<?php if (isset($_GET['ID'])||isset($_GET['MOD'])) {
                                print $formdata[0][12]; }?>" 
                                pattern="[\.,-_0-9áéíóúA-Za-z/\s/]*" maxlength="10" title="No se permiten caracteres especiales"/>    
                                </div>
                        </div>  

                    </div>
                    <div id="cajainput3">
                        <label for="motivovisita" class="labelformat">Motivo Visita</label>
                        <input type="text" id="motivovisita" name="motivovisita" class="input-field-form"
                        value="<?php if (isset($_GET['ID'])||isset($_GET['MOD'])) echo $formdata[0][3];?>" required 
                        pattern="[\.,-_0-9#áéíóúÁÉÍÓÚÑñA-Za-z/\s/]*" minlength="8" maxlength="160" title="No se permiten caracteres especiales"/>
                    </div>
                </div>  
            </div>
            </div>
            <div id="derecha"></div>
        </form>
    </div>    

    <!-- MODAL RESPONSABLE -->
    <div id="ModalResponsable" class="modal">
        <!-- Modal content -->
        <div class="modal-content-responsable">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Seleccione Responsable</h2>
            </div>
            <div class="modal-body">
                <!-- CREA EL TABLE DEL MODAL PARA SELECIONAR RESPONSABLES -->
                <?php
                print "<table id='tblresponsable'class='display'>";
                print "<thead>";
                print "<tr>";
                print "<th class='id_oculto'>ID</th>";
                print "<th>Nombre</th>";
                print "<th>Cedula</th>";
                print "<th>Empresa</th>";
                print "</tr>";
                print "</thead>";
                print "<tbody>";
                for ($i=0; $i<count($responsables); $i++) {
                    print "<tr>";
                    print "<td class='id_oculto'>".$responsables[$i][0]."</td>";
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
                <h2>Seleccione Sala</h2>
            </div>
            <div id="sala-modal" class="modal-body">
                <!-- CREA EL TABLE DEL MODAL PARA SELECIONAR RESPONSABLES -->

            </div>
            <div class="modal-footer">
                <br>
            </div>
        </div>   
        <!--FINAL MODAL RESPONSABLE-->
    </div>

    <!-- MODAL VISITANTE -->
    <div id="ModalVisitante" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Seleccione los Visitantes a Autorizar</h2>
            </div>
            <div id="visitante-modal" class="modal-body">
                <!-- CREA EL TABLE DEL MODAL PARA SELECIONAR VISITANTES -->
            </div>
            <div class="modal-footer">
            <br>
            </div>
        </div>
    </div>

    <!-- MODAL DATACENTER -->
    <div id="ModalDataCenter" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Seleccione el Data Center</h2>
            </div>
            <div id="datacenter-modal" class="modal-body">
                <!-- CREA EL TABLE DEL MODAL PARA SELECIONAR DATACENTER -->
            </div>
            <div class="modal-footer">
            <br>
            </div>
        </div>
    </div>


    
<script type="text/javascript" language="javascript">
    //Se ejecuta al iniciar la pagina
    var iddatacenter=null;
    var idsala=null;
    var idresponsable=null;
    var recargar=0;
    var existeid = "<?php echo $id;?>";      
    var jSala=[];
    var jResponsable=[];
    var jVisitante=[]; 
    var longitudvisitanteform = "<?php if (isset($_GET['ID'])||isset($_GET['MOD'])) {
        echo count($visitanteformulario);
} else {
    echo 0;
}?>";
    // Obtiene el MODAL
    var modalVisitante = document.getElementById('ModalVisitante');    
    var modalResponsable = document.getElementById('ModalResponsable');     
    var modalSala = document.getElementById('ModalSala');
    var modalDataCenter = document.getElementById('ModalDataCenter');
    // Botón que abre el MODAL
    var btn = document.getElementById("btnagregavisitante");
    var inputResponsable = document.getElementById("txtresponsable");
    var inputSala = document.getElementById("selectsala");
    var inputDataCenter = document.getElementById("selectdatacenter");
    // Obtiene el <span> que  cierra el MODAL
    var span = document.getElementsByClassName("close")[0];
    var btnmod = <?php echo $btnmod;?>;

    $(document).ready( function () {  
        //RecargarSala();
        DataCenterDefault();
        MuestraBotonCorrecto();
        ExcluyeVisitanteCarga();
        $("#EnviaFormulario").css("background-color", "cc9900");
        if (existeid!=0){
            EstadoFormulario();  
            //FechaFormMod();
        }  
        else
            FechaFormNuevo();
        // OBTIENE EL CSS PARA LOS TABLES
        $('#tblresponsable').DataTable();          
        //$('#tblsala').DataTable();
        MuestraEstados();
        //Pone por default el color de los botones
        $("#btnInsertaFormulario").css("background-color", "cc9900");
        $("#btnModificaFormulario").css("background-color", "cc9900");

        // Cambia color del botón enviar segun estado del formulario.
        $('input[type=radio][name=estadoformulario]').change(function() {
            if (this.value == '0') {
                $("#btnInsertaFormulario").css("background-color", "cc9900");
                $("#btnModificaFormulario").css("background-color", "cc9900");
            }
            else if (this.value == '1') {
                $("#btnInsertaFormulario").css("background-color", "016DC4");
                $("#btnModificaFormulario").css("background-color", "016DC4");
            }
            else if (this.value == '2') {
                $("#btnInsertaFormulario").css("background-color", "firebrick");
                $("#btnModificaFormulario").css("background-color", "firebrick");
            }
        });
    
         // cierra el modal
        $(".close").click( function(){
            // muestra modal con info básica formulario. y btn cerrar./ x para cerrar
            $(".modal").css({ display: "none" });
        });

        //Oculta Mensaje de Formulario enciado por el Tramitante
        $('#formularioenviado').hide();

    } );

    //RECARGA LA TABLA SALAS
    function RecargarSala(){
        $.ajax({
            type: "POST",
            url: "class/Sala.php",
            data: {
                    action: "Cargar",
                    iddatacenter: iddatacenter
                  }
        })
        .done(function( e ) {
            
            $('#sala-modal').html("");
            $('#sala-modal').append("<table id='tblsala'class='display'>");
            var col="<thead><tr><th id='titulo_idsala'>ID</th><th>NOMBRE</th></tr></thead><tbody id='BodySala'></tbody>";
            $('#tblsala').append(col);
            // carga lista con datos.

            var data = jQuery.parseJSON(e);
            // Recorre arreglo.
            $.each(data, function(i, item) {
                var row="<tr>"+
                    "<td class='columna_idsala'>"+ item.id+"</td>" +
                    "<td>"+ item.nombre+"</td>"+
                "</tr>";
                $('#BodySala').append(row);  
                $('#titulo_idsala').hide();    
            })
            //OCULTA EL ID DE LA SALA
            $('.columna_idsala').hide();
            // formato tabla
            $('#tblsala').DataTable( {
                "order": [[ 1, "asc" ]]
            } );
        })    
        .fail(function(msg){
            alert("Error al Cargar Salas");
        });    
    }

    //PONE DEFAULT EN DATA CENTER
    function DataCenterDefault(){
        $.ajax({
            type: "POST",
            url: "class/DataCenter.php",
            data: {
                    action: "Default"
                  }
        })
        .done(function( e ) {
            var data= JSON.parse(e);
            document.getElementById("selectdatacenter").value = data[0][1];
            iddatacenter = data[0][0];
        })    
        .fail(function(msg){
            alert("Error al Cargar Data Center Default");
        });    
    }


    //SELECIONA EL DATACENTER Y LO INSERTA EN EL INPUT *********/                             
        $(document).on('click','#tbldatacenter tr', function(){        
            //SELECCIONA LA FILA Y LA INSERTA EN EL INPUT DC
            document.getElementById('selectdatacenter').value = $(this).find('td:nth-child(2)').html();
            //GUARDA EL ID EN LA GLOBAL IDDATACENTER
            iddatacenter = $(this).find('td:first').html();
            //CIERRA EL MODAL DATACENTER
            modalDataCenter.style.display = "none";
            //VACIA LA TBL DE LAS SALAS
            $('#sala-modal').html("");
            //VACIA EL INPUT DE LAS SALAS
            $("#selectsala").val("");
    });

    //Confirma no salvar cambios para volver al menu admin     
    $(document).on('click', '#btnatras', function (event) {
        swal({
            title: 'Volver al Menu Administrador?',
            text: "Esta acción no guardará el formulario!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Salir!',
            cancelButtonText: 'No, cancelar!',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger'
        }).then(function () {
            location.href='ListaFormulario.php';
        });    
    });

    //Establece la fecha de hoy a los datetme-local
    function FechaFormNuevo(){
        var today = new Date();
        var salida = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
        var hh = today.getHours();
        var hhs= today.getHours()+2;
        var min = today.getMinutes();

        if(dd<10){
            dd='0'+dd
        } 
        if(mm<10){
            mm='0'+mm
        } 
        if(hh<10){
            hh='0'+hh
        }
        if(min<10){
            min='0'+min
        }
        if(hhs<10){
            hhs='0'+hhs
        }
        if(hhs==24){
            hhs='00'
        }
        if(hhs==25){
            hhs='01'
        }
        if(hhs==26){
            hhs='02'
        }

        today = yyyy+'-'+mm+'-'+dd+'T'+hh+':'+min;
        salida = yyyy+'-'+mm+'-'+dd+'T'+hhs+':'+min;
        document.getElementById("fechaingreso").setAttribute("min", today);
        document.getElementById("fechasalida").setAttribute("min", today);
        document.getElementById("fechaingreso").value = today;
        document.getElementById("fechasalida").value = salida;
    }

    //Establece la fecha de hoy a los datetme-local
    function FechaFormMod(){
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
        var hh = today.getHours();
        var min = today.getMinutes();
        if(dd<10){
            dd='0'+dd
        } 
        if(mm<10){
            mm='0'+mm
        } 
        if(hh<10){
            hh='0'+hh
        }
        if(min<10){
            min='0'+min
        }
        today = yyyy+'-'+mm+'-'+dd+'T'+hh+':'+min;
        document.getElementById("fechaingreso").setAttribute("min", today);
        document.getElementById("fechasalida").setAttribute("min", today);
    }


    //Abre el modal con el evento click en el boton (+) , contruye la tabla visitante en el modal
    $('#btnagregavisitante').click(function() {
        modalVisitante.style.display = "block";
        var visitantereal =[];

        ExcluyeVisitante();    
        
        $.ajax({
            type: "POST",
            url: "class/Visitante.php",
            data: {
                    action: "Excluye",
                    visitanteexcluido: document.getElementById('visitanteexcluido').value
                  }
        })
        .done(function( e ) {
            $('#visitante-modal').html("");
            $('#visitante-modal').append("<table id='tblvisitante'class='display'>");
            var col="<thead><tr id=encabezado><th>Cedula</th><th>Nombre</th><th>Empresa</th></tr></thead><tbody id='BodyVisitante'></tbody>";
            $('#tblvisitante').append(col);

            visitantereal = JSON.parse(e);
            for (var i = 0; i < visitantereal.length; i++) {                
                var tr1="<tr>";
                var td1="<td>"+visitantereal[i][0] +"</td>";
                var td2="<td>"+visitantereal[i][1] +"</td>";
                var td3="<td>"+visitantereal[i][2] +"</td>";
                var tr2="</tr>";
                $('#BodyVisitante').append(tr1+td1+td2+td3+tr2);
            }
            $('#tblvisitante').DataTable();
        })    
        .fail(function(msg){
            alert("Error al cargar visitantes Modal");
        });
        
    });     

    //CONCATENA EL ARREGLO EN UN STRING, LO ASIGNA A UN TAG HIDDEN PARA PASAR POR POST ***/
    function ExcluyeVisitante() {     
        document.getElementById("visitanteexcluido").value = "";
        //**********MODIFICAR
        if(longitudvisitanteform!=0){
            for (var i = 0; i < jVisitante.length; i++) {
                    var element = jVisitante[i][0];
                    if(element==undefined)
                        element = jVisitante[i].id;
                if(i==0){
                    document.getElementById("visitanteexcluido").value += element;
                }
                else{
                    document.getElementById("visitanteexcluido").value += "," + element;
                }    
            }  
        }
        //**********NUEVO
        else{
             for (var i = 0; i < jVisitante.length; i++) {
                var element = jVisitante[i].id;
                if(i==0){
                    document.getElementById("visitanteexcluido").value += element;
                }
                else{
                    document.getElementById("visitanteexcluido").value += "," + element;
                }    
            }
        }
    } 

    //CONCATENA EL ARREGLO EN UN STRING, LO ASIGNA A UN TAG HIDDEN PARA PASAR POR POST ***/
    function ExcluyeVisitanteCarga() {     
        //**********MODIFICAR
        if(longitudvisitanteform!=0){    
            jVisitante = JSON.parse('<?php echo json_encode($visitanteformulario);?>');   
            for (var i = 0; i < jVisitante.length; i++) {
                var element = jVisitante[i][0];
                if(i==0){
                    document.getElementById("visitanteexcluido").value += element;
                }
                else{
                    document.getElementById("visitanteexcluido").value += "," + element;
                }    
            }  
        }
        //**********NUEVO
        else{
             for (var i = 0; i < jVisitante.length; i++) {
                var element = jVisitante[i].id;
                if(i==0){
                    document.getElementById("visitanteexcluido").value += element;
                }
                else{
                    document.getElementById("visitanteexcluido").value += "," + element;
                }    
            }
        }
    } 

    //BORRA FILA DE UN TABLE AL SELECCIONAR EL BOTÓN Y LO QUITA DEL ARREGLO *********/       
    $(document).on('click', '.borrar', function (event) {
        var ced = $(this).parents("tr").find("td").eq(0).text();
        for (var i = 0; i < jVisitante.length; i++) {
            if (jVisitante[i][0]==ced||jVisitante[i].id==ced)
                jVisitante.splice(i,1);                 
        }
        $(this).closest('tr').remove();
        ExcluyeVisitante();
    });

    //SELECION DE LAS LINEAS DEL MODAL **********************/                        
    $(document).on('click','#tblvisitante tr', function(){        
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
            if(jVisitante[jVisitante.length-1].id==undefined)
                return false;
            $("#tblvisitanteform").append(tb1+tr+td1+td2+td3+td4+tb2); 
            $('#imgflecha').removeClass('imagen');
            $('#imgflecha').addClass('imagenNO');
            $(this).css('display', 'none');
        }
    });

    //Carga los Data center
    $(document).on('click', '#selectdatacenter', function (event) {
        
        $.ajax({
            type: "POST",
            url: "class/DataCenter.php",
            data: {
                    action: "SeleccionarDataCenter",
                  }
        })
        .done(function( e ) {
            $('#datacenter-modal').html("");
            $('#datacenter-modal').append("<table id='tbldatacenter'class='display'>");
            var col="<thead><tr><th id='titulo_iddc'>ID</th><th>NOMBRE</th></thead><tbody id='BodyDataCenter'></tbody>";
            $('#tbldatacenter').append(col);
            // carga lista con datos.
            var data= JSON.parse(e);
            // Recorre arreglo.
            $.each(data, function(i, item) {
                var row="<tr>"+
                    "<td class='columna_iddc'>"+ item.id+"</td>" +
                    "<td>"+ item.nombre+"</td>" +
                "</tr>";
                $('#BodyDataCenter').append(row);  
                $('#titulo_iddc').hide();
                $('.columna_iddc').hide();       
            })
            // formato tabla
            $('#tbldatacenter').DataTable( {
                "order": [[ 1, "asc" ]]
            } );
        })    
        .fail(function(msg){
            alert("Error al Cargar Data Centers");
        });
    }); 

    //Abre el modal de responsables
    inputResponsable.onclick = function() {
        modalResponsable.style.display = "block";
    }
    //Abre el modal de Salas
    inputSala.onclick = function() {
        modalSala.style.display = "block";
        RecargarSala();
    }
    //Abre el modal Data Center
    inputDataCenter.onclick = function() {
        modalDataCenter.style.display = "block";
    }

    //Cierra el MODAL en la X
    span.onclick = function() {
        modalResponsable.style.display = "none";
        modalVisitante.style.display = "none";
        ///Borra la tabla 
        modalSala.style.display = "none";
        //Vacia el atg que contiene los visitantes excluidos
        document.getElementById("visitanteexcluido").value ="";
        $('#visitante-modal').html("");
    }

    //Cierra el MODAL en cualquier parte de la ventana
        window.onclick = function(event) {
        if (event.target == modalVisitante) {
            modalVisitante.style.display = "none";   
            document.getElementById("visitanteexcluido").value ="";
            $('#visitante-modal').html("");
        }
    }

    //Oculta o Muestra el DIV de estados del formualario
    function MuestraEstados(){
        var rol = "<?php echo $rol ?>";
        if (rol==1) {
            $('#estadosform').show();
        }else{
            $('#estadosform').hide();
            $('#btnatras').hide();
            $('#lblautorizador').hide();
            $('#txtautorizador').hide();
        }
    }

    //Mientras se digita en el Campo Motivo
    $('#motivovisita').on('keyup', function() {
        $("#motivovisita").css("border", "0px");
        document.getElementById('motivovisita').placeholder ="";
    });

    //Muestra u Oculta el numero del Formulario 
    function NumFormulario(){
        if (isset($_GET['ID'])) {
            document.getElementById("formnum").className = '';    
        }else{
            document.getElementById("formnum").className = 'hidden';    
        }
    }

    function MuestraBotonCorrecto(){
        
        $("#btnInsertaFormulario").hide();
        $("#btnModificaFormulario").hide();
        $("#EnviaFormulario").hide();
        if(btnmod==1){
            $("#btnInsertaFormulario").hide();
            $("#btnModificaFormulario").show();
        }
        else{
            $("#btnModificaFormulario").hide();
            $("#btnInsertaFormulario").show();
        }
    }

    //Valida Caracteres Especiales en el campo motivo 
    $('#motivovisita').keydown(function(e){
        if (e.keyCode == 226 ){
            document.getElementById('motivovisita').placeholder = "CARACTER INVÁLIDO";
            return false;
        } 
    });

    //Maneja el evento checked del estado del radio button formulario
    function EstadoFormulario(){        
        
        var estado = "<?php echo $estadoformulario; ?>";         
        if (estado==0) {
            document.getElementById("pendiente").checked = true;   
            document.getElementById("aprobado").checked = false;
            document.getElementById("denegado").checked = false; 
            $("#EnviaFormulario").css("background-color", "cc9900");
        }
        if(estado==1){
            document.getElementById("pendiente").checked = false;   
            document.getElementById("aprobado").checked = true;
            document.getElementById("denegado").checked = false; 
            $("#EnviaFormulario").css("background-color", "016DC4");
        }
        if(estado==2){
            document.getElementById("pendiente").checked = false;   
            document.getElementById("aprobado").checked = false;
            document.getElementById("denegado").checked = true; 
            $("#EnviaFormulario").css("background-color", "firebrick");
        }
    }

    //CONCATENA EL ARREGLO EN UN STRING, LO ASIGNA A UN TAG HIDDEN PARA PASAR POR POST ***/
    function EnviaVisitante() {
        for (var i = 0; i < jVisitante.length; i++) {
            var element = jVisitante[i].id;
            if(element==undefined)
                element = jVisitante[i][0];
            if(i==0){
                document.getElementById("visitantearray").value += element;
            }
            else{
                document.getElementById("visitantearray").value += "," + element;
            }             
        }   
        //valida si se han agregado visitantes a la tabla        
        var cantidadvisitante = document.getElementById("tblvisitanteform").rows.length;
        if(cantidadvisitante<2){
            $('#imgflecha').addClass('imagen');
            return false;
        }
    }   

        //INSERTA UN FORMULARIO, SI ESTA CCORRECTO REDIRECCIONA A LISAT FORMULARIO ***/
        $(document).on('click', '#btnInsertaFormulario', function (event) {
        $.ajax({
            type: "POST",
            url: "class/Formulario.php",
            data: {
                    action: "Insertar",
                    fechaingreso: document.getElementById('fechaingreso').value,
                    idsala: idsala,
                    fechasalida: document.getElementById('fechasalida').value,
                    placavehiculo: document.getElementById('placavehiculo').value,
                    detalleequipo: document.getElementById('detalleequipo').value,
                    motivovisita: document.getElementById('motivovisita').value,
                    idresponsable: idresponsable,
                    nombreautorizador: document.getElementById('txtautorizador').value,
                    nombretramitante: document.getElementById('txttramitante').value,
                    estado: $('input:radio[name=estadoformulario]:checked').val(),
                    rfc: document.getElementById('txtrfc').value,
                    visitante: document.getElementById('visitantearray').value
                  }
        })
        .done(function( e ) {
            var rol = "<?php echo $rol ?>";
            if (rol==1) 
                location.href='ListaFormulario.php?INS=1';
            else
                $('#formularioenviado').show();
                $('#principal').hide();
                //location.href='FormularioEnviado.php?';
            }
        )    
        .fail(function(msg){
            location.href='ListaFormulario.php?INS=0';
        });
    }); 


    $(document).on('click', '#btnModificaFormulario', function (event) {
        $.ajax({
            type: "POST",
            url: "class/Formulario.php",
            data: {
                    action: "Modificar",
                    fechaingreso: document.getElementById('fechaingreso').value,
                    fechasalida: document.getElementById('fechasalida').value,
                    nombretramitante: document.getElementById('txttramitante').value,
                    nombreautorizador: document.getElementById('txtautorizador').value,
                    idresponsable: idresponsable,
                    placavehiculo: document.getElementById('placavehiculo').value,
                    detalleequipo: document.getElementById('detalleequipo').value,
                    motivovisita: document.getElementById('motivovisita').value,
                    estado: $('input:radio[name=estadoformulario]:checked').val(),
                    idsala: idsala,
                    rfc: document.getElementById('txtrfc').value,
                    id: document.getElementById('idformulario').value,
                    visitante: document.getElementById('visitantearray').value
                  }
        })
        .done(function( e ) {
            location.href='ListaFormulario.php?INS=1';
        })    
        .fail(function(msg){
            alert("Error al Modificar Formulario");
        });
    });

    function CargarFormularioModificar(){
        $.ajax({
            type: "POST",
            url: "class/Formulario.php",
            data: {
                    action: "CargaMOD",
                    id: $_GET["MOD"];
                  }
        })
        .done(function( e ) {
            //CARGAR TODOS LOS CONTROLES CON LOS DATOS DEL FORMULARIO
            
        })    
        .fail(function(msg){
            alert("Error al Modificar Formulario");
        });    
    }



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
        idresponsable=$(this).find('td:first').html();
        ValidacionCorrecta();                       
    });

    //MODAL SALAS ********/
    /*$('#tblsala tr').on('click', function(){        
        $(this).toggleClass('selected');
        jSala.length = 0;
        $("#selectsala").val('');
        var data={
            "sala":$(this).find('td:first').html()
        };
        jSala.push(data); 
        $("#selectsala").val(jSala[jSala.length-1].sala);
        modalSala.style.display = "none";
        ValidacionCorrecta();
    });*/

    $(document).on('click','#tblsala tr', function(){
        $(this).toggleClass('selected');
        jSala.length = 0;
        $("#selectsala").val('');
        var data={
            "sala":$(this).find('td:nth-child(2)').html()
        };
        jSala.push(data); 
        $("#selectsala").val(jSala[jSala.length-1].sala);
        modalSala.style.display = "none";
        idsala = $(this).find('td:first').html();
        ValidacionCorrecta();
    });
    
    
    function ValidacionCorrecta() {
        $("#txtresponsable").css("border", "0px");
        //$("#txtresponsable").css("color", "black");
        document.getElementById('txtresponsable').placeholder = "CLICK";    
        $("#selectsala").css("border", "0px");
        //$("#selectsala").css("color", "black");
        document.getElementById('selectsala').placeholder = "CLICK";
    }

    $("#motivovisita" ).change(function() {
        $("#motivovisita").css("border", "0px");
        //$("#motivovisita").css("color", "black");
        //$("#motivovisita").css("background", "white");
        document.getElementById('motivovisita').placeholder = "8 Caracteres Mínimo";    
    });

</script>
</body>
</html>
