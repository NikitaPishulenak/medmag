<?php

session_start();
session_register("mainadvar");

?>
<HTML>
<HEAD>
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
   <script type="text/javascript" src="../../tiny_mce_popup.js"></script>
   <script type="text/javascript" src="../../utils/mctabs.js"></script>
   <script type="text/javascript" src="../../utils/form_utils.js"></script>
   <script type="text/javascript" src="../../utils/validate.js"></script>
<STYLE TYPE="text/css">
BODY   {font-family:Arial; font-size:8px; BACKGROUND-COLOR:buttonface}
input.Ok {border: 1px #000000 solid; color: #ffffff;background-color: #6D6D6D; font-size:7pt;}
TABLE  {font-family:Arial; font-size:11px}
input {border: 1px #6C6C6C solid; font-size:8pt;}
input.chk {border: 0px #ffffff solid;}
select {border: 1px #6C6C6C solid; font-size:8pt;}
hr {color:#6C6C6C; height:1pt}
</STYLE>
<title>Вставить альбом из фотогалереи</title>
</HEAD>
<script>
<!--

   function BODYalbum_RetSelect(iAl)
   {
      tinyMCEPopup.execCommand("mceInsertContent", false, "[/:photoalbum|"+iAl+"]");
      tinyMCEPopup.close();
   }

   function KeyPress()
   {
      if(window.event.keyCode == 27) window.close();
   }

//-->

</script>

<BODY onKeyPress="KeyPress()">

<br>
<TABLE width=100% height=92% CELLPADDING="2" border="0">
  <TR>
    <TD>
<iframe name="BODYalbum" width="100%" height="98%" frameborder=1 marginwidth=0 marginheight=0 src="../../../../../mainiws.php?go=photoalbums&mode=1"></iframe>
      </TD>
  </TR>
</TABLE>
<div align=right>
<input type="button" id="cancel" name="cancel" value="Отменить" onclick="tinyMCEPopup.close();" />
</div>
</TABLE>
</BODY>
</HTML>