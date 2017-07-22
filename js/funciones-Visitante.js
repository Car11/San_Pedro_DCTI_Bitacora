var id="NULL"; 
$(document).ready( function () {
    //Da la apariencia del css datatable
    $('#tblLista').DataTable();    

    //vuelve al menu
    this.onVuelve = function(){
        location.href = "ListaVisitantes.php";                       
    }; 

    //vuelve al menu
    this.Cerrar = function(){
        $(".modal").css({ display: "none" });
    }; 

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

    // Abre nuevo modal.
    this.Nuevo = function() {
        // limpia valores.
        id="NULL";
        $("#cedula").val("");
        $("#empresa").val("");
        $("#nombre").val("");
        // Muestra modal.
        $(".modal").css({ display: "block" });         
    }

    // guarda el registro.
    this.Guardar = function(){
        // Ajax: insert / Update.
        var miAccion= id=='NULL' ? 'Insertar' : 'Modificar';
        $.ajax({
            type: "POST",
            url: "class/Visitante.php",
            data: { 
                action: miAccion,
                cedula:  $("#cedula").val(),
                nombre: $("#nombre").val(),
                empresa: $("#empresa").val()
            }
        })
        .done(function( e ) {
            // mensaje de visitante salida correcta.
            //location.reload();
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
            location.reload();            
            $(".modal").css({ display: "none" });  
            $("#textomensaje").text(e);
            $("#mensajetop").css("background-color", "firebrick");
            $("#mensajetop").css("color", "white");    
            $("#mensajetop").css("visibility", "visible");
            $("#mensajetop").slideDown("slow");
            $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
        });
    }; 

   
});  // fin document ready.
