<?php

function hdr(){
global $mvr,$mainadvar;

?>

<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<style>
.ovr{BACKGROUND-COLOR: #E2E2E2; BORDER-LEFT: #CCCCCC solid 1px; BORDER-RIGHT: #7C7C7C solid 1px; BORDER-TOP: #CCCCCC solid 1px; cursor:hand; font-family:Verdana, Arial; font-size:8pt;}
.dwn{BACKGROUND-COLOR: #AFAEAE;BORDER-LEFT: #7C7C7C solid 1px;BORDER-RIGHT: #CCCCCC solid 1px;BORDER-TOP: #7C7C7C solid 1px; font-family:Verdana, Arial; font-size:8pt; color:#FFFFFF;}
.tb{BACKGROUND-COLOR: #E2E2E2; BORDER-LEFT: #AEAEAE solid 1px; BORDER-RIGHT: #AEAEAE solid 1px; BORDER-TOP: #AEAEAE solid 1px; font-family:Verdana, Arial; font-size:8pt; color:#000000}
.vrs{ font-family:Verdana, Arial; font-weight:bold; font-size:8pt; color:#000000}
select { border: 1px #ffffff solid; font-size:7pt; width:150px}
</style>
</head>
<BODY leftmargin=0 topmargin=0>
<TABLE WIDTH=100% height=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
        <TR>
                <TD width=200 align=center><img src="images/logo_adm.gif" border=0 alt="iwSite"></TD>
                <TD>
<TABLE WIDTH=100% height=100% BORDER=0 CELLPADDING=0 CELLSPACING=1>
<tr><td colspan=9></td></tr>
<tr height=30>
<?php
switch($mvr){
case "content":
   $atr1="class=\"dwn\"";
   $atr2="class=\"tb\" onclick=\"javascript:window.top.location='index.php?mvr=pref'; return false;\" onmouseover=\"this.className='ovr';\"  onmouseout=\"this.className='tb';\" onmousedown=\"this.className='dwn';\"";
   break;
case "pref":
   $atr1="class=\"tb\" onclick=\"javascript:window.top.location='index.php?mvr=content'; return false;\" onmouseover=\"this.className='ovr';\"  onmouseout=\"this.className='tb';\" onmousedown=\"this.className='dwn';\"";
   $atr2="class=\"dwn\"";
   break;
default:
   $atr1="class=\"dwn\"";
   $atr2="class=\"tb\" onclick=\"javascript:window.top.location='index.php?mvr=pref'; return false;\" onmouseover=\"this.className='ovr';\"  onmouseout=\"this.className='tb';\" onmousedown=\"this.className='dwn';\"";
   break;
}
?>
<td width=19></td>
<td width=150 align=center <?php echo $atr1; ?> nowrap><img src="images/icon_content.gif" align=center>&nbsp;управление&nbsp;</td>
<td width=150 align=center <?php echo $atr2; ?> nowrap><img src="images/icon_strc.gif" align=center>&nbsp;сервис&nbsp;</td>
<td width=150 align=center class="tb" onclick="javascript:parent.C.location='mainiws.php?go=quit'; return false;" onmouseover="this.className='ovr';"  onmouseout="this.className='tb';" onmousedown="this.className='dwn';"><img src="images/icon_quit.gif" align=center>&nbsp;выход&nbsp;</td>
<td></td></tr>
</table>



               </TD></tr>
<tr><TD colspan=2 height=1 BGCOLOR=#8B8B8B></TD></TR>
</TABLE>
</BODY>
</HTML>

<?php
}

function frmIWS($content){
?>
<html>
<HEAD>
<TITLE>iwSite - Управление сайтом</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link rel="stylesheet" type="text/css" href="style.css">
</HEAD>
<BODY leftmargin=0 topmargin=0>
<TABLE WIDTH=100% height=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<tr valign=top><TD HEIGHT=100% WIDTH=1 BGCOLOR=#C0C0C0 style="cursor:col-resize"></TD>
<TD>
<TABLE WIDTH=100% height=100% BORDER=0 CELLPADDING=4 CELLSPACING=0 bgcolor=#ffffff><tr valign=top><td>
<?php echo $content; ?>
</TD></tr></table>
</TD></TR>
</TABLE>
</BODY>
</HTML>
<?php
}

function menu($icn,$typ){
?>
<HTML>
<HEAD>
<TITLE>iwSite - Управление сайтом</TITLE>
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link rel="stylesheet" type="text/css" href="style.css">
<?php
if($typ=="content"){
?>
<script><!--
var tr = 0;
var pr;
function refwin(){
if(!tr){
   pr = parent.document.all["frms"].cols;
   parent.document.all["frms"].cols = "12,*";
   menufnc.style.display="none";
   tr = 1;
} else {
   menufnc.style.display="block";
   if(pr){
      parent.document.all["frms"].cols = pr;
   } else {
      parent.document.all["frms"].cols = "220,*";
   }
   tr = 0;
}
}
//--></script>
<?php
}
?>
</HEAD>
<BODY leftmargin=0 topmargin=0 BGCOLOR=#E9E9E9>

<style>
.tbovr{BORDER-BOTTOM: #A0A0A0 solid 1px; BORDER-LEFT: #FFFFFF solid 1px; BORDER-RIGHT: #A0A0A0 solid 1px; BORDER-TOP: #FFFFFF solid 1px;}
.tbdwn{BORDER-BOTTOM: #FFFFFF solid 1px;BORDER-LEFT: #A0A0A0 solid 1px;BORDER-RIGHT: #FFFFFF solid 1px;BORDER-TOP: #A0A0A0 solid 1px;}
.tb{BORDER: #E9E9E9 solid 1px;}
.spr{BORDER-LEFT: #B0B0B0 solid 1px;FONT-SIZE: 0px;TOP: 0px;HEIGHT: 23px;WIDTH: 0px;}
</style>

<table width=100% border=0 cellpadding=0 cellspacing=0 height=100%>
<tr>
<?php
if($typ=="content"){
?>
   <td width=100%><span id=menufnc>
   <table width=100% border=0 cellpadding=1 cellspacing=1 height=100%>
      <?php   echo $icn; ?>
   </table></span>
   </td>
   <TD HEIGHT=100% WIDTH=11>
      <img src="images/btnw.gif" alt="увеличить/уменьшить левое окно" style="cursor:hand" onclick="refwin()">
   </TD>
<?php
} else {
?>
   <td width=100% valign=top><br>
   <table border=0 cellpadding=2 cellspacing=2 bgcolor=#ffffff width=95% style="BORDER-BOTTOM:#C0C0C0 solid 1px;BORDER-RIGHT: #C0C0C0 solid 1px;BORDER-TOP: #C0C0C0 solid 1px;">
      <?php   echo $icn; ?>
   </table>
   </td>
<?php
}
?>
</tr></table>
</BODY>
</HTML>
<?php
}

function nmsg($nmms){
?>
<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>
<BODY leftmargin=0 topmargin=0 BGCOLOR=#E9E9E9>
<TABLE WIDTH=100% height=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR height=100%><TD align=center>
<?php

  echo $nmms;

?>
</TD></tr>
</TABLE>
</BODY>
</HTML>

<?php
}
/*

<Script language='Javascript'>
<!--
var mess="IWS Admin - Администрирование";
function click(e) {
 if (document.all) {
  if (event.button == 2) {
      alert(mess);
      return false;
 }}
 if (document.layers) {
 if (e.which == 3) {
      alert(mess);
   return false;
}}}
if (document.layers) {
 document.captureEvents(Event.MOUSEDOWN);
}
document.onmousedown=click;
// --></script>
*/

/*

<td align=right class=vrs>верcия сайта: 
<select name=versite onchange="javascript:window.top.location='index.php?lang='+ this.value +'&mvr=<?php echo $mvr; ?>'">
<?php

if($mainadvar['lng']=="ru"){
   echo "<option value=\"ru\" selected>русская версия</option><option value=\"en\">английская версия</option>";
}else{
   echo "<option value=\"ru\">russian version</option><option value=\"en\" selected>english version</option>";
}
?>
</select>
&nbsp;&nbsp;</td>
*/

?>
