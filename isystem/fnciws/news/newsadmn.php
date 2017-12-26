<?php

include('fnciws/news/news.inc.php');

if($act=="addOk" || $act=="delOk" || $act=="edtOk" || $act=="arcOk" || $act=="osnOk"){
   $cont=newsOk();   
} elseif($act=="arch"){
   $cont=arch_news();   
} else {
   $cont=admin_news();     
}


//------------------------------------------------------------------------------------------------------------------------------------

function arch_news(){
global $err,$natbl,$fieldnmn,$patbl,$fieldnmp,$QUERY_STRING,$start,$tmpl,$mainadvar;

$qwr=ereg_replace("&tmpl=".$tmpl,"",ereg_replace("&start=".$start,"",$QUERY_STRING));
list($lmt)=mysql_fetch_row(mysql_query("select ".$fieldnmp['lmt']." from ".$patbl));
if(!$start) $start=1;//$lmt+1; 
$prom=numlink(1,$start,$qwr);
if($prom!="none"){

switch($err){
   case 1:
      $content="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
      ."Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
      break;
   case 3:
      $content="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
      ."Не введена вся информация или же неверный формат даты. Попробуйте еще раз.</td></tr></table><br>";
      break;
}
      $res=mysql_query("select ".$fieldnmn['did'].",DATE_FORMAT(".$fieldnmn['dat'].",'%d.%m.%Y'),".$fieldnmn['tit'].",IF(".$fieldnmn['dat']."<=CURRENT_DATE,0,1) from ".$natbl." where ".$fieldnmn['ac']."=1 and ".$fieldnmn['lng']."='".$mainadvar['lng']."' order by ".$fieldnmn['dat']." DESC limit ".($start-1).",$lmt");
      $content.="<table width=100% border=0 cellpadding=3 cellspacing=0>"
      ."<tr><td align=center class=usr>Архив новостей</td></tr></table>"
      ."<script><!--\n"
         ."var arm = Array('Вы действительно хотите удалить новость из архива?     ','Вы действительно хотите переместить новость в основные новости?     ');"
         ."function fnOk(urli,we){ \n"
         ."if(confirm(arm[we])){\n"
      ."document.location=urli;\n"
      ."}\n"
      ."}\n"
      ."//--></script>\n"
      ."<table width=100% border=0 cellpadding=2 cellspacing=1 align=center>"
      ."<tr><td colspan=4><br><input class=but type=button value=\"<< выйти из архива\" onclick=\"javascript:document.location='mainiws.php?go=news&tmpl=$tmpl'\"><br><br></td></tr>"
      ."<tr align=center><td colspan=5 height=1 bgcolor=#000000></td></tr>"
      ."<tr><td colspan=4>".$prom."<br></td></tr>";
      if(mysql_numrows($res)>=1){
         $cls="menu1";
         while($arr=mysql_fetch_row($res)){
               if($arr[3]){
                  $cls="menuC";
               }else if($cls=="menu1"){
                  $cls="menu";
               } else {
                  $cls="menu1";
               }
            $content.="<tr><td class=$cls>".$arr[1]."</td><td class=$cls>".stripslashes($arr[2])."</td>"
               ."<td class=$cls nowrap>"
               ."<a href=\"#\" onclick=\"fnOk('mainiws.php?go=news&act=osnOk&tmpl=$tmpl&id=".$arr[0]."',1); return false;\"><< из архива</a></td>"
            ."<td class=$cls nowrap>"
            ."<a href=\"#\" onclick=\"fnOk('mainiws.php?go=news&act=delOk&arc=1&tmpl=$tmpl&id=".$arr[0]."',0); return false;\">удалить</a></td></tr>";
         }
      } 
      $content.="<tr><td colspan=4><br>".$prom."</td></tr>"
      ."<tr align=center><td colspan=5 height=1 bgcolor=#000000></td></tr>"
      ."<tr><td colspan=4><br><input class=but type=button value=\"<< выйти из архива\" onclick=\"javascript:document.location='mainiws.php?go=news&tmpl=$tmpl'\"></td></tr>"
      ."</table>";
} else {
   $content="<center><b>Извините в архиве новостей нет!</b>"
   ."<hr><input class=but type=button value=\"<< выйти из архива\" onclick=\"javascript:document.location='mainiws.php?go=news&tmpl=$tmpl'\"></center>";
}
return $content;
}


function numlink($qwr,$start,$oper){
global $natbl,$fieldnmn,$patbl,$fieldnmp,$tmpl,$mainadvar;
list($lmt)=mysql_fetch_row(mysql_query("select ".$fieldnmp['lmt']." from ".$patbl));
list($cnt)=mysql_fetch_row(mysql_query("select count(".$fieldnmn['did'].") from ".$natbl." where ".$fieldnmn['lng']."='".$mainadvar['lng']."' and ".$fieldnmn['ac']."=".$qwr));
if($cnt>=1){
   if(is_integer($cnt/$lmt)){
      $cr=$cnt/$lmt;       
   }else{
      $cr=round(($cnt/$lmt)+(0.5));
   }
   $nv=($start-1)/$lmt;
   if((round(($nv/10)+(0.5)))*10<$cr){          
      $kn=(round(($nv/10)+(0.5)))*10;
   } else {
      $kn=$cr;          
   }
   $rd=round(($nv/10)-0.5);
   if($rd<0){ $rd=0; }
   $nv=($rd*10)+1;
      $rt="<table width=100% border=0 cellpadding=1 cellspacing=0><tr><td>"
      ."<style type=\"text/css\"><!-- "
      ."span.cur {border:1px solid #666666; background-color:#eeeeee;font-size:9pt;font-weight:bold;}"
      ."span.oth {border:1px solid #dddddd;}"
      ."//-->"
      ."</style>";
      if($start<>1){ $rt.="<a class=im href=\"?$oper&tmpl=$tmpl&start=".($start-$lmt)."\">&lt;&lt;предыдущие $lmt</a> "; }
      for($i=$nv;$i<=$kn;$i++){
         if($start==1 && $i==1){
            $rt.=" <b><span class=cur>&nbsp".$i."&nbsp</span></b> ";
         }elseif((($i-1)*$lmt)+1==$start){
            $rt.=" <b><span class=cur>&nbsp".$i."&nbsp</span></b> ";
         } else {
            $rt.=" <span class=oth>&nbsp<a class=im href=\"?$oper&tmpl=$tmpl&start=".((($i-1)*$lmt)+1)."\">".$i."</a>&nbsp</span> ";
         }
      }
      if((($cr-1)*$lmt)+1!=$start){ $rt.=" <a class=im href=\"?$oper&tmpl=$tmpl&start=".($start+$lmt)."\">следующие $lmt>></a>"; }     
      $rt.="</td><td align=right>$start..";
      if(($cnt-$start)>=($lmt-1)){ 
         $rt.=$start+$lmt-1;
      }else{
         $rt.=$cnt;        
      }
      $rt.=" из ".($cnt)."</td></tr></table>";
   return $rt;
} else {
   return "none";
}
}

//-----------------------------------------------------------------------------------------------------------------------------------


function admin_news(){ // работа с пунктом новости
global $act,$err,$id,$natbl,$fieldnmn,$patbl,$fieldnmp,$name,$cont,$QUERY_STRING,$start,$tmpl,$mainadvar;
switch($err){
   case 1:
      $ct="Произошла <font color=#ff0000>ошибка</font>. Попробуйте еще раз.";
      break;
   case 2:
      $ct="<font color=#ff0000>Не удалось</font> переместить новость в основные новости. Попробуйте еще раз.";
      break;
   case 3:
      $ct="<font color=#ff0000>Не введена</font> вся информация или же неверный формат даты. Попробуйте еще раз.";
      break;
}
switch($act){
   case "add":

         $content.="<script><!--
         var trt = 0;
         var prt;

         function submitr(){
         if (frm.name.value && frm.dt.value) { 
            if(window.BODYhtml.FormHTML.elm1.value){
               if(trt) renwin();
               frm.cont.value=window.BODYhtml.FormHTML.elm1.value;
               frm.submit();
            } else {
               alert (\"Не введена информация содержания новости!   \");
            }
         } else {
            alert (\"Не введена вся информация!   \");
         }
         }

      function renwin(){
         if(!trt){
            prt = parent.document.all[\"frms\"].cols;
            parent.document.all[\"mainfrms\"].rows=\"1,*\";
            parent.document.all[\"frms\"].cols = \"1,*\";
            trt = 1;
         } else {
         if(prt){
            parent.document.all[\"frms\"].cols = prt;
         } else {
            parent.document.all[\"frms\"].cols = \"220,*\";
         }
         parent.document.all[\"mainfrms\"].rows = \"45,*\";
         trt = 0;
         }
      }
         //--></script>"
         ."<form method=\"post\" name=frm>"
         ."<input type=hidden name=go value=news>"
         ."<input type=hidden name=act value=addOk>"
         ."<input type=hidden name=tmpl value=$tmpl>"
         ."<input type=hidden name=cont value=\"\">"
         ."<table bgcolor=#ffffff height=100% width=100% border=0 cellpadding=0 cellspacing=0>"
         ."<tr><td class=usr></td><td width=100% align=center class=usr>Добавление новости. $ct</td><td class=usr><img onclick=\"renwin()\" src=\"images/zoom.gif\" border=0 alt=\"Развернуть/уменьшить окно\" style=\"cursor:hand\"></td>"
         ."<td class=usr><img onclick=\"javascript:if(trt){ renwin(); } document.location='mainiws.php?go=news&tmpl=$tmpl'\" src=\"images/close.gif\" border=0 alt=\"Закрыть окно\" style=\"cursor:hand\"></td></tr>"   
         ."<tr><td align=right><br>Дата: </td><td colspan=3 width=100%><br><input name=\"dt\" size=8 maxlength=10 value=\"".date("d.m.Y")."\"> Формат даты (дд.мм.гггг)</td></tr>"
         ."<tr><td align=right valign=top>Заголовок: </td><td colspan=3><input name=\"name\" maxlength=250 value=\"$name\" style=\"width:100%\"><br><br></td></tr>"
         ."<tr><td colspan=4 height=100%>";
         $content.=ret_html($tmpl);
         $content.="</td></tr>"
         ."</form></table>";
      break;

   case "edtv":
      list($dt,$name,$cont)=mysql_fetch_row(mysql_query("select DATE_FORMAT(".$fieldnmn['dat'].",'%d.%m.%Y'),".$fieldnmn['tit'].",".$fieldnmn['cont']." from ".$natbl." where ".$fieldnmn['did']."=$id"));
      $name=stripslashes($name);


         $content.="<script><!--
         var trt = 0;
         var prt;

         function submitr(){
         if (frm.name.value && frm.dt.value) { 
            if(window.BODYhtml.FormHTML.elm1.value){
               if(trt) renwin();
               frm.cont.value=window.BODYhtml.FormHTML.elm1.value;
               frm.submit();
            } else {
               alert (\"Не введена информация содержания новости!   \");
            }
         } else {
            alert (\"Не введена вся информация!   \");
         }
         }

      function renwin(){
         if(!trt){
            prt = parent.document.all[\"frms\"].cols;
            parent.document.all[\"mainfrms\"].rows=\"1,*\";
            parent.document.all[\"frms\"].cols = \"1,*\";
            trt = 1;
         } else {
         if(prt){
            parent.document.all[\"frms\"].cols = prt;
         } else {
            parent.document.all[\"frms\"].cols = \"220,*\";
         }
         parent.document.all[\"mainfrms\"].rows = \"45,*\";
         trt = 0;
         }
      }

      //--></script>"
      ."<form method=\"post\" name=frm>"
      ."<input type=hidden name=go value=news>"
      ."<input type=hidden name=act value=edtOk>"
      ."<input type=hidden name=cont value=\"\">"
      ."<input type=hidden name=tmpl value=$tmpl>"
      ."<input type=hidden name=id value=$id>"     
      ."<table bgcolor=#ffffff width=100% height=100% border=0 cellpadding=0 cellspacing=0>"
         ."<tr><td class=usr></td><td width=100% align=center class=usr>Редактирование новости. $ct</td><td class=usr><img onclick=\"renwin()\" src=\"images/zoom.gif\" border=0 alt=\"Развернуть/уменьшить окно\" style=\"cursor:hand\"></td>"
         ."<td class=usr><img onclick=\"javascript:if(trt){ renwin(); } document.location='mainiws.php?go=news&tmpl=$tmpl'\" src=\"images/close.gif\" border=0 alt=\"Закрыть окно\" style=\"cursor:hand\"></td></tr>"   
      ."<tr><td align=right><br>Дата:</td><td colspan=3 width=100%><br><input name=\"dt\" size=8 maxlength=10 value=\"".$dt."\"> Формат даты (дд.мм.гггг)</td></tr>"
      ."<tr><td align=right valign=top>Заголовок: </td><td colspan=3><input name=name maxlength=250 value=\"$name\" style=\"width:100%\"><br><br></td></tr>"
      ."<tr valign=top ><td colspan=4 height=100%>";
      $content.=ret_html($tmpl);
      $content.="</td></tr>"
      ."</form></table>";
      if($cont){
         $content.="
            <SCRIPT LANGUAGE=JavaScript FOR=\"window\" EVENT=onload><!--
            BODYhtml.FormHTML.elm1.value=\"".ereg_replace("\"","'",ereg_replace("(\r|\n)","",stripslashes($cont)))."\";
            //--></script>\n";
      }

      break;

   default:
      $qwr=ereg_replace("&tmpl=".$tmpl,"",ereg_replace("&start=".$start,"",$QUERY_STRING));
      if(!$start) $start=1;

      $prom=numlink(0,$start,$qwr);
         $content.="<script><!--\n"
         ."var arm = Array('Вы действительно хотите удалить новость?     ','Вы действительно хотите переместить новость в архив?     ');"
         ."function fnOk(urli,we){ \n"
         ."if(confirm(arm[we])){\n"
         ."document.location=urli;\n"
         ."}\n"
         ."}\n"
         ."//--></script>\n";
         list($lmt)=mysql_fetch_row(mysql_query("select ".$fieldnmp['lmt']." from ".$patbl));
         $res=mysql_query("select ".$fieldnmn['did'].",DATE_FORMAT(".$fieldnmn['dat'].",'%d.%m.%Y'),".$fieldnmn['tit'].",IF(".$fieldnmn['dat']."<=NOW(),0,1) as curr from ".$natbl." where ".$fieldnmn['ac']."=0 and ".$fieldnmn['lng']."='".$mainadvar['lng']."' order by ".$fieldnmn['dat']." DESC limit ".($start-1).",".$lmt);
         $content.="<br><table width=100% border=0 cellpadding=2 cellspacing=1 align=center>";
      if($prom!="none"){
         $content.="<tr><td colspan=6>".$prom."<br></td></tr>"
         ."<tr><td colspan=3></td><td colspan=3><a href=\"#\" onclick=\"javascript:document.location='mainiws.php?go=news&act=add&tmpl=$tmpl'\">добавить новость</a></td></tr>"
         ."<tr align=center><td></td><td class=usr width=10%>Дата</td><td class=usr width=90%>Заголовок</td><td class=usr colspan=3></td></tr>";
         if(mysql_numrows($res)>=1){
            $cls="menu1";
            $i=$start;
            while($arr=mysql_fetch_row($res)){
               if($arr[3]){
                  $cls="menuC";
               }else if($cls=="menu1"){
                  $cls="menu";
               } else {
                  $cls="menu1";
               }
               $content.="<tr><td align=right>".($i++).".</td><td class=$cls align=center>".$arr[1]."</td><td class=$cls>".stripslashes($arr[2])."</td>"
               ."<td class=$cls nowrap>"
               ."<a href=\"?go=news&act=edtv&id=".$arr[0]."&tmpl=$tmpl\">редакт.</a></td><td class=$cls nowrap>"
               ."<a href=\"#\" onclick=\"fnOk('mainiws.php?go=news&act=arcOk&tmpl=$tmpl&id=".$arr[0]."',1); return false;\">>> в архив</a></td>"
               ."<td class=$cls nowrap>"
               ."<a href=\"#\" onclick=\"fnOk('mainiws.php?go=news&act=delOk&tmpl=$tmpl&id=".$arr[0]."',0); return false;\">удалить</a></td></tr>";
            }
         } 
         $content.="<tr><td colspan=3></td><td colspan=3><a href=\"#\" onclick=\"javascript:document.location='mainiws.php?go=news&act=add&tmpl=$tmpl'\">добавить новость</a></td></tr>"
         ."<tr><td colspan=6><br>".$prom."</td></tr>"
         ."<tr><td colspan=6><hr><a href=\"#\" onclick=\"javascript:document.location='mainiws.php?go=news&act=arch&tmpl=$tmpl'\">архив новостей >></a></td></tr>"
         ."</table>";
      } else {
         $content.="<tr><td><a href=\"#\" onclick=\"javascript:document.location='mainiws.php?go=news&act=add&tmpl=$tmpl'\">добавить новость</a>"
                  ."&nbsp;&nbsp;/&nbsp;&nbsp;<a href=\"#\" onclick=\"javascript:document.location='mainiws.php?go=news&act=arch&tmpl=$tmpl'\">архив новостей</a></td></tr></table>"
                  ."<hr><center><b>Извините новостей нет!</b></center>";
      }
      break;
}
return $content;
}

function newsOk(){
global $act,$name,$id,$cont,$dt,$natbl,$fieldnmn,$patbl,$fieldnmp,$arc,$tmpl,$mainadvar;
switch($act){
   case "addOk":
      $dt=trim($dt);
      $name=trim($name);
      $cont=trim($cont);
      $dt=substr($dt,0,10);
      $dt=explode(".",$dt);
      if(empty($name) || empty($cont) || !is_numeric($dt[0]) || !is_numeric($dt[1]) || !is_numeric($dt[2]) || !checkdate($dt[1],$dt[0],$dt[2])) { 
         header("location: ?go=news&act=add&name=$name&tmpl=$tmpl&err=3");
         return;
      }
      $name=substr($name,0,250);
      $cont=addslashes($cont);
      $name=addslashes($name);
      if(!mysql_query("insert into ".$natbl." (".$fieldnmn['dat'].",".$fieldnmn['tit'].",".$fieldnmn['cont'].",".$fieldnmn['lng'].") values (CONCAT('".$dt[2]."-".$dt[1]."-".$dt[0]." ', CURTIME()),'$name','$cont','".$mainadvar['lng']."')")){
         header("location: ?go=news&act=add&name=$name&tmpl=$tmpl&err=1");
         return;
      } else {    
         header("location: ?go=news&tmpl=$tmpl");
         return;
      }
      break;
   case "edtOk":
      $dt=trim($dt);
      $name=trim($name);
      $cont=trim($cont);
      $dt=substr($dt,0,10);
      $dt=explode(".",$dt);
      if(empty($name) || empty($cont) || !is_numeric($dt[0]) || !is_numeric($dt[1]) || !is_numeric($dt[2]) || !checkdate($dt[1],$dt[0],$dt[2])) { 
         header("location: ?go=news&act=edtv&tmpl=$tmpl&id=$id&err=3");
         return;
      }
      $name=substr($name,0,250);
      $cont=addslashes($cont);
      $name=addslashes($name);
      if(!mysql_query("update ".$natbl." set ".$fieldnmn['dat']."=CONCAT('".$dt[2]."-".$dt[1]."-".$dt[0]." ', CURTIME()),".$fieldnmn['tit']."='$name',".$fieldnmn['cont']."='$cont' where ".$fieldnmn['did']."=$id")){
         header("location: ?go=news&act=edtv&id=$id&tmpl=$tmpl&err=1");
         return;
      } else {    
         header("location: ?go=news&tmpl=$tmpl");
         return;
      }
      break;
   case "delOk":
      if(!mysql_query("delete from ".$natbl." where ".$fieldnmn['did']."=".$id)){
         if($arc){
            header("location: ?go=news&act=arch&tmpl=$tmpl&err=1");
            return;
         } else {
            header("location: ?go=news&tmpl=$tmpl&err=1");
            return;
         }
      } else {
         if($arc){
            header("location: ?go=news&tmpl=$tmpl&act=arch");
            return;
         } else {
            header("location: ?go=news&tmpl=$tmpl");     
            return;
         }
      }
      break;
   case "arcOk":
      if(!mysql_query("update ".$natbl." set ".$fieldnmn['ac']."=1 where ".$fieldnmn['did']."=".$id)){
            header("location: ?go=news&tmpl=$tmpl&err=2");
            return;
      } else {
            header("location: ?go=news&tmpl=$tmpl");     
            return;
      }
      break;
   case "osnOk":
      if(!mysql_query("update ".$natbl." set ".$fieldnmn['ac']."=0 where ".$fieldnmn['did']."=".$id)){
            header("location: ?go=news&act=arch&tmpl=$tmpl&err=2");
            return;
      } else {
            header("location: ?go=news&act=arch&tmpl=$tmpl");     
            return;
      }
      break;
}
}

function ret_html($tmpl){
return "<iframe name=\"BODYhtml\" width=\"100%\" height=\"100%\" frameborder=0 marginwidth=0 marginheight=0 src=\"fnciws/html/html_edit.php?tml=$tmpl&vrtp=-1\"  scrolling=no></iframe>";
}
?>