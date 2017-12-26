<?php

include('../../exit.php');

   include('../../inc/config.inc.php');

?>
<HTML>
<HEAD>
<STYLE TYPE="text/css">
BODY   {font-family:Arial; font-size:8px; BACKGROUND-COLOR:buttonface}
input.Ok {border: 1px #000000 solid; color: #ffffff;background-color: #6D6D6D; font-size:7pt;}
TABLE  {font-family:Arial; font-size:11px}
input {border: 1px #6C6C6C solid; font-size:8pt;}
hr {color:#6C6C6C; height:1pt}
</STYLE>
<title>Вставка гиперссылки на файл</title>
</HEAD>
<script>
function KeyPress()
{
   if(window.event.keyCode == 27)
      window.close();
}
</script>
<BODY onKeyPress="KeyPress()">
<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
   var ar = "<?php echo $hostName; ?>"+urlt;
  window.returnValue = ar;
  window.close();
// -->
</SCRIPT>
<script>
<!--
   var urlt;
   function filelist_OnFileSelect(strPath)
   {
      urlt=strPath;
      Ok.disabled=false;
   }
//-->
</script>
<TABLE width=100% HEIGHT=90% CELLPADDING="1" border="0">
<tr>
<td width="10">&nbsp;</td>
<td height=100% bgcolor=#6C6C6C>
<iframe name="filelist" src="filemanf.php" width="100%" height="100%" frameborder=0 marginwidth=1 marginheight=1></iframe>
</td>
</tr>
</TABLE>
<div align=right><hr>
<input ID=Ok TYPE=SUBMIT value="Выбрать" DISABLED>&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</div>
</BODY>
</HTML>
