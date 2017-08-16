<?php 
if (!isset($_SESSION)) 
    session_start();

// Sesion de usuario
include("class/sesion.php");
$sesion = new sesion();
if (!$sesion->estado){
    $_SESSION['url']= explode('/',$_SERVER['REQUEST_URI'])[2];
    header('Location: login.php');
    exit;
}

//RESPONSABLE
include("class/responsable.php");  
$responsable= new Responsable();
$responsables= $responsable->Consulta();

//USER AND ROL
include("class/usuario.php");  
$usuario = new usuario();
$usuario->Cargar();
$user= $_SESSION['username'];
$rol=$_SESSION['rol'];
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Control de Accesos</title>       
    <!-- CSS -->
    <link href="css/estilo.css" rel="stylesheet"/>        
    <link rel="stylesheet" type="text/css" href="css/datatables.css">
    <link href="css/formulario.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/sweetalert2.css" type="text/css"/>
    <!-- JS  -->
    <script src="js/jquery.js" type="text/jscript"></script>
 	<script type="text/javascript" charset="utf8" src="js/datatables.js"></script>
    <script src="js/validaciones.js" languaje="javascript" type="text/javascript"></script> 
    <script src="js/sweetalert2.js"></script>
</head>
<body> 
    <header>
	<h1>MANTENIMIENTO RESPONSABLES</h1>        
    <div id="logo"><img src="img/logoice.png" height="75" ></div>
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
            <div id="tablaresponsable">
                <!-- CREA EL TABLE QUE CARGA LOS VISITANTES AL FORMULARIO-->
                <?php
                print "<table id='tblresponsable' class='display'>";
                print "<thead>";
                print "<tr>";
                print "<th id='tituloid'>ID</th>";
                print "<th id='titulocedula'>Nombre</th>";
                print "<th id='titulonombre'>Cédula</th>";
                print "<th id='tituloempresa'>Empresa</th>";
                print "<th id='titulomodificar'>Modificar</th>";
                print "<th id='tituloeliminar'>Eliminar</th>";
                print "</tr>";
                print "</thead>";
                print "<tbody>";
                for($i=0; $i<count($responsables); $i++){
                    print "<tr>";
                    print "<td>".$responsables[$i][0]."</td>";
                    print "<td>".$responsables[$i][1]."</td>";
                    print "<td>".$responsables[$i][2]."</td>";
                    print "<td>".$responsables[$i][3]."</td>";
                    print "<td><img id=imgdelete src=img/file_mod.png class=modificar></td>";
                    print "<td><img id=imgdelete src=img/file_delete.png class=borrar href='EnviaResponsable.php'></td>";
                    print "</tr>";
                }
                print "</tbody>";
                print "</table>"; 
                ?>
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
                        <input type="text" id="txtnombre" name="txtnombre" placeholder="" class="inputformat" value="" pattern="[\.áéíóúÁÉÍÓÚÑñA-Za-z/\s/]*"/>
                    </div>                   
                    <div class="modalmod">
                        <label for="txtautorizador" class="labelformat">Cedula</label></br>
                        <input type="text" id="txtcedula" name="txtcedula" placeholder="" class="inputformat" value="" pattern="[\.-_0-9A-Za-z/\s/]*"/> 
                    </div>
                    <div class="modalmod">
                        <label for="txtautorizador" class="labelformat">Empresa</label></br>
                        <input type="text" id="txtempresa" name="txtempresa" placeholder="" class="inputformat" value="" pattern="[\.,-_0-9#áéíóúÁÉÍÓÚÑñA-Za-z/\s/]*"/> 
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
        modalResponsable.style.display = "block";
    }

    window.onclick = function(event) {
    if (event.target == modalResponsable) {
        modalResponsable.style.display = "none";
        }
    }
    
    $(document).ready( function () {             
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
                alert("Responsable Eliminado!");
            else
                alert("No se puede eliminar Responsable, ya está asignado a un Formulario!");
            location.reload();
        })    
        .fail(function(msg){
            alert("Error al Eliminar");
        });
        
    });

    //MODIFICA FILA DE UN TABLE AL SELECCIONAR EL BOTÓN Y LO CARGA EN LOS INPUTS *********/       
    $(document).on('click', '.modificar', function (event) {
        modalResponsable.style.display = "block";

        idresponsabletbl = $(this).parents("tr").find("td").eq(0).text();
        $('#btnInsertaResponsable').hide();
        $('#btnModificaResponsable').show();
        $.ajax({
            type: "POST",
            url: "class/Responsable.php",
            data: {
                    action: "Cargar",
                    idresponsable:  idresponsabletbl
                  }
        })
        .done(function( e ) {
            var responsable = JSON.parse(e);
            document.getElementById('txtnombre').value = responsable[0][1];
            document.getElementById('txtcedula').value = responsable[0][2];
            document.getElementById('txtempresa').value = responsable[0][3];      
        })    
        .fail(function(msg){
            alert("Error al Cargar");
        });
    });

    //MODIFICA FILA DE UN TABLE AL SELECCIONAR EL BOTÓN Y LO CARGA EN LOS INPUTS *********/       
    $(document).on('click', '#btnModificaResponsable', function (event) {
        $.ajax({
            type: "POST",
            url: "class/Responsable.php",
            data: {
                    action: "Modificar",
                    idresponsable: idresponsabletbl,
                    nombre: document.getElementById('txtnombre').value,
                    cedula: document.getElementById('txtcedula').value,
                    empresa: document.getElementById('txtempresa').value
                  }
        })
        .done(function( e ) {
            
            alert("Responsable Modificado!");
            location.reload();
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
            
            alert("Responsable Insertado!");
            location.reload();
        })    
        .fail(function(msg){
            alert("Error al Eliminar");
        });
    });  

    $( "#txtnombre" ).change(function() {
        $("#txtnombre").css("border", "0px");
        $("#txtnombre").css("color", "black");
        $("#txtnombre").css("background", "white");
        document.getElementById('txtnombre').placeholder = "";
    });

    $( "#txtcedula" ).change(function() {
        $("#txtcedula").css("border", "0px");
        $("#txtcedula").css("color", "black");
        $("#txtcedula").css("background", "white");
        document.getElementById('txtcedula').placeholder = "";
    });

    $( "#txtempresa" ).change(function() {
        $("#txtempresa").css("border", "0px");
        $("#txtempresa").css("color", "black");
        $("#txtempresa").css("background", "white");
        document.getElementById('txtempresa').placeholder = "";
    });

    
</script>
</body>
</html>