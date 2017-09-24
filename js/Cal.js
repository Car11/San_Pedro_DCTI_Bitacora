
$(document).ready( function () {
    /*setInterval(function() {
        Buscar_Proximo();
        Buscar_Ensitio();
  }, 5000);   */ 
  Cal();

});

function Carga_Cal(e){
    // Limpia el div que contiene la tabla.
    $('#calendario').html("");   
    $('#calendario').append('<div id=lunes class=diasemana>LUNES</div>');
    $('#calendario').append('<div id=martes class=diasemana>MARTES</div>');
    $('#calendario').append('<div id=miercoles class=diasemana>MIERCOLES</div>');
    $('#calendario').append('<div id=jueves class=diasemana>JUEVES</div>');
    $('#calendario').append('<div id=viernes class=diasemana>VIERNES</div>');
    $('#calendario').append('<div id=sabado class="diasemana fin">SABADO</div>');
    $('#calendario').append('<div id=domingo class="diasemana fin">DOMINGO</div>');
    //
    CargaDia(e,'lunes');    
};

function CargaDia(e, miDia){
    // HORAS 07:00 - 23:00
    for(i=7; i<=23; i++) {
        $('#'+ miDia).append('<div id='+ miDia + 'H'+i+'></div>');
    }
    // HORAS 00:00 - 06:00
    for(i=0; i<=6; i++) {
        $('#'+ miDia).append('<div id='+ miDia + 'H'+i+'></div>');
    }
    // DATA
    var data= JSON.parse(e);
    var css_actividad='';
    $.each(data, function(i, item) {
        var row="<div class='oper "+ item.actividad +"'>"+ item.inicial + "</div>";
        $('#'+miDia+'H'+item.hora).append(row);
        css_actividad= item.actividad;
    });
    $( '.oper' ).mouseover(function() {
        $(".oper:contains("+$(this).text()+")").toggleClass( "oper-selected" );        
        //alert('m over');
    })
    .mouseout(function() {
        //$(".oper:contains("+$(this).text()+")").toggleClass( "css_actividad" );

    });
    /*$( '.oper' ).mouseover(function() {
        $(".oper:contains("+$(this).text()+")").removeClass( "oper" ).addClass( "oper-selected" );        
    })
    .mouseout(function() {
        $( '.oper-selected' ).removeClass( "oper-selected" ).addClass( "oper" );
    });*/

};



// Muestra errores en ventana
function muestraError(){        
    $('header').append('error - ');
};

function Cal(){
    $.ajax({
        type: "POST",
        url: "class/Cal.php",
        data: { 
            action: "Cargar"
        }
    })
    .done(function( e ) {            
         Carga_Cal(e);
    })    
    .fail(muestraError);
};
