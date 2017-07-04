<?php 
if (!isset($_SESSION)) 
    session_start();
// Sesion de usuario
include("class/sesion.php");
$sesion = new sesion();
if (!$sesion->estado){
    header('Location: login.php');
    exit;
}
// es un formulario temporal
$formtemp="NULL";
if(isset($_SESSION['TEMP']))
{
    $formtemp=$_SESSION['TEMP']; // ID del formulario temporal.
}

include("class/Formulario.php");
$formulario= new Formulario();
$data= $formulario->ConsultaFormulario();
?>


<html>
<head>
    <meta charset="UTF-8">
    <title>Control de Acceso</title>
    <!-- CSS -->
    <link href="css/estilo.css" rel="stylesheet"/>
    <link href="css/formulario.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="css/datatables.css">
    <!-- JS  -->
    <script src="js/jquery.js" type="text/jscript"></script>
    <script type="text/javascript" charset="utf8" src="js/datatables.js"></script>
    <script src="js/validaciones.js" languaje="javascript" type="text/javascript"></script> 
</head>
<body> 
    <header>
	<h1>LISTA FORMULARIOS</h1>        
    <div id="logo"><img src="img/logoice.png" height="75" > </div>
	</header>
    <div id="general">
        <div id="izquierda">
            
        </div>
        <div id="principal">
            <div id="nuevoformulario">
                <div id="nuevo">
                    <input type="buton" id="btnnuevo" value="Nuevo" onclick="location.href='FormularioIngresox.php'";>      
                </div>
                <div id="atras">
                    
                    <input type="buton" id="btnatras" value="Atrás"onclick="location.href='MenuAdmin.php'";>   
                </div>
            </div>
            <div id="listavisitante">
               <?php 
                print "<table id='listaformulario'class='display'>";
                print "<thead>";
                print "<tr>";
                print "<th>ID</th>";
                print "<th>FECHA SOLICITUD</th>";
                print "<th>ESTADO</th>";
                print "<th>MOTIVO</th>";
                print "<th>MODIFICAR</th>";    
                print "</tr>";
                print "</thead>";	
                print "<tbody>";
                for($i=0; $i<count($data); $i++){
                        print "<tr>";
                        print "<td>".$data[$i][0]."</td>";
                        print "<td>".$data[$i][1]."</td>";
                        print "<td>".$data[$i][2]."</td>";
                        print "<td>".$data[$i][3]."</td>";
                        print "<td><img id=imgdelete src=img/file_mod.png class=modificar></td>";
                        print "</tr>";
                }
                print "</tbody>";
                print "</table>";
                ?>
                </div>
                <footer></footer>  
        </div>
        <div id="derecha">
  
        </div>
    </div>    
    <script>
        //Da la apariencia del css datatable
        $(document).ready( function () {
            $('#listaformulario').DataTable();
            //
             //envía notificación al servidor
            this.ajaxSent = function() {
                try {
                    xhr = new XMLHttpRequest();
                } catch (err) {
                    alert(err);
                }
                alert('enviando: ' + formtemp);
                url='notificaciondinamica.php?msg='+formtemp;
                //alert(url);
                xhr.open('GET', url, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {                    
                        if (xhr.status == 200) {   
                            formtemp.value = "";
                            alert('finalizando: ' + formtemp);
                        }
                    }
                };
                xhr.send();
            };

            var formtemp= "<?php echo $formtemp; ?>";
            alert(formtemp);
            if(formtemp!="NULL")
                this.ajaxSent();
            //For sending message
            /*this.sendMsg = function() {
                msg = document.getElementById("cedula").value;
                this.ajaxSent();
                return false;
            };*/

            } );  // fin document ready.
        
    </script>
    </body>
</html>

