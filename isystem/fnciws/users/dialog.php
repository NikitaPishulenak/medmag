<?php

include('../../exit.php');

if($act=="addG"){

?>
<HTML>
<HEAD>
<STYLE TYPE="text/css">
input.Ok {border: 1px #000000 solid; color: #ffffff;background-color: #6D6D6D; font-size:7pt;}
input {border: 1px #6C6C6C solid; font-size:7pt;}
td.wrd {font-family:Arial; font-size:8pt; }
</STYLE>
<title>Новая группа пользователей</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</HEAD>
<script>
function KeyPress()
{
   if(window.event.keyCode == 27)
      window.close();
}
</script>
<BODY onKeyPress="KeyPress()" bgcolor="buttonface">
<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
if(nme.value){
  window.returnValue = nme.value;
  window.close();
}else{
 alert("Не введено имя группы!     ");
   nme.focus();
}
//-->
</SCRIPT>
<br>
<TABLE CELLPADDING="0" width=100% border="0">
  <TR>
    <TD align=right class=wrd><nobr>Имя группы:</nobr></td>
    <TD  width=230>
      <input name="nme" maxlength=30 style="width:100%">
      </TD>
  </TR>
</TABLE>
<CENTER><br>
<input ID=Ok TYPE=SUBMIT value="Добавить">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</CENTER>
</body></html>
<?php

}if($act=="replG"){

?>
<HTML>
<HEAD>
<STYLE TYPE="text/css">
input.Ok {border: 1px #000000 solid; color: #ffffff;background-color: #6D6D6D; font-size:7pt;}
input {border: 1px #6C6C6C solid; font-size:7pt;}
select {border: 1px #6C6C6C solid; font-size:7pt;}
td.wrd {font-family:Arial; font-size:8pt; }
</STYLE>
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<title>Переместить пользователя в группу</title>
</HEAD>
<script>
function KeyPress()
{
   if(window.event.keyCode == 27)
      window.close();
}
</script>
<BODY onKeyPress="KeyPress()" bgcolor="buttonface">
<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
  window.returnValue = gr.value;
  window.close();
//-->
</SCRIPT>
<br>
<TABLE CELLPADDING="0" width=100% border="0">
  <TR>
    <TD align=right class=wrd><nobr>Выбирите группу:</nobr></td>
    <TD  width=190>
      <select name="gr" style="width:100%">
<?php

include('usr.inc.php');
include('../../inc/config.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
@mysql_select_db($dbname) or die("Не могу выбрать базу");
mysql_query('SET NAMES "cp1251"');

$result=mysql_query("select ".$gfld['did'].",".$gfld['nme']." from $gtbl");
if(mysql_numrows($result)>=1){
   while($arr=mysql_fetch_row($result)) echo "<option value=".$arr[0]." selected>".$arr[1]."</option>";

}

?>
      </select>
      </TD>
  </TR>
</TABLE>
<CENTER><br>
<input ID=Ok TYPE=SUBMIT value="Переместить">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</CENTER>
</body></html>
<?php
}
?>
