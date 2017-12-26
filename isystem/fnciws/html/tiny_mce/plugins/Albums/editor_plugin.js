(function() {
   tinymce.create('tinymce.plugins.AdvancedAlbums', {
      init : function(ed, url) {
         // Register commands
         ed.addCommand('mceAlbums', function() {
            ed.windowManager.open({
               file : url + '/dialog.php',
               width : 900 + parseInt(ed.getLang('InfoBlock.delta_width', 0)),
               height : 550 + parseInt(ed.getLang('InfoBlock.delta_height', 0)),
               resizable : "yes",
               inline: true,
               popup_css : false
            }, {
               plugin_url : url
            });
         });

         // Register buttons
         ed.addButton('Albums', {
            title : 'Вставить альбом из фотогалереи',
            cmd : 'mceAlbums',
            image : url + '/img/icon.gif'
         });
      },

      getInfo : function() {
         return {
            longname : 'Albums'
         };
      }
   });

   // Register plugin
   tinymce.PluginManager.add('Albums', tinymce.plugins.AdvancedAlbums);
})();