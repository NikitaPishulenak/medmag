<?php

include('fnciws/guestb/guest.inc.php');

if($act=="addOk" || $act=="delOk" || $act=="delcOk" || $act=="editOk" || $act=="edtCategoryOk" || $act=="addCategoryOk" || $act=="delCategoryOk" || $act=="replaceOk"){
   $cont=guestOk();  
} elseif($act=="edtCategory"){
   $cont=admin_edtCategory(); 
} else {
   $cont=admin_guest();    
}


function admin_edtCategory(){
global $err;

$ct="";
switch($err){
      case 1:
         $ct="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
         ."Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
      break;
      case 2:
         $ct="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
         ."Не введена вся информация. Попробуйте еще раз.</td></tr></table><br>";
      break;
}


      $content.=$ct."<script><!--
         function delOkK(urli){ \n"
      ."if(confirm('Вы действительно хотите удалить категорию ?\\n\\nВнимание!\\nПри удалении, автоматически будут удалены соовтетствующие записи.')){\n"
      ."document.location=urli;\n"
      ."}\n"
      ."}\n"

      ."function addK(){\n"
      ."var arr = null;\n"
      ."arr = showModalDialog(\"fnciws/guestb/dialog.php?evtype=addCategory\", null, \"dialogWidth:410px; dialogHeight:150px; status:no;\");\n"
      ."if (arr != null){\n"
      ."document.location='mainiws.php?go=guestbook&act=addCategoryOk&nm='+arr[\"cname\"]+'&cact='+arr[\"cact\"];\n"
      ."}\n}\n"

      ."function edtK(nme,did,cact){\n"
      ."var args = new Array();\n"
      ."var arr = null;\n"
      ."args[\"cname\"]=nme;\n"
      ."args[\"cact\"]=cact;\n"
      ."arr = showModalDialog(\"fnciws/guestb/dialog.php?evtype=edtCategory\", args, \"dialogWidth:410px; dialogHeight:150px; status:no;\");\n"
      ."if (arr != null){\n"
      ."document.location='mainiws.php?go=guestbook&act=edtCategoryOk&id='+did+'&nm='+arr[\"cname\"]+'&cact='+arr[\"cact\"];\n"
      ."\n}\n}\n"
      ."//--></script>\n"
      ."<table align=center width=70% border=0 cellpadding=2 cellspacing=1>\n"
      ."<tr><td><a href=\"#\" onclick=\"document.location='mainiws.php?go=guestbook'; return false;\">вернуться в категории</a></td><td><a href=\"#\" onclick=\"addK(); return false;\">добавить категорию</a></td></tr>\n"
      ."<tr align=center><td class=usr>Название категории</td><td class=usr>Состояние</td></tr>\n";

      $res=mysql_query("select id,name,activ,IF(activ,'(активна)','(<font size=1 color=#FF0000><i>неактивна</i></font>)') from iws_guestbk_category");
      if(mysql_numrows($res)>=1){
         $cl="menu1";
         while($arr=mysql_fetch_row($res)){
            
            if($cl=="menu1"){ $cl="menu"; } else { $cl="menu1"; }
            $content.="<tr class=$cl><td><b>".$arr[1]."</b>&nbsp;&nbsp;&nbsp;&nbsp;"
               ."[<a href=\"#\" onclick=\"edtK('".$arr[1]."',".$arr[0].",".$arr[2]."); return false;\">редактировать</a>] "
               ."[<a href=\"#\" onclick=\"delOkK('mainiws.php?go=guestbook&act=delCategoryOk&id=".$arr[0]."'); return false;\"><font color=#ff0000>удалить</font></a>]</td>"
               ."<td>".$arr[3]."</td></tr>\n";
         }
      } else {
         $content.="<tr><td colspan=3>Извините, в базе данных нет категорий!</td></tr>";
      } 
      $content.="<tr><td><a href=\"#\" onclick=\"document.location='mainiws.php?go=guestbook'; return false;\">вернуться в категории</a></td><td><a href=\"#\" onclick=\"addK(); return false;\">добавить категорию</a></td></tr>"
      ."</table>";
      return $content;

}

//------------------------------------------------------------------------------------------------------------------------------------

function admin_guest(){
global $act,$err,$natbl,$fieldnmn,$patbl,$fieldnmp,$QUERY_STRING,$start,$id,$cont,$mainadvar,$nme,$email,$fdt,$category;
if(!$start){ $start=1; }

$category = (isset($category) && $category>=1) ? $category : 0;
switch($err){
   case 1:
      $content="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
      ."Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
      break;
   case 2:
      if($act=="search"){
         $content="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
         ."По вашему запросу ничего не найдено. Попробуйте еще раз.</td></tr></table><br>";
      }
      break;
   case 3:
      $content="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
      ."Не введена вся информация. Попробуйте еще раз.</td></tr></table><br>";
      break;
}
switch($act){
case "search":
      $content.="<table width=100% border=0 cellpadding=3 cellspacing=0>"
      ."<tr><td align=center class=usr>Поиск записей</td></tr></table>";
         $content.="<script><!--
         function submitr(fr){
         if (fr.nme.value || fr.email.value || fr.fdt.value) { 
            fr.submit();
         } else {
            alert (\"Не введена информация для поиска записей!   \");
         }
         }
         //--></script>";
         $content.="<form method=\"post\" name=frm>"
         ."<input type=hidden name=go value=guestbook>"
         ."<input type=hidden name=act value=searck>"
         ."<input type=hidden name=start value=$start>"
         ."<input type=hidden name=category value=$category>"
         ."<table bgcolor=#ffffff width=100% border=0 cellpadding=3 cellspacing=2 align=center>"
         ."<tr><td align=right width=40%>По имени: </td><td><input name=\"nme\" size=50 maxlength=100 value=\"".$mainadvar['nme']."\"></td></tr>"
         ."<tr><td align=right>По e-mail: </td><td><input name=\"email\" size=50 maxlength=100 value=\"".$mainadvar['email']."\"></td></tr>"
         ."<tr><td align=right>По дате от: </td><td><input name=\"fdt\" size=10 maxlength=30 value=\"";
         if($mainadvar['fdt']){  $content.=$mainadvar['fdt']; } else {  $content.=date("d.m.Y"); }
         $content.="\"> Формат даты (дд.мм.гггг)</td></tr>"
         ."<tr><td colspan=2><hr></td></tr><tr><td></td><td><input class=but type=button name=btn value=\" Поиск >>\" onClick=\"submitr(frm)\">"
         ."&nbsp;&nbsp<input class=but type=button value=\"Отмена\" onclick=\"javascript:document.location='mainiws.php?go=guestbook&category=$category&start=$start'\"></td></tr>"
         ."</form></table>";
      break;
case "searck":
   $nme=trim($nme);
   $email=trim($email);
   $nme=substr($nme,0,100);
   $email=substr($email,0,100);
   $fdt=trim($fdt);
   if($fdt){
      $mainadvar['fdt']=$fdt;
      $fdt=substr($fdt,0,10);
      $fdt=explode(".",$fdt);
   }
   if(!empty($nme)){
      $scr=" A.".$fieldnmn['nm']." LIKE '$nme%'";
      $mainadvar['nme']=$nme;
   }
   if(!empty($nme) && !empty($email)){
      $scr.=" or A.".$fieldnmn['em']."='$email'";
      $mainadvar['email']=$email;
   }elseif(!empty($email)){
      $scr.=" A.".$fieldnmn['em']."='$email'";
      $mainadvar['email']=$email;
   }
   if(is_numeric($fdt[0]) && is_numeric($fdt[1]) && is_numeric($fdt[2]) && checkdate($fdt[1],$fdt[0],$fdt[2]) && (!empty($nme) || !empty($email))) { 
      $scr.=" or A.".$fieldnmn['dat'].">='".$fdt[2]."-".$fdt[1]."-".$fdt[0]."'";
   }elseif(is_numeric($fdt[0]) && is_numeric($fdt[1]) && is_numeric($fdt[2]) && checkdate($fdt[1],$fdt[0],$fdt[2])) { 
      $scr.=" A.".$fieldnmn['dat'].">='".$fdt[2]."-".$fdt[1]."-".$fdt[0]."'";
   }
   if($scr){
      $scr=$scr." and A.".$fieldnmn['ln']."='".$mainadvar['lng']."'";
   } else {
      header("location: ?go=guestbook&act=search&nme=$nme&email=$email&err=2&category=$category&start=$start");
      return;
   }
   if(!$start){ $start=1; }
   $prom=numlink($start,"go=guestbook&act=searck&category=$category&start=$start&nme=$nme&email=$email&fdt=".$fdt[0].".".$fdt[1].".".$fdt[2],$scr);
   if($prom!="none"){
      list($lmt)=mysql_fetch_row(mysql_query("select ".$fieldnmp['lmt']." from ".$patbl));
      $sqlQuery="select A.".$fieldnmn['did'].",DATE_FORMAT(A.".$fieldnmn['dat'].",'%W, %e %M %Y в %T'),"
                  ."A.".$fieldnmn['nm'].",A.".$fieldnmn['em'].",A.".$fieldnmn['ww'].",A.".$fieldnmn['icq'].","
                  ."A.".$fieldnmn['ct'].",A.".$fieldnmn['cr'].",A.".$fieldnmn['cm'].", IF(A.category>=1,B.name,'Общая') from "
                  .$natbl." A LEFT JOIN iws_guestbk_category B ON A.category=B.id WHERE ".$scr." GROUP BY A.id ORDER BY A.".$fieldnmn['dat']." DESC limit ".($start-1).",$lmt";
      $res=mysql_query($sqlQuery);
      if(mysql_numrows($res)>=1){
         $content.="<table width=100% border=0 cellpadding=2 cellspacing=1 align=center>"
         ."<tr><td colspan=6><input class=but type=button value=\" << вернуться \" onclick=\"javascript:document.location='mainiws.php?go=guestbook&category=$category&start=$start'\">&nbsp;&nbsp;"
         ."<input class=but type=button value=\" Новый поиск \" onclick=\"javascript:document.location='mainiws.php?go=guestbook&act=search&nme=$nme&email=$email&category=$category&start=$start'\">&nbsp;&nbsp;"
         ."<hr><style type=\"text/css\"><!-- "
         ."span.cr {border:1px solid #dddddd;}"
         ."//-->"
         ."</style>"
         ."<b>Шаблон поиска</b> <span class=cr>&nbsp;";
         if($nme){   $content.="имя:<b>$nme</b>"; }
         if($email){ $content.=" e-mail:<b>$email</b>"; }
         if($fdt){ $content.=" дата от <b>".$fdt[0].".".$fdt[1].".".$fdt[2]."</b>"; }
         $content.="&nbsp</span>"
         ."<br><br></td></tr></table>"
         ."<table width=100% border=0 cellpadding=3 cellspacing=0>"
         ."<tr><td align=center class=usr>Результаты поиска</td></tr></table>"
         ."<script><!--\n"
         ."function deOk(urli){ \n"
         ."if(confirm(\"Вы действительно хотите удалить запись?     \")){\n"
         ."document.location=urli;\n"
         ."}\n"
         ."}\n"
         ."//--></script>\n"
         ."<table width=100% border=0 cellpadding=2 cellspacing=1 align=center>"
         ."<tr><td colspan=4>".$prom."<br></td></tr>";
         $cls="menu1";
         $nm=$start;
         while($arr=mysql_fetch_row($res)){
            if($cls=="menu1"){ $cls="menu"; } else { $cls="menu1"; }
            $content.="<tr><td valign=top>".($nm++)."</td><td class=$cls>Категория: <b>".$arr[9]."</b><br>".tblg($arr)."</td><td class=$cls nowrap>"
            ."<a href=\"#\" onclick=\"deOk('mainiws.php?go=guestbook&act=delOk&id=".$arr[0]."&category=$category&start=$start'); return false;\"><font color=#ff0000>удалить</font></a></td></tr>";
         }
         $content.="<tr><td colspan=4><br>".$prom."</td></tr>"
         ."</table>";
      } else {
         header("location: ?go=guestbook&act=search&nme=$nme&email=$email&err=2&category=$category&start=$start");
         return;
      }
   } else {
      header("location: ?go=guestbook&act=search&nme=$nme&email=$email&err=2&category=$category&start=$start");
      return;
   }
      break;
case "addc":

      $content.="
      <table width=100% border=0 cellpadding=0 cellspacing=0><tr><td align=center width=100% class=usr>Добавить комментарий</td>
      <td class=usr><img onclick=\"javascript: document.location='mainiws.php?go=guestbook&category=$category&start=$start';\" src=\"images/close.gif\" border=0 alt=\"Закрыть окно\" style=\"cursor:hand\"></td>
      </tr></table>
      <script><!--
         function submitr(){
            if(frm.name.value && window.BODYhtml.FormHTML.elm1.value){
               frm.cont.value=window.BODYhtml.FormHTML.elm1.value;
               frm.submit();
            } else {
               alert (\"В сохранении отказано!\\n\\nНе введена информация автора!   \");
            }
         }

         function opnew(){
            window.open(\"fnciws/guestb/dialog.php?id=$id\",\"msd\",\"toolbar=no,scrollbars=yes, width=700, height=600\");
         }

         //--></script>

         <form method=\"post\" name=frm>
         <input type=hidden name=go value=guestbook>
         <input type=hidden name=act value=addOk>
         <input type=hidden name=start value=$start>
         <input type=hidden name=category value=$category>
         <input type=hidden name=cont value=\"\">
         <input type=hidden name=id value=$id>
         <table width=100% height=100% border=0 cellpadding=0 cellspacing=5>
         <tr valign=top height=100%>
            <td valgn=top><img src=\"images/obs.gif\" alt=\"Просмотр вопроса\" onClick=\"opnew();\" style=cursor:hand></td>
            <td width=100%>
               <table width=100% height=100% border=0>
                  <tr><td height=40><p><b>Автор</b><br><input type=text name=\"name\" style=\"width: 100%;\"></p></td></tr>
                  <tr><td><p><b>Комментарии</b><br>".ret_html()."</p></td></tr>
               </table>
            </td>
         </tr></table>
         </form><br>";

/* 
   <!--           //-->
      $content.="<form method=\"post\" name=frm>"
         ."<input type=hidden name=go value=guestbook>"
         ."<input type=hidden name=act value=addOk>"
         ."<input type=hidden name=id value=$id>"
         ."<table bgcolor=#ffffff width=100% border=0 cellpadding=3 cellspacing=2 align=center>"
         ."<tr><td align=right width=20%>Имя: </td><td><input name=\"name\" size=80 maxlength=150 value=\"";
         if($name) { $content.=$name; } else { $content.="Администратор"; }
         $content.="\"></td></tr>"
         ."<tr valign=top><td align=right>Комментарий: <br><br><img src=\"images/obs.gif\" alt=\"Предварительный просмотр\" onClick=\"opnew(frm)\" style=cursor:hand>&nbsp;&nbsp;</td><td><textarea name=cont rows=15 cols=60>$cont</textarea></td></tr>"
         ."<tr><td colspan=2><hr></td></tr><tr><td></td><td><input class=but type=button name=btn value=Добавить onClick=\"submitr(frm)\">"
         ."&nbsp;&nbsp<input class=but type=button value=\"Отмена\" onclick=\"javascript:document.location='mainiws.php?go=guestbook'\"></td></tr>"
         ."</form></table>";
*/
      break;
	  case "editc":
	  
	$id=$_GET['id'];
    $res="select name,coment FROM  `iws_guestcm`  where id=".$id;
	$arr=mysql_fetch_row(mysql_query($res));
	
      $content.=mysql_error()."
	  
	    <table width=100% border=0 cellpadding=0 cellspacing=0><tr><td align=center width=100% class=usr>Добавить комментарий</td>
      <td class=usr><img onclick=\"javascript: document.location='mainiws.php?go=guestbook&category=$category&start=$start';\" src=\"images/close.gif\" border=0 alt=\"Закрыть окно\" style=\"cursor:hand\"></td>
      </tr></table>
      <script><!--
         function submitr(){
            if(frm.name.value && window.BODYhtml.FormHTML.elm1.value){
               frm.cont.value=window.BODYhtml.FormHTML.elm1.value;
               frm.submit();
            } else {
               alert (\"В сохранении отказано!\\n\\nНе введена информация автора!   \");
            }
         }
         function opnew(){
            window.open(\"fnciws/guestb/dialog.php?id=$id\",\"msd\",\"toolbar=no,scrollbars=yes, width=700, height=600\");
         }

         //--></script>

         <form method=\"post\" name=frm>
         <input type=hidden name=go value=guestbook>
         <input type=hidden name=act value=editOk>
         <input type=hidden name=start value=$start>
         <input type=hidden name=category value=$category>
         <input type=hidden name=cont value=\"\">
         <input type=hidden name=id value=$id>
         <table width=100% height=100% border=0 cellpadding=0 cellspacing=5>
         <tr valign=top height=100%>
            <td valgn=top><img src=\"images/obs.gif\" alt=\"Просмотр вопроса\" onClick=\"opnew();\" style=cursor:hand></td>
            <td width=100%>
               <table width=100% height=100% border=0>
                  <tr><td height=40><p><b>Автор</b><br><input type=text name=\"name\" value='".$arr[0]."' style=\"width: 100%;\"></p></td></tr>
                  <tr><td><p><b>Комментарии</b><br>".ret_html()."</p></td></tr>
				   <SCRIPT LANGUAGE=JavaScript FOR=\"window\" EVENT=onload><!--
            BODYhtml.FormHTML.elm1.value=\"".ereg_replace("\"","'",ereg_replace("(\r|\n)","",stripslashes($arr[1])))."\";
            //--></script>
               </table>
            </td>
         </tr></table>
         </form><br>";


      break;
	  

default:
   $mainadvar['nme']="";
   $mainadvar['email']="";
   $mainadvar['fdt']="";
   $qwr=ereg_replace("&start=".$start,"",$QUERY_STRING);

   $nameCategory = "<h5>Категория Общая</h5>";
   $content="<table width=100%><tr valign=top><td>";
   $rescat=mysql_query("select id,name,IF(activ,'(активна)','(<font size=1 color=#FF0000><i>неактивна</i></font>)') from iws_guestbk_category");
   $tableCategory = "<table border=0 cellpadding=2 cellspacing=1 width=100% bgcolor=#EFEFEF>";

      if(mysql_numrows($rescat)>=1){
         if($category>=1) $tableCategory.="<tr><td><a href=\"?go=guestbook&category=0\" title=\"Открыть категорию\"><b>Общая</b></a></td><td>(активна)</td></tr>"; 
         while($arrCat=mysql_fetch_row($rescat)){
            if($arrCat[0]==$category) $nameCategory = "<h5>Категория ".$arrCat[1]."</h5>";

            $tableCategory.="<tr><td><a href=\"?go=guestbook&category=".$arrCat[0]."\" title=\"Открыть категорию\"><b>".$arrCat[1]."</b></a></td><td>".$arrCat[2]."</td></tr>"; 
         }
      } else {
         $tableCategory.="<tr><td colspan=2><b>Других категорий нет!</b></td></tr>"; 
      }

   $tableCategory.="<tr><td colspan=2><hr><a href=\"?go=guestbook&act=edtCategory\">Редактировать категории</a></td></tr>"; 
   $tableCategory.="</table>";

   $content.=$nameCategory;
   $prom=numlink($start,$qwr,"");
   if($prom!="none"){

      list($lmt)=mysql_fetch_row(mysql_query("select ".$fieldnmp['lmt']." from ".$patbl));
      $res=mysql_query("select A.".$fieldnmn['did'].",DATE_FORMAT(A.".$fieldnmn['dat'].",'%W, %e %M %Y в %T'),"
                  ."A.".$fieldnmn['nm'].",A.".$fieldnmn['em'].",A.".$fieldnmn['ww'].",A.".$fieldnmn['icq'].","
                  ."A.".$fieldnmn['ct'].",A.".$fieldnmn['cr'].",A.".$fieldnmn['cm'].",A.".$fieldnmn['nom'].",A.".$fieldnmn['ip']."  from "
                  .$natbl." A WHERE A.category=$category AND A.".$fieldnmn['ln']."='".$mainadvar['lng']."' ORDER BY A.".$fieldnmn['dat']." DESC limit ".($start-1).",$lmt");
   
      $content.="<table width=100% border=0 cellpadding=2 cellspacing=1 align=center>"
      ."<tr><td colspan=6><input class=but type=button value=\" Поиск \" onclick=\"javascript:document.location='mainiws.php?go=guestbook&act=search&category=$category&start=$start'\">&nbsp;&nbsp;"
      ."</td></tr></table>"
      ."<script><!--\n"
      ."function deOk(urli){ \n"
      ."if(confirm(\"Вы действительно хотите удалить запись?     \")){\n"
      ."document.location=urli;\n"
      ."}\n"
      ."}
         function replaceOkpos(urlR)
         {
            var arr = null;
            arr = showModalDialog(\"fnciws/guestb/dialog.php?evtype=replacePos&idCat=$category\", null, \"dialogWidth:410px; dialogHeight:120px; status:no;\");
            if (arr != null){
               document.location=urlR+'&newCat='+arr;
            }
         }
      "
      ."//--></script>\n"
      ."<table width=100% border=0 cellpadding=2 cellspacing=1 align=center>"
      ."<tr><td colspan=4>".$prom."<br></td></tr>";
      if(mysql_numrows($res)>=1){
         $cls="menu1";
         $nm=$start;
         
            while($arr=mysql_fetch_row($res)){
               if($arr[9]){
                  $cls="menuC";
               }else if($cls=="menu1"){
                  $cls="menu";
               } else {
                  $cls="menu1";
               }
            
            $content.="\n<tr>\n<td valign=top>".($nm++)."</td><td class=$cls>".tblg($arr)."</td>\n<td class=$cls valign=top>"
            ."<a href=\"#\" title=\"Переместить запись в другую категорию\" onclick=\"replaceOkpos('mainiws.php?go=guestbook&act=replaceOk&start=$start&category=$category&id=".$arr[0]."'); return false;\">переместить</a><br>"
            ."[<a href=\"#\" onclick=\"deOk('mainiws.php?go=guestbook&act=delOk&id=".$arr[0]."&category=$category&start=$start'); return false;\"><font color=#ff0000>удалить</font></a>]</td>\n</tr>";
         }
      } 
      $content.="<tr><td colspan=4><br>".$prom."</td></tr></table>";
   } else {
      $content.="<center><b>Извините в данной категории записей нет!</b></center>";
   }
   $content.="</td><td width=200><h5>Другие категории</h5>".$tableCategory."</td></tr></table>";
   break;
}
unset($act,$err,$natbl,$fieldnmn,$patbl,$fieldnmp,$start,$id,$cont,$nme,$email,$fdt,$scr,$qwr);
return $content;
}


function tblg($arr){
global $gatbl,$fieldnmg,$start,$category;
$res=mysql_query("select ".$fieldnmg['did'].",DATE_FORMAT(".$fieldnmg['dat'].",'%W, %e %M %Y в %T'),"
                  .$fieldnmg['nm'].",".$fieldnmg['em'].",".$fieldnmg['ww'].",".$fieldnmg['icq'].","
                  .$fieldnmg['ct'].",".$fieldnmg['cr'].",".$fieldnmg['cm'].",".$fieldnmg['rt']." from ".$gatbl." where ".$fieldnmg['gd']."=".$arr[0]." order by ".$fieldnmg['dat']." DESC");
$nmr=mysql_numrows($res);
//$ret=mysql_error();
$ret.="<table width=100% cellpadding=2><tr><td class=usr>".$arr[2]."</td>"; 
//if($arr[4]){ $ret.="\n<td bgcolor=#FFFFFF><a class=im target=_blank href=\"".$arr[4]."\"><img src=\"images/icon_www.gif\" border=0 alt=\"homepage: ".$arr[4]."\"></a></td>"; }
if($arr[4]){ $ret.="\n<td class=usr>".$arr[4]."</td>"; }
if($arr[3]){ $ret.="\n<td bgcolor=#FFFFFF><a class=im href=\"mailto:".$arr[3]."\"><img src=\"images/icon_email.gif\" border=0 alt=\"e-mail: ".$arr[3]."\"></a></td>"; } 
if($arr[5]){ $ret.="\n<td bgcolor=#FFFFFF><a class=im target=_blank href=\"http://wwp.icq.com/".$arr[5]."#pager\"><img src=\"images/icon_icq.gif\" border=0 alt=\"icq: ".$arr[5]."\" valign=top></a></td>"; } 
if($arr[6]){ $ret.="\n<td class=usr>".$arr[6]."</td>"; } 
if($arr[7]){ $ret.="\n<td class=usr>".$arr[7]."</td>"; } 

$ret.="<td class=usr></td></tr></table>"
."<br>".stripslashes($arr[8])."<br><br>".$arr[1]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IP: ".$arr[10];
$ret.="<hr><table width=100%><tr><td colspan=2><b>Комментарии:</b></td></tr>";

if($nmr){
   while($arc=mysql_fetch_row($res)){
      $ret.="<tr><td width=25></td><td><table width=100% bgcolor=#000000 cellpadding=2 cellspacing=1><tr><td bgcolor=#ffffff>"; 
      if($arc[9]){
         $ret.="<div class=rt><b>".$arc[2]."</b><hr>".stripslashes($arc[8])."</div>"
         ."<table width=100% cellpadding=2><tr><td>".$arc[1]."</td>"
         ."<td align=right>[ <a href=\"?go=guestbook&act=editc&id=".$arc[0]."&category=$category&start=$start\"><span>редактировать комментарий<span></a> ]&nbsp;[ <a href=\"#\" onclick=\"deOk('mainiws.php?go=guestbook&act=delcOk&id=".$arc[0]."&category=$category&start=$start'); return false;\"><font color=#ff0000>удалить комментарий</font></a> ]</td></tr></table>";
      } else {
         $ret.="<table width=100% cellpadding=2><tr><td><b>".$arc[2]."</b></td>"; 
         if($arc[4]){ $ret.="\n<td><a class=im target=_blank href=\"".$arc[4]."\"><img src=\"images/icon_www.gif\" border=0 alt=\"homepage: ".$arc[4]."\"></a></td>"; }
         if($arc[3]){ $ret.="\n<td><a class=im href=\"mailto:".$arc[3]."\"><img src=\"images/icon_email.gif\" border=0 alt=\"e-mail: ".$arc[3]."\"></a></td>"; } 
         if($arc[5]){ $ret.="\n<td><a class=im target=_blank href=\"http://wwp.icq.com/".$arc[5]."#pager\"><img src=\"images/icon_icq.gif\" border=0 alt=\"icq: ".$arc[5]."\" valign=top></a></td>"; } 
         if($arc[6]){ $ret.="\n<td><b>".$arc[6]."</b></td>"; } 
         if($arc[7]){ $ret.="\n<td><b>".$arc[7]."</b></td>"; } 
         $ret.="<td width=100%></td></tr></table>"
         .stripslashes($arc[8])
         ."\n<table width=100% cellpadding=2><tr><td>".$arc[1]."</td>"
         ."<td align=right>[ <a href=\"?go=guestbook&act=editc&id=".$arc[0]."&category=$category&start=$start\"><span>редактировать комментарий<span></a> ]&nbsp;[ <a href=\"#\" onclick=\"deOk('mainiws.php?go=guestbook&act=delcOk&id=".$arc[0]."&category=$category&start=$start'); return false;\"><font color=#ff0000>удалить комментарий</font></a> ]</td></tr></table>";
      }
      $ret.="</td></tr></table></td></tr>";
   }
}
$ret.="<tr><td colspan=2>[ <a href=\"?go=guestbook&act=addc&id=".$arr[0]."&category=$category&start=$start\">добавить комментарий</a> ]</td></tr></table>";
return $ret;
}

function numlink($start,$oper,$sr){
global $natbl,$fieldnmn,$patbl,$fieldnmp,$mainadvar,$category;
list($lmt,$viv)=mysql_fetch_row(mysql_query("select ".$fieldnmp['lmt'].",".$fieldnmp['vd']." from ".$patbl));
if(!$sr){
   $qw="select count(A.".$fieldnmn['did'].") from ".$natbl." A WHERE A.category=$category AND A.".$fieldnmn['ln']."='".$mainadvar['lng']."'";
}else{
   $qw="select count(A.".$fieldnmn['did'].") from ".$natbl." A WHERE ".$sr;
}
list($cnt)=mysql_fetch_row(mysql_query($qw));
if($cnt>=1){
   if(is_integer($cnt/$lmt)){
      $cr=$cnt/$lmt;       
   }else{
      $cr=round(($cnt/$lmt)+(0.5));
   }
   if(!$viv){
      $nv=($start-1)/$lmt;
      if((round(($nv/10)+(0.5)))*10<$cr){          
         $kn=(round(($nv/10)+(0.5)))*10;
      } else {
         $kn=$cr;          
      }
      $rd=round(($nv/10)-0.5);
      if($rd<0){ $rd=0; }
      $nv=($rd*10)+1;
   } else {
      $nv=1;
      $kn=$cr;
   }
      $rt="<table width=100% border=0 cellpadding=1 cellspacing=0><tr><td";
      if($viv){ $rt.=" align=center width=100%"; }
      $rt.=">"
      ."<style type=\"text/css\"><!-- "
      ."span.cur {border:1px solid #666666; background-color:#eeeeee;font-size:9pt;font-weight:bold;}"
      ."span.oth {border:1px solid #dddddd;}"
      ."//-->"
      ."</style>";
      if($start<>1 && !$viv){ $rt.="<a class=im href=\"?$oper&start=".($start-$lmt)."\">&lt;&lt;предыдущие $lmt</a> "; }
      for($i=$nv;$i<=$kn;$i++){
         if($start==1 && $i==1){
            $rt.=" <b><span class=cur>&nbsp".$i."&nbsp</span></b> ";
         }elseif((($i-1)*$lmt)+1==$start){
            $rt.=" <b><span class=cur>&nbsp".$i."&nbsp</span></b> ";
         } else {
            if($viv){
               $rt.=" [&nbsp<a class=im href=\"?$oper&start=".((($i-1)*$lmt)+1)."\">".$i."</a>&nbsp] ";
            } else {
               $rt.=" <span class=oth>&nbsp<a class=im href=\"?$oper&start=".((($i-1)*$lmt)+1)."\">".$i."</a>&nbsp</span> ";
            }
         }
      }
      if((($cr-1)*$lmt)+1!=$start && !$viv){ $rt.=" <a class=im href=\"?$oper&start=".($start+$lmt)."\">следующие $lmt>></a>"; }    
      $rt.="</td><td align=right valign=top nowrap>&nbsp$start..";
      if($cnt-$start>=$lmt-1){ 
         $rt.=$start+$lmt-1;
      }else{
         $rt.=$cnt;        
      }
      $rt.=" из ".$cnt."</td></tr></table>";
   return $rt;
} else {
   return "none";
}
}

//-----------------------------------------------------------------------------------------------------------------------------------

function ret_html(){
      return "<iframe name=\"BODYhtml\" width=\"100%\" height=\"85%\" frameborder=0 marginwidth=0 marginheight=0 src=\"fnciws/html/html_edit.php?vrtp=-1&guidepst=1\" scrolling=no></iframe>";
}

//-----------------------------------------------------------------------------------------------------------------------------------

function guestOk(){
global $act,$id,$name,$cont,$natbl,$fieldnmn,$gatbl,$fieldnmg,$patbl,$fieldnmp,$lmt,$rd1,$rd2,$rd3,$mainadvar,$start,$category,$nm,$cact,$newCat;
switch($act){
   case "replaceOk":
      if(!mysql_query("update iws_guestbk set category=$newCat where id=$id")){
         header("location: ?go=guestbook&category=$category&start=$start&err=1");
         return;
      } else {    
         header("location: ?go=guestbook&category=$category&start=$start");
         return;
      }
   break;

   case "addOk":
      $cont=trim($cont);
      $name=trim($name);
      if(empty($cont) || empty($name)) { 
         header("location: ?go=guestbook&act=addc&id=$id&cont=$cont&name=$name&err=3");
         return;
      }
      $cont=addslashes($cont);
      if(!mysql_query("insert into $gatbl (".$fieldnmg['gd'].",".$fieldnmg['dat'].",".$fieldnmg['nm'].",".$fieldnmg['cm'].",".$fieldnmg['rt'].") values ($id,'".date('Y-m-d H:i:s', time())."','$name','$cont',1)")){
         header("location: ?go=guestbook&act=addc&id=$id&cont=$cont&name=$name&err=1&category=$category&start=$start");
         return;
      } else {
         mysql_query("update iws_guestbk set nomod=0 where id=$id");       
         if($mainadvar['nme'] || $mainadvar['email'] || $mainadvar['fdt']){
            $ngo="go=guestbook&act=searck&category=$category&start=$start&nme=".$mainadvar['nme']."&email=".$mainadvar['email']."&fdt=".$mainadvar['fdt'];
         } else {
            $ngo="go=guestbook&category=$category&start=$start";
         }        
         header("location: ?$ngo");
         return;
      }
      break;
	  case "editOk":
      $cont=trim($cont);
      $name=trim($name);

      if(empty($cont) || empty($name) ) { 
         header("location: ?go=guestbook&act=editc&id=$id&cont=$cont&name=$name&err=3");
         return;
      }
      $cont=addslashes($cont);
      if(!mysql_query("update `iws_guestcm`  SET name='".$name."', coment='".$cont."' WHERE id=".$id)){
         header("location: ?go=guestbook&act=editc&id=$id&cont=$cont&name=$name&err=1&category=$category&start=$start");
         return;
      } else {
         mysql_query("update iws_guestbk set nomod=0 where id=$id");       
         if($mainadvar['nme'] || $mainadvar['email'] || $mainadvar['fdt']){
            $ngo="go=guestbook&act=searck&category=$category&start=$start&nme=".$mainadvar['nme']."&email=".$mainadvar['email']."&fdt=".$mainadvar['fdt'];
         } else {
            $ngo="go=guestbook&category=$category&start=$start";
         }        
         header("location: ?$ngo");
         return;
      }
      break;
   case "delcOk":
      if(!mysql_query("delete from ".$gatbl." where ".$fieldnmg['did']."=".$id)){
         header("location: ?go=guestbook&category=$category&start=$start&err=1");
         return;
      } else {
         if($mainadvar['nme'] || $mainadvar['email'] || $mainadvar['fdt']){
            $ngo="go=guestbook&act=searck&category=$category&start=$start&nme=".$mainadvar['nme']."&email=".$mainadvar['email']."&fdt=".$mainadvar['fdt'];
         } else {
            $ngo="go=guestbook&category=$category&start=$start";
         }        
         header("location: ?$ngo");
         return;
      }
      break;
   case "delOk":
      if(!mysql_query("delete from ".$gatbl." where ".$fieldnmg['gd']."=".$id) || !mysql_query("delete from ".$natbl." where ".$fieldnmn['did']."=".$id)){
         header("location: ?go=guestbook&category=$category&start=$start&err=1");
         return;
      } else {
         if($mainadvar['nme'] || $mainadvar['email'] || $mainadvar['fdt']){
            $ngo="go=guestbook&act=searck&category=$category&start=$start&nme=".$mainadvar['nme']."&email=".$mainadvar['email']."&fdt=".$mainadvar['fdt'];
         } else {
            $ngo="go=guestbook&category=$category&start=$start";
         }        
         header("location: ?$ngo");
         return;
      }
      break;
   case "delCategoryOk":
         $resultTod = mysql_query("SELECT id FROM iws_guestbk WHERE category=$id");
         if(mysql_numrows($resultTod)>=1){
            while(List($gidd)=mysql_fetch_row($resultTod)) mysql_query("DELETE FROM iws_iws_guestcm WHERE gid=".$gidd);
         }
         mysql_query("delete from iws_guestbk where category=".$id);
         mysql_query("delete from iws_guestbk_category where id=".$id);

         header("location: ?go=guestbook&act=edtCategory");
         return;

      break;

   case "addCategoryOk":
      $nm=trim($nm);
      if(empty($nm)) { 
         header("location: ?go=guestbook&act=edtCategory&err=2");
         return;
      }
      $nm=addslashes($nm);
      if(!mysql_query("insert into iws_guestbk_category (name,activ) values ('$nm',$cact)")){
         header("location: ?go=guestbook&act=edtCategory&err=1");
         return;
      } else {    
         header("location: ?go=guestbook&act=edtCategory");
         return;
      }

   break;

   case "edtCategoryOk":
      $nm=trim($nm);
      if(empty($nm)) { 
         header("location: ?go=guestbook&act=edtCategory&err=2");
         return;
      }
      $nm=addslashes($nm);
      if(!mysql_query("update iws_guestbk_category set name='$nm',activ=$cact where id=$id")){
         header("location: ?go=guestbook&act=edtCategory&err=1");
         return;
      } else {    
         header("location: ?go=guestbook&act=edtCategory");
         return;
      }
      break;

}
}

?>