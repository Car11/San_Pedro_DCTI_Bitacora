$(document).ready(inicio);

function inicio() {
    
    $("#enviar").click(onValidaInicio);
    $("#EnviaNuevoPerfil").click(onValidaNuevoPerfil);
    $("#EnviaInfoVisita").click(onValidaInfoVisita);
    $("#EnviaFormulario").click(onValidaFormulario);
   
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function onValidaFormulario() {
    var sala = document.getElementById('selectsala').value;
    var responsable = document.getElementById('txtresponsable').value;
    var motivo = document.getElementById("motivovisita").value;
    var placa =  document.getElementById('placavehiculo').value;
    var equipo = document.getElementById('detalleequipo').value;
    var rfc = document.getElementById('txtrfc').value;
    var visitante = document.getElementById("visitantelargo").value;

    /*altert(visitante);
    if(visitante.length==0){
        alert("Debe de insertar al menos un Visitante!");
        return false;        
    }*/


    if (responsable == ""){
        alert("Debe de asignar un responsable!");
        return false;
    }
    if (sala == ""){
        alert("Debe de seleccionar una SALA!");
        return false;
    }
    if (motivo == ""){
        alert("El motivo no puede estar vacío");
        return false;
    }
    if (motivo.length<8){
        alert("El motivo debe contener mínimo 8 caracteres");
        return false;
    }
    if(motivo.length>1000){
        alert("El motivo no puede exceder de 1000 caracteres!");    
        return false;
    }
    if(placa.length>200){
        alert("La placa no puede exceder de 200 caracteres!");    
        return false;
    }
    if(equipo.length>200){
        alert("El detalle de los equipos no puede exceder de 200 caracteres!");    
        return false;
    }
    if(rfc.length>200){
        alert("El RFC no puede exceder de 10 caracteres!");    
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
    } else if (cedula.length < 9) {
        $("#mensajetop").css("background-color", "firebrick");
        $("#textomensaje").text("Formato de cedula: mínimo 9 digitos sin guiones ni espacios");
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
    if (cedula.length < 9) {    
        $("#mensajetop").css("background-color", "firebrick");
        $("#textomensaje").text("Formato de cedula: Mínimo 9 digitos sin guiones ni espacios");
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
    if (cedula.length < 9) {
        $("#mensajetop").css("background-color", "firebrick");
        $("#textomensaje").text("Formato de cedula: Mínimo 9 digitos sin guiones ni espacios");
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