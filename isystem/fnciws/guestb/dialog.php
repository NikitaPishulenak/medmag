<?php
include('../../exit.php');
?>
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
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link rel="stylesheet" type="text/css" href="../../style.css">
<script>
<!--
function KeyPress() { if(window.event.keyCode == 27) window.close(); }

//-->
</script>
<?php

if($evtype=="edtCategory"){

?>
<title>Категория</title>
</HEAD>
<BODY onKeyPress="KeyPress()">
<SCRIPT LANGUAGE=JavaScript FOR=window EVENT=onload>
<!--

cname.value = window.dialogArguments["cname"];
if(window.dialogArguments["cact"]){ cact.checked=true; }
// -->
</SCRIPT>
<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
if (cname.value){
  var arr = new Array();
  arr["cname"] = cname.value;
  if(cact.checked==true){
     arr["cact"] = 1;
  }else{
     arr["cact"] = 0;
 }
  window.returnValue = arr;
  window.close();
}else{
   alert("Не введено наименование категории! ");
}
// -->
</SCRIPT>
<br>
<TABLE width=100% CELLPADDING="0" align="center" border="0">
  <TR>
    <TD align=rigth>Наименование категории:&nbsp;</td>
    <TD>
      <input type="text" size="35" name="cname" maxlength=120 value="">
   </TD>
  </TR>
    <TD></td>
    <TD>
      <input type="checkbox" class=chk name="cact"> активировать
   </TD>
  </TR>
</TABLE>
<div align=right><hr>
<input ID=Ok TYPE=SUBMIT value="Сохранить">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</div>


<?php

}elseif($evtype=="addCategory"){

?>
<title>Новая категория</title>
</HEAD>
<BODY onKeyPress="KeyPress()">
<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
if (cname.value){
  var arr = new Array();
  arr["cname"] = cname.value;
  if(cact.checked==true){
     arr["cact"] = 1;
  }else{
     arr["cact"] = 0;
 }
  window.returnValue = arr;
  window.close();
}else{
   alert("Не введено наименование категории! ");
}
// -->
</SCRIPT>
<br>
<TABLE width=100% CELLPADDING="0" align="center" border="0">
  <TR>
    <TD align=rigth>Наименование категории:&nbsp;</td>
    <TD>
      <input type="text" size="35" name="cname" maxlength=120 value="">
   </TD>
  </TR>
  <TR>
    <TD></td>
    <TD>
      <input type="checkbox" class=chk name="cact"> активировать
   </TD>
  </TR>
</TABLE>
<div align=right><hr>
<input ID=Ok TYPE=SUBMIT value="Добавить">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</div>

<?php

}elseif($evtype=="replacePos"){

?>
<title>Переместить запись в другую категорию</title>
</HEAD>
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
    <TD align=right class=wrd nowrap>Выберите категорию:</td>
    <TD width=100%>
      <select name="blk" style="width:100%">
      <option value=0 selected>Общая</option>
<?php
   include('../../inc/config.inc.php');
   
   $dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
   @mysql_select_db($dbname) or die("Не могу выбрать базу");
   mysql_query('SET NAMES "cp1251"');

   $res=mysql_query("select id,name from iws_guestbk_category where id!=$idCat");
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

} else {

?>

<title>Просмотр вопроса</title></head>
<body onKeyPress="KeyPress()">

<?php
   include('../../inc/config.inc.php');
   include('guest.inc.php');  

   $dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
   @mysql_select_db($dbname) or die("Не могу выбрать базу");
   mysql_query('SET NAMES "cp1251"');

   $res=mysql_query("select DATE_FORMAT(".$fieldnmn['dat'].",'%W, %e %M %Y в %T'),"
                  .$fieldnmn['nm'].",".$fieldnmn['em'].",".$fieldnmn['ww'].",".$fieldnmn['icq'].","
                  .$fieldnmn['ct'].",".$fieldnmn['cr'].",".$fieldnmn['cm']." from "
                  .$natbl." where ".$fieldnmn['did']."=".$id);
      if(mysql_numrows($res)>=1){
         $arr=mysql_fetch_row($res);
         $viewQ.="<table width=100% cellpadding=2><tr><td align=center><b>".$arr[1]."</b></td>"; 
//       if($arr[3]){ $viewQ.="<td><a class=im target=_blank href=\"".$arr[3]."\"><img src=\"../../images/icon_www.gif\" border=0 alt=\"homepage: ".$arr[3]."\"></a></td>"; }
         if($arr[3]){ $viewQ.="<td><b>".$arr[3]."</b></td>"; }
         if($arr[2]){ $viewQ.="<td><a class=im href=\"mailto:".$arr[2]."\"><img src=\"../../images/icon_email.gif\" border=0 alt=\"e-mail: ".$arr[2]."\"></a></td>"; } 
         if($arr[4]){ $viewQ.="<td><a class=im target=_blank href=\"http://wwp.icq.com/".$arr[4]."#pager\"><img src=\"../../images/icon_icq.gif\" border=0 alt=\"icq: ".$arr[4]."\" valign=top></a></td>"; } 
         if($arr[5]){ $viewQ.="<td><b>".$arr[5]."</b></td>"; } 
         if($arr[6]){ $viewQ.="<td><b>".$arr[6]."</b></td>"; } 
         $viewQ.="<td width=100%></td></tr>"
         ."<tr><td colspan=7><br>".$arr[7]."</td></tr>"
         ."<tr><td colspan=7><br>".$arr[0]."</td></tr></table>";
         echo $viewQ;
      }

}
?>

</body></html>