<?php

include('fnciws/opros/opr.inc.php');

if($act=="addOk" || $act=="delOk" || $act=="edtOk" || $act=="acto" || $act=="nl"){
	$cont=oprOk();	
} else {
	$cont=admin_opros();		
}



function admin_opros(){ // работа с пунктом новости
global $act,$dact,$err,$id,$name,$otv,$atv,$natbl,$fieldnmn,$patbl,$fieldnmp,$mainadvar;
switch($err){
	case 1:
		$content="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
		."Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
		break;
	case 3:
		$content="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
		."Не введена вся информация. Попробуйте еще раз.</td></tr></table><br>";
		break;
}
switch($act){
	case "add":
		if($dact=="addv"){
			$cnt=count($otv)-2;
		}elseif($dact=="addd"){
			$cnt=count($otv)-4;
		} else {
			$cnt=count($otv)-3;
		}
		$content.="<table width=100% border=0 cellpadding=3 cellspacing=0>"
		."<tr><td align=center class=usr>Добавить голосование/опрос</td></tr></table>";
			$content.="<script><!--
			function submitr(fr){
			cn=fr.elements.length;
			tr=true;
			for(i=0;i<=cn-1;i++){ if(!fr.elements[i].value){ tr=false; }	}
			if (tr) { 
				fr.submit();
			} else {
				alert (\"Не введена вся информация!   \");
			}
			}
			function addvopr(fr){
				fr.dact.value=\"addv\";
				fr.act.value=\"add\";
				fr.submit();			
			}
			function delvopr(fr){
				fr.dact.value=\"addd\";
				fr.act.value=\"add\";
				fr.submit();			
			}
			//--></script>"
			."<form method=\"post\" name=frm>"
			."<input type=hidden name=go value=opros>"
			."<input type=hidden name=dact value=e>"
			."<input type=hidden name=act value=addOk>"
			."<center>Все поля для заполнения обязательны!</center><br>"
			."<table bgcolor=#ffffff width=100% border=0 cellpadding=3 cellspacing=2 align=center>"
			."<tr><td align=right width=35%>Вопрос: </td><td><input name=\"name\" size=60 maxlength=250 value=\"$name\"></td></tr>"
			."<tr><td align=right>Активировать голосование: </td><td><input type=checkbox class=chk name=\"atv\"";
			if(isset($atv) && $atv){ $content.=" checked"; }
			$content.="></td></tr>"
			."<tr><td colspan=2><hr></td></tr>"
			."<tr><td align=right>Вариант ответа 1: </td><td><input name=\"otv[]\" size=60 maxlength=250 value=\"".$otv[0]."\"></td></tr>"
			."<tr><td align=right>Вариант ответа 2: </td><td><input name=\"otv[]\" size=60 maxlength=250 value=\"".$otv[1]."\"></td></tr>";
			for($i=0;$i<=$cnt;$i++){ $content.="<tr><td align=right>Вариант ответа ".($i+3).": </td><td><input name=\"otv[]\""
												." size=60 maxlength=250 value=\"".$otv[$i+2]."\">"
												."</td></tr>"; }
			$content.="<tr><td></td><td><input class=but type=button value=\"Добавить ответ\" onclick=\"addvopr(frm)\">&nbsp;&nbsp";
			if(isset($cnt) && $cnt>=0){ $content.="<input class=but type=button value=\"Удалить ответ\" onclick=\"delvopr(frm)\">";	}
			$content.="</td></tr>"
			."<tr><td colspan=2><hr></td></tr><tr><td></td><td><input class=but type=button name=btn value=Добавить onClick=\"submitr(frm)\">"
			."&nbsp;&nbsp<input class=but type=button value=\"Отмена\" onclick=\"javascript:document.location='mainiws.php?go=opros'\"></td></tr>"
			."</form></table>";
		break;

	case "edtv":
		list($name,$atv)=mysql_fetch_row(mysql_query("select ".$fieldnmn['vopr'].",".$fieldnmn['act']." from ".$natbl." where ".$fieldnmn['did']."=$id"));
		$name=stripslashes($name);
		if($dact!="eddv" && $dact!="eddd") {
			$res=mysql_query("select ".$fieldnmp['did'].",".$fieldnmp['otv']." from ".$patbl." where ".$fieldnmp['mid']."=$id order by ".$fieldnmp['did']);	
			$i=1;
			while($arr=mysql_fetch_row($res)){
				$otv[]=stripslashes($arr[1]);
				$tvc.="<tr><td align=right>Вариант ответа ".($i++).": </td><td><input name=\"otv[]\" size=60 maxlength=250 value=\"".stripslashes($arr[1])."\"></td></tr>";
			}
		}
		$cnt=count($otv)-1;
		if($dact=="eddv"){
			$cnt++;
		}elseif($dact=="eddd"){
			$cnt--;
		}
		$content.="<table width=100% border=0 cellpadding=3 cellspacing=0>"
		."<tr><td align=center class=usr>Редактировать голосование/опрос</td></tr></table>";
			$content.="<script><!--
			function submitr(fr){
			cn=fr.elements.length;
			tr=true;
			for(i=0;i<=cn-1;i++){ if(!fr.elements[i].value){ tr=false; }	}
			if (tr) { 
				fr.submit();
			} else {
				alert (\"Не введена вся информация!   \");
			}
			}
			function addvopr(fr){
				fr.dact.value=\"eddv\";
				fr.act.value=\"edtv\";
				fr.submit();			
			}
			function delvopr(fr){
				fr.dact.value=\"eddd\";
				fr.act.value=\"edtv\";
				fr.submit();			
			}
			//--></script>"
			."<form method=\"post\" name=frm>"
			."<input type=hidden name=go value=opros>"
			."<input type=hidden name=dact value=e>"
			."<input type=hidden name=act value=edtOk>"
			."<center>Все поля для заполнения обязательны!</center><br>"
			."<table bgcolor=#ffffff width=100% border=0 cellpadding=3 cellspacing=2 align=center>"
			."<tr><td align=right width=35%>Вопрос: </td><td><input name=\"name\" size=60 maxlength=250 value=\"$name\"></td></tr>"
			."<tr><td align=right>Статус: </td><td>";
			if(isset($atv) && $atv){
				$content.="<i>активен</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=button class=but value=\"деактивировать\"";
			}else{
				$content.="<i>неактивен</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=button class=but value=\"активировать\"";
			}
			$content.="  onclick=\"javascript:document.location='mainiws.php?go=opros&act=acto&id=$id'\"></td></tr>"
			."<tr><td colspan=2><hr></td></tr>"
			."<tr><td colspan=2 align=center><table bgcolor=#F6F7F7><tr><td><b>Внимание!</b> При сохранении изменений голосования/опроса автоматически будут обнулены счетчики голосования</b></td></tr></table><br><br></td></tr>"			
			.$tvc;
			if($dact=="eddv" || $dact=="eddd") {
			for($i=0;$i<=$cnt;$i++){ $content.="<tr><td align=right>Вариант ответа ".($i+1).": </td><td><input name=\"otv[]\""
												." size=60 maxlength=250 value=\"".$otv[$i]."\">"
												."</td></tr>"; }
			}
			$content.="<tr><td></td><td><input class=but type=button value=\"Добавить ответ\" onclick=\"addvopr(frm)\">&nbsp;&nbsp";
			if($cnt>=2){ $content.="<input class=but type=button value=\"Удалить ответ\" onclick=\"delvopr(frm)\">";	}
			$content.="</td></tr>"
			."<tr><td colspan=2><hr></td></tr><tr><td></td><td><input class=but type=button name=btn value=Сохранить onClick=\"submitr(frm)\">"
			."&nbsp;&nbsp<input class=but type=button value=\"Отмена\" onclick=\"javascript:document.location='mainiws.php?go=opros'\"></td></tr>"
			."</form></table>";
		break;
	case "view":
		list($name,$atv,$mxi)=mysql_fetch_row(mysql_query("select ".$natbl.".".$fieldnmn['vopr'].",".$natbl.".".$fieldnmn['act']
																	.",MAX(".$patbl.".".$fieldnmp['gls'].") from ".$natbl.",".$patbl
																	." where ".$natbl.".".$fieldnmn['did']."=$id and ".$patbl.".".$fieldnmp['mid']
																	."=$id GROUP BY ".$natbl.".".$fieldnmn['vopr']));
		$name=stripslashes($name);
		$res=mysql_query("select ".$fieldnmp['otv'].",".$fieldnmp['gls']." from ".$patbl." where ".$fieldnmp['mid']."=$id order by ".$fieldnmp['did']);	
		while($arr=mysql_fetch_row($res)){
			$otvet[]=stripslashes($arr[0]);
			$gl[]=$arr[1];
			$sum+=$arr[1];
		}
		$cnt=count($otvet)-1;
		for($i=0;$i<=$cnt;$i++){
			$per=substr(($gl[$i]/$sum)*100,0,5);
			$lnw=($gl[$i]/$mxi);
			$resl.="<tr><td align=right>".$otvet[$i].": </td><td>"
			."<img src=\"images/vote.gif\" width=".round(200*$lnw)." height=8> <b>".$gl[$i]."</b> ( ".$per."% )</td></tr>";
		}
		$content.="<table width=100% border=0 cellpadding=3 cellspacing=0>"
			."<tr><td align=center class=usr>Результаты голосования/опроса</td></tr></table>"
			."<table bgcolor=#ffffff width=100% border=0 cellpadding=3 cellspacing=2 align=center>"
			."<tr><td align=right width=35%>Вопрос: </td><td>$name</td></tr>"
			."<tr><td align=right>Активность: </td><td>";
			if($atv){ $content.="<b>Да</b>"; } else { $content.="<i>Нет</i>"; }
			$content.="</td></tr>"
			."<tr><td colspan=2><hr></td></tr>"
			.$resl
			."<tr><td colspan=2><hr></td></tr><tr><td></td><td><input class=but type=button value=\"<< Назад\" onclick=\"javascript:document.location='mainiws.php?go=opros'\">"
			."&nbsp;&nbsp;&nbsp;&nbsp;<input class=but type=button value=\"Обнулить счетчик\" onclick=\"javascript:document.location='mainiws.php?go=opros&act=nl&id=$id'\"></td></tr>"
			."</table>";
		break;
	default:
		$content.="<script><!--\n"
		."function deOk(urli){ \n"
		."if(confirm(\"Вы действительно хотите удалить голосование/опрос?     \")){\n"
		."document.location=urli;\n"
		."}\n"
		."}\n"
		."//--></script>\n";
		$res=mysql_query("select ".$fieldnmn['did'].",".$fieldnmn['vopr'].",".$fieldnmn['act']." from ".$natbl." WHERE ".$fieldnmn['lng']."='".$mainadvar['lng']."' order by ".$fieldnmn['act']." DESC");
		$content.="<table width=100% border=0 cellpadding=2 cellspacing=1 align=center>"
		."<tr><td colspan=7><input class=but type=button value=\"Добавить голосование/опрос\" onclick=\"javascript:document.location='mainiws.php?go=opros&act=add'\">&nbsp;&nbsp;"
		."<hr></td></tr>"
		."<tr align=center><td></td><td class=usr>Активность</td><td class=usr width=60%>Вопрос</td><td class=usr>Голосов</td><td class=usr colspan=3></td></tr>";
		if(mysql_numrows($res)>=1){
			$cls="menu1";
			$i=1;
			while($arr=mysql_fetch_row($res)){
				list($gls)=mysql_fetch_row(mysql_query("select SUM(".$fieldnmp['gls'].") from ".$patbl." where ".$fieldnmp['mid']."=".$arr[0]));
				if($cls=="menu1"){ $cls="menu"; } else { $cls="menu1"; }
				$content.="<tr><td>".($i++).".</td><td class=$cls align=center>";
				if($arr[2]){ $content.="<b>Да</b>"; } else { $content.="<i>Нет</i>"; }
				$content.="</td><td class=$cls>".stripslashes($arr[1])."</td>"
				."<td class=$cls align=center>".$gls."</td>"
				."<td class=$cls nowrap><a href=\"?go=opros&act=edtv&id=".$arr[0]."\">редактировать</a></td>"
				."<td class=$cls nowrap>";
				if($gls){ $content.="<a href=\"?go=opros&act=view&id=".$arr[0]."\">см. результаты</a>"; }
				$content.="</td><td class=$cls nowrap><a href=\"#\" onclick=\"deOk('mainiws.php?go=opros&act=delOk&id=".$arr[0]."'); return false;\">удалить</a></td></tr>";
			}
		} 
		$content.="</table>";
     	break;
}
return $content;
}

function oprOk(){
global $act,$atv,$name,$id,$otv,$natbl,$fieldnmn,$patbl,$fieldnmp,$mainadvar;
switch($act){
	case "addOk":
		$name=trim($name);
		$cnt=count($otv)-1;
		$tr=1;
		for($i=0;$i<=$cnt;$i++){
			$otv[$i]=trim($otv[$i]);
			if(empty($otv[$i])){ $tr=0; }
			$otv[$i]=substr($otv[$i],0,250);
			$otv[$i]=ereg_replace("\"","&quot",$otv[$i]);
			$otv[$i]=addslashes($otv[$i]);
		}
		if(empty($name) || !$tr) { 
			header("location: ?go=opros&act=add&name=$name&atv=$atv&err=3");
			return;
		}
		$name=substr($name,0,250);
		$name=ereg_replace("\"","&quot",$name);
		$name=addslashes($name);
		if($atv){ $activ=1; } else { $activ=0; }
		if(!mysql_query("insert into ".$natbl." (".$fieldnmn['vopr'].",".$fieldnmn['act'].",".$fieldnmn['lng'].") values ('$name',$activ,'".$mainadvar['lng']."')")){
			header("location: ?go=opros&act=add&name=$name&atv=$atv&err=1");
			return;
		} else {		
			list($vid)=mysql_fetch_row(mysql_query("select ".$fieldnmn['did']." from ".$natbl." where ".$fieldnmn['vopr']."='$name'"));
			$qur="insert into ".$patbl." (".$fieldnmp['mid'].",".$fieldnmp['otv'].",".$fieldnmp['gls'].") values ";
			for($i=0;$i<=$cnt;$i++){
				$qur.="($vid,'".$otv[$i]."',0)";
				if($cnt>=1){
					if($i<>$cnt){ $qur.=","; } else { $qur.=";";	}
				}
			}
			mysql_query($qur);
			header("location: ?go=opros");
			return;
		}
		break;
	case "edtOk":
		$name=trim($name);
		$cnt=count($otv)-1;
		$tr=1;
		for($i=0;$i<=$cnt;$i++){
			$otv[$i]=trim($otv[$i]);
			if(empty($otv[$i])){ $tr=0; }
			$otv[$i]=substr($otv[$i],0,250);
			$otv[$i]=ereg_replace("\"","&quot",$otv[$i]);
			$otv[$i]=addslashes($otv[$i]);
		}
		if(empty($name) || !$tr) { 
			header("location: ?go=opros&act=edtv&name=$name&atv=$atv&id=$id&err=3");
			return;
		}
		$name=substr($name,0,250);
		$name=ereg_replace("\"","&quot",$name);
		$name=addslashes($name);
		if(!mysql_query("update ".$natbl." set ".$fieldnmn['vopr']."='$name' where ".$fieldnmn['did']."=$id")){
			header("location: ?go=opros&act=edtv&name=$name&atv=$atv&id=$id&err=1");
			return;
		} else {		
			$qur="insert into ".$patbl." (".$fieldnmp['mid'].",".$fieldnmp['otv'].",".$fieldnmp['gls'].") values ";
			for($i=0;$i<=$cnt;$i++){
				$qur.="($id,'".$otv[$i]."',0)";
				if($cnt>=1){
					if($i<>$cnt){ $qur.=","; } else { $qur.=";";	}
				}
			}
			if(mysql_query("delete from ".$patbl." where ".$fieldnmp['mid']."=".$id) && mysql_query($qur)){
				header("location: ?go=opros");
				return;
			} else {
				header("location: ?go=opros&act=edtv&name=$name&atv=$atv&id=$id&err=1");
				return;
			}
		}
	case "delOk":
		if(mysql_query("delete from ".$patbl." where ".$fieldnmp['mid']."=".$id) && mysql_query("delete from ".$natbl." where ".$fieldnmn['did']."=".$id)){
			header("location: ?go=opros");		
			return;
		} else {
			header("location: ?go=opros&err=1");
			return;
		}
		break;
	case "acto":
		mysql_query("UPDATE ".$natbl." SET ".$fieldnmn['act']."=IF(".$fieldnmn['act'].",0,1) where ".$fieldnmn['did']."=".$id);
		header("location: ?go=opros");		
		return;
	break;
	case "nl":
		mysql_query("UPDATE ".$patbl." SET ".$fieldnmp['gls']."=0 where ".$fieldnmp['mid']."=".$id);
		header("location: ?go=opros");		
		return;
	break;
}
}

?>