var mouseX;
var mouseY;
var objData = [];
// var tarjetaSeleccionada;
// var idformulario;
// var idvisitante;
// var idTarjeta;
// var idBitacora;

$(document).ready( function () {
    setInterval(function() {
        Buscar_Proximo();
        Buscar_Ensitio();
    }, 900000);   
    Buscar_Proximo();
    Buscar_Ensitio();
    $(document).mousemove( function(e) {
        mouseX = e.pageX; 
        mouseY = e.pageY;
    }); 
    $('.dropDownMenu li#liberar').click(function(){
        Liberar();
    });
});

$(document).mouseup(function(e) 
{
    var container = $("#submenu");
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.hide();
    }
});

function Datos_Proximo(e){
    // Limpia el div que contiene la tabla.
    $('#proximo').html(""); 
    var data= JSON.parse(e);
    $.each(data, function(i, item) {
        var row="<div class='tarjeta'>"+
            "<div class='bloq-normal'>"+ item.consecutivo + "</div>"+
            "<div class='bloq-normal'>"+ item.fechaingreso + "</div>"+
            "<div class='bloq-normal'>"+ item.cedula +"</div>"+
            "<div class='bloq-noflex'>"+ item.nombre +"</div>"+
        "</div>";
        $('#proximo').append(row);
    });
};

// Muestra errores en ventana
function muestraError(){        
    $('header').append('error-');
};

function Buscar_Proximo(){
    $.ajax({
        type: "POST",
        url: "class/Visitante.php",
        data: { 
            action: "Webnoc_proximo"
        }
    })
    .done(function( e ) {            
         Datos_Proximo(e);
    })    
    .fail(muestraError);
};

function Datos_Ensitio(e){
    // Limpia el div que contiene la tabla.
    $('#ensitio').html(""); 
    var data= JSON.parse(e);
    $.each(data, function(i, item) {
        var row="<div class='tarjeta'>"+
            "<div class='bloq-normal'>"+ item.consecutivo + "</div>"+
            "<div class='bloq-normal'>"+ item.entrada + "</div>"+
            "<div class='bloq-dif'>"+ item.tarjeta + "</div>"+   
            "<div class='bloq-normal cedula'>"+ item.cedula +"</div>"+
            "<div class='bloq-noflex'>"+ item.nombre +"</div>"+
            "<div class='Bloq-noVisible idvisitante'>"+ item.idvisitante +"</div>"+
            "<div class='Bloq-noVisible idformulario'>"+ item.idformulario +"</div>"+
            "<div class='Bloq-noVisible idtarjeta'>"+ item.idtarjeta +"</div>"+
            "<div class='Bloq-noVisible idbitacora'>"+ item.idbitacora +"</div>"+
        "</div>";
        $('#ensitio').append(row);
    });
    //  
    $('.bloq-dif').click(function(){
        objData = new Object();
        objData.numtarjeta = $(this).text();
        objData.idvisitante = $(this).parent().find('.idvisitante')[0].textContent
        objData.idformulario = $(this).parent().find('.idformulario')[0].textContent
        objData.idtarjeta = $(this).parent().find('.idtarjeta')[0].textContent
        objData.idbitacora = $(this).parent().find('.idbitacora')[0].textContent;
        objData.cedula = $(this).parent().find('.cedula')[0].textContent;
        //objData.push(item);
        $('#submenu').css({'top':mouseY,'left':mouseX}).fadeIn('slow');
    });
    $('.dropDownMenu li').click(function(){
        $('#submenu').fadeOut('slow');
    });
};

function Buscar_Ensitio(){
    $.ajax({
        type: "POST",
        url: "class/Visitante.php",
        data: { 
            action: "Webnoc_ensitio"
        }
    })
    .done(function( e ) {            
         Datos_Ensitio(e);
    })    
    .fail(muestraError);
};

function Liberar(){
    $.ajax({
        type: "POST",
        url: "class/Bitacora.php",
        data: { 
            accion: 'salida',  
            idBitacora: objData.idbitacora,    
            numtarjeta: objData.numtarjeta,
            idformulario: objData.idformulario,
            idvisitante: objData.idvisitante,
            idtarjeta: objData.idtarjeta,
            cedula: objData.cedula
        }
    })
    .done(function( e ) {
        swal({
            
            type: 'success',
            title: 'Good!',
            showConfirmButton: false,
            timer: 800
        });      
        Buscar_Ensitio();
    })    
    .fail(muestraError);
};