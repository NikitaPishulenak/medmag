(function() {
	tinymce.create('tinymce.plugins.AdvancedInfoBlock', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mceInfoBlock', function() {
				ed.windowManager.open({
					file : url + '/dialog.php?vrtp=' + vrtp,
					width : 360 + parseInt(ed.getLang('InfoBlock.delta_width', 0)),
					height : 220 + parseInt(ed.getLang('InfoBlock.delta_height', 0)),
					inline: true,
					popup_css : false
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('InfoBlock', {
				title : 'Вставить информационный блок',
				cmd : 'mceInfoBlock',
				image : url + '/img/icon.gif'
			});
		},

		getInfo : function() {
			return {
				longname : 'Info Block'
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('InfoBlock', tinymce.plugins.AdvancedInfoBlock);
})();