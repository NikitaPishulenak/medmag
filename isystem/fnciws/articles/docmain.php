<?php

if($act=="replaceDocOk" || $act=="delDocOk" || $act=="deldepartmentOk" || $act=="adddepartment" || $act=="edtdepartment"){
   $cont=catalogOk();   
} else {
   $cont=admin_catalog();     
}

function admin_catalog()
{
   global $act,$err,$id;

   $ct="";
      switch($err){
         case 1:
            $ct="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
         break;
         case 2:
            $ct="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>Не введена вся информация. Попробуйте еще раз.</td></tr></table><br>";
         break;
         case 3:
            $ct="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>Произошла ошибка. Не удалось загрузить файл изображения.</td></tr></table><br>";
         break;
      }

   switch($act){
      case "edtDocOk":
         $ct.=edtDocTOk();
      break;
      case "edtDoc":
         $ct.=edtDocT($id);
      break;
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

function edtDocTOk(){
global $namepos,$shortcontent,$bigcontent,$dt,$department,$sortBy,$start,$docarchive,$docarchiveT,$id;

   $namepos=trim($namepos);
   $shortcontent=trim($shortcontent);
   $bigcontent=trim($bigcontent);
   $dt=substr($dt,0,10);
   $dt=explode(".",$dt);

   if(empty($namepos) || empty($shortcontent) || empty($bigcontent) || !is_numeric($dt[0]) || !is_numeric($dt[1]) || !is_numeric($dt[2]) || !checkdate($dt[1],$dt[0],$dt[2])) { header("location: ?go=articles&act=edtDoc".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=2&id=$id"); return; }

   if($docarchive){
      include('fnciws/articles/docFunctions.php');
      list($nWidthS,$nWidthM)=mysql_fetch_row(mysql_query("SELECT nWidthS, nWidthM FROM iws_art_prefernce WHERE id=1"));
      $retDocName=Recopy_doc_toserver($nWidthS,$nWidthM,$docarchiveT);
      if(!$retDocName) { header("location: ?go=articles&act=edtDoc".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=3&id=$id"); return; }
   } else {
      $retDocName=$docarchiveT;
   }

   $namepos=addslashes($namepos);
   $shortcontent=addslashes($shortcontent);
   $bigcontent=addslashes($bigcontent);

   $sql = "UPDATE iws_art_records SET name='$namepos',description='$shortcontent',bigcontent='$bigcontent',file='$retDocName',data=CONCAT('".$dt[2]."-".$dt[1]."-".$dt[0]." ', CURTIME()) WHERE id=$id";

   if(!mysql_query($sql)){
      header("location: ?go=articles&act=edtDoc".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=1&id=$id");
      return;
   } else {
      header("location: ?go=articles".(($sortBy) ? '&sortBy='.$sortBy : ''));
      return;
   }
}




function edtDocT($aid)
{
   global $sortBy,$start;

   $content="
      <script><!--
      function submitr(){
         if(formS.namepos.value && formS.shortcontent.value && formS.dt.value){
            if(window.BODYhtml.FormHTML.elm1.value){
               formS.bigcontent.value=window.BODYhtml.FormHTML.elm1.value;
               formS.submit();
            } else {
               alert (\"Не введена информация полного текста Новости-статьи!   \");
            }
         } else {
            alert (\"В добавлении отказано! Не введена вся информация.                    \");
         }
      }
      //--></script>
               <form action=\"mainiws.php\" name=formS method=\"post\" enctype=\"multipart/form-data\">
               <input type=\"hidden\" name=go value=articles>
               <input type=\"hidden\" name=act value=edtDocOk>
               <input type=\"hidden\" name=id value=$aid>
               <input type=hidden name=bigcontent value=\"\">
               ".(($sortBy) ? '<input type=hidden name=sortBy value='.$sortBy.'>' : '').(($start) ? '<input type=hidden name=start value='.$start.'>' : '')."

         <table bgcolor=#ffffff width=100% border=0 cellpadding=0 cellspacing=0>
         <tr><td width=100% align=center class=usr>Новости-статьи / Редактировать новость</td>
         <td class=usr><img onClick=\"document.location='mainiws.php?go=articles".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."'; return false;\" src=\"images/close.gif\" border=0 alt=\"Закрыть окно\" style=\"cursor:hand\"></td>
         </tr>";
      
         $res=mysql_query("SELECT (SELECT B.name FROM iws_art_department B WHERE B.id=A.department), DATE_FORMAT(A.data,'%d.%m.%Y'), A.name, A.description, A.bigcontent, A.file FROM iws_art_records A WHERE A.id=$aid");
         if(mysql_numrows($res)>=1){

            List($department, $dt, $namepos, $shortcontent, $bigcontent, $docarchive)=mysql_fetch_row($res);
            $content.="<tr><td colspan=2><br><b>Рубрика</b> $department
            &nbsp;&nbsp;&nbsp;<b>Дата</b> <input name=dt size=8 maxlength=10 value=\"".$dt."\">
            &nbsp;&nbsp;&nbsp;<b>Файл изображения</b> <input type=hidden name=docarchiveT value=\"$docarchive\"><input type=file name=docarchive size=40><br><br>
            <b>Заголовок</b><br><input type=text name=namepos style=\"width=100%\" value=\"".stripslashes($namepos)."\"><br>
            <b>Анонс</b><br><textarea name=shortcontent rows=4 style=\"width:100%\">".stripslashes($shortcontent)."</textarea>
            </td></tr>
            <tr><td height=600 colspan=2><b>Полное содержание Новости-статьи</b><br>".ret_html()."</td></tr></form></table>
            <SCRIPT LANGUAGE=JavaScript FOR=\"window\" EVENT=onload><!--
            BODYhtml.FormHTML.elm1.value=\"".ereg_replace("\"","'",ereg_replace("(\r|\n)","",stripslashes($bigcontent)))."\";
            //--></script>\n";
         } else {
            $content.="</form></table><br>Извините, произошёл сбой при открытии Новости-статьи.";
         }

   unset($sortBy,$res);

return $content;
}


//-----------------------------------------------------------------------------------------------------------------------------



function addDocTOk(){
global $namepos,$shortcontent,$bigcontent,$dt,$department,$sortBy,$start,$docarchive;

   $namepos=trim($namepos);
   $shortcontent=trim($shortcontent);
   $bigcontent=trim($bigcontent);
   $dt=substr($dt,0,10);
   $dt=explode(".",$dt);

   if(empty($namepos) || empty($shortcontent) || empty($bigcontent) || !is_numeric($dt[0]) || !is_numeric($dt[1]) || !is_numeric($dt[2]) || !checkdate($dt[1],$dt[0],$dt[2])) { header("location: ?go=articles&act=addDoc".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=2"); return; }

   include('fnciws/articles/docFunctions.php');

   list($nWidthS,$nWidthM,$rss,$countinmain)=mysql_fetch_row(mysql_query("SELECT nWidthS, nWidthM, rss, countinmain FROM iws_art_prefernce WHERE id=1"));

   if($docarchive){
      $retDocName=copy_doc_toserver($nWidthS,$nWidthM);
      if(!$retDocName) { header("location: ?go=articles&act=addDoc".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=3"); return; }
   } else {
      $retDocName="";
   }

   $namepos=addslashes($namepos);
   $shortcontent=addslashes($shortcontent);
   $bigcontent=addslashes($bigcontent);

   $sql = "INSERT INTO iws_art_records (department,name,description,bigcontent,file,data) VALUES ($department,'$namepos','$shortcontent','$bigcontent','$retDocName',CONCAT('".$dt[2]."-".$dt[1]."-".$dt[0]." ', CURTIME()))";

   if(!mysql_query($sql)){
      delDoc($retDocName);
      header("location: ?go=articles&act=addDoc".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=1");
      return;
   } else {
     if ($rss){ rsscreate($rss,$countinmain); }
   
     header("location: ?go=articles".(($sortBy) ? '&sortBy='.$sortBy : ''));
     return;
   }
}

/*------------------------------------------------------------------------------------------*/


function addDocT()
{
   global $sortBy,$start;

   $content="
      <script><!--
      function submitr(){
         if(formS.namepos.value && formS.shortcontent.value && formS.dt.value){
            if(window.BODYhtml.FormHTML.elm1.value){
               formS.bigcontent.value=window.BODYhtml.FormHTML.elm1.value;
               formS.submit();
            } else {
               alert (\"Не введена информация полного текста Новости-статьи!   \");
            }
         } else {
            alert (\"В добавлении отказано! Не введена вся информация.                    \");
         }
      }
      //--></script>
               <form action=\"mainiws.php\" name=formS method=\"post\" enctype=\"multipart/form-data\">
               <input type=\"hidden\" name=go value=articles>
               <input type=\"hidden\" name=act value=addDocOk>
               <input type=hidden name=bigcontent value=\"\">
               ".(($sortBy) ? '<input type=hidden name=sortBy value='.$sortBy.'>' : '').(($start) ? '<input type=hidden name=start value='.$start.'>' : '')."

         <table bgcolor=#ffffff width=100% border=0 cellpadding=0 cellspacing=0>
         <tr><td width=100% align=center class=usr>Новости-статьи / Добавить новость</td>
         <td class=usr><img onClick=\"document.location='mainiws.php?go=articles".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."'; return false;\" src=\"images/close.gif\" border=0 alt=\"Закрыть окно\" style=\"cursor:hand\"></td>
         </tr>";


         $resDep=mysql_query("SELECT id,name FROM iws_art_department WHERE mid<=0 ORDER BY pos");
         if(mysql_numrows($resDep)>=1){

            $content.="<tr><td colspan=2><br><b>Рубрика</b> <select name=department>";

            while($arr=mysql_fetch_row($resDep)){
               if($sortBy==$arr[0]){
                  $content.="<option value=".$arr[0]." selected>".$arr[1]."</option>";
               } else {
                  $content.="<option value=".$arr[0].">".$arr[1]."</option>";
               }
               $resSub=mysql_query("SELECT id,name FROM iws_art_department WHERE mid=".$arr[0]." ORDER BY pos");
               if(mysql_numrows($resSub)>=1){
                  while($arr=mysql_fetch_row($resSub)){
                     if($sortBy==$arr[0]){
                        $content.="<option value=".$arr[0]." selected>&#160;&#160;&#160;&#160;-&#160;".$arr[1]."</option>";
                     } else {
                        $content.="<option value=".$arr[0].">&#160;&#160;&#160;&#160;-&#160;".$arr[1]."</option>";
                     }
                  }
               }
            }

            $content.="</select>
            &nbsp;&nbsp;&nbsp;<b>Дата</b> <input name=dt size=8 maxlength=10 value=\"".date("d.m.Y")."\">
            &nbsp;&nbsp;&nbsp;<b>Файл изображения</b> <input type=file name=docarchive size=40><br><br>
            <b>Заголовок</b><br><input type=text name=namepos style=\"width=100%\"><br>
            <b>Анонс</b><br><textarea name=shortcontent rows=4 style=\"width:100%\"></textarea>
            </td></tr>
            <tr><td height=600 colspan=2><b>Полное содержание Новости-статьи</b><br>".ret_html()."</td></tr>
            </form></table>";
         } else {
            $content.="Извините, вы не можете добавить новость, пока не создана хотя бы одна рубрика. <a title=\"Создать рубрику\" href=\"mainiws.php?go=articles&act=department\"><b>Создать рубрику</b></a>";
         }

   unset($sortBy,$resDep,$resSub);

return $content;
}


//-----------------------------------------------------------------------------------------------------------------------------


function defaultView(){
global $sortBy,$start,$QUERY_STRING;

      if(!$start) $start=1;

      include('fnciws/articles/docFunctions.php');
      $prom=numlink($start,ereg_replace("&start=".$start,"",$QUERY_STRING),"iws_art_records",$sortBy);


      $content="<script><!--
      function delOkDoc(urli,nmk){
         if(confirm('Вы действительно хотите удалить новость?       ')) document.location=urli+'&docName='+nmk;
      }

      function replaceOkDoc(urlR)
      {
         var arr = null;
         arr = showModalDialog(\"fnciws/articles/gdialog.php?evtype=replaceDoc\", null, \"dialogWidth:410px; dialogHeight:120px; status:no;\");
         if (arr != null) document.location=urlR+'&newCat='+arr;
      }

      //--></script>
      <table width=100% border=0 cellpadding=1 cellspacing=4><tr valign=top><td><h5>Новости-статьи</h5>
      <table width=100% border=0 cellpadding=2 cellspacing=1>
      <tr><td colspan=4></td><td align=right><a href=\"#\" onclick=\"document.location='mainiws.php?go=articles&act=addDoc".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."'; return false;\">добавить новость</a></td></tr>
      <tr><td></td><td colspan=4 bgcolor=#EBEBEB><b>Рубрика</b> <select name=sortBy onChange=\"document.location='mainiws.php?go=articles'+sortBy.value+'&start=$start'; return false;\">
      <option value=\"\">Все рубрики</option>";

      $resDep=mysql_query("SELECT id,name FROM iws_art_department WHERE mid<=0 ORDER BY pos");
      if(mysql_numrows($resDep)>=1){
         while($arr=mysql_fetch_row($resDep)){
               if($sortBy==$arr[0]){
                  $content.="<option value='&sortBy=".$arr[0]."' selected>".$arr[1]."</option>";
               } else {
                  $content.="<option value='&sortBy=".$arr[0]."'>".$arr[1]."</option>";
               }
            $resSub=mysql_query("SELECT id,name FROM iws_art_department WHERE mid=".$arr[0]." ORDER BY pos");
            if(mysql_numrows($resSub)>=1){
               while($arr=mysql_fetch_row($resSub)){
                  if($sortBy==$arr[0]){
                     $content.="<option value='&sortBy=".$arr[0]."' selected>&#160;&#160;&#160;&#160;-&#160;".$arr[1]."</option>";
                  } else {
                     $content.="<option value='&sortBy=".$arr[0]."'>&#160;&#160;&#160;&#160;-&#160;".$arr[1]."</option>";
                  }
               }
            }
         }
      }

      $content.="</select>&nbsp;&nbsp;&nbsp;<a title=\"Добавить/Удалить рубрику\" href=\"mainiws.php?go=articles&act=department\"><b>Редактировать</b></a></td></tr>";

      if($prom){

         $res=mysql_query("SELECT A.id,(SELECT B.name FROM iws_art_department B WHERE B.id=A.department), DATE_FORMAT(A.data,'%d.%m.%Y %T'), A.name, A.file
          FROM iws_art_records A ".(($sortBy) ? 'WHERE A.department='.$sortBy : '')." ORDER BY A.data DESC LIMIT ".($start-1).",50");
   
         $content.="<tr><td></td><td colspan=5>".$prom."<br></td></tr><tr align=center><td></td><td class=usr></td><td class=usr></td><td class=usr>Рубрика</td><td class=usr>Операции</td></tr>";

         if(mysql_numrows($res)>=1){
            include("inc/config.inc.php");
            $cls="menu1";
            $i=$start;
            while($arr=mysql_fetch_row($res)){
               if($cls=="menu1"){ $cls="menu"; } else { $cls="menu1"; }
         
               $content.="<tr valign=top><td align=right>".($i++).".</td>
                           <td class=$cls>".(($arr[4]) ? "<img src=\"../ImgForArticles/s_".$arr[4]."\">" : "")."</td>
                           <td class=$cls><p>".$arr[2]."<br><a href=\"#\" title=\"Редактировать новость\" onclick=\"document.location='mainiws.php?go=articles&act=edtDoc&sortBy=$sortBy&start=$start&id=".$arr[0]."'; return false;\"><b>".$arr[3]."</b></a></p></td>
                           <td class=$cls>".$arr[1]."</td>";
               $content.="<td class=$cls>
                           [<a href=\"#\" title=\"Сменить рубрику\" onclick=\"replaceOkDoc('mainiws.php?go=articles&act=replaceDocOk&sortBy=$sortBy&start=$start&id=".$arr[0]."'); return false;\">сменить рубрику</a>] 
                           [<a href=\"#\" onclick=\"delOkDoc('mainiws.php?go=articles&act=delDocOk&sortBy=$sortBy&start=$start&id=".$arr[0]."','".$arr[4]."'); return false;\"><font color=#ff0000>удалить</font></a>]
                           </td></tr>";
            }
            unset($res);
         } 
         $content.="<tr><td></td><td colspan=5><br>".$prom."</td></tr>";
   
      } else {
         $content.="<tr><td colspan=2></td><td colspan=4><br><br><h4>Извините, на сервере нет статей!</h4></td></tr>";
      }

      $content.="<tr><td colspan=4></td><td align=right><a href=\"#\" onclick=\"document.location='mainiws.php?go=articles&act=addDoc".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."'; return false;\">добавить новость</a></td></tr>"
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
         if(confirm('Вы действительно хотите удалить рубрику \"'+nmk+'\"?')) document.location=urli;
      }

      function addDep(mid)
      {
         var arr = null;
         arr = showModalDialog(\"fnciws/articles/gdialog.php?evtype=adddepartment\", null, \"dialogWidth:550px; dialogHeight:220px; status:no;\");
         if (arr != null) document.location='mainiws.php?go=articles&act=adddepartment&nm='+arr[\"cname\"]+'&pos='+arr[\"pos\"]+'&activ='+arr[\"activ\"]+'&banner='+arr[\"banner\"]+'&mid='+mid;
      }

      function edtDep(did)
      {
         var args = new Array();
         var arr = null;
         args[\"did\"]=did;
         arr = showModalDialog(\"fnciws/articles/gdialog.php?evtype=edtdepartment&mid=\"+did, args, \"dialogWidth:550px; dialogHeight:220px; status:no;\");
         if (arr != null) document.location='mainiws.php?go=articles&act=edtdepartment&id='+did+'&nm='+arr[\"cname\"]+'&pos='+arr[\"pos\"]+'&activ='+arr[\"activ\"]+'&banner='+arr[\"banner\"];
      }
      //--></script>
      <table align=center width=70% border=0 cellpadding=2 cellspacing=1>
      <tr><td><a href=\"#\" onclick=\"document.location='mainiws.php?go=articles'; return false;\">вернуться в Новости-статьи</a></td><td><a href=\"#\" onclick=\"addDep(0); return false;\">добавить новую рубрику</a></td></tr>
      <tr align=center><td class=usr>Рубрика</td><td class=usr></td></tr>\n";

      $res=mysql_query("SELECT id,name,activ,pos FROM iws_art_department WHERE mid<=0 ORDER BY pos");
      if(mysql_numrows($res)>=1){
         $cl="menu1";
         while($arr=mysql_fetch_row($res)){
            if($cl=="menu1"){ $cl="menu"; } else { $cl="menu1"; }

            $content.="<tr class=$cl><td><b>".$arr[1]."</b>&nbsp;&nbsp;".(($arr[2]) ? '(Активна)' : '(<font color=#ff0000>Неактивна</font>)')."&nbsp;&nbsp;<i>".$arr[3]."</i>&nbsp;&nbsp;</td><td>"
               ."[<a href=\"#\" onclick=\"edtDep(".$arr[0]."); return false;\">редактировать</a>] "
               ."[<a href=\"#\" onclick=\"addDep(".$arr[0]."); return false;\">добавить подрубрику</a>] "
               ."[<a href=\"#\" onclick=\"delOkDep('mainiws.php?go=articles&act=deldepartmentOk&id=".$arr[0]."','".$arr[1]."'); return false;\"><font color=#ff0000>удалить</font></a>]</td></tr>\n";

               $resSub=mysql_query("SELECT id,name,activ,pos FROM iws_art_department WHERE mid=".$arr[0]." ORDER BY pos");
               if(mysql_numrows($resSub)>=1){
                  while($arr=mysql_fetch_row($resSub)){
                     if($cl=="menu1"){ $cl="menu"; } else { $cl="menu1"; }

                     $content.="<tr class=$cl><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - <b>".$arr[1]."</b>&nbsp;&nbsp;".(($arr[2]) ? '(Активна)' : '(<font color=#ff0000>Неактивна</font>)')."&nbsp;&nbsp;<i>".$arr[3]."</i>&nbsp;&nbsp;</td><td>"
                     ."[<a href=\"#\" onclick=\"edtDep(".$arr[0]."); return false;\">редактировать</a>] "
                     ."[<a href=\"#\" onclick=\"delOkDep('mainiws.php?go=articles&act=deldepartmentOk&id=".$arr[0]."','".$arr[1]."'); return false;\"><font color=#ff0000>удалить</font></a>]</td></tr>\n";
                  }
               }
         }
      } else {
         $content.="<tr><td colspan=3>Извините, в базе данных нет рубрик!</td></tr>";
      } 
      $content.="<tr><td><a href=\"#\" onclick=\"document.location='mainiws.php?go=articles'; return false;\">вернуться в Новости-статьи</a></td><td><a href=\"#\" onclick=\"addDep(0); return false;\">добавить новую рубрику</a></td></tr>"
      ."</table>";
      return $content;
}

//------------------------------------------------------------------------------------------------------------------------------------


function catalogOk()
{
   global $act,$id,$sortBy,$start,$newCat,$nm,$docName,$pos,$activ,$banner,$mid;
   
   switch($act){

   case "replaceDocOk":
      if(!mysql_query("UPDATE iws_art_records SET department=$newCat WHERE id=$id")){
         header("location: ?go=articles&err=1".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : ''));
         return;
      } else {    
         header("location: ?go=articles".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : ''));
         return;
      }
   break;

   case "delDocOk":
      include('fnciws/articles/docFunctions.php');
      delDoc($docName);
      mysql_query("DELETE FROM iws_art_records WHERE id=$id");
	   list($rss,$countinmain)=mysql_fetch_row(mysql_query("SELECT rss, countinmain FROM iws_art_prefernce WHERE id=1"));
	   if ($rss){ rsscreate($rss,$countinmain); }
      header("location: ?go=articles".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : ''));

      return;
   break;


   case "deldepartmentOk":
      mysql_query("DELETE FROM iws_art_department WHERE id=$id");

      header("location: ?go=articles&act=department");
      return;
   break;

   case "adddepartment":
      $nm=trim($nm);
      if(empty($nm)) { 
         header("location: ?go=articles&act=department&err=2");
         return;
      }
      $nm=addslashes($nm);
      if(!mysql_query("INSERT INTO iws_art_department (name,mid,pos,activ,banner) VALUES ('$nm',$mid,'$pos',$activ,'$banner')")){
         header("location: ?go=articles&act=department&err=1");
         return;
      } else {    
         header("location: ?go=articles&act=department");
         return;
      }
   break;

   case "edtdepartment":
      $nm=trim($nm);
      if(empty($nm)) { 
         header("location: ?go=articles&act=department&err=2");
         return;
      }
      $nm=addslashes($nm);
      if(!mysql_query("UPDATE iws_art_department SET name='$nm', pos='$pos', activ=$activ, banner='$banner' WHERE id=$id")){
         header("location: ?go=articles&act=department&err=1");
         return;
      } else {    
         header("location: ?go=articles&act=department");
         return;
      }
   break;
}
}


function ret_html(){
   return "<iframe name=\"BODYhtml\" width=\"100%\" height=\"95%\" frameborder=0 marginwidth=0 marginheight=0 src=\"fnciws/html/html_edit.php?tml=0&vrtp=-1\"  scrolling=no></iframe>";
}

?>