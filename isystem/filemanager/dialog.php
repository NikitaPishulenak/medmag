<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link rel="stylesheet" type="text/css" href="../style.css">
<?php

include("../inc/config.inc.php");

$surl=substr($sfile,strrpos($sfile,"/")-strlen($sfile)+1);
switch($evtype){
case "show":
	echo "<title>Просмотр файла $surl</title>"
		."</head>"		
		."<body>"
		."<h5>Просмотр файла: [ $surl ]</h5><hr>";
		show_source($sfile);
break;
case "imgshow":
	echo "<title>Просмотр рисунка $surl</title>"
		."</head>"		
		."<body>"
		."<h5>Просмотр рисунка: [ $surl ]</h5><pre>"
		."Размер файла: <b>".$filesz."</b>";
		$wdh=getimagesize($sfile);
		if($wdh)
			echo "&nbsp;&nbsp;&nbsp; ширина: <b>".$wdh[0]."px</b> высота: <b>".$wdh[1]."px</b>";

	echo "</pre><hr>";
	echo "<center><img src=\"".ereg_replace($docRoot,"",$sfile)."\" alt=\"рисунок: $surl\"></center>";
break;
case "rnm":
	$surl=ereg_replace($docRoot,"",$sfile);
?>
<title>Переимен../перемест.. <?php echo $surl; ?></title>
</head>
<script><!--
function KeyPress()
{
	if(window.event.keyCode == 27)
		window.close();
}
//--></script>
<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
	var strDoc = nme.value;
	if(strDoc && strDoc!="/"){
		if(strDoc.charAt(0)!="/") strDoc='/'+strDoc
		window.returnValue = '<?php echo $docRoot; ?>' + strDoc;
		window.close();
	} else {
		alert("Вы не указали имя файла/каталога или путь для перемещения!     ");
		nme.focus();
	}
// -->
</script>
<body onKeyPress="KeyPress()" bgcolor="buttonface">
<table cellspacing=5 align="center" width=100% height=100%>
<tr>
	<td>Переименовать/переместить<br>[ <b><?php echo $surl; ?></b> ] в:
</td></tr>
<tr>
	<td height=100% align=center><input name="nme" value="<?php echo $surl; ?>" style="width:95%"></td></tr>
<tr>
	<td align=center>
<input ID=Ok TYPE=SUBMIT value="     Да    ">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
<br><br></td></tr></table>
<?php
break;

case "copynme":

	$surl=ereg_replace($docRoot,"",$sfile);
?>
<title>Скопировать файл <?php echo $surl; ?></title>
</head>
<script><!--
function KeyPress()
{
	if(window.event.keyCode == 27)
		window.close();
}
//--></script>
<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
	var strDoc = nme.value;
	if(strDoc && strDoc!="/"){
		if(strDoc.charAt(0)!="/") strDoc='/'+strDoc
		window.returnValue = '<?php echo $docRoot; ?>' + strDoc;
		window.close();
	} else {
		alert("Вы не указали имя файла или путь для копирования!     ");
		nme.focus();
	}
// -->
</script>
<body onKeyPress="KeyPress()" bgcolor="buttonface">
<table cellspacing=5 align="center" width=100% height=100%>
<tr>
	<td>Скопировать файл <br>[ <b><?php echo $surl; ?></b> ] в:
</td></tr>
<tr>
	<td height=100% align=center><input name="nme" value="<?php echo $surl; ?>" style="width:95%"></td></tr>
<tr>
	<td align=center>
<input ID=Ok TYPE=SUBMIT value="     Да    ">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
<br><br></td></tr></table>
<?php
break;

case "createdir":

?>
<title>Создать каталог</title>
</head>
<script><!--
function KeyPress()
{
	if(window.event.keyCode == 27)
		window.close();
}
//--></script>
<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
	var strDoc = nme.value;
	if(strDoc && strDoc!="/"){
		window.returnValue = strDoc.replace(/\//g,"");
		window.close();
	} else {
		alert("Вы не указали имя каталога!     ");
		nme.focus();
	}
// -->
</script>
<body onKeyPress="KeyPress()" bgcolor="buttonface">
<table cellspacing=5 align="center" width=100% height=100%>
<tr>
	<td>Введите имя каталога:
</td></tr>
<tr>
	<td height=100% align=center><input name="nme" style="width:95%"></td></tr>
<tr>
	<td align=center>
<input ID=Ok TYPE=SUBMIT value="     Да    ">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
<br><br></td></tr></table>
<?php
break;

case "createfile":
?>
<title>Создать файл</title>
<STYLE>
textarea {border: 1px #6C6C6C solid; font-size:8pt; width:100%; height:100%; font-family:'Courier New,Arial'}
</STYLE>
</head>
<script><!--
function KeyPress()
{
	if(window.event.keyCode == 27)
		window.close();
}
//--></script>
<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
	var arr = Array();
	if(namefile.value && namefile.value!="*.*"){
		arr["name"] = namefile.value;		
		arr["content"] = fcode.value;
		window.returnValue = arr;
		window.close();
	}else{
		alert ("Неправильный формат имени файла! Например 'index.htm'          ");
		namefile.focus();
	}
// -->
</script>
<body onKeyPress="KeyPress()" bgcolor="buttonface">
<table cellspacing=5 align="center" width=100% height=100%>
<tr>
	<td align=center><b>Создать файл</b>
</td></tr>
<tr>
	<td>Введите имя файла:</td></tr>
	<td><input name="namefile" size=60 value="*.*"></td></tr>
<tr>
<tr>
	<td>Текст файла:</td></tr>
	<td height=100% align=center><textarea name="fcode"></textarea></td></tr>
<tr>
	<td align=center>
<input ID=Ok TYPE=SUBMIT value="     Да    ">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
<br><br></td></tr></table>
</body>
</html>
<?php
break;

case "uploadfile":
	if(isset($fnme) && $fnme){
			if(!file_exists($fmnbasedir."/".$fnme_name)){
				error_reporting(1);
				if(!copy($fnme,$fmnbasedir."/".$fnme_name)){
				 	$vr="<pre><b>Ошибка:</b> не удалось загрузить файл.</pre>";
				} else {
					$vr="<pre>Файл '<b>$fnme_name</b>' успешно загружен!</pre>";
				}
			} else {
			 	$vr="<pre><b>Ошибка:</b> файл с именем '$fnme_name' уже есть.</pre>";
			}
?>
<title>Загрузка файла в текущий каталог</title>
</head>
<body bgcolor="buttonface">
<?php echo $vr; ?>
<script><!--
if (typeof(window.opener) == "object")
   window.opener.location="filemanager.php";

setTimeout("window.close()",2000);
//--></script>
<?php

} else {

?>
<title>Загрузить файл в текущий каталог</title>
</head>
<script><!--
function KeyPress()
{
	if(window.event.keyCode == 27)
		window.close();
}
//--></script>

<SCRIPT LANGUAGE=JavaScript FOR=fnme EVENT=onchange>
<!--
	if(fnme.value){
		uplf.Ok.disabled=false;
	} else {
		uplf.Ok.disabled=true;
	}
// -->
</script>

<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
	var strDoc = uplf.fnme.value;
	if(strDoc && strDoc!="/"){
		uplf.submit();	
//		window.close();	
	} else {
		alert("Вы не выбрали файл для закачки !     ");
		uplf.fnme.focus();
	}
// -->
</script>
<body onKeyPress="KeyPress()" bgcolor="buttonface">
<form name="uplf" action="dialog.php" method="post" enctype="multipart/form-data">
<input type=hidden name=evtype value="uploadfile">
<input type=hidden name=fmnbasedir value="<?php echo $fmnbasedir; ?>">
<table cellspacing=5 align="center" width=100% height=100%>
<tr>
	<td>Загрузка файла в каталог: <b><?php echo ereg_replace($docRoot,"",$fmnbasedir); ?></b>:
</td></tr>
<tr>
	<td height=100% align=center><input type="file" name="fnme" style="width:95%"></td></tr>
<tr>
	<td align=center>
<input name=Ok ID=Ok TYPE=BUTTON value="     Да    " DISABLED>&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
<br><br></td></tr></form></table>
<?php
}
break;

}
?>
</body>
</html>
