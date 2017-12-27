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

//Formulario - Cargar Datos en Formulario Ingreso para Modificar
include("class/Formulario.php");
$estadoformulario=0;
$formulario = new Formulario();
$id=0;
$largo=0;
$visitanteformulario=0;
$btnmod=3;
$idformulario=null;
if (isset($_GET['ID'])){
    $btnmod=1;
    $id=$_GET['ID'];
    $_SESSION['TEMP']=$id;
}
if (isset($_GET['MOD'])) {
    $btnmod=1;
    $idformulario = $_GET['MOD'];
    $id=$_GET['MOD'];
}

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
    <!--<meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <title>Control de Accesos</title>       
    <!-- CSS -->
    <link href="css/Estilo.css?v= <?php echo Globals::cssversion; ?>" rel="stylesheet" />  
    <link rel="stylesheet" href="css/datatables.css" type="text/css"/>        
    <link rel="stylesheet" href="css/Formulario.css" type="text/css"/>
    <link rel="stylesheet" href="css/sweetalert2.css" type="text/css"/>    
    <!--<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"/>    -->
    <!-- JS  -->
    <script src="js/jquery.js" type="text/jscript"></script>
 	<script src="js/datatables.js" type="text/javascript" charset="utf8"></script>
    <script src="js/Validaciones.js" languaje="javascript" type="text/javascript"></script> 
    <script src="js/sweetalert2.js"></script>
    <!--<script src="js/bootstrap.min.js"></script>-->
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
                </div>
                <div id="medioizq">                                  
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
                    <input type="button" id="btncopiar" class="nbtn_blue-sp-c" value="Copiar" onclick="Copiar()";>   
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
                            value="" required/>
                        </div>
                        <div class="cajainput">
                            <label for="txtresponsable" class="labelformat">Seleccione el Responsable</label></br>
                            <input type="text" id="txtresponsable" name="txtresponsable" class="input-field-form" placeholder="CLICK" readonly="readonly"
                            value="" required/>
                        </div>
                    </div>
                    <div id="caja">
                        <div class="cajainput">
                            <label for="fechasalida" class="labelformat">Fecha y hora Salida</label>
                            <input type="datetime-local" id="fechasalida" name="fechasalida" class="input-field-form" 
                            value="" required/> 
                        </div>
                        <div class="cajainput">
                            <label for="txttramitante" id="lbltxttramitante" class="labelformat">Tramitante</label>
                            <input type="text" id="txttramitante" name="txttramitante" class="input-field-form" readonly="readonly"
                            value="<?php if (!isset($_GET['ID']) && !isset($_GET['MOD'])) echo $usuario->nombre;?>"/>
                            <label id="lblautorizador" for="txtautorizador" class="labelformat">Autorizador</label>
                            <input type="text" id="txtautorizador" name="txtautorizador" class="input-field-form" readonly="readonly" 
                            value="<?php if (!isset($_GET['ID']) || !isset($_GET['MOD']) && $rol==1) echo $usuario->nombre;?>"/>
                        </div>
                    </div>
                    <div id="caja">
                        <div id="cajainput_tramitante">
                            <label for="selectdatacenter" class="labelformat">Seleccione Data Center</label></br>
                            <input type="text" id="selectdatacenter" name="selectsaladatacenter" placeholder="CLICK" class="input-field-form" readonly="readonly"
                            value="" required/>  
                        </div>                   
                        <div id="cajainput_autorizador">
                            <label for="selectsala" class="labelformat">Seleccione la Sala</label></br>
                            <input type="text" id="selectsala" name="selectsala" placeholder="CLICK" class="input-field-form" readonly="readonly"
                            value="" required/>
                        </div>
                    </div>
                </div>  
                <div id="medio">
                    <div id="tabla">
                       <div id="distribuciontabla">
                            <div id="listavisitanteform" style="text-transform:uppercase">
                                <!-- CREA EL TABLE QUE CARGA LOS VISITANTES AL formulario-->
                                
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
                                value=""/>   
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
                            <input id="idformulario" name="idformulario" type="hidden" value=""
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
                            value="" 
                            pattern="[\.,-_0-9áéíóúA-Za-z/\s/]*" maxlength="500" title="No se permiten caracteres especiales"/>
                            </div>
                        </div>      

                        <div class="cajainput2">
                        <div class="positionlabel">
                            <label for="detalleequipo" class="labelformat">Detalle Equipo</label>
                            </div>
                            <div class="positioninput">
                            <input type="text" id="detalleequipo" class="input-field-form" name="detalleequipo" 
                            value="" 
                            pattern="[\.,-_0-9áéíóúA-Za-z/\s/]*" maxlength="500" title="No se permiten caracteres especiales"/>
                            </div>
                        </div>

                        <div class="cajainput2">
                            <div class="positionlabel">
                                <label for="txtrfc" class="labelformat">RFC</label>
                            </div>
                            <div class="positioninput">
                                <input type="text" id="txtrfc" name="txtrfc" placeholder="" class="input-field-form" 
                                value="" 
                                pattern="[\.,-_0-9áéíóúA-Za-z/\s/]*" maxlength="10" title="No se permiten caracteres especiales"/>    
                                </div>
                        </div>  
                    </div>
                    <div id="cajainput3">
                        <label for="motivovisita" class="labelformat">Motivo Visita</label>
                        <input type="text" id="motivovisita" name="motivovisita" class="input-field-form"
                        value="" required 
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
                <input type="button" id="btnVisitante" class="linkButton" value="Nuevo" onclick="NuevoVisitante()";>  
                <h2>Seleccione los Visitantes a Autorizar</h2>
                <span class="close">&times;</span>
            </div>
            <div id="visitante-modal" class="modal-body" style="text-transform:uppercase">
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
        <div class="modal-content-datacenter">
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

    <!-- MODAL NUEVO VISITANTE -->
    <div id="ModalNuevoVisitante" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">                                
                <h2>Nuevo Visitante</h2>
                <span class="close">&times;</span>
            </div>
            <div id="mensajetop_display-secundario">
                <div id="mensajetop-secundario">
                    <span id="textomensaje-secundario"></span>
                </div>
            </div>
            <div id="nuevovisitante-modal" class="modal-body" style="text-transform:uppercase">
                <div id="form">
                    <form name="perfil" id='perfil' method="POST" >
                        <label for="cedula"><span class="campoperfil">Cédula / Identificación <span class="required">*</span></span>
                            <input autofocus type="text"  id="cedula"                                 
                                class="input-field" name="cedula" placeholder="0 0000 0000" title="Número de cédula separado con CEROS"  onkeypress="return isNumber(event)" required >                                
                        </label>
                        <label for="empresa"><span class="campoperfil">Empresa / Dependencia <span class="required">*</span></span>
                            <input type="text"   style="text-transform:uppercase"                                 
                                class="input-field" name="empresa" value="" id="empresa" required >
                        </label>
                        <label for="nombre"><span class="campoperfil">Nombre Completo <span class="required">*</span></span>
                            <input  required type="text" class="input-field" name="nombre" style="text-transform:uppercase" id="nombre"/>
                        </label>
                        <label for="permiso"><span class="campoperfil">Tiene permiso de Ingreso Anual?</span>
                            <br>
                            <input type="checkbox" name="permiso" id="permiso" class="input-field" >
                        </label>

                        <nav class="btnfrm">
                            <ul>
                                <li><button type="button" class="nbtn_blue" onclick="Guardar()" >Guardar</button></li>
                                <li><button type="button" class="nbtn_gray" onclick="Cerrar()" >Cerrar</button></li>
                            </ul>
                        </nav>  
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <br>
            </div>
        </div>
    </div>

<script type="text/javascript" language="javascript">
    //DATOS RELEVANTES
    var fecha_ingreso;
    var fecha_salida;
    var responsable;
    var datacenter;
    var sala;
    var visitantes;
    //Estado Formulario
    var estadoformulario = 0;
    // visitante
    var formReady = false;
    var id = "NULL";
    //SE EJECUTA AL INICIAR LA PAGINA
    var iddatacenter=null;
    var idsala=null;
    var idresponsable=null;
    var recargar=0;
    var existeid = "<?php echo $id;?>";      
    var jSala=[];
    var jResponsable=[];
    var jVisitante=[]; 
    var longitudvisitanteform = "<?php if (isset($_GET['ID'])||isset($_GET['MOD'])) { echo count($visitanteformulario);} else {echo 0;}?>";
    var idformulario = "<?php echo $idformulario;?>";
    var btnmod = <?php echo $btnmod;?>;
    var autorizador = "<?php echo $usuario->nombre;?>";
    // OBTIENE EL MODAL
    var modalVisitante = document.getElementById('ModalVisitante');    
    var modalResponsable = document.getElementById('ModalResponsable');     
    var modalSala = document.getElementById('ModalSala');
    var modalDataCenter = document.getElementById('ModalDataCenter');
    // BOTONES QUE ABREN EL MODAL
    var btn = document.getElementById("btnagregavisitante");
    var inputResponsable = document.getElementById("txtresponsable");
    var inputSala = document.getElementById("selectsala");
    var inputDataCenter = document.getElementById("selectdatacenter");
    // OBTIENE EL <span> QUE CIERRA EL MODAL
    var span = document.getElementsByClassName("close")[0];
    
    $(document).ready( function () {  
        MuestraBotonCorrecto();
        ExcluyeVisitanteCarga();
        MuestraEstados();

        if (existeid!=0){
            //MODIFICA FORMULARIO
            EstadoFormulario();  
            CargarFormularioModificar();
            CargaVisitantesFormulario();  
            CargaAutorizador();
        }  
        else{
            //FORMULARIO NUEVO
            FechaFormNuevo();
            DataCenterDefault();
            CreaTblVisitanteFormulario();
            // oculta btn copiar
            $("#btncopiar").css({ display: "none" });
        }
            
        // OBTIENE EL CSS PARA LOS TABLES
        $('#tblresponsable').DataTable({
                "order": [[ 1, "asc" ]],
                searching: false, 
                paging: false
        });          
        
        //Pone por default el color de los botones
        $("#EnviaFormulario").css("background-color", "cc9900");
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

        //vuelve a la lista de visitantes
        this.Cerrar = function(){
            $("#ModalNuevoVisitante").css({ display: "none" });
        }; 

        //valida cedula unica al perder el foco en el input cedula.
        $('#cedula').focusout(ValidaCedulaUnica);

    } );

    //NUEVO VISITANTE
    function NuevoVisitante(){
         // limpia valores.        
        id="NULL";
        $("#cedula").val("");
        $("#empresa").val("");
        $("#nombre").val("");
        $("#permiso")[0].checked = false;
        $("#cedula").css({
            "border": "1px solid #C2C2C2"
        });
        $("#nombre").css({
            "border": "1px solid #C2C2C2"
        });
        $("#empresa").css({
            "border": "1px solid #C2C2C2"
        });
        $("#ModalNuevoVisitante").css({ display: "block" }); 
    }
    
    function validarForm(){
        ValidaCedulaUnica();
        //   
        if($("#cedula").val()=="")
        {
            $("#cedula").css("border", "0.3px solid firebrick");
            document.getElementById('cedula').placeholder = "REQUERIDO";
            $("#cedula").focus();
            return false;
        }        
        else if($("#cedula").val().length<8)
        {
            $("#cedula").css("border", "0.3px solid firebrick");
            // mensaje
            muestraError_Visita("Formato de cedula: Mínimo 8 digitos sin guiones ni espacios");
            return false;
        }
        //
        if($("#empresa").val()=="")
        {
            $("#empresa").css("border", "0.3px solid firebrick");
            document.getElementById('empresa').placeholder = "REQUERIDO";
            $("#empresa").focus();
            return false;
        }
        //
        if($("#nombre").val()=="")
        {
            $("#nombre").css("border", "0.3px solid firebrick");
            document.getElementById('nombre').placeholder = "REQUERIDO";
            $("#nombre").focus();
            return false;
        }
        else if($("#nombre").val().length<10)
        {
            $("#nombre").css("border", "0.3px solid firebrick");
            // mensaje
            muestraError_Visita("El nombre del visitante debe tener mínimo 10 caracteres");
            return false;
        }
        if(!formReady){
            return false;
        } 
        //        
        return true;
    };

    // Muestra errores en ventana
    function muestraError_Visita(msg){        
        $("#textomensaje-secundario").text(msg);
        $("#mensajetop-secundario").css("background-color", "firebrick");
        $("#mensajetop-secundario").css("color", "white");    
        $("#mensajetop-secundario").css("visibility", "visible");
        $("#mensajetop-secundario").slideDown("slow");
        $("#mensajetop-secundario").slideDown("slow").delay(3000).slideUp("slow");
    };

    // Muestra información en ventana
    function muestraInfo(){             
        // mesaje NUEVO formulario copiado!
        swal({
                title: 'Visitante Agregado!!',
                type: 'info',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Continuar',
                cancelButtonText: 'No, cancelar!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger'
            }).then(function () {
                $("#ModalNuevoVisitante").css({ display: "none" });  
                CargarListaVisitantes();
        });  

    };

    //Valida cedula unica.
    function ValidaCedulaUnica(){    
        $.ajax ({
            type: "POST",
            url: "class/Visitante.php",
            data: { 
                action: "ValidaCedulaUnica",
                cedula:  $("#cedula").val(),
                nombre: $("#nombre").val(),
            }
        })
        .done(function( e ) {    
            if(e=="invalida"){
                $("#cedula").css({
                    "border-color": "firebrick",
                    "border-width": "0.3px"
                });
                $("#cedula").focus();
                muestraError_Visita('Número de cédula duplicado');         
                formReady=false;   
            }
            else {
                $("#cedula").css({
                    "border-color": "green",
                    "border-width": "0.3px"
                });
                formReady=true;
            }
        })
        .fail(function( e ) {    
            // ...
            formReady=false;
        });
    };

    //Guarda el nuevo visitante
    function Guardar(){   
        // Ajax: insert / Update.
        if(!validarForm())
            return false;
        var miAccion= id=='NULL' ? 'Insertar' : 'Modificar';
        $.ajax({
            type: "POST",
            url: "class/Visitante.php",
            data: { 
                action: miAccion,  
                idvisitante: id,              
                cedula:  $("#cedula").val(),
                nombre: $("#nombre").val(),
                empresa: $("#empresa").val(),
                permiso: $("#permiso")[0].checked
            }
        })
        .done(muestraInfo)
        .fail(function( e ) {               
            swal({
                title: 'Error!!',
                type: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Continuar',
                cancelButtonText: 'No, cancelar!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger'
            }).then(function () {
                $(".Modal").css({ display: "none" });  
            });  
        });
    };  

    //COPIA FORM
    function Copiar(){        
        // copiar datos generales del form. Fecha actual + 2h    
        FechaFormNuevo();
        EnviaVisitante();
        $.ajax({
            type: "POST",
            url: "class/Formulario.php",
            data: {
                action: "Insertar",
                iscopy: "1",
                fechaingreso: document.getElementById('fechaingreso').value,
                idsala: idsala,
                fechasalida: document.getElementById('fechasalida').value,
                placavehiculo: document.getElementById('placavehiculo').value,
                detalleequipo: document.getElementById('detalleequipo').value,
                motivovisita: document.getElementById('motivovisita').value,
                idresponsable: idresponsable,
                nombreautorizador: document.getElementById('txtautorizador').value,
                nombretramitante: document.getElementById('txttramitante').value,
                estado: 0,
                rfc: document.getElementById('txtrfc').value,
                visitante: document.getElementById('visitantearray').value
            }
        })
        .done(function( e ) {            
            // mesaje NUEVO formulario copiado!
            swal({
                title: 'Formulario Copiado!!',
                type: 'info',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Continuar',
                cancelButtonText: 'No, cancelar!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger'
            }).then(function () {
                location.href= "FormularioIngreso.php?MOD=" + e;  
            });    
                      
        })    
        .fail(function(msg){
            location.href='ListaFormulario.php?INS=0';
        });
        // copiar lista de visitantes en nuevo form
        // cargar ventana con info y nuevo id.

    };

    //CARGA UN FORMULARIO FINALIZADO COMO SOLO LECTURA
    function SoloLectura(){
        document.getElementById('btnagregavisitante').disabled=true;
        document.getElementById('btnModificaFormulario').disabled=true;
        document.getElementById("fechaingreso").readOnly = true;
        document.getElementById("fechasalida").readOnly = true;
        document.getElementById("placavehiculo").readOnly = true;
        document.getElementById("detalleequipo").readOnly = true;
        document.getElementById("txtrfc").readOnly = true;
        document.getElementById("motivovisita").readOnly = true;
        document.getElementById("pendiente").checked = false;
        document.getElementById("aprobado").checked = false;
        document.getElementById("denegado").checked = false;
        document.getElementById("pendiente").disabled = false;
        document.getElementById("aprobado").disabled = false;
        document.getElementById("denegado").disabled = false;
        $('#txtresponsable').removeAttr('onclick');
    }

    //CARGA EL AUTORIZADOR AL FORMULARIO
    function CargaAutorizador(){
        if (document.getElementById('txtautorizador').value==null)
            document.getElementById('txtautorizador').value = autorizador;
    }

    //RECARGA LA TABLA DATACENTER
    function RecargarSalaporDataCenter(){
        $.ajax({
            type: "POST",
            url: "class/Sala.php",
            data: {
                    action: "CargarporDataCenter",
                    nombredatacenter: document.getElementById('selectdatacenter').value
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
                "order": [[ 1, "asc" ]],
                searching: false, 
                paging: false
            } );
        })    
        .fail(function(msg){
            alert("Error al Cargar Salas");
        });    
    }

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
                "order": [[ 1, "asc" ]],
                searching: false, 
                paging: false
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
            document.getElementById("selectdatacenter").value = data[0].nombre;
            iddatacenter = data[0].id;
        })    
        .fail(function(msg){
            alert("Error al Cargar Data Center Default");
        });    
    }

    //SELECIONA EL DATACENTER Y LO INSERTA EN EL INPUT                            
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

    //CONFIRMA NO SALVAR CAMBIOS PARA VOLVER AL MENU ADMIN
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

    //ESTABLECE LA FECHA DE HOY A LOS DATETIME LOCAL CUANDO SE CREA UN FORMULARIO NUEVO
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
        //SUMA UN DIA A LA FECHA DE SALIDA 
        if(hh>=22)
            salida = yyyy+'-'+mm+'-'+(dd+1)+'T'+hhs+':'+min;
        else
            salida = yyyy+'-'+mm+'-'+dd+'T'+hhs+':'+min;
        document.getElementById("fechaingreso").setAttribute("min", today);
        document.getElementById("fechasalida").setAttribute("min", today);
        document.getElementById("fechaingreso").value = today;
        document.getElementById("fechasalida").value = salida;
    }

    //ESTABLECE LA FECHA DE HOY A LOS DATETIME LOCAL CUANDO SE MODIFICA UN FORMULARIO
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

    //ABRE EL MODAL EN EL BOTON (+), CONSTRUYE LA TABLA VISITANTE MODAL
    $('#btnagregavisitante').click(function() {
        modalVisitante.style.display = "block";
        var visitantereal =[];

        ExcluyeVisitante();    
        
        CargarListaVisitantes();
        
    });     

    function CargarListaVisitantes(){
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
            var col="<thead><tr id=encabezado><th id='titulo_idvisitante'>Id</th><th>Cedula</th><th>Nombre</th><th>Empresa</th></tr></thead><tbody id='BodyVisitante'></tbody>";
            $('#tblvisitante').append(col);

            visitantereal = JSON.parse(e);
            for (var i = 0; i < visitantereal.length; i++) {                
                var tr1="<tr>";
                var td1="<td class='idvisitante'>"+visitantereal[i][0] +"</td>";
                var td2="<td>"+visitantereal[i][1] +"</td>";
                var td3="<td>"+visitantereal[i][2] +"</td>";
                var td4="<td>"+visitantereal[i][3] +"</td>";
                var tr2="</tr>";
                $('#BodyVisitante').append(tr1+td1+td2+td3+td4+tr2);
                $('.idvisitante').hide();
                $('#titulo_idvisitante').hide();
            }
            $('#tblvisitante').DataTable();
        })    
        .fail(function(msg){
            alert("Error al cargar visitantes Modal");
        });
    };

    //CONCATENA EL ARREGLO EN UN STRING, LO ASIGNA A UN TAG HIDDEN PARA PASAR POR POST
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

    //CONCATENA EL ARREGLO EN UN STRING, LO ASIGNA A UN TAG HIDDEN PARA PASAR POR POST
    function ExcluyeVisitanteCarga() {     
        //**********MODIFICAR
        if(longitudvisitanteform!=0){        
            $.ajax({
                type: "POST",
                url: "class/Formulario.php",
                data: {
                        action: "CargaVisitantesFORM",
                        id: idformulario
                    }
            })
            .done(function( e ) {
                
                jVisitante = JSON.parse(e);
                
            })    
            .fail(function(msg){
                alert("Error al Cargar los Visitantes a Excluir");
            });    
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

    //BORRA FILA DE UN TABLE AL SELECCIONAR EL BOTÓN Y LO QUITA DEL ARREGLO      
    $(document).on('click', '.borrar', function (event) {
        if(estadoformulario!='3')
        {
            var ced = $(this).parents("tr").find("td").eq(0).text();
            for (var i = 0; i < jVisitante.length; i++) {
                if (jVisitante[i][0]==ced||jVisitante[i].id==ced)
                    jVisitante.splice(i,1);                 
            }
            $(this).closest('tr').remove();
            ExcluyeVisitante();
        }
    });

    //SELECIONA LOS REGISTROS DEL MODAL Y LOS CARGA EN tblvisitanteform                        
    $(document).on('click','#tblvisitantex tr', function(){        
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

    //CARGA LOS DATACENTERS AL INPUT SELECT
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
                "order": [[ 1, "asc" ]],
                searching: false, 
                paging: false
            } );
        })    
        .fail(function(msg){
            alert("Error al Cargar Data Centers");
        });
    }); 

    //ABRE EL MODAL RESPONSABLES
    inputResponsable.onclick = function() {
        if(estadoformulario!='3')
            modalResponsable.style.display = "block";
    }
    //ABRE EL MDOAL SALA
    inputSala.onclick = function() {
        if(estadoformulario!='3')
        {
            modalSala.style.display = "block";
            if (existeid!=0){
                //MODIFICA FORMULARIO
                RecargarSalaporDataCenter();
            }  
            else{
                //FORMULARIO NUEVO
                RecargarSala();
            }
        }
    }
    //ABRE EL MODAL DATACENTER
    inputDataCenter.onclick = function() {
        if(estadoformulario!='3')
            modalDataCenter.style.display = "block";
    }

    //CIERRA EL MODAL EN LA X Y VACIA LOS VISITANTES EXCLUIDOS
    span.onclick = function() {
        modalResponsable.style.display = "none";
        modalVisitante.style.display = "none";
        ///Borra la tabla 
        modalSala.style.display = "none";
        //Vacia el atg que contiene los visitantes excluidos
        document.getElementById("visitanteexcluido").value ="";
        $('#visitante-modal').html("");
    }

    //CIERRA EL MODAL EN CUALQUIER PARTE DE LA VENTANA
    window.onclick = function(event) {
        if (event.target == modalVisitante) {
            modalVisitante.style.display = "none";   
            document.getElementById("visitanteexcluido").value ="";
            $('#visitante-modal').html("");
        }
    }

    //OCULTA O MUESTRA EL DIV DE ESTADOS DEL FORMULARIO
    function MuestraEstados(){
        var rol = "<?php echo $rol ?>";
        if (rol==1) {
            $('#estadosform').show();
            $('#txttramitante').hide();
            $('#lbltxttramitante').hide();
        }else{
            $('#estadosform').hide();
            //$('#btnatras').hide();
            $('#lblautorizador').hide();
            $('#txtautorizador').hide();
            $('#txttramitante').show();
            $('#lbltxttramitante').show();
        }
    }

    //CAMBIA EL CSS DEL CAMPO MOTIVO MIENTRAS SE DIGITA
    $('#motivovisita').on('keyup', function() {
        $("#motivovisita").css("border", "0px");
        document.getElementById('motivovisita').placeholder ="";
    });

    //MUESTRA U OCULTA EL NUMERO DE FORMUARIO
    function NumFormulario(){
        if (isset($_GET['ID'])) {
            document.getElementById("formnum").className = '';    
        }else{
            document.getElementById("formnum").className = 'hidden';    
        }
    }

    //ELIJE EL BOTON PARA INSERTAR O MODIFICAR
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

    //MANEJA EL EVENTO CHECHED DEL ESTADO DEL RADIO BUTTON DEL FORMULARIO
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

    //CONCATENA EL ARREGLO EN UN STRING, LO ASIGNA A UN TAG HIDDEN PARA PASAR POR POST
    function EnviaVisitante() {
        $('#novisitante').remove();
        document.getElementById("visitantearray").value = null;
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
            $('#listavisitanteform').append("<div id=novisitante><label for=imgflecha style=opacity:0.5>Inserte Visitante</label><br><br><img id=imgflecha src=img/flecha-error.png></div>");
            $('#imgflecha').addClass('imagen');
            return false;
        }
    }   

    //INSERTA UN FORMULARIO, SI ESTA CCORRECTO REDIRECCIONA A LISAT FORMULARIO
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
            location.href='ListaFormulario.php?INS=1';
        })    
        .fail(function(msg){
            location.href='ListaFormulario.php?INS=0';
        });
    }); 

    //EVENTO DEL BOTON MODIFICAR FORMULARIO
    $(document).on('click', '#btnModificaFormulario', function (event) {
        var rol = "<?php echo $rol ?>";
        var visitantes_str = document.getElementById('visitantearray').value;
        var visitantes_actuales = visitantes_str.split(",");
        var cont=0;
        if(visitantes.length == visitantes_actuales.length)
        {
            for (x=0;x<visitantes.length;x++) 
            { 
                for (y=0;y<visitantes_actuales.length;y++) 
                { 
                    if (visitantes[x][0] == visitantes_actuales[y]) 
                    { 
                        cont++;       
                    } 
                } 
            } 
        }
        if((fecha_ingreso != document.getElementById('fechaingreso').value ||
           fecha_salida != document.getElementById('fechasalida').value ||
           responsable != document.getElementById('txtresponsable').value ||
           sala != document.getElementById('selectsala').value ||
           visitantes.length != visitantes_actuales.length ||
           cont != visitantes_actuales.length) && rol ==2)
            ModificaFormularioTramitante();
        else
            ModificaFormularioAutorizador();
    });

    //MODIFICA UN FORMULARIO REALIZADO POR UN AUTORIZADOR
    function ModificaFormularioAutorizador(){
        $.ajax({
                type: "POST",
                url: "class/Formulario.php",
                data: {
                        action: "Modificar",
                        fechaingreso: document.getElementById('fechaingreso').value,
                        fechasalida: document.getElementById('fechasalida').value,
                        nombretramitante: document.getElementById('txttramitante').value,
                        nombreautorizador: document.getElementById('txtautorizador').value,
                        nombreresponsable: document.getElementById('txtresponsable').value,
                        placavehiculo: document.getElementById('placavehiculo').value,
                        detalleequipo: document.getElementById('detalleequipo').value,
                        motivovisita: document.getElementById('motivovisita').value,
                        estado: $('input:radio[name=estadoformulario]:checked').val(),
                        nombresala: document.getElementById('selectsala').value,
                        rfc: document.getElementById('txtrfc').value,
                        id: idformulario,
                        visitante: document.getElementById('visitantearray').value
                    }
            })
            .done(function( e ) {
                location.href='ListaFormulario.php?INS=1';
            })    
            .fail(function(msg){
                alert("Error al Modificar Formulario");
            });
    }

    //MODIFICA FORMULARIO TRAMITANTE EN CASO DE QUE SE CAMBIE UN DATO IMPORTANTE,
    //SE CAMBIA EL ESTADO A PENDEINTE
    function ModificaFormularioTramitante(){
        $.ajax({
                type: "POST",
                url: "class/Formulario.php",
                data: {
                        action: "Modificar",
                        fechaingreso: document.getElementById('fechaingreso').value,
                        fechasalida: document.getElementById('fechasalida').value,
                        nombretramitante: document.getElementById('txttramitante').value,
                        nombreautorizador: document.getElementById('txtautorizador').value,
                        nombreresponsable: document.getElementById('txtresponsable').value,
                        placavehiculo: document.getElementById('placavehiculo').value,
                        detalleequipo: document.getElementById('detalleequipo').value,
                        motivovisita: document.getElementById('motivovisita').value,
                        estado: 0,
                        nombresala: document.getElementById('selectsala').value,
                        rfc: document.getElementById('txtrfc').value,
                        id: idformulario,
                        visitante: document.getElementById('visitantearray').value
                    }
            })
            .done(function( e ) {
                location.href='ListaFormulario.php?INS=1';
            })    
            .fail(function(msg){
                alert("Error al Modificar Formulario");
            });    
    }

    //CARGAR TODOS LOS CONTROLES CON LOS DATOS DEL FORMULARIO
    function CargarFormularioModificar(){
        $.ajax({
            type: "POST",
            url: "class/Formulario.php",
            data: {
                    action: "CargaMOD",
                    id: idformulario
                  }
        })
        .done(function( e ) {
            var data= JSON.parse(e);
            $('#lblnumeroform').val(data[0]['consecutivo']);
            $('#motivovisita').val(data[0]['motivovisita']);
            $('#fechaingreso').val(data[0]['fechaingreso']);
            $('#fechasalida').val(data[0]['fechasalida']);
            $('#txttramitante').val(data[0]['nombretramitante']);
            //SI ES VACIO PONE AL USUARIO ACTUAL COMO ATORIZADOR
            if(data[0]['nombreautorizador']!=null)
                $('#txtautorizador').val(data[0]['nombreautorizador']);
            $('#txtresponsable').val(data[0]['nombreresponsable']);
            idresponsable= data[0]['idresponsable'];
            $('#selectsala').val(data[0]['nombresala']);
            idsala= data[0]['idsala'];
            $('#placavehiculo').val(data[0]['placavehiculo']);
            $('#detalleequipo').val(data[0]['detalleequipo']);
            $('#txtrfc').val(data[0]['rfc']);
            $('#selectdatacenter').val(data[0]['datacenter']);
            
            if (data[0]['idestado']==0) {
            document.getElementById("pendiente").checked = true;   
            document.getElementById("aprobado").checked = false;
            document.getElementById("denegado").checked = false; 
            $("#EnviaFormulario").css("background-color", "cc9900");
            $("#btnModificaFormulario").css("background-color", "cc9900");
            }
            if(data[0]['idestado']==1){
            document.getElementById("pendiente").checked = false;   
            document.getElementById("aprobado").checked = true;
            document.getElementById("denegado").checked = false; 
            $("#EnviaFormulario").css("background-color", "016DC4");
            $("#btnModificaFormulario").css("background-color", "016DC4");
            }
            if(data[0]['idestado']==2){
            document.getElementById("pendiente").checked = false;   
            document.getElementById("aprobado").checked = false;
            document.getElementById("denegado").checked = true; 
            $("#EnviaFormulario").css("background-color", "firebrick");
            $("#btnModificaFormulario").css("background-color", "firebrick");
            }
            if(data[0]['idestado']==3){
                SoloLectura();
                estadoformulario = data[0]['idestado'];
            }

            //Datos importantes que no deben cambiar
            fecha_ingreso = data[0]['fechaingreso'];
            fecha_salida = data[0]['fechasalida'];
            responsable = data[0]['nombreresponsable'];
            datacenter = data[0]['datacenter'];
            sala = data[0]['nombresala'];

        })    
        .fail(function(msg){
            alert("Error al Modificar Formulario");
        });    
    }

    //RECARGA LA TABLA CON LOS VISITANTES POR FORMULARIO AJAX
    function CargaVisitantesFormulario(){
        $.ajax({
            type: "POST",
            url: "class/Formulario.php",
            data: {
                    action: "CargaVisitantesFORM",
                    id: idformulario
                  }
        })
        .done(function( e ) {
            $('#listavisitanteform').html("");
            $('#listavisitanteform').append("<table id='tblvisitanteform'class='display'>");
            var col="<thead><tr class='filatitulo'><th id='titulo_idvisform'>ID</th><th>CEDULA</th><th>NOMBRE</th><th>EMPRESA</th><th>ELIMINAR</th></tr></thead><tbody id='BodyVisintantesForm'></tbody>";
            $('#tblvisitanteform').append(col);
            // carga lista con datos.
            var data= JSON.parse(e);
            visitantes = data;
            // Recorre arreglo.
            $.each(data, function(i, item) {
                var row="<tr class='fila'>"+
                    "<td class='columna_idvisform'>"+ item.id+"</td>" +
                    "<td>"+ item.cedula+"</td>" +
                    "<td>"+ item.nombre + "</td>"+
                    "<td>"+ item.empresa + "</td>"+
                    "<td><img id=imgdelete src=img/file_delete.png class=borrar href='EnviaResponsable.php'></td>"+
                "</tr>";
                $('#BodyVisintantesForm').append(row);  
                $('#titulo_idvisform').hide();
                $('.columna_idvisform').hide();       
            })
        })    
        .fail(function(msg){
            alert("Error al Cargar la lista de Responsables");
        });    
    }

    //CREA LA TABLA DE VISITANTES DEL FORMULARIO
    function CreaTblVisitanteFormulario(){
        $('#listavisitanteform').append("<table id='tblvisitanteform'class='display'>");
        var col="<thead><tr class='filatitulo'><th id='titulo_idvisform'>ID</th><th>CEDULA</th><th>NOMBRE</th><th>EMPRESA</th><th>ELIMINAR</th></tr></thead><tbody id='BodyVisintantesForm'></tbody>";
        $('#tblvisitanteform').append(col);
        $('#titulo_idvisform').hide();
    }

    //SELECION DE LAS LINEAS DEL MODAL VISITANTE                       
    $(document).on('click','#tblvisitante tr', function(){        
        var data={
            "id":$(this).find('td:first').html(),
            "cedula":$(this).find('td:nth-child(2)').html(),
            "nombre":$(this).find('td:nth-child(3)').html(),
            "empresa":$(this).find('td:nth-child(4)').html()
        };

        var result = $.grep(jVisitante, function(e){  return e.id== data.id; });
        if (result.length  == 0) { // El visitante no esta en la lista
            jVisitante.push(data);

            var row="<tr class='fila'>"+
                    "<td class='columna_idvisform'>"+ jVisitante[jVisitante.length-1].id+"</td>" +
                    "<td>"+ jVisitante[jVisitante.length-1].cedula+"</td>" +
                    "<td>"+ jVisitante[jVisitante.length-1].nombre + "</td>"+
                    "<td>"+ jVisitante[jVisitante.length-1].empresa + "</td>"+
                    "<td><img id=imgdelete src=img/file_delete.png class=borrar href='EnviaResponsable.php'></td>"+
                "</tr>";
                $('#BodyVisintantesForm').append(row); 
                $('#titulo_idvisform').hide();
                $('.columna_idvisform').hide();
            if(jVisitante[jVisitante.length-1].id==undefined)
                return false;
            //$('#imgflecha').removeClass('imagen');
            //$('#imgflecha').addClass('imagenNO');
            $(this).css('display', 'none');
            $('#novisitante').html("");
        }
    });

    //SELECCION MODAL RESPONSABLES
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

    //SELECCION MODAL SALA
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
    
    //VALIDA RESPONSABLE Y SALA, BORDE ROJOS
    function ValidacionCorrecta() {
        $("#txtresponsable").css("border", "0.3px solid #C2C2C2");
        document.getElementById('txtresponsable').placeholder = "CLICK";    
        $("#selectsala").css("border", "0.3px solid #C2C2C2");
        document.getElementById('selectsala').placeholder = "CLICK";
    }

    //DEVUELVE EL CSS A MOTIVO DE LA VISITA
    $("#motivovisita" ).change(function() {
        $("#motivovisita").css("color", "#EDEDED");
        $("#motivovisita").css("border", "0.3px solid #C2C2C2");
        //document.getElementById('motivovisita').placeholder = "8 Caracteres Mínimo";    
    });
    
    //DEVUELVE EL CSS A LAS FECHAS
    $("#fechaingreso" ).change(function() {
        $("#fechaingreso").css("color", "#EDEDED");
        $("#fechasalida").css("color", "#EDEDED");
        $("#fechaingreso").css("border", "0.3px solid #C2C2C2");
        $("#fechasalida").css("border", "0.3px solid #C2C2C2");
        //document.getElementById('motivovisita').placeholder = "8 Caracteres Mínimo";    
    });

    //DEVUELVE EL CSS A LAS FECHAS
    $("#fechasalida" ).change(function() {
        $("#fechaingreso").css("color", "#EDEDED");
        $("#fechasalida").css("color", "#EDEDED");
        $("#fechaingreso").css("border", "0.3px solid #C2C2C2");
        $("#fechasalida").css("border", "0.3px solid #C2C2C2");
        //document.getElementById('motivovisita').placeholder = "8 Caracteres Mínimo";    
    });

    //VALIDA CARACTERES ESPECIALES EN EL CAMPO MOTIVO
    $('#motivovisita').keydown(function(e){
        if (e.keyCode == 226 ){
            document.getElementById('motivovisita').placeholder = "CARACTER INVÁLIDO";
            return false;
        } 
    });

</script>
</body>
</html>
