<?php

include('../../exit.php');

if($act=="edtOk"){
   $cont=paramOk();  
} else {
   $cont=admin_param();    
}


function admin_param(){
global $err,$mainadvar,$p;

if(isset($p) && $p){
   $p ="Параметры сайта успешно сохранены";
} else {
   $p ="Параметры сайта";
}
if($err==1){
      $ret="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
      ."Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
}
         $res=mysql_query(" select  title".$mainadvar['lng'].",  descr".$mainadvar['lng'].",  keyword".$mainadvar['lng'].", antiddos".$mainadvar['lng']."  from iws_pref ");
         $arr=mysql_fetch_row($res);

         $ret.="<table cellpadding=1 cellspacing=0 border=0 width=100%><tr><td class=usr>&nbsp;".$p."</td></tr></table>\n<br>"
            ."<form method=\"post\" name=frm>"
            ."<input type=hidden name=gopr value=param>"
            ."<input type=hidden name=act value=edtOk>"
            ."<table border=0 cellpadding=10 cellspacing=1 align=center>"
            ."<tr><td align=right>Название сайта (<font color=#ff0000>title</font>)</td><td><input name=tit value=\"".$arr[0]."\" maxlength=250 size=91></td></tr>"
            ."<tr valign=top><td align=right>Описание сайта (<font color=#ff0000>description</font>)</td><td><textarea name=desc rows=10 cols=65>".$arr[1]."</textarea></td></tr>"
            ."<tr valign=top><td align=right>Ключевые слова сайта (<font color=#ff0000>keywords</font>)<br><font color=#909090 size=1>ключевые слова записывайте через запятую</font></td><td><textarea name=keyw rows=10 cols=65>".$arr[2]."</textarea></td></tr>"
         ."<tr valign=top><td align=right>Защита от ddos атак</td><td><input name=antiddos type='checkbox' ";
         if($arr[3]==1){$ret.="checked";}
         $ret.="></td></tr>"
            ."<tr valign=top><td></td><td><br><input class=but type=button name=btn value=\"Сохранить изменения\" onClick=\"frm.submit();\"></td></tr>"
            ."</form></table>";
         
return $ret;

}
function paramOk(){
global $tit,$desc,$keyw,$mainadvar,$antiddos;
if($antiddos){$antiddos=1;}else{$antiddos=0;}
   if(!mysql_query("update iws_pref set title".$mainadvar['lng']."='".addslashes($tit)."',descr".$mainadvar['lng']."='".addslashes($desc)."',keyword".$mainadvar['lng']."='".addslashes($keyw)."',antiddos".$mainadvar['lng']."='".$antiddos."'")){
         header("location: ?gopr=param&err=1");
         return;
      } else {    
         header("location: ?gopr=param&p=1");
         return;
      }

}

?>