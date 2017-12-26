<?php
include('../../exit.php');
?>
<html>
<head>
<STYLE>
BODY {margin:10; font-family:Verdana, Arial; background:buttonface; font-size:10pt;}
input {border: 1px #6C6C6C solid; font-size:7pt;}
hr {color:#6C6C6C; height:1pt}
</STYLE>
<title>Проверка связанности данных</title></head>
<script>
      <!--
      function KeyPress()
            {
               if(window.event.keyCode == 27) window.close();
            }
      //-->
</script>
<body onKeyPress="KeyPress();" onload="buildReport('reDel.php');">
<script>

function buildReport(url) {
var dt = new Date();
createXMLHttpRequest("callback", url+'?nocache='+dt.getTime());
}

function createXMLHttpRequest(responseFunction, url) {
   if (window.ActiveXObject) {
      xmlReq = new ActiveXObject("Microsoft.XMLHTTP");
   } else if (window.XMLHttpRequest) {
      xmlReq = new XMLHttpRequest();
   } 
   xmlReq.open("GET", url, true);
   xmlReq.onreadystatechange = eval(responseFunction);
   xmlReq.send(null);
}

function callback() {
   if (xmlReq.readyState == 4){
      if (xmlReq.status == 200) {
         document.getElementById("ret").innerHTML = xmlReq.responseText;
      }
   }
}
</script>
<div id=ret></div>
</body></html>

