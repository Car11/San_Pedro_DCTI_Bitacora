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
    <link href="css/Estilo.css?v=<?php echo Globals::cssversion; ?>" rel="stylesheet" />
    <script src="js/jquery.js" type="text/jscript"></script>
    <script src="js/Funciones.js" languaje="javascript" type="text/javascript"></script>
     <script>
        $('#username').attr("autocomplete", "off");
        $('#password').attr("autocomplete", "off");
        setTimeout('$("#username").val("");', 100);
        setTimeout('$("#password").val("");', 100);
    </script>

</head>
<body>
    <header>
        <h1>Control de Acceso - Centros de Datos Corporativos</h1>        
        <div id="logo"><img src="img/Logoice.png" height="75" > </div>
        <div id="fechahora"><span id="date"></span></div>
    </header>
    
    <aside></aside>
    
    <section>
       <h2>Ingrese su usuario y contraseña</h2>
        <div id="form">
            <div class="login">    
                <form  name="Usuario" action="request/EnviaUsuario.php" method="POST">                      
                    <input type="text" id="username" class="input-field" name="username" placeholder="USUARIO" maxlength="20" /><br>
                    <input type="password" id="password" class="input-field" name="password" placeholder="CONTRASEÑA" maxlength="20" />
                    <nav class="btnfrm">
                        <ul>
                            <li> <input class="nbtn_blue" type="submit" value="Ingresar" id="login" /></li>
                        </ul>
                    </nav>
                </form>      
                <div id="invalido">
                    <h3>Usuario o Contraseña Inválido</h3>
                </div>
            </div>     
        </div>
        
    </section>       
    
    <aside></aside>
    
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







