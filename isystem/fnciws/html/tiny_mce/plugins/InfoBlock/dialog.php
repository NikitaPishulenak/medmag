<?php

session_start();
session_register("mainadvar");
include('../../../../../inc/config.inc.php');

?>
<HTML>
<HEAD>
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
   <script type="text/javascript" src="../../tiny_mce_popup.js"></script>
   <script type="text/javascript" src="../../utils/mctabs.js"></script>
   <script type="text/javascript" src="../../utils/form_utils.js"></script>
   <script type="text/javascript" src="../../utils/validate.js"></script>
<STYLE TYPE="text/css">
BODY   {font-family:Arial; font-size:8px; BACKGROUND-COLOR:buttonface}
input.Ok {border: 1px #000000 solid; color: #ffffff;background-color: #6D6D6D; font-size:7pt;}
TABLE  {font-family:Arial; font-size:11px}
input {border: 1px #6C6C6C solid; font-size:8pt;}
input.chk {border: 0px #ffffff solid;}
select {border: 1px #6C6C6C solid; font-size:8pt;}
hr {color:#6C6C6C; height:1pt}
</STYLE>
<title>Вставка информационного блока</title>
</HEAD>
<script>
function KeyPress()
{
   if(window.event.keyCode == 27)
      window.close();
}
</script>

<BODY onKeyPress="KeyPress()">
<BODY onKeyPress="KeyPress()">
<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
   tinyMCEPopup.execCommand("mceInsertContent", false, vrs.value);
   tinyMCEPopup.close();
// -->
</SCRIPT>
<br>
<TABLE width=100% CELLPADDING="2" border="0">
  <TR>
    <TD><select name=vrs style="width=100%" size=9 onchange="javascript:Ok.disabled=false">  
<?php 
      
      $dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
      @mysql_select_db($dbname) or die("Не могу выбрать базу");
      mysql_query('SET NAMES "cp1251"');

      $res = mysql_query("SELECT name,vr FROM iws_vars ".((isset($vrtp) && $vrtp>=0) ? 'WHERE lc='.$vrtp.' || lc=8 || lc=0 || lc=3' : 'WHERE lc=8 || lc=3')." ORDER BY lc,name");
      while($ar = mysql_fetch_row($res)) echo "<option value=\"".$ar[1]."\">".$ar[0]."</option>";
?>
      </select>
      </TD>
  </TR>
</TABLE>
<div align=right><hr>
<input ID=Ok name=Ok TYPE=SUBMIT value="Выбрать" DISABLED>&nbsp;&nbsp;
<input type="button" id="cancel" name="cancel" value="Отменить" onclick="tinyMCEPopup.close();" />
</div>
</TABLE>
</BODY>
</HTML>