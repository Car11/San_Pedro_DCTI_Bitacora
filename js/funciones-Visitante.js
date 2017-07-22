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
        // Ajax: Consulta visitante.   
        var miAccion= id=='NULL' ? 'Insertar' : 'Modificar';
        $.ajax({
            type: "POST",
            url: "class/Visitante.php",
            data: { 
                action: 'miAccion',
                cedula:  $("#cedula").val(),
                nombre: $("#nombre").val(),
                empresa: $("#empresa").val()
            }
        })
        .done(function( e ) {
            // mensaje de visitante salida correcta.
            $(".modal").css({ display: "none" });  
            $("#textomensaje").text("Información almacenada correctamente!!");
            $("#mensajetop").css("background-color", "60E800");
            $("#mensajetop").css("color", "white");    
            $("#mensajetop").css("visibility", "visible");
            $("#mensajetop").slideDown("slow");
            $("#mensajetop").slideDown("slow").delay(3000).slideUp("slow");
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


});  // fin document ready.
