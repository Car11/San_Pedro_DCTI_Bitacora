<?php
if (!isset($_SESSION)) 
    session_start();
include_once('class/Globals.php');
?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Calendario Operaciones CDC</title>
    
    <script src="js/jquery.js" type="text/jscript"></script> 
    <script src="js/Cal.js" type="text/jscript"></script>                   
    <link href="css/Cal.css?v=<?php echo Globals::cssversion; ?>" rel="stylesheet" />

</head>
<body>
    <header>
        <h1>OPER cal</h1>        
        <div id="logo"><img src="img/Logoice.png" height="75" > </div>  
        <div id="fechahora"><span id="date"></span></div>        
    </header>

    <aside>        
        <div id="btn-operacion" class='icon-box'><img class='icon' src="img/Operacion.png" alt='op'> </div>
        <div id="btn-vacaciones" class='icon-box'><img class='icon' src="img/Vacaciones.png" alt='vac'> </div>  
        <div id="oper-selectall" class='icon-box'><img class='icon' src="img/123.png" alt='sel' > </div>  
        <div id="oper-unselectall" class='icon-box'><img class='icon' src="img/123.png" alt='un' > </div>
        <div id="oper-only" class='icon-box'><img class='icon' src="img/123.png" alt='onl' > </div>
        <div id="oper-add" class='icon-box'><img class='icon' src="img/123.png" alt='add' > </div>
    </aside>

    <section>       
        <div class='bloq-hours'>  
            <div class='hour'>07:00</div>
            <div class='hour'>08:00</div>
            <div class='hour'>09:00</div>
            <div class='hour'>10:00</div>
            <div class='hour'>11:00</div>
            <div class='hour'>12:00</div>
            <div class='hour'>13:00</div>
            <div class='hour'>14:00</div>
            <div class='hour'>15:00</div>
            <div class='hour'>16:00</div>
            <div class='hour'>17:00</div>
            <div class='hour'>18:00</div>
            <div class='hour'>19:00</div>
            <div class='hour'>20:00</div>
            <div class='hour'>21:00</div>
            <div class='hour'>22:00</div>
            <div class='hour'>23:00</div>
            <div class='hour'>00:00</div>
            <div class='hour'>01:00</div>
            <div class='hour'>02:00</div>
            <div class='hour'>03:00</div>
            <div class='hour'>04:00</div>
            <div class='hour'>05:00</div>
            <div class='hour'>06:00</div>
        </div>
        <div id='container'>  
            <div id='calendario'>                          
            </div>
        </div>        
        
    </section>

     <!-- MODAL formulario -->
     <div class="modal" id="modal-oper-add" >
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Información del Visitante</h2>                
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <div id="form">
                    <form name="form-oper-add" id='form-oper-add' method="POST" >
                        <label for="inioper-inioper"><span class="campoperfil">OPERADOR<span class="required">*</span></span>
                            <input autofocus type="text"  id="oper-ini"                                 
                                class="input-field" name="oper-ini" title="OPERADOR"  required >
                        </label>
                        <label for="dia"><span class="campoperfil">DIA <span class="required">*</span></span>
                            <input type="text" class="input-field" name="oper-day" value="" id="oper-day" required >
                        </label>
                        <label for="nombre"><span class="campoperfil">Turno<span class="required">*</span></span>
                            <input  required type="text" class="input-field" name="oper-turn" id="oper-turn"/>
                        </label>
                        <nav class="btnfrm">
                            <ul>
                                <li><button type="button" class="nbtn_blue" onclick="AddOperDay()" >Guardar</button></li>
                                <li><button type="button" class="nbtn_gray" onclick="Cerrar()" >Cerrar</button></li>
                            </ul>
                        </nav>

                    </form>
                    
                </div>
            </div>    
            
            <div class="modal-footer">
                <br>
            </div>

        </div>
    </div>      
    <!-- FIN MODAL -->


</body>

</html>
