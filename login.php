<?php
    $ID="";
if (isset($_GET['ID'])) {
    $ID=$_GET['ID'];
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Bitácora de Ingreso DCTI San Pedro</title>
    <link href="css/estilo.css" rel="stylesheet"/>
    <script src="js/jquery.js" type="text/jscript"></script>
    <script src="js/funciones.js" languaje="javascript" type="text/javascript"></script>
     <script>
        function onVuelve() {
            location.href = "index.php";
        }
        //
        $('#username').attr("autocomplete", "off");
        $('#password').attr("autocomplete", "off");
        setTimeout('$("#username").val("");', 100);
        setTimeout('$("#password").val("");', 100);
    </script>

</head>
<body>
    <header>
        <h1>BITÁCORA DE ENTRADA & SALIDA</h1>        
        <div id="logo"><img src="img/logoice.png" height="75" > </div>
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
                            <li> <input type="submit" value="Ingresar" id="login" /></li>
                            <li><button type="button" onclick="onVuelve()">Cancelar</button></li>
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







