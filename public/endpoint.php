<?php

?>
<!DOCTYPE html>
<html>
<head>
    <script>
        function showHint(str) {
            if (str.length == 0) {
                document.getElementById("txtHint").innerHTML = "";
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
                    } else {
                        console.log("state: " + xmlhttp.readyState);
                        console.log("status: " + xmlhttp.status);
                    }
                }
                xmlhttp.open("GET", "http://zb.funda.info/frontend/geo/suggest/?niveau=3&max=3&type=koop&query="+str, true);
                xmlhttp.setRequestHeader('Access-Control-Allow-Origin', '*')
                xmlhttp.send();
            }
        }
    </script>
</head>
<body>

<p><b>Start typing a name in the input field below:</b></p>
<form>
    First name: <input type="text" onkeyup="showHint(this.value)">
</form>
<p>Suggestions: <span id="txtHint"></span></p>
</body>
</html>