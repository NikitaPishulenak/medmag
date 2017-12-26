<?php
include('../../exit.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
   <title>Создать превью</title>
   <style type="text/css">
      body, html {padding:0; margin:0;}
      body {padding:10px 15px; font-family:Arial; font-size:11px;}
      div {padding-bottom:15px;}
      input.Ok {border: 1px #000000 solid; color: #ffffff;background-color: #6D6D6D; font-size:8pt;}
      input {border: 1px #6C6C6C solid; font-size:8pt;}
   </style>

   <link rel="stylesheet" type="text/css" href="fc-cropresizer.css" />
   <script type="text/javascript" src="fc-cropresizer.js"></script>
   <script type="text/javascript">
   //<![CDATA[

   var arr = new Array();

   cropresizer.getObject("photo1").init({
      cropWidth : <?php echo $_GET[cW]; ?>,
      cropHeight : <?php echo $_GET[cH]; ?>,
      onUpdate : function() {
            arr["W"] = this.iWidth;            
            arr["H"] = this.iHeight;
            arr["cY"] = this.cropTop - this.iTop;
            arr["cX"] = this.cropLeft - this.iLeft;
      }
   });
   //]]>
   </script>


<script>
function KeyPress()
{
        if(window.event.keyCode == 27)
                window.close();
}
</script>

</head>


<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--

        arr["alt"] = Nalt.value;
        window.returnValue = arr;
        window.close();

//-->
</SCRIPT>

<body onSelectStart="return false;" onKeyPress="KeyPress()">
<div>
Описание к изображению<br><input name=Nalt size="100" ><br><br>
<div><input ID=Ok TYPE=SUBMIT value=" Готово ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</div>
</div>
<div>
   <img id="photo1" src="../../../PhotoAlbums/<?php echo $_GET[nameF]; ?>" width="<?php echo $_GET[W]; ?>" height="<?php echo $_GET[H]; ?>" />
</div>
</body>
</html>