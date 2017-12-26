(function() {
tinymce.create('tinymce.plugins.InfoTemplate', {
    createControl: function(n, cm) {
        switch (n) {
            case 'InfoTemplate':
                var mlb = cm.createListBox('InfoTemplate', {
                     title : 'Шаблоны_ввода',
                     onselect : function(v) {
								if(v>=1) window.location="html_edit.php?tml="+ v + (blok>=1 ? '&blok='+ blok : '') + (vrtp>=1 ? '&vrtp='+vrtp : '&vrtp=-1') + (guidepst==1 ? '&guidepst=1' : '');
                     }
                });

					for (i in templatesAr) { mlb.add(templatesAr[i], i); }
               return mlb;
        }

        return null;
    }
});

tinymce.PluginManager.add('InfoTemplate', tinymce.plugins.InfoTemplate);
})();