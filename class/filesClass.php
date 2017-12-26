<?php

Class FNFiles {

   var $retcon;
   
   var $usertrue;
   var $countinpage;
   var $space;
   var $extention;

   var $templateFile;
   var $templateAll;
   var $templateSearch;

   function retPreference()
   {
      list($this->usertrue, $this->countinpage, $this->space, $this->extention)=mysql_fetch_row(mysql_query("SELECT usertrue, IF(countinpage>=1,countinpage,15), space, extention FROM iws_arfiles_prefernce WHERE id=1"));
   }

   function retTemplates()
   {
      list($this->templateFile)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=14"));
      list($this->templateAll)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=13"));
   }

   function retTemplatesSearch()
   {
      list($this->templateFile)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=14"));
      list($this->templateSearch)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=9"));
   }

//-----------------------------------------------------------------------------------------------------------------------------
//функция поиска файлов

   function srcFile($wrd)
   {
      global $hostName;
      include('languages/lang_ru.php');

      $wrd=trim($wrd);
      if(isset($wrd) && strlen($wrd)>=3)
      {  
         $wrd = htmlspecialchars(substr($wrd,0,120));
         $this->templateSearch=stripslashes($this->templateSearch);

         $this->sql = "SELECT A.pse, A.name, A.authors, A.description, A.file, (SELECT B.name FROM iws_arfiles_department B WHERE B.id=A.department)
                      FROM iws_arfiles_records A WHERE (A.name LIKE '%".$wrd."%') OR (A.authors LIKE '%".$wrd."%') OR (A.description LIKE '%".$wrd."%') ORDER BY A.data DESC";

         $this->resultSearch = mysql_query($this->sql);

         if(mysql_numrows($this->resultSearch)>=1){
            include('filesArFunctions.php');
            $this->retTemplatesSearch();

            $this->retcon.="<DIV>".$lang['filesSearchMsg1']." ".mysql_numrows($this->resultSearch)." ".$lang['filesSearchMsg2']."</DIV>";
            $i=1;
            setlocale(LC_ALL,"ru_RU");
            if(ereg("\[\/:searchtext\]",$this->templateSearch) && (ereg("\[\/:searchtopath\]",$this->templateSearch) || ereg("\[\/:searchurl\]",$this->templateSearch))){
                  while($this->arr=mysql_fetch_row($this->resultSearch))
               {
                  $this->arr[1]=preg_replace("|(".preg_quote($wrd).")|i","<b>\\1</b>",$this->arr[1]);
                  $this->arr[2]=preg_replace("|(".preg_quote($wrd).")|i","<b>\\1</b>",$this->arr[2]);
                  $this->arr[3]=preg_replace("|(".preg_quote($wrd).")|i","<b>\\1</b>",$this->arr[3]);

                  $this->retcon.="<DIV>".str_replace("[/:searchcounter]",($i++),
                                 str_replace("[/:searchtopath]",$this->replaceTemplate($this->arr[0], stripslashes($this->arr[1]), stripslashes($this->arr[2]), stripslashes($this->arr[3]), $this->arr[5], display_size($this->arr[4]), substr($this->arr[4],-(strpos(strrev($this->arr[4]),"."))), $hostName, $lang['fileslink']),
                                 str_replace("[/:searchtext]","",
                                 str_replace("[/:searchurl]",$hostName."/index.php?go=GetFile&uid=".$this->arr[0],$this->templateSearch))))."</DIV>";

               }
            } else {
               while($this->arr=mysql_fetch_row($this->resultSearch))
               {
                  $this->arr[1]=preg_replace("|(".preg_quote($wrd).")|i","<b>\\1</b>",$this->arr[1]);
                  $this->arr[2]=preg_replace("|(".preg_quote($wrd).")|i","<b>\\1</b>",$this->arr[2]);
                  $this->arr[3]=preg_replace("|(".preg_quote($wrd).")|i","<b>\\1</b>",$this->arr[3]);

                  $this->retcon="<DIV><table width=80%><tr>
                                 <td width=10 rowspan=2 valign=top>".($i++).".</td><td width=3 rowspan=2 bgcolor=#A8A8A8></td>
                                 <td>".$this->replaceTemplate($this->arr[0], stripslashes($this->arr[1]), stripslashes($this->arr[2]), stripslashes($this->arr[3]), $this->arr[5], display_size($this->arr[4]), substr($this->arr[4],-(strpos(strrev($this->arr[4]),"."))), $hostName, $lang['fileslink'])."</td></tr>
                                 <tr><td class=path>".$hostName."/index.php?go=GetFile&uid=".$this->arr[0]."</td></tr>
                                 </table></DIV>";
               }
            }

         } else {

            $this->retcon.="<DIV>".$lang['filesSearchNot']."</DIV>";
         }
         mysql_free_result($this->resultSearch);

      } else {

         $this->retcon.="<DIV>".$lang['filesSearchNT']."</DIV>";
      }
   
      $wrd=trim($wrd);

      $this->retcon = "<DIV class=searchFiles><DIV>".$this->FormSearch($hostName,$lang['filesSearchButton'])."</DIV><DIV>".$lang['filesTitleResult']."</DIV>".$this->retcon."</DIV>";
      return true;
   }

//конец функции поиска

//-----------------------------------------------------------------------------------------------------------------------------


   function addNewFile($Category,$FileName,$FileContent,$FileAuthor,$orderBy)
   {
      global $hostName;

      $this->retPreference();
      if($this->usertrue)
      {
         $FileName=trim($FileName); $FileAuthor=trim($FileAuthor); $FileContent=trim($FileContent);

         if(empty($FileName) || empty($FileContent) || empty($FileAuthor))
         {
//          header("location: ?go=filesarchive&err=2".(($orderBy) ? '&orderBy='.$orderBy : '')); return;
            header("location: ".$hostName."/files".(($orderBy) ? '/category'.$orderBy : '')."/err2"); return;
         }

         $FileName = substr($FileName,0,200); $FileAuthor = substr($FileAuthor,0,200); $FileContent = substr($FileContent,0,700);

         include('filesArFunctions.php');
         $this->retFileName=copy_file_toserver($this->space, $this->extention);
         if(!$this->retFileName)
         {
//          header("location: ?go=filesarchive&err=3".(($orderBy) ? '&orderBy='.$orderBy : '')); return;
            header("location: ".$hostName."/files".(($orderBy) ? '/category'.$orderBy : '')."/err3"); return;
         }

         $FileName=addslashes($FileName); $FileContent=addslashes($FileContent); $FileAuthor=addslashes($FileAuthor);

         $this->sql = "INSERT INTO iws_arfiles_records (department,pse,authors,name,description,file,data) VALUES ($Category,'".unicumId()."','$FileAuthor','$FileName','$FileContent','".$this->retFileName."',NOW())";

         if(!mysql_query($this->sql)){
            delFile($this->retFileName);
//          header("location: ?go=filesarchive&err=1".(($orderBy) ? '&orderBy='.$orderBy : ''));
            header("location: ".$hostName."/files".(($orderBy) ? '/category'.$orderBy : '')."/err1"); return;
            return;
         } else {
//          header("location: ?go=filesarchive".(($orderBy) ? '&orderBy='.$orderBy : ''));
            header("location: ".$hostName."/files".(($orderBy) ? '/category'.$orderBy : ''));
            return;
         }

      } else {
         header("location: ".$hostName."/files".(($orderBy) ? '/category'.$orderBy : ''));
         return;
      }
   }


//-----------------------------------------------------------------------------------------------------------------------------
   
   function replaceContent($start,$orderBy,$err=0)
   {
      global $QUERY_STRING,$hostName;

      $orderBy = substr($orderBy,0,3);
      $start = substr($start,0,10);

      if(!$start) $start=1;

      include('languages/lang_ru.php');
      include('filesArFunctions.php');
      $this->retPreference();
      $this->retTemplates();
      $this->prom=numlink($start,ereg_replace("&start=".$start,"",$QUERY_STRING),"iws_arfiles_records",$orderBy,$this->countinpage,$lang,$hostName);

      if(ereg("/:filesAll",$this->templateAll) && ereg("/:filesList",$this->templateAll)){
         $this->templateAll=stripslashes($this->templateAll);

         $this->retcon=str_replace("[/:filesAll]",$this->AllFiles($hostName, $lang['fileslink'], $start, $orderBy),$this->templateAll);
         if($this->prom){ $this->retcon=str_replace("[/:filesList]",$this->prom,$this->retcon); } else { $this->retcon=str_replace("[/:filesList]","",$this->retcon); }
         if(ereg("/:filesSearch",$this->templateAll)) $this->retcon=str_replace("[/:filesSearch]",$this->FormSearch($hostName,$lang['filesSearchButton']),$this->retcon);
         if(ereg("/:filesCategory",$this->templateAll)) $this->retcon=str_replace("[/:filesCategory]",$this->FormCategory($hostName,$lang['filesAllCategory'],$orderBy),$this->retcon);
         if(ereg("/:filesCurCategory",$this->templateAll)) $this->retcon=str_replace("[/:filesCurCategory]",$this->NameCategory($orderBy),$this->retcon);
         if(ereg("/:filesAdd",$this->templateAll))
         {
            if($this->usertrue)
            {
               $this->retcon=str_replace("[/:filesAdd]",$this->FormAdd($hostName,$lang,$orderBy,$err),$this->retcon);
            } else {
               $this->retcon=str_replace("[/:filesAdd]","",$this->retcon);
            }
         }

      } else {

         $this->retcon="<P>".$this->FormSearch($hostName,$lang['filesSearchButton'])."</P>";
         $this->retcon.="<P>".$this->FormCategory($hostName,$lang['filesAllCategory'],$orderBy)."</P>";
         $this->retcon.="<P>".$this->AllFiles($hostName, $lang['fileslink'], $start, $orderBy)."</P>";
         $this->retcon.="<P>".$this->prom."</P>";
         if($this->usertrue) $this->retcon.="<HR><P>".$this->FormAdd($hostName,$lang,$orderBy,$err)."</P>";

      }
      return true;
   }

//------------------------------------------------------------------------------------------------------------

   function AllFiles($hostN, $TextLink, $start, $orderBy)
   {
      
      $this->result=mysql_query("SELECT A.pse, A.name, A.authors, A.description, A.file, (SELECT B.name FROM iws_arfiles_department B WHERE B.id=A.department) 
                                 FROM iws_arfiles_records A ".(($orderBy && is_numeric($orderBy)) ? 'WHERE A.department='.$orderBy : '')." ORDER BY A.data DESC LIMIT ".($start-1).",".$this->countinpage);

      if(mysql_numrows($this->result)>=1){
         $this->lstFiles="<DIV class=FilesInPage>\n";
         if(ereg("/:filesName",$this->templateFile)){
            $this->templateFile=stripslashes($this->templateFile);
            while($this->arr=mysql_fetch_row($this->result)){
               $this->lstFiles.="<DIV>".$this->replaceTemplate($this->arr[0], stripslashes($this->arr[1]), stripslashes($this->arr[2]), stripslashes($this->arr[3]), $this->arr[5], display_size($this->arr[4]), substr($this->arr[4],-(strpos(strrev($this->arr[4]),"."))), $hostN, $TextLink)."</DIV>\n\n";
            }
         } else {
            while($this->arr=mysql_fetch_row($this->result)){
               $this->lstFiles.="<DIV><b>".stripslashes($this->arr[2])."</b><br><a href=\"$hostN/index.php?go=GetFile&uid=".$this->arr[0]."\">".stripslashes($this->arr[1])."</a></DIV>";
            }
         }
         $this->lstFiles.="</DIV>";
         unset($this->arr,$this->result);

         return $this->lstFiles;
      }
   }

   function replaceTemplate($Uid, $FileName, $FileAuthors='', $FileContent='', $FileFrom='', $FileSpace='', $FileExt='', $hostN, $TextLink)
   {
      $this->PreFiles=str_replace("[/:filesName]","<a title=\"$TextLink\" href=\"$hostN/index.php?go=GetFile&uid=$Uid\">$FileName</a>",$this->templateFile);
      if(ereg("/:filesAuthors",$this->templateFile)) $this->PreFiles=str_replace("[/:filesAuthors]",$FileAuthors,$this->PreFiles);
      if(ereg("/:filesShortContent",$this->templateFile)) $this->PreFiles=str_replace("[/:filesShortContent]",$FileContent,$this->PreFiles);
      if(ereg("/:filesFromList",$this->templateFile)) $this->PreFiles=str_replace("[/:filesFromList]",$FileFrom,$this->PreFiles);
      if(ereg("/:filesSpace",$this->templateFile)) $this->PreFiles=str_replace("[/:filesSpace]",$FileSpace,$this->PreFiles);
      if(ereg("/:filesExtention",$this->templateFile)) $this->PreFiles=str_replace("[/:filesExtention]",$FileExt,$this->PreFiles);
      if(ereg("/:filesLink",$this->templateFile)) $this->PreFiles=str_replace("[/:filesLink]","<a href=\"$hostN/index.php?go=GetFile&uid=$Uid\">$TextLink</a>",$this->PreFiles);

      return $this->PreFiles;
   }

//------------------------------------------------------------------------------------------------------------
//Формы

   function FormAdd($hostN,$lang,$orderBy,$err)
   {
      $this->retAdd="\n<DIV class=FormAddFiles>
            <script><!--
               function tosubmit(){
                  if(formS.FileName.value && formS.FileContent.value && formS.FileAuthor.value && formS.FileUp.value){
                     formS.submit();
                  } else {
                     alert (\"В добавлении отказано! Не введена вся информация.                    \");
                  }
               }
            //--></script>";
      if($err)
      {
         switch($err){
            case 1:
               $this->retAdd.="<DIV class=err>".$lang['filesError1']."</DIV>";
            break;
            case 2:
               $this->retAdd.="<DIV class=err>".$lang['filesError2']."</DIV>";
            break;
            case 3:
               $this->retAdd.="<DIV class=err>".$lang['filesError3']."</DIV>";
            break;
         }
      }

/*
      $this->retAdd.="
            <form action=\"$hostN/index.php\" name=formS method=\"post\" enctype=\"multipart/form-data\">
            <input type=hidden name=go value=filesarchive>
            <input type=hidden name=act value=addFileOk>
            ".(($orderBy) ? '<input type=hidden name=orderBy value='.$orderBy.'>' : '')."
*/
      $this->retAdd.="
            <form action=\"$hostN/files/".(($orderBy) ? 'category'.$orderBy.'/' : '')."addFile\" name=formS method=\"post\" enctype=\"multipart/form-data\">
            ".$lang['filesRulesAdd']."<br><br>
            <DIV>".$lang['filesCategory']."<br><select name=Category>
               <option value=0>".$lang['filesNonCategory']."</option>\n";

      $this->resDep=mysql_query("SELECT id,name FROM iws_arfiles_department ORDER BY name");
      if(mysql_numrows($this->resDep)>=1){
         while($this->arr=mysql_fetch_row($this->resDep)){
            if($orderBy==$this->arr[0]){
               $this->retAdd.="<option value='".$this->arr[0]."' selected>".$this->arr[1]."</option>\n";
            } else {
               $this->retAdd.="<option value='".$this->arr[0]."'>".$this->arr[1]."</option>\n";
            }
         }
         unset($this->arr,$this->resDep);
      }

      $this->retAdd.="</select></DIV>
            <DIV>".$lang['filesName']."<br><input type=text name=FileName></DIV>
            <DIV>".$lang['filesAuthor']."<br><input type=text name=FileAuthor></DIV>
            <DIV>".$lang['filesContent']."<br><textarea name=FileContent></textarea></DIV>
            <DIV>".$lang['filesAddfile']."<br><input type=file name=FileUp size=50>";
            if($this->space || $this->extention)
            {
               $this->retAdd.="<span><br>".$lang['filesRestrict'];
               $this->retAdd.=($this->space ? '<br>'.$lang['filesRestrictSpace'].' '.$this->space.' Мб' : '');
               $this->retAdd.=($this->extention ? '<br>'.$lang['filesRestrictExt'].' '.str_replace(","," ",trim($this->extention)) : '');
               $this->retAdd.="</span>";
            }
      $this->retAdd.="</DIV>
            <DIV><br><input class=AddFileBut type=image src=\"$hostN/design/addFile.gif\" title=\"".$lang['filesAddbutton']."\" alt=\"".$lang['filesAddbutton']."\" onclick=\"tosubmit(); return false;\"></DIV>
            </form>
            </DIV>";

      return $this->retAdd;

   }


   function FormSearch($hostN,$TextButton,$wrdRet='')
   {
      return "<form name=frmsearch class=search method=get action=\"$hostN/searchFiles/\">
               <div style=\"vertical-align: middle;\"><input class=searchFile name=words maxlength=120 value='$wrdRet'><input type=image class=search_image title=\"$TextButton\" alt=\"$TextButton\" align=top src=\"$hostN/design/searchFile.gif\"></div>
               </form>";
/*
      return "<form name=frmsearch method=get action=\"$hostN/index.php\">
               <input type=hidden name=go value=filesarchive>
               <input type=hidden name=act value=search>
               <nobr><input class=searchFile name=words maxlength=200>
               <input type=image class=search_image title=\"$TextButton\" alt=\"$TextButton\" align=absMiddle src=\"$hostN/design/searchFile.gif\"></nobr>
               </form>";   
*/
   }


   function FormCategory($hostN,$TextNonCategory,$orderBy)
   {

//    $this->retCat="<select class=selectFile name=orderBy onChange=\"document.location='$hostN/index.php?go=filesarchive&orderBy='+this.value; return false;\">
      $this->retCat="<select class=selectFile name=orderBy onChange=\"if(this.value>0){ document.location='$hostN/files/category'+this.value; } else { document.location='$hostN/files'; }\">
                     <option value=0>$TextNonCategory</option>\n";

      $this->resDep=mysql_query("SELECT id,name FROM iws_arfiles_department ORDER BY name");
      if(mysql_numrows($this->resDep)>=1){
         while($this->arr=mysql_fetch_row($this->resDep)){
            if($orderBy==$this->arr[0]){
               $this->retCat.="<option value=".$this->arr[0]." selected>".$this->arr[1]."</option>\n";
            } else {
               $this->retCat.="<option value=".$this->arr[0].">".$this->arr[1]."</option>\n";
            }
         }
         unset($this->arr,$this->resDep);
      }
      $this->retCat.="</select>\n";
      return $this->retCat;

   }



   function NameCategory($orderBy)
   {
      $this->retCat="";
      $this->resDep=mysql_query("SELECT name FROM iws_arfiles_department WHERE id=".$orderBy);
      if(mysql_numrows($this->resDep)>=1){
         list($this->Cname)=mysql_fetch_row($this->resDep);
         $this->retCat=$this->Cname;
         unset($this->resDep);
      }
      return $this->retCat;

   }

//-----------------------------------------------------------------------------------------------------------------

}
?>
