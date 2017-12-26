<?php

unset($mainadvar);
session_start();
session_register("mainadvar");


if(!isset($mainadvar) || empty($mainadvar['ath']) || $mainadvar['ath']!="avtores") { 
	header("Location: ../unautor.html");
	return;
}

include("../inc/config.inc.php");

function display_size($file){
    $file_size = filesize($file);
    if($file_size >= 1073741824) {
        $file_size = round($file_size / 1073741824 * 100) / 100 . " Gb";
    } elseif($file_size >= 1048576) {
        $file_size = round($file_size / 1048576 * 100) / 100 . " Mb";
    } elseif($file_size >= 1024) {
        $file_size = round($file_size / 1024 * 100) / 100 . " Kb";
    } else {
        $file_size = $file_size . " b";
    }
    return $file_size;
}

function displaydir() {
    global $mainadvar,$udir;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
<script><!--
function DoEvent(str)
{
try
{
	eval("parent."+this.name+"_"+str);
}
catch(e){}
}
<?php
if(ereg("isystem",$mainadvar["fmnbasedir"])){
	$lt=0;
?>

DoEvent("OnLoad(1)");

<?php
}else{
	$lt=1;
?>
DoEvent("OnLoad(0)");
<?php
}
?>
//--></script>
<?php
	 chdir($mainadvar["fmnbasedir"]);
    $handle=opendir(".");
    while ($file = readdir($handle)) {
		if(is_dir($file) && $file!="isystem" && $file!="class" && $file!="FilesForDownload" && $file!="DocsForDownload")	$dirlist[] = $file;
		if(is_file($file) && $file!="index.php") $filelist[] = $file;
    }
    closedir($handle);
if($lt){
?>
<script><!--
function delOk(urli,tp,nmf){ 
var msg
if(tp=="file"){
	msg="Вы действительно хотите удалить файл '"+nmf+"' ?     ";
} else {
	msg="Вы действительно хотите удалить каталог '"+nmf+"' ?     ";
}
if(confirm(msg)){ document.location=urli; }
}

function editcde(sFile){
var arr=null;
arr=showModalDialog('filemanager.php?modl=edt&sfile='+sFile,'', 'dialogWidth:650px; dialogHeight:500px; resizable:yes; scroll:yes; status:no');
if(arr!=null){
	document.edt.fle.value = sFile;
	document.edt.fcode.value = arr;
	edt.submit();
}
}

function newfl(){
var arr=null;
arr=showModalDialog('dialog.php?evtype=createfile','', 'dialogWidth:650px; dialogHeight:500px; resizable:yes; scroll:yes; status:no');
if(arr!=null){
	document.newf.nme.value = arr["name"];
	document.newf.ncode.value = arr["content"];
	newf.submit();
}
}

function uploadfl(){
	window.open('filemanager/dialog.php?evtype=uploadfile&fmnbasedir=<?php echo $mainadvar['fmnbasedir']; ?>','', 'width=400px; height=135px; resizable=no, scrollbars=no, status=no, edge=raised, left='+Math.floor((screen.width - 400)/2)+',top='+Math.floor((screen.height - 135)/2));
}

function rnm(sFile,cPr){
var arr=null;
if(cPr){
	var hrfdialog='dialog.php?evtype=copynme&sfile='+sFile;
	var hrfevent='filemanager.php?evtpe=copynme&oldp='+sFile;
} else {
	var hrfdialog='dialog.php?evtype=rnm&sfile='+sFile;
	var hrfevent='filemanager.php?evtpe=rnme&oldp='+sFile;
}
arr=showModalDialog(hrfdialog,'', 'dialogWidth:400px; dialogHeight:140px; status:no');
if(arr!=null){
	document.location=hrfevent+'&newp='+arr;
}
}

//--></script>

<?php
}
    echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";
	 echo "<tr><td colspan=5> ".$mainadvar['retpath']."<hr></td></tr>";
    echo "<tr>";
    echo "<td class=usr align=\"center\" width=25>Тип</td>";
    echo "<td class=usr align=\"center\" width=100%>Имя</td>";
    echo "<td class=usr align=\"center\"><nobr>Размер файла</nobr></td>";
    echo "<td class=usr align=\"center\">Загружен</td>";
    echo "<td class=usr align=\"center\">Действия</td>";
    echo "</tr>";
    if($dirlist) {
		asort($dirlist);
		while (list ($key, $file) = each ($dirlist)) {
			if (!($file == ".")) {
				$filename=$file;
				$fileurl=rawurlencode($file);
				$lastchanged = filectime($filename);
				$changeddate = date("d.m.Y H:i:s", $lastchanged);
				echo "<tr class=menu>\n";
				if($file == "..") {
			    echo "<td bgcolor=#E6E6E6><a href=\"filemanager.php?udir=1\"><img src=\"images/parent.gif\" alt=\"Вверх\" border=\"0\"></a></td>"
						."<td bgcolor=#E6E6E6><b><a href=\"filemanager.php?udir=1\">[..]</a></b></td>"
						."<td bgcolor=#E6E6E6><b>{DIR}</b></a></td>"
						."<td colspan=2 bgcolor=#E6E6E6></td>";
				} else {
			    echo "<td align=\"center\"><a href=\"filemanager.php?wdir=$fileurl\"><img src=\"images/folder.gif\" alt=\"Войти в каталог $file\" border=\"0\"></a></td>\n";
			    echo "<td><a href=\"filemanager.php?wdir=$fileurl\">".htmlspecialchars($file)."</a></td>";
			    echo "<td><b>{DIR}</b></td>";
			    echo "<td align=\"center\"><nobr>".$changeddate."</nobr></td>";
				 echo "<td><nobr>";
				 if($lt && $file!="isystem"){
				    echo " [ <a href=\"#\" onclick=\"rnm('".$mainadvar["fmnbasedir"]."/".$fileurl."',0); return false;\">переимен..</a> ]"
//					 ." [ <a href=\"#\" onclick=\"rnm('".$mainadvar["fmnbasedir"]."/".$fileurl."',1); return false;\">скопир..</a> ]"
					 ." [ <a href=\"#\" onclick=\"delOk('filemanager.php?evtpe=deldir&fle=$fileurl','dir','$file'); return false;\">удалить</a> ]";
				 }
				 echo "</td></nobr>";
				}

		    }
		}
   }
   if($filelist) {
		asort($filelist);
		while (list ($key, $file) = each ($filelist)) {
		    if (ereg(".gif|.jpg|.png|.ico",$file)) {
				$icon = "<IMG src=\"images/fimage.gif\" alt=\"Рисунок\" border=\"0\">";
				$image = "1";
				$fview = "0";
		    } elseif (ereg(".txt",$file)) {
				$icon = "<IMG src=\"images/ftext.gif\" alt=\"Текстовый файл\" border=\"0\">";
				$image = "0";
				$fview = "1";
		    } elseif (ereg(".phps|.php|.php2|.php3|.php4|.asp|.asa|.cgi|.pl|.shtml|.phtml",$file)) {
				$icon = "<IMG src=\"images/webscript.gif\" alt=\"Файл скриптов\" border=\"0\">";
				$image = "0";
				$fview = "1";
		    } elseif (ereg(".htaccess",$file)) {
				$icon = "<IMG src=\"images/security.gif\" alt=\"Файл доступа\" border=\"0\">" ;
				$image = "0";
				$fview = "1";
		    } elseif (ereg(".html|.htm",$file))	{
				$icon = "<IMG src=\"images/webpage.gif\" alt=\"html файл\" border=\"0\">";
				$image = "0";
				$fview = "1";
		    } elseif (ereg(".css",$file))	{
				$icon = "<IMG src=\"images/fcss.gif\" alt=\"Файл таблицы стилей\" border=\"0\">";
				$image = "0";
				$fview = "1";
		    } elseif (ereg(".js",$file))	{
				$icon = "<IMG src=\"images/fjs.gif\" alt=\"Файл сценариев\" border=\"0\">";
				$image = "0";
				$fview = "1";
		    } elseif (ereg(".sql|.SQL",$file))	{
				$icon = "<IMG src=\"images/sql.gif\" alt=\"Файл копии базы данных SQL\" border=\"0\">";
				$image = "0";
				$fview = "1";
		    } elseif (ereg(".xls|.xlsx",$file))	{
				$icon = "<IMG src=\"images/fxls.gif\" alt=\"Файл Microsoft Exсel\" border=\"0\">";
				$image = "0";
				$fview = "0";
		    } elseif (ereg(".pdf",$file))	{
				$icon = "<IMG src=\"images/fpdf.gif\" alt=\"Файл Acrobat Reader\" border=\"0\">";
				$image = "0";
				$fview = "0";
		    } elseif (ereg(".doc|.rtf|.docx",$file))	{
				$icon = "<IMG src=\"images/fwrd.gif\" alt=\"Файл Microsoft Word\" border=\"0\">";
				$image = "0";
				$fview = "0";
		    } elseif (ereg(".zip|.rar|.arg|.gz",$file))	{
				$icon = "<IMG src=\"images/farc.gif\" alt=\"Файл архива\" border=\"0\">";
				$image = "0";
				$fview = "0";
		    } else { 
				$icon = "<IMG src=\"images/fnone.gif\" alt=\"Неизвестный\" border=\"0\">";
				$image = "0";
				$fview = "0";
		    }
		    $filename=$file;
		    $fileurl=$mainadvar["fmnbasedir"]."/".$file;
		    $lastchanged = filectime($filename);
		    $changeddate = date("d.m.Y H:i:s", $lastchanged);
			  $flsize=display_size($filename);	
		    echo "<tr class=menu1>";
		    echo "<td align=\"center\">";
			if(!$image && $fview){	

       	 echo "<a href=\"#\" onclick=\"window.open('dialog.php?evtype=show&sfile=$fileurl', '', 'scrollbars=yes,resizable=yes,width=600,height=450'); return false;\">";
		    echo "$icon</a></td>\n";
		    echo "<td><a href=\"#\" onclick=\"window.open('dialog.php?evtype=show&sfile=$fileurl', '', 'scrollbars=yes,resizable=yes,width=600,height=450'); return false;\">$file</a></td>\n";

			}elseif($image && !$fview){

       	 echo "<a href=\"#\" onclick=\"window.open('dialog.php?evtype=imgshow&sfile=$fileurl&filesz=$flsize', '', 'scrollbars=yes,resizable=yes,width=600,height=450'); return false;\">";
		    echo "$icon</a></td>\n";
		    echo "<td><a href=\"#\" onclick=\"window.open('dialog.php?evtype=imgshow&sfile=$fileurl&filesz=$flsize', '', 'scrollbars=yes,resizable=yes,width=600,height=450'); return false;\">$file</a></td>\n";

			}elseif(!$image && !$fview){

       	 echo "$icon</td><td>$file</td>\n";

			}
		    echo "<td>".$flsize."</td>";
		    echo "<td align=\"center\"><nobr>".$changeddate."</nobr></td>";
				 echo "<td><nobr>";
				 if($lt){
					 echo " [ <a href=\"#\" onclick=\"rnm('$fileurl',0); return false;\">переимен..</a> ]"
					 ." [ <a href=\"#\" onclick=\"delOk('filemanager.php?evtpe=delfile&fle=$file','file','$file'); return false;\">удалить</a> ]"
					 ." [ <a href=\"#\" onclick=\"rnm('$fileurl',1); return false;\">скопир..</a> ]";
					 if($fview) echo " [ <a href=\"#\" onclick=\"editcde('$fileurl'); return false;\">редакт..</a> ]";
				 }
				 echo "</td></nobr>";
		}
	}
	 echo "</tr>";
	 echo "<tr><td colspan=6><hr> ".$mainadvar['retpath']."</td></tr>";
	 echo "</table>";
	 echo "<form name=\"edt\" action=\"filemanager.php\" method=post>";	 
	 echo "<input type=hidden name=evtpe value=\"editfile\">";
	 echo "<input type=hidden name=fle value=\"\">";
	 echo "<input type=hidden name=fcode value=\"\">";
	 echo "</form>";
	 echo "<form name=\"newf\" action=\"filemanager.php\" method=post>";	 
	 echo "<input type=hidden name=evtpe value=\"newfile\">";
	 echo "<input type=hidden name=nme value=\"\">";
	 echo "<input type=hidden name=ncode value=\"\">";
	 echo "</form>";
    echo "</body></html>";
}

function shmodal(){
	global $sfile;
	$surl=substr($sfile,strrpos($sfile,"/")-strlen($sfile)+1);
	$fp=fopen($sfile,"r");
	$contents=htmlspecialchars(fread($fp,filesize($sfile)));

?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../style.css">
<title>Редактирование файла <?php echo $surl; ?></title>
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
	var strDoc;
	if(fcode.value) strDoc = fcode.value;
	window.returnValue = strDoc;
	window.close();
// -->
</script>
<body onKeyPress="KeyPress()" bgcolor="buttonface">
<table cellspacing=5 align="center" width=100% height=100%>
<tr>
	<td align=center>Редактирование файла: [ <b><?php echo $surl; ?></b> ]
</td></tr>
<tr>
	<td height=100% align=center><textarea name="fcode"><?php echo $contents; ?></textarea></td></tr>
<tr>
	<td align=center>
<input ID=Ok TYPE=SUBMIT value="     Да    ">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
<br><br></td></tr></table>
</body>
</html>
<?php
}

if(isset($modl) && $modl=="edt"){
	shmodal();
} else {
if($wdir) {
	$drname=$mainadvar['fmnbasedir']."/".$wdir;
	if(is_dir($drname)){
		$mainadvar['fmnbasedir'] = $drname;
		$udir=1;
		$mainadvar['retpath'].="\ <a href=\"filemanager.php?pth=".$drname."\">$wdir</a> ";
	}
}elseif($udir) {
	if($mainadvar['fmnbasedir']!=$docRoot)
		$mainadvar['fmnbasedir']=substr($mainadvar['fmnbasedir'],0,strrpos($mainadvar['fmnbasedir'],"/"));	
		$mainadvar['retpath']=substr($mainadvar['retpath'],0,strrpos($mainadvar['retpath'],"\\"));
}elseif($pth && is_dir($pth)){
	$mainadvar['fmnbasedir'] = $pth;
	$rt=substr(strrchr($pth,"/"),1);
	$mainadvar['retpath']=substr($mainadvar['retpath'],0,strpos($mainadvar['retpath'],$rt."<")+strlen($rt)+5);	
}elseif(isset($evtpe) && $evtpe){
	switch($evtpe){
	case "deldir":
		if(isset($fle) && $fle){
			if(is_dir($mainadvar['fmnbasedir']."/".$fle))
				error_reporting(1);
				if(!rmdir($mainadvar['fmnbasedir']."/".$fle))
					echo "<pre><b>Ошибка:</b> невозможно удалить каталог. Каталог либо не пуст или нет прав на удаление.</pre>";

//				error_reporting(7);
		}
	break;
	case "delfile":
		if(isset($fle) && $fle){
			if(is_file($mainadvar['fmnbasedir']."/".$fle))
				error_reporting(1);
				if(!unlink($mainadvar['fmnbasedir']."/".$fle))
					echo "<pre><b>Ошибка:</b> невозможно удалить файл.</pre>";
		}
	break;
	case "editfile":
		if(isset($fle) && $fle){
			if(file_exists($fle)){
				$fp=fopen($fle,"w");
    			fputs($fp,stripslashes($fcode));
    			fclose($fp);
			}
		}
	case "rnme":
		if(isset($oldp) && $oldp && isset($newp) && $newp){
			if(!file_exists($newp)){
				error_reporting(1);
				if(!rename($oldp,$newp))
					echo "<pre><b>Ошибка:</b> невозможно переместить или переименовать файловый объект.</pre>";
			}			
		}
	break;
	case "copynme":
		if(isset($oldp) && $oldp && isset($newp) && $newp){
			if(!file_exists($newp)){
				error_reporting(1);
				if(!copy($oldp,$newp))
					echo "<pre><b>Ошибка:</b> невозможно скопировать файл.</pre>";
			} else {
					echo "<pre><b>Ошибка:</b> невозможно скопировать. Файл с таким именем уже есть.</pre>";
			}
		}
	break;
	case "createdir":
		if(isset($catname) && $catname){
			if(!is_dir($mainadvar['fmnbasedir']."/".$catname)){
				error_reporting(1);
				if(!mkdir($mainadvar['fmnbasedir']."/".$catname, 0755))
					echo "<pre><b>Ошибка:</b> невозможно создать каталог.</pre>";
			} else {
					echo "<pre><b>Ошибка:</b> каталог с таким именем уже есть.</pre>";
			}
		}
	break;
	case "newfile":
		if(isset($nme) && $nme && isset($ncode) && $ncode){
			if(!file_exists($mainadvar['fmnbasedir']."/".$nme)){
				error_reporting(1);
				$fp=fopen($mainadvar['fmnbasedir']."/".$nme,"w");
				if($fp){
					fputs($fp,stripslashes($ncode));
					fclose($fp);
				} else {
					echo "<pre><b>Ошибка:</b> невозможно создать файл.</pre>";
				}
			} else {
					echo "<pre><b>Ошибка:</b> файл с именем '$nme' уже есть.</pre>";
			}
		}
	break;
	}

}
if($mainadvar['fmnbasedir']==$docRoot)
	$mainadvar['retpath']="<a href=\"filemanager.php?pth=".$docRoot."\">Корень</a> :";
displaydir();
}
?>

