<?php


if($act=="replaceAlbumOk" || $act=="delAlbumOk" || $act=="editAlbumOk" || $act=="deldepartmentOk" || $act=="adddepartment" || $act=="edtdepartment" || $act=="editPhotoOk" || $act=="delPhotoOk" ){
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
                                $ct="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>Произошла ошибка. Не удалось загрузить изображение.</td></tr></table><br>";
                        break;
                        case 4:
                                $ct="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>Произошла ошибка. Не удалось обновить информацию об альбоме.</td></tr></table><br>";
                        break;
                        case 5:
                                $ct="<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>Произошла ошибка. Не удалось создать превью изображения.</td></tr></table><br>";
                        break;

                }

        switch($act){

                case "NotaddPhoto":
                      include('fnciws/photoalbums/faFunctions.php');
                      delFile($_GET[imgFile]);
                      header("location: ?go=photoalbums&act=openAlbum".(($_GET[sortBy]) ? '&sortBy='.$_GET[sortBy] : '&sortBy=0').(($_GET[start]) ? '&start='.$_GET[start] : '').(($_GET[mode]) ? '&mode=1' : '')."&id=".$_GET[id]);
                      return;
                break;

                case "addPhoto":
                        $ct.=AddPhoto();
                break;

                case "addPhotoOk":
                        $ct.=AddPhotoOk();
                break;

                case "openAlbum":
                        $ct.=OpenAlbum();
                break;
        
                case "addAlbumOk":
                        $ct.=addAlbumTOk();
                break;
                case "addAlbum":
                        $ct.=addAlbumT();
                break;
                case "department":
                        $ct.=department();
                break;
                case "hashtags":
                        $ct.=HashTags();
                break;
                case "addHashTagOk":
                        $ct.=addHashTagOk();
                break;
                case "delhashtags":
                        $ct.=DelHashTags();
                break;
            case "gethashrangs":
                        $ct.=GetHashRangs();
                break;
                default:
                        $ct.=defaultView();
                break;
        }
        unset($act,$err);
        return $ct;
}

//----------------------------------------------------------------------------------------------------------------------------

function AddPhotoOk()
{
   global $sortBy,$mode,$start,$aid,$imgFile,$cropW,$cropH,$imgNW,$imgNH,$cropY,$cropX,$alt;


   include('fnciws/photoalbums/faFunctions.php');
   if(img_resize($imgFile, $cropW, $cropH, $imgNW, $imgNH, $cropY, $cropX)){
        $alt=addslashes(trim($alt));
        if(!mysql_query("INSERT INTO iws_photos_records (aid,alt,file) VALUES ($aid,'$alt','$imgFile')")){
                delFile($imgFile);
                header("location: ?go=photoalbums&act=openAlbum".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : '')."&id=".$aid."&err=1");
                return;
        } else {
                header("location: ?go=photoalbums&act=openAlbum".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : '')."&id=".$aid);
                return;
        }

   } else {
      delFile($imgFile);
      header("location: ?go=photoalbums&act=openAlbum".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : '')."&id=".$aid."&err=5");
      return;
   }
}


//----------------------------------------------------------------------------------------------------------------------------

function AddPhoto()
{
   global $sortBy,$mode,$start,$aid,$mode;

       include('fnciws/photoalbums/faFunctions.php');
       $retFileName=copy_file_toserver();
       if(!$retFileName[0]) { header("location: ?go=photoalbums&act=openAlbum".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : '')."&id=".$aid."&err=3");  return; }

        $arr=mysql_fetch_row(mysql_query("SELECT nWidth, nHeight FROM iws_photos_prefernce WHERE id=1"));
      $content="
      <script type=\"text/javascript\">
      var arr = null;
      arr = showModalDialog(\"fnciws/photoalbums/dialogCrop.php?nameF=".$retFileName[0]."&W=".$retFileName[1]."&H=".$retFileName[2]."&cW=".$arr[0]."&cH=".$arr[1]."\", null, \"dialogWidth:1000px; dialogHeight:700px; status:no; center:yes; scroll:yes; resizable:yes; maximize:yes;\");
      if (arr != null){
         document.location='mainiws.php?go=photoalbums&act=addPhotoOk".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : '')."&aid=".$aid."&imgFile=".$retFileName[0]."&cropW=".$arr[0]."&cropH=".$arr[1]."&imgNW='+arr[\"W\"]+'&imgNH='+arr[\"H\"]+'&cropY='+arr[\"cY\"]+'&cropX='+arr[\"cX\"]+'&alt='+arr[\"alt\"];
      } else {
         document.location='mainiws.php?go=photoalbums&act=NotaddPhoto".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : '')."&id=".$aid."&imgFile=".$retFileName[0]."';
      }
      </script>

";


   unset($sortBy);

   return $content;
}

//----------------------------------------------------------------------------------------------------------------------------

function OpenAlbum()
{
        global $sortBy,$mode,$start,$id;
        $arr=mysql_fetch_row(mysql_query("SELECT (SELECT B.name FROM iws_photos_category B WHERE B.id=A.cid), DATE_FORMAT(A.data,'%e.%m.%Y в %T'), A.title, A.description FROM iws_photos_albums A WHERE A.id=".$id));


        $content="<h5>Фотогалерея / Альбом</h5>
                <script><!--
                function tosubmit(){
                        if(formS.fileImage.value){
                                formS.submit();
                        } else {
                                alert (\"Не выбран файл изображения!           \");
                        }
                }


                function delOkPhoto(urli){
                        if(confirm('Вы действительно хотите удалить изображение?       ')) document.location=urli;
                }


                function editOkPhoto(urli,ids)
                {   var arr = null;
                       arr = showModalDialog(\"fnciws/photoalbums/gdialog.php?evtype=editPhoto&id=\"+ids+\"\", null, \"dialogWidth:450px; dialogHeight:240px; status:no;\");
                       if (arr != null)
                       {
                           document.location=urli+'&alt='+arr['Ndescription'];
                       }
                       
                }

                //--></script>
                <form action=\"mainiws.php\" name=formS method=\"post\" enctype=\"multipart/form-data\">
                <input type=\"hidden\" name=go value=photoalbums>
                <input type=\"hidden\" name=act value=addPhoto>
                <input type=\"hidden\" name=aid value=".$id.">
                ".(($sortBy) ? '<input type=hidden name=sortBy value='.$sortBy.'>' : '<input type=hidden name=sortBy value=0>').(($start) ? '<input type=hidden name=start value='.$start.'>' : '').(($_GET[mode]) ? '<input type=hidden name=mode value=1>' : '')."

                <table width=100% border=0 cellpadding=0 cellspacing=10>
                <tr><td><h5>".$arr[2]."</h5><i>Рубрика</i>: <b>".(($arr[0]) ? $arr[0] : "Без рубрики")."</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Дата создания альбома</i>: <b>".$arr[1]."</b>
                <div style=\"float:right\"><a href=\"mainiws.php?go=photoalbums".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : '')."\">Выйти из альбома</a></div></td></tr>
                <tr><td>".$arr[3]."<hr></td></tr>
                <tr><td><b>Файл изображения</b> <span style=\"font-size:10px\">(не более 7Mb, форматы: jpg, gif, png)</span><br><br>
                <input type=file name=fileImage size=40> <input type=\"button\" value=\"Добавить\" onclick=\"tosubmit(); return false;\"></td></tr>
                <tr><td style=\"padding-top:20px;\">";

         $res=mysql_query("SELECT id,file,alt FROM iws_photos_records WHERE aid=".$id." ORDER BY id");
         if(mysql_numrows($res)>=1){
            $content.="&nbsp;&nbsp;Всего фотографий в альбоме: ".mysql_numrows($res);            
            while($arr=mysql_fetch_row($res)){
               $content.="<div class=imgAlbum><img src=\"../PhotoAlbums/s_".$arr[1]."\" alt=\"".$arr[2]."\"><br><br>
               [ <a href=\"#\" onclick=\"editOkPhoto('mainiws.php?go=photoalbums&act=editPhotoOk".(($mode) ? '&mode=1' : '')."&sortBy=$sortBy&start=$start&id=".$id."&idPhoto=".$arr[0]."',".$arr[0]."); return false;\">".(($arr[2]) ? 'Редактировать описание' : 'Добавить описание')."</a> ]<br>
               [ <a href=\"#\" onclick=\"delOkPhoto('mainiws.php?go=photoalbums&act=delPhotoOk".(($mode) ? '&mode=1' : '')."&sortBy=$sortBy&start=$start&id=".$id."&idPhoto=".$arr[0]."&fileName=".$arr[1]."'); return false;\"><font color=#ff0000>удалить</font></a> ]
               </div>";
            }

         } else {
            $content.="<br><br><h4>Пока в этом альбоме фотографий нет!</h4>";
         }
         $content.="</td></tr></table></form><br>";

   unset($sortBy);

   return $content;
}




//----------------------------------------------------------------------------------------------------------------------------


function addAlbumTOk(){
global $dt,$Ntitle,$shortcontent,$department,$sortBy,$mode,$start,$hashtag;

        $Ntitle=trim($Ntitle);
        $shortcontent=trim($shortcontent);
        $dt=substr($dt,0,10);
        $dt=explode(".",$dt);
        $hashtag ="#".(($hashtag && count($hashtag)>=1) ? implode("#",$hashtag) : '')."#";
        if(empty($dt) || empty($Ntitle) || !is_numeric($dt[0]) || !is_numeric($dt[1]) || !is_numeric($dt[2]) || !checkdate($dt[1],$dt[0],$dt[2])) { header("location: ?go=photoalbums&act=addAlbum".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : '')."&err=2"); return; }

        $Ntitle=addslashes($Ntitle);
        $shortcontent=preg_replace("#(\r)\n#si", "<br>",addslashes($shortcontent));

        $sql = "INSERT INTO iws_photos_albums (cid,data,title,description,hashtags) VALUES ($department,CONCAT('".$dt[2]."-".$dt[1]."-".$dt[0]." ', CURTIME()),'$Ntitle','$shortcontent','$hashtag')";

        if(!mysql_query($sql)){
            header("location: ?go=photoalbums&act=addAlbum".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : '')."&err=1");
            return;
        } else {
            header("location: ?go=photoalbums&act=openAlbum".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($mode) ? '&mode=1' : '')."&id=".mysql_insert_id());
            return;
        }
}



function addAlbumT()
{
        global $sortBy,$mode,$start;
        $content.="<h5>Фотогалерея / Добавить альбом</h5>
                <script><!--
                function tosubmit(){
                        if(formS.Ntitle.value && formS.dt.value){
                                formS.submit();
                        } else {
                                alert (\"В добавлении отказано! Не введена вся информация.                    \");
                        }
                }
                //--></script>
                <form action=\"mainiws.php\" name=formS method=\"post\">
                <input type=\"hidden\" name=go value=photoalbums>
                <input type=\"hidden\" name=act value=addAlbumOk>
                ".(($sortBy) ? '<input type=hidden name=sortBy value='.$sortBy.'>' : '<input type=hidden name=sortBy value=0>').(($start) ? '<input type=hidden name=start value='.$start.'>' : '').(($mode) ? '<input type=hidden name=mode value=1>' : '')."

                <table width=100% border=0 cellpadding=0 cellspacing=10 >
                <tr><td valign=top><b>Рубрики</b> <select name=department>
                <option value=0>Без рубрики</option>";

                        $resDep=mysql_query("SELECT id,name FROM iws_photos_category ORDER BY name");
                        if(mysql_numrows($resDep)>=1){
                                while($arr=mysql_fetch_row($resDep)){
                                        if($sortBy==$arr[0]){
                                                $content.="<option value=".$arr[0]." selected>".$arr[1]."</option>";
                                        } else {
                                                $content.="<option value=".$arr[0].">".$arr[1]."</option>";
                                        }
                                }
                        }
                $content.="</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Дата</b> <input name=dt size=8 maxlength=10 value=\"".date("d.m.Y")."\">
                     &nbsp;&nbsp;&nbsp;<b>Хэштэги</b> <select size='6' style=\"width:300px;\" multiple name='hashtag[]'>";
                $res=mysql_query("SELECT name FROM iws_photos_hashtags ORDER BY name");

                        if(mysql_numrows($res)>=1){
                                while($arr=mysql_fetch_row($res)){
                        $content.="<option value='".$arr[0]."'>".$arr[0]."</option>";  
                                }
                        }
                     
                     $content.="
                     </select></td></tr>
                                <tr><td>
                                <p><b>Название альбома</b><br><input type=text name=Ntitle style=\"width=100%\"></p>
                                <p><b>Описание</b><br><textarea name=shortcontent rows=8 style=\"width:100%\"></textarea></p>
                                </td></tr>
                                <tr height=80><td><input class=but type=\"button\" value=\"Добавить\" onclick=\"tosubmit(); return false;\">&nbsp;&nbsp;
                                <input class=but type=\"button\" value=\"Отмена\" onClick=\"document.location='mainiws.php?go=photoalbums".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : '')."'; return false;\">
                                </td></tr></table></form><br>";

        unset($sortBy,$resDep);

return $content;
}


//-----------------------------------------------------------------------------------------------------------------------------


function defaultView(){
global $sortBy,$mode,$start,$QUERY_STRING;

               if(!$start) $start=1;
               if(!isset($sortBy)) $sortBy=0;

               include('fnciws/photoalbums/faFunctions.php');
               $prom=numlink($start,ereg_replace("&start=".$start,"",$QUERY_STRING),"iws_photos_albums",$sortBy);


                $content="<script><!--
                function delOkAlbum(urli,nmk){
                        if(confirm('Вы действительно хотите удалить альбом '+nmk+'?       ')) document.location=urli+'&fileName='+nmk;
                }

                function replaceOkAlbum(urlR)
                {
                        var arr = null;
                        arr = showModalDialog(\"fnciws/photoalbums/gdialog.php?evtype=replaceAlbum\", null, \"dialogWidth:410px; dialogHeight:120px; status:no;\");
                        if (arr != null) document.location=urlR+'&newCat='+arr;
                }
                

                function editOkAlbum(sort,start,ids)
                {   var arr = null;
            
                       arr = showModalDialog(\"fnciws/photoalbums/gdialog.php?evtype=editAlbum&id=\"+ids+\"\", null, \"dialogWidth:410px; dialogHeight:360px; status:no;\");
                  if (arr != null)
                       {

                       hid.dt.value=arr['dt']; 
                       hid.Ntitle.value=arr['Ntitle'];
                       hid.Ndescription.value=arr['Ndescription'];
                       hid.id.value=ids; 
                  hid.hashtags.value=arr['HashTags'];

                       hid.sortBy.value=sort;
                       hid.start.value=start;
                       hid.submit();
                       
                       
                       }

                       
                } 
               
                ";

               if($mode){
                 $content.="
          
                    function DoEvent(str){
                      try{eval('parent.'+this.name+'_'+str);}catch(e){}
                    }

                   function RetVar(ids)
                   {
                     DoEvent(\"RetSelect(\"+ids+\")\");
                   }

                 ";
               }


     $content.="//--></script>
                <form action='mainiws.php' name='hid' method='post'>
                <input name='go' type='hidden' value='photoalbums'>
                <input name='act' type='hidden' value='editAlbumOk' >
                <input name='sortBy' type='hidden'  >
                <input name='start' type='hidden' >
                <input name='id' type='hidden'  >
                <input name='dt' type='hidden' >
            <input name='hashtags' type='hidden' >
                <input name='Ntitle' type='hidden' >
                <input name='Ndescription' type='hidden' >
                ".(($mode) ? '<input name=mode type=hidden value=1>' : '')."
                </form>
                
                <table width=100% border=0 cellpadding=1 cellspacing=4><tr valign=top><td><h5>Фотогалерея</h5>
                <table width=100% border=0 cellpadding=4 cellspacing=1>
                <tr><td colspan=4></td><td align=right><a href=\"#\" onclick=\"document.location='mainiws.php?go=photoalbums&act=addAlbum".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : '')."'; return false;\">добавить альбом</a></td></tr>
                <tr><td colspan=2></td><td colspan=4 bgcolor=#EBEBEB><b>Рубрики</b> <select name=sortBy onChange=\"document.location='mainiws.php?go=photoalbums".(($mode) ? '&mode=1' : '')."'+sortBy.value+'&start=$start'; return false;\">
                <option value=\"&sortBy=0\">Без рубрики</option>";

                        $resDep=mysql_query("SELECT id,name FROM iws_photos_category ORDER BY name");
                        if(mysql_numrows($resDep)>=1){
                                while($arr=mysql_fetch_row($resDep)){
                                        if($sortBy==$arr[0]){
                                                $content.="<option value=\"&sortBy=".$arr[0]."\" selected>".$arr[1]."</option>";
                                        } else {
                                                $content.="<option value=\"&sortBy=".$arr[0]."\">".$arr[1]."</option>";
                                        }
                                }
                        }

                $content.="</select>&nbsp;&nbsp;&nbsp;<a title=\"Добавить/Удалить рубрику\" href=\"mainiws.php?go=photoalbums&act=department".(($mode) ? '&mode=1' : '')."\"><b>Редактировать</b></a>&nbsp;&nbsp;&nbsp;<a href=\"mainiws.php?go=photoalbums&act=hashtags".(($mode) ? '&mode=1' : '')."\">Управление хэштэгами<a/></td></tr>";

                if($prom){

                        $res=mysql_query("SELECT A.id, DATE_FORMAT(A.data,'%e.%m.%Y %T'), A.title, A.description, (SELECT COUNT(B.id) FROM iws_photos_records B WHERE B.aid=A.id), A.hashtags 
                                          FROM iws_photos_albums A ".(($sortBy) ? 'WHERE cid='.$sortBy : 'WHERE cid=0')." ORDER BY data DESC LIMIT ".($start-1).",50");
   
                        $content.="<tr><td></td><td colspan=4>".$prom."<br></td></tr><tr align=center><td></td><td class=usr></td><td class=usr>Дата</td><td class=usr>Название альбома и описание</td><td class=usr>Операции</td></tr>";

                        if(mysql_numrows($res)>=1){
                                include("inc/config.inc.php");
                                $cls="menu1";
                                $i=$start;
                                if(!$sortBy) $sortBy=0;

                                while($arr=mysql_fetch_row($res)){
                                        if($cls=="menu1"){ $cls="menu"; } else { $cls="menu1"; }
                                        $ress=mysql_query("SELECT file FROM iws_photos_records WHERE aid=".$arr[0]." ORDER BY id LIMIT 1");
                                        if(mysql_numrows($ress)>=1){
                                           list($img)=mysql_fetch_row($ress);
                                           $img="<img src=\"../PhotoAlbums/s_".$img."\">";
                                        } else {
                                           $img="";        
                                        }
                                        $content.="<tr valign=top><td align=right>".($i++).".</td>
                                                                        <td class=$cls>".$img."</td>
                                                                        <td class=$cls align=center>".$arr[1]."<br><br>Фото: ".$arr[4]."</td>
                                                                        <td class=$cls><p><a title=\"Добавить фотографии в альбом\" href=\"mainiws.php?go=photoalbums&act=openAlbum".(($mode) ? '&mode=1' : '')."&sortBy=$sortBy&start=$start&id=".$arr[0]."\"><b>".$arr[2]."</b></a><br><br>".$arr[3]."<br><br><font size=1>";

                                        $hasht=explode("#",trim($arr[5],"#"));
                                        $cntHS=count($hasht)-1;
                                        for($iH=0; $iH<=$cntHS; $iH++){
                                          if(!$iH){
                                             $content.="<b>Теги:</b> <i>".$hasht[0];
                                          } else {
                                             $content.=", ".$hasht[$iH];
                                          }
                                        }

                                        $content.="</i></font></p></td><td class=$cls>";

                                                  if($mode){ $content.="[ <a href=\"#\" title=\"Вставить фотоальбом в страницу\" onclick=\"RetVar(".$arr[0]."); return false;\">Вставить &rarr;</a> ]<br><br>"; }

                                                             $content.="[<a href=\"#\" title=\"сменить раздел\" onclick=\"replaceOkAlbum('mainiws.php?go=photoalbums&act=replaceAlbumOk".(($mode) ? '&mode=1' : '')."&sortBy=$sortBy&start=$start&id=".$arr[0]."'); return false;\">сменить раздел</a>] 
                                                                        [<a href=\"#\" title=\"редактировать описание альбома\" onclick=\"editOkAlbum(".$sortBy.",".$start.",".$arr[0]."); return false;\">редактировать</a>]
                                                                        [<a href=\"#\" onclick=\"delOkAlbum('mainiws.php?go=photoalbums&act=delAlbumOk".(($mode) ? '&mode=1' : '')."&sortBy=$sortBy&start=$start&id=".$arr[0]."','".$arr[2]."'); return false;\"><font color=#ff0000>удалить</font></a>]
                                        </td></tr>";
                                }
                                unset($res,$ress);
                        } 
                        $content.="<tr><td></td><td colspan=4><br>".$prom."</td></tr>";
        
                } else {
                        $content.="<tr><td colspan=2></td><td colspan=3><br><br><h4>Извините, в этой рубрике нет альбомов!</h4></td></tr>";
                }

                $content.="<tr><td colspan=4></td><td align=right><a href=\"#\" onclick=\"document.location='mainiws.php?go=photoalbums&act=addAlbum".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : '')."'; return false;\">добавить альбом</a></td></tr>"
                ."</table></td></tr></table>";

                unset($sortBy,$start);

                return $content;
}

//--------------------------------------------------------------------------------------------------


function HashTags(){
global $mode;
   $content="<script><!--
   function delOkTag(urli){
            if(confirm('Вы действительно хотите удалить хэштэг?       ')){ document.location=urli; }
   }


    function addHashTag(){   
         var arr = null;
         arr = showModalDialog(\"fnciws/photoalbums/gdialog.php?evtype=addTag\", null, \"dialogWidth:410px; dialogHeight:20px; status:no;\");
         if (arr!= null)document.location='mainiws.php?go=photoalbums&act=addHashTagOk&nm='+arr[\"TagName\"];
    }
         //--></script>
   <table align=center width=70% border=0 cellpadding=2 cellspacing=1> <tr><td class=usr style='text-align:center;'>Управление хэштэгами</td></tr></table>";

   $content.="<table align=center width=70% border=0 cellpadding=2 cellspacing=1><tr><td><a href=\"#\" onclick=\"document.location='mainiws.php?go=photoalbums".(($mode) ? '&mode=1' : '')."'; return false;\">вернуться в Фотогалерею</a><td/><td style='text-align:right;'><a href='#' onclick=\"addHashTag(); return false;\">Добавить тэг</a></td></tr></table>";

   $result=mysql_query("SELECT id,name FROM iws_photos_hashtags ORDER BY name");  

   $content.="<table align=center width=70% border=0 cellpadding=2 cellspacing=1><tr><td class=usr>Название тэга</td><td class=usr>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
   if(mysql_numrows($result)>=1){
        while($arr=mysql_fetch_row($result)){
         if($cl=="menu1"){ $cl="menu"; } else { $cl="menu1"; }
         $content.="<tr class=$cl><td >".$arr[1]."</td><td ><a href=\"#\" onclick=\"delOkTag('mainiws.php?go=photoalbums&act=delhashtags&nam=".$arr[1]."');  return false;\">Удалить</a></td></tr>";
        }
   }
   $content.="</table>";
   $content.="<table align=center width=70% border=0 cellpadding=2 cellspacing=1><tr><td><a href=\"#\" onclick=\"document.location='mainiws.php?go=photoalbums".(($mode) ? '&mode=1' : '')."'; return false;\">вернуться в Фотогалерею</a><td/><td style='text-align:right;'><a href='#' onclick=\"addHashTag(); return false;\">Добавить тэг</a></td></tr></table>";
   return $content;
}





function addHashTagOk(){         
   mysql_query("INSERT INTO `iws_photos_hashtags` (name) VALUES('".$_GET["nm"]."') ");
   header("location: ?go=photoalbums&act=hashtags");
   return;
}

function DelHashTags(){         
   Global $nam;
   mysql_query("UPDATE `iws_photos_albums` SET  hashtags=IF(hashtags='#".$nam."#','',REPLACE( hashtags,'#".$nam."#','#')) WHERE  hashtags LIKE '%#".$nam."#%'");
   mysql_query("DELETE FROM iws_photos_hashtags WHERE name='".$nam."'");
   header("location: ?go=photoalbums&act=hashtags");
   return;
}

//function GetHashRangs(){
//   $t=mysql_result(mysql_query("SELECT MAX(counter) FROM `iws_photos_hashtags` "),0);
//   if($t>0){mysql_query("UPDATE `iws_photos_hashtags` SET rang=IF(ROUND(counter/".$t.",1)<0.1,0.1,ROUND(counter/".$t.",1))");}
//   header("location: ?go=photoalbums&act=hashtags");
//}

//---------------------------------------------------------------------------------------------------


//--------------------------------------------------------------------------------------------------

function department(){
   global $mode;
                $content.="
                <script><!--
                function delOkDep(urli,nmk,cntA)
                {
                  if(cntA){
                     alert(\"Невозможно удалить рубрику, пока в ней есть хотя бы один альбом. Прежде удалите все альбомы в рубрике, либо переместите их в другую.\");
                  } else {
                     if(confirm('Вы действительно хотите удалить раздел \"'+nmk+'\"?')) document.location=urli;
                  }
                }

                function addDep()
                {
                        var arr = null;
                        arr = showModalDialog(\"fnciws/photoalbums/gdialog.php?evtype=adddepartment\", null, \"dialogWidth:410px; dialogHeight:120px; status:no;\");
                        if (arr != null) document.location='mainiws.php?go=photoalbums&act=adddepartment".(($mode) ? '&mode=1' : '')."&nm='+arr[\"cname\"]+'&vw='+arr[\"activ\"];
                }

                function edtDep(nme,did,activ)
                {
                        var args = new Array();
                        var arr = null;
                        args[\"cname\"]=nme;
                        args[\"activ\"]=activ;
                        arr = showModalDialog(\"fnciws/photoalbums/gdialog.php?evtype=edtdepartment\", args, \"dialogWidth:410px; dialogHeight:120px; status:no;\");
                        if (arr != null) document.location='mainiws.php?go=photoalbums&act=edtdepartment".(($mode) ? '&mode=1' : '')."&id='+did+'&nm='+arr[\"cname\"]+'&vw='+arr[\"activ\"];
                }
                //--></script>
                <table align=center width=70% border=0 cellpadding=2 cellspacing=1>
                <table align=center width=70% border=0 cellpadding=2 cellspacing=1> <tr><td width=18></td><td><a href=\"#\" onclick=\"document.location='mainiws.php?go=photoalbums".(($mode) ? '&mode=1' : '')."'; return false;\">вернуться в Фотогалерею</a></td><td><a href=\"#\" onclick=\"addDep(); return false;\">Добавить рубрику</a></td></tr>
                <tr align=center><td></td><td class=usr>Рубрика</td><td class=usr></td></tr>\n";

                $res=mysql_query("SELECT A.id,A.name,(SELECT COUNT(B.id) FROM iws_photos_albums B WHERE B.cid=A.id),A.view FROM iws_photos_category A ORDER BY A.name");
                if(mysql_numrows($res)>=1){
                        $cl="menu1";
                        while($arr=mysql_fetch_row($res)){
                                if($cl=="menu1"){ $cl="menu"; } else { $cl="menu1"; }
                                $content.="<tr><td>".($arr[3]==1 ? "<img src='/isystem/fnciws/photoalbums/imgcropresize/eye.png' alt='Виден'>" : "<img src='/isystem/fnciws/photoalbums/imgcropresize/eye_c.png' alt='Не виден'>")."</td><td class=$cl><b>".$arr[1]."</b> &nbsp;&nbsp;(альбов: ".$arr[2].")&nbsp;&nbsp;</td><td class=$cl>"
                                        ."[<a href=\"#\" onclick=\"edtDep('".$arr[1]."',".$arr[0].",".$arr[3]."); return false;\">редактировать</a>] "
                                        ."[<a href=\"#\" onclick=\"delOkDep('mainiws.php?go=photoalbums&act=deldepartmentOk".(($mode) ? '&mode=1' : '')."&id=".$arr[0]."','".$arr[1]."',".$arr[2]."); return false;\"><font color=#ff0000>удалить</font></a>]</td></tr>\n";
                        }
                } else {
                        $content.="<tr><td colspan=3>Извините, в базе данных нет названий разделов!</td></tr>";
                } 
                $content.="<tr><td></td><td><a href=\"#\" onclick=\"document.location='mainiws.php?go=photoalbums".(($mode) ? '&mode=1' : '')."'; return false;\">вернуться в Фотогалерею</a></td><td><a href=\"#\" onclick=\"addDep(); return false;\">Добавить рубрику</a></td></tr>"
                ."</table>";
                return $content;
}

//------------------------------------------------------------------------------------------------------------------------------------


function catalogOk()
{
        global $act,$id,$sortBy,$mode,$start,$newCat,$nm,$vw,$fileName;
        
        switch($act){

        case "delPhotoOk":
                include('fnciws/photoalbums/faFunctions.php');
                delFile($fileName);
                mysql_query("DELETE FROM iws_photos_records WHERE id=".$_GET["idPhoto"]);

                header("location: ?go=photoalbums&act=openAlbum&id=".$id.(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : ''));
                return;
        break;

        case "editPhotoOk":
                if(!mysql_query("UPDATE iws_photos_records SET alt='".$_GET["alt"]."' WHERE id=".$_GET["idPhoto"])){
                        header("location: ?go=photoalbums&err=1&act=openAlbum&id=".$id.(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : ''));
                        return;
                } else {                
                        header("location: ?go=photoalbums&act=openAlbum&id=".$id.(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : ''));
                        return;
                }
        break;

        case "replaceAlbumOk":
                if(!mysql_query("UPDATE iws_photos_albums SET cid=$newCat WHERE id=$id")){
                        header("location: ?go=photoalbums&err=1".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : ''));
                        return;
                } else {                
                        header("location: ?go=photoalbums".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : ''));
                        return;
                }
        break;
        
        case "editAlbumOk":
                   $dt=substr($_POST[dt],0,10);
                  if(strlen($_POST[hashtags])>0){$HashTags="#".($_POST[hashtags])."#";}
                   $dt=explode(".",$dt);
                   $Ndescription=preg_replace("#(\r)\n#si", "<br>",addslashes($_POST[Ndescription]));
                  if(!mysql_query("UPDATE iws_photos_albums SET `data`=CONCAT('".$dt[2]."-".$dt[1]."-".$dt[0]." ', CURTIME()), `title`='".$_POST[Ntitle]."',`description`='".$Ndescription."',`hashtags`='".$HashTags."' WHERE id='".$id."'")){
                     header("location: ?go=photoalbums&err=4".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : ''));
                     return;
                  }else{        
                     header("location: ?go=photoalbums".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : ''));
                     return;
                  }
    break;
        

        case "delAlbumOk":
                $res=mysql_query("SELECT file FROM iws_photos_records WHERE aid=".$id);
                if(mysql_numrows($res)>=1){
                  include('fnciws/photoalbums/faFunctions.php');
                  while(List($img)=mysql_fetch_row($res)){ delFile($img); }
              
                  mysql_query("DELETE FROM iws_photos_records WHERE aid=$id");                  
                }
/*
            $HashTags=mysql_result(mysql_query("SELECT hashtags FROM `iws_photos_albums` WHERE id='".$id."'"),0);
            $sqll=mysql_query("SELECT id,name,counter FROM iws_photos_hashtags ORDER BY id");
                  if(mysql_numrows($sqll)>=1){
                     while($arr=mysql_fetch_row($sql)){
                        if( substr_count( $HashTags,"#".$arr[1]."#")>0 ){
                           $t=$arr[2]-1;
                           $sql1=mysql_query("UPDATE `iws_photos_hashtags` SET counter = '".$t."' WHERE id=".$arr[0]."");
                        }
                     }
                  }           
            
*/            
                mysql_query("DELETE FROM iws_photos_albums WHERE id=$id");

                header("location: ?go=photoalbums".(($sortBy) ? '&sortBy='.$sortBy : '&sortBy=0').(($start) ? '&start='.$start : '').(($mode) ? '&mode=1' : ''));
                return;
        break;


        case "deldepartmentOk":
                mysql_query("DELETE FROM iws_photos_category WHERE id=$id");

                header("location: ?go=photoalbums&act=department".(($mode) ? '&mode=1' : ''));
                return;
        break;

        case "adddepartment":
                $nm=trim($nm);
                if(empty($nm)) { 
                        header("location: ?go=photoalbums&act=department&err=2".(($mode) ? '&mode=1' : ''));
                        return;
                }
                $nm=addslashes($nm);
                if(!mysql_query("INSERT INTO iws_photos_category (name,view) VALUES ('$nm',$vw)")){
                        header("location: ?go=photoalbums&act=department&err=1".(($mode) ? '&mode=1' : ''));
                        return;
                } else {                
                        header("location: ?go=photoalbums&act=department".(($mode) ? '&mode=1' : ''));
                        return;
                }
        break;

        case "edtdepartment":
                $nm=trim($nm);
                if(empty($nm)) { 
                        header("location: ?go=photoalbums&act=department&err=2".(($mode) ? '&mode=1' : ''));
                        return;
                }
                $nm=addslashes($nm);
                if(!mysql_query("UPDATE iws_photos_category SET name='$nm', view=$vw WHERE id=$id")){
                        header("location: ?go=photoalbums&act=department&err=1".(($mode) ? '&mode=1' : ''));
                        return;
                } else {                
                        header("location: ?go=photoalbums&act=department".(($mode) ? '&mode=1' : ''));
                        return;
                }
        break;
}
}


?>