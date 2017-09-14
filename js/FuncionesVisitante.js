var formReady = false;
var id = "NULL";

$(document).ready( function () {
    //Da la apariencia del css datatable
    ReCargar();

    //vuelve al menu
    this.onVuelve = function(){
        location.href = "ListaVisitantes.php";                       
    }; 

    // cierra el modal
    $(".close").click( function(){
        // muestra modal con info básica formulario. y btn cerrar./ x para cerrar
        $(".modal").css({ display: "none" });
    });

     // Cierra el MODAL en cualquier parte de la ventana
    window.onclick = function(event) {
        if (event.target.className=="modal") {
            $(".modal").css({ display: "none" });
        }    
    };

    //vuelve al menu
    this.Cerrar = function(){
        $(".modal").css({ display: "none" });
    }; 

    //valida cedula unica al perder el foco en el input cedula.
    $('#cedula').focusout(ValidaCedulaUnica);

});

// Muestra información en ventana
function muestraInfo(){     
    $(".modal").css({ display: "none" });  
    $("#textomensaje").text("Información almacenada correctamente!!");
    $("#mensajetop").css("background-color", "#016DC4");
    $("#mensajetop").css("color", "#FFFFFF");    
    $("#mensajetop").css("visibility", "visible");
    $("#mensajetop").slideDown("slow");
    $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
};

// Muestra errores en ventana
function muestraError(){        
    $(".modal").css({ display: "none" });  
    $("#textomensaje").text("Error al procesar la información");
    $("#mensajetop").css("background-color", "firebrick");
    $("#mensajetop").css("color", "white");    
    $("#mensajetop").css("visibility", "visible");
    $("#mensajetop").slideDown("slow");
    $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
};

// AJAX: Carga la lista 
function ReCargar(){
    $.ajax({
        type: "POST",
        url: "class/Visitante.php",
        data: { 
            action: "CargarTodos"
        }
    })
    .done(function( e ) {            
        // Limpia el div que contiene la tabla.
        $('#lista').html(""); 
        $('#lista').append("<br><br><br> <table id='tblLista' class='display' > </table>");
        var col= "<thead><tr> <th style='display:none;'>ID</th> <th>CEDULA</th> <th>NOMBRE</th>  <th>EMPRESA</th> <th>PERMISO ANUAL</th> <th>MODIFICAR</th> <th>ELIMINAR</th> </tr></thead>"+
            "<tbody id='tableBody'>  </tbody>";
        $('#tblLista').append(col); 
        // carga lista con datos.
        var data= JSON.parse(e);
        // Recorre arreglo.
        $.each(data, function(i, item) {
            var row="<tr class=fila>"+
                "<td style='display:none;' >" + item.id +"</td>" +
                "<td>"+ item.cedula + "</td>"+
                "<td>"+ item.nombre + "</td>"+
                "<td>"+ item.empresa + "</td>"+
                "<td>"+ item.permisoanual +"</td>"+
                "<td><img id=imgdelete src=img/file_mod.png class=modificar></td>"+
                "<td><img id=imgdelete src=img/file_delete.png class=eliminar></td>"+
            "</tr>";
            $('#tableBody').append(row);
        })
        // evento click del boton modificar-eliminar
        $('.modificar').click(EventoClickModificar);
        $('.eliminar').click(EventoClickEliminar);
        // formato tabla
        $('#tblLista').DataTable( {
            "order": [[ 2, "asc" ]]
        } ); 
    })    
    .fail(muestraError);
};

// Abre nuevo modal.
this.Nuevo = function() {        
    // limpia valores.        
    id="NULL";
    $("#cedula").val("");
    $("#empresa").val("");
    $("#nombre").val("");
    $("#permiso")[0].checked = false;
    $("#cedula").css({
        "border": "1px solid #C2C2C2"
    });
     $("#nombre").css({
        "border": "1px solid #C2C2C2"
    });
     $("#empresa").css({
        "border": "1px solid #C2C2C2"
    });
    // Muestra modal.
    $(".modal").css({ display: "block" });         
};

// evento click del boton eliminar
function EventoClickEliminar(){
    id = $(this).parents("tr").find("td").eq(0).text();    
    // Mensaje de borrado:
    swal({
        title: 'Eliminar el Perfil?',
        text: "Esta acción es irreversible!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'No, cancelar!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger'
    }).then(function () {
        // eliminar registro.
        Eliminar();
    })
};

function EventoClickModificar(){
    $("#cedula").css({
        "border-color": "green",
        "border-width": "0.3px"
    });
    //                
    id = $(this).parents("tr").find("td").eq(0).text();           
    // Ajax: Consulta visitante.        
    $.ajax({
        type: "POST",
        url: "class/Visitante.php",
        data: { 
            action: 'CargarID',                
            idvisitante:  id
        }            
    })
    .done(function( e ) {
        // mensaje de visitante salida correcta.
        var data= JSON.parse(e);
        $("#cedula").val(data[0].cedula);
        $("#empresa").val(data[0].empresa);
        $("#nombre").val(data[0].nombre);
        $("#permiso")[0].checked= data[0].permisoanual==1?true:false;
        $(".modal").css({ display: "block" }); 
    })    
    .fail(function(e){
        $(".modal").css({ display: "none" });  
        $("#textomensaje").text(e);
        $("#mensajetop").css("background-color", "firebrick");
        $("#mensajetop").css("color", "white");    
        $("#mensajetop").css("visibility", "visible");
        $("#mensajetop").slideDown("slow");
        $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
    });
};

function validarForm(){
    ValidaCedulaUnica();
    //
    if(!formReady){
        return false;
    } 
    if($("#cedula").val()=="")
    {
        $("#cedula").css("border", "0.3px solid firebrick");
        document.getElementById('cedula').placeholder = "REQUERIDO";
        $("#cedula").focus();
        return false;
    }        
    else if($("#cedula").val().length<8)
    {
        $("#cedula").css("border", "0.3px solid firebrick");
        // mensaje
        // ...
        return false;
    }
    //
    if($("#empresa").val()=="")
    {
        $("#empresa").css("border", "0.3px solid firebrick");
        document.getElementById('empresa').placeholder = "REQUERIDO";
        $("#empresa").focus();
        return false;
    }
    //
    if($("#nombre").val()=="")
    {
        $("#nombre").css("border", "0.3px solid firebrick");
        document.getElementById('nombre').placeholder = "REQUERIDO";
        $("#nombre").focus();
        return false;
    }
    else if($("#nombre").val().length<10)
    {
        $("#nombre").css("border", "0.3px solid firebrick");
        // mensaje
        // ...
        return false;
    }
    //        
    return true;
};

// guarda el registro.
function Guardar(){   
    // Ajax: insert / Update.
    if(!validarForm())
        return false;
    var miAccion= id=='NULL' ? 'Insertar' : 'Modificar';
    $.ajax({
        type: "POST",
        url: "class/Visitante.php",
        data: { 
            action: miAccion,  
            idvisitante: id,              
            cedula:  $("#cedula").val(),
            nombre: $("#nombre").val(),
            empresa: $("#empresa").val(),
            permiso: $("#permiso")[0].checked
        }
    })
    .done(muestraInfo)
    .fail(muestraError)
    .always(ReCargar);
};    

function Eliminar(){            
    $.ajax({
        type: "POST",
        url: "class/Visitante.php",
        data: { 
            action: 'Eliminar',                
            idvisitante:  id
        }
        /*,statusCode: {
            200: function (response) {
                alert('200 ec');         
            }
        }*/
    })
    .done(function(e){
        if(e=="Registro en uso")
        {
            swal(
            'Mensaje!',
            'El registro se encuentra  en uso, no es posible eliminar.',
            'error'
        );
        }
        else swal(
            'Eliminado!',
            'El registro se ha eliminado.',
            'success'
        );
        ReCargar();
    })        
    .fail(muestraError);
};

//valida cedula unica.
function ValidaCedulaUnica(){
    $.ajax ({
        type: "POST",
        url: "class/Visitante.php",
        data: { 
            action: "ValidaCedulaUnica",
            cedula:  $("#cedula").val(),
            nombre: $("#nombre").val(),
        }
    })
    .done(function( e ) {    
        if(e=="invalida"){
             $("#cedula").css({
                "border-color": "firebrick",
                "border-width": "0.3px"
            });
            $("#cedula").focus();
        }
        else {
            $("#cedula").css({
                "border-color": "green",
                "border-width": "0.3px"
            });
            formReady=true;
        }
     })
    .fail(function( e ) {    
        // ...
     });
};


