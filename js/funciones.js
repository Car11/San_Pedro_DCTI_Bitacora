$(document).ready(inicio);
var r = false;

function inicio() {
    startTime();
    $("#avisoFormulario").click(onClickIDFormulario);
    //$("#logo").click(onShowLogin);
    // COMBOBOX
    $('.sala').styleddropdown();
}

this.CapturaMensajeFormulario = function () {
    //pushed server data
    if (typeof (EventSource) !== "undefined") {
        var source = new EventSource("envianotificaciondinamica.php");
        source.onmessage = function (event) {
            onMuestraFormulario(event.data);
        };
    } else {
        document.getElementById("avisoFormulario").innerHTML = "Sorry, your browser does not support server-sent events...";
    }
};

this.onShowLogin = function () {
    alert("iniciando");
    //var i="";
    /*if ($(".login").is(":hidden")) {
        $(".login").css("visibility", "visible");
        $(".login").slideDown(1000);
    } else {
        $(".login").slideUp(1000);
    }*/
    var sesion= '<?php print "no"?>';
    //'<?php include("php/sesion.php"); $sesion = new sesion();  print  $sesion->estadoLogin(); ?>';
    alert(sesion);
    /*if (sesion==true) {
        window.location.href = "MenuAdmin.php";
    } else {
        window.location.href = "login.php";                    
    } */   
};

function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    h = checkTime(h);
    m = checkTime(m);
    s = checkTime(s);
    //
    curtime = h + ":" + m + ":" + s;
    document.getElementById('date').innerHTML = curtime;
    var t = setTimeout(startTime, 500);
}

function checkTime(i) {
    if (i < 10) {
        i = "0" + i
    }; // add zero in front of numbers < 10
    return i;
}

function onMuestraFormulario(id) {
    document.getElementById("avisoFormulario").innerText = id;
    $("#avisoFormulario").css("visibility", "visible");
    $("#avisoFormulario").slideDown(150000);
}

function onClickIDFormulario() {
    //$("#cedula").text("12");
    document.getElementById('cedula').value = $("#avisoFormulario")[0].innerText;
    document.getElementById("datos").submit();
    //elimina elemento de archivo.
    $.get("filemanager.php");
}

/********COMBO BOX********/

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
/*
function onValidaInicio() {
    var cedula = document.getElementById('cedula').value;
    //var sala= document.getElementById('sala').value;
    //var detalle= document.getElementById('detalle').value;
    if (cedula == "") {
        $("#cedula").attr({
            placeholder: "REQUERIDO"
        });
        $("#cedula").focus();
        return false;
    } else if (cedula.length < 9) {
        $("#mensaje").css("background-color", "firebrick");
        $("#textomensaje").text("Formato de cedula: 9 digitos sin guiones ni espacios");
        $("#mensaje").css("visibility", "visible");
        $("#mensaje").slideDown("slow");
        //
        $("#cedula").focus();
        return false;
    }*/
/* else if(sala=="" && type!="OUT") 
 {
     $("#mensaje").css("background-color", "firebrick"); 
   	$("#textomensaje").text("Seleccione la sala a visitar");
   	$("#mensaje").css("visibility", "visible"); 
     //$(".field").css("background", "firebrick"); 
     setInterval(function(){
         $(".field").toggleClass("emptyfield");
     },1000);
 	$( "#mensaje" ).slideDown( "slow" );
     //
     return false;
 }*/
/*
    else {
        $("#mensaje").hide();
        return true;
    }
}*/
/*
function onValidaPerfil() {
    var formlisto = true;
    var cedula = document.getElementById('cedula').value;
    var empresa = document.getElementById('empresa').value;
    var nombre = document.getElementById('nombre').value;
    if (cedula == "") {
        $("#cedula").attr({
            placeholder: "REQUERIDO"
        });
        $("#cedula").focus();
        formlisto = false;
    } else if (cedula.length < 9) {
        $("#mensaje").css("background-color", "firebrick");
        $("#textomensaje").text("Formato de cedula: 9 digitos sin guiones ni espacios");
        $("#mensaje").css("visibility", "visible");
        $("#mensaje").slideDown("slow");
        //
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
    } else if (nombre.length < 10) {
        $("#mensaje").css("background-color", "firebrick");
        $("#textomensaje").text("El Nombre debe tener mínimo 10 caracteres");
        $("#mensaje").css("visibility", "visible");
        $("#mensaje").slideDown("slow");
        //
        $("#nombre").focus();
        formlisto = false;
    }
    if (formlisto) return true;
    else return false;

}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}*/
