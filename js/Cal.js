var arrCal  = [];
var operBox='NULL';
var operDay='NULL';
var operFilter=0;

$(document).ready( function () {
    /*setInterval(function() {
        Buscar_Proximo();
        Buscar_Ensitio();
    }, 5000);   */ 
    Cal();
    //    
    $('#btn-operacion').click(function() {        
        arrCal  = [];
        //busca cajas marcadas
        $( '.oper-checked div' ).each(function(){
            //carga arreglo
            var data={
                "id": $(this).attr('id'),
                "actividad": 'operaciones'
            };
            arrCal.push(data); 
        });
        //modifica bd
        setActivity();
    });
    
    $('#btn-vacaciones').click(function(){
        arrCal  = [];
        //busca cajas marcadas
        $( '.oper-checked div' ).each(function(){
            //carga arreglo
            var data={
                "id": $(this).attr('id'),
                "actividad": 'vacaciones'
            };
            arrCal.push(data); 
        });
        //modifica bd
        setActivity();
    });


    $('#btn-vacaciones').click(function(){

    });
});

function AddOperDay(){
    $.ajax({
        type: "POST",
        url: "class/Cal.php",
        data: { 
            action: "AddOperDay",
            operini: $("#oper-ini").val(),
            operday:$("#oper-day").val(),
            operturn: $("#oper-turn").val()
        }
    })
    .done(function( e ) {            
         // Carga_Cal(e);
    })    
    .fail(muestraError);
}

function setActivity(){
    $.ajax({
        type: "POST",
        url: "class/Cal.php",
        data: { 
            action: "SetActivity",
            obj: arrCal
        }
    })
    .done(function( e ) {            
         Carga_Cal(e);
    })    
    .fail(muestraError);
};



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
    $('#calendario').append('<div id=espacio class="espacio">...</div>');
    //
    CargaDia(e);     
};


function CargaDia(e, miDia){
    // Carga el contenido de cada dia 
    $.each( [ "lunes" ], function( i, miDia ){
        // HORAS 07:00 - 23:00
        for(i=7; i<=23; i++) {
            $('#'+ miDia).append('<div id='+ miDia + 'H'+i+'></div>');
        }
        // HORAS 00:00 - 06:00
        for(i=0; i<=6; i++) {
            $('#'+ miDia).append('<div id='+ miDia + 'H'+i+'></div>');
        }  
        //
        // DATA
        var data= JSON.parse(e);
        $.each(data, function(i, item) {
            var row="<div class='oper "+ item.actividad +"'>"+ item.inicial + 
                "<div id="+item.id+"></div>"    
            "</div>";
            $('#'+miDia+'H'+item.hora).append(row);
            /*var row="<div class='oper "+ item.actividad +"'>"+ "AA" + "</div>";
            $('#'+miDia+'H'+item.hora).append(row);
            var row="<div class='oper "+ item.actividad +"'>"+ "BB" + "</div>";
            $('#'+miDia+'H'+item.hora).append(row);
            var row="<div class='oper "+ item.actividad +"'>"+ "CC" + "</div>";
            $('#'+miDia+'H'+item.hora).append(row);*/
        });  
    });    
    this.BoxEventHandler();    
    if(operFilter==1)
        this.operOnly();
};

function BoxEventHandler(){
    // muestra cajas con el mismo oper
    $( '.oper' ).mouseover(function() {
        $(".oper:contains("+$(this).text()+")").addClass( "oper-mouseover" );                
    })
    .mouseout(function() {        
        $(".oper:contains("+$(this).text()+")").removeClass("oper-mouseover");
    });
    // marca caja al dar click
    $( '.oper' ).click(function() {
        operBox= $(this).text();
        operDay= $(this).parent().parent().attr('id');
        if($(this).hasClass("oper-checked"))
            $(this).removeClass( "oper-checked" );
        else $(this).addClass( "oper-checked" );
    });
    // Marca todas las cajas del mismo día.
    $( '#oper-selectall' ).click(function() {        
        $('#'+operDay).find(".oper:contains("+operBox+")").addClass( "oper-checked" );
    });
    // Marca todas las cajas del mismo día.
    $( '#oper-unselectall' ).click(function() {        
        $(".oper").removeClass( "oper-checked" );                    
    });
    //despliega solo oper seleccionado
    $('#oper-only').click(function(){      
        operOnly();
    });
}

function operOnly(){
    if(operFilter==0)
        operFilter=1;
    else operFilter=0;
    if($('.oper').hasClass("oper-displaynone"))  {        
        $(".oper").removeClass( "oper-displaynone" ); 
    }else{             
        $(".oper:not(:contains("+operBox+"))").addClass( "oper-displaynone" ); 
    }
}

// Muestra errores en ventana
function muestraError(){        
    $('header').append('error - ');
};

function Cal(){
    $.ajax({
        type: "POST",
        url: "class/Cal.php",
        data: { 
            action: "Load"
        }
    })
    .done(function( e ) {            
         Carga_Cal(e);
    })    
    .fail(muestraError);
};


