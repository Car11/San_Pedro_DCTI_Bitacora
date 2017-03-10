$(document).ready(inicio);
var r = false;
function inicio(){
    startTime();
    $("#enviar").click(onValidaIndex);
    $("#enviarPerfil").click(onValidaPerfil);
}

function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('date').innerHTML = h + ":" + m + ":" + s;
    var t = setTimeout(startTime, 500);
}

function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}

function onValidaIndex(){
    var cedula= document.getElementById('cedula').value;
    if(cedula=="") {
        $("#cedula").attr({placeholder:"REQUERIDO"});
        $("#cedula").focus();
        return false;
    }    
    else if(cedula.length<9) {      	
        $("#mensaje").css("background-color", "firebrick"); 
      	$("#textomensaje").text("Formato de cedula: 9 digitos sin guiones ni espacios");
      	$("#mensaje").css("visibility", "visible"); 
    	$( "#mensaje" ).slideDown( "slow" );
  		//
        $("#cedula").focus();
        return false;
    } else {
		$("#mensaje").hide();
		return true;
	}    
}

function onValidaPerfil(){
    var formlisto=true;
    var cedula= document.getElementById('cedula').value;
    var empresa= document.getElementById('empresa').value;
    var nombre= document.getElementById('nombre').value;
    if(cedula=="") {
        $("#cedula").attr({placeholder:"REQUERIDO"});
        $("#cedula").focus();
        formlisto=false;
    }    
    else if(cedula.length<9) {
        $("#mensaje").css("background-color", "firebrick"); 
        $("#textomensaje").text("Formato de cedula: 9 digitos sin guiones ni espacios");
      	$("#mensaje").css("visibility", "visible"); 
    	$( "#mensaje" ).slideDown( "slow" );
  		//
        $("#cedula").focus();
        formlisto=false;
    }        
    if(empresa=="") {
        $("#empresa").attr({placeholder:"REQUERIDO"});
        $("#empresa").focus();
        formlisto=false;
    }    
    if(nombre=="") {
        $("#nombre").attr({placeholder:"REQUERIDO"});
        $("#nombre").focus();
        formlisto=false;
    }
    if(formlisto) return true;
    else return false;
    
}
    
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}