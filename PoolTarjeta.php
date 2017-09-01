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
	<h1>POOL TARJETAS</h1>        
    <div id="logo"><img src="img/logoice.png" height="75" ></div>
	</header>
    <div id="general">
        <form class="cbp-mc-form" method="POST" action="request/EnviaResponsable.php" onSubmit="return EnviaResponsable()">       
        <div id="izquierda">
 
        </div>
        <div id="principal">
            <div id="superiornavegacion">
                <div id="nuevo">
                </div>
                <div id="atraslista">
                    <input type="button" id="btnatras" class="cbp-mc-submit" value="Atrás"onclick="location.href='MenuAdmin.php'";>   
                </div>
            </div>
            <div id="tarjetas"></div>            

        </div>
        <div id="derecha">

        </div>
    </form>
    </div>    
    
<script>

    $(document).ready( function () {
        RecargarPool();
    } );

    function RecargarPool(){
        $.ajax({
            type: "POST",
            url: "class/Tarjeta.php",
            data: {
                    action: "RecargaPool"
                  }
        })
        .done(function( e ) {
            var contarjeta=0;
            $('#tarjetas').html("");
            $('#tarjetas').append("<table id='pooltarjeta'class='display'>");
            var col="<thead><tr><th class='enc_tajeta'>ADMINISTRATIVO</th><th class='enc_tajeta'>TELECOMUNICACIONES</th><th class='enc_tajeta'>ACCESO TOTAL</th><th class='enc_tajeta'>SALA 1</th><th class='enc_tajeta'>SALA 2</th class='enc_tajeta'><th class='enc_tajeta'>SALA 3</th></tr></thead>"+
                     "<tbody id='tableBody'>"+
                     "<td class='anchocolumna'><table id='tarjadmin'></table></td>"+
                     "<td class='anchocolumna'><table id='tarjtele'></table></td>"+
                     "<td class='anchocolumna'><table id='tarjtotal'></table></td>"+
                     "<td class='anchocolumna'><table id='tarjs1'></table></td>"+
                     "<td class='anchocolumna'><table id='tarjs2'></table></td>"+
                     "<td class='anchocolumna'><table id='tarjs3'></table></td>"+
                     "</tbody>";
            $('#tarjetas').append(col);
            // carga lista con datos.
            var data= JSON.parse(e);
            // Recorre arreglo.
            $.each(data, function(i, item) {
                if(item.idsala==1){    
                    var admin="<td class='tarjeta' id=tarj_"+ item.id +">" + item.id + "</td>";
                    $('#tarjadmin').append(admin);
                    if(item.estado==1)
                        $("#tarj_"+item.id).css("background", "red");
                }
                if(item.idsala==2){    
                    var tele="<td class='tarjeta'>" + item.id + "</td>";
                    $('#tarjtele').append(tele);
                }
                if(item.idsala==3){    
                    var total="<td class='tarjeta'>" + item.id + "</td>";
                    $('#tarjtotal').append(total);
                }
                if(item.idsala==4){    
                    var s1="<td class='tarjeta'>" + item.id + "</td>";
                    $('#tarjs1').append(s1);
                }
                if(item.idsala==5){    
                    var s2="<td class='tarjeta'>" + item.id + "</td>";
                    $('#tarjs2').append(s2);
                }
                if(item.idsala==6){    
                    var s3="<td class='tarjeta'>" + item.id + "</td>";
                    $('#tarjs3').append(s3);
                }
            })
            $('#pooltarjeta').DataTable();
        })    
        .fail(function(msg){
            alert("Error al Cargar Pool de Tarjetas");
        });    
    }
    
</script>
</body>
</html>