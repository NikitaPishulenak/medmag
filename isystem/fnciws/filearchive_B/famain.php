<?php

if($act=="replaceFileOk" || $act=="delFileOk" || $act=="editFileOk" || $act=="deldepartmentOk" || $act=="adddepartment" || $act=="edtdepartment"){
        $cont=catalogOk();      
} else {
        $cont=admin_catalog();          
}

function admin_catalog()
{
        global $act,$err;

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


function addFileTOk(){
global $namepos,$author,$shortcontent,$department,$sortBy,$start;

        $namepos=trim($namepos);
        $author=trim($author);
        $shortcontent=trim($shortcontent);
        if(empty($namepos) || empty($shortcontent) || empty($author)) { header("location: ?go=filearchive_B&act=addFile".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=2"); return; }

        include('fnciws/filearchive_B/faFunctions.php');
        $retFileName=copy_file_toserver();
        if(!$retFileName) { header("location: ?go=filearchive_B&act=addFile".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=3"); return; }

        $namepos=addslashes($namepos);
        $shortcontent=preg_replace("#(\r)\n#si", "<br>",addslashes($shortcontent));
        $author=addslashes($author);
        $unicumC=unicumId();

        $sql = "INSERT INTO iws_arfiles_B_records (department,pse,authors,name,description,file,data) VALUES ($department,'$unicumC','$author','$namepos','$shortcontent','$retFileName',NOW())";

        if(!mysql_query($sql)){
                delFile($retFileName);
                header("location: ?go=filearchive_B&act=addFile".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."&err=1");
                return;
        } else {
                header("location: ?go=filearchive_B".(($sortBy) ? '&sortBy='.$sortBy : ''));
                return;
        }
}




function addFileT()
{
        global $sortBy,$start;
        $content.="<h5>Журнал Военная медицина / Добавить файл</h5>
                <script><!--
                function tosubmit(){
                        if(formS.namepos.value && formS.shortcontent.value && formS.author.value && formS.filearchive.value){
                                formS.submit();
                        } else {
                                alert (\"В добавлении отказано! Не введена вся информация.                    \");
                        }
                }
                //--></script>
                <form action=\"mainiws.php\" name=formS method=\"post\" enctype=\"multipart/form-data\">
                <input type=\"hidden\" name=go value=filearchive_B>
                <input type=\"hidden\" name=act value=addFileOk>
                ".(($sortBy) ? '<input type=hidden name=sortBy value='.$sortBy.'>' : '').(($start) ? '<input type=hidden name=start value='.$start.'>' : '')."

                <table width=100% border=0 cellpadding=0 cellspacing=10>
                <tr><td><b>Разделы</b><br><select name=department>
                <option value=0>Без раздела</option>";

                        $resDep=mysql_query("SELECT id,name FROM iws_arfiles_B_department ORDER BY name");
                        if(mysql_numrows($resDep)>=1){
                                while($arr=mysql_fetch_row($resDep)){
                                        if($sortBy==$arr[0]){
                                                $content.="<option value=".$arr[0]." selected>".$arr[1]."</option>";
                                        } else {
                                                $content.="<option value=".$arr[0].">".$arr[1]."</option>";
                                        }
                                }
                        }

                $content.="</select></td></tr>
                                <tr><td>
                                <p><b>Название</b><br><input type=text name=namepos style=\"width=100%\"></p>
                                <p><b>Авторы</b><br><input type=text name=author style=\"width=100%\"></p>
                                <p><b>Описание</b><br><textarea name=shortcontent rows=8 style=\"width:100%\"></textarea></p>
                                <p><b>Файл</b><br><input type=file name=filearchive size=50></p>
                                </td></tr>
                                <tr height=80><td><input class=but type=\"button\" value=\"Добавить\" onclick=\"tosubmit(); return false;\">&nbsp;&nbsp;
                                <input class=but type=\"button\" value=\"Отмена\" onClick=\"document.location='mainiws.php?go=filearchive_B".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."'; return false;\">
                                </td></tr></table></form><br>";

        unset($sortBy,$resDep);

return $content;
}


//-----------------------------------------------------------------------------------------------------------------------------


function defaultView(){
global $sortBy,$start,$QUERY_STRING;

                if(!$start) $start=1;

                include('fnciws/filearchive_B/faFunctions.php');
                $prom=numlink($start,ereg_replace("&start=".$start,"",$QUERY_STRING),"iws_arfiles_B_records",$sortBy);


                $content.="<script><!--
                function delOkFile(urli,nmk){
                        if(confirm('Вы действительно хотите удалить файл \"'+nmk+'\"?       ')) document.location=urli+'&fileName='+nmk;
                }

                function replaceOkFile(urlR)
                {
                        var arr = null;
                        arr = showModalDialog(\"fnciws/filearchive_B/gdialog.php?evtype=replaceFile\", null, \"dialogWidth:410px; dialogHeight:120px; status:no;\");
                        if (arr != null) document.location=urlR+'&newCat='+arr;
                }
                

                function editOkFile(sort,start,ids)
                {   var arr = null;
                       arr = showModalDialog(\"fnciws/filearchive_B/gdialog.php?evtype=editFile&id=\"+ids+\"\", null, \"dialogWidth:410px; dialogHeight:260px; status:no;\");
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
                <input name='go' type='hidden' value='filearchive_B'>
                <input name='act' type='hidden' value='editFileOk' >
                <input name='sortBy' type='hidden'  >
                <input name='start' type='hidden' >
                <input name='id' type='hidden'  >
                <input name='Nname' type='hidden' >
                <input name='Nauthors' type='hidden' >
                <input name='Ndescription' type='hidden' >
                </form>

                <table width=100% border=0 cellpadding=1 cellspacing=4><tr valign=top><td><h5>Журнал Военная медицина</h5>
                <table width=100% border=0 cellpadding=2 cellspacing=1>
                <tr><td colspan=5></td><td align=right><a href=\"#\" onclick=\"document.location='mainiws.php?go=filearchive_B&act=addFile".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."'; return false;\">добавить файл</a></td></tr>
                <tr><td colspan=2></td><td colspan=4 bgcolor=#EBEBEB><b>Раздел</b> <select name=sortBy onChange=\"document.location='mainiws.php?go=filearchive_B'+sortBy.value+'&start=$start'; return false;\">
                <option value=\"\">Все</option>";

                        $resDep=mysql_query("SELECT id,name FROM iws_arfiles_B_department ORDER BY name");
                        if(mysql_numrows($resDep)>=1){
                                while($arr=mysql_fetch_row($resDep)){
                                        if($sortBy==$arr[0]){
                                                $content.="<option value='&sortBy=".$arr[0]."' selected>".$arr[1]."</option>";
                                        } else {
                                                $content.="<option value='&sortBy=".$arr[0]."'>".$arr[1]."</option>";
                                        }
                                }
                        }

                $content.="</select>&nbsp;&nbsp;&nbsp;<a title=\"Добавить/Удалить раздел\" href=\"mainiws.php?go=filearchive_B&act=department\"><b>Редактировать</b></a></td></tr>";

                if($prom){

                        $res=mysql_query("SELECT A.id,(SELECT B.name FROM iws_arfiles_B_department B WHERE B.id=A.department), DATE_FORMAT(A.data,'%e.%m.%Y %T'), A.name,  A.authors, LEFT(A.description,200), A.file
                         FROM iws_arfiles_B_records A ".(($sortBy) ? 'WHERE A.department='.$sortBy : '')." ORDER BY A.data DESC LIMIT ".($start-1).",50");
   
                        $content.="<tr><td></td><td colspan=5>".$prom."<br></td></tr><tr align=center><td></td><td class=usr>Дата</td><td class=usr>Название, авторы</td><td class=usr>Файл</td><td class=usr>Раздел</td><td class=usr>Операции</td></tr>";

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
                                                                        <td class=$cls>".$arr[6]."<br>".display_size($arr[6])."</td>
                                                                        <td class=$cls>".$arr[1]."</td>";
                                        $content.="<td class=$cls>
                                                                        [<a href=\"#\" title=\"сменить раздел\" onclick=\"replaceOkFile('mainiws.php?go=filearchive_B&act=replaceFileOk&sortBy=$sortBy&start=$start&id=".$arr[0]."'); return false;\">сменить раздел</a>] 
                                                                        [<a href=\"#\" title=\"редактировать\" onclick=\"editOkFile(".$sortBy.",".$start.",".$arr[0]."); return false;\">редактировать</a>]
                                                                        [<a href=\"#\" onclick=\"delOkFile('mainiws.php?go=filearchive_B&act=delFileOk&sortBy=$sortBy&start=$start&id=".$arr[0]."','".$arr[6]."'); return false;\"><font color=#ff0000>удалить</font></a>]
                                                                        </td></tr>";
                                }
                                unset($res);
                        } 
                        $content.="<tr><td></td><td colspan=5><br>".$prom."</td></tr>";
        
                } else {
                        $content.="<tr><td colspan=2></td><td colspan=4><br><br><h4>Извините, на сервере нет файлов!</h4></td></tr>";
                }

                $content.="<tr><td colspan=5></td><td align=right><a href=\"#\" onclick=\"document.location='mainiws.php?go=filearchive_B&act=addFile".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : '')."'; return false;\">добавить файл</a></td></tr>"
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
                        if(confirm('Вы действительно хотите удалить раздел \"'+nmk+'\"?')) document.location=urli;
                }

                function addDep()
                {
                        var arr = null;
                        arr = showModalDialog(\"fnciws/filearchive_B/gdialog.php?evtype=adddepartment\", null, \"dialogWidth:410px; dialogHeight:120px; status:no;\");
                        if (arr != null) document.location='mainiws.php?go=filearchive_B&act=adddepartment&nm='+arr[\"cname\"];
                }

                function edtDep(nme,did)
                {
                        var args = new Array();
                        var arr = null;
                        args[\"cname\"]=nme;
                        arr = showModalDialog(\"fnciws/filearchive_B/gdialog.php?evtype=edtdepartment\", args, \"dialogWidth:410px; dialogHeight:120px; status:no;\");
                        if (arr != null) document.location='mainiws.php?go=filearchive_B&act=edtdepartment&id='+did+'&nm='+arr[\"cname\"];
                }
                //--></script>
                <table align=center width=70% border=0 cellpadding=2 cellspacing=1>
                <tr><td><a href=\"#\" onclick=\"document.location='mainiws.php?go=filearchive_B'; return false;\">вернуться в Журнал Военная медицина</a></td><td><a href=\"#\" onclick=\"addDep(); return false;\">добавить название раздела</a></td></tr>
                <tr align=center><td class=usr>Название раздела</td><td class=usr></td></tr>\n";

                $res=mysql_query("SELECT id,name FROM iws_arfiles_B_department ORDER BY name");
                if(mysql_numrows($res)>=1){
                        $cl="menu1";
                        while($arr=mysql_fetch_row($res)){
                                if($cl=="menu1"){ $cl="menu"; } else { $cl="menu1"; }
                                $content.="<tr class=$cl><td><b>".$arr[1]."</b>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>"
                                        ."[<a href=\"#\" onclick=\"edtDep('".$arr[1]."',".$arr[0]."); return false;\">редактировать</a>] "
                                        ."[<a href=\"#\" onclick=\"delOkDep('mainiws.php?go=filearchive_B&act=deldepartmentOk&id=".$arr[0]."','".$arr[1]."'); return false;\"><font color=#ff0000>удалить</font></a>]</td></tr>\n";
                        }
                } else {
                        $content.="<tr><td colspan=3>Извините, в базе данных нет названий разделов!</td></tr>";
                } 
                $content.="<tr><td><a href=\"#\" onclick=\"document.location='mainiws.php?go=filearchive_B'; return false;\">вернуться в Журнал Военная медицина</a></td><td><a href=\"#\" onclick=\"addDep(); return false;\">добавить название раздела</a></td></tr>"
                ."</table>";
                return $content;
}

//------------------------------------------------------------------------------------------------------------------------------------


function catalogOk()
{
        global $act,$id,$sortBy,$start,$newCat,$nm,$fileName;
        
        switch($act){

        case "replaceFileOk":
                if(!mysql_query("UPDATE iws_arfiles_B_records SET department=$newCat WHERE id=$id")){
                        header("location: ?go=filearchive_B&err=1".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : ''));
                        return;
                } else {                
                        header("location: ?go=filearchive_B".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : ''));
                        return;
                }
        break;
        
        case "editFileOk":
                if(!mysql_query("UPDATE iws_arfiles_B_records SET `authors`='".$_POST[Nauthors]."',`name`='".$_POST[Nname]."',`description`='".$_POST[Ndescription]."' WHERE `id`='".$id."'")){
                       header("location: ?go=filearchive_B&err=4".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : ''));
                       return;
                } else {        
                        header("location: ?go=filearchive_B".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : ''));
                        return;
                }
                
        break;
        

        case "delFileOk":
                include('fnciws/filearchive_B/faFunctions.php');
                delFile($fileName);
                mysql_query("DELETE FROM iws_arfiles_B_records WHERE id=$id");

                header("location: ?go=filearchive_B".(($sortBy) ? '&sortBy='.$sortBy : '').(($start) ? '&start='.$start : ''));
                return;
        break;


        case "deldepartmentOk":
                mysql_query("DELETE FROM iws_arfiles_B_department WHERE id=$id");

                header("location: ?go=filearchive_B&act=department");
                return;
        break;

        case "adddepartment":
                $nm=trim($nm);
                if(empty($nm)) { 
                        header("location: ?go=filearchive_B&act=department&err=2");
                        return;
                }
                $nm=addslashes($nm);
                if(!mysql_query("INSERT INTO iws_arfiles_B_department (name) VALUES ('$nm')")){
                        header("location: ?go=filearchive_B&act=department&err=1");
                        return;
                } else {                
                        header("location: ?go=filearchive_B&act=department");
                        return;
                }
        break;

        case "edtdepartment":
                $nm=trim($nm);
                if(empty($nm)) { 
                        header("location: ?go=filearchive_B&act=department&err=2");
                        return;
                }
                $nm=addslashes($nm);
                if(!mysql_query("UPDATE iws_arfiles_B_department SET name='$nm' WHERE id=$id")){
                        header("location: ?go=filearchive_B&act=department&err=1");
                        return;
                } else {                
                        header("location: ?go=filearchive_B&act=department");
                        return;
                }
        break;
}
}


?>