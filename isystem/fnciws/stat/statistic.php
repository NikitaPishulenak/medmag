<?php

session_start();
session_register("mainadvar");

if(!isset($act) || !$act) $act="month";
if($act=="shD"){
   showD();
}elseif($act=="delP" || $act=="delM" || $act=="delD" || $act=="delDOther" || $act=="delR"){
   delSt();
}else{
   $cont=stats();    
}

//-------------------------------------------------------
//Функция которая выводит форму выбора месяца, в ктором надо посмотреть статистику по дням


function showD(){
global $mainadvar;
include('../../inc/config.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
@mysql_select_db($dbname) or die("Не могу выбрать базу");
mysql_query('SET NAMES "cp1251"');

?>
<HTML>
<HEAD>
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<STYLE TYPE="text/css">
input.Ok {border: 1px #000000 solid; color: #ffffff;background-color: #6D6D6D; font-size:7pt;}
input {border: 1px #6C6C6C solid; font-size:7pt;}
td.wrd {font-family:Arial; font-size:8pt; }
select { border: 1px #ffffff solid; font-size:7pt; }
</STYLE>
<title>Статистика по дням</title>
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
  arr["month"] = mnth.value;
  window.returnValue = arr;
  window.close();
//-->
</SCRIPT>
<br>
<TABLE CELLPADDING="0" width=100% border="0">
  <TR>
    <TD align=right class=wrd><nobr>Выберите месяц:</nobr></td>
    <TD  width=190>
      <select name="mnth" style="width:100%">
<?php

   $result=mysql_query("SELECT DATE_FORMAT(dt,'%Y %M'),DATE_FORMAT(dt,'%Y-%m') FROM iws_statistics GROUP BY DATE_FORMAT(dt,'%Y-%m') ORDER BY dt DESC");
   while($arr=mysql_fetch_row($result)){ echo "<option value=".$arr[1]." selected>".$arr[0]."</option>\n"; }


   $result=mysql_query("SELECT DATE_FORMAT(month,'%Y %M'),DATE_FORMAT(month,'%Y-%m') FROM iws_stat_month GROUP BY DATE_FORMAT(month,'%Y-%m') ORDER BY month DESC");
   while($arr=mysql_fetch_row($result)){ echo "<option value=".$arr[1].">".$arr[0]."</option>\n"; }

?>
      </select>
   </TD>
  </TR>
</TABLE>
<CENTER><br>
<input ID=Ok TYPE=SUBMIT value="Готово">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</CENTER>
</body></html>
<?php
}

//---------------------------------------------------------------
//Основная функция анализа статистики, по часам по дням по месяцам по переходам по разделам

function stats(){
global $hostName,$act,$mnt,$mainadvar;

//$dateminus = "DATE_SUB(dt, INTERVAL 1 HOUR)"; // отнимает 1 час от времени, т.к. сервер расположен в Москве
$dateminus = "dt";

// $timeString если сервер находится в Москве
//$timeString = "CASE id WHEN 2 THEN '00..01' WHEN 3 THEN '01..02' WHEN 4 THEN '02..03' WHEN 5 THEN '03..04' WHEN 6 THEN '04..05' WHEN 7 THEN '05..06' WHEN 8 THEN '06..07' WHEN 9 THEN '07..08' WHEN 10 THEN '08..09' WHEN 11 THEN '09..10' WHEN 12 THEN '10..11' WHEN 13 THEN '11..12' WHEN 14 THEN '12..13' WHEN 15 THEN '13..14' WHEN 16 THEN '14..15' WHEN 17 THEN '15..16' WHEN 18 THEN '16..17' WHEN 19 THEN '17..18' WHEN 20 THEN '18..19' WHEN 21 THEN '19..20' WHEN 22 THEN '20..21' WHEN 23 THEN '21..22' WHEN 24 THEN '22..23' WHEN 1 THEN '23..00' END";

// $timeString если сервер находится в Беларуси
$timeString = "CASE id WHEN 1 THEN '00..01' WHEN 2 THEN '01..02' WHEN 3 THEN '02..03' WHEN 4 THEN '03..04' WHEN 5 THEN '04..05' WHEN 6 THEN '05..06' WHEN 7 THEN '06..07' WHEN 8 THEN '07..08' WHEN 9 THEN '08..09' WHEN 10 THEN '09..10' WHEN 11 THEN '10..11' WHEN 12 THEN '11..12' WHEN 13 THEN '12..13' WHEN 14 THEN '13..14' WHEN 15 THEN '14..15' WHEN 16 THEN '15..16' WHEN 17 THEN '16..17' WHEN 18 THEN '17..18' WHEN 19 THEN '18..19' WHEN 20 THEN '19..20' WHEN 21 THEN '20..21' WHEN 22 THEN '21..22' WHEN 23 THEN '22..23' WHEN 24 THEN '23..00' END";


$ret.="<script><!--
            function goD(){
               arr = showModalDialog(\"fnciws/stat/statistic.php?act=shD\",null,\"dialogWidth:330px; dialogHeight:100px; status:no;\");
               if (arr != null)
               {
               if(arr[\"month\"])
                  document.location='mainiwspref.php?gopr=stat&act=dday&mnt='+ arr[\"month\"];
               }     
        }
            function delOk(ur){
            if(confirm(\"Вы действительно хотите удалить статистические данные?      \"))
               document.location=ur;
            }
         //-->
         </script>";
$ret.="<table cellpadding=1 cellspacing=0 border=0 width=100%>"
      ."<tr><td colspan=2 class=usr>&nbspСтатистика</td></tr>";   
$ret.="<tr><td colspan=2><br>"
      ."<a href=\"mainiwspref.php?gopr=stat&act=hour\">по часам...</a><br>"
      ."<a href=\"#\" onclick=\"goD()\">по дням...</a><br>"
      ."<a href=\"mainiwspref.php?gopr=stat&act=month\">по месяцам...</a><br>"
      ."<a href=\"mainiwspref.php?gopr=stat&act=frm\">по переходам с сайтов...</a><br>"
      ."<a href=\"mainiwspref.php?gopr=stat&act=mn\">по разделам сайта...</a><br>"
     ."______________________<br><br>"
     ."<a href=\"mainiwspref.php?gopr=stat&act=prop\">Настройки</a><br>"
  ."<a href=\"".$hostName."/isystem/fnciws/stat/dump.php\" target=\"_blank\">Упаковка статистических данных</a><br></td></tr>";

switch($act){
// сохранение настроек  
   case "ipupdate":
      if($_GET[ipen]=='on'){ 
         if (mysql_query("UPDATE iws_stat_inner_ip SET enabled='1', ip='".$_GET[ipadr]."' WHERE id=1")){header("location: mainiwspref.php?gopr=stat&act=mn");
      return;}else{$ret="Ошибка сохранения";}
      }else{
         if (mysql_query("UPDATE iws_stat_inner_ip SET enabled='0', ip='".$_GET[ipadr]."' WHERE id=1")){header("location: mainiwspref.php?gopr=stat&act=mn");
      return;}else{$ret="Ошибка сохранения";}
      }

 break;
// редактирование настроек 
   case "prop":
      $result=mysql_query("SELECT enabled,ip FROM iws_stat_inner_ip WHERE id= 1 ");
      $arr=mysql_fetch_row($result); 
      if($arr[0]==1){ $t = "checked";} else { $t ="";}   
      $ret="<table style='width:100%;'><tr><td colspan=2 class=usr >&nbspНастройки фильтрации</td></tr><tr><td><div></div><br><br><form name='frm' method='get' action='mainiwspref.php'>Включить фильтр IP адресов: &nbsp;<input type='checkbox' style='border: none;' name='ipen'".$t."><br><br>"
      ."<input type='hidden' value='stat' name='gopr'><input type='hidden' value='ipupdate' name='act'>"
      ."<div> IP адреса (вводите IP адреса чезрез пробел)<br><textarea  name='ipadr' style=' width:900px; height:300px;'>".$arr[1]."</textarea><br><br><input type='submit' name='ipsub' value='Сохранить'>  &nbsp; &nbsp; <a href=\"mainiwspref.php?gopr=stat&act=month\">отмена</a></form> </div></table>";
   break;   
// анализ статистики по часам
   case "hour":   
      $ret.="<tr><td colspan=2>"
      ."<br><br><b>По часам</b>:"
      ."<table cellpadding=2 cellspacing=1 border=0 align=center>"
      ."<tr><td class=usr width=130 align=center>Часы</td><td class=usr>&nbsp;Кол-во уникальных посещений</td><td class=usr>&nbsp;Кол-во посещенных страниц</td><td class=usr width=130 align=center>Часы</td></tr>";

      $result=mysql_query("SELECT CONCAT(DATE_FORMAT($dateminus,'%H'),'..',DATE_FORMAT(DATE_ADD($dateminus,INTERVAL 1 HOUR),'%H')),count(ip_adr) FROM iws_statistics GROUP BY DATE_FORMAT($dateminus,'%H'),ip_adr,coockie");
      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            $rdo[$arr[0]]+=$arr[1]; $rdn[$arr[0]]++;
            $sum+=$arr[1]; $snm++;
         }
      }

      $result=mysql_query("SELECT $timeString, user, page FROM iws_stat_hour ORDER BY id");
      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            $rdo[$arr[0]]+=$arr[2]; $rdn[$arr[0]]+=$arr[1];
            $sum+=$arr[2]; $snm+=$arr[1];
         }
      }

      if(count($rdo)>=1){
         $mx=max($rdo); $mxu=max($rdn);         

         ksort($rdo);

         while (list($key,$val) = each($rdo)) {
            if($sum>0){ $lnw=($rdo[$key]/$mx); $lnwn=($rdn[$key]/$mxu); } else { $lnwn = 0.1; $lnw = 0.1; }

            $ret.="<tr bgcolor=#F1F1F1><td align=center>".$key."</td>"
            ."<td align=right><small><font color=#CD3601>".$rdn[$key]."</font></small> <img src=\"images/voter.gif\" width=".round(200*$lnwn)." height=8></td>"
            ."<td><img src=\"images/vote.gif\" width=".round(200*$lnw)." height=8> <small><font color=#5B5B5B>".$rdo[$key]."</font></small></td><td align=center>".$key."</td></tr>\n";
         }  

         $ret.="<tr><td colspan=4 class=usr height=1></td></tr><tr><td></td><td> <font color=#CD3601><b>Всего:</b> ".$snm."</font></td><td> <font color=#5B5B5B><b>Всего:</b> ".$sum."</font></td></tr></table>";
         unset($rdo,$rdn,$snm,$sum,$lnw);

      } else {
         $ret.="<tr><td colspan=4 align=center>Статистических данных нет!</td></tr></table>";
      }
   break;



// анализ статистики по дням

   case "dday":   
      $m=explode("-",$mnt);
      $ret.="<tr><td colspan=2>"
      ."<br><br>По дням <b>".date('F Y',mktime(0,0,0,$m[1],1,$m[0]))."</b>г.:"
      ."<table cellpadding=2 cellspacing=1 border=0 align=center>"
      ."<tr><td class=usr width=130 align=center>Дата</td><td class=usr>&nbsp;Кол-во уникальных посещений</td><td class=usr>&nbsp;Кол-во посещенных страниц</td><td  class=usr width=110></td></tr>";

      $today = getdate(); 

      if($m[0]==$today['year'] && $m[1]==$today['mon']){

         $result=mysql_query("SELECT DATE_FORMAT(dt,'%d.%m.%Y (%a)'), count(ip_adr), DATE_FORMAT(dt,'%Y-%m-%d') FROM iws_statistics WHERE dt LIKE '$mnt%' GROUP BY DATE_FORMAT(dt,'%e'),ip_adr,coockie ORDER BY dt");
         if(mysql_numrows($result)>=1){
            while($arr=mysql_fetch_row($result)){
               $rdo[$arr[0]]+=$arr[1]; $rdn[$arr[0]]++; $rdd[$arr[0]]=$arr[2];
               $sum+=$arr[1]; $snm++;
            }

            $mx=max($rdo); $mxu=max($rdn);         
      
            while (list($key,$val) = each($rdo)) {
               if($sum>0){ $lnw=($rdo[$key]/$mx); $lnwn=($rdn[$key]/$mxu); } else { $lnwn = 0.1; $lnw = 0.1; }

               $ret.="<tr bgcolor=#F1F1F1><td>".$key."</td>
               <td align=right><small><font color=#CD3601>".$rdn[$key]."</font></small> <img src=\"images/voter.gif\" width=".round(200*$lnwn)." height=8></td>
               <td><img src=\"images/vote.gif\" width=".round(200*$lnw)." height=8> <small><font color=#5B5B5B>".$rdo[$key]."</font></small></td>
               <td align=center><a href=\"#\" onclick=\"delOk('mainiwspref.php?gopr=stat&act=delD&url=".$rdd[$key]."&mnt=$mnt')\">удалить</a></td></tr>\n";
            }  

            $ret.="<tr><td colspan=4 class=usr height=1></td></tr><tr><td></td><td> <font color=#CD3601><b>Всего:</b> ".$snm."</font></td><td> <font color=#5B5B5B><b>Всего:</b> ".$sum."</font></td></tr></table>";
            unset($rdo,$rdn,$snm,$sum,$lnw);
   
         } else {
            $ret.="<tr><td colspan=4 align=center>Статистических данных нет!</td></tr></table>";
         }
      } else {
         $result=mysql_query("SELECT DATE_FORMAT(day,'%d.%m.%Y (%a)'),SUM(user),SUM(page),DATE_FORMAT(day,'%Y-%m-%d') FROM iws_stat_day WHERE day LIKE '$mnt%' GROUP BY day ORDER BY day");
         if(mysql_numrows($result)>=1){
            while($arr=mysql_fetch_row($result)){
               $rdo[$arr[0]]=$arr[2]; $rdn[$arr[0]]=$arr[1]; $rdd[$arr[0]]=$arr[3];
               $sum+=$arr[2]; $snm+=$arr[1];
            }

            $mx=max($rdo); $mxu=max($rdn);         
      
            while (list($key,$val) = each($rdo)) {
               if($sum>0){ $lnw=($rdo[$key]/$mx); $lnwn=($rdn[$key]/$mxu); } else { $lnwn = 0.1; $lnw = 0.1; }

               $ret.="<tr bgcolor=#F1F1F1><td>".$key."</td>
               <td align=right><small><font color=#CD3601>".$rdn[$key]."</font></small> <img src=\"images/voter.gif\" width=".round(200*$lnwn)." height=8></td>
               <td><img src=\"images/vote.gif\" width=".round(200*$lnw)." height=8> <small><font color=#5B5B5B>".$rdo[$key]."</font></small></td>
               <td align=center><a href=\"#\" onclick=\"delOk('mainiwspref.php?gopr=stat&act=delDOther&url=".$rdd[$key]."&mnt=$mnt')\">удалить</a></td></tr>\n";
            }  

            $ret.="<tr><td colspan=4 class=usr height=1></td></tr><tr><td></td><td> <font color=#CD3601><b>Всего:</b> ".$snm."</font></td><td> <font color=#5B5B5B><b>Всего:</b> ".$sum."</font></td></tr></table>";
            unset($rdo,$rdn,$snm,$sum,$lnw);
         } else {
            $ret.="<tr><td colspan=4 align=center>Статистических данных нет!</td></tr></table>";
         }
      }
   break;

   case "mn":

// анализ статистики по разделам сайта
//----------------------Основные разделы сайта--------------------------------------------------------------------------------------------------

      $ret.="<tr><td colspan=2><br><br><b>По разделам сайта</b>:";

      $ret.="\n<script><!--
         function setCheckboxes(do_check){
            var elts = (typeof(document.forms['mnp'].elements['url[]']) != 'undefined') ? document.forms['mnp'].elements['url[]'] : 'undefined';
            var elts_cnt  = (typeof(elts.length) != 'undefined') ? elts.length : 0;
            if (elts_cnt) {
              for (var i = 0; i < elts_cnt; i++) { elts[i].checked = do_check;  } 
            } else {
              elts.checked = do_check; 
            }
            return true;
          } 
         //--></script>\n
         <form name=mnp action=\"mainiwspref.php\" method=post>
         <input type=\"hidden\" name=gopr value=stat>
         <input type=\"hidden\" name=act value=delR>
         <table cellpadding=1 cellspacing=1 border=0 align=center>";


      $result=mysql_query("SELECT A.url, IF(LENGTH(B.name)>50,CONCAT(LEFT(B.name, 50),'&nbsp;...&nbsp;'),B.name), B.m_level, A.menu, CONCAT(B.blk,'a',B.m_left), user, page FROM iws_stat_menu A LEFT JOIN iws_menu B ON (B.idm=A.menu)");

      if(mysql_numrows($result)>=1){
            while($arr=mysql_fetch_row($result)){
               if($arr[0]=="news"){
                  $arr[1] = "Новости"; $arr[2] = 1;
               }elseif($arr[0]=="main"){
                  $arr[1] = "Главная страница"; $arr[3] = 0;
               }

               if($arr[1]==""){
                  $unkn[$arr[3]] = $arr[0]; $unknown['rdn']+=$arr[5]; $unknown['rdo']+=$arr[6];
               }else{
                  $rd[$arr[3]][0]=$arr[2]; $rd[$arr[3]][1]=$arr[1]; $rd[$arr[3]][2]=$arr[0];
                  $rdn[$arr[3]]+=$arr[5]; $rdo[$arr[3]]+=$arr[6];
                  if(!$arr[3]) { $rdsort[$arr[3]]="a1"; } else {  $rdsort[$arr[3]]="a".$arr[4]; }
               }

               $sum+=$arr[6]; $snm+=$arr[5];
            }
//         mysql_free_result($result);
      }


      $result=mysql_query("SELECT A.url, B.name, B.m_level, 12, CONCAT(B.blk,'a',B.m_left), SUM(user), SUM(page) FROM iws_stat_arts A LEFT JOIN iws_menu B ON (B.idm=12) GROUP BY A.url");

      if(mysql_numrows($result)>=1){
         $arr=mysql_fetch_row($result);
         $rd[$arr[3]][0]=$arr[2]; $rd[$arr[3]][1]=$arr[1]; $rd[$arr[3]][2]=$arr[0];
         $rdn[$arr[3]]+=$arr[5]; $rdo[$arr[3]]+=$arr[6];
         $rdsort[$arr[3]]="a".$arr[4];
         $sum+=$arr[6]; $snm+=$arr[5];

//         mysql_free_result($result);
      }

      $result=mysql_query("SELECT A.url, B.name, B.m_level, 10, CONCAT(B.blk,'a',B.m_left), SUM(user), SUM(page) FROM iws_stat_files A LEFT JOIN iws_menu B ON (B.idm=10) GROUP BY A.url");

      if(mysql_numrows($result)>=1){
         $arr=mysql_fetch_row($result);
         $rd[$arr[3]][0]=$arr[2]; $rd[$arr[3]][1]=$arr[1]; $rd[$arr[3]][2]=$arr[0];
         $rdn[$arr[3]]+=$arr[5]; $rdo[$arr[3]]+=$arr[6];
         $rdsort[$arr[3]]="a".$arr[4];
         $sum+=$arr[6]; $snm+=$arr[5];

//         mysql_free_result($result);
      }

      $result=mysql_query("SELECT A.url, B.name, B.m_level, 11, CONCAT(B.blk,'a',B.m_left), SUM(user), SUM(page) FROM iws_stat_files_A A LEFT JOIN iws_menu B ON (B.idm=11) GROUP BY A.url");

      if(mysql_numrows($result)>=1){
         $arr=mysql_fetch_row($result);
         $rd[$arr[3]][0]=$arr[2]; $rd[$arr[3]][1]=$arr[1]; $rd[$arr[3]][2]=$arr[0];
         $rdn[$arr[3]]+=$arr[5]; $rdo[$arr[3]]+=$arr[6];
         $rdsort[$arr[3]]="a".$arr[4];
         $sum+=$arr[6]; $snm+=$arr[5];

//         mysql_free_result($result);
      }

      $result=mysql_query("SELECT A.url, B.name, B.m_level, 13, CONCAT(B.blk,'a',B.m_left), SUM(user), SUM(page) FROM iws_stat_files_B A LEFT JOIN iws_menu B ON (B.idm=13) GROUP BY A.url");

      if(mysql_numrows($result)>=1){
         $arr=mysql_fetch_row($result);
         $rd[$arr[3]][0]=$arr[2]; $rd[$arr[3]][1]=$arr[1]; $rd[$arr[3]][2]=$arr[0];
         $rdn[$arr[3]]+=$arr[5]; $rdo[$arr[3]]+=$arr[6];
         $rdsort[$arr[3]]="a".$arr[4];
         $sum+=$arr[6]; $snm+=$arr[5];

//         mysql_free_result($result);
      }


      $result=mysql_query("SELECT A.url, B.name, B.m_level, 14, CONCAT(B.blk,'a',B.m_left), SUM(user), SUM(page) FROM iws_stat_files_C A LEFT JOIN iws_menu B ON (B.idm=14) GROUP BY A.url");

      if(mysql_numrows($result)>=1){
         $arr=mysql_fetch_row($result);
         $rd[$arr[3]][0]=$arr[2]; $rd[$arr[3]][1]=$arr[1]; $rd[$arr[3]][2]=$arr[0];
         $rdn[$arr[3]]+=$arr[5]; $rdo[$arr[3]]+=$arr[6];
         $rdsort[$arr[3]]="a".$arr[4];
         $sum+=$arr[6]; $snm+=$arr[5];

//         mysql_free_result($result);
      }


      $result=mysql_query("SELECT A.url, B.name, B.m_level, 16, CONCAT(B.blk,'a',B.m_left), SUM(user), SUM(page) FROM iws_stat_photo A LEFT JOIN iws_menu B ON (B.idm=16) GROUP BY A.url");

      if(mysql_numrows($result)>=1){
         $arr=mysql_fetch_row($result);
         $rd[$arr[3]][0]=$arr[2]; $rd[$arr[3]][1]=$arr[1]; $rd[$arr[3]][2]=$arr[0];
         $rdn[$arr[3]]+=$arr[5]; $rdo[$arr[3]]+=$arr[6];
         $rdsort[$arr[3]]="a".$arr[4];
         $sum+=$arr[6]; $snm+=$arr[5];

//         mysql_free_result($result);
      }

      $result=mysql_query("SELECT A.url, count(A.id), IF(LENGTH(B.name)>50,CONCAT(LEFT(B.name, 50),'&nbsp;...&nbsp;'),B.name), B.m_level, IF(A.url='arts' OR A.url='photo' OR A.url='files' OR A.url='files_A' OR A.url='files_B' OR A.url='files_C',B.idm,A.menu), count(A.ip_adr), CONCAT(B.blk,'a',B.m_left) 
                           FROM iws_statistics A LEFT JOIN iws_menu B ON (B.idm=IF(A.url='arts',12,IF(A.url='photo',16,IF(A.url='files',10,IF(A.url='files_A',11,IF(A.url='files_B',13,IF(A.url='files_C',14,A.menu))))))) 
                           GROUP BY IF(A.url='arts',12,IF(A.url='photo',16,IF(A.url='files',10,IF(A.url='files_A',11,IF(A.url='files_B',13,IF(A.url='files_C',14,A.menu)))))),A.url,A.ip_adr,A.coockie");

      if(mysql_numrows($result)>=1){
            while($arr=mysql_fetch_row($result)){
               if($arr[0]=="news"){
                  $arr[2] = "Новости"; $arr[3] = 1;
               } elseif($arr[0]=="main"){
                  $arr[2] = "Главная страница"; $arr[4] = 0;
               }

               if($arr[2]==""){
                  $unkn[$arr[4]] = $arr[0]; $unknown['rdn']++; $unknown['rdo']+=$arr[1];
               } else {
                  $rd[$arr[4]][0]=$arr[3]; $rd[$arr[4]][1]=$arr[2]; $rd[$arr[4]][2]=$arr[0];
                  $rdn[$arr[4]]++; $rdo[$arr[4]]+=$arr[1];
                  if(!$arr[4]) { $rdsort[$arr[4]]="a1"; } else {  $rdsort[$arr[4]]="a".$arr[6]; }
               }

               $sum+=$arr[1]; $snm++;
            }
//         mysql_free_result($result);
      }

      if(count($rdo)>=1 || count($unknown)>=1){

      $ret.="<tr><td colspan=2 align=right><a href=\"#\" onclick=\"setCheckboxes(true);\">отметить все</a> / <a href=\"#\" onclick=\"setCheckboxes(false);\">снять отметку со всех</a> | 
            <a href=\"#\" onClick=\"mnp.submit();\"><font color=#ff0000>удалить отмеченые</font></a></td></tr>
            <tr><td class=usr></td><td class=usr align=center>&nbsp;Разделы сайта</td><td class=usr>&nbsp;Кол-во уникальных посещений</td><td class=usr>&nbsp;Кол-во посещений раздела</td></tr>";

         $mx=max($rdo); $mxu=max($rdn);   
      
         if(isset($unknown)){
            $i=0;
            while (list($key,$val) = each($unkn)){
               if($i){ $unknown['url'].= "%|%"; } else { $i=1; }
               $unknown['url'].= $val."||".$key;
            }
            if($unknown['rdo']>$mx) $mx = $unknown['rdo'];
            if($unknown['rdn']>$mxu) $mxu = $unknown['rdn'];

            $ret.="<tr bgcolor=#c0c0c0><td><input type=checkbox class=chk name=\"url[]\" value=\"".$unknown['url']."\"></td>
                  <td><b>Неизвестно</b></td><td align=right><small><font color=#CD3601>".$unknown['rdn']."</font></small> <img src=\"images/voter.gif\" width=".round(200*($unknown['rdn']/$mxu))." height=8></td>
                  <td><img src=\"images/vote.gif\" width=".round(200*($unknown['rdo']/$mx))." height=8> <small><font color=#5B5B5B>".$unknown['rdo']."</font></small></td></tr>\n";
         }
         
         natsort($rdsort);
         reset($rdsort);
         while (list($key,$val) = each($rdsort)) {
            if($sum>0){ $lnw=($rdo[$key]/$mx); $lnwn=($rdn[$key]/$mxu); } else { $lnwn = 0.1; $lnw = 0.1; }

            $ret.="\n<tr bgcolor=#F1F1F1><td><input type=checkbox class=chk name=\"url[]\" value=\"".$rd[$key][2]."||".$key."\"></td>
                  <td>".str_repeat("&nbsp;&nbsp;&nbsp;",$rd[$key][0]*2)."- ".($key==13 ? '<a title="Подробнее..." href="mainiwspref.php?gopr=stat&act=files_B">'.$rd[$key][1].'</a>' :
                     ($key==11 ? '<a title="Подробнее..." href="mainiwspref.php?gopr=stat&act=files_A">'.$rd[$key][1].'</a>' :
                     ($key==10 ? '<a title="Подробнее..." href="mainiwspref.php?gopr=stat&act=files">'.$rd[$key][1].'</a>' : 
                     ($key==12 ? '<a title="Подробнее..." href="mainiwspref.php?gopr=stat&act=arts">'.$rd[$key][1].'</a>' : 
                     ($key==16 ? '<a title="Подробнее..." href="mainiwspref.php?gopr=stat&act=photo">'.$rd[$key][1].'</a>' : 
                     ($key==14 ? '<a title="Подробнее..." href="mainiwspref.php?gopr=stat&act=files_C">'.$rd[$key][1].'</a>' : $rd[$key][1]
                     ))))))."</td>
                  <td align=right><small><font color=#CD3601>".$rdn[$key]."</font></small> <img src=\"images/voter.gif\" width=".round(200*$lnwn)." height=8></td>
                  <td><img src=\"images/vote.gif\" width=".round(200*$lnw)." height=8> <small><font color=#5B5B5B>".$rdo[$key]."</font></small></td></tr>\n";
         }
         $ret.="<tr><td colspan=4 class=usr height=1></td></tr><tr><td></td><td></td><td> <font color=#CD3601><b>Всего:</b> ".$snm."</font></td><td> <font color=#5B5B5B><b>Всего:</b> ".$sum."</font></td></tr></table></form>";

         unset($rdo,$rd,$rdn,$sum,$lnw,$snm,$arr,$unknown,$rdsort);

      } else {
         $ret.="<tr><td colspan=4><b>Разделы сайта:</b> статистических данных нет!</td></tr></table></form>";
      }

   // mysql_free_result($result);

   break;

//------------------------------------------------------------------------------------------------------------------------------------------
// анализ статистики по просмотрам фотографий (по альбомам)


   case "photo":

      $ret.="<tr><td colspan=2><br><br><b>По просмотрам фотоальбомов</b>:";

      $result=mysql_query("SELECT B.id, SUM(A.user), SUM(A.page), B.title 
                           FROM iws_stat_photo A LEFT JOIN iws_photos_albums B ON (B.id=A.menu) 
                           GROUP BY B.id");

      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            if(!$arr[3]){
               $unknown['rdn']+=$arr[1]; $unknown['rdo']+=$arr[2];
            } else {
               $rdo[$arr[0]."i"]+=$arr[2]; $rdn[$arr[0]."i"]+=$arr[1]; $rdd[$arr[0]."i"]=$arr[3];
            }
            $sum+=$arr[2]; $snm+=$arr[1];
         }
//         mysql_free_result($result);
      }


      $result=mysql_query("SELECT B.id, count(A.id), B.title 
                           FROM iws_statistics A LEFT JOIN iws_photos_albums B ON (B.id=A.menu) WHERE A.url='photo' 
                           GROUP BY B.id,A.ip_adr,A.coockie");

      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            if(!$arr[2]){
               $unknown['rdn']++; $unknown['rdo']+=$arr[1];
            } else {
               $rdo[$arr[0]."i"]+=$arr[1]; $rdn[$arr[0]."i"]++; $rdd[$arr[0]."i"]=$arr[2];
            }
            $sum+=$arr[1];
            $snm++;
         }
//         mysql_free_result($result);
      }

      $ret.="\n<table cellpadding=3 cellspacing=1 border=0 align=center>";

      if(count($rdo)>=1 || count($unknown)>=1){

      $ret.="<tr><td class=usr align=center>&nbsp;Альбомы</td><td class=usr>&nbsp;Кол-во уникальных просмотров</td>
            <td class=usr>&nbsp;Кол-во просмотров альбома</td></tr>";

         $mx=max($rdo);
         $mxu=max($rdn);   
      
         if(isset($unknown)){
            $i=0;
            if($unknown['rdo']>$mx) $mx = $unknown['rdo'];
            if($unknown['rdn']>$mxu) $mxu = $unknown['rdn'];

            $ret.="\n<tr bgcolor=#c0c0c0><td><b>Неизвестно</b></td>
                  <td align=right><small><font color=#CD3601>".$unknown['rdn']."</font></small> <img src=\"images/voter.gif\" width=".round(200*($unknown['rdn']/$mxu))." height=8></td>
                  <td><img src=\"images/vote.gif\" width=".round(200*($unknown['rdo']/$mx))." height=8> <small><font color=#5B5B5B>".$unknown['rdo']."</font></small></td></tr>\n";
         }
         
         asort($rdd);
         reset($rdd);
         while (list($key,$val) = each($rdd)) {
            if($sum>0){ $lnw=($rdo[$key]/$mx); $lnwn=($rdn[$key]/$mxu); } else { $lnwn = 0.1; $lnw = 0.1; }

            $ret.="\n<tr bgcolor=#F1F1F1><td>".$rdd[$key]."</td>
                  <td align=right><small><font color=#CD3601>".$rdn[$key]."</font></small> <img src=\"images/voter.gif\" width=".round(200*$lnwn)." height=8></td>
                  <td><img src=\"images/vote.gif\" width=".round(200*$lnw)." height=8> <small><font color=#5B5B5B>".$rdo[$key]."</font></small></td></tr>\n";
         }
         $ret.="<tr><td colspan=3 class=usr height=1></td></tr>
               <tr><td></td><td> <font color=#CD3601><b>Всего:</b> ".$snm."</font></td>
               <td> <font color=#5B5B5B><b>Всего:</b> ".$sum."</font></td></tr></table>";

         unset($rdo,$rdn,$sum,$lnw,$snm,$arr,$unknown);
      } else {
         $ret.="<tr><td colspan=3><b>Фотоальбомы:</b> статистических данных нет!</td></tr></table></form>";
      }
//      mysql_free_result($result);
   break;



//------------------------------------------------------------------------------------------------------------------------------------------
// анализ статистики по скачиванию журнала Военная медицина (по разделам)


   case "files_B":

      $ret.="<tr><td colspan=2><br><br><b>По скачиванию журнала Военная медицина</b>:";

      $result=mysql_query("SELECT B.department, SUM(A.user), SUM(A.page), (SELECT C.name FROM iws_arfiles_B_department C WHERE C.id=B.department)
                           FROM iws_stat_files_B A LEFT JOIN iws_arfiles_B_records B ON (B.id=A.menu) 
                           GROUP BY B.department");

      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            if(!$arr[3]){
               $unknown['rdn']+=$arr[1]; $unknown['rdo']+=$arr[2];
            } else {
               $rdo[$arr[0]."i"]+=$arr[2]; $rdn[$arr[0]."i"]+=$arr[1]; $rdd[$arr[0]."i"]=$arr[3];
            }
            $sum+=$arr[2]; $snm+=$arr[1];
         }
//         mysql_free_result($result);
      }


      $result=mysql_query("SELECT B.department, count(A.id), (SELECT C.name FROM iws_arfiles_B_department C WHERE C.id=B.department) 
                           FROM iws_statistics A LEFT JOIN iws_arfiles_B_records B ON (B.id=A.menu) WHERE A.url='files_B' 
                           GROUP BY B.department,A.ip_adr,A.coockie");

      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            if(!$arr[2]){
               $unknown['rdn']++; $unknown['rdo']+=$arr[1];
            } else {
               $rdo[$arr[0]."i"]+=$arr[1]; $rdn[$arr[0]."i"]++; $rdd[$arr[0]."i"]=$arr[2];
            }
            $sum+=$arr[1];
            $snm++;
         }
//         mysql_free_result($result);
      }

      $ret.="\n<table cellpadding=3 cellspacing=1 border=0 align=center>";

      if(count($rdo)>=1 || count($unknown)>=1){

      $ret.="<tr><td class=usr align=center>&nbsp;Разделы</td><td class=usr>&nbsp;Кол-во уникальных скачиваний</td>
            <td class=usr>&nbsp;Кол-во скачиваний в разделе</td></tr>";

         $mx=max($rdo);
         $mxu=max($rdn);   
      
         if(isset($unknown)){
            $i=0;
            if($unknown['rdo']>$mx) $mx = $unknown['rdo'];
            if($unknown['rdn']>$mxu) $mxu = $unknown['rdn'];

            $ret.="\n<tr bgcolor=#c0c0c0><td><b>Неизвестно</b></td>
                  <td align=right><small><font color=#CD3601>".$unknown['rdn']."</font></small> <img src=\"images/voter.gif\" width=".round(200*($unknown['rdn']/$mxu))." height=8></td>
                  <td><img src=\"images/vote.gif\" width=".round(200*($unknown['rdo']/$mx))." height=8> <small><font color=#5B5B5B>".$unknown['rdo']."</font></small></td></tr>\n";
         }
         
         asort($rdd);
         reset($rdd);
         while (list($key,$val) = each($rdd)) {
            if($sum>0){ $lnw=($rdo[$key]/$mx); $lnwn=($rdn[$key]/$mxu); } else { $lnwn = 0.1; $lnw = 0.1; }

            $ret.="\n<tr bgcolor=#F1F1F1><td>".$rdd[$key]."</td>
                  <td align=right><small><font color=#CD3601>".$rdn[$key]."</font></small> <img src=\"images/voter.gif\" width=".round(200*$lnwn)." height=8></td>
                  <td><img src=\"images/vote.gif\" width=".round(200*$lnw)." height=8> <small><font color=#5B5B5B>".$rdo[$key]."</font></small></td></tr>\n";
         }
         $ret.="<tr><td colspan=3 class=usr height=1></td></tr>
               <tr><td></td><td> <font color=#CD3601><b>Всего:</b> ".$snm."</font></td>
               <td> <font color=#5B5B5B><b>Всего:</b> ".$sum."</font></td></tr></table>";

         unset($rdo,$rdn,$sum,$lnw,$snm,$arr,$unknown);
      } else {
         $ret.="<tr><td colspan=3><b>Разделы журнала Военная медицина:</b> статистических данных нет!</td></tr></table></form>";
      }
//      mysql_free_result($result);
   break;


//------------------------------------------------------------------------------------------------------------------------------------------
// анализ статистики по скачиванию Авторефератов (по разделам)


   case "files_C":

      $ret.="<tr><td colspan=2><br><br><b>По скачиванию Авторефератов</b>:";

      $result=mysql_query("SELECT B.department, SUM(A.user), SUM(A.page), (SELECT C.name FROM iws_arfiles_C_department C WHERE C.id=B.department)
                           FROM iws_stat_files_C A LEFT JOIN iws_arfiles_C_records B ON (B.id=A.menu) 
                           GROUP BY B.department");

      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            if(!$arr[3]){
               $unknown['rdn']+=$arr[1]; $unknown['rdo']+=$arr[2];
            } else {
               $rdo[$arr[0]."i"]+=$arr[2]; $rdn[$arr[0]."i"]+=$arr[1]; $rdd[$arr[0]."i"]=$arr[3];
            }
            $sum+=$arr[2]; $snm+=$arr[1];
         }
//         mysql_free_result($result);
      }


      $result=mysql_query("SELECT B.department, count(A.id), (SELECT C.name FROM iws_arfiles_C_department C WHERE C.id=B.department) 
                           FROM iws_statistics A LEFT JOIN iws_arfiles_C_records B ON (B.id=A.menu) WHERE A.url='files_C' 
                           GROUP BY B.department,A.ip_adr,A.coockie");

      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            if(!$arr[2]){
               $unknown['rdn']++; $unknown['rdo']+=$arr[1];
            } else {
               $rdo[$arr[0]."i"]+=$arr[1]; $rdn[$arr[0]."i"]++; $rdd[$arr[0]."i"]=$arr[2];
            }
            $sum+=$arr[1];
            $snm++;
         }
//         mysql_free_result($result);
      }

      $ret.="\n<table cellpadding=3 cellspacing=1 border=0 align=center>";

      if(count($rdo)>=1 || count($unknown)>=1){

      $ret.="<tr><td class=usr align=center>&nbsp;Разделы</td><td class=usr>&nbsp;Кол-во уникальных скачиваний</td>
            <td class=usr>&nbsp;Кол-во скачиваний в разделе</td></tr>";

         $mx=max($rdo);
         $mxu=max($rdn);   
      
         if(isset($unknown)){
            $i=0;
            if($unknown['rdo']>$mx) $mx = $unknown['rdo'];
            if($unknown['rdn']>$mxu) $mxu = $unknown['rdn'];

            $ret.="\n<tr bgcolor=#c0c0c0><td><b>Неизвестно</b></td>
                  <td align=right><small><font color=#CD3601>".$unknown['rdn']."</font></small> <img src=\"images/voter.gif\" width=".round(200*($unknown['rdn']/$mxu))." height=8></td>
                  <td><img src=\"images/vote.gif\" width=".round(200*($unknown['rdo']/$mx))." height=8> <small><font color=#5B5B5B>".$unknown['rdo']."</font></small></td></tr>\n";
         }
         
         asort($rdd);
         reset($rdd);
         while (list($key,$val) = each($rdd)) {
            if($sum>0){ $lnw=($rdo[$key]/$mx); $lnwn=($rdn[$key]/$mxu); } else { $lnwn = 0.1; $lnw = 0.1; }

            $ret.="\n<tr bgcolor=#F1F1F1><td>".$rdd[$key]."</td>
                  <td align=right><small><font color=#CD3601>".$rdn[$key]."</font></small> <img src=\"images/voter.gif\" width=".round(200*$lnwn)." height=8></td>
                  <td><img src=\"images/vote.gif\" width=".round(200*$lnw)." height=8> <small><font color=#5B5B5B>".$rdo[$key]."</font></small></td></tr>\n";
         }
         $ret.="<tr><td colspan=3 class=usr height=1></td></tr>
               <tr><td></td><td> <font color=#CD3601><b>Всего:</b> ".$snm."</font></td>
               <td> <font color=#5B5B5B><b>Всего:</b> ".$sum."</font></td></tr></table>";

         unset($rdo,$rdn,$sum,$lnw,$snm,$arr,$unknown);
      } else {
         $ret.="<tr><td colspan=3><b>Разделы Авторефератов:</b> статистических данных нет!</td></tr></table></form>";
      }
//      mysql_free_result($result);
   break;




//------------------------------------------------------------------------------------------------------------------------------------------
// анализ статистики по скачиванию Медицинского журнала (по разделам)


   case "files_A":

      $ret.="<tr><td colspan=2><br><br><b>По скачиванию журнала</b>:";

      $result=mysql_query("SELECT B.department, SUM(A.user), SUM(A.page), (SELECT C.name FROM iws_arfiles_A_department C WHERE C.id=B.department)
                           FROM iws_stat_files_A A LEFT JOIN iws_arfiles_A_records B ON (B.id=A.menu) 
                           GROUP BY B.department");

      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            if(!$arr[3]){
               $unknown['rdn']+=$arr[1]; $unknown['rdo']+=$arr[2];
            } else {
               $rdo[$arr[0]."i"]+=$arr[2]; $rdn[$arr[0]."i"]+=$arr[1]; $rdd[$arr[0]."i"]=$arr[3];
            }
            $sum+=$arr[2]; $snm+=$arr[1];
         }
//         mysql_free_result($result);
      }


      $result=mysql_query("SELECT B.department, count(A.id), (SELECT C.name FROM iws_arfiles_A_department C WHERE C.id=B.department) 
                           FROM iws_statistics A LEFT JOIN iws_arfiles_A_records B ON (B.id=A.menu) WHERE A.url='files_A' 
                           GROUP BY B.department,A.ip_adr,A.coockie");

      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            if(!$arr[2]){
               $unknown['rdn']++; $unknown['rdo']+=$arr[1];
            } else {
               $rdo[$arr[0]."i"]+=$arr[1]; $rdn[$arr[0]."i"]++; $rdd[$arr[0]."i"]=$arr[2];
            }
            $sum+=$arr[1];
            $snm++;
         }
//         mysql_free_result($result);
      }

      $ret.="\n<table cellpadding=3 cellspacing=1 border=0 align=center>";

      if(count($rdo)>=1 || count($unknown)>=1){

      $ret.="<tr><td class=usr align=center>&nbsp;Разделы</td><td class=usr>&nbsp;Кол-во уникальных скачиваний</td>
            <td class=usr>&nbsp;Кол-во скачиваний в разделе</td></tr>";

         $mx=max($rdo);
         $mxu=max($rdn);   
      
         if(isset($unknown)){
            $i=0;
            if($unknown['rdo']>$mx) $mx = $unknown['rdo'];
            if($unknown['rdn']>$mxu) $mxu = $unknown['rdn'];

            $ret.="\n<tr bgcolor=#c0c0c0><td><b>Неизвестно</b></td>
                  <td align=right><small><font color=#CD3601>".$unknown['rdn']."</font></small> <img src=\"images/voter.gif\" width=".round(200*($unknown['rdn']/$mxu))." height=8></td>
                  <td><img src=\"images/vote.gif\" width=".round(200*($unknown['rdo']/$mx))." height=8> <small><font color=#5B5B5B>".$unknown['rdo']."</font></small></td></tr>\n";
         }
         
         asort($rdd);
         reset($rdd);
         while (list($key,$val) = each($rdd)) {
            if($sum>0){ $lnw=($rdo[$key]/$mx); $lnwn=($rdn[$key]/$mxu); } else { $lnwn = 0.1; $lnw = 0.1; }

            $ret.="\n<tr bgcolor=#F1F1F1><td>".$rdd[$key]."</td>
                  <td align=right><small><font color=#CD3601>".$rdn[$key]."</font></small> <img src=\"images/voter.gif\" width=".round(200*$lnwn)." height=8></td>
                  <td><img src=\"images/vote.gif\" width=".round(200*$lnw)." height=8> <small><font color=#5B5B5B>".$rdo[$key]."</font></small></td></tr>\n";
         }
         $ret.="<tr><td colspan=3 class=usr height=1></td></tr>
               <tr><td></td><td> <font color=#CD3601><b>Всего:</b> ".$snm."</font></td>
               <td> <font color=#5B5B5B><b>Всего:</b> ".$sum."</font></td></tr></table>";

         unset($rdo,$rdn,$sum,$lnw,$snm,$arr,$unknown);
      } else {
         $ret.="<tr><td colspan=3><b>Разделы журнала:</b> статистических данных нет!</td></tr></table></form>";
      }
//      mysql_free_result($result);
   break;



//------------------------------------------------------------------------------------------------------------------------------------------
// анализ статистики по скачиванию файлов Публикаций (по разделам)


   case "files":

      $ret.="<tr><td colspan=2><br><br><b>По скачиванию файлов публикаций</b>:";

      $result=mysql_query("SELECT B.department, SUM(A.user), SUM(A.page), (SELECT C.name FROM iws_arfiles_department C WHERE C.id=B.department)
                           FROM iws_stat_files A LEFT JOIN iws_arfiles_records B ON (B.id=A.menu) 
                           GROUP BY B.department");

      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            if(!$arr[3]){
               $unknown['rdn']+=$arr[1]; $unknown['rdo']+=$arr[2];
            } else {
               $rdo[$arr[0]."i"]+=$arr[2]; $rdn[$arr[0]."i"]+=$arr[1]; $rdd[$arr[0]."i"]=$arr[3];
            }
            $sum+=$arr[2]; $snm+=$arr[1];
         }
//         mysql_free_result($result);
      }


      $result=mysql_query("SELECT B.department, count(A.id), (SELECT C.name FROM iws_arfiles_department C WHERE C.id=B.department) 
                           FROM iws_statistics A LEFT JOIN iws_arfiles_records B ON (B.id=A.menu) WHERE A.url='files' 
                           GROUP BY B.department,A.ip_adr,A.coockie");

      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            if(!$arr[2]){
               $unknown['rdn']++; $unknown['rdo']+=$arr[1];
            } else {
               $rdo[$arr[0]."i"]+=$arr[1]; $rdn[$arr[0]."i"]++; $rdd[$arr[0]."i"]=$arr[2];
            }
            $sum+=$arr[1];
            $snm++;
         }
//         mysql_free_result($result);
      }

      $ret.="\n<table cellpadding=3 cellspacing=1 border=0 align=center>";

      if(count($rdo)>=1 || count($unknown)>=1){

      $ret.="<tr><td class=usr align=center>&nbsp;Разделы публикаций</td><td class=usr>&nbsp;Кол-во уникальных скачиваний</td>
            <td class=usr>&nbsp;Кол-во скачиваний в разделе</td></tr>";

         $mx=max($rdo);
         $mxu=max($rdn);   
      
         if(isset($unknown)){
            $i=0;
            if($unknown['rdo']>$mx) $mx = $unknown['rdo'];
            if($unknown['rdn']>$mxu) $mxu = $unknown['rdn'];

            $ret.="\n<tr bgcolor=#c0c0c0><td><b>Неизвестно</b></td>
                  <td align=right><small><font color=#CD3601>".$unknown['rdn']."</font></small> <img src=\"images/voter.gif\" width=".round(200*($unknown['rdn']/$mxu))." height=8></td>
                  <td><img src=\"images/vote.gif\" width=".round(200*($unknown['rdo']/$mx))." height=8> <small><font color=#5B5B5B>".$unknown['rdo']."</font></small></td></tr>\n";
         }
         
         asort($rdd);
         reset($rdd);
         while (list($key,$val) = each($rdd)) {
            if($sum>0){ $lnw=($rdo[$key]/$mx); $lnwn=($rdn[$key]/$mxu); } else { $lnwn = 0.1; $lnw = 0.1; }

            $ret.="\n<tr bgcolor=#F1F1F1><td>".$rdd[$key]."</td>
                  <td align=right><small><font color=#CD3601>".$rdn[$key]."</font></small> <img src=\"images/voter.gif\" width=".round(200*$lnwn)." height=8></td>
                  <td><img src=\"images/vote.gif\" width=".round(200*$lnw)." height=8> <small><font color=#5B5B5B>".$rdo[$key]."</font></small></td></tr>\n";
         }
         $ret.="<tr><td colspan=3 class=usr height=1></td></tr>
               <tr><td></td><td> <font color=#CD3601><b>Всего:</b> ".$snm."</font></td>
               <td> <font color=#5B5B5B><b>Всего:</b> ".$sum."</font></td></tr></table>";

         unset($rdo,$rdn,$sum,$lnw,$snm,$arr,$unknown);
      } else {
         $ret.="<tr><td colspan=3><b>Разделы публикаций:</b> статистических данных нет!</td></tr></table></form>";
      }
//      mysql_free_result($result);
   break;

//------------------------------------------------------------------------------------------------------------------------------------------
// анализ статистики по скачиванию Новостей-статей (по разделам)


   case "arts":

      $ret.="<tr><td colspan=2><br><br><b>По просмотру Новостей-статей</b>:";

      $result=mysql_query("SELECT B.department, SUM(A.user), SUM(A.page), (SELECT C.name FROM iws_art_department C WHERE C.id=B.department)
                           FROM iws_stat_arts A LEFT JOIN iws_art_records B ON (B.id=A.menu) 
                           GROUP BY B.department");

      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            if(!$arr[3]){
               $unknown['rdn']+=$arr[1]; $unknown['rdo']+=$arr[2];
            } else {
               $rdo[$arr[0]."i"]+=$arr[2]; $rdn[$arr[0]."i"]+=$arr[1]; $rdd[$arr[0]."i"]=$arr[3];
            }
            $sum+=$arr[2]; $snm+=$arr[1];
         }
//         mysql_free_result($result);
      }


      $result=mysql_query("SELECT B.department, count(A.id), (SELECT C.name FROM iws_art_department C WHERE C.id=B.department) 
                           FROM iws_statistics A LEFT JOIN iws_art_records B ON (B.id=A.menu) WHERE A.url='arts' 
                           GROUP BY B.department,A.ip_adr,A.coockie");

      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            if(!$arr[2]){
               $unknown['rdn']++; $unknown['rdo']+=$arr[1];
            } else {
               $rdo[$arr[0]."i"]+=$arr[1]; $rdn[$arr[0]."i"]++; $rdd[$arr[0]."i"]=$arr[2];
            }
            $sum+=$arr[1];
            $snm++;
         }
//         mysql_free_result($result);
      }

      $ret.="\n<table cellpadding=3 cellspacing=1 border=0 align=center>";

      if(count($rdo)>=1 || count($unknown)>=1){

      $ret.="<tr><td class=usr align=center>&nbsp;Рубрики Новостей-статей</td><td class=usr>&nbsp;Кол-во уникальных просмотров</td>
            <td class=usr>&nbsp;Кол-во просмотров статей</td></tr>";

         $mx=max($rdo);
         $mxu=max($rdn);   
      
         if(isset($unknown)){
            $i=0;
            if($unknown['rdo']>$mx) $mx = $unknown['rdo'];
            if($unknown['rdn']>$mxu) $mxu = $unknown['rdn'];

            $ret.="\n<tr bgcolor=#c0c0c0><td><b>Неизвестно</b></td>
                  <td align=right><small><font color=#CD3601>".$unknown['rdn']."</font></small> <img src=\"images/voter.gif\" width=".round(200*($unknown['rdn']/$mxu))." height=8></td>
                  <td><img src=\"images/vote.gif\" width=".round(200*($unknown['rdo']/$mx))." height=8> <small><font color=#5B5B5B>".$unknown['rdo']."</font></small></td></tr>\n";
         }
         
         asort($rdd);
         reset($rdd);
         while (list($key,$val) = each($rdd)) {
            if($sum>0){ $lnw=($rdo[$key]/$mx); $lnwn=($rdn[$key]/$mxu); } else { $lnwn = 0.1; $lnw = 0.1; }

            $ret.="\n<tr bgcolor=#F1F1F1><td>".$rdd[$key]."</td>
                  <td align=right><small><font color=#CD3601>".$rdn[$key]."</font></small> <img src=\"images/voter.gif\" width=".round(200*$lnwn)." height=8></td>
                  <td><img src=\"images/vote.gif\" width=".round(200*$lnw)." height=8> <small><font color=#5B5B5B>".$rdo[$key]."</font></small></td></tr>\n";
         }
         $ret.="<tr><td colspan=3 class=usr height=1></td></tr>
               <tr><td></td><td> <font color=#CD3601><b>Всего:</b> ".$snm."</font></td>
               <td> <font color=#5B5B5B><b>Всего:</b> ".$sum."</font></td></tr></table>";

         unset($rdo,$rdn,$sum,$lnw,$snm,$arr,$unknown);
      } else {
         $ret.="<tr><td colspan=3><b>Рубрики Новостей-статей:</b> статистических данных нет!</td></tr></table></form>";
      }
//      mysql_free_result($result);
   break;



// анализ статистики по месяцам

   case "month":  
      $ret.="<tr><td colspan=2>"
      ."<br><br><b>По месяцам</b>:"
      ."<table cellpadding=2 cellspacing=1 border=0 align=center>"
      ."<tr><td class=usr width=130 align=center>&nbsp;Период времени</td><td class=usr>&nbsp;Кол-во уникальных посещений</td><td class=usr>&nbsp;Кол-во посещенных страниц</td><td  class=usr width=110></td></tr>";

      $result=mysql_query("SELECT DATE_FORMAT(dt,'%M %Y'),count(ip_adr),DATE_FORMAT(dt,'%Y-%m') FROM iws_statistics GROUP BY DATE_FORMAT(dt,'%M %Y'),ip_adr,coockie ORDER BY dt DESC");
      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            $rdo[$arr[0]]+=$arr[1]; $rdd[$arr[0]]=$arr[2]; $rdn[$arr[0]]++;
            $sum+=$arr[1]; $snm++;
         }
      }

      $result=mysql_query("SELECT DATE_FORMAT(month,'%M %Y'),user,page,DATE_FORMAT(month,'%Y-%m') FROM iws_stat_month ORDER BY month DESC");
      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            $rdo[$arr[0]]+=$arr[2]; $rdn[$arr[0]]+=$arr[1]; $rdd[$arr[0]]=$arr[3];
            $sum+=$arr[2]; $snm+=$arr[1];
         }
      }

      if(count($rdo)>=1){
         $mx=max($rdo); $mxu=max($rdn);

         while (list($key,$val) = each($rdo)) {
            if($sum>0){ $lnw=($rdo[$key]/$mx); $lnwn=($rdn[$key]/$mxu); } else { $lnwn = 0.1; $lnw = 0.1; }

            $ret.="<tr bgcolor=#F1F1F1><td>".$key."</td>"
            ."<td align=right><small><font color=#CD3601>".$rdn[$key]."</font></small> <img src=\"images/voter.gif\" width=".round(200*$lnwn)." height=8></td>"
            ."<td><img src=\"images/vote.gif\" width=".round(200*$lnw)." height=8> <small><font color=#5B5B5B>".$rdo[$key]."</font></small></td>"
            ."<td align=center><a href=\"#\" onclick=\"delOk('mainiwspref.php?gopr=stat&act=delM&url=".$rdd[$key]."')\">удалить</a></td></tr>\n";
         }  

         $ret.="<tr><td colspan=4 class=usr height=1></td></tr><tr><td></td><td> <font color=#CD3601><b>Всего:</b> ".$snm."</font></td><td> <font color=#5B5B5B><b>Всего:</b> ".$sum."</font></td></tr></table>";
         unset($rdo,$rdn,$snm,$sum,$lnw);
      }else{
         $ret.="<tr><td colspan=4 align=center>Статистических данных нет!</td></tr></table>";
      }
   break;


// анализ статистики по переходам с сайтов

   case "frm":
      $ret.="<tr><td colspan=2>"
         ."<br><br><b>По переходам с других сайтов</b>:"
         ."<table cellpadding=2 cellspacing=1 border=0 align=center>"
         ."<tr><td class=usr align=center>&nbsp;URL перехода (сайта)</td><td class=usr>&nbsp;Кол-во уникальных переходов</td><td class=usr>&nbsp;Всего переходов</td><td class=usr width=110></td></tr>";

      
      $result=mysql_query("SELECT IF(url='unknown','неизвестный',url), user, page FROM iws_stat_from");
      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            $rdo[$arr[0]]+=$arr[2]; $rdn[$arr[0]]+=$arr[1];
            $sum+=$arr[2]; $snm+=$arr[1];
         }
      }

      $result=mysql_query("SELECT LCASE(SUBSTRING_INDEX(REPLACE(REPLACE(frm,'http://',''),'www.',''),'/',1)), count(ip_adr) FROM iws_statistics WHERE frm NOT LIKE '%".$hostName."%' GROUP BY LCASE(SUBSTRING_INDEX(REPLACE(REPLACE(frm,'http://',''),'www.',''),'/',1)),ip_adr,coockie ORDER BY frm");
      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            if(!$arr[0]) $arr[0] = "неизвестный";
            $rdo[$arr[0]]+=$arr[1]; $rdn[$arr[0]]++;
            $sum+=$arr[1]; $snm++;
         }
      }

      if(count($rdo)>=1){
         arsort($rdo); arsort($rdn);
         $mx=max($rdo); $mxu=max($rdn);         

         while (list($key,$val) = each($rdo)) {
            if($sum>0){ $lnw=($rdo[$key]/$mx); $lnwn=($rdn[$key]/$mxu); } else { $lnw = 0.1; $lnwn = 0.1; }

            $ret.="<tr bgcolor=#F1F1F1><td align=right>".$key."</td>
            <td align=right><small><font color=#CD3601>".$rdn[$key]."</font></small> <img src=\"images/voter.gif\" width=".round(200*$lnwn)." height=8></td>
            <td><img src=\"images/vote.gif\" width=".round(200*$lnw)." height=8> <small><font color=#5B5B5B>".$rdo[$key]."</font></small></td>
            <td align=center><a href=\"#\" onclick=\"delOk('mainiwspref.php?gopr=stat&act=delP&url=".$key."')\">удалить</a></td></tr>";
         }

         $ret.="<tr><td colspan=4 class=usr height=1></td></tr><tr><td></td><td> <font color=#CD3601><b>Всего:</b> ".$snm."</font></td><td> <font color=#5B5B5B><b>Всего:</b> ".$sum."</font></td></tr></table>";
         unset($rdo,$rdn,$snm,$sum,$lnw);
      } else {
         $ret.="<tr><td colspan=4 align=center>Статистических данных нет!</td></tr></table>";
      }
   break;

}

$ret.="</td></tr></table>";

return $ret;
}



// функция удаления статистических данных

function delSt(){
global $act,$url,$mnt,$mainadvar;

switch($act){
   case "delP":  // по переходам с сайтов
      if($url=="неизвестный"){
         mysql_query("DELETE FROM iws_statistics WHERE frm=''");
         mysql_query("DELETE FROM iws_stat_from WHERE url='unknown'");
      }else{
         mysql_query("DELETE FROM iws_statistics WHERE frm LIKE '%$url%'");
         mysql_query("DELETE FROM iws_stat_from WHERE url LIKE '%$url%'");
      }
      header("location: mainiwspref.php?gopr=stat&act=frm");
      return;
   break;

   case "delM":  // месяцам
      mysql_query("DELETE FROM iws_statistics WHERE dt LIKE '$url%'");
      mysql_query("DELETE FROM iws_stat_month WHERE month LIKE '$url%'");
      header("location: mainiwspref.php?gopr=stat&act=month");
      return;
   break;

   case "delD":  //по дням
     $sql="DELETE FROM iws_statistics WHERE dt LIKE '$url%'";
      mysql_query($sql);
      header("location: mainiwspref.php?gopr=stat&act=dday&mnt=$mnt");
      return;
   break;

   case "delDOther": //по дням
     $sql="DELETE FROM iws_stat_day WHERE day LIKE '$url%'";
      mysql_query($sql);
      header("location: mainiwspref.php?gopr=stat&act=dday&mnt=$mnt");
      return;
   break;

   case "delR":   // по разделам
      if(isset($url) && $url){
         while(list($key,$val) = each($url)){
            $unk=explode("%|%",$val);
            if(count($unk)>=2){
               for($i=0;$i<=(count($unk)-1);$i++){
                  $arl=explode("||",$unk[$i]);
                  mysql_query("DELETE FROM iws_statistics WHERE url='".$arl[0]."' AND menu=".$arl[1]);
                  mysql_query("DELETE FROM iws_stat_menu WHERE url='".$arl[0]."' AND menu=".$arl[1]);
               }
            }else{
               $arl=explode("||",$val);
               if($arl[0]=="main"){
                  mysql_query("DELETE FROM iws_statistics WHERE url='main'");
                  mysql_query("DELETE FROM iws_stat_menu WHERE url='main'");
               }elseif($arl[0]=="news"){
                  mysql_query("DELETE FROM iws_statistics WHERE url='news'");
                  mysql_query("DELETE FROM iws_stat_menu WHERE url='news'");
               }elseif($arl[0]=="photo"){
                  mysql_query("DELETE FROM iws_statistics WHERE url='photo'");
                  mysql_query("DELETE FROM iws_stat_photo");
               }elseif($arl[0]=="files_A"){
                  mysql_query("DELETE FROM iws_statistics WHERE url='files_A'");
                  mysql_query("DELETE FROM iws_stat_files_A");
               }elseif($arl[0]=="files_B"){
                  mysql_query("DELETE FROM iws_statistics WHERE url='files_B'");
                  mysql_query("DELETE FROM iws_stat_files_B");
               }elseif($arl[0]=="files_C"){
                  mysql_query("DELETE FROM iws_statistics WHERE url='files_C'");
                  mysql_query("DELETE FROM iws_stat_files_C");
               }elseif($arl[0]=="files"){
                  mysql_query("DELETE FROM iws_statistics WHERE url='files'");
                  mysql_query("DELETE FROM iws_stat_files");
               }elseif($arl[0]=="arts"){
                  mysql_query("DELETE FROM iws_statistics WHERE url='arts'");
                  mysql_query("DELETE FROM iws_stat_arts");
               }else{
                 mysql_query("DELETE FROM iws_statistics WHERE url='".$arl[0]."' AND menu=".$arl[1]);
                 mysql_query("DELETE FROM iws_stat_menu WHERE url='".$arl[0]."' AND menu=".$arl[1]);
               }
            }
         }
      }
      header("location: mainiwspref.php?gopr=stat&act=mn");
      return;
   break;


}
}

?>