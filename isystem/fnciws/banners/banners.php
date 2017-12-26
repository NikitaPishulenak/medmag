<?php

include('fnciws/banners/banner.inc.php');

if($act=="edtOk"){
	$cont=banOk();	
} else {
	$cont=admin_ban();		
}


function admin_ban(){
global $act,$err,$id,$ban,$pbtbl,$pbfld;
if($err==1){
		$ret="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
		."Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
}
switch($act){
	case "edtv":
		list($nme,$ban)=mysql_fetch_row(mysql_query("select ".$pbfld['nme'].",".$pbfld['ban']." from ".$pbtbl." where ".$pbfld['did']."=$id"));
		$ret.="<table width=100% border=0 cellpadding=3 cellspacing=0>"
		."<tr><td align=center class=usr>Редактировать код баннера</td></tr></table>"
		."<form method=\"post\" name=frm>"
		."<input type=hidden name=gopr value=banners>"
		."<input type=hidden name=act value=edtOk>"
		."<input type=hidden name=ban value=\"\">"
		."<input type=hidden name=id value=$id>"		
		."<table bgcolor=#ffffff width=100% border=0 cellpadding=1 cellspacing=2 align=center>"
		."<tr><td align=center><b>$nme</b></td></tr>"
		."<tr><td>Код баннера: </td></tr><tr><td width=100%><textarea name=\"ban\" style=\"width:100%\" rows=15>".stripslashes($ban)."</textarea></td></tr>"
		."<tr valign=top><td><br><input class=but type=button name=btn value=\"Сохранить изменения\" onClick=\"frm.submit();\">"
		."&nbsp;&nbsp<input class=but type=button value=\"Отмена\" onclick=\"javascript:document.location='mainiwspref.php?gopr=banners'\"></td></tr>"
		."</form></table>";
		break;

	default:
			$res=mysql_query("select ".$pbfld['did'].",".$pbfld['nme'].",".$pbfld['ban']." from ".$pbtbl." ORDER BY ".$pbfld['did']);

			$ret.="<table cellpadding=1 cellspacing=0 border=0 width=100%><tr><td class=usr>&nbspБаннеры/счетчики</td></tr></table>\n<br><br>"
				."<table border=0 cellpadding=1 cellspacing=1 align=center>"
				."<tr align=center><td class=usr width=25%>Тип баннера/счетчика</td><td class=usr width=60%>Баннеры/счетчики</td><td class=usr></td></tr>";
			if(mysql_numrows($res)>=1){
				$cls="menu1";
				while($arr=mysql_fetch_row($res)){
					if($cls=="menu1"){ $cls="menu"; } else { $cls="menu1"; }
					$ret.="<tr><td class=$cls align=center>".$arr[1].".</td><td align=center class=$cls>".stripslashes($arr[2])."</td>"
					."<td class=$cls align=center><a href=\"?gopr=banners&act=edtv&id=".$arr[0]."\">редактировать</a></td></tr>";
				}
			} 
			$ret.="</table>";
     	break;
}
return $ret;
}

function banOk(){
global $id,$ban,$pbtbl,$pbfld;

	if(!mysql_query("update ".$pbtbl." set ".$pbfld['ban']."='".addslashes($ban)."' where ".$pbfld['did']."=".$id)){
			header("location: ?gopr=banners&act=edtv&id=$id&err=1");
			return;
		} else {		
			header("location: ?gopr=banners");
			return;
		}

}
?>