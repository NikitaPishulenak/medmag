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
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<?php

if($evtype=="adddepartment"){

?>
<title>Новая рубрика</title>
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
  arr["pos"] = pos.value;
  arr["banner"] = banner.value;
  if(activ.checked==true){ arr["activ"] = 1; } else { arr["activ"] = 0;  }
  window.returnValue = arr;
  window.close();
}else{

   alert("Не введено наименование рубрики! ");
   cname.focus();

}
// -->
</SCRIPT>
<br>
<TABLE CELLPADDING="0" align="center" border="0">
  <TR>
    <TD>Наименование рубрики:&nbsp;</td>
    <TD>
      <input type="text" size="60" name="cname" maxlength=60 value="">
   </TD>
  </TR>
  <TR>
    <TD align=right>Позиция:&nbsp;</td>
    <TD>
      <input type="text" size="2" name="pos" maxlength=3 value="1">&nbsp;&nbsp;&nbsp; Активна: <input class=chk type=checkbox name=activ checked> 
   </TD>
  </TR>
  <TR>
    <TD align=right valign=top>Код баннера:&nbsp;</td>
    <TD>
      <textarea cols="40" rows="6" name="banner"></textarea>
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
<title>Рубрика</title>
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
  arr["pos"] = pos.value;
  arr["banner"] = banner.value;
  if(activ.checked==true){ arr["activ"] = 1; } else { arr["activ"] = 0;  }
  window.returnValue = arr;
  window.close();
}else{

   alert("Не введено наименование рубрики! ");
   cname.focus();

}
// -->
</SCRIPT>
<br>
<?php
   include('../../inc/config.inc.php');
   
   $dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
   @mysql_select_db($dbname) or die("Не могу выбрать базу");
   mysql_query('SET NAMES "cp1251"');

   $res=mysql_query("SELECT name,pos,activ,banner FROM iws_art_department WHERE id=".$mid);
   $arr=mysql_fetch_row($res);
?>
<TABLE CELLPADDING="0" align="center" border="0">
  <TR>
    <TD>Наименование рубрики:&nbsp;</td>
    <TD>
      <input type="text" size="60" name="cname" maxlength=60 value="<?php echo $arr[0]; ?>">
   </TD>
  </TR>
  <TR>
    <TD align=right>Позиция:&nbsp;</td>
    <TD>
      <input type="text" size="2" name="pos" maxlength=3 value="<?php echo $arr[1]; ?>">&nbsp;&nbsp;&nbsp; Активна: <input class=chk type=checkbox name=activ <?php if($arr[2]==1) echo "checked"; ?>>
   </TD>
  </TR>
  <TR>
    <TD align=right valign=top>Код баннера:&nbsp;</td>
    <TD>
      <textarea cols="40" rows="6" name="banner"><?php echo $arr[3]; ?></textarea>
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
<title>Переместить новость в другую рубрикку</title>
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
    <TD align=right class=wrd nowrap>Выберите рубрику:</td>
    <TD width=100%>
      <select name="blk" style="width:100%">
<?php
   include('../../inc/config.inc.php');
   
   $dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
   @mysql_select_db($dbname) or die("Не могу выбрать базу");
   mysql_query('SET NAMES "cp1251"');

      $res=mysql_query("SELECT id,name FROM iws_art_department WHERE mid<=0 ORDER BY pos");
      if(mysql_numrows($res)>=1){
         while($arr=mysql_fetch_row($res)){
            echo "<option value=".$arr[0].">".$arr[1]."</option>\n";

            $resSub=mysql_query("SELECT id,name FROM iws_art_department WHERE mid=".$arr[0]." ORDER BY pos");
            if(mysql_numrows($resSub)>=1){
               while($arr=mysql_fetch_row($resSub)){
                  echo "<option value=".$arr[0].">&#160;&#160;&#160;&#160;-&#160;".$arr[1]."</option>\n";
               }
            }
         }
      }

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
