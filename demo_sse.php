<html>

<head>
    <meta charset="UTF-8">
    <script>
        //For sending message
        this.sendMsg = function() {
            msg = document.getElementById("cedula").value;
            this.ajaxSent();
            return false;
        };
                
        //sending message to server
        this.ajaxSent = function() {
            try {
                xhr = new XMLHttpRequest();
            } catch (err) {
                alert(err);
            }
            //alert(msg.value);
            //alert(xhr.status);
            url='envianotificaciondinamica.php?msg='+msg;
            //alert(url);
            xhr.open('GET', url, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {                    
                    if (xhr.status == 200) {   
                        msg.value = "";
                    }
                }
            };
            xhr.send();
        };

    </script>
</head>

<body>
<form onsubmit="sendMsg(); return false;">
    
        <input type="text" maxlength="9" id="cedula" name="cedula" />
        <input type="submit" value="Enviar" id="enviar" />
    </form>
</body>

</html>