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


</body>

</html>
