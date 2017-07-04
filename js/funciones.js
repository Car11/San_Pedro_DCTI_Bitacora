$(document).ready(inicio);
var formularioConsultado='NULL'; // formulario consultado por medio de la cedula del visitante en punto de seguridad.
var formularioID= []; // formularios temporales aprobado/Denegado en tiempo real por operaciones.
var modal;

function inicio() {    
    // Cierra el MODAL en cualquier parte de la ventana
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            $("#cedula").focus();
        }
    };
    
    startTime();
    // COMBOBOX
    $('.sala').styleddropdown();
    //modal index
    modal = document.getElementById('modal-index'); 
    //
    $(".mensajeriaInfo").click( function(){
        // Cierra div
        $(this).toggle("fadeOut");
    });
    $(".mensajeriaAdvertencia").click( function(){
        $(this).toggle("fadeOut");
    });
    $(".mensajeriaError").click( function(){
        $(this).toggle("fadeOut");
    });
    $(".mensajeriaOk").click( function(){
        // muestra modal con número de carnet y btn aceptar/x para cerrar para ingreso bitácora. info básica formulario.        
        modal.style.display = "block";
        $("#btncontinuar").toggle("fadeIn");
        $("#btnsalida").hide();
        // desaparece div mensaje y elimina registro _chat.txt
        $(this).toggle("fadeOut");
        $.get("filemanager.php");
    });

    $("#closemodal").click( function(){
        // muestra modal con info básica formulario. y btn cerrar./ x para cerrar
        modal.style.display = "none";
        $("#cedula").focus();
    });

    this.onCancelar= function(){
         modal.style.display = "none";
         $("#cedula").focus();
    };

    
    this.MensajeTop= function(msg){
        $("#textomensaje").text(msg);
        $("#mensajetop").css("background-color", "60E800");
        $("#mensajetop").css("color", "white");    
        $("#mensajetop").css("visibility", "visible");
        $("#mensajetop").slideDown("slow");
        $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
        //
        //$("#cedula").focus();

    };

    $('#btnsalida').click(function() {
        $.ajax({
            type: "POST",
            url: "class/Bitacora.php",
            data: { 
                accion: 'salida',                
                idtarjeta:  document.getElementById('idtarjeta').value,
                idformulario: document.getElementById('idformulario').value,
                cedula: document.getElementById('modal-cedula').value
                //motivovisita: document.getElementById('motivovisita').value
            }
        })
        .done(function( e ) {
            // mensaje de visitante salida correcta.
            modal.style.display = "none";
            //alert("Salida ok:"+ e + " ... "  + document.getElementById('modal-cedula').value  + 'f-'+ document.getElementById('idformulario').value +'v-' + document.getElementById('idtarjeta').value );
            //MensajeTop(e);
            $("#textomensaje").text(e);
            $("#mensajetop").css("background-color", "60E800");
            $("#mensajetop").css("color", "white");    
            $("#mensajetop").css("visibility", "visible");
            $("#mensajetop").slideDown("slow");
            $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
        })    
        .fail(function(msg){
            modal.style.display = "none";
            //alert("Salida ERROR:"  + document.getElementById('modal-cedula').value );
            //MensajeTop(e);
            $("#textomensaje").text(e);
            $("#mensajetop").css("background-color", "firebrick");
            $("#mensajetop").css("color", "white");    
            $("#mensajetop").css("visibility", "visible");
            $("#mensajetop").slideDown("slow");
            $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
        });
    });

     $('#btncontinuar').click(function() {
        $.ajax({
            type: "POST",
            url: "class/Bitacora.php",
            data: { 
                accion: 'entrada',                
                cedula: document.getElementById('modal-cedula').value ,
                idtarjeta:  document.getElementById('idtarjeta').value,
                idformulario: document.getElementById('idformulario').value
                //motivovisita: document.getElementById('motivovisita').value
            }
        })
        .done(function( e ) {
            // mensaje de visitante salida correcta.
            modal.style.display = "none";
            $("#textomensaje").text(e);
            $("#mensajetop").css("background-color", "60E800");
            $("#mensajetop").css("color", "white");    
            $("#mensajetop").css("visibility", "visible");
            $("#mensajetop").slideDown("slow");
            $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
        })    
        .fail(function(msg){
            modal.style.display = "none";
            $("#textomensaje").text(e);
            $("#mensajetop").css("background-color", "firebrick");
            $("#mensajetop").css("color", "white");    
            $("#mensajetop").css("visibility", "visible");
            $("#mensajetop").slideDown("slow");
            $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
        });
    });

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

this.MensajeriaHtml = function(mensaje, id){
    formularioConsultado = id;    
    if(mensaje!="NULL"){
        if(mensaje=="fin")
            onMuestraPerfilSalida();
        else onMuestraEstadoFormulario(mensaje);
    }
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

// Muestra modal de información del visitante y formulario para realizar salida.
function onMuestraPerfilSalida() { 
    //alert(formularioConsultado);    
    modal = document.getElementById('modal-index');  
    modal.style.display = "block";
    $("#btncontinuar").hide();
    $("#btnsalida").toggle("fadeIn");
    $("#btnsalida").css("background", "#990000");
}

function onMuestraEstadoFormulario(estado) { // id del formulario a consultar       
    var htmltext= ""; 
    // Identificador del tag.
    var divId=+ new Date();
    // estado= pendiente; cuando se envió el formulaio temporal
    if(estado=="pendiente")
    {
        estado="Formulario Enviado!<br>Por favor espere";
        htmltext= "<div class=mensajeriaInfo id=" + divId + ">" + estado + "</div>";    
    }else if(estado=="0") // estado=0; pendiente por autorizar.
    {
        estado="Formulario (<b><i>"+ formularioConsultado + "</b></i>) <br>Pendiente por Autorizar.";
        htmltext= "<div class=mensajeriaAdvertencia id=" + divId + ">" + estado + "</div>";    
    }else if(estado=="1"){
        estado="Formulario (<b><i>"+ formularioConsultado + "</b></i>) <br>Autorizado.";
        htmltext= "<div class=mensajeriaOk id=" + divId + ">" + estado + "</div>";
    }else if(estado=="2"){
        if(formularioConsultado!='NULL') {
            estado="Formulario (<b><i>"+ formularioConsultado + "</b></i>) <br>Denegado.";
            htmltext= "<div class=mensajeriaError id=" + divId + ">" + estado + "</div>";
        } else {
            estado="Acceso Denegado<br>No hay formulario.";
            htmltext= "<div class=mensajeriaError id=" + divId + ">" + estado + "</div>";
        }
    }else if(estado=="3"){
        estado="Formulario (<b><i>"+ formularioConsultado + "</b></i>) <br>Denegado, Tiempo de visita excedido.";
        htmltext= "<div class=mensajeriaAdvertencia id=" + divId + ">" + estado + "</div>";
    }
    else if(estado=="4"){
        estado= estado="Formulario (<b><i>"+ formularioConsultado + "</b></i>) <br>No hay tarjetas de visitante disponibles.";
        htmltext= "<div class=mensajeriaAdvertencia id=" + divId + ">" + estado + "</div>";
    }
    //
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