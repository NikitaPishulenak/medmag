
function init() {
	var inst = tinyMCEPopup.editor;
	var elm = inst.selection.getNode();

	elm = inst.dom.getParent(elm, "A");
}

function insertActionMenu(mn,url,sb) {

if(!sb==1){
	var inst = tinyMCEPopup.editor;
	var elm, elementArray;

	if(inst.selection.isCollapsed()) {

		tinyMCEPopup.execCommand("mceInsertContent", false, "<A href='" + url + "'>" + mn +"</A>");

	} else {
		elm = inst.selection.getNode();
		elm = inst.dom.getParent(elm, "A");

	// Remove element if there is no href
		if (!url) {
			tinyMCEPopup.execCommand("mceBeginUndoLevel");
			i = inst.selection.getBookmark();
			inst.dom.remove(elm, 1);
			inst.selection.moveToBookmark(i);
			tinyMCEPopup.execCommand("mceEndUndoLevel");
			tinyMCEPopup.close();
			return;
		}

		tinyMCEPopup.execCommand("mceBeginUndoLevel");

	// Create new anchor elements
		if (elm == null) {
			inst.getDoc().execCommand("unlink", false, null);
			tinyMCEPopup.execCommand("CreateLink", false, url, {skip_undo : 1});

			elementArray = tinymce.grep(inst.dom.select("a"), function(n) {return inst.dom.getAttrib(n, 'href') == url;});
		} else {
			setAttrib(elm, 'href', url);

			if (tinyMCE.isMSIE5)
				elm.outerHTML = elm.outerHTML;
		}
	}

	tinyMCEPopup.execCommand("mceEndUndoLevel");
} else {

	tinyMCEPopup.execCommand("mceInsertContent", false, mn);

}
}

tinyMCEPopup.onInit.add(init);
