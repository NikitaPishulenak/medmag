<?php

unset($mainadvar);
session_start();
session_register("mainadvar");

include('inc/main.inc.php');

if(isset($_POST['lgn']) && isset($_POST['pwd'])){

   $_POST['lgn']=trim($_POST['lgn']);
   $_POST['pwd']=trim($_POST['pwd']);

   if(!empty($_POST['lgn']) && !empty($_POST['pwd'])){
      admin_login($_POST['lgn'], $_POST['pwd']);
   } else {
      admin_login_error($_POST['lgn']);
   }
} else {
   logn();
}

function admin_login($loginUs, $passUs) {
global $mainadvar;

include('inc/config.inc.php');
include('fnciws/admin/admc.inc.php');

   if(empty($loginUs) or empty($passUs)) {                                                                // пустые поля
      admin_login_error($loginUs);
      return;
   }

   $loginUs = substr($loginUs, 0, 30);
   $passUs = substr($passUs, 0, 25);
   if(preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $loginUs)) {                    // логин не вписывается в формат
      admin_login_error($loginUs);
      return;
   }

   $dbresult = mysql_query("SELECT ".$fieldnm['did'].",".$fieldnm['password'].",".$fieldnm['superadm'].",".$fieldnm['group']." FROM ".$atbl." WHERE ".$fieldnm['login']."='".$loginUs."'");
   if(!$dbresult) die("".mysql_errno(). ": ".mysql_error(). "<br>");
   if(mysql_numrows($dbresult) != 1) {                                                             // логин не найден
      admin_login_error($loginUs);
      mysql_free_result($dbresult);
      return;
   }
   list($did,$pass,$superadm,$grp) = mysql_fetch_row($dbresult);                                                // получли шифрованный пароль из запроса
   mysql_free_result($dbresult);

   if($pass != crypt($passUs,$pass)) {                                        // собсна проверка
     admin_login_error($loginUs);
     return;
   }

        // логин OK
   if(!$superadm){
      $result=mysql_query("select ".$gfld['cnt'].",".$gfld['prf'].",".$gfld['msg'].",".$gfld['did']." from $gtbl where ".$gfld['did']."=".$grp);
      if(mysql_numrows($result)>=1){
         list($mainadvar['cnt'],$mainadvar['prf'],$mainadvar['msg'],$mainadvar['grop'])=mysql_fetch_row($result);
      }else{
        admin_login_error($loginUs);
        return;
      }
   }
         $mainadvar['ath']="avtores";
         $mainadvar['lgn']=$loginUs;
         $mainadvar['sadm']=$superadm;
         $mainadvar['id']=$did;
         $mainadvar['basedir']=$docRoot;
         $mainadvar['fmnbasedir']=$docRoot;
         $mainadvar['urlp']="";
         $mainadvar['lng']="ru";
      unset($did,$pass,$superadm);
      header("Location: index.php");
} // admin_login()

function admin_login_error($logUs) {
   global $mainadvar;
   $mainadvar['ath']="unavtores";
   header("Location: login.php?lgn=".substr(trim($logUs),0,30)."&err=1");     
} // admin_login_error()

function logn(){
global $err,$lgn;

?>
<HTML>
<HEAD>
<TITLE>iwSite - Управление сайтом</TITLE>
<link rel="stylesheet" type="text/css" href="style.css">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<script>
<!--
function sbmt(){
if(!flgn.lgn.value || !flgn.pwd.value) {
      alert("Не введены все данные!      ");
} else {
        document.flgn.submit();
}
}
//-->
</script>
</HEAD>
<BODY leftmargin=0 topmargin=0>
<TABLE height=100% BORDER=0 CELLPADDING=0 CELLSPACING=0 align=center WIDTH=350>
        <tr>
                <TD HEIGHT=100%><p><img src="images/logo_adm.gif" border=0 alt="iwSite"></p>
<?php
if($err){ echo "<b><font size=1 color=#ffffff style=\"font-family:Verdana,Arial;background-color:#D20303;font-weight:bold;\">Ошибка</font></b> при авторизации! Попробуйте еще раз<br>"; }
?>
                        <form method="post" name="flgn" action="login.php" onsubmit="sbmt(); return false;">
                        <TABLE  width=222 height=190 BORDER=0 CELLPADDING=0 CELLSPACING=0 background="images/login.gif" align=center>
                        <tr><td height=50% colspan=2></td></tr>
                        <tr><td align=right class="bld">Логин:&nbsp</td><td><input type=text name="lgn" size="20" maxlength="30"
<?php
if(isset($lgn) && !empty($lgn)) { echo " value=\"$lgn\""; }
?>
></td></tr>                     
                        <tr><td align=right class="bld">Пароль:&nbsp</td><td><input type=password name="pwd" size="20" maxlength="25"></td></tr>                 
                        <tr><td></td><td><input type=submit value="Войти" class="but"></td></tr>                       
                        <tr><td height=50% colspan=2></td></tr>
                        </TD></TR></TABLE></form>
                </TD>
        </TR>
</TABLE>
</BODY>
</HTML>
<?php
}

unset($lgn,$pwd);
?>