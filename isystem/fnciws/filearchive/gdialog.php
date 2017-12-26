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

}elseif($evtype=="replaceFile"){

?>
<title>Переместить файл в другой раздел</title>
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

        $res=mysql_query("SELECT id,name FROM iws_arfiles_department ORDER BY name");
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
/*---------------------------------изменение информации о фаиле-------------------------------*/
}elseif($evtype=="editFile"){

?>
<title>Изменение информации о файле</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251>
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
function SymbolToSpec(text)
{

   var re = new RegExp(String.fromCharCode(34),"gi");
   text = text.replace(re, "&quot;");
   re = new RegExp(String.fromCharCode(147),"gi");
   text = text.replace(re, "&quot;");
   re = new RegExp(String.fromCharCode(148),"gi");
   text = text.replace(re, "&quot;");
   re = new RegExp(String.fromCharCode(132),"gi");
   text = text.replace(re, "&quot;");
   re = new RegExp(String.fromCharCode(8220),"gi");
   text = text.replace(re, "&quot;");
   re = new RegExp(String.fromCharCode(8221),"gi");
   text = text.replace(re, "&quot;");
   re = new RegExp(String.fromCharCode(8222),"gi");
   text = text.replace(re, "&quot;");


   re = new RegExp(String.fromCharCode(171),"gi");
   text = text.replace(re, "&laquo;");
   re = new RegExp(String.fromCharCode(187),"gi");
   text = text.replace(re, "&raquo;");


   re = new RegExp(String.fromCharCode(39),"gi");
   text = text.replace(re, "&#39;");
   re = new RegExp(String.fromCharCode(130),"gi");
   text = text.replace(re, "&#39;");
   re = new RegExp(String.fromCharCode(145),"gi");
   text = text.replace(re, "&#39;");
   re = new RegExp(String.fromCharCode(146),"gi");
   text = text.replace(re, "&#39;");
   re = new RegExp(String.fromCharCode(180),"gi");
   text = text.replace(re, "&#39;");
   re = new RegExp(String.fromCharCode(8216),"gi");
   text = text.replace(re, "&#39;");
   re = new RegExp(String.fromCharCode(8217),"gi");
   text = text.replace(re, "&#39;");
   re = new RegExp(String.fromCharCode(8218),"gi");
   text = text.replace(re, "&#39;");

   re = new RegExp(String.fromCharCode(44),"gi");
   text = text.replace(re, "&#44;");

   return text;
}

  if (!Nname.value || !Nauthors.value || !Ndescription.value){
        alert("Не заполнены все поля! ");
  } else {
        var arr = new Array();
        arr["Nname"] = SymbolToSpec(Nname.value);
        arr["Nauthors"] = SymbolToSpec(Nauthors.value);
        arr["Ndescription"] = SymbolToSpec(Ndescription.value);

        window.returnValue = arr;
        window.close();

  }

//-->
</SCRIPT>

<?php
        include('../../inc/config.inc.php');
        
        $dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
        @mysql_select_db($dbname) or die("Не могу выбрать базу");
   mysql_query('SET NAMES "cp1251"');
 
         $res=mysql_fetch_assoc(mysql_query("SELECT authors,name,description FROM iws_arfiles_records WHERE id='".$_GET[id]."'")); 


?>
<SCRIPT LANGUAGE=JavaScript for=window EVENT=onload>
<!--
function SpecToSymbol(text)
{
   text = text.replace(/&laquo;/g, String.fromCharCode(171));
   text = text.replace(/&raquo;/g, String.fromCharCode(187));
   text = text.replace(/&quot;/g, String.fromCharCode(34));
   text = text.replace(/&#39;/g, String.fromCharCode(39));
   text = text.replace(/&#44;/g, String.fromCharCode(44));
   

   return text;
}

Nname.value= SpecToSymbol('<?echo $res[name];?>');
Nauthors.value= SpecToSymbol('<?echo $res[authors];?>');
Ndescription.value= SpecToSymbol('<?echo $res[description];?>');

//-->
</SCRIPT>

<table>
<tr><td>Название</td><td>

<input name=Nname size="60" ></td></tr>
<tr><td>Авторы</td><td>
<input name=Nauthors size="60" ></td></tr>
<tr><td valign=top>Описание</td><td>
<textarea name=Ndescription type="text" cols="38" rows="10" ></textarea></td></tr></table>
<div align=right><hr>
<input ID=Ok TYPE=SUBMIT value="Сохранить">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</div>

<?php

}


?>
</body>
</html>
