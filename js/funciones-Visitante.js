var id="NULL"; 
var formReady=false;
$(document).ready( function () {
    //Da la apariencia del css datatable
    $('#tblLista').DataTable();    

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

    //valida cedula unica.
    $('#cedula').focusout(function() {
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
                    "border-width": "5px"
                });
                $("#cedula").focus();
            }
            else {
                $("#cedula").css({
                    "border-color": "green",
                    "border-width": "5px"
                });
                formReady=true;
            }
         })
        .fail(function( e ) {    
            // ...
         });
    });

    // AJAX: Carga la lista 
    this.ReCargar = function(){
        $.ajax({
            type: "POST",
            url: "class/Visitante.php",
            data: { 
                action: "CargarTodos"
            }
        })
        .done(function( e ) {            
            // Limpia la lista.
            //$('#tblLista').html(""); 
            $('#tableBody').html(""); 
            // carga lista con datos.
            var data= JSON.parse(e);
            // Recorre arreglo.
            $.each(data, function(i, item) {
                var tr1="<tr>";
                    var td1="<td>"+ item.cedula +"</td>";
                    var td2="<td>"+ item.nombre +"</td>";
                    var td3="<td>"+ item.empresa +"</td>";
                    var td4="<td>"+ item.permisoanual +"</td>";
                var tr2="</tr>";
                $('#tableBody').append(tr1+td1+td2+td3+td4+tr2);
            })
        })    
        .fail(function(e){
            location.reload();
            $("#textomensaje").text('Error al cargar la lista, Intente de nuevo.');
            $("#mensajetop").css("background-color", "firebrick");
            $("#mensajetop").css("color", "white");    
            $("#mensajetop").css("visibility", "visible");
            $("#mensajetop").slideDown("slow");
            $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
        });
    };

    // evento click del boton eliminar
    $('.eliminar').click( function(){
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
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        }).then(function () {
            // eliminar registro.
            swal(
                'Deleted!',
                'Your file has been deleted.',
                'success'
            )
        })
    });

    // evento click del boton modificar
    $('.modificar').click( function(){
        $("#cedula").css({
                    "border-color": "green",
                    "border-width": "5px"
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
            $("#cedula").val(data[0].CEDULA);
            $("#empresa").val(data[0].EMPRESA);
            $("#nombre").val(data[0].NOMBRE);
            $("#permiso")[0].checked= data[0].PERMISOANUAL=1?true:false;
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
    });

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
        // Muestra modal.
        $(".modal").css({ display: "block" });         
    }

    this.muestraError = function(){
        location.reload();            
        $(".modal").css({ display: "none" });  
        $("#textomensaje").text("Error al almacenar la información");
        $("#mensajetop").css("background-color", "firebrick");
        $("#mensajetop").css("color", "white");    
        $("#mensajetop").css("visibility", "visible");
        $("#mensajetop").slideDown("slow");
        $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
    }

    // guarda el registro.
    this.Guardar = function(){
        /*$('#perfil').validate({
            submitHandler:
        });*/
        //
        if(!formReady){
            alert('Error de datos, hay errores en el formulario');
            return false;
        }            
        // Ajax: insert / Update.
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
        .done(function( e ) {
            // mensaje de visitante salida correcta.
            // location.reload();
            // recarga la lista:
            $.ajax({
                type: "POST",
                url: "class/Visitante.php",
                data: { 
                    action: "CargarTodos"
                }
            })
            .done(function( e ) {            
                // Limpia la lista.
                $('#tableBody').html(""); 
                // carga lista con datos.
                var data= JSON.parse(e);
                // Recorre arreglo.
                $.each(data, function(i, item) {
                    var row="<tr>"+
                        "<td>" + item.cedula +"</td>"+
                        "<td>"+ item.nombre +"</td>"+
                        "<td>"+ item.empresa +"</td>"+
                        "<td>"+ item.permisoanual +"</td>"+
                        "<td><img id=imgdelete src=img/file_mod.png class=modificar></td>"+
                        "<td><img id=imgdelete src=img/file_delete.png class=eliminar></td>";
                        "</tr>";
                    $('#tableBody').append(row);
                })
                // evento click del boton modificar
                $('.modificar').click( function(){
                    id = $(this).parents("tr").find("td").eq(0).text();           
                    // Ajax: Consulta visitante.        
                    $.ajax({
                        type: "POST",
                        url: "class/Visitante.php",
                        data: { 
                            action: 'Cargar',                
                            idvisitante:  id
                        }
                    })
                    .done(function( e ) {
                        // mensaje de visitante salida correcta.
                        var data= JSON.parse(e);
                        $("#cedula").val(data[0].CEDULA);
                        $("#empresa").val(data[0].EMPRESA);
                        $("#nombre").val(data[0].NOMBRE);
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
                });
            }); // fin de recarga            
            // Muestra mensaje.
            $(".modal").css({ display: "none" });  
            $("#textomensaje").text("Información almacenada correctamente!!");
            $("#mensajetop").css("background-color", "60E800");
            $("#mensajetop").css("color", "white");    
            $("#mensajetop").css("visibility", "visible");
            $("#mensajetop").slideDown("slow");
            $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
        })    
        .fail(function(e){
            // location.reload();            
            $(".modal").css({ display: "none" });  
            $("#textomensaje").text("Error al almacenar la información");
            $("#mensajetop").css("background-color", "firebrick");
            $("#mensajetop").css("color", "white");    
            $("#mensajetop").css("visibility", "visible");
            $("#mensajetop").slideDown("slow");
            $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
        });
    }; 

   
});  // fin document ready.
