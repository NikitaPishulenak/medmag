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
   echo "<html>\n"
      ."<head>\n"
      ."<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">\n"
      ."<link rel=\"stylesheet\" type=\"text/css\" href=\"../../style.css\">\n"
      ."</head leftmargin=0 topmargin=0>\n"
      ."<body>\n"
      ."<script>\n"
      ."<!--\n"
      ."function DoEvent(str)\n"
      ."{\n"
      ." try\n"
      ."{\n"
      ." eval(\"parent.\"+this.name+\"_\"+str);\n"
      ."}\n"
      ."catch(e){}\n"
      ."\n}"
      ."DoEvent(\"OnLoad('".$otnos."')\");\n"
      ."function OpenFile(fileencode, path)\n"
      ."{ \n" 
      ."DoEvent(\"OnFileSelect('\"+path+'/'+fileencode+\"')\");\n"
      ."}\n"
      ."function okfilename_OnClick()\n"
      ."{\n"
      ."fileencode = document.fform.actfile_name.value;\n"
      ."OpenFile(fileencode, '".$otnos."');\n"
      ."}\n"
      ."//-->\n"
      ."</script>\n";
    echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" width=\"100%\">\n";
      echo "<tr><td colspan=4>Путь: ".$otnos."</td></tr>";
    echo "<tr>";
    echo "<td class=usr align=\"center\">Тип</td>";
    echo "<td class=usr align=\"center\">Имя</td>";
    echo "<td class=usr align=\"center\">Размер файла</td>";
      echo "<td class=usr align=\"center\">Загружен</td>";
    echo "</tr>";
    chdir($mainadvar["basedir"]);
    $handle=opendir(".");
    while ($file = readdir($handle)) {
         if(is_dir($file) && $file!="isystem" && $file!="class" && $file!="FilesForDownload" && $file!="DocsForDownload" && $file!="ImgForArticles") $dirlist[] = $file;
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
             echo "<td colspan=4><a href=\"fileman.php?udir=1\"><img src=\"images/parent.gif\" alt=\"Вверх\" border=\"0\"></a><hr></td>\n";
            } else {
             echo "<td align=\"center\"><a href=\"fileman.php?wdir=$fileurl\"><img src=\"images/folder.gif\" alt=\"Войти в каталог $file\" border=\"0\"></a></td>\n";
             echo "<td colspan=2><a href=\"fileman.php?wdir=$fileurl\">".htmlspecialchars($file)."</a></td>\n";
               $changeddate = date("d.m.Y H:i:s",filectime($filename));
               echo "<td align=\"center\">".$changeddate."</td>\n";
            }
          }
      }
   }
   if($filelist) {
      asort($filelist);
      while (list ($key, $file) = each ($filelist)) {
          if (ereg(".gif|.jpg|.png|.ico",$file)) {
              $icon = "<IMG src=\"images/fimage.gif\" alt=\"Рисунок\" border=\"0\">";
             $filename=$file;
             $fileurl=rawurlencode($otnos.$file);
             $changeddate = date("d.m.Y H:i:s",filectime($filename));
             echo "<tr class=menu1>";
             echo "<td align=\"center\">";
         echo "<a href=\"javascript:OpenFile('".$file."','".$otnos."')\">";
             echo "$icon</a></td>\n";
             echo "<td><a href=\"javascript:OpenFile('".$file."','".$otnos."')\">".htmlspecialchars($file)."</a></td>\n";
             echo "<td align=\"right\">".display_size($filename)."</td>";
             echo "<td align=\"center\">".$changeddate."";
             echo "</td>";
         }
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
