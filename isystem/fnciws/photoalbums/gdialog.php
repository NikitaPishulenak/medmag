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
<title>Новое название рубрики</title>
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
  if(activ.checked==true){ arr["activ"] = 1; } else { arr["activ"] = 0; }
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
    <TD>Наименование рубрики&nbsp;</td>
    <TD><input type="text" size="40" name="cname" maxlength=60 value=""></TD>
  </TR>
  <TR>
    <TD align=right>Виден в фотогалерее&nbsp;</td>
    <TD><input class=chk type=checkbox name=activ checked></TD>
  </TR>
</TABLE>
<div align=right><hr>
<input ID=Ok TYPE=SUBMIT value="Добавить">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</div>

<?php

}elseif($evtype=="edtdepartment"){

?>
<title>Название рубрики</title>
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
if(window.dialogArguments["activ"] == 1) activ.checked=true;
// -->
</SCRIPT>
<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
if (cname.value){
  var arr = new Array();
  arr["cname"] = cname.value;
  if(activ.checked==true){ arr["activ"] = 1; } else { arr["activ"] = 0; }
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
    <TD>Наименование рубрики&nbsp;</td>
    <TD><input type="text" size="40" name="cname" maxlength=60 value=""></TD>
  </TR>
  <TR>
    <TD align=right>Виден в фотогалерее&nbsp;</td>
    <TD><input class=chk type=checkbox name=activ></TD>
  </TR>
</TABLE>
<div align=right><hr>
<input ID=Ok TYPE=SUBMIT value="Сохранить">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</div>

<?php

}elseif($evtype=="replaceAlbum"){

?>
<title>Переместить альбом в другую рубрику</title>
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
    <TD align=right class=wrd nowrap>Выберите рубрику:</td>
    <TD width=100%>
                <select name="blk" style="width:100%">
                <option value=0>Без рубрики</option>
<?php
        include('../../inc/config.inc.php');
        
        $dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
        @mysql_select_db($dbname) or die("Не могу выбрать базу");
        mysql_query('SET NAMES "cp1251"');

        $res=mysql_query("SELECT id,name FROM iws_photos_category ORDER BY name");
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

}elseif($evtype=="editAlbum"){

?>
<title>Изменение информации об альбоме</title>
<meta http-equiv="Content-Type" content="text/html"; charset="windows-1251">
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

  if (!dt.value || !Ntitle.value){
        alert("Не заполнены все поля!   ");
  } else {
        var arr = new Array();
   
        arr["dt"] = dt.value;
        arr["Ntitle"] = SymbolToSpec(Ntitle.value);
        arr["Ndescription"] = SymbolToSpec(Ndescription.value);
      temp=getSelectedIndexes(hashtag);
      arr["HashTags"]=temp.join('#');
      window.returnValue = arr;
      window.close();

  }
   function getSelectedIndexes (hashtag)
   {
      var hash = new Array();
      for (var i=0; i < hashtag.options.length; i++)
      {
         if (hashtag.options[i].selected) hash.push(hashtag.options[i].value);
      }
   return hash;
   };

//-->                  
</SCRIPT>

<?php
        include('../../inc/config.inc.php');
        
        $dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
        @mysql_select_db($dbname) or die("Не могу выбрать базу");
        mysql_query('SET NAMES "cp1251"');
 
        $res=mysql_fetch_assoc(mysql_query("SELECT DATE_FORMAT(data,'%d.%m.%Y') as data, title, description,hashtags FROM iws_photos_albums WHERE id='".$_GET[id]."'")); 


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

dt.value= '<?echo $res[data];?>';
Ntitle.value= SpecToSymbol('<?echo $res[title];?>');
Ndescription.value= SpecToSymbol('<?echo $res[description];?>');

//-->
</SCRIPT>

<table>
<tr><td>Дата</td><td><input name=dt size=8 maxlength=10></td></tr>
<tr><td>Название</td><td><input name=Ntitle size="60" ></td></tr>
<tr><td valign=top>Описание</td><td><textarea name=Ndescription type="text" cols="38" rows="10" ></textarea></td></tr>
<tr><td valign=top>Хэштэги</td><td valign=top><select size='5' multiple name='hashtag' style=" width:325px;">
<?php

$res1=mysql_query("SELECT name FROM iws_photos_hashtags ORDER BY name");
                        if(mysql_numrows($res1)>=1){
                            while($arr1=mysql_fetch_row($res1)){
                               if( substr_count($res[hashtags],"#".$arr1[0]."#")>0 || substr_count($res[hashtags],$arr1[0]."#")>0 || substr_count($res[hashtags],"#".$arr1[0])>0 || $res[hashtags]==$arr1[0]){                            
                                 echo "<option selected value='".$arr1[0]."'>".$arr1[0]."</option>"; 
                               }else{
                                 echo "<option value='".$arr1[0]."'>".$arr1[0]."</option>";  
                               }
                            }
                        }
            
?>
 </select></td></tr></table><div align=right><hr>
<input ID=Ok TYPE=SUBMIT value="Сохранить">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</div>

<?php

}elseif($evtype=="editPhoto"){

?>
<title>Изменение описания изображения</title>
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

        var arr = new Array();
        arr["Ndescription"] = SymbolToSpec(Ndescription.value);
        window.returnValue = arr;
        window.close();
//-->
</SCRIPT>

<?php
        include('../../inc/config.inc.php');
        
        $dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
        @mysql_select_db($dbname) or die("Не могу выбрать базу");
        mysql_query('SET NAMES "cp1251"');
 
        $res=mysql_fetch_assoc(mysql_query("SELECT alt FROM iws_photos_records WHERE id='".$_GET[id]."'")); 


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

Ndescription.value= SpecToSymbol('<?echo $res[alt];?>');

//-->
</SCRIPT>


<table>
<tr><td valign=top>Описание изображения</td><td><textarea name=Ndescription type="text" cols="38" rows="10" ></textarea></td></tr></table>
<div align=right><hr>
<input ID=Ok TYPE=SUBMIT value="Сохранить">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</div>

<?php

}elseif($evtype=="addTag"){
?>
<title>Добавление хэштэга</title>
<BODY >
<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
if (tagname.value){
      var arr = new Array();
        arr["TagName"] =tagname.value;
        window.returnValue = arr;
        window.close();
}else{
        alert("Не введено название тэга! ");
        tagname.focus();
}
// -->
</SCRIPT>
<table>
<tr><td>Имя хэштэга</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input NAME="tagname" style="width:280px;"></td></tr></table><hr>
<div style="float:right;"><tr><td><input ID=Ok TYPE=SUBMIT value="Сохранить"></td>
<td><input TYPE=BUTTON ONCLICK="window.close();" value="Отмена"></td></tr></div>

</body>
<?php
}
?>
</body>
</html>
