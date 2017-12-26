<?php

include('fnciws/pagemulti/multip.inc.php');

if($act=="addOk" || $act=="delOk" || $act=="edtOk" || $act=="edtpOk"){
	$cont=pageOk();	
} else {
	$cont=admin_page();		
}

function admin_page(){
global $act,$err,$id,$scont,$lcont,$pstbl,$psfld,$pptbl,$ppfld,$pmtbl,$pmfld,$tmpl,$menu,$newpage;
switch($err){
	case 1:
		$ct="Произошла <font color=#ff0000>ошибка</font>. Попробуйте еще раз.";
		break;
	case 2:
		$ct="<font color=#ff0000>Не введена</font> вся информация. Попробуйте еще раз.";
		break;
}
switch($act){
	case "add":
			$content.="<script><!--
			var trt = 0;
			var prt;

		function submitr(){
		if (frm.scont.value) { 
			if(window.BODYhtml.FormHTML.elm1.value){
				if(trt) renwin();
				frm.lcont.value=window.BODYhtml.FormHTML.elm1.value;
				frm.submit();
			} else {
				alert (\"Не введена информация полного содержания страницы!   \");
			}
		} else {
			alert (\"Не введена информация короткого содержания страницы!   \");
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
			."<input type=hidden name=go value=mpage>"
			."<input type=hidden name=act value=addOk>"
			."<input type=hidden name=lcont value=\"\">"
			."<input type=hidden name=menu value=$menu>"
			."<input type=hidden name=tmpl value=$tmpl>"
			."<table bgcolor=#ffffff width=100% height=100% border=0 cellpadding=0 cellspacing=0>"
		."<tr><td align=center class=usr width=100%>Добаление новой страницы. $ct</td>"
		."<td class=usr><img onclick=\"renwin()\" src=\"images/zoom.gif\" border=0 alt=\"Развернуть/уменьшить окно\" style=\"cursor:hand\"></td>"
		."<td class=usr><img onclick=\"javascript:if(trt){ renwin(); } document.location='mainiws.php?go=mpage&menu=$menu&tmpl=$tmpl';\" src=\"images/close.gif\" border=0 alt=\"Закрыть страницу\" style=\"cursor:hand\"></td></tr>"	
			."<tr><td colspan=3><br> <b> Короткое содержание:</b></td></tr>"
			."<tr><td width=100% colspan=3><textarea name=\"scont\" style=\"width:100%\" rows=4>".$scont."</textarea><br><br></td></tr>"
			."<tr><td colspan=3> <b> Полное содержание:</b></td></tr>"
			."<tr><td  colspan=3 height=100%>";
			$content.=ret_html($tmpl);
			$content.="</td></tr>"
			."</form></table>";
		break;

	case "edtv":
		list($scont,$lcont)=mysql_fetch_row(mysql_query("select ".$pmfld['scontent'].",".$pmfld['lcontent']." from ".$pmtbl." where ".$pmfld['did']."=$id"));
		$scont=stripslashes($scont);

		$content.="<script><!--
		var trt = 0;
		var prt;

		function submitr(){
		if (frm.scont.value) { 
			if(window.BODYhtml.FormHTML.elm1.value){
				if(trt) renwin();
				frm.lcont.value=window.BODYhtml.FormHTML.elm1.value;
				frm.submit();
			} else {
				alert (\"Не введена информация полного содержания страницы!   \");
			}
		} else {
			alert (\"Не введена информация короткого содержания страницы!    \");
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
		."<input type=hidden name=go value=mpage>"
		."<input type=hidden name=act value=edtOk>"
		."<input type=hidden name=lcont value=\"\">"
		."<input type=hidden name=id value=$id>"		
		."<input type=hidden name=menu value=$menu>"
		."<input type=hidden name=tmpl value=$tmpl>"
		."<table bgcolor=#ffffff width=100% height=100% border=0 cellpadding=0 cellspacing=0>"
		."<tr><td align=center class=usr width=100%>Редактирование страницы. $ct</td>"
		."<td class=usr><img onclick=\"renwin()\" src=\"images/zoom.gif\" border=0 alt=\"Развернуть/уменьшить окно\" style=\"cursor:hand\"></td>"
		."<td class=usr><img onclick=\"javascript:if(trt){ renwin(); } document.location='mainiws.php?go=mpage&menu=$menu&tmpl=$tmpl';\" src=\"images/close.gif\" border=0 alt=\"Закрыть страницу\" style=\"cursor:hand\"></td></tr>"	
		."<tr><td colspan=3><br> <b> Короткое содержание:</b></td></tr>"
		."<tr><td  colspan=3 width=100%><textarea name=\"scont\" style=\"width:100%\" rows=4>".$scont."</textarea><br><br></td></tr>"
		."<tr><td colspan=3> <b> Полное содержание:</b></td></tr>"
		."<tr><td  colspan=3 height=100%>";
		$content.=ret_html($tmpl);
		$content.="</td></tr>"
		."</form></table>";
		if($lcont){
			$content.="
				<SCRIPT LANGUAGE=JavaScript FOR=\"window\" EVENT=onload><!--
				BODYhtml.FormHTML.elm1.value=\"".ereg_replace("\"","'",ereg_replace("(\r|\n)","",stripslashes($cont)))."\";
				//--></script>\n";
		}
		break;

	case "edtp":
		if(!isset($lcont) && !$lcont && isset($menu) && $menu){
			list($lcont)=mysql_fetch_row(mysql_query("select ".$psfld['content']." from ".$pstbl." where ".$psfld['md']."=$menu"));
		}
		$content.="<script><!--\n";

if(isset($newpage) && $newpage==1){
	$content.="var np = 1;";
}else{
	$content.="var np = 0;";
}
		$content.="
		var trt = 0;
		var prt;

		function submitr(){
			if(window.BODYhtml.FormHTML.elm1.value){
				if(trt) renwin();
				if(!np){
					frm.lcont.value=window.BODYhtml.FormHTML.elm1.value;
					frm.submit();
				}else{
				 	parent.B.svn.newpage.value = window.BODYhtml.FormHTML.elm1.value;
					parent.B.saveMN();
					parent.C.location='../../mainiws.php?go=ret';
				}
			} else {
				alert (\"Не введена информация содержания родительской страницы!   \");
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
		."<input type=hidden name=go value=mpage>"
		."<input type=hidden name=act value=edtpOk>"
		."<input type=hidden name=tmpl value=$tmpl>"
		."<input type=hidden name=lcont value=\"\">"
		."<input type=hidden name=menu value=$menu>"		
		."<table bgcolor=#ffffff height=100% width=100% border=0 cellpadding=0 cellspacing=0>"
		."<tr><td align=center class=usr width=100%>Родительская страница. $ct</td>"
		."<td class=usr><img onclick=\"renwin()\" src=\"images/zoom.gif\" border=0 alt=\"Развернуть/уменьшить окно\" style=\"cursor:hand\"></td>"
		."<td class=usr><img onclick=\"javascript:if(trt){ renwin(); } if(np){ parent.B.location.reload(true); document.location='?go=ret'; } else { document.location='mainiws.php?go=mpage&menu=$menu&tmpl=$tmpl'; }\" src=\"images/close.gif\" border=0 alt=\"Закрыть страницу\" style=\"cursor:hand\"></td></tr>"	
		."<tr><td colspan=3 height=100%>";
		$content.=ret_html(0,1);
		$content.="</td></tr>"
		."</form></table>";
		if($lcont){
			$content.="
				<SCRIPT LANGUAGE=JavaScript FOR=\"window\" EVENT=onload><!--
				BODYhtml.FormHTML.elm1.value=\"".ereg_replace("\"","'",ereg_replace("(\r|\n)","",stripslashes($lcont)))."\";
				//--></script>\n";
		}

		break;

	default:
			$content.="<script><!--\n"
			."var arm = \"Вы действительно хотите удалить страницу?     \";"
			."function fnOk(urli){ \n"
			."if(confirm(arm)){\n"
			."document.location=urli;\n"
			."}\n"
			."}\n"
			."//--></script>\n";
			$res=mysql_query("select ".$pmfld['did'].",".$pmfld['scontent']." from ".$pmtbl." where ".$pmfld['md']."=".$menu." order by ".$pmfld['did']);
			$content.="<table width=100% border=0 cellpadding=2 cellspacing=1 align=center>"
			."<tr><td colspan=4><img src=\"images/parentpage.gif\" border=0 alt=\"\" align=left> <a href=\"#\" onclick=\"javascript:document.location='mainiws.php?go=mpage&act=edtp&tmpl=$tmpl&menu=$menu'\">родительская страница</a>"
			."<hr></td></tr>"
			."<tr><td colspan=2></td><td colspan=2><a href=\"#\" onclick=\"javascript:document.location='mainiws.php?go=mpage&act=add&tmpl=$tmpl&menu=$menu'\">добавить страницу</a></td></tr>";
			if(mysql_numrows($res)>=1){
			$content.="<tr align=center><td></td><td class=usr width=80%>Короткое содержание</td><td class=usr colspan=2></td></tr>";
				$cls="menu1";
				$i=1;
				while($arr=mysql_fetch_row($res)){
					if($cls=="menu1"){ $cls="menu"; } else { $cls="menu1"; }
					$content.="<tr><td align=right>".($i++).".</td><td class=$cls>".stripslashes($arr[1])."</td>"
					."<td class=$cls nowrap>"
					."<a href=\"?go=mpage&act=edtv&id=".$arr[0]."&tmpl=$tmpl&menu=$menu\">редактировать</a></td>"
					."<td class=$cls nowrap>"
					."<a href=\"#\" onclick=\"fnOk('mainiws.php?go=mpage&act=delOk&id=".$arr[0]."&tmpl=$tmpl&menu=$menu'); return false;\">удалить</a></td></tr>";
				}
			$content.="<tr><td colspan=2></td><td colspan=2><a href=\"#\" onclick=\"javascript:document.location='mainiws.php?go=mpage&act=add&tmpl=$tmpl&menu=$menu'\">добавить страницу</a></td></tr></table>";
		} else {
			$content.="</table><center><b>Извините в базе данных записей нет!</b>"
			."</center>";
		}
     	break;
}
return $content;
}

function pageOk(){
global $act,$id,$scont,$lcont,$tmpl,$menu,$pstbl,$psfld,$pmtbl,$pmfld;
switch($act){
	case "addOk":
		$scont=trim($scont);
		$lcont=trim($lcont);
		if(empty($scont) || empty($lcont)) { 
			header("location: ?go=mpage&act=add&scont=$scont&err=2&menu=$menu&tmpl=$tmpl");
			return;
		}
		$lcont=addslashes($lcont);
		$scont=addslashes($scont);
		if(!mysql_query("insert into ".$pmtbl." (".$pmfld['md'].",".$pmfld['scontent'].",".$pmfld['lcontent'].") values ($menu,'$scont','$lcont')")){
			header("location: ?go=mpage&act=add&scont=$scont&err=1&menu=$menu&tmpl=$tmpl");
			return;
		} else {		
			header("location: ?go=mpage&menu=$menu&tmpl=$tmpl");
			return;
		}
		break;
	case "edtOk":
		$scont=trim($scont);
		$lcont=trim($lcont);
		if(empty($scont) || empty($lcont)) { 
			header("location: ?go=mpage&act=edtv&id=$id&err=2&menu=$menu&tmpl=$tmpl");
			return;
		}
		$lcont=addslashes($lcont);
		$scont=addslashes($scont);
		if(!mysql_query("update ".$pmtbl." set ".$pmfld["scontent"]."='$scont',".$pmfld["lcontent"]."='$lcont' where ".$pmfld['did']."=$id and ".$pmfld['md']."=$menu")){
			header("location: ?go=mpage&act=edtv&id=$id&err=1&menu=$menu&tmpl=$tmpl");
			return;
		} else {		
			header("location: ?go=mpage&menu=$menu&tmpl=$tmpl");
			return;
		}
		break;

	case "edtpOk":
		$lcont=trim($lcont);
		if(empty($lcont)) { 
			header("location: ?go=mpage&act=edtp&err=2&tmpl=$tmpl&menu=$menu");
			return;
		}
		$lcont=addslashes($lcont);
		if(!mysql_query("update ".$pstbl." set ".$psfld['content']."='$lcont' where ".$psfld['md']."=$menu")){
			header("location: ?go=mpage&act=edtp&err=1&tmpl=$tmpl&menu=$menu");
			return;
		} else {		
			header("location: ?go=mpage&tmpl=$tmpl&menu=$menu");
			return;
		}

		break;
	case "delOk":
		if(!mysql_query("delete from ".$pmtbl." where ".$pmfld['did']."=".$id)){
			header("location: ?go=mpage&err=1&menu=$menu&tmpl=$tmpl");
			return;
		} else {
			header("location: ?go=mpage&menu=$menu&tmpl=$tmpl");		
			return;
		}
		break;
}
}

function ret_html($tmpl,$vr="-1"){
if($vr) $vr = "&vrtp=".$vr;
		return "<iframe name=\"BODYhtml\" width=\"100%\" height=\"100%\" frameborder=0 marginwidth=0 marginheight=0 src=\"fnciws/html/html_edit.php?tml=$tmpl".$vr."\" scrolling=no></iframe>";
}

?>