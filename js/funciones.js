$(document).ready(inicio);
var r = false;
var formularioID= [];

function inicio() {
    startTime();
    /*$(".avisoFormulario").click( function(){
        //document.getElementById('cedula').value = $(this).innerText;
        alert("aa");
        
        //document.getElementById("datos").submit();
        //elimina elemento de archivo.
        //$.get("filemanager.php");
    });*/
    $(".mensajeria").click( function(){
        $(this).toggle("fadeOut");
    });
    // COMBOBOX
    $('.sala').styleddropdown();
}

this.CapturaMensajeFormulario = function () {
    //pushed server data
    if (typeof (EventSource) !== "undefined") {
        var source = new EventSource("Pruebas/envianotificaciondinamica.php");
        source.onmessage = function (event) {
            onMuestraFormulario(event.data);
        };
    } else {
        document.getElementById("avisoFormulario").innerHTML = "Sorry, your browser does not support server-sent events...";
    }
};

this.MensajeriaHtml = function(mensaje){
    if(mensaje!="NULL")
        onMuestraMensaje(mensaje);
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
    
    window.location.href = "login.php";                    
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
    // add zero in front of numbers < 10
    if (i < 10) {
        i = "0" + i
    }; 
    return i;
}

function onMuestraFormulario(id) {
    var divId=+ new Date(); 
    id= id.split("-");
    //lista de id's en pantalla
    var data={
        "id":id[1]
    };
    //
    var result = $.grep(formularioID, function(e){  return e.id== data.id; });
    if (result.length  == 0)// si esta en el arreglo, por lo tanto ya está en pantalla
    {
        //agrega al arreglo
        formularioID.push(data); 
        //muestra en pantalla
        var htmltext= "<div class=avisoFormulario id=" + divId + ">" + id[1] + "</div>";    
        $("#IDsformulario").append(htmltext);
        $("#"+divId).toggle("fadeIn");
        $("#"+divId).click(onClickIDFormulario); 
    }
    
}

function onMuestraMensaje(msg) {        
    if(msg=="pendiente")
        msg="Formulario Enviado!<br>Por favor espere";
    //elimina espacios en blanco para crear id unico de DIV
    var divId=+ new Date();
    var htmltext= "<div class=mensajeria id=" + divId + ">" + msg + "</div>";    
    $("#mensajespersonales").append(htmltext);
    $("#"+divId).toggle("fadeIn");
}

function onClickIDFormulario() {
    //elimina ID del arreglo
    formularioID.splice(
        formularioID.findIndex(x => x.id === $(this)[0].innerText)
        ,1);
    //ejecuta form con la cedula
    document.getElementById('cedula').value = $(this)[0].innerText;
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