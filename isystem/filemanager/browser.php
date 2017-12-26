<?php

unset($mainadvar);
session_start();
session_register("mainadvar");


if(!isset($mainadvar) || empty($mainadvar['ath']) || $mainadvar['ath']!="avtores") { 
	header("Location: ../unautor.html");
	return;
}

if(!$mainadvar['sadm']){
	if(!ereg("filemanager",$mainadvar['prf'])){
		echo "<html><head>"
					."<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">"
					."</head>"
					."<body><center><b>Извините данная функция Вам недоступна!</b></center></body></html>";
		return;
	}
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body BGCOLOR=#E9E9E9>
<script>
<!--		
window.focus();

function filelist_OnLoad(dis){
if(dis){
	browserupload.savefile.disabled=true;
	browserupload.createcat.disabled=true;
	browserupload.createfile.disabled=true;
}else{
	browserupload.savefile.disabled=false;
	browserupload.createcat.disabled=false;
	browserupload.createfile.disabled=false;
}
}

function creatcat(){
var arr=null;
arr=showModalDialog('filemanager/dialog.php?evtype=createdir','', 'dialogWidth:400px; dialogHeight:135px; status:no');
if(arr!=null){
	document.filelist.location='filemanager/filemanager.php?evtpe=createdir&catname='+arr;
}
}

//-->
</script>
<title>Файловый менеджер</title>
<form action="filemanager/browser.php" method="post" enctype="multipart/form-data" name="browserupload">
<table cellpadding="1" cellspacing="0" border="0" height=100% width="100%">
<tr height=100%>
<td bgcolor=#C0C0C0>
<iframe name="filelist" src="filemanager/filemanager.php" width="100%" height="100%" frameborder=0 marginwidth=5 marginheight=5></iframe>
</td>
<td align="center" valign=top width=150>
	<input type="button" name="savefile" value=" Загрузить файл " onclick="document.filelist.uploadfl()">
	<br>	<br>
	<input type="button" name="createcat" value="Создать каталог" onclick="creatcat()">
	<br>	<br>
	<input type="button" name="createfile" value="  Создать файл   " onclick="document.filelist.newfl()">
</td>
</tr>
</table>
</form>
</body>
</html>
