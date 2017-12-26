<?php


if(preg_match("/&amp;/",$mainp->design_page)) $mainp->design_page = preg_replace("/&amp;/","&",$mainp->design_page);

$mainp->design_page = preg_replace("/index.php\?go=main/","main/",$mainp->design_page);
$mainp->design_page = preg_replace("/index.php\?go=map/","map/",$mainp->design_page);

if(preg_match("/go=page|go=mpage/",$mainp->design_page))
{
   $mainp->design_page = preg_replace("/index.php\?go=(.{1,5})&block=(\d{1,})&menu=(\d{1,})/","\\1/\\2/\\3/",$mainp->design_page);
   $mainp->design_page = preg_replace("/&act=(\d{1,})/","/\\1/",$mainp->design_page);
}

if(preg_match("/go=news/",$mainp->design_page))
{
   $mainp->design_page = preg_replace("/index.php\?go=news&archiv=0&start=(\d{1,})/","news/\\1/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=news&archiv=1&start=(\d{1,})/","newsarchiv/\\1/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=news&archiv=0&start=/","news/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=news&archiv=1&start=/","newsarchiv/",$mainp->design_page);


   $mainp->design_page = preg_replace("/index.php\?go=news&id=(\d{1,})&archiv=0&act=view&start=(\d{1,})/","news/view/\\1/\\2/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=news&id=(\d{1,})&archiv=1&act=view&start=(\d{1,})/","newsarchiv/view/\\1/\\2/",$mainp->design_page);

   $mainp->design_page = preg_replace("/index.php\?go=news&id=(\d{1,})&archiv=0&act=view/","news/view/\\1/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=news&id=(\d{1,})&archiv=1&act=view/","newsarchiv/view/\\1/",$mainp->design_page);

   $mainp->design_page = preg_replace("/index.php\?go=news&archiv=1/","newsarchiv/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=news&archiv=0/","news/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=news/","news/",$mainp->design_page);
}

if(preg_match("/go=qa/",$mainp->design_page))
{
   $mainp->design_page = preg_replace("/index.php\?go=qa&act=single&questionid=(\d{1,})/","qa/question/\\1/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=qa&category=(\d{1,})&act=addmess&start=(\d{1,})&id=(\d{1,})/","qa/q\\1/addmess/\\2/\\3/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=qa&act=addmess&start=(\d{1,})&id=(\d{1,})/","qa/addmess/\\1/\\2/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=qa&category=(\d{1,})&act=addmess&start=(\d{1,})&id=/","qa/q\\1/addmess/\\2/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=qa&act=addmess&start=(\d{1,})&id=/","qa/addmess/\\1/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=qa&category=(\d{1,})&act=addmess&start=(\d{1,})/","qa/q\\1/addmess/\\2/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=qa&act=addmess&start=(\d{1,})/","qa/addmess/\\1/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=qa&category=(\d{1,})&id=(\d{1,})&act=addmess&start=(\d{1,})/","qa/q\\1/\\2/addmess/\\3/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=qa&id=(\d{1,})&act=addmess&start=(\d{1,})/","qa/\\1/addmess/\\2/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=qa&category=(\d{1,})&act=rules&start=(\d{1,})&id=(\d{1,})/","qa/q\\1/rules/\\2/\\3/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=qa&act=rules&start=(\d{1,})&id=(\d{1,})/","qa/rules/\\1/\\2/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=qa&category=(\d{1,})&act=rules&start=(\d{1,})&id=/","qa/q\\1/rules/\\2/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=qa&act=rules&start=(\d{1,})&id=/","qa/rules/\\1/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=qa&category=(\d{1,})&start=(\d{1,})/","qa/q\\1/\\2/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=qa&start=(\d{1,})/","qa/\\1/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=qa&category=(\d{1,})/","qa/q\\1/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=qa/","qa/",$mainp->design_page);
}


if(preg_match("/go=opros/",$mainp->design_page))
{
   $mainp->design_page = preg_replace("/index.php\?go=opros&act=viewresult&id=(\d{1,})/","opros/viewresult/\\1/",$mainp->design_page);  
}

//------------------------------------------------------------------------------------------------


if(preg_match("/go=GetFile_A/",$mainp->design_page))
{
   $mainp->design_page = preg_replace("/index.php\?go=GetFile_A&orderBy=(\d{1,})&uid=(.{1,32})/","category\\1/\\2/",$mainp->design_page);
}

if(preg_match("/go=filesarchive_A/",$mainp->design_page))
{
   $mainp->design_page = preg_replace("/index.php\?go=filesarchive_A&Rubric=(\d{1,})&start=(\d{1,})/","rubric\\1/\\2/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=filesarchive_A&Rubric=(\d{1,})/","rubric\\1/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=filesarchive_A&orderBy=(\d{1,})&id=(\d{1,})/","category\\1/article\\2/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=filesarchive_A&orderBy=(\d{1,})/","category\\1/",$mainp->design_page);
}


if(preg_match("/go=articles/",$mainp->design_page))
{
   $mainp->design_page = preg_replace("/index.php\?go=articles&orderBy=(\d{1,})&start=(\d{1,})&anons=(\d{1,})/","allarticles/rubric\\1/page\\2/article\\3/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=articles&orderBy=(\d{1,})&anons=(\d{1,})/","allarticles/rubric\\1/article\\2/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=articles&orderBy=(\d{1,})&start=(\d{1,})/","allarticles/rubric\\1/page\\2/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=articles&orderBy=(\d{1,})/","allarticles/rubric\\1/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=articles&act=AllArt&start=(\d{1,})/","allarticles/page\\1/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=articles&act=AllArt/","allarticles/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=articles/","allarticles/",$mainp->design_page);
}


if(preg_match("/go=photosA/",$mainp->design_page))
{

   $mainp->design_page = preg_replace("/index.php\?go=photosA&start=(\d{1,})&tag=(.)/","photos/\\1/tag/\\2",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=photosA&tag=(.)/","photos/tag/\\1",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=photosA&rubric=(\d{1,})&start=(\d{1,})/","photos/rubric\\1/\\2/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=photosA&rubric=(\d{1,})/","photos/rubric\\1/",$mainp->design_page);
   $mainp->design_page = preg_replace("/index.php\?go=photosA/","photos/",$mainp->design_page);
}

if(strpos($mainp->design_page,$_SERVER[REQUEST_URI])>0){
   global $hostName;
   $mainp->design_page = str_replace("<a class=menu href=\"".$hostName.$_SERVER[REQUEST_URI]."\">","<a class=\"menu current_url\" href=\"#\">",$mainp->design_page);
   $mainp->design_page = str_replace("<a class=submenu href=\"".$hostName.$_SERVER[REQUEST_URI]."\">","<a class=\"submenu current_url\" href=\"#\">",$mainp->design_page);
   $mainp->design_page = str_replace("<li><a class=\"navi_current\" href=\"".$hostName.$_SERVER[REQUEST_URI]."\">","<li><a class=\"navi_current\" href=\"#\">",$mainp->design_page);
   $mainp->design_page = str_replace("<li><a href=\"".$hostName.$_SERVER[REQUEST_URI]."\">","<li><a class=navi_current href=\"#\">",$mainp->design_page);
   $mainp->design_page = str_replace("<a href=\"".$hostName.$_SERVER[REQUEST_URI]."\">","<a class=current_url href=\"#\">",$mainp->design_page);
}

?>