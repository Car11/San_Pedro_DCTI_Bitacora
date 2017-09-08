<?php 
if (!isset($_SESSION)) 
    session_start();

if (isset($_GET['Message'])) {
    print '<script type="text/javascript">alert("' . $_GET['Message'] . '");</script>';
}
// Sesion de usuario
include("class/Sesion.php");
$sesion = new Sesion();
if (!$sesion->estado){
    $_SESSION['url']= explode('/',$_SERVER['REQUEST_URI'])[2];
    header('Location: Login.php');
    exit;
}
// es un formulario temporal
$formtemp="NULL";
if(isset($_SESSION['TEMP']))
{
    $formtemp=$_SESSION['TEMP'];
    unset($_SESSION['TEMP']);
}

?>


<html>
<head>
    <meta charset="UTF-8">
    <title>Control de Acceso</title>
    <!-- CSS -->
    <link href="css/Estilo.css" rel="stylesheet"/>
    <link href="css/Formulario.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="css/datatables.css">
    <!-- JS  -->
    <script src="js/jquery.js" type="text/jscript"></script>
    <script type="text/javascript" charset="utf8" src="js/datatables.js"></script>
    <script src="js/Validaciones.js" languaje="javascript" type="text/javascript"></script> 
</head>
<body onload="CargarEstiloTablas();"> 
    <header>
	<h1>LISTA FORMULARIOS</h1>        
    <div id="logo"><img src="img/Logoice.png" height="75" > </div>
	</header>
    <div id="general">
        <div id="izquierda">
             
            
        </div>
        <div id="principal">
            <div id="superiornavegacion">
                <div id="supnuevo">
                    <input type="button" id="btnnuevo" class="cbp-mc-submit" value="Nuevo" onclick="location.href='FormularioIngreso.php'";>      
                </div>
                <div id="supbusca">
                    <div id="izq_busqueda"></div>
                    <div id="cen_busqueda">
                        <label for="txtbuscavisitante" class="labelformat">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Busqueda por visitante:</label></br>
                        <input type="checkbox" id="checkconsultavisitante" name="checkconsultavisitante" value="Bike">
                        <input type="text" id="txtbuscavisitante" name="txtbuscavisitante" class="inputformat" value=""/>       
                    </div>
                    <div id="der_busqueda"></div>
                </div>
                <div id="supatras">
                    <input type="button" id="btnatras" class="cbp-mc-submit" value="Atrás"onclick="location.href='MenuAdmin.php'";>   
                </div>
            </div>
            <div id="listavisitante">

            </div>
                <footer></footer>  
        </div>
        <div id="derecha">
  
        </div>
    </div>    
    <script>
        
        $(document).ready( function () {
            ActivaConsultaVisitante();
            //Da la apariencia del css datatable
            //CargarEstiloTablas();
            //envía notificación al servidor
            this.ajaxSent = function() {
                try {
                    xhr = new XMLHttpRequest();
                } catch (err) {
                    alert(err);
                }
                //alert('enviando formulario temporal: ' + formtemp);
                url='NotificacionDinamica.php?msg='+formtemp;
                xhr.open('GET', url, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {                    
                        if (xhr.status == 200) {   
                            formtemp.value = "";
                            //alert('finalizando formulario temporal: ' + formtemp);
                        }
                    }
                };
                xhr.send();
            };

            var formtemp= "<?php echo $formtemp; ?>";
            //alert(formtemp);

            if(formtemp!="NULL")
                this.ajaxSent();
        } );  // fin document ready.
        
        function CargarEstiloTablas() {
            $('#listaformulario').DataTable();    
        }

        //MODIFICA EL REGISTRO SELECIONADO EN EL CAMPO MODIFICAR *********/       
        $(document).on('click', '.modificar', function (event) {    
            //CAPTURAR UUID
            $.ajax({
            type: "POST",
            url: "class/Formulario.php",
            data: {
                    action: "CargaIDFormulario",
                    consecutivo: $(this).parents("tr").find("td").eq(0).text()
                  }
            })
            .done(function( e ) {
                // carga lista con datos.
                var formulario= JSON.parse(e);
                location.href='FormularioIngreso.php?MOD='+formulario[0][0];
            })    
                .fail(function(msg){
                alert("Error al carga id del formulario");
            }); 
            
        }); 

        //FUNCIONALIDAD DEL CHECKBOX QUE ACTIVA LA CONSULTA DE VISITANTES *********/       
        $(document).on('click', '#checkconsultavisitante', function (event) {    
            ActivaConsultaVisitante();
        }); 

        function ActivaConsultaVisitante(){
            if(document.getElementById("checkconsultavisitante").checked == true){
                $("#txtbuscavisitante").prop("readonly", false);
            }
            if(document.getElementById("checkconsultavisitante").checked == false){
                $("#txtbuscavisitante").prop("readonly", true);
                $("#txtbuscavisitante").val("");
                RecargarTabla();
            }
        } 
    
    function RecargarTabla(){
        $.ajax({
            type: "POST",
            url: "class/Formulario.php",
            data: {
                    action: "RecargaTabla"
                  }
        })
        .done(function( e ) {
            var formularioxvisitante = JSON.parse(e);
            
            $('#listavisitante').html("");
            $('#listavisitante').append("<table id='listaformulario'class='display'>");
            var col="<thead><tr> <th>ID</th> <th>FECHA SOLICITUD</th> <th>MOTIVO</th> <th>ESTADO</th> <th>RFC</th> <th>MODIFICAR</th></tr></thead><tbody id='tableBody'></tbody>";
            $('#listaformulario').append(col);
            // carga lista con datos.
            var data= JSON.parse(e);
            // Recorre arreglo.
            $.each(data, function(i, item) {
                var row="<tr>"+
                    "<td>"+ item.consecutivo+"</td>" +
                    "<td>"+ item.fechasolicitud + "</td>"+
                    "<td>"+ item.motivovisita + "</td>"+
                    "<td>"+ item.estado + "</td>"+
                    "<td>"+ item.rfc +"</td>"+
                    "<td><img id=imgdelete src=img/file_mod.png class=modificar></td>"+
                "</tr>";
                $('#tableBody').append(row);         
            })
            // formato tabla
            $('#listaformulario').DataTable( {
                "order": [[ 0, "desc" ]]
            } );
        })    
        .fail(function(msg){
            alert("Error al Cargar la lista de Formularios");
        });    
    }

    $('#txtbuscavisitante').on('keyup', function() {
        
        $.ajax({
            type: "POST",
            url: "class/Formulario.php",
            data: {
                    action: "Consultarporvisitante",
                    busqueda: this.value
                  }
        })
        .done(function( e ) {
            var formularioxvisitante = JSON.parse(e);
            
            $('#listavisitante').html("");
            $('#listavisitante').append("<table id='listaformulario'class='display'>");
            var col="<thead><tr> <th>ID</th> <th>FECHA SOLICITUD</th> <th>MOTIVO</th> <th>ESTADO</th> <th>RFC</th> <th>MODIFICAR</th></tr></thead><tbody id='tableBody'></tbody>";
            $('#listaformulario').append(col);
            // carga lista con datos.
            var data= JSON.parse(e);
            // Recorre arreglo.
            $.each(data, function(i, item) {
                var row="<tr>"+
                    "<td>"+ item.consecutivo+"</td>" +
                    "<td>"+ item.fechasolicitud + "</td>"+
                    "<td>"+ item.motivovisita + "</td>"+
                    "<td>"+ item.estado + "</td>"+
                    "<td>"+ item.rfc +"</td>"+
                    "<td><img id=imgdelete src=img/file_mod.png class=modificar></td>"+
                "</tr>";
                $('#tableBody').append(row);         
            })
            // formato tabla
            $('#listaformulario').DataTable( {
                "order": [[ 0, "desc" ]]
            } );
        })    
        .fail(function(msg){
            alert("Error al Eliminar");
        });
    });

    </script>
    </body>
</html>

