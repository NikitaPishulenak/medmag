<?php

if($act=="replaceDocOk" || $act=="delDocOk" || $act=="deldepartmentOk" || $act=="adddepartment" || $act=="edtdepartment"){
	$cont=catalogOk();	
} else {
	$cont=admin_catalog();		
}

function admin_catalog()
{
	global $act,$err;

	$ct="";
		switch($err){
			case 1:
				$ct="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
			break;
			case 2:
				$ct="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>Не введена вся информация. Попробуйте еще раз.</td></tr></table><br>";
			break;
			case 3:
				$ct="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>Произошла ошибка. Не удалось загрузить файл.</td></tr></table><br>";
			break;
		}

	switch($act){
		case "addDocOk":
			$ct.=addDocTOk();
		break;
		case "addDoc":
			$ct.=addDocT();
		break;
		case "department":
			$ct.=department();
		break;
		default:
			$ct.=defaultView();
		break;
	}
	unset($act,$err);
	return $ct;
}

//----------------------------------------------------------------------------------------------------------------------------


function addDocTOk(){
global $namepos,$shortcontent,$department,$sortBy,$start;

	$namepos=trim($namepos);
	$shortcontent=trim($shortcontent);
	if(empty($namepos) || empty($shortcontent)) { header("location: ?go=docsarchive&act=addDoc".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=2"); return; }

	include('fnciws/docsarchive/docFunctions.php');
	$retDocName=copy_doc_toserver();
  	if(!$retDocName) { header("location: ?go=docsarchive&act=addDoc".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=3"); return; }

	$namepos=addslashes($namepos);
	$shortcontent=addslashes($shortcontent);
	$unicumC=unicumId();

	$sql = "INSERT INTO iws_ardoc_records (department,pse,name,description,file,data) VALUES ($department,'$unicumC','$namepos','$shortcontent','$retDocName',NOW())";

	if(!mysql_query($sql)){
		delDoc($retDocName);
		header("location: ?go=docsarchive&act=addDoc".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=1");
		return;
	} else {
		header("location: ?go=docsarchive".(($sortBy) ? '&sortBy='.$sortBy : ''));
		return;
	}
}




function addDocT()
{
	global $sortBy,$start;
	$content.="<h5>Архив документов / Добавить документ</h5>
		<script><!--
		function tosubmit(){
			if(formS.namepos.value && formS.shortcontent.value && formS.docarchive.value){
				formS.submit();
			} else {
				alert (\"В добавлении отказано! Не введена вся информация.                    \");
			}
		}
		//--></script>
		<form action=\"mainiws.php\" name=formS method=\"post\" enctype=\"multipart/form-data\">
		<input type=\"hidden\" name=go value=docsarchive>
		<input type=\"hidden\" name=act value=addDocOk>
		".(($sortBy) ? '<input type=hidden name=sortBy value='.$sortBy.'>' : '').(($start) ? '<input type=hidden name=start value='.$start.'>' : '')."

		<table width=100% border=0 cellpadding=0 cellspacing=10>
		<tr><td><b>Разделы</b><br><select name=department>
		<option value=0>Без раздела</option>";

			$resDep=mysql_query("SELECT id,name FROM iws_ardoc_department ORDER BY name");
			if(mysql_numrows($resDep)>=1){
				while($arr=mysql_fetch_row($resDep)){
					if($sortBy==$arr[0]){
						$content.="<option value=".$arr[0]." selected>".$arr[1]."</option>";
					} else {
						$content.="<option value=".$arr[0].">".$arr[1]."</option>";
					}
				}
			}

		$content.="</select></td></tr>
				<tr><td>
				<p><b>Название</b><br><input type=text name=namepos style=\"width=100%\"></p>
	 			<p><b>Описание</b><br><textarea name=shortcontent rows=8 style=\"width:100%\"></textarea></p>
	 			<p><b>Файл документа</b><br><input type=file name=docarchive size=50></p>
	 			</td></tr>
				<tr height=80><td><input class=but type=\"button\" value=\"Добавить\" onclick=\"tosubmit(); return false;\">&nbsp;&nbsp;
				<input class=but type=\"button\" value=\"Отмена\" onClick=\"document.location='mainiws.php?go=docsarchive".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."'; return false;\">
				</td></tr></table></form><br>";

	unset($sortBy,$resDep);

return $content;
}


//-----------------------------------------------------------------------------------------------------------------------------


function defaultView(){
global $sortBy,$start,$QUERY_STRING;

		if(!$start) $start=1;

		include('fnciws/docsarchive/docFunctions.php');
		$prom=numlink($start,ereg_replace("&start=".$start,"",$QUERY_STRING),"iws_ardoc_records",$sortBy);


		$content.="<script><!--
		function delOkDoc(urli,nmk){
			if(confirm('Вы действительно хотите удалить документ \"'+nmk+'\"?       ')) document.location=urli+'&docName='+nmk;
		}

		function replaceOkDoc(urlR)
		{
			var arr = null;
			arr = showModalDialog(\"fnciws/docsarchive/gdialog.php?evtype=replaceDoc\", null, \"dialogWidth:410px; dialogHeight:120px; status:no;\");
			if (arr != null) document.location=urlR+'&newCat='+arr;
		}

		//--></script>
		<table width=100% border=0 cellpadding=1 cellspacing=4><tr valign=top><td><h5>Архив документов</h5>
		<table width=100% border=0 cellpadding=2 cellspacing=1>
		<tr><td colspan=5></td><td align=right><a href=\"#\" onclick=\"document.location='mainiws.php?go=docsarchive&act=addDoc".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."'; return false;\">добавить документ</a></td></tr>
		<tr><td colspan=2></td><td colspan=4 bgcolor=#EBEBEB><b>Раздел</b> <select name=sortBy onChange=\"document.location='mainiws.php?go=docsarchive'+sortBy.value+'&start=$start'; return false;\">
		<option value=\"\">Все</option>";

			$resDep=mysql_query("SELECT id,name FROM iws_ardoc_department ORDER BY name");
			if(mysql_numrows($resDep)>=1){
				while($arr=mysql_fetch_row($resDep)){
					if($sortBy==$arr[0]){
						$content.="<option value='&sortBy=".$arr[0]."' selected>".$arr[1]."</option>";
					} else {
						$content.="<option value='&sortBy=".$arr[0]."'>".$arr[1]."</option>";
					}
				}
			}

		$content.="</select>&nbsp;&nbsp;&nbsp;<a title=\"Добавить/Удалить раздел\" href=\"mainiws.php?go=docsarchive&act=department\"><b>Редактировать</b></a></td></tr>";

		if($prom){

			$res=mysql_query("SELECT A.id,(SELECT B.name FROM iws_ardoc_department B WHERE B.id=A.department), DATE_FORMAT(A.data,'%e.%m.%Y %T'), A.name, LEFT(A.description,200), A.file
			 FROM iws_ardoc_records A ".(($sortBy) ? 'WHERE A.department='.$sortBy : '')." ORDER BY A.data DESC LIMIT ".($start-1).",50");
   
			$content.="<tr><td></td><td colspan=5>".$prom."<br></td></tr><tr align=center><td></td><td class=usr>Дата</td><td class=usr>Название</td><td class=usr>Документ</td><td class=usr>Раздел</td><td class=usr>Операции</td></tr>";

			if(mysql_numrows($res)>=1){
				include("inc/config.inc.php");
				$cls="menu1";
				$i=$start;
				while($arr=mysql_fetch_row($res)){
					if($cls=="menu1"){ $cls="menu"; } else { $cls="menu1"; }
			
					$content.="<tr valign=top><td align=right>".($i++).".</td>
									<td class=$cls align=center>".$arr[2]."</td>
									<td class=$cls><p><b>".$arr[3]."</b></p><p>".$arr[4]."</p></td>
									<td class=$cls>".$arr[5]."<br>".display_size($arr[5])."</td>
									<td class=$cls>".$arr[1]."</td>";
					$content.="<td class=$cls>
									[<a href=\"#\" title=\"Сменить раздел\" onclick=\"replaceOkDoc('mainiws.php?go=docsarchive&act=replaceDocOk&sortBy=$sortBy&start=$start&id=".$arr[0]."'); return false;\">сменить раздел</a>] 
									[<a href=\"#\" title=\"Редактировать\" onclick=\"editOkDoc('mainiws.php?go=docsarchive&act=editDocOk&sortBy=$sortBy&start=$start&id=".$arr[0]."','".$arr[5]."'); return false;\">Редактировать</a>] 
									[<a href=\"#\" onclick=\"delOkDoc('mainiws.php?go=docsarchive&act=delDocOk&sortBy=$sortBy&start=$start&id=".$arr[0]."','".$arr[5]."'); return false;\"><font color=#ff0000>удалить</font></a>]
									</td></tr>";
				}
				unset($res);
			} 
			$content.="<tr><td></td><td colspan=5><br>".$prom."</td></tr>";
	
		} else {
			$content.="<tr><td colspan=2></td><td colspan=4><br><br><h4>Извините, на сервере нет документов!</h4></td></tr>";
		}

		$content.="<tr><td colspan=5></td><td align=right><a href=\"#\" onclick=\"document.location='mainiws.php?go=docsarchive&act=addDoc".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."'; return false;\">добавить документ</a></td></tr>"
		."</table></td></tr></table>";

		unset($sortBy,$start);

		return $content;
}


//--------------------------------------------------------------------------------------------------

function department(){
		$content.="
		<script><!--
		function delOkDep(urli,nmk)
		{
			if(confirm('Вы действительно хотите удалить раздел \"'+nmk+'\"?')) document.location=urli;
		}

	 	function addDep()
	 	{
			var arr = null;
			arr = showModalDialog(\"fnciws/docsarchive/gdialog.php?evtype=adddepartment\", null, \"dialogWidth:410px; dialogHeight:120px; status:no;\");
			if (arr != null) document.location='mainiws.php?go=docsarchive&act=adddepartment&nm='+arr[\"cname\"];
		}

	 	function edtDep(nme,did)
	 	{
			var args = new Array();
			var arr = null;
			args[\"cname\"]=nme;
			arr = showModalDialog(\"fnciws/docsarchive/gdialog.php?evtype=edtdepartment\", args, \"dialogWidth:410px; dialogHeight:120px; status:no;\");
			if (arr != null) document.location='mainiws.php?go=docsarchive&act=edtdepartment&id='+did+'&nm='+arr[\"cname\"];
		}
		//--></script>
		<table align=center width=70% border=0 cellpadding=2 cellspacing=1>
		<tr><td><a href=\"#\" onclick=\"document.location='mainiws.php?go=docsarchive'; return false;\">вернуться в архив документов</a></td><td><a href=\"#\" onclick=\"addDep(); return false;\">добавить название раздела</a></td></tr>
		<tr align=center><td class=usr>Название раздела</td><td class=usr></td></tr>\n";

		$res=mysql_query("SELECT id,name FROM iws_ardoc_department ORDER BY name");
		if(mysql_numrows($res)>=1){
			$cl="menu1";
			while($arr=mysql_fetch_row($res)){
				if($cl=="menu1"){ $cl="menu"; } else { $cl="menu1"; }
				$content.="<tr class=$cl><td><b>".$arr[1]."</b>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>"
					."[<a href=\"#\" onclick=\"edtDep('".$arr[1]."',".$arr[0]."); return false;\">редактировать</a>] "
					."[<a href=\"#\" onclick=\"delOkDep('mainiws.php?go=docsarchive&act=deldepartmentOk&id=".$arr[0]."','".$arr[1]."'); return false;\"><font color=#ff0000>удалить</font></a>]</td></tr>\n";
			}
		} else {
			$content.="<tr><td colspan=3>Извините, в базе данных нет названий разделов!</td></tr>";
		} 
		$content.="<tr><td><a href=\"#\" onclick=\"document.location='mainiws.php?go=docsarchive'; return false;\">вернуться в архив документов</a></td><td><a href=\"#\" onclick=\"addDep(); return false;\">добавить название раздела</a></td></tr>"
		."</table>";
		return $content;
}

//------------------------------------------------------------------------------------------------------------------------------------


function catalogOk()
{
	global $act,$id,$sortBy,$start,$newCat,$nm,$docName;
	
	switch($act){

	case "replaceDocOk":
		if(!mysql_query("UPDATE iws_ardoc_records SET department=$newCat WHERE id=$id")){
			header("location: ?go=docsarchive&err=1".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : ''));
			return;
		} else {		
			header("location: ?go=docsarchive".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : ''));
			return;
		}
	break;

	case "delDocOk":
		include('fnciws/docsarchive/docFunctions.php');
		delDoc($docName);
		mysql_query("DELETE FROM iws_ardoc_records WHERE id=$id");

		header("location: ?go=docsarchive".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : ''));
		return;
	break;


	case "deldepartmentOk":
		mysql_query("DELETE FROM iws_ardoc_department WHERE id=$id");

		header("location: ?go=docsarchive&act=department");
		return;
	break;

	case "adddepartment":
		$nm=trim($nm);
		if(empty($nm)) { 
			header("location: ?go=docsarchive&act=department&err=2");
			return;
		}
		$nm=addslashes($nm);
		if(!mysql_query("INSERT INTO iws_ardoc_department (name) VALUES ('$nm')")){
			header("location: ?go=docsarchive&act=department&err=1");
			return;
		} else {		
			header("location: ?go=docsarchive&act=department");
			return;
		}
	break;

	case "edtdepartment":
		$nm=trim($nm);
		if(empty($nm)) { 
			header("location: ?go=docsarchive&act=department&err=2");
			return;
		}
		$nm=addslashes($nm);
		if(!mysql_query("UPDATE iws_ardoc_department SET name='$nm' WHERE id=$id")){
			header("location: ?go=docsarchive&act=department&err=1");
			return;
		} else {		
			header("location: ?go=docsarchive&act=department");
			return;
		}
	break;
}
}


?>