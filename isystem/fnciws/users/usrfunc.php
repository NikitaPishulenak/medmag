<?php

include('fnciws/users/usr.inc.php');

if($act=="addOk" || $act=="edtOk" || $act=="delOk" || $act=="delG" || $act=="addG" || $act=="replG"){
	$cont=fOk();	
} else {
	if(!$act){
		$cont=user_view();
	} elseif($act=="del") {
		$cont=user_view();			
	} else {
		$cont=user_pref();
	}
}

function user_pref() {
global $act,$mainadvar,$lgn,$id,$err,$atbl,$fieldnm,$gtbl,$gfld,$grp,$fio,$knt;

switch($act){
 	case "add":
		$masc="Добавление пользователя";
		break;
 	case "edt":
		$masc="Редактирование пользователя";
		break;
 	case "edtv":
		$masc="Редактирование пользователя";
		break;
}
switch($err){
	case 1:
		$massc.="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
		."Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
		break;
	case 2:
		$massc.="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
		."Извините пользователь с таким логином <b>$lgn</b> уже есть. Введите другой логин и пробуйте еще раз.</td></tr></table><br>";
		break;
	case 3:
		$massc.="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
		."Не введена вся информация или пароль менее 6-ти символов или же присутсвуют недопустимые символы. Попробуйте еще раз.</td></tr></table><br>";
		break;
}

	if($act=="add"){
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
		$massc.="<input type=hidden name=act value=addOk>";
		$massc.="<input type=hidden name=grp value=".$grp."><input type=hidden name=gopr value=users>"
		."<table width=100% border=0 cellpadding=3 cellspacing=0>"
		."<tr><td align=center class=usr>".$masc."</td></tr></table>"
		."<table bgcolor=#ffffff width=100% border=0 cellpadding=3 cellspacing=2 align=center>"
		."<tr><td align=right width=40%>Логин: </td><td><input name=lgn size=25 maxlength=30> (Макс. 30 символов)</td></tr>"
		."<tr><td align=right>Пароль: </td><td><input type=password name=passd[0] size=25 maxlength=30> (не менее 6 символов)</td></td></tr>"
		."<tr><td align=right>Подтвердите пароль: </td><td><input type=password name=passd[1] size=25 maxlength=30></td></td></tr>"
		."<tr><td align=right>Ф.И.О.: </td><td><input name=fio size=45 maxlength=250 value=\"$fio\"></td></td></tr>"
		."<tr><td align=right>Контакты: </td><td><input name=knt size=45 maxlength=150 value=\"$knt\"></td></td></tr>"
		."<tr><td colspan=2><hr></td></tr><tr><td></td><td><input class=but type=button name=btn value=регистрация"
		." onClick=\"submitr(frm)\">&nbsp;&nbsp<input type=button value=\"отмена\" onclick=\"javascript:document.location='mainiwspref.php?gopr=users&grp=$grp'\"></td></tr></form></table>";
	}	

	if($act=="edt"){
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
		list($lgn,$fio,$knt)=mysql_fetch_row(mysql_query("select ".$fieldnm['login'].",".$fieldnm['nme'].",".$fieldnm['eml']." from ".$atbl." where ".$fieldnm['did']."=$id"));
		$massc.="<input type=hidden name=act value=edtOk>"
		."<input type=hidden name=id value=$id>"			
		."<input type=hidden name=grp value=$grp><input type=hidden name=gopr value=users>"			
		."<table width=100% border=0 cellpadding=3 cellspacing=0>"
		."<tr><td align=center class=usr>".$masc."</td></tr></table>"
		."<table bgcolor=#ffffff width=100% border=0 cellpadding=3 cellspacing=2 align=center>"
		."<tr><td align=right width=40%>Логин: </td><td><input name=lgn size=25 maxlength=30 value=$lgn"
		."> (Макс. 30 символов)</td></tr>"
		."<tr><td align=right>Пароль: </td><td><input type=password name=passd[0] size=25 maxlength=30> (не менее 6 символов)</td></td></tr>"
		."<tr><td align=right>Подтвердите пароль: </td><td><input type=password name=passd[1] size=25 maxlength=30></td></td></tr>"
		."<tr><td align=right>Ф.И.О.: </td><td><input name=fio size=45 maxlength=250 value=\"$fio\"></td></td></tr>"
		."<tr><td align=right>Контакты: </td><td><input name=knt size=45 maxlength=150 value=\"$knt\"></td></td></tr>";
		$massc.="<tr><td colspan=2><hr></td></tr><tr><td></td><td><input class=but type=button name=btn value="
		."сохранить onClick=\"submitr(frm)\">&nbsp;&nbsp<input type=button value=\"отмена\" onclick=\"javascript:document.location='mainiwspref.php?gopr=users&grp=$grp'\"></td></tr></form></table>";
	}
unset($pwdr);
return $massc;
}

function user_view() {
global $lgn,$atbl,$fieldnm,$gtbl,$gfld,$grp,$err;

$massc="<script><!--\n"
."function deOk(urli){ \n"
."if(confirm(\"Вы действительно хотите удалить пользователя?     \")){\n"
."document.location=urli;\n"
."}\n"
."}\n//--></script>";

	if($err==1){
		$massc.="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
		."Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
	}elseif($err==2){
		$massc.="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
		."Группа с таким наименование уже есть. Попробуйте еще раз.</td></tr></table><br>";
	}elseif($err==3){
		$massc.="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
		."Не удалось переместить пользователя в другую группу. Попробуйте еще раз.</td></tr></table><br>";
	}
	
	$massc.="<table bgcolor=#ffffff width=100% border=0 cellpadding=2 cellspacing=0>"
		."<tr><td class=usr>&nbspГруппы пользователей</td></tr></table><br><br>";

		$result=mysql_query("select ".$gfld['did'].",".$gfld['nme']." from ".$gtbl." ORDER BY ".$gfld['did']);
		$cnt=mysql_numrows($result);
		$massc.="<table border=0 cellpadding=2 cellspacing=1 align=center width=90%>"
		."<tr><td><nobr>"
		."<select name=\"grp\" style=\"width:100%\" onchange=\"document.location='?gopr=users&grp='+ this.value\">";
		if($cnt>=1){
			while($arr=mysql_fetch_row($result)){
				$massc.="<option value=".$arr[0];
				if(!isset($grp) || !$grp) $grp = $arr[0];
				if($arr[0]==$grp)	$massc.=" selected";
				$massc.=">".$arr[1]."</option>\n";
			}
		}
	$massc.="</select></td><td width=100% bgcolor=#f0f0f0 style=\"border: 1px #bCbCbC solid;\" colspan=3>"
		." <a href=\"#\" onclick=\"addG()\">добавить группу</a>";
		if($cnt>=1)	$massc.="&nbsp;/&nbsp;<a href=\"#\" onclick=\"delG()\">удалить группу</a>";
		$massc.="</nobr></td></tr>";
	if($cnt>=1){
	$massc.="<tr><td colspan=3></td><td><br><a href=\"#\" onclick=\"javascript:document.location='mainiwspref.php?gopr=users&act=add&grp=$grp'\">добавить пользователя</a></td></tr>"
	."<tr><td class=usr width=20%>пользователь</td><td class=usr width=40%>Ф.И.О.</td><td class=usr width=20%>контакты</td><td align=center class=usr>действие</td><td></td></tr>";
	$res=mysql_query("select ".$fieldnm['did'].",".$fieldnm['login'].",".$fieldnm['nme'].",".$fieldnm['eml']." from ".$atbl." where ".$fieldnm['group']."=".$grp." and ".$fieldnm['tr']."=0");
	if(mysql_numrows($res)>=1){
		while($arr=mysql_fetch_row($res)){
			if($cls=="menu1"){ $cls="menu"; } else { $cls="menu1"; }
			$massc.="<tr><td class=$cls>".$arr[1]."</td>"
						."<td class=$cls>".$arr[2]."</td>"
						."<td class=$cls>".$arr[3]."</td>"
						."<td class=$cls><a href=\"?gopr=users&act=edt&grp=$grp&id=".$arr[0]."\">редактировать</a>"
						."&nbsp;/&nbsp;<a href=\"#\" onclick=replG(".$arr[0].")>переместить</a>"
						."&nbsp;/&nbsp;<a href=\"#\" onclick=\"deOk('mainiwspref.php?gopr=users&act=delOk&grp=$grp&id=".$arr[0]."'); return false;\">удалить</a>"
						."</td></tr>";			
		}
	}
	$massc.="<tr><td colspan=3></td><td><a href=\"#\" onclick=\"javascript:document.location='mainiwspref.php?gopr=users&act=add&grp=$grp'\">добавить пользователя</a></td></tr>";
	}
	$massc.="</table>";
$massc.="<script><!--\n
   function delG(){
		var ms = \"Внимание!\\n\\nПри удалении группы, будут удалены все\\nподченненые учетные записи пользователей.\\n\\nВы действительно хотите удалить группу?           \";
		if(confirm(ms)) document.location='mainiwspref.php?gopr=users&act=delG&grp=".$grp."';	
	 }
   function addG(){
		nme = showModalDialog(\"fnciws/users/dialog.php?act=addG\",null,\"dialogWidth:330px; dialogHeight:120px; status:no;\");
		if (nme != null)
				document.location='mainiwspref.php?gopr=users&act=addG&grp=".$grp."&nme='+ nme;
	 }
   function replG(ds){
		gr = showModalDialog(\"fnciws/users/dialog.php?act=replG\",null,\"dialogWidth:330px; dialogHeight:120px; status:no;\");
		if (gr != null)
				document.location='mainiwspref.php?gopr=users&act=replG&grp=".$grp."&gr='+ gr +'&id='+ ds;
	 }
	//--></script>";
return $massc;
}

function fOk(){
global $act,$mainadvar,$lgn,$passd,$id,$atbl,$fieldnm,$grp,$gfld,$gtbl,$nme,$fio,$knt,$gr;
switch ($act){

 	case "addG":
		$nme=trim($nme);	
		if(!empty($nme)){
			$nme=substr($nme,0,30);
			$sqlq="select count(".$gfld['did'].") from ".$gtbl." where ".$gfld['nme']."='$nme'";
			list($i)=mysql_fetch_row(mysql_query($sqlq));
			if($i){
				header("location: ?gopr=users&err=2&grp=$grp");
				break;
			}
			if(!mysql_query("insert into $gtbl (".$gfld['nme'].") values ('$nme')")){
				header("location: ?gopr=users&err=1&grp=$grp");
				break;
			}else{
				list($grp)=mysql_fetch_row(mysql_query("select ".$gfld['did']." from $gtbl where ".$gfld['nme']."='$nme'"));
				header("location: ?gopr=users&grp=$grp");
				break;
			}
		}
	break;

 	case "delG":
		mysql_query("delete from ".$atbl." where ".$fieldnm['group']."=$grp");
		if(!mysql_query("delete from ".$gtbl." where ".$gfld['did']."=$grp")){
			header("location: ?gopr=users&err=1&grp=$grp");
			break;
		} else {
			header("location: ?gopr=users");
			break;
		}
	break;

 	case "delOk":
		if(!(mysql_query("delete from ".$atbl." where ".$fieldnm['did']."=$id"))){
			header("location: ?gopr=users&err=1&grp=$grp");
			break;
		}else{
			header("location: ?gopr=users&grp=$grp");
			break;
		}
	break;
	
	case "addOk":
		$passd[0]=trim($passd[0]);
		$passd[1]=trim($passd[1]);
		$lgn=trim($lgn);
		if(empty($lgn) || preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $lgn) || empty($passd[0]) || empty($passd[1]) || strlen($passd[0])<6 || $passd[0]!=$passd[1]){
			header("location: ?gopr=users&act=add&err=3&grp=$grp&fio=$fio&knt=$knt");
			break;
		}
		$sqlq="select count(".$fieldnm['did'].") from ".$atbl." where ".$fieldnm['login']."='$lgn'";
		list($i)=mysql_fetch_row(mysql_query($sqlq));

		if($i>=1){
			header("location: ?gopr=users&act=add&err=2&grp=$grp&fio=$fio&knt=$knt");
			break;
		}

		$pwdr=crypt($passd[0],$mainadvar['pwdshifr']);
		$mainadvar['pwdshifr']="";
		$lgn=substr($lgn,0,30);
		$fio=trim($fio);
		$knt=trim($knt);
		$fio=substr($fio,0,250);
		$knt=substr($knt,0,150);
		$sqlq="insert into ".$atbl." (".$fieldnm['login'].",".$fieldnm['password'].",".$fieldnm['group'].",".$fieldnm['nme'].",".$fieldnm['eml'].") values ('$lgn','".$pwdr."',$grp,'$fio','$knt')";

		if(!(mysql_query($sqlq))){
			header("location: ?gopr=users&act=add&lgn=$lgn&fio=$fio&knt=$knt&err=1&grp=$grp");
			break;
		} else {
			header("location: ?gopr=users&grp=$grp");
			break;
		}
	break;

	case "edtOk":
		$passd[0]=trim($passd[0]);
		$passd[1]=trim($passd[1]);
		$lgn=trim($lgn);
		if(empty($lgn) || preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $lgn) || empty($passd[0]) || empty($passd[1]) || strlen($passd[0])<6 || $passd[0]!=$passd[1]){
			header("location: ?gopr=users&act=edt&err=3&grp=$grp");
			break;
		}
		$sqlq="select count(".$fieldnm['did'].") from ".$atbl." where ".$fieldnm['login']."='$lgn' and ".$fieldnm['did']."<>$id";
		list($i)=mysql_fetch_row(mysql_query($sqlq));

		if($i>=1){
			header("location: ?gopr=users&act=edt&err=2&grp=$grp");
			break;
		}

		$pwdr=crypt($passd[0],$mainadvar['pwdshifr']);
		$mainadvar['pwdshifr']="";
		$lgn=substr($lgn,0,30);
		$sqlq="update ".$atbl." set ".$fieldnm["login"]."='$lgn',".$fieldnm["password"]."='$pwdr',".$fieldnm["nme"]."='$fio',".$fieldnm["eml"]."='$knt' where ".$fieldnm['did']."=$id";

		if(!(mysql_query($sqlq))){
			header("location: ?gopr=users&act=edt&lgn=$lgn&err=1&grp=$grp");
			break;
		} else {
			header("location: ?gopr=users&grp=$grp");
			break;
		}
	break;

	case "replG":
		$sqlq="update ".$atbl." set ".$fieldnm["group"]."=$gr where ".$fieldnm['did']."=$id";

		if(!(mysql_query($sqlq))){
			header("location: ?gopr=users&err=3&grp=$grp");
			break;
		} else {
			header("location: ?gopr=users&grp=$grp");
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
unset($atbl,$fieldnm,$passd,$pwdr,$sl,$dst);
?>
