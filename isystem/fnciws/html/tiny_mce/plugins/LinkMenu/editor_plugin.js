(function() {
   tinymce.create('tinymce.plugins.AdvancedLinkPluginMenu', {
      init : function(ed, url) {
         // Register commands
         ed.addCommand('mceLinkMenu', function() {
            ed.windowManager.open({
               file : url + '/dialog.php?blok=' + blok,
               width : 550 + parseInt(ed.getLang('LinkMenu.delta_width', 0)),
               height : 400 + parseInt(ed.getLang('LinkMenu.delta_height', 0)),
               inline: true,
               popup_css : false
            }, {
               plugin_url : url
            });
         });

         // Register buttons
         ed.addButton('LinkMenu', {
            title : '—сылка на меню',
            cmd : 'mceLinkMenu',
            image : url + '/img/icon.gif'
         });
      },

      getInfo : function() {
         return {
            longname : 'Link from Menu'
         };
      }
   });

   // Register plugin
   tinymce.PluginManager.add('LinkMenu', tinymce.plugins.AdvancedLinkPluginMenu);
})();