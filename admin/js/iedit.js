SavedRange = null;

function insertTagAround (which, openTag, closeTag) {
//--- Surrounds the selected text with the specified tags

	which.focus ();

	if (document.all) {
		range = document.selection.createRange ();
		range.text = openTag + range.text + closeTag;
		range.select ();
	} else
	if (document.getElementById) {
		sLngt = which.textLength;
		sStrt = which.selectionStart;
		sFnsh = which.selectionEnd;
		if ((sFnsh == 1) || (sFnsh == 2)) { sFnsh = sLngt; }
		which.value = which.value.substr (0, sStrt) + openTag + which.value.substr (sStrt, sFnsh - sStrt) + closeTag + which.value.substr (sFnsh, sLngt - sFnsh);
	}

	which.focus ();

}

function insertTagBehind (which, fullTag) {
//--- Inserts the specified tag at the cursor

	which.focus ();

	if (document.all) {
		range = document.selection.createRange ();
		range.text = fullTag;
		range.select ();
	} else
	if (document.getElementById) {
		sLngt = which.textLength;
		sStrt = which.selectionStart;
		sFnsh = which.selectionEnd;
		if ((sFnsh == 1) || (sFnsh == 2)) { sFnsh = sLngt; }
		which.value = which.value.substr (0, sStrt) + fullTag + which.value.substr (sStrt, sLngt);
	}

	which.focus ();

}

function showHelp (e, helpText) {
//--- Displays help context for the selected question

	_$ ('helpBox').innerHTML = helpText;
	_showAtDynamicPosition (e, _$ ('helpBox'), 'follow', 10, 10);

}

function hideHelp () {
//--- Hides the help context

	_hideElement (_$ ('helpBox'));

}

function showImageUpload (e) {
//--- Displays the image upload box

	if (document.all) {
		SavedRange = document.selection.createRange ();
	}

	_showAtDynamicPosition (e, _$ ('imageUploadBox'), 'belowleft', 0, 5);

	document.forms['imageUpload'].elements['imageURL'].focus ();

}

function hideImageUpload () {
//--- Hides the image upload box

	document.forms['imageUpload'].elements['imageURL'].value = '';
	document.forms['imageUpload'].elements['ImageField'].value = '';
	document.forms['imageUpload'].elements['SubmitButton'].disabled = false;

	_hideElement (_$ ('imageUploadBox'));

	if ((document.all) && (SavedRange != null)) {
		SavedRange.select ();
	}

}

function showLinksUpload (e) {
//--- Displays the links upload box

	if (document.all) {
		SavedRange = document.selection.createRange ();
	}

	_showAtDynamicPosition (e, _$ ('linksUploadBox'), 'belowleft', 0, 5);

	document.forms['linksUpload'].elements['linkURL'].focus ();

}

function hideLinksUpload () {
//--- Hides the links upload box

	document.forms['linksUpload'].elements['linkURL'].value = '';
	document.forms['linksUpload'].elements['SubmitButton'].disabled = false;

	_hideElement (_$ ('linksUploadBox'));

	if ((document.all) && (SavedRange != null)) {
		SavedRange.select ();
	}

}

function showFontsUpload (e) {
//--- Displays the fonts upload box

	if (document.all) {
		SavedRange = document.selection.createRange ();
	}

	_showAtDynamicPosition (e, _$ ('fontsUploadBox'), 'belowleft', 0, 5);

	document.forms['fontsUpload'].elements['Color'].focus ();

}

function hideFontsUpload () {
//--- Hides the fonts upload box

	document.forms['fontsUpload'].elements['Color'].value = '';
	document.forms['fontsUpload'].elements['SubmitButton'].disabled = false;

	_hideElement (_$ ('fontsUploadBox'));

	if ((document.all) && (SavedRange != null)) {
		SavedRange.select ();
	}

}
