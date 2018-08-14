$(document).ready(inicio);
var formularioConsultado='NULL'; // formulario consultado por medio de la cedula del visitante en punto de seguridad.
var formularioID= []; // formularios temporales aprobado/Denegado en tiempo real por operaciones.
var modal;
var modalVisitante; 

function inicio() {    
    // Cierra el MODAL en cualquier parte de la ventana
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            $("#cedula").focus();
        }
        if (event.target == modalVisitante) {
            modalVisitante.style.display = "none";
        }    
    };
    
    startTime();
    // COMBOBOX
    $('.sala').styleddropdown();
    //modal index
    modal = document.getElementById('modal-index'); 
    modalVisitante  = document.getElementById('Modal-Visitante');         
    //
    $(".close").click( function(){
        // muestra modal con info básica formulario. y btn cerrar./ x para cerrar
        modal.style.display = "none";
        modalVisitante.style.display="none";
        $("#cedula").focus();
    });

    this.onCancelar= function(){
         modal.style.display = "none";
         $("#cedula").focus();
    };
            
    this.MensajeTop= function(msg){
        $("#textomensaje").text(msg);
        $("#mensajetop").css("background-color", "016DC4");
        $("#mensajetop").css("color", "FFFFFF");    
        $("#mensajetop").css("visibility", "visible");
        $("#mensajetop").slideDown("slow");
        $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
        //
        //$("#cedula").focus();

    };

    $('#btnsalida').click(function() {
        $('#btnsalida').attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "class/Bitacora.php",
            data: { 
                accion: 'salida',      
                numtarjeta: document.getElementById('numtarjeta').value,          
                idtarjeta:  document.getElementById('idtarjeta').value,
                idformulario: document.getElementById('idformulario').value,
                idvisitante: document.getElementById('idvisitante').value,
                cedula: document.getElementById('modal-cedula').value
            }
        })
        .done(function( e ) {
            // mensaje de visitante salida correcta.
            modal.style.display = "none";
            //alert("Salida ok:"+ e + " ... "  + document.getElementById('modal-cedula').value  + 'f-'+ document.getElementById('idformulario').value +'v-' + document.getElementById('idtarjeta').value );
            //MensajeTop(e);
            $("#textomensaje").text(e);
            $("#mensajetop").css("background-color", "016DC4");
            $("#mensajetop").css("color", "FFFFFF");    
            $("#mensajetop").css("visibility", "visible");
            $("#mensajetop").slideDown("slow");
            $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
            $("#btnsalida").removeAttr("disabled");
        })    
        .fail(function(e){
            modal.style.display = "none";
            $("#textomensaje").text('Ha ocurrido un error al realizar la salida, comunicarse con Operaciones TI');
            $("#mensajetop").css("background-color", "firebrick");
            $("#mensajetop").css("color", "white");    
            $("#mensajetop").css("visibility", "visible");
            $("#mensajetop").slideDown("slow");
            $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
            $("#btnsalida").removeAttr("disabled");
        });
    });

     $('#btncontinuar').click(function() {
        $('#btncontinuar').attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "class/Bitacora.php",
            data: { 
                accion: 'entrada',  
                idvisitante: document.getElementById('idvisitante').value,              
                cedula: document.getElementById('modal-cedula').value ,
                idtarjeta:  document.getElementById('idtarjeta').value,
                numtarjeta: document.getElementById('numtarjeta').value,    
                idformulario: document.getElementById('idformulario').value
            }
        })
        .done(function( e ) {
            // mensaje de visitante salida correcta.
            modal.style.display = "none";
            $("#textomensaje").text(e);
            $("#mensajetop").css("background-color", "016DC4");
            $("#mensajetop").css("color", "FFFFFF");    
            $("#mensajetop").css("visibility", "visible");
            $("#mensajetop").slideDown("slow");
            $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
            $("#btncontinuar").removeAttr("disabled");
        })    
        .fail(function(e){
            modal.style.display = "none";
            $("#textomensaje").text("Ha ocurrido un problema al realizar la entrada, comunicarse con Operaciones TI");
            $("#mensajetop").css("background-color", "firebrick");
            $("#mensajetop").css("color", "FFFFFF");    
            $("#mensajetop").css("visibility", "visible");
            $("#mensajetop").slideDown("slow");
            $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
            $("#btncontinuar").removeAttr("disabled");
        });
    });

    $('#Modal-Visitante tr').click(function() {
        $("#cedula").val($(this).find('td:first').html());
        modalVisitante.style.display = "none";
    });
    // tabla de busqueda
    if($.fn.DataTable)
        $('#tblvisitante-buscar').DataTable();

}

// xmlHttpRequest
this.CapturaMensajeFormulario = function () {
    //pushed server data
    if (typeof (EventSource) !== "undefined") {
        var source = new EventSource("NotificacionDinamica.php");
        source.onmessage = function (event) {
            onMuestraFormulario(event.data);
        };
    } else {
        document.getElementById("avisoFormulario").innerHTML = "Sorry, your browser does not support server-sent events...";
    }
};

this.MuestraMensajeTarjetaSinUso= function(){
    $( ".dialog-message" ).dialog({
        modal: true,
        closeOnEscape: false,
        open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); },
        buttons: {
            Salir: function() {                
                $( this ).dialog( "close" );                
                $("#cedula").focus();
                return false;
            }
        }
    });
};

this.MensajeriaHtml = function(mensaje, id){
    formularioConsultado = id;    
    if(mensaje!="NULL"){
        if(mensaje=="buscar"){                        
            modalVisitante.style.display = "block";
        } else if(mensaje=="TARJETANULL"){
            //alert("La tarjeta NO está en uso.");       
            $('#texto-mensaje').text("La tarjeta NO está en uso.");
            MuestraMensajeTarjetaSinUso();
            return false;
        }
        else if(mensaje=="fin")
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

function onMuestraFormulario(txt) {
    //var divId=+ new Date(); 
    // txt contiene texto del mensaje enviado por notificacion dinamica xmlHttpRequest
    var miTxt= txt.split("-");
    //lista de id's en pantalla
    var data={
        "uid":miTxt[0],
        "id":miTxt[1],
        "estado":miTxt[2]
    };
    var result = $.grep(formularioID, function(e){  return e.id== data.id; });
    //
    if (result.length  == 0)// si esta en el arreglo, por lo tanto ya está en pantalla
    {
        //agrega al arreglo
        formularioID.push(data); 
        //muestra en pantalla
        var bground="";
        switch (data.estado) {
            case "0":
                bground="#cc9900";
                break;
            case "1":
                bground="#016DC4";
                break;
            case "2":
                bground="#990000";
                break;
            default:
                break;
        }
        var htmltext= "<div class=avisoFormulario style='background:"+ bground +"' id=" + data.uid + ">" + data.id + "</div>";    
        $("#IDsformulario").append(htmltext);
        $("#"+data.uid).toggle("fadeIn");
        $("#"+data.uid).click(onClickIDFormulario); 
    }
    
}

// Muestra modal de información del visitante y formulario para realizar salida.
function onMuestraPerfilSalida() { 
    //alert(formularioConsultado);    
    modal = document.getElementById('modal-index');  
    modal.style.display = "block";
    $("#btncontinuar").hide();
    $("#btnsalida").toggle("fadeIn");
    $("#btnsalida").css("background", "firebrick");
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
        estado="Formulario (<b><i>"+ formularioConsultado + "</b></i>) <br>Tiempo de visita excedido.";
        htmltext= "<div class=mensajeriaError id=" + divId + ">" + estado + "</div>";
    }
    else if(estado=="4"){
        estado= estado="No hay formulario.";
        htmltext= "<div class=mensajeriaAdvertencia id=" + divId + ">" + estado + "</div>";
    }
    //
    $("#mensajespersonales").append(htmltext);
    $("#"+divId).toggle("fadeIn");
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
        // desaparece div mensaje.
        $(this).toggle("fadeOut");
    });
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
    $.get("FileManager.php");
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