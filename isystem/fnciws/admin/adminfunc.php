<?php

include('fnciws/admin/admc.inc.php');

if($act=="addOk" || $act=="edtOk" || $act=="sedtOk" || $act=="delOk" || $act=="delG" || $act=="addG"){
   $cont=fOk();   
} else {
   if(!$act || $act=="edt"){
      if(!$mainadvar['sadm']){
         $id=$mainadvar['id'];
         $cont=admin_pref();           
      } else {
         $cont=admin_view();
      }        
   } elseif($act=="del") {
      $cont=admin_view();        
   } else {
      $cont=admin_pref();
   }
}

function admin_pref() {
global $act,$mainadvar,$lgn,$id,$err,$gfld,$grp;

switch($act){
   case "add":
      $masc="Добавление редактора";
      break;
   case "edt":
      $masc="Редактирование редактора";
      break;
   case "edtv":
      $masc="Редактирование редактора";
      break;
   case "sedt":
      $masc="Редактирование администратора";
      break;
}
switch($err){
   case 1:
      $massc.="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
      ."Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
      break;
   case 2:
      $massc.="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
      ."Извините редактор с таким логином <b>$lgn</b> уже есть. Введите другой логин и пробуйте еще раз.</td></tr></table><br>";
      break;
   case 3:
      $massc.="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
      ."Не введена вся информация или пароль менее 6-ти символов или же присутсвуют недопустимые символы. Попробуйте еще раз.</td></tr></table><br>";
      break;
}

   if($act=="add" || $act=="sedt"){
      $massc.="<script><!--
      function submitr(fr){
      if (fr.lgn.value && fr.elements['passd[0]'].value && fr.elements['passd[1]'].value) { 
            fr.submit();
      } else {
         alert (\"Не введена вся информация!   \");
         fr.lgn.focus();
      }
      }
      //--></script>"
      ."<form method=\"post\" name=frm>";
      $mainadvar['pwdshifr']=genert();
      if($act=="sedt"){
         $massc.="<input type=hidden name=act value=sedtOk>";
         list($lgn)=mysql_fetch_row(mysql_query("select aid from iws_authadmn where sdm=1"));
      } else {
         $massc.="<input type=hidden name=act value=addOk>";
      }
      $massc.="<input type=hidden name=grp value=".$grp."><input type=hidden name=gopr value=admin>"
      ."<table width=100% border=0 cellpadding=3 cellspacing=0>"
      ."<tr><td align=center class=usr>".$masc."</td></tr></table>"
      ."<table bgcolor=#ffffff width=100% border=0 cellpadding=3 cellspacing=2 align=center>"
      ."<tr><td align=right width=40%>Логин: </td><td><input name=lgn size=25 maxlength=30";
      if($act=="sedt"){ $massc.=" value=$lgn"; }
      $massc.="> (Макс. 30 символов)</td></tr>"
      ."<tr><td align=right>Пароль: </td><td><input type=password name=passd[0] size=25 maxlength=30> (не менее 6 символов)</td></td></tr>"
      ."<tr><td align=right>Подтвердите пароль: </td><td><input type=password name=passd[1] size=25 maxlength=30></td></td></tr>";
      $massc.="<tr><td colspan=2><hr></td></tr><tr><td></td><td><input class=but type=button name=btn value=";
      if($act=="sedt"){ $massc.="сохранить"; } else { $massc.="регистрация"; }
      $massc.=" onClick=\"submitr(frm)\">&nbsp;&nbsp<input type=button value=\"отмена\" onclick=\"javascript:document.location='mainiwspref.php?gopr=admin&grp=$grp'\"></td></tr></form></table>";
   }  

   if($act=="edt" || $act=="edtv"){
      $massc.="<script><!--
      function submitr(fr){
      if (fr.lgn.value && fr.elements['passd[0]'].value && fr.elements['passd[1]'].value) { 
            fr.submit();
      } else {
         alert (\"Не введена вся информация!   \");
         fr.lgn.focus();
      }
      }
      //--></script>"
      ."<form method=\"post\" name=frm>";
      $mainadvar['pwdshifr']=genert();
      list($lgn)=mysql_fetch_row(mysql_query("select aid from iws_authadmn where id=$id"));
      $massc.="<input type=hidden name=gopr value=admin><input type=hidden name=act value=edtOk>"
      ."<input type=hidden name=id value=$id>"        
      ."<input type=hidden name=grp value=$grp>"         
      ."<table width=100% border=0 cellpadding=3 cellspacing=0>"
      ."<tr><td align=center class=usr>".$masc."</td></tr></table>"
      ."<table bgcolor=#ffffff width=100% border=0 cellpadding=3 cellspacing=2 align=center>"
      ."<tr><td align=right width=40%>Логин: </td><td><input name=lgn size=25 maxlength=30 value=$lgn"
      ."> (Макс. 30 символов)</td></tr>"
      ."<tr><td align=right>Пароль: </td><td><input type=password name=passd[0] size=25 maxlength=30> (не менее 6 символов)</td></td></tr>"
      ."<tr><td align=right>Подтвердите пароль: </td><td><input type=password name=passd[1] size=25 maxlength=30></td></td></tr>";
      $massc.="<tr><td colspan=2><hr></td></tr><tr><td></td><td><input class=but type=button name=btn value="
      ."сохранить onClick=\"submitr(frm)\">&nbsp;&nbsp<input type=button value=\"отмена\" onclick=\"javascript:document.location='mainiwspref.php?gopr=admin&grp=$grp'\"></td></tr></form></table>";
   }
unset($pwdr);
return $massc;
}

function admin_view() {
global $lgn,$fieldnm,$gfld,$grp,$err;
if(!isset($grp) || !$grp) $grp = 1;

$massc="<script><!--\n"
."function deOk(urli){ \n"
."if(confirm(\"Вы действительно хотите удалить редактора?     \")){\n"
."document.location=urli;\n"
."}\n"
."}\n";
$massc.="
   function delG(){
      var ms = \"Внимание!\\n\\nПри удалении группы, будут удалены все\\nподченненые учетные записи редакторов.\\n\\nВы действительно хотите удалить группу?           \";
      if(confirm(ms)) document.location='mainiwspref.php?gopr=admin&act=delG&grp=".$grp."';  
    }
   function addG(){
      nme = showModalDialog(\"fnciws/admin/dialog.php?act=addG\",null,\"dialogWidth:330px; dialogHeight:120px; status:no;\");
      if (nme != null)
            document.location='mainiwspref.php?gopr=admin&act=addG&grp=".$grp."&nme='+ nme;
    }
    function dtG(){
      window.open(\"fnciws/admin/dialog.php?act=dtG&grp=".$grp."\",\"grpM\",\"width=350,height=500,status=no,toolbar=no,scrollbars=yes,resizable=yes\");
    } 
   //--></script>";

   if($err==1){
      $massc.="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
      ."Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
   }elseif($err==2){
      $massc.="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
      ."Группа с таким наименование уже есть. Попробуйте еще раз.</td></tr></table><br>";
   }

   $massc.="<table bgcolor=#ffffff width=100% border=0 cellpadding=2 cellspacing=0>"
      ."<tr><td class=usr>&nbspГруппы редакторов</td></tr></table><br><br>"

      ."<table border=0 cellpadding=2 cellspacing=1 align=center width=90%>"
      ."<tr><td><select name=\"grp\" style=\"width:200\" onchange=\"document.location='?gopr=admin&grp='+ this.value\">";

      $result=mysql_query("select id,name from iws_admin_group ORDER BY id");
      $ds = 1;    
      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            $massc.="<option value=".$arr[0];
            if($arr[0]==$grp) $massc.=" selected";
            $massc.=">".$arr[1]."</option>\n";
            if($grp==1) $ds = 0;
         }
      }

   $massc.="</select></td>"
   ."<td bgcolor=#f0f0f0 style=\"border: 1px #bCbCbC solid;\" width=100%><nobr> <a href=\"#\" onclick=\"dtG()\">права доступа</a>&nbsp;/&nbsp;<a href=\"#\" onclick=\"addG()\">добавить группу</a>";
   if($ds) $massc.="&nbsp;/&nbsp;<a href=\"#\" onclick=\"delG()\">удалить группу</a>";
   $massc.="</nobr></td>"
   ."<td bgcolor=#f0f0f0 style=\"border: 1px #bCbCbC solid;\" align=center><a href=\"#\" onclick=\"javascript:document.location='mainiwspref.php?gopr=admin&act=sedt&grp=$grp'\">администратор</a></td></tr>"
   ."<tr><td colspan=2></TD><td nowrap><br><a href=\"#\" onclick=\"javascript:document.location='mainiwspref.php?gopr=admin&act=add&grp=$grp'\">добавить редактора</a></td></tr>"
   ."<tr><td class=usr colspan=2>редактор</td><td align=center class=usr>действие</td><td></td></tr>";

   $res=mysql_query("select id,aid from iws_authadmn where sdm=0 and grp=".$grp);
   if(mysql_numrows($res)>=1){
      while($arr=mysql_fetch_row($res)){
         if($cls=="menu1"){ $cls="menu"; } else { $cls="menu1"; }
         $massc.="<tr><td class=$cls colspan=2 nowrap>".$arr[1]."</td><td class=$cls>"
                  ."<a href=\"?gopr=admin&act=edtv&grp=$grp&id=".$arr[0]."\">редактировать</a>"
                  ."&nbsp;/&nbsp;<a href=\"#\" onclick=\"deOk('mainiwspref.php?gopr=admin&act=delOk&grp=$grp&id=".$arr[0]."'); return false;\">удалить</a>"
                  ."</td></tr>";       
      }
   }

   $massc.="<tr><td colspan=2></TD><td><a href=\"#\" onclick=\"javascript:document.location='mainiwspref.php?gopr=admin&act=add&grp=$grp'\">добавить редактора</a></td></tr></table>";
return $massc;
}

function fOk(){
global $act,$mainadvar,$lgn,$passd,$id,$fieldnm,$grp,$gfld,$nme;
switch ($act){

   case "addG":
      $nme=trim($nme);  
      if(!empty($nme)){
         $nme=substr($nme,0,30);
         $sqlq="select count(id) from iws_admin_group where name='$nme'";
         list($i)=mysql_fetch_row(mysql_query($sqlq));
         if($i){
            header("location: ?gopr=admin&err=2&grp=$grp");
            break;
         }
         if(!mysql_query("insert into iws_admin_group (name) values ('$nme')")){
            header("location: ?gopr=admin&err=1&grp=$grp");
            break;
         }else{
            list($grp)=mysql_fetch_row(mysql_query("select id from iws_admin_group where name='$nme'"));
            header("location: ?gopr=admin&grp=$grp");
            break;
         }
      }
   break;

   case "delG":
      if(!(mysql_query("delete from iws_authadmn where grp=$grp"))){
         header("location: ?gopr=admin&err=1&grp=$grp");
         break;
      }else{
         if(!(mysql_query("delete from iws_admin_group where id=$grp"))){
            header("location: ?gopr=admin&err=1&grp=$grp");
            break;
         } else {
            header("location: ?gopr=admin");
            break;
         }
      }
   break;

   case "delOk":
      if(!(mysql_query("delete from iws_authadmn where id=$id"))){
         header("location: ?gopr=admin&err=1&grp=$grp");
         break;
      }else{
         header("location: ?gopr=admin&grp=$grp");
         break;
      }
   break;
   
   case "addOk":
      $passd[0]=trim($passd[0]);
      $passd[1]=trim($passd[1]);
      $lgn=trim($lgn);
      if(empty($lgn) || preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $lgn) || empty($passd[0]) || empty($passd[1]) || strlen($passd[0])<6 || $passd[0]!=$passd[1]){
         header("location: ?gopr=admin&act=add&err=3&grp=$grp");
         break;
      }
      $sqlq="select count(id) from iws_authadmn where aid='$lgn'";
      list($i)=mysql_fetch_row(mysql_query($sqlq));

      if($i>=1){
         header("location: ?gopr=admin&act=add&err=2&grp=$grp");
         break;
      }

      $pwdr=crypt($passd[0],$mainadvar['pwdshifr']);
      $mainadvar['pwdshifr']="";
      $lgn=substr($lgn,0,30);
      $sqlq="insert into iws_authadmn (aid,pwd,grp) values ('$lgn','$pwdr',$grp)";

      if(!(mysql_query($sqlq))){
         header("location: ?gopr=admin&act=add&lgn=$lgn&err=1&grp=$grp");
         break;
      } else {
         header("location: ?gopr=admin&grp=$grp");
         break;
      }
   break;

   case "sedtOk":
      $passd[0]=trim($passd[0]);
      $passd[1]=trim($passd[1]);
      $lgn=trim($lgn);
      if(empty($lgn) || preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $lgn) || empty($passd[0]) || empty($passd[1]) || strlen($passd[0])<6 || $passd[0]!=$passd[1]){
         header("location: ?gopr=admin&act=sedt&err=3&grp=$grp");
         break;
      }
      $sqlq="select count(id) from iws_authadmn where aid='$lgn' and sdm<>1";
      list($i)=mysql_fetch_row(mysql_query($sqlq));

      if($i>=1){
         header("location: ?gopr=admin&act=sedt&err=2&grp=$grp");
         break;
      }

      $pwdr=crypt($passd[0],$mainadvar['pwdshifr']);
      $mainadvar['pwdshifr']="";
      $lgn=substr($lgn,0,30);
      $sqlq="update iws_authadmn set aid='$lgn',pwd='$pwdr' where sdm=1";

      if(!(mysql_query($sqlq))){
         header("location: ?gopr=admin&act=sedt&lgn=$lgn&err=1&grp=$grp");
         break;
      } else {
         header("location: ?gopr=admin&grp=$grp");
         break;
      }
   break;
   case "edtOk":
      $passd[0]=trim($passd[0]);
      $passd[1]=trim($passd[1]);
      $lgn=trim($lgn);
      if(empty($lgn) || preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $lgn) || empty($passd[0]) || empty($passd[1]) || strlen($passd[0])<6 || $passd[0]!=$passd[1]){
         header("location: ?gopr=admin&act=edt&err=3&grp=$grp");
         break;
      }
      $sqlq="select count(id) from iws_authadmn where aid='$lgn' and id<>$id";
      list($i)=mysql_fetch_row(mysql_query($sqlq));

      if($i>=1){
         header("location: ?gopr=admin&act=edt&err=2&grp=$grp");
         break;
      }

      $pwdr=crypt($passd[0],$mainadvar['pwdshifr']);
      $mainadvar['pwdshifr']="";
      $lgn=substr($lgn,0,30);
      $sqlq="update iws_authadmn set aid='$lgn',pwd='$pwdr' where id=$id";

      if(!(mysql_query($sqlq))){
         header("location: ?gopr=admin&act=edt&lgn=$lgn&err=1&grp=$grp");
         break;
      } else {
         header("location: ?gopr=admin&grp=$grp");
         break;
      }
   break;
}
}

function genert(){
   mt_srand((double)microtime()*1000000);
   $rnd=mt_rand(97,122);
   $sl=chr($rnd);
   mt_srand((double)microtime()*1000000);
   $rnd=mt_rand(97,122);
   $sl.=chr($rnd);
   return $sl;
}
unset($fieldnm,$passd,$pwdr,$sl,$dst);
?>
