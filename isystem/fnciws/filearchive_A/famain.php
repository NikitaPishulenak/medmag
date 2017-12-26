<?php

if($act=="replaceFileOk" || $act=="delFileOk" || $act=="deldepartmentOk" || $act=="adddepartment" || $act=="edtdepartment" || $act=="delrubricOk" || $act=="addrubric" || $act=="edtrubric"){
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
                                $ct="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>Произошла ошибка. Не удалось загрузить файл.</td></tr></table><br>";
                        break;
                        case 4:
                                $ct="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>Произошла ошибка. Не удалось обновить информацию о файле.</td></tr></table><br>";
                        break;
                }

        switch($act){
        
                case "addFileOk":
                        $ct.=addFileTOk();
                break;
                case "addFile":
                        $ct.=addFileT();
                break;
                case "edtFileOk":
                        $ct.=edtFileTOk($id);
                break;
                case "edtFile":
                        $ct.=edtFileT($id);
                break;
                case "department":
                        $ct.=department();
                break;
                case "rubric":
                        $ct.=rubric();
                break;
                default:
                        $ct.=defaultView();
                break;
        }
        unset($act,$err);
        return $ct;
}

//----------------------------------------------------------------------------------------------------------------------------


function edtFileTOk($id){
global $namepos,$author,$shortcontent,$keyw,$dep,$liter,$nameposENG,$authorENG,$shortcontentENG,$keywENG,$department,$rubric,$sortBy,$start,$curFile,$filearchive;

        $namepos=trim($namepos);
        $shortcontent=trim($shortcontent);
        if(empty($namepos) || empty($shortcontent)) { header("location: ?go=filearchive_A&act=edtFile&id=".$id.(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=2"); return; }

        $author=trim($author);
        $authorENG=trim($authorENG);
        $namepos=trim($namepos);
        $nameposENG=trim($nameposENG);
        $shortcontent=trim($shortcontent);
        $shortcontentENG=trim($shortcontentENG);
        $keyw=trim($keyw);
        $keywENG=trim($keywENG);
        $dep=trim($dep);
        $liter=trim($liter);

        if(isset($filearchive) && $filearchive){
           include('fnciws/filearchive_A/faFunctions.php');
           delFile($curFile);
           $retFileName=copy_file_toserver();
           if(!$retFileName) { header("location: ?go=filearchive_A&act=edtFile&id=".$id.(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=3"); return; }
        } else {
           $retFileName=$curFile;
        }
        

        $namepos=addslashes($namepos);
        $shortcontent=preg_replace("#(\r)\n#si", "<br>",addslashes($shortcontent));
        $author=addslashes($author);
        $keyw=addslashes($keyw);
        $dep=addslashes($dep);
        $liter=preg_replace("#(\r)\n#si", "<br>",addslashes($liter));
        $nameposENG=addslashes($nameposENG);
        $shortcontentENG=preg_replace("#(\r)\n#si", "<br>",addslashes($shortcontentENG));
        $authorENG=addslashes($authorENG);
        $keywENG=addslashes($keywENG);


        $sql = "UPDATE iws_arfiles_A_records SET department=$department,rubric=$rubric,authors='$author',name='$namepos',description='$shortcontent',keyw='$keyw',dep='$dep',liter='$liter',authors_e='$authorENG',name_e='$nameposENG',description_e='$shortcontentENG',keyw_e='$keywENG',file='$retFileName' WHERE id=$id";

        if(!mysql_query($sql)){
                include('fnciws/filearchive_A/faFunctions.php');
                delFile($retFileName);
                header("location: ?go=filearchive_A&act=edtFile&id=".$id.(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=1");
                return;
        } else {
                header("location: ?go=filearchive_A".(($sortBy) ? '&sortBy='.$sortBy : ''));
                return;
        }
}


function edtFileT($id)
{
        global $sortBy,$start;

        $res=mysql_query("SELECT department, rubric, name, authors, description, keyw, dep, liter, name_e, authors_e, description_e, keyw_e, file FROM iws_arfiles_A_records WHERE id=$id");

        if(mysql_numrows($res)===1){
                     
           $arrAll=mysql_fetch_row($res);

           $content="<h5>Журнал / Редактировать статью</h5>
                <script><!--
                function tosubmit(){
                        if(formS.namepos.value && formS.shortcontent.value){
                                formS.submit();
                        } else {
                                alert (\"В добавлении отказано! Не введена вся информация.                    \");
                        }
                }
                //--></script>
                <form action=\"mainiws.php\" name=formS method=\"post\" enctype=\"multipart/form-data\">
                <input type=\"hidden\" name=go value=filearchive_A>
                <input type=\"hidden\" name=act value=edtFileOk>
                <input type=\"hidden\" name=id value=$id>
                <input type=\"hidden\" name=curFile value=\"".$arrAll[12]."\">

                ".(($sortBy) ? '<input type=hidden name=sortBy value='.$sortBy.'>' : '').(($start) ? '<input type=hidden name=start value='.$start.'>' : '')."

                <table width=100% border=0 cellpadding=0 cellspacing=10>
                <tr><td width=50%><b>№ журнала</b><br><select name=department>
                <option value=0>Без раздела</option>";

                        $resDep=mysql_query("SELECT id,name FROM iws_arfiles_A_department ORDER BY name DESC");
                        if(mysql_numrows($resDep)>=1){
                                while($arr=mysql_fetch_row($resDep)){
                                        if($arrAll[0]===$arr[0]){
                                                $content.="<option value=".$arr[0]." selected>".$arr[1]."</option>";
                                        } else {
                                                $content.="<option value=".$arr[0].">".$arr[1]."</option>";
                                        }
                                }
                        }

                $content.="</select></td>
                <td><b>Рубрика</b><br><select name=rubric>
                <option value=0>Без рубрики</option>";

                        $resDep=mysql_query("SELECT id,name FROM iws_arfiles_A_rubric ORDER BY name");
                        if(mysql_numrows($resDep)>=1){
                                while($arr=mysql_fetch_row($resDep)){
                                        if($arrAll[1]===$arr[0]){
                                                $content.="<option value=".$arr[0]." selected>".$arr[1]."</option>";
                                        } else {
                                                $content.="<option value=".$arr[0].">".$arr[1]."</option>";
                                        }
                                }
                        }

                $content.="</select></td></tr>
                                <tr valign=top><td>
                                <p><b>Название</b><br><input type=text name=namepos style=\"width=100%\" value=\"".htmlspecialchars(stripslashes($arrAll[2]))."\"></p>
                                <p><b>Авторы</b><br><input type=text name=author style=\"width=100%\" value=\"".htmlspecialchars(stripslashes($arrAll[3]))."\"></p>
                                <p><b>Ключевые слова</b><br><input type=text name=keyw style=\"width=100%\" value=\"".htmlspecialchars(stripslashes($arrAll[5]))."\"></p>
                                <p><b>Учреждения авторов</b><br><input type=text name=dep style=\"width=100%\" value=\"".htmlspecialchars(stripslashes($arrAll[6]))."\"></p></td>

                                <td><p><em>Название (ENG)</em><br><input type=text name=nameposENG style=\"width=100%\" value=\"".htmlspecialchars(stripslashes($arrAll[8]))."\"></p>
                                <p><em>Авторы (ENG)</em><br><input type=text name=authorENG style=\"width=100%\" value=\"".htmlspecialchars(stripslashes($arrAll[9]))."\"></p>
                                <p><em>Ключевые слова (ENG)</em><br><input type=text name=keywENG style=\"width=100%\" value=\"".htmlspecialchars(stripslashes($arrAll[11]))."\"></p></td></tr>

                                <tr><td><p><b>Описание</b><br><textarea name=shortcontent rows=15 style=\"width:100%\">".preg_replace("#<br>#si", "\r\n",stripslashes($arrAll[4]))."</textarea></p></td>
                                <td><p><em>Описание (ENG)</em><br><textarea name=shortcontentENG rows=15 style=\"width:100%\">".preg_replace("#<br>#si", "\r\n",stripslashes($arrAll[10]))."</textarea></p></td><tr>
                                <tr><td><p><br><b>Список литературы</b><br><textarea name=liter rows=8 style=\"width:100%\">".preg_replace("#<br>#si", "\r\n",stripslashes($arrAll[7]))."</textarea></p>
                                <p><b>Полный текст статьи на закачку</b>: <a href=\"/FilesForDownload_A/".$arrAll[12]."\">".$arrAll[12]."</a><br><br>
                                   <b>Заменить файл</b><br><input type=file name=filearchive style=\"width=80%\"></p>
                                </td></tr>
                                <tr height=80><td colspan=2><input class=but type=\"button\" value=\"Сохранить\" onclick=\"tosubmit(); return false;\">&nbsp;&nbsp;
                                <input class=but type=\"button\" value=\"Отмена\" onClick=\"document.location='mainiws.php?go=filearchive_A".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."'; return false;\">
                                </td></tr></table></form><br>";

           unset($sortBy,$resDep);

        } else {
           $content="<h5>Журнал / Редактировать статью</h5><h3>Произошла ошибка</h3>";
        }

return $content;
}


function addFileTOk(){
global $namepos,$author,$shortcontent,$keyw,$dep,$liter,$nameposENG,$authorENG,$shortcontentENG,$keywENG,$department,$rubric,$sortBy,$start;

        $namepos=trim($namepos);
        $shortcontent=trim($shortcontent);
        if(empty($namepos) || empty($shortcontent)) { header("location: ?go=filearchive_A&act=addFile".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=2"); return; }

        $author=trim($author);
        $authorENG=trim($authorENG);
        $namepos=trim($namepos);
        $nameposENG=trim($nameposENG);
        $shortcontent=trim($shortcontent);
        $shortcontentENG=trim($shortcontentENG);
        $keyw=trim($keyw);
        $keywENG=trim($keywENG);
        $dep=trim($dep);
        $liter=trim($liter);


        include('fnciws/filearchive_A/faFunctions.php');
        $retFileName=copy_file_toserver();
        if(!$retFileName) { header("location: ?go=filearchive_A&act=addFile".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=3"); return; }

        $namepos=addslashes($namepos);
        $shortcontent=preg_replace("#(\r)\n#si", "<br>",addslashes($shortcontent));
        $author=addslashes($author);
        $keyw=addslashes($keyw);
        $dep=addslashes($dep);
        $liter=preg_replace("#(\r)\n#si", "<br>",addslashes($liter));
        $nameposENG=addslashes($nameposENG);
        $shortcontentENG=preg_replace("#(\r)\n#si", "<br>",addslashes($shortcontentENG));
        $authorENG=addslashes($authorENG);
        $keywENG=addslashes($keywENG);

        $unicumC=unicumId();

        $sql = "INSERT INTO iws_arfiles_A_records (department,rubric,pse,authors,name,description,keyw,dep,liter,authors_e,name_e,description_e,keyw_e,file,data) VALUES ($department,$rubric,'$unicumC','$author','$namepos','$shortcontent','$keyw','$dep','$liter','$authorENG','$nameposENG','$shortcontentENG','$keywENG','$retFileName',NOW())";

        if(!mysql_query($sql)){
                delFile($retFileName);
                header("location: ?go=filearchive_A&act=addFile".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=1");
                return;
        } else {
                header("location: ?go=filearchive_A".(($sortBy) ? '&sortBy='.$sortBy : ''));
                return;
        }
}




function addFileT()
{
        global $sortBy,$start;
        $content="<h5>Журнал / Добавить статью</h5>
                <script><!--
                function tosubmit(){
                        if(formS.namepos.value && formS.shortcontent.value && formS.filearchive.value){
                                formS.submit();
                        } else {
                                alert (\"В добавлении отказано! Не введена вся информация.                    \");
                        }
                }
                //--></script>
                <form action=\"mainiws.php\" name=formS method=\"post\" enctype=\"multipart/form-data\">
                <input type=\"hidden\" name=go value=filearchive_A>
                <input type=\"hidden\" name=act value=addFileOk>
                ".(($sortBy) ? '<input type=hidden name=sortBy value='.$sortBy.'>' : '').(($start) ? '<input type=hidden name=start value='.$start.'>' : '')."

                <table width=100% border=0 cellpadding=0 cellspacing=10>
                <tr><td width=50%><b>№ журнала</b><br><select name=department>
                <option value=0>Без раздела</option>";

                        $resDep=mysql_query("SELECT id,name FROM iws_arfiles_A_department ORDER BY name DESC");
                        if(mysql_numrows($resDep)>=1){
                                while($arr=mysql_fetch_row($resDep)){
                                        if($sortBy==$arr[0]){
                                                $content.="<option value=".$arr[0]." selected>".$arr[1]."</option>";
                                        } else {
                                                $content.="<option value=".$arr[0].">".$arr[1]."</option>";
                                        }
                                }
                        }

                $content.="</select></td>
                <td><b>Рубрика</b><br><select name=rubric>
                <option value=0>Без рубрики</option>";

                        $resDep=mysql_query("SELECT id,name FROM iws_arfiles_A_rubric ORDER BY name");
                        if(mysql_numrows($resDep)>=1){
                                while($arr=mysql_fetch_row($resDep)){
                                                $content.="<option value=".$arr[0].">".$arr[1]."</option>";
                                }
                        }

                $content.="</select></td></tr>
                                <tr valign=top><td>
                                <p><b>Название</b><br><input type=text name=namepos style=\"width=100%\"></p>
                                <p><b>Авторы</b><br><input type=text name=author style=\"width=100%\"></p>
                                <p><b>Ключевые слова</b><br><input type=text name=keyw style=\"width=100%\"></p>
                                <p><b>Учреждения авторов</b><br><input type=text name=dep style=\"width=100%\"></p></td>

                                <td><p><em>Название (ENG)</em><br><input type=text name=nameposENG style=\"width=100%\"></p>
                                <p><em>Авторы (ENG)</em><br><input type=text name=authorENG style=\"width=100%\"></p>
                                <p><em>Ключевые слова (ENG)</em><br><input type=text name=keywENG style=\"width=100%\"></p></td></tr>

                                <tr><td><p><b>Описание</b><br><textarea name=shortcontent rows=15 style=\"width:100%\"></textarea></p></td>
                                <td><p><em>Описание (ENG)</em><br><textarea name=shortcontentENG rows=15 style=\"width:100%\"></textarea></p></td><tr>
                                <tr><td><p><br><b>Список литературы</b><br><textarea name=liter rows=8 style=\"width:100%\"></textarea></p>
                                <p><b>Полный текст статьи на закачку</b><br><input type=file name=filearchive style=\"width=80%\"></p>
                                </td></tr>
                                <tr height=80><td colspan=2><input class=but type=\"button\" value=\"Добавить\" onclick=\"tosubmit(); return false;\">&nbsp;&nbsp;
                                <input class=but type=\"button\" value=\"Отмена\" onClick=\"document.location='mainiws.php?go=filearchive_A".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."'; return false;\">
                                </td></tr></table></form><br>";

        unset($sortBy,$resDep);

return $content;
}


//-----------------------------------------------------------------------------------------------------------------------------


function defaultView(){
global $sortBy,$start,$QUERY_STRING;

                if(!$start) $start=1;

                include('fnciws/filearchive_A/faFunctions.php');
                $prom=numlink($start,ereg_replace("&start=".$start,"",$QUERY_STRING),"iws_arfiles_A_records",$sortBy);


                $content.="<script><!--
                function delOkFile(urli,nmk){
                        if(confirm('Вы действительно хотите удалить статью \"'+nmk+'\"?       ')) document.location=urli+'&fileName='+nmk;
                }

                function replaceOkFile(urlR)
                {
                        var arr = null;
                        arr = showModalDialog(\"fnciws/filearchive_A/gdialog.php?evtype=replaceFile\", null, \"dialogWidth:410px; dialogHeight:120px; status:no;\");
                        if (arr != null) document.location=urlR+'&newCat='+arr;
                }
                
                
                function editOkFile(sort,start,ids)
                {   var arr = null;
                       arr = showModalDialog(\"fnciws/filearchive_A/gdialog.php?evtype=editFile&id=\"+ids+\"\", null, \"dialogWidth:410px; dialogHeight:260px; status:no;\");
                       if (arr != null)
                       {
                       hid.Nname.value=arr['Nname']; 
                       hid.Nauthors.value=arr['Nauthors'];
                       hid.Ndescription.value=arr['Ndescription'];
                                  
                       hid.id.value=ids; 
                       hid.sortBy.value=sort;
                       hid.start.value=start;
                       hid.submit();
                       
                       
                       }
                       
                }

                //--></script>
                <form action='mainiws.php' name='hid' method='post'>
                <input name='go' type='hidden' value='filearchive_A'>
                <input name='act' type='hidden' value='editFileOk' >
                <input name='sortBy' type='hidden'  >
                <input name='start' type='hidden' >
                <input name='id' type='hidden'  >
                <input name='Nname' type='hidden' >
                <input name='Nauthors' type='hidden' >
                <input name='Ndescription' type='hidden' >
                </form>
                <table width=100% border=0 cellpadding=1 cellspacing=4><tr valign=top><td><h3>Журнал</h3>
                <table width=100% border=0 cellpadding=2 cellspacing=1>
                <tr><td colspan=5></td><td align=right><a href=\"#\" onclick=\"document.location='mainiws.php?go=filearchive_A&act=addFile".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."'; return false;\">добавить файл</a></td></tr>
                <tr><td colspan=2></td><td colspan=4 bgcolor=#EBEBEB><b>№ журнала</b> <select name=sortBy onChange=\"document.location='mainiws.php?go=filearchive_A'+sortBy.value+'&start=$start'; return false;\">
                <option value=\"\">Все</option>";

                        $resDep=mysql_query("SELECT id,name FROM iws_arfiles_A_department ORDER BY name DESC");
                        if(mysql_numrows($resDep)>=1){
                                while($arr=mysql_fetch_row($resDep)){
                                        if($sortBy==$arr[0]){
                                                $content.="<option value='&sortBy=".$arr[0]."' selected>".$arr[1]."</option>";
                                        } else {
                                                $content.="<option value='&sortBy=".$arr[0]."'>".$arr[1]."</option>";
                                        }
                                }
                        }

                $content.="</select>&nbsp;&nbsp;&nbsp;<a title=\"Добавить/Удалить журнал\" href=\"mainiws.php?go=filearchive_A&act=department\"><b>Редактировать</b></a>
                           &nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp; <a title=\"Добавить/Удалить журнал\" href=\"mainiws.php?go=filearchive_A&act=rubric\"><b>Рубрики</b></a>
                           </td></tr>";

                if($prom){

                        $res=mysql_query("SELECT A.id,(SELECT B.name FROM iws_arfiles_A_department B WHERE B.id=A.department), DATE_FORMAT(A.data,'%e.%m.%Y %T'), A.name,  A.authors, LEFT(A.description,200), A.file,(SELECT C.name FROM iws_arfiles_A_rubric C WHERE C.id=A.rubric)
                         FROM iws_arfiles_A_records A ".(($sortBy) ? 'WHERE A.department='.$sortBy : '')." ORDER BY A.data DESC LIMIT ".($start-1).",50");
   
                        $content.="<tr><td></td><td colspan=5>".$prom."<br></td></tr><tr align=center><td></td><td class=usr>Дата</td><td class=usr>Название, авторы</td><td class=usr>Файл</td><td class=usr>№ и рубрика</td><td class=usr>Операции</td></tr>";

                        if(mysql_numrows($res)>=1){
                                include("inc/config.inc.php");
                                $cls="menu1";
                                $i=$start;
                                if(!$sortBy) $sortBy=0;

                                while($arr=mysql_fetch_row($res)){
                                        if($cls=="menu1"){ $cls="menu"; } else { $cls="menu1"; }
                        
                                        $content.="<tr valign=top><td align=right>".($i++).".</td>
                                                                        <td class=$cls align=center>".$arr[2]."</td>
                                                                        <td class=$cls><p><b>".$arr[3]."</b><br>".$arr[4]."</p><p>".$arr[5]."</p></td>
                                                                        <td class=$cls><a href=\"/FilesForDownload_A/".$arr[6]."\">".$arr[6]."</a><br><br>".display_size($arr[6])."</td>
                                                                        <td class=$cls>".$arr[1]."<br><br>".$arr[7]."</td>";
                                        $content.="<td class=$cls>
                                                                        [ <b><a href=\"#\" title=\"редактировать\" onclick=\"document.location='mainiws.php?go=filearchive_A&act=edtFile&id=".$arr[0].(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."'; return false;\">редактировать</a></b> ]&nbsp;&nbsp;<a href=\"#\" onclick=\"delOkFile('mainiws.php?go=filearchive_A&act=delFileOk&sortBy=$sortBy&start=$start&id=".$arr[0]."','".$arr[6]."'); return false;\"><font color=#ff0000>удалить</font></a>
                                                                        </td></tr>";
                                }
                                unset($res);
                        } 
                        $content.="<tr><td></td><td colspan=5><br>".$prom."</td></tr>";
        
                } else {
                        $content.="<tr><td colspan=2></td><td colspan=4><br><br><h4>Извините, на сервере нет файлов!</h4></td></tr>";
                }

                $content.="<tr><td colspan=5></td><td align=right><a href=\"#\" onclick=\"document.location='mainiws.php?go=filearchive_A&act=addFile".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."'; return false;\">добавить файл</a></td></tr>"
                ."</table></td></tr></table>";

                unset($sortBy,$start);

                return $content;
}


//--------------------------------------------------------------------------------------------------

function rubric(){
                $content.="
                <script><!--
                function delOkDep(urli,nmk)
                {
                        if(confirm('Вы действительно хотите удалить рубрику \"'+nmk+'\"?')) document.location=urli;
                }

                function addDep()
                {
                        var arr = null;
                        arr = showModalDialog(\"fnciws/filearchive_A/gdialog.php?evtype=addrubric\", null, \"dialogWidth:410px; dialogHeight:120px; status:no;\");
                        if (arr != null) document.location='mainiws.php?go=filearchive_A&act=addrubric&nm='+arr[\"cname\"];
                }

                function edtDep(nme,did)
                {
                        var args = new Array();
                        var arr = null;
                        args[\"cname\"]=nme;
                        arr = showModalDialog(\"fnciws/filearchive_A/gdialog.php?evtype=edtrubric\", args, \"dialogWidth:410px; dialogHeight:120px; status:no;\");
                        if (arr != null) document.location='mainiws.php?go=filearchive_A&act=edtrubric&id='+did+'&nm='+arr[\"cname\"];
                }
                //--></script>
                <table align=center width=70% border=0 cellpadding=2 cellspacing=1>
                <tr><td><a href=\"#\" onclick=\"document.location='mainiws.php?go=filearchive_A'; return false;\">Вернуться в журнал</a></td><td><a href=\"#\" onclick=\"addDep(); return false;\">добавить название рубрики</a></td></tr>
                <tr align=center><td class=usr>Название рубрики</td><td class=usr></td></tr>\n";

                $res=mysql_query("SELECT id,name FROM iws_arfiles_A_rubric ORDER BY name");
                if(mysql_numrows($res)>=1){
                        $cl="menu1";
                        while($arr=mysql_fetch_row($res)){
                                if($cl=="menu1"){ $cl="menu"; } else { $cl="menu1"; }
                                $content.="<tr class=$cl><td><b>".$arr[1]."</b>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>"
                                        ."[<a href=\"#\" onclick=\"edtDep('".$arr[1]."',".$arr[0]."); return false;\">редактировать</a>] "
                                        ."[<a href=\"#\" onclick=\"delOkDep('mainiws.php?go=filearchive_A&act=delrubricOk&id=".$arr[0]."','".$arr[1]."'); return false;\"><font color=#ff0000>удалить</font></a>]</td></tr>\n";
                        }
                } else {
                        $content.="<tr><td colspan=3>Извините, в базе данных нет названий рубрик!</td></tr>";
                } 
                $content.="<tr><td><a href=\"#\" onclick=\"document.location='mainiws.php?go=filearchive_A'; return false;\">Вернуться в журнал</a></td><td><a href=\"#\" onclick=\"addDep(); return false;\">добавить название рубрики</a></td></tr>"
                ."</table>";
                return $content;
}


//--------------------------------------------------------------------------------------------------

function department(){
                $content.="
                <script><!--
                function delOkDep(urli,nmk)
                {
                        if(confirm('Вы действительно хотите удалить журнал \"'+nmk+'\"?')) document.location=urli;
                }

                function addDep()
                {
                        var arr = null;
                        arr = showModalDialog(\"fnciws/filearchive_A/gdialog.php?evtype=adddepartment\", null, \"dialogWidth:410px; dialogHeight:120px; status:no;\");
                        if (arr != null) document.location='mainiws.php?go=filearchive_A&act=adddepartment&nm='+arr[\"cname\"];
                }

                function edtDep(nme,did)
                {
                        var args = new Array();
                        var arr = null;
                        args[\"cname\"]=nme;
                        arr = showModalDialog(\"fnciws/filearchive_A/gdialog.php?evtype=edtdepartment\", args, \"dialogWidth:410px; dialogHeight:120px; status:no;\");
                        if (arr != null) document.location='mainiws.php?go=filearchive_A&act=edtdepartment&id='+did+'&nm='+arr[\"cname\"];
                }
                //--></script>
                <table align=center width=70% border=0 cellpadding=2 cellspacing=1>
                <tr><td><a href=\"#\" onclick=\"document.location='mainiws.php?go=filearchive_A'; return false;\">Вернуться в журнал</a></td><td><a href=\"#\" onclick=\"addDep(); return false;\">добавить № журнала</a></td></tr>
                <tr align=center><td class=usr>Название раздела</td><td class=usr></td></tr>\n";

                $res=mysql_query("SELECT id,name FROM iws_arfiles_A_department ORDER BY name DESC");
                if(mysql_numrows($res)>=1){
                        $cl="menu1";
                        while($arr=mysql_fetch_row($res)){
                                if($cl=="menu1"){ $cl="menu"; } else { $cl="menu1"; }
                                $content.="<tr class=$cl><td><b>".$arr[1]."</b>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>"
                                        ."[<a href=\"#\" onclick=\"edtDep('".$arr[1]."',".$arr[0]."); return false;\">редактировать</a>] "
                                        ."[<a href=\"#\" onclick=\"delOkDep('mainiws.php?go=filearchive_A&act=deldepartmentOk&id=".$arr[0]."','".$arr[1]."'); return false;\"><font color=#ff0000>удалить</font></a>]</td></tr>\n";
                        }
                } else {
                        $content.="<tr><td colspan=3>Извините, в базе данных нет названий разделов!</td></tr>";
                } 
                $content.="<tr><td><a href=\"#\" onclick=\"document.location='mainiws.php?go=filearchive_A'; return false;\">Вернуться в журнал</a></td><td><a href=\"#\" onclick=\"addDep(); return false;\">добавить № журнала</a></td></tr>"
                ."</table>";
                return $content;
}

//------------------------------------------------------------------------------------------------------------------------------------


function catalogOk()
{
        global $act,$id,$sortBy,$start,$newCat,$nm,$fileName;
        
        switch($act){

        case "replaceFileOk":
                if(!mysql_query("UPDATE iws_arfiles_A_records SET department=$newCat WHERE id=$id")){
                        header("location: ?go=filearchive_A&err=1".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : ''));
                        return;
                } else {                
                        header("location: ?go=filearchive_A".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : ''));
                        return;
                }
        break;
        
        case "editFileOk":
                if(!mysql_query("UPDATE iws_arfiles_A_records SET `authors`='".$_POST[Nauthors]."',`name`='".$_POST[Nname]."',`description`='".$_POST[Ndescription]."' WHERE `id`='".$id."'")){
                       header("location: ?go=filearchive_A&err=4".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : ''));
                        return;
                } else {        
                        header("location: ?go=filearchive_A".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : ''));
                        return;
                }
                
        break;
        

        case "delFileOk":
                include('fnciws/filearchive_A/faFunctions.php');
                delFile($fileName);
                mysql_query("DELETE FROM iws_arfiles_A_records WHERE id=$id");

                header("location: ?go=filearchive_A".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : ''));
                return;
        break;


        case "deldepartmentOk":
                mysql_query("DELETE FROM iws_arfiles_A_department WHERE id=$id");

                header("location: ?go=filearchive_A&act=department");
                return;
        break;

        case "adddepartment":
                $nm=trim($nm);
                if(empty($nm)) { 
                        header("location: ?go=filearchive_A&act=department&err=2");
                        return;
                }
                $nm=addslashes($nm);
                if(!mysql_query("INSERT INTO iws_arfiles_A_department (name) VALUES ('$nm')")){
                        header("location: ?go=filearchive_A&act=department&err=1");
                        return;
                } else {                
                        header("location: ?go=filearchive_A&act=department");
                        return;
                }
        break;

        case "edtdepartment":
                $nm=trim($nm);
                if(empty($nm)) { 
                        header("location: ?go=filearchive_A&act=department&err=2");
                        return;
                }
                $nm=addslashes($nm);
                if(!mysql_query("UPDATE iws_arfiles_A_department SET name='$nm' WHERE id=$id")){
                        header("location: ?go=filearchive_A&act=department&err=1");
                        return;
                } else {                
                        header("location: ?go=filearchive_A&act=department");
                        return;
                }
        break;

        case "delrubricOk":
                mysql_query("DELETE FROM iws_arfiles_A_rubric WHERE id=$id");

                header("location: ?go=filearchive_A&act=rubric");
                return;
        break;

        case "addrubric":
                $nm=trim($nm);
                if(empty($nm)) { 
                        header("location: ?go=filearchive_A&act=rubric&err=2");
                        return;
                }
                $nm=addslashes($nm);
                if(!mysql_query("INSERT INTO iws_arfiles_A_rubric (name) VALUES ('$nm')")){
                        header("location: ?go=filearchive_A&act=rubric&err=1");
                        return;
                } else {                
                        header("location: ?go=filearchive_A&act=rubric");
                        return;
                }
        break;

        case "edtrubric":
                $nm=trim($nm);
                if(empty($nm)) { 
                        header("location: ?go=filearchive_A&act=rubric&err=2");
                        return;
                }
                $nm=addslashes($nm);
                if(!mysql_query("UPDATE iws_arfiles_A_rubric SET name='$nm' WHERE id=$id")){
                        header("location: ?go=filearchive_A&act=rubric&err=1");
                        return;
                } else {                
                        header("location: ?go=filearchive_A&act=rubric");
                        return;
                }
        break;
}
}


?>