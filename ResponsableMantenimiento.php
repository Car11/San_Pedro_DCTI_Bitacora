<?php 
if (!isset($_SESSION)) 
    session_start();
    include_once('class/Globals.php');
// Sesion de usuario
include("class/Sesion.php");
$sesion = new Sesion();
if (!$sesion->estado){
    $_SESSION['url']= explode('/',$_SERVER['REQUEST_URI'])[2];
    header('Location: Login.php');
    exit;
}

//RESPONSABLE
include("class/Responsable.php");  
$responsable= new Responsable();
$responsables= $responsable->Consulta();

//USER AND ROL
include("class/Usuario.php");  
$usuario = new Usuario();
$usuario->Cargar();
$user= $_SESSION['username'];
$rol=$_SESSION['rol'];
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Control de Accesos</title>       
    <!-- CSS -->
    <link href="css/Estilo.css?v= <?php echo Globals::cssversion; ?>" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="css/datatables.css">
    <link href="css/Formulario.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/sweetalert2.css" type="text/css"/>
    <!-- JS  -->
    <script src="js/jquery.js" type="text/jscript"></script>
 	<script type="text/javascript" charset="utf8" src="js/datatables.js"></script>
    <script src="js/Validaciones.js" languaje="javascript" type="text/javascript"></script> 
    <script src="js/sweetalert2.js"></script>
</head>
<body> 
    <header>
	<h1>MANTENIMIENTO RESPONSABLES</h1>        
    <div id="logo"><img src="img/Logoice.png" height="75" ></div>
	</header>
    <div id="general">
        <form class="cbp-mc-form" method="POST" action="request/EnviaResponsable.php" onSubmit="return EnviaResponsable()">       
        <div id="izquierda">
 
        </div>
        <div id="principal">
            <div id="superiornavegacion">
                <div id="nuevo">
                    <input type="button" id="btnnuevo" class="cbp-mc-submit" value="Nuevo" onclick="location.href='FormularioIngreso.php'";>         
                </div>
                <div id="atraslista">
                    <input type="button" id="btnatras" class="cbp-mc-submit" value="Atrás"onclick="location.href='MenuAdmin.php'";>   
                </div>
            </div>
            <div id="listaresponsable">

            </div>

        </div>
        <div id="derecha">

        </div>
    </form>
    </div>    

    <!-- MODAL RESPONSABLE -->
    <div id="ModalResponsable" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Responsables</h2>
            </div>
            <div class="modal-body">
                <div id="resizquierda"></div>
                
                    <div class="modalmod">
                        <label for="txttramitante" class="labelformat">Nombre</label></br>
                        <input type="text" id="txtnombre" name="txtnombre" placeholder="" class="input-field" value="" pattern="[\.áéíóúÁÉÍÓÚÑñA-Za-z/\s/]*"/>
                    </div>                   
                    <div class="modalmod">
                        <label for="txtautorizador" class="labelformat">Cedula</label></br>
                        <input type="text" id="txtcedula" name="txtcedula" placeholder="" class="input-field" value="" pattern="[\.-_0-9A-Za-z/\s/]*"/> 
                    </div>
                    <div class="modalmod">
                        <label for="txtautorizador" class="labelformat">Empresa</label></br>
                        <input type="text" id="txtempresa" name="txtempresa" placeholder="" class="input-field" value="" pattern="[\.,-_0-9#áéíóúÁÉÍÓÚÑñA-Za-z/\s/]*"/> 
                    </div>
                    <div class="modalmod">
                        <input id="btnInsertaResponsable" class="cbp-mc-submit" type="button" value="Inserta Responsable">
                        <input id="btnModificaResponsable" class="cbp-mc-submit" type="button" value="Modifica Responsable">
                        <input id="idresponsable" name="idresponsable" type="hidden">
                    </div>    
                
                <div id="resderecha"></div>
            </div>
            <div class="modal-footer">
            <br>
            </div>
        </div>
    </div>
    <!--FINAL MODAL RESPONSABLE-->
    
<script>
    var idresponsabletbl;
    var modalResponsable = document.getElementById('ModalResponsable');  
    var btnmodificar = document.getElementsByClassName('modificar');
    var btnnuevo  = document.getElementById('btnnuevo');
    var span = document.getElementsByClassName("close")[0];

    span.onclick = function() {modalResponsable.style.display = "none";}

    btnnuevo.onclick = function() {
        $('#btnInsertaResponsable').show();
        $('#btnModificaResponsable').hide();
        modalResponsable.style.display = "block";
    }

    window.onclick = function(event) {
    if (event.target == modalResponsable) {
        modalResponsable.style.display = "none";
        }
    }
    
    $(document).ready( function () {      
        RecargarTabla();       
        $('#btnModificaResponsable').hide();
        $('#btnInsertaResponsable').show();
        $('#tblresponsable').DataTable();   
        LimpiaInputs();
    } );


    //Limpia los input despues de insertar o modificar
    function LimpiaInputs(){
        document.getElementById("txtnombre").value = "";
        document.getElementById("txtcedula").value = "";
        document.getElementById("txtempresa").value = "";
    }
    
    //BORRA FILA DE UN TABLE AL SELECCIONAR EL BOTÓN Y LO QUITA DEL ARREGLO *********/       
    $(document).on('click', '.borrar', function (event) {
        var idresponsable = $(this).parents("tr").find("td").eq(0).text();
        document.getElementById("idresponsable").value = idresponsable;
        swal({
            title: 'Eliminar Responsable?',
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
        });
    });

    function Eliminar(){
        $.ajax({
            type: "POST",
            url: "class/Responsable.php",
            data: {
                    action: "Eliminar",
                    idresponsable: document.getElementById('idresponsable').value
                }
            })
            .done(function( e ) {
            var existeresponsable = JSON.parse(e);
            if (existeresponsable[0][0]==0)
                swal("Eliminado!", "El registro ha sido eliminado correctamente.", "success");
            else
                swal('Mensaje!','El registro se encuentra  en uso, no es posible eliminar.','error');
            RecargarTabla();
            })    
            .fail(function(msg){
                alert("Error al Eliminar");
            }
        );
    }
    
    //CARGA EN LOS INPUTS *********/       
    $(document).on('click', '.modificar', function (event) {
        modalResponsable.style.display = "block";
        $('#btnInsertaResponsable').hide();
        $('#btnModificaResponsable').show();
        $.ajax({
            type: "POST",
            url: "class/Responsable.php",
            data: {
                    action: "CargarMod",
                    identificador: $(this).parents("tr").find("td").eq(0).text()
                  }
        })
        .done(function( e ) {
            var responsable = JSON.parse(e);
            document.getElementById('txtnombre').value = responsable[0][0];
            document.getElementById('txtcedula').value = responsable[0][1];
            document.getElementById('txtempresa').value = responsable[0][2];      
        })    
        .fail(function(msg){
            alert("Error al Cargar");
        });
        document.getElementById("idresponsable").value = $(this).parents("tr").find("td").eq(0).text();
    });

    //MODIFICA FILA DE UN TABLE AL SELECCIONAR EL BOTÓN Y LO CARGA EN LOS INPUTS *********/       
    $(document).on('click', '#btnModificaResponsable', function (event) {
        $.ajax({
            type: "POST",
            url: "class/Responsable.php",
            data: {
                    action: "Modificar",
                    idresponsable: document.getElementById('idresponsable').value,
                    nombre: document.getElementById('txtnombre').value,
                    cedula: document.getElementById('txtcedula').value,
                    empresa: document.getElementById('txtempresa').value
                  }
        })
        .done(function( e ) {
            swal("Responsable Modificado!", "correctamente", "success")
            RecargarTabla();
            modalResponsable.style.display = "none";
            LimpiaInputs();
        })    
        .fail(function(msg){
            alert("Error al Eliminar");
        });
    });

    //CONCATENA EL ARREGLO EN UN STRING, LO ASIGNA A UN TAG HIDDEN PARA PASAR POR POST ***/
    $(document).on('click', '#btnInsertaResponsable', function (event) {
        
        $.ajax({
            type: "POST",
            url: "class/Responsable.php",
            data: {
                    action: "Insertar",
                    nombre: document.getElementById('txtnombre').value,
                    cedula: document.getElementById('txtcedula').value,
                    empresa: document.getElementById('txtempresa').value
                  }
        })
        .done(function( e ) {
            
            //alert("Responsable Insertado!");
            swal("Responsable Insertado!", "correctamente", "success")
            RecargarTabla();
            modalResponsable.style.display = "none";
            LimpiaInputs();
        })    
        .fail(function(msg){
            alert("Error al Eliminar");
        });
    });  

    //RECARGA LA TABLA CON RESPONSABLES AJAX
    function RecargarTabla(){
        $.ajax({
            type: "POST",
            url: "class/Responsable.php",
            data: {
                    action: "CargarTabla"
                  }
        })
        .done(function( e ) {
            $('#listaresponsable').html("");
            $('#listaresponsable').append("<table id='tblresponsable'class='display'>");
            var col="<thead><tr><th id='titulo_idres'>ID</th><th>NOMBRE</th><th>CEDULA</th><th>EMPRESA</th><th>MODIFICAR</th><th>ELIMINAR</th></tr></thead><tbody id='tableBody'></tbody>";
            $('#tblresponsable').append(col);
            // carga lista con datos.
            var data= JSON.parse(e);
            // Recorre arreglo.
            $.each(data, function(i, item) {
                var row="<tr>"+
                    "<td class='columna_idres'>"+ item.id+"</td>" +
                    "<td>"+ item.nombre+"</td>" +
                    "<td>"+ item.cedula + "</td>"+
                    "<td>"+ item.empresa + "</td>"+
                    "<td><img id=imgdelete src=img/file_mod.png class=modificar></td>"+
                    "<td><img id=imgdelete src=img/file_delete.png class=borrar href='EnviaResponsable.php'></td>"+
                "</tr>";
                $('#tableBody').append(row);  
                $('#titulo_idres').hide();
                $('.columna_idres').hide();       
            })
            // formato tabla
            $('#tblresponsable').DataTable( {
                "order": [[ 1, "asc" ]]
            } );
        })    
        .fail(function(msg){
            alert("Error al Cargar la lista de Responsables");
        });    
    }
    
</script>
</body>
</html>