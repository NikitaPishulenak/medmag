<?php

include('../../inc/config.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
@mysql_select_db($dbname) or die("Не могу выбрать базу");
mysql_query('SET NAMES "cp1251"');

if(isset($trueDelete) && $trueDelete){

   if(isset($mnu) && $mnu){
         $i=0;
         $sql = "";
         while(list($key,$val) = each($mnu)){
               if(!$i){ 
                  $sql.="idm=$val";
               } else {
                  $sql.=" or idm=$val";
               }
               $i++; 
         }
         mysql_query("delete from iws_menu where blk>2 and ($sql)");
   }

   if(isset($pge) && $pge){
         $i=0;
         $sql = "";
         while(list($key,$val) = each($pge)){
               if(!$i){ 
                  $sql.="mid=$val";
               } else {
                  $sql.=" or mid=$val";
               }
               $i++; 
         }
         mysql_query("delete from iws_page_simple where $sql");
         mysql_query("delete from iws_page_multi where $sql");
         mysql_query("delete from iws_page_multi_pref where $sql");
   }
   mysql_close($dblink);

   header('Content-type: text/xml');
   header('Cache-Control: no-cache, max-age=0, must-revalidate'); 
   header('Content-Type: text/plain; charset=Windows-1251');

   echo "<br><center>Готово...</center> <script defer=true> window.returnValue = true; setTimeout('window.close()',2000); </script>";

} else {

   $cnt = 0;
   $ret=mysql_query("select A.idm from iws_menu A LEFT JOIN iws_page_simple B ON A.idm=B.mid WHERE A.blk>2 AND B.mid IS NULL");
   if(mysql_numrows($ret)>=1){   $cnt = mysql_numrows($ret); }

   $rev=mysql_query("select A.mid from iws_page_simple A LEFT JOIN iws_menu B ON A.mid=B.idm AND B.blk>2 WHERE B.idm IS NULL");
   if(mysql_numrows($rev)>=1){   $cnt += mysql_numrows($rev);  }

if($cnt){

$tst = "var mn = ''; var pg = ''; ";

   if(mysql_numrows($ret)>=1){
      while(list($id)=mysql_fetch_row($ret)){ $tst.=" mn += '&mnu[]=".$id."';"; }
   }

   if(mysql_numrows($rev)>=1){
      while(list($id)=mysql_fetch_row($rev)){ $tst.=" pg += '&pge[]=".$id."';"; }
   }

   mysql_close($dblink);

   $tst.=" var dt=new Date(); var qr=mn+pg+'&trueDelete=1&nocache='+dt.getTime(); qr=qr.substr(1); buildReport('reDel.php?'+qr);";

header('Content-type: text/xml');
header('Cache-Control: no-cache, max-age=0, must-revalidate'); 
header('Content-Type: text/plain; charset=Windows-1251');

      echo "<br>Найдено <b>".$cnt."</b> несвязанных пунктов меню и страниц.<br>";
      echo "<div align=right><hr><input type=button value=\"Удалить\" onclick=\"".$tst."\">&nbsp;&nbsp;"
      ."<input TYPE=BUTTON ONCLICK=\"window.close();\" value=\"Отмена\">"
      ."</div>";
   } else {

header('Content-type: text/xml');
header('Cache-Control: no-cache, max-age=0, must-revalidate'); 
header('Content-Type: text/plain; charset=Windows-1251');

      echo "<br><center>Все пункты меню и страницы связаны!</center> <script defer=true> setTimeout('window.close()',2000); </script>";
   }

}
?>
