
$(document).ready( function () {
    /*setInterval(function() {
        Buscar_Proximo();
        Buscar_Ensitio();
    }, 5000);   */
  Buscar_Proximo();
  Buscar_Ensitio();
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
    })
    /*$('#proximo').append("<table id='tblLista_proximo'></table>");
    var col= "<thead><tr> <th style='display:none;'>IDVISITANTE</th> <th style='display:none;'>IDFORMULARIO</th> "+
        " <th class='encabezado'>FORMULARIO</th> <th class='encabezado'>FECHA INGRESO</th> <th class='encabezado'>CEDULA</th> <th class='encabezado'>NOMBRE</th>  <th class='encabezado'>EMPRESA</th> <th class='encabezado'>MOTIVO</th> <th class='encabezado'>RFC</th> </tr></thead>"+
    "<tbody id='tableBody_proximo'>  </tbody>";
    $('#tblLista_proximo').append(col); 
    // recorre el arreglo.
    // carga lista con datos.
    // v.id as idvisitante ,f.id as idformulario, f.consecutivo, fechaingreso,nombre, cedula, motivovisita, rfc
    var data= JSON.parse(e);
    $.each(data, function(i, item) {
        var row="<tr class='tarjeta'>"+
            "<td style='display:none;' >" + item.idvisitante +"</td>" +
            "<td style='display:none;' >"+ item.idformulario + "</td>"+
            "<td class='bloq-normal'>"+ item.consecutivo + "</td>"+
            "<td class='bloq-normal'>"+ item.fechaingreso + "</td>"+            
            "<td class='bloq-normal'>"+ item.cedula +"</td>"+
            "<td class='bloq-noflex'>"+ item.nombre +"</td>"+
            // "<td class='bloq-normal'>"+ item.empresa +"</td>"+
            // "<td class='bloq-normal'>"+ item.motivovisita +"</td>"+
            // "<td class='bloq-noflex'>"+ item.rfc +"</td>"+
        "</tr>";
        $('#tableBody_proximo').append(row);
    })*/
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
            "<div class='bloq-normal'>"+ item.cedula +"</div>"+
            "<div class='bloq-noflex'>"+ item.nombre +"</div>"+
        "</div>";
        $('#ensitio').append(row);
    })    
    /*$('#ensitio').append("<table id='tblLista_ensitio'></table>");
    var col= "<thead><tr> <th style='display:none;'>IDVISITANTE</th> <th style='display:none;'>IDFORMULARIO</th> "+
        " <th class='encabezado'>FORMULARIO</th> <th class='encabezado'>ENTRADA</th> <th class='encabezado'>TARJETA</th> <th class='encabezado'>CEDULA</th> <th class='encabezado'>NOMBRE</th>"+
        "<tbody id='tableBody_ensitio'>  </tbody>";
    $('#tblLista_ensitio').append(col); 
    // recorre el arreglo.
    // carga lista con datos.
    var data= JSON.parse(e);
    $.each(data, function(i, item) {
        var row="<tr class='tarjeta'>"+            
            "<td style='display:none;' >" + item.idvisitante +"</td>" +
            "<td style='display:none;' >"+ item.idformulario + "</td>"+
            "<td class='bloq-normal'>"+ item.consecutivo + "</td>"+
            "<td class='bloq-normal'> "+ item.entrada + "</td>"+      
            "<td class='bloq-dif'>"+ item.tarjeta + "</td>"+            
            "<td class='bloq-normal'>"+ item.cedula +"</td>"+
            "<td class='bloq-noflex'>"+ item.nombre +"</td>"+
            //"<td>"+ item.empresa +"</td>"+
            //"<td>"+ item.motivovisita +"</td>"+
            //"<td>"+ item.rfc +"</td>"+
        "</tr>";
        $('#tableBody_ensitio').append(row);
    })*/
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