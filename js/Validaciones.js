$(document).ready(inicio);

function inicio() {
    
    $("#enviar").click(onValidaInicio);
    $("#EnviaNuevoPerfil").click(onValidaNuevoPerfil);
    $("#EnviaInfoVisita").click(onValidaInfoVisita);
    $("#btnInsertaFormulario").click(onValidaFormulario);
    $("#btnModificaFormulario").click(onValidaFormulario);
    $("#btnInsertaResponsable").click(onValidaResponsable);
    $("#btnModificaResponsable").click(onValidaResponsable);
    
}

this.MuestraMensajeTarjeta= function(){    
    $( ".dialog-message" ).dialog({
        modal: true,
        closeOnEscape: false,
        open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); },
        buttons: {
            Yes: function() {
                $( this ).dialog( "close" );
                document.getElementById("datos").submit();
                return true;
            },
            No: function() {
                $( this ).dialog( "close" );
                return false;
            }
        }
    });
};

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function onValidaFormulario() {
    EnviaVisitante();
    var sala = document.getElementById('selectsala').value;
    var responsable = document.getElementById('txtresponsable').value;
    var motivo = document.getElementById("motivovisita").value;
    var placa =  document.getElementById('placavehiculo').value;
    var equipo = document.getElementById('detalleequipo').value;
    var rfc = document.getElementById('txtrfc').value;
    var visitante = document.getElementById("visitantearray").value;
    var fechaingreso = document.getElementById("fechaingreso").value;
    var fechasalida = document.getElementById("fechasalida").value;

    //MUESTRA LA PALABRA REQUERIDO SI EL RESPOSANBLE ESTA VACIO
    if (responsable == ""){
        $("#txtresponsable").css("border", "2px solid red");
        document.getElementById('txtresponsable').placeholder = "REQUERIDO";
        //alert("Debe de asignar un responsable!");
        return false;
    }
    
    //MUESTRA LA PALABRA REQUERIDO SI EL SALA ESTA VACIO
    if (sala == ""){
        $("#selectsala").css("border", "2px solid red");
        document.getElementById('selectsala').placeholder = "REQUERIDO";
        return false;
    }

    //MUESTRA LA PALABRA REQUERIDO SI EL MOTIVO ESTA VACIO
    if(motivo == ""){
        $("#motivovisita").css("border", "2px solid red");
        document.getElementById('motivovisita').placeholder = "REQUERIDO";
        return false;
    }

    //MUESTRA UNA ALERTA SI HAY MENOS DE 8 CARACTERES
    if(motivo.length<8){
        $('#motivovisita').val('');
        $("#motivovisita").css("border", "2px solid red");
        document.getElementById('motivovisita').placeholder = "8 CARACTERES MÍNIMO";
        return false;
    }

    //MUESTRA LA PALABRA REQUERIDO SI EL VISITANTE ESTA VACIO
    if(visitante == ""){
        return false;
    }

    //MUESTRA ALERTA SI LAS FECHAS NO SON VALIDAS
    if(ComparaFechas(fechaingreso,fechasalida)==false){
        $("#fechaingreso").css("border", "2px solid red");
        $("#fechaingreso").css("color", "red");
        $("#fechasalida").css("border", "2px solid red");
        $("#fechasalida").css("color", "red");
        document.getElementById('fechaingreso').placeholder = "FECHA INVALIDA";
        document.getElementById('fechasalida').placeholder = "FECHA INVALIDA";
        return false;
    }
}

function ComparaFechas(Fecha_1,Fecha_2){
    //Resultado < 0 return false else return true, utilizar Diff
    var ms = moment(Fecha_2,"DD/MM/YYYYTHH:mm:ss").diff(moment(Fecha_1,"DD/MM/YYYYTHH:mm:ss"));
    if (ms<=0)
        return false;
    else
        return true;
}

function onValidaResponsable() {
    var nombre = document.getElementById('txtnombre').value;
    var cedula = document.getElementById('txtcedula').value;
    var empresa = document.getElementById("txtempresa").value;

    if (nombre == ""){
        $("#txtnombre").css("border", "2px solid red");
        $("#txtnombre").css("color", "red");
        document.getElementById('txtnombre').placeholder = "REQUERIDO";
        //alert("Debe de asignar un responsable!");
        return false;
    }
    if (cedula == ""){
        $("#txtcedula").css("border", "2px solid red");
        $("#txtcedula").css("color", "red");
        document.getElementById('txtcedula').placeholder = "REQUERIDO";
        return false;
    }
    if(empresa == ""){
        $("#txtempresa").css("border", "2px solid red");
        $("#txtempresa").css("color", "red");
        document.getElementById('txtempresa').placeholder = "REQUERIDO";
        return false;
    }
}



function onValidaInicio() {
    var cedula = document.getElementById('cedula').value;
    if (cedula == "") {
        $("#cedula").attr({
            placeholder: "REQUERIDO"
        });
        $("#cedula").focus();
        return false;
    } if (cedula.length<=2) {
        // es una tarjeta.
        $('#texto-mensaje').text("Está realizando una salida de tarjeta?");
        MuestraMensajeTarjeta();
        return false;
    }
    else if (cedula.length < 8) {
        $("#mensajetop").css("background-color", "firebrick");
        $("#textomensaje").text("Formato de cedula: mínimo 8 digitos sin guiones ni espacios");
        $("#mensajetop").css("visibility", "visible");
        $("#mensajetop").slideDown("slow");
        //
        $("#cedula").focus();
        return false;
    } else {
        $("#mensajetop").hide();
        return true;
    }
}

function onValidaNuevoPerfil() {
    var formlisto = true;
    $("#mensajetop").hide();
    var cedula = document.getElementById('cedula').value;
    var empresa = document.getElementById('empresa').value;
    var nombre = document.getElementById('nombre').value;
    //
    if (cedula == "") {
        $("#cedula").attr({
            placeholder: "REQUERIDO"
        });
        $("#cedula").focus();
        formlisto = false;
    }  
    if (empresa == "") {
        $("#empresa").attr({
            placeholder: "REQUERIDO"
        });
        $("#empresa").focus();
        formlisto = false;
    }
    if (nombre == "") {
        $("#nombre").attr({
            placeholder: "REQUERIDO"
        });
        $("#nombre").focus();
        formlisto = false;
    } 
    //
    if (cedula.length < 8) {    
        $("#mensajetop").css("background-color", "firebrick");
        $("#textomensaje").text("Formato de cedula: Mínimo 8 digitos sin guiones ni espacios");
        $("#mensajetop").css("visibility", "visible");
        $("#mensajetop").slideDown("slow");
        //
        $("#cedula").focus();
        formlisto = false;
    }
    else if (nombre.length < 10) {
        $("#mensajetop").css("background-color", "firebrick");
        $("#textomensaje").text("El Nombre debe tener mínimo 10 caracteres");
        $("#mensajetop").css("visibility", "visible");
        $("#mensajetop").slideDown("slow");
        //
        $("#nombre").focus();
        formlisto = false;
    }
    //
    //if (formlisto)
    //    $("#mensajetop").hide();
    //
    return formlisto;

}

function onValidaInfoVisita() {
    var formlisto = true;
    $("#mensajetop").hide();
    var cedula = document.getElementById('cedula').value;
    var detalle = document.getElementById('detalle').value;
    var sala = document.getElementById('sala').value;
    //
    if (cedula == "") {
        $("#cedula").attr({
            placeholder: "REQUERIDO"
        });
        $("#cedula").focus();
        formlisto = false;
    } 
    if (detalle == "") {
        $("#detalle").attr({
            placeholder: "REQUERIDO"
        });
        $("#detalle").focus();
        formlisto = false;
    } 
    //
    if (cedula.length < 8) {
        $("#mensajetop").css("background-color", "firebrick");
        $("#textomensaje").text("Formato de cedula: Mínimo 8 digitos sin guiones ni espacios");
        $("#mensajetop").css("visibility", "visible");
        $("#mensajetop").slideDown("slow");
        //
        $("#cedula").focus();
        formlisto = false;
    }else if (detalle.length < 8) {
        $("#mensajetop").css("background-color", "firebrick");
        $("#textomensaje").text("El motivo de la visita debe tener mínimo 8 caracteres");
        $("#mensajetop").css("visibility", "visible");
        $("#mensajetop").slideDown("slow");
        //
        $("#detalle").focus();
        formlisto = false;
    } else if (sala == "") {
        $("#mensajetop").css("background-color", "firebrick");
        $("#textomensaje").text("Seleccione la sala a visitar");
        $("#mensajetop").css("visibility", "visible");
        setInterval(function () {
            $(".field").toggleClass("emptyfield");
        }, 1000);
        $("#mensajetop").slideDown("slow");
        //
        formlisto = false;
    }
    else {
        $(".field").css("background", "#ccc;");  
    }
    //alert(formlisto);
    //
    //if (formlisto)
    //    $("#mensajetop").hide();
    //
    return formlisto;

}