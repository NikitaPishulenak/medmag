<?php
include('../../exit.php');

include('../../inc/config.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
@mysql_select_db($dbname) or die("Не могу выбрать базу");
mysql_query('SET NAMES "cp1251"');


if($func=="addBL"){
include('menu.inc.php');

?>
<HTML>
<HEAD>
<link rel="stylesheet" type="text/css" href="../../style.css">
<STYLE TYPE="text/css">
input.Ok {border: 1px #000000 solid; color: #ffffff;background-color: #6D6D6D; font-size:7pt;}
input {border: 1px #6C6C6C solid; font-size:7pt;}
td.wrd {font-family:Arial; font-size:8pt; }
select { border: 1px #ffffff solid; font-size:7pt; }
hr {color:#6C6C6C; height:1pt}
</STYLE>
<title>Добавление нового блока меню</title>
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
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
  var arr = new Array();
  arr["name"] = nme.value;
  arr["dst"] = dst.value;
  arr["inTemplate"] = template.value;
  if(activeB.checked==true){ arr["activeB"] = 1; } else { arr["activeB"] = 0;  }
  window.returnValue = arr;
  window.close();
}else{
   alert("Невведено название блока меню!   ");
   nme.focus();
}
//-->
</SCRIPT>
<br>
<TABLE CELLPADDING="0" align="center" border="0">
  <TR>
    <TD align=right class=wrd>Название блока меню:</td>
    <TD>
      <input type="text" size="50" name="nme" value="" maxlength="50">
   </TD>
  </TR>
  <TR>
    <TD align=right class=wrd><br>Доступ пользователей:</td>
    <TD><br>
      <select name="dst" style="width:100%">
      <option value=0 selected>Полный (для всех)</option>
<?php
   $res = mysql_query("select ".$ugfld['did'].",".$ugfld['nme']." from ".$ugtbl);
   while($arr=mysql_fetch_row($res)){ echo "<option value=".$arr[0]."> . ".$arr[1]."</option>\n"; }

?>
      </select>
   </TD>
  </TR>
  <TR>
    <TD align=right class=wrd>Шаблон дизайна:</td>
    <TD>
      <select name="template" style="width:100%">
      <option value=0 selected>Общего дизайна сайта</option>
<?php
   $rest=mysql_query("SELECT id, name FROM iws_html_templ WHERE inTemplate=1 ORDER BY name");
   while($arr=mysql_fetch_row($rest)){ echo "<option value=".$arr[0]."> . ".$arr[1]."</option>\n"; }

?>
      </select>
   </TD>
  </TR>
  <TR>
    <TD align=right class=wrd><br>Активировать блок меню:</td>
    <TD><br>
      <input class=chk type=checkbox name=activeB>
   </TD>
  </TR>
</TABLE>
<div align=right><hr>
<input ID=Ok TYPE=SUBMIT value="Добавить">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</div>
</body></html>
<?php

}else if($func=="actBL"){
include('menu.inc.php');

List($nameB, $activB, $usrgrpB, $inTemplateB)=mysql_fetch_row(mysql_query("SELECT name, activ, usrgrp, inTemplate FROM iws_blockmenu WHERE notdel=0 AND bid=".$bId));

?>
<HTML>
<HEAD>
<link rel="stylesheet" type="text/css" href="../../style.css">
<STYLE TYPE="text/css">
input.Ok {border: 1px #000000 solid; color: #ffffff;background-color: #6D6D6D; font-size:7pt;}
input {border: 1px #6C6C6C solid; font-size:7pt;}
td.wrd {font-family:Arial; font-size:8pt; }
select { border: 1px #ffffff solid; font-size:7pt; }
hr {color:#6C6C6C; height:1pt}
</STYLE>
<title>Настройки блока <?php echo $nameB; ?></title>
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
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
  var arr = new Array();
  arr["dst"] = dst.value;
  arr["inTemplate"] = template.value;
  if(activeB.checked==true){ arr["activeB"] = 1; } else { arr["activeB"] = 0;  }
  window.returnValue = arr;
  window.close();
//-->
</SCRIPT>
<br>
<TABLE CELLPADDING="0" align="center" border="0">
  <TR>
    <TD align=right class=wrd>Доступ пользователей:</td>
    <TD>
      <select name="dst" style="width:260px;">
      <option value=0 selected>Полный (для всех)</option>
<?php
   $res = mysql_query("select ".$ugfld['did'].",".$ugfld['nme']." from ".$ugtbl);
   while($arr=mysql_fetch_row($res)){ 
      echo "<option value=".$arr[0];
      if($arr[0]==$usrgrpB) echo " selected";

      echo "> . ".$arr[1]."</option>\n";
   }

?>
      </select>
   </TD>
  </TR>
  <TR>
    <TD align=right class=wrd>Шаблон дизайна:</td>
    <TD>
      <select name="template" style="width:100%">
      <option value=0 selected>Общего дизайна сайта</option>
<?php
   $rest=mysql_query("SELECT id, name FROM iws_html_templ WHERE inTemplate=1 ORDER BY name");
   while($arr=mysql_fetch_row($rest)){
      echo "<option value=".$arr[0];
      if($arr[0]==$inTemplateB) echo " selected";

      echo "> . ".$arr[1]."</option>\n";  
   }

?>
      </select>
   </TD>
  </TR>
  <TR>
    <TD align=right class=wrd><br>Активировать блок меню:</td>
    <TD><br>
      <input class=chk type=checkbox name=activeB<?php if($activB==1) echo " checked"; ?>>
   </TD>
  </TR>
</TABLE>
<div align=right><hr>
<input ID=Ok TYPE=SUBMIT value="Сохранить">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</div>
</body></html>
<?php

}else if($func=="rplMN"){

session_start();
session_register("mainadvar");

include('menu.inc.php');

?>
<HTML>
<HEAD>
<STYLE TYPE="text/css">
input.Ok {border: 1px #000000 solid; color: #ffffff;background-color: #6D6D6D; font-size:7pt;}
input {border: 1px #6C6C6C solid; font-size:7pt;}
select { border: 1px #ffffff solid; font-size:7pt; }
td.wrd {font-family:Arial; font-size:8pt; }
hr {color:#6C6C6C; height:1pt}
</STYLE>
<title>Перемещение меню в другой блок</title>
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
<BODY onKeyPress="KeyPress()" bgcolor="buttonface">
<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
  window.returnValue = blk.value;
  window.close();
//-->
</SCRIPT>
<br>
<TABLE width=95% CELLPADDING="0" align="center" border="0">
  <TR>
    <TD align=right class=wrd nowrap>Выберите блок меню:</td>
    <TD width=100%>
      <select name="blk" style="width:100%">
<?php
   $res=mysql_query("select ".$fieldnmb['did'].",".$fieldnmb['nme']." from ".$batbl." where ".$fieldnmb['nd']."=0 and ".$fieldnmb['did']."!=$blok and ".$fieldnmb['lng']."='".$mainadvar['lng']."' ORDER BY ".$fieldnmb['did']);
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
</body></html>
<?php

}elseif($func=="addMN"){

session_start();
session_register("mainadvar");


include('menu.inc.php');

?>
<HTML>
<HEAD>
<STYLE TYPE="text/css">
input.Ok {border: 1px #000000 solid; color: #ffffff;background-color: #6D6D6D; font-size:7pt;}
input {border: 1px #6C6C6C solid; font-size:7pt;}
td.wrd {font-family:Arial; font-size:8pt; }
select { border: 1px #ffffff solid; font-size:7pt; }
hr {color:#6C6C6C; height:1pt}
</STYLE>
<title>Добавление нового пункта меню</title>
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
<BODY onKeyPress="KeyPress()" bgcolor="buttonface">
<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
if(nme.value){
  var arr = new Array();
  arr["name"] = nme.value;
  arr["tml"] = 0;
  arr["pos"] = pos.value;
  arr["type"] = typeMN.value;
  window.returnValue = arr;
  window.close();
}else{
   alert("Невведено название пункта меню!   ");
   nme.focus();
}
//-->
</SCRIPT>
<br>
<TABLE CELLPADDING="2" align="center" border="0">
  <TR>
    <TD align=right class=wrd><nobr>Название меню:</nobr></td>
    <TD>
      <input type="text" size="50" name="nme" value="">
   </TD>
  </TR>
  <TR>
    <TD align=right class=wrd>Тип страницы:</td>
    <TD>
      <select name="typeMN">
<?php
   $res=mysql_query("select ".$typefld["did"].",".$typefld["nme"]." from ".$typetbl." where ".$typefld["thc"].">=1 ORDER BY ".$typefld["did"]);
   $i=0;
   while($art=mysql_fetch_row($res)){
      echo "<option value=".$art[0];
      if(!$i){
         echo " selected";
         $i++;
      }
      echo ">".$art[1]."</option>\n";
   }

?>
      </select>
   </TD>
  </TR>

<?php
/*
  <TR>
    <TD align=right class=wrd><nobr>Шаблон страницы:</nobr></td>
    <TD>
      <select name="tml">
      <option value=0 selected>без шаблона</option>
<?php
   $rest=mysql_query("select ".$tmplfld["did"].",".$tmplfld["nme"]." from ".$tmpltbl." WHERE ".$tmplfld["lng"]."='".$mainadvar['lng']."' ORDER BY ".$tmplfld["nme"]);
   while($arr=mysql_fetch_row($rest)){ echo "<option value=".$arr[0].">".$arr[1]."</option>\n"; }

?>
      </select>
   </TD>
  </TR>
*/
 ?>

  <TR>
    <TD align=right class=wrd>Позиция:</td>
    <TD>
      <select name="pos">
         <option value=3>выше выделенного</option>
         <option value=2 selected>ниже выделенного</option>
<?php
         if($chld) echo "<option value=4>дочерним выделенного</option>\n";

?>
      </select>
   </TD>
  </TR>
</TABLE>
<div align=right><hr>
<input ID=Ok TYPE=SUBMIT value="Добавить">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</div>
</body></html>
<?php

}elseif($func=="newMN"){

include('menu.inc.php');
$mnAr = explode("|%|",$mnu);
$cnt = count($mnAr);
$actb = true;


if($cnt){
   if(mysql_query("DELETE from ".$matbl." where ".$fieldnmm["block"]."=".$blok." and ".$fieldnmm["level"].">0")){
      for($i=0;$i<=($cnt-1);$i++){
         $mnAr[$i] = explode(",",$mnAr[$i]);
            if($mnAr[$i][8] && $mnAr[$i][8] > 0){                                                              //вставка существующих страниц
               $insql = "INSERT into ".$matbl." (".$fieldnmm["did"].",".$fieldnmm["block"].",".$fieldnmm["left"].",".$fieldnmm["right"].","
                           .$fieldnmm["level"].",".$fieldnmm["descr"].",".$fieldnmm["url2"].",".$fieldnmm["type"].","
                           .$fieldnmm["edt"].",".$fieldnmm["tm"].") values (".$mnAr[$i][8].",".$blok.",".$mnAr[$i][2].",".$mnAr[$i][3].","
                           .$mnAr[$i][4].",'".$mnAr[$i][0]."','".$mnAr[$i][1]."',".$mnAr[$i][5].",".$mnAr[$i][6].",".$mnAr[$i][7].")";          
               mysql_query($insql);
               $actb = false;
            }else{                                                                                             //вставка новой страницы
               $insql = "INSERT into ".$matbl." (".$fieldnmm["block"].",".$fieldnmm["left"].",".$fieldnmm["right"].","
                           .$fieldnmm["level"].",".$fieldnmm["descr"].",".$fieldnmm["url2"].",".$fieldnmm["type"].","
                           .$fieldnmm["edt"].",".$fieldnmm["tm"].") values (".$blok.",".$mnAr[$i][2].",".$mnAr[$i][3].","
                           .$mnAr[$i][4].",'".$mnAr[$i][0]."','".$mnAr[$i][1]."',".$mnAr[$i][5].",".$mnAr[$i][6].",".$mnAr[$i][7].")";          
               if(mysql_query($insql)){
                  list($did) = mysql_fetch_row(mysql_query("SELECT ".$fieldnmm["did"]." from ".$matbl." where ".$fieldnmm["block"]."=".$blok." and ".$fieldnmm["left"]."=".$mnAr[$i][2]." and ".$fieldnmm["descr"]."='".$mnAr[$i][0]."'"));
                  if(empty($robots)) { $robots="all"; }
                  mysql_query("INSERT into ".$pstbl." (".$psfld["md"].",".$psfld["content"].",title,robots,keywords,descr) values (".$did.",'".$newpage."','".$ttle."','".$robots."','".$keyw."','".$descr."')");

//                mysql_query("INSERT into ".$pstbl." (".$psfld["md"].",".$psfld["content"].") values (".$did.",'".$newpage."')");
                  if($mnAr[$i][5] == 2) mysql_query("INSERT into ".$pptbl." (".$ppfld["md"].") values (".$did.")");
                  $actb = false;
               }else{
                  header("location: ../../left.php?typ=content&blok=$blok&err=3");
               }
         }
      }
         if($actb) mysql_query("UPDATE ".$batbl." SET ".$fieldnmb["act"]."=0 where ".$fieldnmb["did"]."=".$blok);
   }else{
      header("location: ../../left.php?typ=content&blok=$blok&err=3");
   }
}
header("location: ../../left.php?typ=content&blok=$blok");


}elseif($func=="saveMN"){

include('menu.inc.php');
$mnAr = explode("|%|",$mnu);
$cnt = count($mnAr);
$actb = true;

if($cnt){
   if(mysql_query("DELETE from ".$matbl." where ".$fieldnmm["block"]."=".$blok." and ".$fieldnmm["level"].">0")){
      for($i=0;$i<=($cnt-1);$i++){
         $mnAr[$i] = explode(",",$mnAr[$i]);
         if($mnAr[$i][8] > 0){                                                               //удаление пункта меню
            mysql_query("DELETE from ".$pstbl." where ".$psfld["md"]."=".$mnAr[$i][9]);
            if($mnAr[$i][5] == 2){
               mysql_query("DELETE from ".$pmtbl." where ".$pmfld["md"]."=".$mnAr[$i][9]);
               mysql_query("DELETE from ".$pptbl." where ".$ppfld["md"]."=".$mnAr[$i][9]);
            }                                                                                //конец удаление пункта меню
         }else{
            if($mnAr[$i][9] && $mnAr[$i][9] > 0){                                                              //вставка существующей страницы
               $insql = "INSERT into ".$matbl." (".$fieldnmm["did"].",".$fieldnmm["block"].",".$fieldnmm["left"].",".$fieldnmm["right"].","
                           .$fieldnmm["level"].",".$fieldnmm["descr"].",".$fieldnmm["url2"].",".$fieldnmm["type"].","
                           .$fieldnmm["edt"].",".$fieldnmm["tm"].") values (".$mnAr[$i][9].",".$blok.",".$mnAr[$i][2].",".$mnAr[$i][3].","
                           .$mnAr[$i][4].",'".$mnAr[$i][0]."','".$mnAr[$i][1]."',".$mnAr[$i][5].",".$mnAr[$i][6].",".$mnAr[$i][7].")";          
               mysql_query($insql);
               $actb = false;
            }
         }
      }
         if($actb) mysql_query("UPDATE ".$batbl." SET ".$fieldnmb["act"]."=0 where ".$fieldnmb["did"]."=".$blok);
   }else{
      header("location: ../../left.php?typ=content&blok=$blok&err=3");
   }
}
header("location: ../../left.php?typ=content&blok=$blok");



//-------------------------------------------------------------------------------------------------------
}elseif($func=="prfMNp"){

include('pref.inc.php');
   if($act == "prefOk"){
         $sql="update ".$pmtbl." set ";
         if($lnk){
            $sql.=$pmfld['lnk']."=1";
         } else {
            $sql.=$pmfld['lnk']."=0";
         }
         $sql.=" where ".$pmfld['did']."=".$did." and ".$pmfld['md']."=".$menu;
         if(!mysql_query($sql)){
            header("location: allwindow.php?err=1&func=prfMNp&nme=$nme&menu=$menu");
            return;
         } else {    
            echo "<HTML><HEAD>"
            ."<title>Изменение настроек $nme</title>"
            ."</head><body bgcolor=buttonface>"
            ."<h4>Настройки модуля $nme успешно сохранены</h4>"
           ."<script><!--\n"
               ."setTimeout(\"window.close()\",2000);\n"
               ."//--></script>"
               ."</body></html>";
         }

   }else{
      echo "<HTML><HEAD>"
      ."<title>Изменение настроек </title>"
      ."<link rel=\"stylesheet\" type=\"text/css\" href=\"../../style.css\">"
      ."</head>"
      ."<script>\n"
      ."function KeyPress()\n"
      ."{\n"
      ."if(window.event.keyCode == 27) window.close();\n"
      ."}\n"
      ."</script>\n"
      ."<BODY onKeyPress=\"KeyPress()\" bgcolor=buttonface>";
      $res = mysql_query("select ".$pmfld['did'].",".$pmfld['lnk']." from ".$pmtbl." where ".$pmfld['md']."=".$menu);
      if(mysql_numrows($res)>=1){
         IF($err==1){
            echo "<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
            ."Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
         }
         list($did,$lnk)=mysql_fetch_row($res);
         echo "<table width=100% border=0 cellpadding=3 cellspacing=0>"
         ."<tr><td align=center class=usr>Настройки вывода записей $nme</td></tr></table>"
         ."<script><!--
            function submitr(fr){
                  fr.submit();
            }
            //--></script>"
         ."<form method=\"post\" name=frm>"
         ."<input type=hidden name=func value=prfMNp>"
         ."<input type=hidden name=did value=$did>"
         ."<input type=hidden name=menu value=$menu>"
         ."<input type=hidden name=nme value=\"$nme\">"
         ."<input type=hidden name=act value=prefOk>"
         ."<table width=100% border=0 cellpadding=3 cellspacing=2 align=center>"
         ."<tr><td align=right>Использовать короткое содержание<br>в качестве ссылки открытия: </td><td>"
         ."<input class=chk type=checkbox name=\"lnk\"";
         if($lnk){ echo " checked"; }
         echo "></td></tr>"
         ."<tr><td colspan=2><hr></td></tr>"
         ."<tr><td></td><td align=right><input class=but type=button name=btn value=Сохранить onClick=\"submitr(frm)\">"
         ."&nbsp;&nbsp<input class=but type=button value=\"Отмена\" onclick=\"window.close()\"></td></tr>"
         ."</form></table>"
         ."</body></html>";
      }else{
         echo "<h4>Настройки модуля $nme недоступны, так как пункт меню не сохранен!<br><br>Данная функция будет доступна после сохранения нового пункта меню</h4>"
        ."<script><!--\n"
            ."setTimeout(\"window.close()\",4000);\n"
            ."//--></script>"
         ."</body></html>";
      }
   }

}

?>
