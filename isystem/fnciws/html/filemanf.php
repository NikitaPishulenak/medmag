<?php

session_start();
session_register("mainadvar");

include('../../inc/config.inc.php');

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
global $mainadvar,$udir,$docRoot;

   $otnos=str_replace($docRoot,"",$mainadvar['basedir']);
   echo "<html>
      <head>
      <link rel=\"stylesheet\" type=\"text/css\" href=\"../../style.css\">
      </head leftmargin=0 topmargin=0>
      <body>
      <script>
      <!--
         parent.Ok.disabled = true;
         function DoEvent(str){
          try{eval(\"parent.\"+this.name+\"_\"+str);}catch(e){}
         }
         DoEvent(\"OnLoad('".$otnos."')\");

         function OpenFile(fileencode, path){  
            DoEvent(\"OnFileSelect('\"+path+'/'+fileencode+\"')\");
         }
         function okfilename_OnClick(){
            fileencode = document.fform.actfile_name.value;
            OpenFile(fileencode, '".$otnos."');
         }
      //-->
      </script>";
    echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" width=\"100%\">\n";
      echo "<tr><td colspan=4>Путь: ".$otnos."</td></tr>";
    echo "<tr>";
    echo "<td class=usr align=\"center\">Тип</td>";
    echo "<td class=usr>Имя</td>";
    echo "<td class=usr align=\"right\">Размер файла</td>";
    echo "</tr>";
    chdir($mainadvar["basedir"]);
    $handle=opendir(".");
    while ($file = readdir($handle)) {
         if(is_dir($file) && $file!="isystem" && $file!="class" && $file!="FilesForDownload" && $file!="DocsForDownload"  && $file!="ImgForArticles") $dirlist[] = $file;
         if(is_file($file) && $file!="index.php") $filelist[] = $file;
    }
    closedir($handle);
    if($dirlist) {
      asort($dirlist);
      while (list ($key, $file) = each ($dirlist)) {
          if (!($file == ".")) {
            $filename=$file;
            $fileurl=rawurlencode($file);
            echo "<tr class=menu>\n";
            if($file == "..") {
             echo "<td colspan=4><a href=\"filemanf.php?udir=1\"><img src=\"images/parent.gif\" alt=\"Вверх\" border=\"0\"></a><hr></td>\n";
            } else {
             echo "<td align=\"center\"><a href=\"filemanf.php?wdir=$fileurl\"><img src=\"images/folder.gif\" alt=\"Войти в каталог $file\" border=\"0\"></a></td>\n";
             echo "<td colspan=2><a href=\"filemanf.php?wdir=$fileurl\">".htmlspecialchars($file)."</a></td>\n";
            }
          }
      }
   }
   if($filelist) {
      asort($filelist);
      while (list ($key, $file) = each ($filelist)) {
          if (ereg(".gif|.jpg|.jpeg|.png|.ico",$file)) $icon = "<IMG src=\"images/fimage.gif\" alt=\"Рисунок\" border=\"0\">";
          elseif (ereg(".txt",$file))  $icon = "<IMG src=\"images/ftext.gif\" alt=\"Текстовый файл\" border=\"0\">";
          elseif (ereg(".html|.htm",$file))  $icon = "<IMG src=\"images/webpage.gif\" alt=\"html файл\" border=\"0\">";
          elseif (ereg(".pdf",$file))  $icon = "<IMG src=\"images/fpdf.gif\" alt=\"Файл Acrobat Reader\" border=\"0\">";
          elseif (ereg(".xls|.xlsx",$file))  $icon = "<IMG src=\"images/fxls.gif\" alt=\"Файл Microsoft Excel\" border=\"0\">";
          elseif (ereg(".doc|.rtf|.docx",$file))   $icon = "<IMG src=\"images/fwrd.gif\" alt=\"Файл Microsoft Word\" border=\"0\">";
          elseif (ereg(".zip|.rar|.arg|.gz",$file))   $icon = "<IMG src=\"images/farc.gif\" alt=\"Файл архива\" border=\"0\">";
          elseif (ereg(".phps|.php|.php2|.php3|.php4|.asp|.asa|.cgi|.pl|.shtml|.phtml",$file)) $icon = "<IMG src=\"images/webscript.gif\" alt=\"Файл скриптов\" border=\"0\">";
          elseif (ereg(".htaccess",$file))   $icon = "<IMG src=\"images/security.gif\" alt=\"Файл доступа\" border=\"0\">" ;
          elseif (ereg(".css",$file))  $icon = "<IMG src=\"images/fcss.gif\" alt=\"Файл таблицы стилей\" border=\"0\">";
          elseif (ereg(".js",$file))   $icon = "<IMG src=\"images/fjs.gif\" alt=\"Файл сценариев\" border=\"0\">";
          elseif (ereg(".sql|.SQL",$file))   $icon = "<IMG src=\"images/sql.gif\" alt=\"Файл копии базы данных SQL\" border=\"0\">";
          else $icon = "<IMG src=\"images/fnone.gif\" alt=\"Неизвестный\" border=\"0\">";

          $filename=$file;
          $fileurl=rawurlencode($otnos.$file);
          echo "<tr class=menu1>";
          echo "<td align=\"center\">";
         echo "<a href=\"javascript:OpenFile('".$file."','".$otnos."')\">";
          echo "$icon</a></td>\n";
           echo "<td><a href=\"javascript:OpenFile('".$file."','".$otnos."')\">".htmlspecialchars($file)."</a></td>\n";
           echo "<td align=\"right\">".display_size($filename)."</td>";
      }
   }
    echo "</tr></table>";
    echo "</body></html>";
}
if($wdir) {
   $drname=$mainadvar['basedir']."/".$wdir;
   if(is_dir($drname)){
      $mainadvar['basedir'] = $drname;
      $udir=1;
   }
}elseif($udir) {
   if($mainadvar['basedir']!=$docRoot)
      $mainadvar['basedir']=substr($mainadvar['basedir'],0,strrpos($mainadvar['basedir'],"/")); 

}
displaydir();
?>
