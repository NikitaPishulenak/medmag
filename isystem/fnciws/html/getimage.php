<?php

include('../../exit.php');

include("../../inc/config.inc.php");

function doeUpload($field_name,$new_name,$storage){ 

$filename=$_FILES[$field_name]['name']; 
$tmpfname=$_FILES[$field_name]['tmp_name']; 
$filename = ereg_replace("[^a-z0-9._]", "", 
            str_replace(" ", "_", 
            str_replace("%20", "_", strtolower($filename)))); 

if ($filename="") die("Недопустимое имя файла. Только английские буквы, цифры и '_'!"); 


$filepath=$storage; 
if($new_name){
   $filepath=$docRoot.$filepath."/".$new_name; 
}else{
   $filepath=$docRoot.$filepath."/".$filename; 
}


if (is_uploaded_file($tmpfname)) { 

   copy($tmpfname, $filepath) 
   or die("Ошибка закачки файла: ".$filename); 
  } 
} 
 


if($saveimg=="Y"){ doeUpload('imagefile',$newfilename,$path); }

?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<style>
td {font-family:Arial; font-size:11px;}
input.Ok {border: 1px #000000 solid; color: #ffffff;background-color: #6D6D6D; font-size:7pt;}
input {border: 1px #6C6C6C solid; font-size:7pt;}
hr {color:#6C6C6C; height:1pt}
</style>
</head>
<body onKeyPress="KeyPress()" bgcolor=buttonface>
<script>
<!--     
var urlHost = "<?php echo $hostName; ?>";

window.focus();

function OnNameChange()
{
   if(imageupload.newfilename.value.length>0)
      imageupload.save.disabled=false;
   else
      imageupload.save.disabled=true;
}

function NewFileName()
{
   var str_filename;
   var filename;
   var str_file = document.imageupload.imagefile.value;
   filename = str_file.substr(str_file.lastIndexOf("\\")+1);
   document.imageupload.newfilename.value = filename;
   imageupload.preview.src=document.imageupload.imagefile.value;
   hiddenimg.src=document.imageupload.imagefile.value;
   OnNameChange();
}

function KeyPress()
{
   if(window.event.keyCode == 27)
      window.close();
}

function filelist_OnLoad(strDir)
{
   window.opener.strPath=strDir;
   imageupload.url.value=strDir+"/";
   imageupload.path.value=strDir;
   imageupload.bSelect.disabled=true;
}

function filelist_OnFileSelect(strPath)
{
   imageupload.url.value=strPath;
   imageupload.preview.src=strPath;
   imageupload.bSelect.disabled=false;
   hiddenimg.src=strPath;
}

function SelectImage(fname)
{
   opener.InsertImageM(urlHost+fname);
   window.close();
}

function ShowSize(obj)
{
   imageupload.imgwidth.value=obj.width;
   imageupload.imgheight.value=obj.height;
   var W=obj.width, H=obj.height;
   if(W>100)
   {
      H=H*((100.0)/W);
      W=100;
   }
   
   if(H>100)
   {
      W=W*((100.0)/H);
      H=100;
   }


   imageupload.preview.width=W;
   imageupload.preview.height=H;
}
//-->
</script>
<title>Загрузка картинок</title><img id=hiddenimg style="visibility:hidden; position: absolute; left:-1000; top: -1000px;" onerror="badimg = true;" onload="ShowSize(this)">
<form action="getimage.php" method="post" enctype="multipart/form-data" name="imageupload">
<input type="hidden" name="saveimg" value="Y">
<table cellpadding="1" cellspacing="0" border="0" width="100%">
<tr>
<td width="0%" bgcolor=#6C6C6C>
<iframe name="filelist" src="fileman.php" width="450" height="250" frameborder=0 marginwidth=1 marginheight=1></iframe>
</td>
<td width="2%">&nbsp;</td>
<td width="100%" align="center" valign=top>
   <table cellpadding="0" cellspacing="0" border="0">
      <tr>
         <td align="right">Ширина:&nbsp;</td>
         <td><input type="text" size="3" name=imgwidth style="border: 0px; BACKGROUND-COLOR:buttonface"></td>
      </tr>
      <tr>
         <td align="right">Высота:&nbsp;</td>
         <td><input type="text" size="3" name=imgheight style="border: 0px; BACKGROUND-COLOR:buttonface"></td>
      </tr>
   </table>
<br>
   <img src="images/pv.gif" width="100" name="preview">
</td>
</tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td colspan="2" align="left"><b>Выбрать картинку</b></td>
   </tr>
   <tr>
      <td width="0%" align="right">&nbsp;URL:&nbsp;</td>
      <td width="100%"><input type="text" name="url" size="40" value=""><img src="images/pv.gif" width="2" height="1" border=0 alt=""><input class="typesubmit" type="button" name="bSelect" onclick="SelectImage(imageupload.url.value)" value="Выбрать картинку"></td>
   </tr>
   <tr>
      <td colspan="2" nowrap align="center"></td>
   </tr>
   <tr>
      <td colspan="2" nowrap align="left"><hr><b>Загрузка картинки с компьютера в текущий каталог сервера</b></td>
   </tr>
   <tr>
      <td nowrap align="right">&nbsp;Файл:&nbsp;</td>
      <td><input type="file" name="imagefile" size="20" onchange="NewFileName();"></td>
   </tr>
   <tr>
      <td nowrap align="right">&nbsp;Новое имя файла:&nbsp;</td>
      <td><input type="text" name="newfilename" size="20" onchange="OnNameChange();"><img src="/images/pv.gif" width="2" height="1" border=0 alt=""><input type="submit" name="save" value="Загрузить" DISABLED></td>
   </tr>
   <tr>
      <td colspan="2" nowrap align="center"><input type="hidden" name="path" value=""></td>
   </tr>
   <tr>
      <td colspan="2" nowrap align="center"><br></td>
   </tr>
   <tr>
      <td colspan="2" nowrap align="right"><input type="button" value="Отмена" onClick="window.close();"></td>
   </tr>
</table>
</form>
</body>
</html>
