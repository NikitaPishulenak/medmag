<?php

Class FNFiles {

   var $retcon;
   
   var $usertrue;
   var $countinpage;
   var $space;
   var $extention;

   var $templateFile;
   var $templateAll;
   var $templateAllRubric;
   var $templateSearch;

   function retPreference()
   {
      list($this->usertrue, $this->countinpage, $this->space, $this->extention)=mysql_fetch_row(mysql_query("SELECT usertrue, IF(countinpage>=1,countinpage,15), space, extention FROM iws_arfiles_A_prefernce WHERE id=1"));
   }

   function retTemplates()
   {
      list($this->templateFile)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=14"));
      list($this->templateAllRubric)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=13"));
      list($this->templateAll)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=15"));
      list($this->templateText)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=16"));
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

         $this->sql = "SELECT A.pse, A.name, A.authors, A.description, A.file, (SELECT B.name FROM iws_arfiles_A_department B WHERE B.id=A.department)
                      FROM iws_arfiles_A_records A WHERE (A.name LIKE '%".$wrd."%') OR (A.authors LIKE '%".$wrd."%') OR (A.description LIKE '%".$wrd."%') ORDER BY A.data DESC";

         $this->resultSearch = mysql_query($this->sql);

         if(mysql_numrows($this->resultSearch)>=1){
            include('A_filesArFunctions.php');
            $this->retTemplatesSearch();

            $this->retcon.="<DIV>".$lang['A_filesSearchMsg1']." ".mysql_numrows($this->resultSearch)." ".$lang['A_filesSearchMsg2']."</DIV>";
            $i=1;
            setlocale(LC_ALL,"ru_RU");
            if(ereg("\[\/:searchtext\]",$this->templateSearch) && (ereg("\[\/:searchtopath\]",$this->templateSearch) || ereg("\[\/:searchurl\]",$this->templateSearch))){
                  while($this->arr=mysql_fetch_row($this->resultSearch))
               {
                  $this->arr[1]=preg_replace("|(".preg_quote($wrd).")|i","<b>\\1</b>",$this->arr[1]);
                  $this->arr[2]=preg_replace("|(".preg_quote($wrd).")|i","<b>\\1</b>",$this->arr[2]);
                  $this->arr[3]=preg_replace("|(".preg_quote($wrd).")|i","<b>\\1</b>",$this->arr[3]);

                  $this->retcon.="<DIV>".str_replace("[/:searchcounter]",($i++),
                                 str_replace("[/:searchtopath]",$this->replaceTemplate($this->arr[0], stripslashes($this->arr[1]), stripslashes($this->arr[2]), stripslashes($this->arr[3]), $this->arr[5], display_size($this->arr[4]), substr($this->arr[4],-(strpos(strrev($this->arr[4]),"."))), $hostName, $lang['A_fileslink']),
                                 str_replace("[/:searchtext]","",
                                 str_replace("[/:searchurl]",$hostName."/index.php?go=GetFile_A&uid=".$this->arr[0],$this->templateSearch))))."</DIV>";

               }
            } else {
               while($this->arr=mysql_fetch_row($this->resultSearch))
               {
                  $this->arr[1]=preg_replace("|(".preg_quote($wrd).")|i","<b>\\1</b>",$this->arr[1]);
                  $this->arr[2]=preg_replace("|(".preg_quote($wrd).")|i","<b>\\1</b>",$this->arr[2]);
                  $this->arr[3]=preg_replace("|(".preg_quote($wrd).")|i","<b>\\1</b>",$this->arr[3]);

                  $this->retcon="<DIV><table width=80%><tr>
                                 <td width=10 rowspan=2 valign=top>".($i++).".</td><td width=3 rowspan=2 bgcolor=#A8A8A8></td>
                                 <td>".$this->replaceTemplate($this->arr[0], stripslashes($this->arr[1]), stripslashes($this->arr[2]), stripslashes($this->arr[3]), $this->arr[5], display_size($this->arr[4]), substr($this->arr[4],-(strpos(strrev($this->arr[4]),"."))), $hostName, $lang['A_fileslink'])."</td></tr>
                                 <tr><td class=path>".$hostName."/index.php?go=GetFile_A&uid=".$this->arr[0]."</td></tr>
                                 </table></DIV>";
               }
            }

         } else {

            $this->retcon.="<DIV>".$lang['A_filesSearchNot']."</DIV>";
         }
         mysql_free_result($this->resultSearch);

      } else {

         $this->retcon.="<DIV>".$lang['A_filesSearchNT']."</DIV>";
      }
   
      $wrd=trim($wrd);

      $this->retcon = "<DIV class=searchFiles><DIV>".$this->FormSearch($hostName,$lang['A_filesSearchButton'])."</DIV><DIV>".$lang['A_filesTitleResult']."</DIV>".$this->retcon."</DIV>";
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
            header("location: ".$hostName."/medicaljournal".(($orderBy) ? '/category'.$orderBy : '')."/err2"); return;
         }

         $FileName = substr($FileName,0,200); $FileAuthor = substr($FileAuthor,0,200); $FileContent = substr($FileContent,0,700);

         include('A_filesArFunctions.php');
         $this->retFileName=copy_file_toserver($this->space, $this->extention);
         if(!$this->retFileName)
         {
            header("location: ".$hostName."/medicaljournal".(($orderBy) ? '/category'.$orderBy : '')."/err3"); return;
         }

         $FileName=addslashes($FileName); $FileContent=addslashes($FileContent); $FileAuthor=addslashes($FileAuthor);

         $this->sql = "INSERT INTO iws_arfiles_A_records (department,pse,authors,name,description,file,data) VALUES ($Category,'".unicumId()."','$FileAuthor','$FileName','$FileContent','".$this->retFileName."',NOW())";

         if(!mysql_query($this->sql)){
            delFile($this->retFileName);
            header("location: ".$hostName."/medicaljournal".(($orderBy) ? '/category'.$orderBy : '')."/err1"); return;
            return;
         } else {
            header("location: ".$hostName."/medicaljournal".(($orderBy) ? '/category'.$orderBy : ''));
            return;
         }

      } else {
         header("location: ".$hostName."/medicaljournal".(($orderBy) ? '/category'.$orderBy : ''));
         return;
      }
   }



//-----------------------------------------------------------------------------------------------------------------------------

   function replaceContentId($orderBy, $id)
   {
      global $hostName;
      $orderBy = substr($orderBy,0,3);

      $this->result=mysql_query("SELECT A.name, A.authors, A.description, A.pse, A.dep, A.keyw, A.liter, A.authors_e, A.name_e, A.description_e, A.keyw_e, A.file, (SELECT B.name FROM iws_arfiles_A_department B WHERE B.id=A.department), (SELECT C.name FROM iws_arfiles_A_rubric C WHERE C.id=A.rubric), A.rubric FROM iws_arfiles_A_records A WHERE A.department=".$orderBy." AND id=".$id);
      if(mysql_numrows($this->result)===1){      
         $this->retTemplates();
         $this->templateText=stripslashes($this->templateText);
         if(ereg("/:filesName",$this->templateFile)){
            $this->arr=mysql_fetch_row($this->result);

            $this->retcon=str_replace("[/:filesName]","<a href=\"$hostName/index.php?go=GetFile_A&orderBy=".$orderBy."&uid=".$this->arr[3]."\">".stripslashes($this->arr[0])."</a>",$this->templateText);

            if(ereg("/:filesFromList",$this->templateText)) $this->retcon=str_replace("[/:filesFromList]","<DIV><a href=\"".$hostName."/index.php?go=filesarchive_A&orderBy=".$orderBy."\">".stripslashes($this->arr[12])."</a></DIV> <DIV><a href=\"".$hostName."/index.php?go=filesarchive_A&Rubric=".$this->arr[14]."\">".stripslashes($this->arr[13])."</a></DIV>",$this->retcon);
            if(ereg("/:filesAuthors",$this->templateText)) $this->retcon=str_replace("[/:filesAuthors]",stripslashes($this->arr[1]),$this->retcon);
            if(ereg("/:filesDep",$this->templateText)) $this->retcon=str_replace("[/:filesDep]",stripslashes($this->arr[4]),$this->retcon);
            if(ereg("/:filesShortContent",$this->templateText)) $this->retcon=str_replace("[/:filesShortContent]",stripslashes($this->arr[2]),$this->retcon);
            if(ereg("/:filesKey",$this->templateText)) $this->retcon=str_replace("[/:filesKey]",stripslashes($this->arr[5]),$this->retcon);
            if(ereg("/:filesNameENG",$this->templateText)) $this->retcon=str_replace("[/:filesNameENG]",stripslashes($this->arr[8]),$this->retcon);
            if(ereg("/:filesAuthorsENG",$this->templateText)) $this->retcon=str_replace("[/:filesAuthorsENG]",stripslashes($this->arr[7]),$this->retcon);
            if(ereg("/:filesShortContentENG",$this->templateText)) $this->retcon=str_replace("[/:filesShortContentENG]",stripslashes($this->arr[9]),$this->retcon);
            if(ereg("/:filesKeyENG",$this->templateText)) $this->retcon=str_replace("[/:filesKeyENG]",stripslashes($this->arr[10]),$this->retcon);
            if(ereg("/:filesLiter",$this->templateText)) $this->retcon=str_replace("[/:filesLiter]",stripslashes($this->arr[6]),$this->retcon);
            if(ereg("/:filesLink",$this->templateText)){
               include('languages/lang_ru.php');
               $this->retcon=str_replace("[/:filesLink]","<a href=\"$hostName/index.php?go=GetFile_A&orderBy=".$orderBy."&uid=".$this->arr[3]."\">".$lang['A_fileslink']."</a>",$this->retcon);
            }
            if(ereg("/:filesExtention",$this->templateText)) $this->retcon=str_replace("[/:filesExtention]",substr($this->arr[11],-(strpos(strrev($this->arr[11]),"."))),$this->retcon);
            if(ereg("/:filesSpace",$this->templateText)){
               include('A_filesArFunctions.php');
               $this->retcon=str_replace("[/:filesSpace]",display_size($this->arr[11]),$this->retcon);
            }
         }
         return true;
      } else {
         return false;
      }
   }


//-----------------------------------------------------------------------------------------------------------------------------
   
   function replaceContentRubric($start,$orderBy,$err=0)
   {
      global $QUERY_STRING,$hostName;

      $orderBy = substr($orderBy,0,3);
      $start = substr($start,0,10);

      if(!$start) $start=1;

      include('languages/lang_ru.php');
      include('A_filesArFunctions.php');
      $this->retPreference();
      $this->retTemplates();
      $this->prom=numlink($start,ereg_replace("&start=".$start,"",$QUERY_STRING),"iws_arfiles_A_records",$orderBy,$this->countinpage,$lang,$hostName);

      if(ereg("/:filesAll",$this->templateAllRubric) && ereg("/:filesList",$this->templateAllRubric)){
         $this->templateAllRubric=stripslashes($this->templateAllRubric);

         $this->retcon=str_replace("[/:filesAll]",$this->AllFilesRubric($hostName, $start, $orderBy),$this->templateAllRubric);
         if($this->prom){ $this->retcon=str_replace("[/:filesList]",$this->prom,$this->retcon); } else { $this->retcon=str_replace("[/:filesList]","",$this->retcon); }
         if(ereg("/:filesSearch",$this->templateAllRubric)) $this->retcon=str_replace("[/:filesSearch]",$this->FormSearch($hostName,$lang['A_filesSearchButton']),$this->retcon);
         if(ereg("/:filesCategory",$this->templateAllRubric)) $this->retcon=str_replace("[/:filesCategory]",$this->FormCategory($hostName,$lang['A_filesAllCategory'],$orderBy),$this->retcon);
         if(ereg("/:filesCurCategory",$this->templateAllRubric)) $this->retcon=str_replace("[/:filesCurCategory]",$this->NameCategoryRubric($orderBy),$this->retcon);
         if(ereg("/:filesAdd",$this->templateAllRubric))
         {
            if($this->usertrue)
            {
               $this->retcon=str_replace("[/:filesAdd]",$this->FormAdd($hostName,$lang,$orderBy,$err),$this->retcon);
            } else {
               $this->retcon=str_replace("[/:filesAdd]","",$this->retcon);
            }
         }

      } else {

         $this->retcon="<P>".$this->FormSearch($hostName,$lang['A_filesSearchButton'])."</P>";
         $this->retcon.="<P>".$this->FormCategory($hostName,$lang['A_filesAllCategory'],$orderBy)."</P>";
         $this->retcon.="<P>".$this->AllFiles($hostName, $lang['A_fileslink'], $start, $orderBy)."</P>";
         $this->retcon.="<P>".$this->prom."</P>";
         if($this->usertrue) $this->retcon.="<HR><P>".$this->FormAdd($hostName,$lang,$orderBy,$err)."</P>";

      }
      return true;
   }


//------------------------------------------------------------------------------------------------------------

   function AllFilesRubric($hostN, $start, $orderBy)
   {
      $this->result=mysql_query("SELECT A.id, A.name, A.authors, A.description, A.file, A.department, (SELECT B.name FROM iws_arfiles_A_department B WHERE B.id=A.department) FROM iws_arfiles_A_records A WHERE A.rubric=".$orderBy." ORDER BY A.data DESC LIMIT ".($start-1).",".$this->countinpage);

      if(mysql_numrows($this->result)>=1){
         $this->lstFiles="<DIV class=FilesInPage>\n";
         if(ereg("/:filesName",$this->templateFile)){
            $this->templateFile=stripslashes($this->templateFile);
            while($this->arr=mysql_fetch_row($this->result)){
               $this->lstFiles.="<DIV class=FileRow>".$this->replaceTemplate($this->arr[0], stripslashes($this->arr[1]), stripslashes($this->arr[2]), stripslashes($this->arr[3]), "<a href=\"".$hostN."/index.php?go=filesarchive_A&orderBy=".$this->arr[5]."\">".stripslashes($this->arr[6])."</a>", display_size($this->arr[4]), substr($this->arr[4],-(strpos(strrev($this->arr[4]),"."))), $hostN, $this->arr[5])."</DIV>\n\n";
            }
         } else {
            while($this->arr=mysql_fetch_row($this->result)){
               $this->lstFiles.="<DIV class=FileRow><b>".stripslashes($this->arr[2])."</b><br><a href=\"$hostN/index.php?go=filesarchive_A&orderBy=".$this->arr[5]."&id=".$this->arr[0]."\">".stripslashes($this->arr[1])."</a></DIV>";
            }
         }
         $this->lstFiles.="</DIV>";
         unset($this->arr,$this->result);

         return $this->lstFiles;
      }
   }


//-----------------------------------------------------------------------------------------------------------------------------
   
   function replaceContent($orderBy, $err=0)
   {
      global $hostName;

      $orderBy = substr($orderBy,0,3);

      include('languages/lang_ru.php');
      $this->retTemplates();
      include('A_filesArFunctions.php');

      if(ereg("/:filesAll",$this->templateAll)){
         $this->templateAll=stripslashes($this->templateAll);
         $this->templateFile=stripslashes($this->templateFile);

         $this->retcon=str_replace("[/:filesAll]",$this->AllFiles($hostName, $orderBy),$this->templateAll);
         $this->retcon=str_replace("[/:filesList]","",$this->retcon);
         if(ereg("/:filesSearch",$this->templateAll)) $this->retcon=str_replace("[/:filesSearch]",$this->FormSearch($hostName,$lang['A_filesSearchButton']),$this->retcon);
         if(ereg("/:filesCategory",$this->templateAll)) $this->retcon=str_replace("[/:filesCategory]",$this->FormCategory($hostName,$lang['A_filesAllCategory'],$orderBy),$this->retcon);
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

         $this->retcon="<P>".$this->FormSearch($hostName,$lang['A_filesSearchButton'])."</P>";
         $this->retcon.="<P>".$this->FormCategory($hostName,$lang['A_filesAllCategory'],$orderBy)."</P>";
         $this->retcon.="<P>".$this->AllFiles($hostName, $orderBy)."</P>";
         if($this->usertrue) $this->retcon.="<HR><P>".$this->FormAdd($hostName,$lang,$orderBy,$err)."</P>";

      }
      return true;
   }

//------------------------------------------------------------------------------------------------------------

   function AllFiles($hostN, $orderBy)
   {

      if(ereg("/:filesName",$this->templateFile)){

                  $this->result=mysql_query("SELECT id, name, authors, description, file FROM iws_arfiles_A_records WHERE department=".$orderBy." AND rubric=0 ORDER BY data DESC");

                  if(mysql_numrows($this->result)>=1){
                     $this->lstFiles.="<DIV class=FilesInPage>\n\n";
                        while($this->arr=mysql_fetch_row($this->result)){
                            $this->lstFiles.="<DIV class=FileRow>".$this->replaceTemplate($this->arr[0], stripslashes($this->arr[1]), stripslashes($this->arr[2]), stripslashes($this->arr[3]), "", display_size($this->arr[4]), substr($this->arr[4],-(strpos(strrev($this->arr[4]),"."))), $hostN, $orderBy)."</DIV>\n\n";
                        }
                     $this->lstFiles.="</DIV>\n\n";
                     unset($this->arr,$this->result);
                  }
            


         $this->resultR=mysql_query("SELECT id,name FROM iws_arfiles_A_rubric ORDER BY name");

         if(mysql_numrows($this->resultR)>=1){
               while($this->arrR=mysql_fetch_row($this->resultR)){

                  $this->result=mysql_query("SELECT id, name, authors, description, file FROM iws_arfiles_A_records WHERE department=".$orderBy." AND rubric=".$this->arrR[0]." ORDER BY data DESC");

                  if(mysql_numrows($this->result)>=1){
                     $this->lstFiles.="<DIV class=FilesInPage>\n<DIV class=RubricName><a href=\"".$hostN."/index.php?go=filesarchive_A&Rubric=".$this->arrR[0]."\">".stripslashes($this->arrR[1])."</a></DIV>\n\n";
                        while($this->arr=mysql_fetch_row($this->result)){
                            $this->lstFiles.="<DIV class=FileRow>".$this->replaceTemplate($this->arr[0], stripslashes($this->arr[1]), stripslashes($this->arr[2]), stripslashes($this->arr[3]), "", display_size($this->arr[4]), substr($this->arr[4],-(strpos(strrev($this->arr[4]),"."))), $hostN, $orderBy)."</DIV>\n\n";
                        }
                     $this->lstFiles.="</DIV>\n\n";
                     unset($this->arr,$this->result);
                  }
               }
               unset($this->arrR,$this->resultR);               
               return $this->lstFiles;
         }
      }
   }

//------------------------------------------------------------------------------------------------------------

   function replaceTemplate($id, $FileName, $FileAuthors='', $FileContent='', $FileFrom='', $FileSpace='', $FileExt='', $hostN, $depart)
   {
      $this->PreFiles=str_replace("[/:filesName]","<a href=\"$hostN/index.php?go=filesarchive_A&orderBy=".$depart."&id=".$id."\">$FileName</a>",$this->templateFile);
      if(ereg("/:filesAuthors",$this->templateFile)) $this->PreFiles=str_replace("[/:filesAuthors]",$FileAuthors,$this->PreFiles);
      if(ereg("/:filesShortContent",$this->templateFile)) $this->PreFiles=str_replace("[/:filesShortContent]",$FileContent,$this->PreFiles);
      if(ereg("/:filesFromList",$this->templateFile)) $this->PreFiles=str_replace("[/:filesFromList]",$FileFrom,$this->PreFiles);
      if(ereg("/:filesSpace",$this->templateFile)) $this->PreFiles=str_replace("[/:filesSpace]",$FileSpace,$this->PreFiles);
      if(ereg("/:filesExtention",$this->templateFile)) $this->PreFiles=str_replace("[/:filesExtention]",$FileExt,$this->PreFiles);
      if(ereg("/:filesLink",$this->templateFile)) $this->PreFiles=str_replace("[/:filesLink]","",$this->PreFiles);

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
               $this->retAdd.="<DIV class=err>".$lang['A_filesError1']."</DIV>";
            break;
            case 2:
               $this->retAdd.="<DIV class=err>".$lang['A_filesError2']."</DIV>";
            break;
            case 3:
               $this->retAdd.="<DIV class=err>".$lang['A_filesError3']."</DIV>";
            break;
         }
      }

      $this->retAdd.="
            <form action=\"$hostN/medicaljournal/".(($orderBy) ? 'category'.$orderBy.'/' : '')."addFile\" name=formS method=\"post\" enctype=\"multipart/form-data\">
            ".$lang['A_filesRulesAdd']."<br><br>
            <DIV>".$lang['A_filesCategory']."<br><select name=Category>
               <option value=0>".$lang['A_filesNonCategory']."</option>\n";

      $this->resDep=mysql_query("SELECT id,name FROM iws_arfiles_A_department ORDER BY name");
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
            <DIV>".$lang['A_filesName']."<br><input type=text name=FileName></DIV>
            <DIV>".$lang['A_filesAuthor']."<br><input type=text name=FileAuthor></DIV>
            <DIV>".$lang['A_filesContent']."<br><textarea name=FileContent></textarea></DIV>
            <DIV>".$lang['A_filesAddfile']."<br><input type=file name=FileUp size=50>";
            if($this->space || $this->extention)
            {
               $this->retAdd.="<span><br>".$lang['A_filesRestrict'];
               $this->retAdd.=($this->space ? '<br>'.$lang['A_filesRestrictSpace'].' '.$this->space.' Мб' : '');
               $this->retAdd.=($this->extention ? '<br>'.$lang['A_filesRestrictExt'].' '.str_replace(","," ",trim($this->extention)) : '');
               $this->retAdd.="</span>";
            }
      $this->retAdd.="</DIV>
            <DIV><br><input class=AddFileBut type=image src=\"$hostN/design/addFile.gif\" title=\"".$lang['A_filesAddbutton']."\" alt=\"".$lang['A_filesAddbutton']."\" onclick=\"tosubmit(); return false;\"></DIV>
            </form>
            </DIV>";

      return $this->retAdd;

   }


   function FormSearch($hostN,$TextButton,$wrdRet='')
   {
      return "<form name=frmsearch class=search method=get action=\"$hostN/searchMedicaljournal/\">
               <div style=\"vertical-align: middle;\"><input class=searchFile name=words maxlength=120 value='$wrdRet'><input type=image class=search_image title=\"$TextButton\" alt=\"$TextButton\" align=top src=\"$hostN/design/searchFile.gif\"></div>
               </form>";
   }


   function FormCategory($hostN,$TextNonCategory,$orderBy)
   {

      $this->retCat="<select class=selectFile name=orderBy onChange=\"if(this.value>0){ document.location='$hostN/medicaljournal/category'+this.value; } else { document.location='$hostN/medicaljournal'; }\">
                     <option value=0>$TextNonCategory</option>\n";

      $this->resDep=mysql_query("SELECT id,name FROM iws_arfiles_A_department ORDER BY name");
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
      $this->resDep=mysql_query("SELECT name FROM iws_arfiles_A_department WHERE id=".$orderBy);
      if(mysql_numrows($this->resDep)>=1){
         list($this->Cname)=mysql_fetch_row($this->resDep);
         $this->retCat=$this->Cname;
         unset($this->resDep);
      }
      return $this->retCat;

   }

   function NameCategoryRubric($orderBy)
   {
      $this->retCat="";
      $this->resDep=mysql_query("SELECT name FROM iws_arfiles_A_rubric WHERE id=".$orderBy);
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
