<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link rel="stylesheet" type="text/css" href="../style.css">
<?php

include("../inc/config.inc.php");

$surl=substr($sfile,strrpos($sfile,"/")-strlen($sfile)+1);
switch($evtype){
case "show":
	echo "<title>�������� ����� $surl</title>"
		."</head>"		
		."<body>"
		."<h5>�������� �����: [ $surl ]</h5><hr>";
		show_source($sfile);
break;
case "imgshow":
	echo "<title>�������� ������� $surl</title>"
		."</head>"		
		."<body>"
		."<h5>�������� �������: [ $surl ]</h5><pre>"
		."������ �����: <b>".$filesz."</b>";
		$wdh=getimagesize($sfile);
		if($wdh)
			echo "&nbsp;&nbsp;&nbsp; ������: <b>".$wdh[0]."px</b> ������: <b>".$wdh[1]."px</b>";

	echo "</pre><hr>";
	echo "<center><img src=\"".ereg_replace($docRoot,"",$sfile)."\" alt=\"�������: $surl\"></center>";
break;
case "rnm":
	$surl=ereg_replace($docRoot,"",$sfile);
?>
<title>��������../��������.. <?php echo $surl; ?></title>
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
		alert("�� �� ������� ��� �����/�������� ��� ���� ��� �����������!     ");
		nme.focus();
	}
// -->
</script>
<body onKeyPress="KeyPress()" bgcolor="buttonface">
<table cellspacing=5 align="center" width=100% height=100%>
<tr>
	<td>�������������/�����������<br>[ <b><?php echo $surl; ?></b> ] �:
</td></tr>
<tr>
	<td height=100% align=center><input name="nme" value="<?php echo $surl; ?>" style="width:95%"></td></tr>
<tr>
	<td align=center>
<input ID=Ok TYPE=SUBMIT value="     ��    ">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="������">
<br><br></td></tr></table>
<?php
break;

case "copynme":

	$surl=ereg_replace($docRoot,"",$sfile);
?>
<title>����������� ���� <?php echo $surl; ?></title>
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
		alert("�� �� ������� ��� ����� ��� ���� ��� �����������!     ");
		nme.focus();
	}
// -->
</script>
<body onKeyPress="KeyPress()" bgcolor="buttonface">
<table cellspacing=5 align="center" width=100% height=100%>
<tr>
	<td>����������� ���� <br>[ <b><?php echo $surl; ?></b> ] �:
</td></tr>
<tr>
	<td height=100% align=center><input name="nme" value="<?php echo $surl; ?>" style="width:95%"></td></tr>
<tr>
	<td align=center>
<input ID=Ok TYPE=SUBMIT value="     ��    ">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="������">
<br><br></td></tr></table>
<?php
break;

case "createdir":

?>
<title>������� �������</title>
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
		alert("�� �� ������� ��� ��������!     ");
		nme.focus();
	}
// -->
</script>
<body onKeyPress="KeyPress()" bgcolor="buttonface">
<table cellspacing=5 align="center" width=100% height=100%>
<tr>
	<td>������� ��� ��������:
</td></tr>
<tr>
	<td height=100% align=center><input name="nme" style="width:95%"></td></tr>
<tr>
	<td align=center>
<input ID=Ok TYPE=SUBMIT value="     ��    ">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="������">
<br><br></td></tr></table>
<?php
break;

case "createfile":
?>
<title>������� ����</title>
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
		alert ("������������ ������ ����� �����! �������� 'index.htm'          ");
		namefile.focus();
	}
// -->
</script>
<body onKeyPress="KeyPress()" bgcolor="buttonface">
<table cellspacing=5 align="center" width=100% height=100%>
<tr>
	<td align=center><b>������� ����</b>
</td></tr>
<tr>
	<td>������� ��� �����:</td></tr>
	<td><input name="namefile" size=60 value="*.*"></td></tr>
<tr>
<tr>
	<td>����� �����:</td></tr>
	<td height=100% align=center><textarea name="fcode"></textarea></td></tr>
<tr>
	<td align=center>
<input ID=Ok TYPE=SUBMIT value="     ��    ">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="������">
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
				 	$vr="<pre><b>������:</b> �� ������� ��������� ����.</pre>";
				} else {
					$vr="<pre>���� '<b>$fnme_name</b>' ������� ��������!</pre>";
				}
			} else {
			 	$vr="<pre><b>������:</b> ���� � ������ '$fnme_name' ��� ����.</pre>";
			}
?>
<title>�������� ����� � ������� �������</title>
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
<title>��������� ���� � ������� �������</title>
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
		alert("�� �� ������� ���� ��� ������� !     ");
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
	<td>�������� ����� � �������: <b><?php echo ereg_replace($docRoot,"",$fmnbasedir); ?></b>:
</td></tr>
<tr>
	<td height=100% align=center><input type="file" name="fnme" style="width:95%"></td></tr>
<tr>
	<td align=center>
<input name=Ok ID=Ok TYPE=BUTTON value="     ��    " DISABLED>&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="������">
<br><br></td></tr></form></table>
<?php
}
break;

}
?>
</body>
</html>
