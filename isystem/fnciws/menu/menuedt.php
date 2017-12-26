<?php
include('../../exit.php');
?>
<html><head>
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link rel="stylesheet" type="text/css" href="../../style.css">
<STYLE TYPE="text/css">
   BODY { margin:10; }
   input {border: 1px #6C6C6C solid; font-size:7pt;}
   hr {color:#6C6C6C; height:1pt}
   .tbovr{BORDER-BOTTOM: #A0A0A0 solid 1px; BORDER-LEFT: #FFFFFF solid 1px; BORDER-RIGHT: #A0A0A0 solid 1px; BORDER-TOP: #FFFFFF solid 1px;}
   .tbdwn{BORDER-BOTTOM: #FFFFFF solid 1px;BORDER-LEFT: #A0A0A0 solid 1px;BORDER-RIGHT: #FFFFFF solid 1px;BORDER-TOP: #A0A0A0 solid 1px;}
   .tb{BORDER: buttonface solid 1px;}
   .spr{BORDER-LEFT: #909090 solid 1px;FONT-SIZE: 0px;TOP: 0px;HEIGHT: 23px;WIDTH: 0px;}
</STYLE>
<script>
<!--
var pt = null;

function KeyPress() { if(window.event.keyCode == 27) window.close(); }

function closeWin()
{
   if(pt && pt==true && btnSave==1)
   {
      saveMN();
      return false;
   }
}
//-->
</script>
<title>Редактирование меню</title></head>
<body bgcolor=buttonface onKeyPress="KeyPress()" onload="buildReport('menuView.php?adm=<?php echo $adm; ?>&blok=<?php echo $blok; ?>');" onunload="closeWin();">
<script>

               function buildReport(url) {
                  var dt = new Date();
                  createXMLHttpRequest("callback", url+'&nocache='+dt.getTime());
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
                     if (xmlReq.status == 200){
                        document.getElementById("retS").innerHTML = xmlReq.responseText;
                        if(pt && pt==true) document.all["savemenu"].disabled=false;
                     }
                  }
               }
</script>
<div id=retS></div></body></html>