<?php
    include_once('class/Globals.php');
    $ID="";
    if (isset($_GET['ID'])) {
        $ID=$_GET['ID'];
    }
    if(isset($_SESSION['estado']))
        unset($_SESSION['estado']);
    if(isset($_SESSION['idformulario']))
        unset($_SESSION['idformulario']);
    if(isset($_SESSION['cedula']))
        unset($_SESSION['cedula']);
    if(isset($_SESSION['link']))
        unset($_SESSION['link']);
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Control de Acceso</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

   
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="css/Estilo.css?v=<?php echo Globals::cssversion; ?>" rel="stylesheet" />

    <script src="js/jquery.js" type="text/jscript"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/Funciones.js" languaje="javascript" type="text/javascript"></script>
     <script>
        /*$('#username').attr("autocomplete", "off");
        $('#password').attr("autocomplete", "off");
        setTimeout('$("#username").val("");', 100);
        setTimeout('$("#password").val("");', 100);*/
    </script>

</head>
<body>

<div class="container-fluid">
    <header>
        <div class="row" >             
            <div class="col-md-2"  >
                <div id="logo"  ><img src="img/Logoice.png" height="75" > </div>
            </div>
            <div class="col-md-8">                
                <h1 class="text-center">Centros de Datos Corporativos</h1>                     
            </div>           
            <div class="col-md-2"></div>
        </div>
    </header>     

    <div class="row" >    
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div id="form" class="form-container">
                <h3 class="text-center">Usuario y Contraseña de <strong>Dominio</strong></h3>
                <div class="login">    
                    <form  name="Usuario" action="request/EnviaUsuario.php" method="POST">              
                        <div class="form-group">
                            <input type="text" id="username" class="form-control" name="username" placeholder="USUARIO" maxlength="20" />
                        </div>
                        <div class="form-group">
                            <input type="password" id="password" class="form-control" name="password" placeholder="CONTRASEÑA" maxlength="20" />                    
                        </div>
                        <div class="form-group">
                            <nav class="btnfrm">
                                <ul>
                                    <li> <input class="nbtn_blue" type="submit" value="Ingresar" id="login" /></li>
                                </ul>
                            </nav>
                        </div>
                    </form>
                    <div id="invalido">
                        <h3>Usuario o Contraseña Inválido</h3>
                    </div>
                </div>                
            </div>
            
        <div class="col-md-2"></div>
    </div> <!--End row-->

</div> <!--End container-->





   
          
           
                
            

   
    
    



    
<script> 
    var ID = '<?php print $ID ?>';
    if(ID=='invalid'){            
        $("#invalido").css("visibility", "visible");
        //$("#invalido").slideDown("slow");
            $("#mensaje").css("visibility", "visible");
        $("h3").css("color", "firebrick");
    }
</script>
</body>
</html>







