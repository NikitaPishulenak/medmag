<?php

unset($mainadvar);
session_start();
session_register("mainadvar");

if(!isset($mainadvar) || empty($mainadvar['ath']) || $mainadvar['ath']!="avtores") { 
   return false;
}

include('../../inc/config.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
@mysql_select_db($dbname) or die("Не могу выбрать базу");
mysql_query('SET NAMES "cp1251"');

if(isset($tml) && $tml){
   $result=mysql_query("select id,name,IF(id=".$tml.",template,'') from iws_html_templ where inTemplate=0 and lng='".$mainadvar['lng']."'");
} else {
   $result=mysql_query("select id,name from iws_html_templ where inTemplate=0 and lng='".$mainadvar['lng']."'");
}

$opt = "";

if(mysql_numrows($result)>=1){
   while($arr=mysql_fetch_row($result)){
      if($tml && $tml==$arr[0]) $ast=ereg_replace("\"","'",ereg_replace("(\r|\n)","",stripslashes($arr[2])));
         $opt.="templatesAr['".$arr[0]."'] = '".$arr[1]."';\n";
   }
}             

?>
<html>
<head>
<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
var urlHostCSS = "<?php echo $hostName; ?>/style_<?php echo $mainadvar['lng']; ?>.css";
var urlEditorCSS = "<?php echo $hostName; ?>/editor.css";
var blok = "<?php echo ((isset($blok) && $blok>0) ? $blok : "0"); ?>";
var vrtp = "<?php echo ((isset($vrtp) && $vrtp>=0) ? $vrtp : "-1"); ?>";
var guidepst = "<?php echo ((isset($guidepst) && $guidepst==1) ? "1" : "0"); ?>";
var templatesAr = Array();

<?php echo $opt; ?>

   tinyMCE.init({
      // General options
      mode : "textareas",
      theme : "advanced",
      plugins : "Albums,InfoTemplate,InfoBlock,LinkMenu,pagebreak,style,table,save,advimage,advlink,iespell,inlinepopups,searchreplace,contextmenu,paste,directionality,noneditable,nonbreaking,xhtmlxtras,advlist",

      // Theme options
      theme_advanced_buttons1 : "code,save,|,cut,copy,paste,pasteword,|,undo,redo,|,search,link,LinkMenu,anchor,image,Albums,InfoBlock,|,tablecontrols,|,hr,removeformat,|,forecolor,backcolor",
      theme_advanced_buttons2 : "formatselect,fontselect,fontsizeselect,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,sub,sup,charmap,|,bullist,numlist,|,outdent,indent,blockquote,|,InfoTemplate",
      theme_advanced_buttons3 : "",
      theme_advanced_buttons4 : "",
      theme_advanced_toolbar_location : "top",
      theme_advanced_toolbar_align : "left",
      document_base_url : "<?php echo $hostName; ?>",
      relative_urls : false,
      remove_script_host : false,

      paste_auto_cleanup_on_paste : true,
      paste_convert_headers_to_strong : false,
      paste_strip_class_attributes : "all",
      paste_remove_spans : false,
      paste_remove_styles : false,

      convert_fonts_to_spans : false,
      extended_valid_elements : "iframe[width|height|frameborder|scrolling|marginheight|marginwidth|src],form[name],script[charset|defer|language|src|type],div[*],li[*]",
//     force_br_newlines : true, 
//     force_p_newlines : false, 
      forced_root_block : "",


      // Example content CSS (should be your site CSS)
      content_css : urlEditorCSS,


      // Drop lists for link/image/media/template dialogs
      //template_external_list_url : "lists/template_list.js",
      //external_link_list_url : "lists/link_list.js",
      //external_image_list_url : "lists/image_list.js",
      //media_external_list_url : "lists/media_list.js",

      // Replace values for the template plugin
      template_replace_values : {
         username : "IggiJ",
         staffid : "561937"
      },

      translate_mode : true,
      language : "ru"
   });
</script>

</head>
<body>
<form action="#" onSubmit="return false;" name="FormHTML">
      <div>
         <textarea id="elm1" name="elm1" rows="15" cols="80" style="width: 100%; height: 100%;">
            <?php if(isset($ast)) echo $ast; ?>
         </textarea>
      </div>
</form>
</body>
</html>
