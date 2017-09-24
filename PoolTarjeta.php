<?php 
if (!isset($_SESSION)) 
    session_start();
include_once('class/Globals.php');
// Sesion de usuario
include("class/Sesion.php");
$sesion = new Sesion();
if (!$sesion->estado){
    $_SESSION['url']= explode('/',$_SERVER['REQUEST_URI'])[2];
    header('Location: login.php');
    exit;
}

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
    <link href="css/Estilo.css?v=<?php echo Globals::cssversion; ?>" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="css/datatables.css">
    <link href="css/Formulario.css" rel="stylesheet"/>
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
    <div id="logo"><img src="img/Logoice.png" height="75" ></div>
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
            var col="<thead><tr><th class='enc_tajeta'>ADMIN</th><th class='enc_tajeta'>TEL</th><th class='enc_tajeta'>TOTAL</th><th class='enc_tajeta'>SALA 1</th><th class='enc_tajeta'>SALA 2</th class='enc_tajeta'><th class='enc_tajeta'>SALA 3</th><th class='enc_tajeta'>NOC</th></tr></thead>"+
                     "<tbody id='tableBody'>"+
                     "<td class='anchocolumna'><table id='tarjadmin'></table></td>"+
                     "<td class='anchocolumna'><table id='tarjtele'></table></td>"+
                     "<td class='anchocolumna'><table id='tarjtotal'></table></td>"+
                     "<td class='anchocolumna'><table id='tarjs1'></table></td>"+
                     "<td class='anchocolumna'><table id='tarjs2'></table></td>"+
                     "<td class='anchocolumna'><table id='tarjs3'></table></td>"+
                     "<td class='anchocolumna'><table id='noc'></table></td>"+
                     "</tbody>";
            $('#tarjetas').append(col);
            // carga lista con datos.
            var data= JSON.parse(e);
            // Recorre arreglo.
            for (var i = 0; i < data.length; i++) {        
                    if(data[i][1]=='Área Administrativa'){    
                        var admin="<td class='tarjeta' id=tarj_"+ data[i][0] +"'>" + data[i][0] + "</td>";
                        $('#tarjadmin').append(admin);
                        if(data[i][2]==1)
                            $("#tarj_"+data[i][0]).css("background", "red");
                    }
                    if(data[i][1]=='Telecomunicaciones'){    
                        var tele="<td class='tarjeta' id=tarj_"+ data[i][0] +">" + data[i][0] + "</td>";
                        $('#tarjtele').append(tele);
                        if(data[i][2]==1)
                            $("#tarj_"+data[i][0]).css("background", "red");
                    }
                    if(data[i][1]=='Acceso Total'){    
                        var total="<td class='tarjeta' id=tarj_"+ data[i][0] +">" + data[i][0] + "</td>";
                        $('#tarjtotal').append(total);
                        if(data[i][2]==1)
                            $("#tarj_"+data[i][0]).css("background", "red");
                    }
                    if(data[i][1]=='Sala 1'){    
                        var s1="<td class='tarjeta' id=tarj_"+ data[i][0] +">" + data[i][0] + "</td>";
                        $('#tarjs1').append(s1);
                        if(data[i][2]==1)
                            $("#tarj_"+data[i][0]).css("background", "red");
                    }
                    if(data[i][1]=='Sala 2'){    
                        var s2="<td class='tarjeta' id=tarj_"+ data[i][0] +">" + data[i][0] + "</td>";
                        $('#tarjs2').append(s2);
                        if(data[i][2]==1)
                            $("#tarj_"+data[i][0]).css("background", "red");
                    }
                    if(data[i][1]=='Sala 3'){    
                        var s3="<td class='tarjeta' id=tarj_"+ data[i][0] +">" + data[i][0] + "</td>";
                        $('#tarjs3').append(s3);
                        if(data[i][2]==1)
                            $("#tarj_"+data[i][0]).css("background", "red");
                    }
                    if(data[i][1]=='NOC'){    
                        var noc="<td class='tarjeta' id=tarj_"+ data[i][0] +">" + data[i][0] + "</td>";
                        $('#noc').append(noc);
                        if(data[i][2]==1)
                            $("#tarj_"+data[i][0]).css("background", "red");
                    }
            }
        })    
        .fail(function(msg){
            alert("Error al Cargar Pool de Tarjetas");
        });    
    }
    
</script>
</body>
</html>