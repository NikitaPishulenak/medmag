<?php

if($act=="main" || $act=="vvod" || $act=="vivod"){
   if($ret=="edtOk" || $ret=="addOk" || $ret=="addOkinTemplate" || $ret=="delOk"){
      $cont=funcOk();
   }elseif($ret=="addTMinTemplate"){
      $cont=addTMinTemplate();
   }elseif($ret=="addTM"){
      $cont=addTM();
   }else{
      $cont=edtTM();
   }
} else {
   $cont=maintm();
}

function funcOk()
{
global $ret,$act,$id,$cont,$nme;

switch($ret){
   case "edtOk":
      $cont=trim($cont);
      if(empty($cont)){
         header("location:?gopr=htmtampl&act=$act&err=2&id=$id");
         return;
      }
      $cont=addslashes($cont);
      switch($act){
       case "main":
            $sql="UPDATE iws_html_templ_design SET templru='".$cont."' WHERE id=$id";
         break;
       case "vivod":
            $sql="UPDATE iws_html_templ_vivod SET templateru='".$cont."' WHERE id=$id";
         break;
       case "vvod":
            $sql="UPDATE iws_html_templ SET template='".$cont."' WHERE id=$id";
         break;
      }
      if(!mysql_query($sql)){
         header("location:?gopr=htmtampl&act=$act&err=1&id=$id");
         return;
      }else{
         header("location:?gopr=htmtampl");
         return;
      }
   break;
   case "addOk":
      $cont=trim($cont);
      $nme=trim($nme);  
      if(empty($cont) || empty($nme)){
         header("location:?gopr=htmtampl&act=vvod&ret=addTM&err=2&nme=$nme");
         return;
      }
      $cont=addslashes($cont);
      $nme=addslashes($nme);
      $sql="INSERT INTO iws_html_templ (name,template,lng) VALUES ('".$nme."','".$cont."','ru')";
      if(!mysql_query($sql)){
         header("location:?gopr=htmtampl&act=vvod&ret=addTM&err=1&nme=$nme");
         return;
      }else{
         header("location:?gopr=htmtampl");
         return;
      }
   break;
   case "addOkinTemplate":
      $cont=trim($cont);
      $nme=trim($nme);  
      if(empty($cont) || empty($nme)){
         header("location:?gopr=htmtampl&act=vvod&ret=addTMinTemplate&err=2&nme=$nme");
         return;
      }
      $cont=addslashes($cont);
      $nme=addslashes($nme);
      $sql="INSERT INTO iws_html_templ (name, template, lng, variables, inTemplate) VALUES ('".$nme."', '".$cont."', 'ru', '0,2,3', 1)";
      if(!mysql_query($sql)){
         header("location:?gopr=htmtampl&act=vvod&ret=addTMinTemplate&err=1&nme=$nme");
         return;
      }else{
         header("location:?gopr=htmtampl");
         return;
      }
   break;

   case "delOk":
      mysql_query("DELETE FROM iws_html_templ WHERE id=".$id);
      header("location:?gopr=htmtampl");
      return;
   break;
}
}

function addTMinTemplate()
{
global $err,$act,$nme,$cont;

if($err==1){
   $ct="Произошла <FONT color=#ff0000>ошибка</font>. Попробуйте еще раз.";
}elseif($err==2){
   $ct="<FONT color=#ff0000>Не введена</font> информация шаблона. Попробуйте еще раз.";
}
      $content.="<script><!--
      var trt = 0;
      var prt;

      function submitr(){
      if(frm.nme.value){
         if(window.BODYhtml.FormHTML.elm1.value){
            if(trt) renwin();
            frm.cont.value=window.BODYhtml.FormHTML.elm1.value;
            frm.submit();
         } else {
            alert (\"Не введена информация содержания шаблона!   \");
         }
      }else{
         alert (\"Не введена информация имени шаблона!   \");
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
      //--></script>
      <form method=\"post\" name=frm>
      <input type=hidden name=gopr value=htmtampl>
      <input type=hidden name=act value=vvod>
      <input type=hidden name=ret value=addOkinTemplate>
      <input type=hidden name=cont value=\"\">
      <table bgcolor=#ffffff height=100% width=100% border=0 cellpadding=0 cellspacing=0>
      <tr><td width=100% nowrap class=usr>&nbsp;Новый шаблон блока меню. $ct</td><td class=usr><img onclick=\"renwin()\" src=\"images/zoom.gif\" border=0 alt=\"Развернуть/уменьшить окно\" style=\"cursor:hand\"></td>
      <td class=usr><img onclick=\"javascript:if(trt){ renwin(); } document.location='?gopr=htmtampl'\" src=\"images/close.gif\" border=0 alt=\"Закрыть шаблон\" style=\"cursor:hand\"></td></tr>
      <tr><td colspan=3><br>Введите имя шаблона <input name=nme maxlength=100 size=80 value=\"".stripslashes($nme)."\"></td></tr>
      <tr valign=top><td height=100% colspan=3>";
      $content.=ret_html("0 || lc=2 || lc=3");
      $content.="</td></tr>"
      ."</form></table>";
      if($cont){
         $content.="
            <SCRIPT LANGUAGE=JavaScript FOR=\"window\" EVENT=onload><!--
            BODYhtml.FormHTML.elm1.value=\"".ereg_replace("\"","'",ereg_replace("(\r|\n)","",stripslashes($cont)))."\";
            //--></script>\n";
      }
   return $content;
}

//---------------------------------------------------------------------------------------------


function addTM(){
global $err,$act,$nme,$cont;

if($err==1){
   $ct="Произошла <FONT color=#ff0000>ошибка</font>. Попробуйте еще раз.";
}elseif($err==2){
   $ct="<FONT color=#ff0000>Не введена</font> информация шаблона. Попробуйте еще раз.";
}
      $content.="<script><!--
      var trt = 0;
      var prt;

      function submitr(){
      if(frm.nme.value){
         if(window.BODYhtml.FormHTML.elm1.value){
            if(trt) renwin();
            frm.cont.value=window.BODYhtml.FormHTML.elm1.value;
            frm.submit();
         } else {
            alert (\"Не введена информация содержания шаблона!   \");
         }
      }else{
         alert (\"Не введена информация имени шаблона!   \");
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
      //--></script>
      <form method=\"post\" name=frm>
      <input type=hidden name=gopr value=htmtampl>
      <input type=hidden name=act value=vvod>
      <input type=hidden name=ret value=addOk>
      <input type=hidden name=cont value=\"\">
      <table bgcolor=#ffffff height=100% width=100% border=0 cellpadding=0 cellspacing=0>
      <tr><td width=100% nowrap class=usr>&nbsp;Новый шаблон ввода информации. $ct</td><td class=usr><img onclick=\"renwin()\" src=\"images/zoom.gif\" border=0 alt=\"Развернуть/уменьшить окно\" style=\"cursor:hand\"></td>
      <td class=usr><img onclick=\"javascript:if(trt){ renwin(); } document.location='?gopr=htmtampl'\" src=\"images/close.gif\" border=0 alt=\"Закрыть шаблон\" style=\"cursor:hand\"></td></tr>
      <tr><td colspan=3><br>Введите имя шаблона <input name=nme maxlength=100 size=80 value=\"".stripslashes($nme)."\"></td></tr>
      <tr valign=top><td height=100% colspan=3>";
      $content.=ret_html(-1);
      $content.="</td></tr>"
      ."</form></table>";
      if($cont){
         $content.="
            <SCRIPT LANGUAGE=JavaScript FOR=\"window\" EVENT=onload><!--
            BODYhtml.FormHTML.elm1.value=\"".ereg_replace("\"","'",ereg_replace("(\r|\n)","",stripslashes($cont)))."\";
            //--></script>\n";
      }
   return $content;
}

//---------------------------------------------------------------------------------------------

function edtTM()
{
global $err,$act,$id,$cont;

if($err==1){
   $ct="Произошла <FONT color=#ff0000>ошибка</font>. Попробуйте еще раз.";
}elseif($err==2){
   $ct="<FONT color=#ff0000>Не введена</font> информация шаблона. Попробуйте еще раз.";
}

if(empty($cont)){
   switch($act){
    case "main":
         $sql="SELECT name,templru,variables FROM iws_html_templ_design WHERE id=$id";
      break;
    case "vivod":
         $sql="SELECT name,templateru,variables FROM iws_html_templ_vivod WHERE id=$id";
      break;
    case "vvod":
         $sql="SELECT name,template,variables FROM iws_html_templ WHERE id=$id";
      break;
   }
   list($nme,$cont,$variables)=mysql_fetch_row(mysql_query($sql));

   $variables=explode(",",$variables);
   $cnt=count($variables);
   if($cnt>=1){
      for($i=0;$i<=($cnt-1);$i++){
         if($i==0){  $vr=$variables[0]; } else { $vr.=" || lc=".$variables[$i]; }
      }
   } else {
      $vr=-1;
   }

}
      $content.="<script><!--
         var trt = 0;
         var prt;

      function submitr()
      {
         if(window.BODYhtml.FormHTML.elm1.value){
            if(trt) renwin();
            frm.cont.value=window.BODYhtml.FormHTML.elm1.value;
            frm.submit();
         } else {
            alert (\"Не введена информация содержания шаблона!   \");
         }
      }

      function renwin()
      {
         if(!trt){
            prt = parent.document.all[\"frms\"].cols; parent.document.all[\"mainfrms\"].rows=\"1,*\"; parent.document.all[\"frms\"].cols = \"1,*\"; trt = 1;
         } else {
            if(prt){ parent.document.all[\"frms\"].cols = prt; } else { parent.document.all[\"frms\"].cols = \"220,*\"; }
            parent.document.all[\"mainfrms\"].rows = \"45,*\";
            trt = 0;
         }
      }
      //--></script>
      <form method=\"post\" name=frm>
      <input type=hidden name=gopr value=htmtampl>
      <input type=hidden name=act value=$act>
      <input type=hidden name=ret value=edtOk>
      <input type=hidden name=cont value=\"\">
      <input type=hidden name=id value=$id>
      <table bgcolor=#ffffff height=100% width=100% border=0 cellpadding=0 cellspacing=0>
      <tr><td width=100% class=usr>&nbsp;$nme (шаблон) $ct </td><td class=usr><img onclick=\"renwin()\" src=\"images/zoom.gif\" border=0 alt=\"Развернуть/уменьшить окно\" style=\"cursor:hand\"></td>
      <td class=usr><img onclick=\"javascript:if(trt){ renwin(); } document.location='?gopr=htmtampl'\" src=\"images/close.gif\" border=0 alt=\"Закрыть шаблон\" style=\"cursor:hand\"></td></tr>
      <tr valign=top><td height=100% colspan=3>";

      $content.=ret_html($vr);

      $content.="</td></tr></form></table>";

      if($cont){
         $content.="
            <SCRIPT LANGUAGE=JavaScript FOR=\"window\" EVENT=onload><!--
            BODYhtml.FormHTML.elm1.value=\"".ereg_replace("\"","'",ereg_replace("(\r|\n)","",stripslashes($cont)))."\";
            //--></script>\n";
      }
return $content;
}


//-------------------------------------------------------------------------

function ret_html($vrt)
{
   return "<iframe name=\"BODYhtml\" width=\"100%\" height=\"100%\" frameborder=0 marginwidth=0 marginheight=0 src=\"fnciws/html/html_edit.php?tml=0&vrtp=$vrt\" scrolling=no></iframe>";
}

//-------------------------------------------------------------------------

function maintm()
{

$ret="<script>
<!--
   function fnOk(urli)
   {
      if(confirm(\"Вы действительно хотите удалить выбранный шаблон?     \")) document.location=urli;
   }
//--></script>

<table cellpadding=1 cellspacing=0 border=0 width=100%>
<tr><td class=usr colspan=2>&nbspHTML-шаблоны</td></tr><tr valign=top width=50%><td><br><br>
<table cellpadding=2 cellspacing=1 border=0 width=90%>
<tr><td class=usr>Шаблоны вывода информации</td><td align=center class=usr width=130>Действие</td></tr>";

list($did,$nme)=mysql_fetch_row(mysql_query("SELECT id, name FROM iws_html_templ_design"));

$ret.="<tr bgcolor=#f1f1f1><td><font color=#ff0000>$nme</font></td><td align=center><a href=\"#\" onclick=\"document.location='?gopr=htmtampl&act=main&id=$did'\">редактировать</a></td></tr>";

   $result=mysql_query("SELECT id,name FROM iws_html_templ_vivod ORDER BY name");
   while($arr=mysql_fetch_row($result)){
      $ret.="<tr bgcolor=#f1f1f1><td>".$arr[1]."</td><td align=center><a href=\"#\" onclick=\"document.location='?gopr=htmtampl&act=vivod&id=".$arr[0]."'\">редактировать</a></td></tr>\n";
   }

   $result=mysql_query("SELECT id, name FROM iws_html_templ WHERE inTemplate=1 ORDER BY name");
   while($arr=mysql_fetch_row($result)){
      $ret.="<tr bgcolor=#d1d1d1><td>".$arr[1]."</td><td align=center><nobr><a href=\"#\" onclick=\"document.location='?gopr=htmtampl&act=vvod&id=".$arr[0]."&inTemplate=1'\">редактировать</a>&nbsp;/&nbsp;
            <a href=\"#\" onclick=\"fnOk('?gopr=htmtampl&act=vvod&ret=delOk&id=".$arr[0]."')\">удалить</a></nobr></td></tr>\n";
   }  

$ret.="<tr><td></td><td><a href=\"#\" onclick=\"document.location='?gopr=htmtampl&act=vvod&ret=addTMinTemplate'\">Добавить шаблон блока меню</a></td></tr>
</table>
</td>
<td><br><br>
   <table cellpadding=2 cellspacing=1 border=0 width=90%>
   <tr><td class=usr>Шаблоны ввода информации</td><td align=center class=usr width=130>Действие</td></tr>";

   $result=mysql_query("SELECT id,name FROM iws_html_templ WHERE inTemplate=0 ORDER BY name");
   while($arr=mysql_fetch_row($result)){
      $ret.="<tr bgcolor=#f1f1f1><td>".$arr[1]."</td><td align=center><nobr><a href=\"#\" onclick=\"document.location='?gopr=htmtampl&act=vvod&id=".$arr[0]."&inTemplate=0'\">редактировать</a>&nbsp;/&nbsp;
            <a href=\"#\" onclick=\"fnOk('?gopr=htmtampl&act=vvod&ret=delOk&id=".$arr[0]."')\">удалить</a></nobr></td></tr>\n";
   }  

$ret.="<tr><td></td><td><a href=\"#\" onclick=\"document.location='?gopr=htmtampl&act=vvod&ret=addTM'\">Добавить шаблон</a></td></tr></table></td></tr></table>";
return $ret;
}


?>