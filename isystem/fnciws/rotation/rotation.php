<?php

if($act=="edtOk"){
   $cont=rotationOk();  
}elseif($act=="addOk"){
   $cont=rotationAddOk();
}elseif($act=="delOk"){
   $cont=rotationdelOk();
} else {
   $cont=admin_rotation();    
}


function admin_rotation(){
global $act,$err,$id,$hostName,$docRoot;

if($err==1){
      $ret="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
      ."Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
}

      $ret.="<script><!--
            function shMenu()
            {
               window.open(\"/isystem/fnciws/rotation/dialog.php\",\"winMenu\", \"width=550,height=500,status=no,left=\"+Math.floor((screen.width - 550)/2)+\",top=\"+Math.floor((screen.height - 500)/2));
            }

function InsertLinkM(url)
{
   if(url) frm.imageHREF.value = url;
}
            //--></script>";

switch($act){
   case "edtv":
      list($url,$main,$href,$alt)=mysql_fetch_row(mysql_query("select url,main,href,alt from iws_rotation where id=$id"));
      $ret.="<table width=100% border=0 cellpadding=3 cellspacing=0>"
      ."<tr><td align=center class=usr>Редактирование</td></tr></table>"
      ."<form method=\"post\" enctype=\"multipart/form-data\" name=frm>"
      ."<input type=hidden name=go value=rotation>"
      ."<input type=hidden name=act value=edtOk>"
      ."<input type=hidden name=preurl value=\"".$docRoot."/".$url."\">"
      ."<input type=hidden name=id value=$id>"     
      ."<table bgcolor=#ffffff width=100% border=0 cellpadding=1 cellspacing=2 align=center>"
      ."<tr><td align=right valign=top>Текущее изображение </td><td><img src=\"".$hostName."/".$url."\" border=0></td></tr>"
      ."<tr><td align=right>Всегда отображать на главной странице </td><td><input type=checkbox class=chk name=main";
      if($main) $ret.=" checked";
      $ret.="></td></tr>"
      ."<tr><td align=right>Выберите изображение</td><td><input type=file name=imagefile size=40></td></tr>"
      ."<tr><td align=right>Подпись (<i>alt</i>)</td><td><input type=text name=imageALT size=40 maxlength=255 value=\"".$alt."\"></td></tr>"
      ."<tr><td align=right>Адрес ссылки (<i>href</i>)</td><td><input type=text name=imageHREF size=40 maxlength=255 value=\"".$href."\">
       <img align=absMiddle onclick=\"shMenu();\" style=\"cursor:hand;\" height=22 width=23 src=\"/isystem/fnciws/html/images/linkmn.gif\" alt=\"Гиперссылка из меню\"></td></tr>"
      ."<tr valign=top><td></td><td><br><br><input class=but type=button name=btn value=\"Сохранить изменения\" onClick=\"frm.submit();\">"
      ."&nbsp;&nbsp<input class=but type=button value=\"Отмена\" onclick=\"javascript:document.location='mainiws.php?go=rotation'\"></td></tr>"
      ."</form></table>";
      break;

   case "add":
      $ret.="<table width=100% border=0 cellpadding=3 cellspacing=0>"
      ."<tr><td align=center class=usr>Добавить изображение</td></tr></table>"
      ."<form method=\"post\" enctype=\"multipart/form-data\" name=frm>"
      ."<input type=hidden name=go value=rotation>"
      ."<input type=hidden name=act value=addOk>"
      ."<table bgcolor=#ffffff width=100% border=0 cellpadding=1 cellspacing=2 align=center>"
      ."<tr><td align=right>Всегда отображать на главной странице </td><td><input type=checkbox class=chk name=main></td></tr>"
      ."<tr><td align=right>Выберите изображение</td><td><input type=file name=imagefile size=40></td></tr>"
      ."<tr><td align=right>Подпись (<i>alt</i>)</td><td><input type=text name=imageALT size=40 maxlength=255></td></tr>"
      ."<tr><td align=right>Адрес ссылки (<i>href</i>)</td><td><input type=text name=imageHREF size=40 maxlength=255>
       <img align=absMiddle onclick=\"shMenu();\" style=\"cursor:hand;\" height=22 width=23 src=\"/isystem/fnciws/html/images/linkmn.gif\" alt=\"Гиперссылка из меню\"></td></tr>"
      ."<tr valign=top><td></td><td><br><br><input class=but type=button name=btn value=\"Добавить изображение\" onClick=\"frm.submit();\">"
      ."&nbsp;&nbsp<input class=but type=button value=\"Отмена\" onclick=\"javascript:document.location='mainiws.php?go=rotation'\"></td></tr>"
      ."</form></table>";
      break;

   default:
         $ret.="<table cellpadding=1 cellspacing=0 border=0 width=100%><tr><td class=usr>&nbspРотация изображений</td></tr></table>\n<br><br>"
            ."<table border=0 cellpadding=1 cellspacing=1 align=center>"
            ."<tr><td colspan=6><a href=\"?go=rotation&act=add\">Добавить изображение</a></td></tr>"
            ."<tr align=center><td class=usr></td><td class=usr>На главной</td><td class=usr>Изображение</td><td class=usr>Ссылка</td><td class=usr>Подпись</td><td class=usr></td></tr>";

         $res=mysql_query("select id,url,main,href,alt from iws_rotation ORDER BY main DESC");
         if(mysql_numrows($res)>=1){
            $cls="menu1";
            $i=1;
            while($arr=mysql_fetch_row($res)){
               if($cls=="menu1"){ $cls="menu"; } else { $cls="menu1"; }
               $ret.="<tr valign=top><td class=$cls align=right>".$i.".</td><td align=center class=$cls>";
               if($arr[2]) $ret.="<b>Да</b>";
               $ret.="</td><td class=$cls><img src=\"".$hostName."/".$arr[1]."\" border=0></td><td class=$cls>".$arr[3]."</td><td class=$cls>".$arr[4]."</td><td class=$cls>[<a href=\"?go=rotation&act=edtv&id=".$arr[0]."\">редактировать</a>]<br>[<a href=\"?go=rotation&act=delOk&id=".$arr[0]."\"><font style=\"color:#ff0000;\">удалить</font></a>]</td></tr>";
               $i++;
            }
         } else {
            $ret.="<tr><td colspan=6 align=center>Извините изображения для случайной ротации в базе данных нет!</td></tr>";
         } 
         $ret.="<tr><td colspan=6><a href=\"?go=rotation&act=add\">Добавить изображение</a></td></tr></table>";
      break;
}

return $ret;
}

function rotationOk(){
global $id,$preurl,$main,$hostName,$docRoot,$imageALT,$imageHREF;

$mainTrue=0;
if(isset($main) && $main){
   unsetMain();
   $mainTrue=1;
}
   if (is_uploaded_file($_FILES['imagefile']['tmp_name'])) { 
      if($preurl && is_file($preurl)){
         unlink($preurl);
         $retResult=img_copy($_FILES['imagefile']['tmp_name'],$_FILES['imagefile']['name'],$docRoot);
         if($retResult){
            $imageALT=trim($imageALT);
            $imageHREF=trim($imageHREF);
            if(!mysql_query("update iws_rotation set url='design/rotation/".$retResult."',main=".$mainTrue.",href='".$imageHREF."',alt='".$imageALT."' where id=".$id)){
               header("location: ?go=rotation&act=edtv&err=1&id=$id");
               return;
            } else {    
               header("location: ?go=rotation");
               return;
            }
         } else {
            header("location: ?go=rotation&act=edtv&err=1&id=$id");
            return;
         }
      } else {
         header("location: ?go=rotation&act=edtv&err=1&id=$id");
         return;
      }
   } else {
      $imageALT=trim($imageALT);
      $imageHREF=trim($imageHREF);
      if(!mysql_query("update iws_rotation set main=".$mainTrue.",href='".$imageHREF."',alt='".$imageALT."' where id=".$id)){
         header("location: ?go=rotation&act=edtv&err=1&id=$id");
         return;
      } else {    
         header("location: ?go=rotation");
         return;
      }
   }
}

function rotationAddOk(){
global $main,$hostName,$docRoot,$imageALT,$imageHREF;
$mainTrue=0;
   $retResult=img_copy($_FILES['imagefile']['tmp_name'],$_FILES['imagefile']['name'],$docRoot);
            if($retResult){
               if(isset($main) && $main){
                  unsetMain();
                  $mainTrue=1;
               }
               $imageALT=trim($imageALT);
               $imageHREF=trim($imageHREF);
               if(!mysql_query("insert into iws_rotation (url,main,href,alt) values ('design/rotation/".$retResult."',".$mainTrue.",'".$imageHREF."','".$imageALT."')")){
                  header("location: ?go=rotation&act=add&err=1");
                  return;
               } else {    
                  header("location: ?go=rotation");
                  return;
               }
            } else {
               header("location: ?go=rotation&act=add&err=1");
               return;
            }
}

function rotationdelOk(){
global $id,$hostName,$docRoot;
   list($tounlink)=mysql_fetch_row(mysql_query("select url from iws_rotation where id=".$id));
   $tounlink=$docRoot."/".$tounlink;
   if($tounlink && is_file($tounlink)){
      unlink($tounlink);
      if(mysql_query("delete from iws_rotation where id=".$id)){
         header("location: ?go=rotation");
         return;
      } else {
         header("location: ?go=rotation&err=1");
         return;
      }
   }else{
      header("location: ?go=rotation&err=1");
      return;
   }
}

function img_copy($tmpfname,$realname,$droot){
   if (is_uploaded_file($tmpfname)) { 
      $size = getimagesize($tmpfname);
      switch ($size[2]){
         case 1:
            $rs="gif";
            $rst="gif";
         break;
         case 2:
            $rs="jpg";
            $rst="jpeg";
         break;
         case 3:
            $rs="png";
            $rst="gif";
         break;
      }
      $realname=explode(".",$realname);
      $imgfile = $realname[0].".".$rs;
      $filepath=$droot."/design/rotation/".$imgfile; 

      if(copy($tmpfname, $filepath)){
         return $imgfile;
      } else {
         return false;
      }
   } else { 
      return false;
   }
}

function unsetMain(){
   mysql_query("update iws_rotation set main=0");
}
?>
