<!doctype html>
<html>

<head>
    <script src="js/jquery.js" type="text/jscript"></script>
    <script type="text/javascript" charset="utf8" src="DataTables/datatables.js"></script>
    <link href="css/estilo.css" rel="stylesheet" />
    <meta charset="UTF-8">
    <title>Inicio</title>

    <script>
        function onVuelve() {
           <?php 
                // load the data and delete the line from the array 
                $lines = file('_chat.txt'); 
                //print $lines;exit;
                $last = sizeof($lines) - 1 ; 
                unset($lines[$last]); 
                // write the new data to the file 
                $fp = fopen('_chat.txt', 'w'); 
                fwrite($fp, implode('', $lines)); 
                fclose($fp); 

            ?>
        }
        $( "result" ).click(function() {
            
            
        });
        //pushed server data
        if (typeof(EventSource) !== "undefined") {
            //alert("EVENTSOURCE");
            var source = new EventSource("envianotificaciondinamica.php");
            source.onmessage = function(event) {
                //alert(event.data);
                document.getElementById("result").innerHTML  = event.data;
                
                               
            };
        } else {
            document.getElementById("result").innerHTML = "Sorry, your browser does not support server-sent events...";
        }

    </script>

</head>

<body>
    <h1>visitantes</h1>
    <br>
    <h1>Getting server updates</h1>
    <div id="result" name="result">Resultado...</div>
    <input type=text class="textarea-field" id="detalle" name="detalle" placeholder="NO HAY PENDIENTES">
    <button type="button" onclick="onVuelve()">Volver </button>
</body>

</html>
