<?php

include('fnciws/pagemain/mainp.inc.php');

if($act=="edtOk"){
	$cont=edtOk();	
} else {
	$cont=admin_mainp();		
}



function admin_mainp(){
global $err,$id,$mntbl,$mnfld,$cont,$tmpl;

if($err==1){
	$ct="Произошла <font color=#ff0000>ошибка</font>. Попробуйте еще раз.";
}elseif($err==2){
	$ct="Данные успешно сохранены.";
}

if(empty($cont)){
	list($cont)=mysql_fetch_row(mysql_query("select ".$mnfld['cont']." from ".$mntbl." where ".$mnfld['did']."=1"));
}
		$content="<script><!--
		var trt = 0;
		var prt;


		function submitr(){
			if(window.BODYhtml.FormHTML.elm1.value){
				if(trt) renwin();
				frm.cont.value=window.BODYhtml.FormHTML.elm1.value;
				frm.submit();
			} else {
				alert (\"Не введена информация содержания главной страницы!   \");
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
		."<input type=hidden name=go value=main>"
		."<input type=hidden name=act value=edtOk>"
		."<input type=hidden name=tmpl value=$tmpl>"
		."<input type=hidden name=cont value=\"\">"
		."<input type=hidden name=id value=1>"		
		."<table bgcolor=#ffffff height=100% width=100% border=0 cellpadding=0 cellspacing=0>"
		."<tr><td class=usr width=100% align=center>Главная страница. $ct</td><td class=usr><img onclick=\"renwin()\" src=\"images/zoom.gif\" border=0 alt=\"Развернуть/уменьшить окно\" style=\"cursor:hand\"></td>"
		."<td class=usr><img onclick=\"javascript:if(trt){ renwin(); } document.location='?go=ret';\" src=\"images/close.gif\" border=0 alt=\"Закрыть главную страницу\" style=\"cursor:hand\"></td></tr>"	
		."<tr valign=top><td colspan=3 height=100%>";
		$content.=ret_html($tmpl);
		$content.="</td></tr>"
		."</form></table>";
		if($cont){
			$content.="\n<SCRIPT LANGUAGE=JavaScript FOR=\"window\" EVENT=onload><!--
				BODYhtml.FormHTML.elm1.value=\"".ereg_replace("\"","'",ereg_replace("(\r|\n)","",stripslashes($cont)))."\";
				//--></script>\n";
		}
return $content;
}

function edtOk(){
global $id,$cont,$mntbl,$mnfld,$tmpl;

		$cont=trim($cont);
		if(empty($cont)) { 
			header("location: ?go=main&err=1&tmpl=$tmpl");
			return;
		}
		$cont=addslashes($cont);
		if(!mysql_query("update ".$mntbl." set ".$mnfld['cont']."='$cont' where ".$mnfld['did']."=$id")){
			header("location: ?go=main&err=1&tmpl=$tmpl");
			return;
		} else {		
			header("location: ?go=main&tmpl=$tmpl&err=2");
			return;
		}

}

function ret_html($tmpl){
	return "<iframe name=\"BODYhtml\" width=\"100%\" height=\"100%\" frameborder=0 marginwidth=0 marginheight=0 src=\"fnciws/html/html_edit.php?tml=$tmpl&vrtp=0\" scrolling=no></iframe>";
}

?>