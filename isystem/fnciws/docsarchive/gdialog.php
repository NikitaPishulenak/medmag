<?php
include('../../exit.php');
?>
<HTML>
<HEAD>
<STYLE TYPE="text/css">
BODY   {margin-left:10; font-family:Arial; font-size:11px; BACKGROUND-COLOR:buttonface}
input.Ok {border: 1px #000000 solid; color: #ffffff;background-color: #6D6D6D; font-size:8pt;}
TABLE  {font-family:Arial; font-size:11px}
P      {text-align:center}
input {border: 1px #6C6C6C solid; font-size:8pt;}
select {border: 1px #6C6C6C solid; font-size:8pt;}
input.chk {border: 0px #ffffff solid;}
hr {color:#6C6C6C; height:1pt}
</STYLE>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<?php

if($evtype=="adddepartment"){

?>
<title>Новое название раздела</title>
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
if (cname.value){
  var arr = new Array();
  arr["cname"] = cname.value;
  window.returnValue = arr;
  window.close();
}else{

   alert("Не введено наименование раздела! ");
   cname.focus();

}
// -->
</SCRIPT>
<br>
<TABLE CELLPADDING="0" align="center" border="0">
  <TR>
    <TD>Наименование раздела:&nbsp;</td>
    <TD>
      <input type="text" size="40" name="cname" maxlength=60 value="">
   </TD>
  </TR>
</TABLE>
<div align=right><hr>
<input ID=Ok TYPE=SUBMIT value="Добавить">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</div>

<?php

}elseif($evtype=="edtdepartment"){

?>
<title>Название раздела</title>
</HEAD>
<script>
function KeyPress()
{
   if(window.event.keyCode == 27)
      window.close();
}
</script>
<BODY onKeyPress="KeyPress()">
<SCRIPT LANGUAGE=JavaScript FOR=window EVENT=onload>
<!--
cname.value = window.dialogArguments["cname"];
// -->
</SCRIPT>
<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
if (cname.value){
  var arr = new Array();
  arr["cname"] = cname.value;
  window.returnValue = arr;
  window.close();
}else{
alert("Не введено наименование раздела! ");
cname.focus();
}
// -->
</SCRIPT>
<br>
<TABLE CELLPADDING="0" align="center" border="0">
  <TR>
    <TD>Наименование раздела:&nbsp;</td>
    <TD>
      <input type="text" size="40" name="cname" maxlength=60 value="">
   </TD>
  </TR>
</TABLE>
<div align=right><hr>
<input ID=Ok TYPE=SUBMIT value="Сохранить">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</div>

<?php

}elseif($evtype=="replaceDoc"){

?>
<title>Переместить документ в другой раздел</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
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
  window.returnValue = blk.value;
  window.close();
//-->
</SCRIPT>
<br>
<TABLE width=95% CELLPADDING="0" align="center" border="0">
  <TR>
    <TD align=right class=wrd nowrap>Выберите раздел:</td>
    <TD width=100%>
      <select name="blk" style="width:100%">
      <option value=0>Без раздела</option>
<?php
   include('../../inc/config.inc.php');
   
   $dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
   @mysql_select_db($dbname) or die("Не могу выбрать базу");
   mysql_query('SET NAMES "cp1251"');

   $res=mysql_query("SELECT id,name FROM iws_ardoc_department ORDER BY name");
   while($arr=mysql_fetch_row($res)){ echo "<option value=".$arr[0].">".$arr[1]."</option>\n"; }

?>
      </select>
   </TD>
  </TR>
</TABLE>
<div align=right><hr>
<input ID=Ok TYPE=SUBMIT value="Переместить">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</div>

<?php

}


?>
</body>
</html>
