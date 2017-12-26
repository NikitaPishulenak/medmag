<?php

include('fnciws/pagesimple/simplep.inc.php');

if($act=="edtOk"){
	$cont=edtOk();	
} else {
	$cont=admin_simpl();		
}



function admin_simpl(){
global $err,$mntbl,$mnfld,$cont,$tmpl,$menu,$newpage,$keyw,$descr,$robots,$title;
if($err==1){
	$ct="Произошла <font color=#ff0000>ошибка</font>. Попробуйте еще раз.";
}elseif($err==2){
	$ct="Данные успешно сохранены";
}

if(empty($cont) && isset($menu) && $menu){
	list($cont,$title,$robots,$keyw,$descr)=mysql_fetch_row(mysql_query("select ".$mnfld['content'].",title,robots,keywords,descr from ".$mntbl." where ".$mnfld['md']."=$menu"));
}
		$content="<script><!--\n";

if(isset($newpage) && $newpage==1){
	$robots="all";
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
					frm.cont.value=window.BODYhtml.FormHTML.elm1.value;
					frm.submit();
				}else{
				 	parent.B.svn.newpage.value = window.BODYhtml.FormHTML.elm1.value;
				 	parent.B.svn.ttle.value = frm.title.value;
				 	parent.B.svn.robots.value = frm.robots.value;
				 	parent.B.svn.descr.value = frm.descr.value;
				 	parent.B.svn.keyw.value = frm.keyw.value;
					parent.B.saveMN();
					parent.C.location='../../mainiws.php?go=ret';
				}
			} else {
				alert (\"Не введена информация содержания страницы!   \");
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
		."<input type=hidden name=go value=page>"
		."<input type=hidden name=act value=edtOk>"
		."<input type=hidden name=tmpl value=$tmpl>"
		."<input type=hidden name=cont value=\"\">"
		."<input type=hidden name=menu value=$menu>"		
		."<table bgcolor=#ffffff height=100% width=100% border=0 cellpadding=0 cellspacing=0>"
		."<tr><td class=usr align=center width=100%>$ct</td><td class=usr><img onclick=\"renwin()\" src=\"images/zoom.gif\" border=0 alt=\"Развернуть/уменьшить окно\" style=\"cursor:hand\"></td>"
		."<td class=usr><img onclick=\"javascript:if(trt){ renwin(); } if(np){ parent.B.location.reload(true);} document.location='?go=ret';\" src=\"images/close.gif\" border=0 alt=\"Закрыть страницу\" style=\"cursor:hand\"></td></tr>"	
		."<tr><td colspan=3>
		<table bgcolor=#EEEEEE border=0 cellpadding=3 cellspacing=0 width=100%>
		<tr><td width=20%><b>Title</b><br><input type=text name=title size=50 maxlength=250 value=\"".$title."\"><br>
					<b>Robots</b><br><input type=text size=50 name=robots maxlength=100 value=\"".$robots."\"></td>
		<td width=40%><b>Description</b><br><textarea name=descr rows=3 style=\"width:100%\" >".$descr."</textarea></td>
		<td width=40%><b>Keywords</b><br><textarea name=keyw rows=3 style=\"width:100%\" >".$keyw."</textarea></td></tr>
		</table>
		</td></tr>"
		."<tr valign=top><td height=100% colspan=3>";
		$content.=ret_html($tmpl);
		$content.="</td></tr>"
//		."<tr><td valign=top colspan=2><input class=but type=button name=btn value=\"Сохранить изменения\" onClick=\"submitr(frm)\"></td></tr>"
		."</form></table>";
		if($cont){
			$content.="
				<SCRIPT LANGUAGE=JavaScript FOR=\"window\" EVENT=onload><!--
				BODYhtml.FormHTML.elm1.value=\"".ereg_replace("\"","'",ereg_replace("(\r|\n)","",stripslashes($cont)))."\";
				//--></script>\n";
		}
return $content;
}

function edtOk(){
global $menu,$cont,$mntbl,$mnfld,$tmpl,$keyw,$descr,$robots,$title;

		$cont=trim($cont);
		if(empty($cont)) { 
			header("location: ?go=page&err=1&tmpl=$tmpl&menu=$menu");
			return;
		}
		if(empty($robots)) { $robots="all"; }
		$cont=addslashes($cont);
		if(!mysql_query("update ".$mntbl." set ".$mnfld['content']."='$cont',title='$title',robots='$robots',keywords='$keyw',descr='$descr' where ".$mnfld['md']."=$menu")){
			header("location: ?go=page&err=1&tmpl=$tmpl&menu=$menu");
			return;
		} else {		
			header("location: ?go=page&err=2&tmpl=$tmpl&menu=$menu");
			return;
		}

}

function ret_html($tmpl){
return "<iframe name=\"BODYhtml\" width=\"100%\" height=\"100%\" frameborder=0 marginwidth=0 marginheight=0 src=\"fnciws/html/html_edit.php?tml=$tmpl&vrtp=-1\" scrolling=no></iframe>";
}

?>