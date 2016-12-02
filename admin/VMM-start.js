var unreadMatch = new RegExp ("\([0-9]+\)");
var replyCountMatch = new RegExp ("\([0-9]+\)");
var urlMatch = new RegExp ("https?://[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=#+@]+($|\\s)");
var urlMatchGlobal = new RegExp ("https?://[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=#+@]+($|\\s)", 'g');
var falseUrlMatch = new RegExp ("(^| )(www\\.)", 'g')
var imagePreviews = new Array ();
var imageDownload = new Array ();
var resizeTimeout = null;
var postBoxHeight = 0;
var scrollInterval = null;
var lastHover = null;
var lastYourPostsInterval = null;
var lastScheduledPostsInterval = null;
var lastWhatOthersAreSayingInterval = null;
var lastConversationHistoryInterval = null;
var lastCustomInterval = new Array ();
var lastYourPostsFilter = 'all';
var lastScheduledPostsFilter = 'all';
var lastWhatOthersAreSayingFilter = 'all';
var lastCustomFilter = new Array ();
var dragSrc = null;
var dragCol = null;
var dragTimeout = null;
var dragIcon = document.createElement('img');
dragIcon.src = 'images/move.png';
var isFirefox = (navigator.userAgent.indexOf ('Firefox') != -1);
var isMac = (navigator.userAgent.indexOf ('Macintosh') != -1);
var fbPreviewImgSize = 90; // 90 or 154

function getIEVersion () {
//--- Returns the version of IE

	var rv = -1;
	if (navigator.appName == 'Microsoft Internet Explorer') {
		var ua = navigator.userAgent;
		var re  = new RegExp ("MSIE ([0-9]{1,}[\.0-9]{0,})");
		if (re.exec (ua) != null) {
			rv = parseFloat (RegExp.$1);
		}
	}

	return rv;

}

function getCaretPosition (field) {
//--- Returns the current caret position in a textarea

	if (document.selection) {
		field.focus ();
		var tRange = document.selection.createRange ();
		var stored_range = tRange.duplicate();
		stored_range.moveToElementText (field);
		stored_range.setEndPoint ('EndToEnd', tRange);
		return stored_range.text.length - tRange.text.length;
	} else
	if (field.selectionStart || field.selectionStart == '0') {
		return field.selectionStart;
	}

}

function setCaretPosition (field, pos) {
//--- Sets the current caret position in a textarea

	if (field.createTextRange) {
		field.focus ();
		var tRange = field.createTextRange ();
		tRange.move ('character', pos);
		tRange.select ();
	} else {
		field.setSelectionRange (pos, pos);
	}

}

function NewWindow (URL, Title, Width, Height) {
//--- Opens a new window with the specifics in the center of the screen

	top.modalWindows.openWindow (URL, Title, (parseInt (Width) + 5), (parseInt (Height) + 40), window);

}

function Prompt (Question, Default, Title, ResponseType) {
//--- Replaces the prompt function that doesn't work anymore

	top.modalWindows.openWindow ("prompt-dialog.php?Question=" + escape (Question) + "&Default=" + escape (Default), Title, 460, 130, Array (ResponseType, null), PromptResponse);

}

function PromptResponse (Response) {
//--- Handles the response to a prompt

	if (Response[1] == null) {
		return;
	}

	switch (Response[0]) { // Response Type
		case 1:		if (Response[1]) {
						if (!urlMatch.test (Response[1])) {
							Response[1] = 'http://' + Response[1];
						}
						if (!urlMatch.test (_$ ('social_comment_post_form').Comment.value)) {
							_$ ('social_comment_post_form').Link.value = ''; // always try to capture the link story
						}
						_$ ('social_comment_textarea').select ();
						_$ ('social_comment_textarea').focus ();
						_$ ('social_comment_textarea').value = ((_$ ('social_comment_textarea').value == 'What\'s happening?') || (_$ ('social_comment_textarea').value == '') ? Response[1] : _$ ('social_comment_textarea').value + (_$ ('social_comment_textarea').value[_$ ('social_comment_textarea').value.length - 1] != ' ' ? ' ' : '') + Response[1]);
						pasteLinkHandler (_$ ('social_comment_textarea'), document.forms[0].Session_ID.value);
					}
					break;
	}

}

function ConfirmYesNo (Question, CallOnSubmit, CallOnClose) {
//--- Replaces the confirm function with a custom dialog

	top.modalWindows.openWindow ("yesno-dialog.php?Question=" + escape (Question), 'Confirm', 460, 130, Array (CallOnSubmit), CallOnClose);

}

function Alert (Text, CallOnClose) {
//--- Replaces the alert function with a custom dialog

	top.modalWindows.openWindow ("alert-dialog.php?Text=" + escape (Text) + '&OKButton=OK', 'Notice', 500, 185, Array (), CallOnClose);

}

function charsLeft (service) {
//--- Works out the correct length based on a number of factors

	var orig = _$ ('social_comment_post_form').Comment.value;
	var origMaxLength = 0;
	var linkLen = 20;
	switch (service) {
		case 'fb':	linkLen = 20; origMaxLength = 2500; break;
		case 'li':	linkLen = 20; origMaxLength = 600; break;
		case 'tw':	linkLen = 22; origMaxLength = 140 - (_$ ('social_comment_post_form').Picture.value ? 25 : 0); break;
		default:	linkLen = 20; origMaxLength = 500; break;
	}
	var linkTrimmed = '';
	var linkPadding = Array (linkLen).join ('*');
	var linkMatches = orig.match (urlMatchGlobal);
	if (!linkMatches) {
		return origMaxLength;
	}
	for (var i = 0; i < linkMatches.length; i++) {
		linkTrimmed = linkMatches[i].replace(/^\\s*/, '').replace(/\\s*$/, '').replace(/^ */, '').replace(/ *$/, '');
		if (linkTrimmed.length < linkLen) {
			origMaxLength = origMaxLength - (linkLen - linkTrimmed.length);
		}
		if (linkTrimmed.length > linkLen) {
			origMaxLength = origMaxLength + (linkTrimmed.length - linkLen);
		}
	}

	return origMaxLength;

}

function getAspectRatio (W, H, MaxW, MaxH, BestWeight) {
//--- Works out the best width and height

	//--- Calculate ratio
	var Xratio = MaxW / W;
	var Yratio = MaxH / H;
	var TrueW = 0;
	var TrueH = 0;

	//--- Check that image doesn't already meet our criteria, and if so, do not resize
	if ((W <= MaxW) && (H <= MaxH)) {
		TrueW = W;
		TrueH = H;
	} else
	if (BestWeight == 'W') {
		TrueW = MaxW;
		TrueH = Math.round (Xratio * H);
	} else
	if (BestWeight == 'H') {
		TrueW = Math.round (Yratio * W);
		TrueH = MaxH;
	} else {
		if ((Xratio * H) < MaxH) {
			TrueW = MaxW;
			TrueH = Math.round (Xratio * H);
		} else {
			TrueW = Math.round (Yratio * W);
			TrueH = MaxH;
		}
	}

	return [TrueW, TrueH];

}

function showLinkBox () {
//--- Reposition the link box as an overlay

	if ((_$ ('social_comment_post_form').Link.value == '') || (_$ ('social_comment_post_form').Link.value == 'http://')) {
		return;
	}

	//--- Reset the arrow
	targetElement = _$ ('postBox');
	targetElementPos = _getDynamicObjectPosition (targetElement);

	objectH = _getDynamicObjectHeight (_$ ('social_comment_link_form'));
	objectW = _getDynamicObjectWidth  (_$ ('social_comment_link_form'));
	targetH = _getDynamicObjectHeight (targetElement);
	targetW = _getDynamicObjectWidth  (targetElement);

	_$ ('linkBoxArrowUp').style.left = targetElementPos[0] + parseInt (targetW / 2) + 200 + 'px';
	_$ ('linkBoxArrowUp').style.top  = targetElementPos[1] + targetH - 5 + 'px';
	_$ ('linkBoxArrowUp').style.display = 'block';

	_$ ('social_comment_link_form').style.left = targetElementPos[0] + 90 + 'px';
	_$ ('social_comment_link_form').style.top = targetElementPos[1] + targetH + 7 + 'px';
	if ((_$ ('fb_link')) && (_$ ('tw_link'))) {
		var fb = 0;
		var tw = 0;
		var li = 0;
		var services = _$ ('social_comment_post_form').Services.value.substr (1, _$ ('social_comment_post_form').Services.value.length - 1).split (';');
		for (var i = 0; i < services.length; i++) {
			tService = services[i].split (':');
			switch (tService[0]) {
				case '1':	fb = 1; break;
				case '2':	tw = 1; break;
				case '3':	li = 1; break;
				case '4':	break;
			}
		}
		if ((fb) && (tw)) {
			_$ ('fb_link').style.display = '';
			_$ ('fb_link').style.borderRight = '1px solid #ececec';
			_$ ('tw_link').style.display = '';
			_$ ('tw_link').style.paddingLeft = '15px';
			_$ ('social_comment_link_form').style.width = '1030px';
		} else
		if ((fb)) {
			_$ ('fb_link').style.display = '';
			_$ ('fb_link').style.borderRight = '';
			_$ ('tw_link').style.display = 'none';
			_$ ('tw_link').style.paddingLeft = '0';
			_$ ('social_comment_link_form').style.width = '520px';
		} else {
			_$ ('fb_link').style.display = 'none';
			_$ ('fb_link').style.borderRight = '';
			_$ ('tw_link').style.display = '';
			_$ ('tw_link').style.paddingLeft = '0';
			_$ ('social_comment_link_form').style.width = '520px';
		}
	} else {
		_$ ('social_comment_link_form').style.width = '520px';
	}
	if (_$ ('social_comment_link_form').style.display != 'block') {
		_fadeIn ('social_comment_link_form');
	}

	if (document.all) {
		setTimeout (function () {
			_$ ('linkBoxShadow').style.display = 'block';
			_$ ('linkBoxShadow').style.left = parseInt (_$ ('social_comment_link_form').style.left) + 10 + 'px';
			_$ ('linkBoxShadow').style.top = parseInt (_$ ('social_comment_link_form').style.top) + 5 + 'px';
			_$ ('linkBoxShadow').style.height = _getDynamicObjectHeight (_$ ('social_comment_link_form')) + 'px';
			_$ ('linkBoxShadow').style.width = _$ ('social_comment_link_form').style.width;
		}, 10);
		_$ ('PictureButton').disabled = true;
		_$ ('PictureButton').style.filter = "progid:DXImageTransform.Microsoft.BasicImage(grayscale=1)";
	} else {
		_$ ('PictureButton').disabled = true;
		_$ ('PictureButton').style.opacity = 0.3;
	}

}

function hideLinkBox () {
//--- Hides the link box

	if (!confirm ('Are you sure you want to remove the story details?')) {
		return;
	}

	_$ ('social_link_loading').style.display = 'none';
	_$ ('social_link_not_found').style.display = 'none';
	_$ ('social_link_preview').innerHTML = '';
	_$ ('linkBoxArrowUp').style.display = 'none';
	_$ ('linkBoxShadow').style.display = 'none';
	_$ ('social_comment_post_form').Comment.focus ();
	_$ ('social_comment_post_form').LinkTitle.value = '';
	_$ ('social_comment_post_form').LinkPreviewImage.value = '';
	_$ ('social_comment_post_form').LinkSummary.value = '';

	_$ ('twitterCounter') .innerHTML = countChars (_$ ('social_comment_post_form').Comment, charsLeft ('tw'));
	_$ ('facebookCounter').innerHTML = countChars (_$ ('social_comment_post_form').Comment, charsLeft ('fb'));
	_$ ('linkedInCounter').innerHTML = countChars (_$ ('social_comment_post_form').Comment, charsLeft ('li'));

	_fadeOut ('social_comment_link_form');

	if (document.all) {
		_$ ('PictureButton').disabled = false;
		_$ ('PictureButton').style.filter = "";
	} else {
		_$ ('PictureButton').disabled = false;
		_$ ('PictureButton').style.opacity = 1;
	}

}

function showPictureBox () {
//--- Reposition the picture box as an overlay

	//--- Reset the arrow
	targetElement = _$ ('postBox');
	targetElementPos = _getDynamicObjectPosition (targetElement);

	objectH = _getDynamicObjectHeight (_$ ('social_comment_picture_form'));
	objectW = _getDynamicObjectWidth  (_$ ('social_comment_picture_form'));
	targetH = _getDynamicObjectHeight (targetElement);
	targetW = _getDynamicObjectWidth  (targetElement);

	_$ ('pictureBoxArrowUp').style.left = targetElementPos[0] + parseInt (targetW / 2) + 200 + 'px';
	_$ ('pictureBoxArrowUp').style.top  = targetElementPos[1] + targetH - 5 + 'px';
	_$ ('pictureBoxArrowUp').style.display = 'block';

	_$ ('social_comment_picture_form').style.left = targetElementPos[0] + 90 + 'px';
	_$ ('social_comment_picture_form').style.top = targetElementPos[1] + targetH + 7 + 'px';
	if ((_$ ('fb_pic')) && (_$ ('tw_pic'))) {
		var fb = 0;
		var tw = 0;
		var services = _$ ('social_comment_post_form').Services.value.substr (1, _$ ('social_comment_post_form').Services.value.length - 1).split (';');
		for (var i = 0; i < services.length; i++) {
			tService = services[i].split (':');
			switch (tService[0]) {
				case '1':	fb = 1; break;
				case '2':	tw = 1; break;
				case '3':	break;
				case '4':	break;
			}
		}
		if ((fb) && (tw)) {
			_$ ('fb_pic').style.display = '';
			_$ ('fb_pic').style.borderRight = '1px solid #ececec';
			_$ ('tw_pic').style.display = '';
			_$ ('tw_pic').style.paddingLeft = '15px';
			_$ ('social_comment_picture_form').style.width = '1030px';
		} else
		if ((fb)) {
			_$ ('fb_pic').style.display = '';
			_$ ('fb_pic').style.borderRight = '';
			_$ ('tw_pic').style.display = 'none';
			_$ ('tw_pic').style.paddingLeft = '0';
			_$ ('social_comment_picture_form').style.width = '520px';
		} else {
			_$ ('fb_pic').style.display = 'none';
			_$ ('fb_pic').style.borderRight = '';
			_$ ('tw_pic').style.display = '';
			_$ ('tw_pic').style.paddingLeft = '0';
			_$ ('social_comment_picture_form').style.width = '520px';
		}
	} else {
		_$ ('social_comment_picture_form').style.width = '520px';
	}
	if (_$ ('social_comment_picture_form').style.display != 'block') {
		_fadeIn ('social_comment_picture_form');
	}

	if (document.all) {
		setTimeout (function () {
			_$ ('pictureBoxShadow').style.display = 'block';
			_$ ('pictureBoxShadow').style.left = parseInt (_$ ('social_comment_picture_form').style.left) + 10 + 'px';
			_$ ('pictureBoxShadow').style.top = parseInt (_$ ('social_comment_picture_form').style.top) + 5 + 'px';
			_$ ('pictureBoxShadow').style.height = _getDynamicObjectHeight (_$ ('social_comment_picture_form')) + 'px';
			_$ ('pictureBoxShadow').style.width = _$ ('social_comment_picture_form').style.width;
		}, 10);
		_$ ('PictureButton').disabled = true;
		_$ ('PictureButton').style.filter = "progid:DXImageTransform.Microsoft.BasicImage(grayscale=1)";
	} else {
		_$ ('PictureButton').disabled = true;
		_$ ('PictureButton').style.opacity = 0.3;
	}

}

function hidePictureBox () {
//--- Hides the picture box

	if (!confirm ('Are you sure you want to remove the attached picture?')) {
		return;
	}

	_$ ('social_picture_loading').style.display = 'none';
	_$ ('social_picture_not_found').style.display = 'none';
	_$ ('social_picture_preview').innerHTML = '';
	_$ ('pictureBoxArrowUp').style.display = 'none';
	_$ ('pictureBoxShadow').style.display = 'none';
	_$ ('social_comment_post_form').Comment.focus ();
	_$ ('social_comment_post_form').Picture.value = '';

	_$ ('twitterCounter') .innerHTML = countChars (_$ ('social_comment_post_form').Comment, charsLeft ('tw'));
	_$ ('facebookCounter').innerHTML = countChars (_$ ('social_comment_post_form').Comment, charsLeft ('fb'));
	_$ ('linkedInCounter').innerHTML = countChars (_$ ('social_comment_post_form').Comment, charsLeft ('li'));

	_fadeOut ('social_comment_picture_form');

	if (document.all) {
		_$ ('PictureButton').disabled = false;
		_$ ('PictureButton').style.filter = "";
	} else {
		_$ ('PictureButton').disabled = false;
		_$ ('PictureButton').style.opacity = 1;
	}

}

function pasteLinkHandler (which, Session_ID) {
//--- Handles the paste request

	if (falseUrlMatch.test (which.value)) {
		var c = getCaretPosition (which);
		which.value = which.value.replace (falseUrlMatch, "$1http://$2");
		setCaretPosition (which, c + 7);
	}

	if ((urlMatch.test (which.value)) && (_$ ('social_comment_link_form').style.display == 'none') && (_$ ('social_comment_picture_form').style.display == 'none')) {

		var newLink = which.value.match (urlMatch)[0].replace(/^\\s*/, '').replace(/\\s*$/, '').replace(/^ */, '').replace(/ *$/, '');
		if (newLink == which.form.Link.value) {
			return; // ignore
		}

		which.form.Link.value = newLink;

		_$ ('social_link_loading').style.display = 'block';
		_$ ('social_link_preview').style.display = 'none';
		showLinkBox ();

		_ajaxRequest ('VMM-url-cache.php?URL=' + encodeURI (which.form.Link.value) + '&Session_ID=' + Session_ID + '&Rand=' + Math.random (0, 10000), 'get', getLinkProperties, getLinkProperties);

	}

}

function textAreaPaste (e, which, Session_ID) {
//--- Prepares for a paste event

	setTimeout (function () { pasteLinkHandler (which, Session_ID); }, 10);

}

function textAreaKeyUp (e, which, Session_ID) {
//--- Handles key events in a comments text area

	//--- What was typed last?
	var key = (e.keyCode ? e.keyCode : e.which);
	var validTriggers = 
		((key == 32) || (key == 13)) || 
		((key == 86) && (e.ctrlKey)) || 
		((key == 86) && (e.metaKey));

	if (falseUrlMatch.test (which.value)) {
		var c = getCaretPosition (which);
		which.value = which.value.replace (falseUrlMatch, "$1http://$2");
		setCaretPosition (which, c + 7);
	}

	if (_$ ('social_link_fb_comment')) {
		_$ ('social_link_fb_comment').innerHTML = (_$ ('social_comment_post_form').Picture.value != '' ? which.value.replace (urlMatchGlobal, '<span style="color: #3b5998">shortened.link</span> ').replace (/\n/g, '<br />') : which.value.replace (urlMatch, ' ').replace (urlMatchGlobal, '<span style="color: #3b5998">shortened.link</span> ').replace (/\n/g, '<br />'));
		_$ ('social_link_tw_comment').innerHTML = (_$ ('social_comment_post_form').Picture.value != '' ? which.value.replace (urlMatchGlobal, '<span style="color: #2fc2ef">shortened.link</span> ').replace (/\n/g, '<br />') + (which.value.charAt (which.value.length - 1) != ' ' ? ' ' : '') + '<span style="color: #2fc2ef">picture.link</span>' : which.value.replace (urlMatchGlobal, '<span style="color: #2fc2ef">shortened.link</span> ').replace (/\n/g, '<br />'));
		_$ ('linkBoxShadow').style.height = _getDynamicObjectHeight (_$ ('social_comment_link_form')) + 'px';
		_$ ('pictureBoxShadow').style.height = _getDynamicObjectHeight (_$ ('social_comment_picture_form')) + 'px';
	}

	if (!validTriggers) {
		return;
	}

	pasteLinkHandler (which, Session_ID);

}

function countChars (which, maxChars) {
//--- Counts the characters and returns a message if too big

	return (which.value.length ? (which.value.length > maxChars ? '<span style=\'color: red\'>' + (maxChars - which.value.length) + '</span>' : (maxChars - which.value.length)) : maxChars);

}

function getLinkProperties (response, url) {
//--- Retrieves information about a supplied link and displays a link preview box

	if ((response.status != 200) || (!response.responseXML)) {
		_$ ('social_link_loading').style.display = 'none';
		_$ ('social_link_preview').style.display = 'none';
		_$ ('social_link_not_found').style.display = 'block';
		showLinkBox ();
		return;
	}

	//--- Parse through XML response
	var linkResult = response.responseXML.getElementsByTagName ('link');
	if (linkResult.length != 1) {
		_$ ('social_link_loading').style.display = 'none';
		_$ ('social_link_preview').style.display = 'none';
		_$ ('social_link_not_found').style.display = 'block';
		showLinkBox ();
		return;
	}

	var title = (linkResult[0].getElementsByTagName ('title')[0].firstChild ? linkResult[0].getElementsByTagName ('title')[0].firstChild.nodeValue.toString () : 'No title');
	var summary = (linkResult[0].getElementsByTagName ('summary')[0].firstChild ? linkResult[0].getElementsByTagName ('summary')[0].firstChild.nodeValue.toString () : '&nbsp;');
	var url = linkResult[0].getElementsByTagName ('url')[0].firstChild.nodeValue.toString ();
	var images = linkResult[0].getElementsByTagName ('image');

	if (url.length > 70) {
		url = url.substr (0, 70) + '...';
	}

	HTML  = '';
	HTML += '<div id="fb_link" style="float: left;">';
	HTML += '	<div style="width: 30px; margin-right: 5px; float: left; margin-top:10px"><img src="images/social/big-facebook.png" width="24" />&nbsp;<img src="images/linkedin-page.png" width="24" /></div>';
	HTML += '	<div style="width: 440px; float: left; min-height: 110px; padding-right: 10px;">';
	HTML += '		<div id="social_link_fb_comment" style="font-family: \'Lucida Grande\', Tahoma, Verdana, Arial, sans-serif; font-size: 13px; font-weight: normal; line-height: 17px; width: 430px; word-break: break-word; word-wrap: break-word;">' + _$ ('social_comment_post_form').Comment.value.replace (urlMatch, ' ').replace (urlMatchGlobal, '<span style="color: #3b5998">shortened.link</span> ').replace (/\n/g, '<br />') + '</div>';
	HTML += '		<table border="0" cellpadding="0" cellspacing="1" style="width: 440px; margin-top: 10px; margin-bottom: 5px; background-color: #e2e2e2">';
	HTML += '		<tr valign="top">';
	HTML += '			<td id="social_link_preview_image_nothumbnail" style="display: none; background-color: #f8f8f8; text-align: center; width: ' + fbPreviewImgSize + 'px; line-height: ' + fbPreviewImgSize + 'px; vertical-align: middle;"><div id="social_link_preview_image" style="width: ' + fbPreviewImgSize + 'px; height: ' + fbPreviewImgSize + 'px; overflow: hidden; text-align: center;"></div></td>';
	HTML += '			<td style="background-color: #f8f8f8; height: ' + fbPreviewImgSize + 'px;">';
	HTML += '				<div id="social_link_preview_summary" style="margin: 8px; height: ' + (fbPreviewImgSize - 18) + 'px; overflow: hidden;">';
	HTML += '					<div id="social_link_preview_title" style="font-family: \'Lucida Grande\', Tahoma, Verdana, Arial, sans-serif; font-size: 11px; font-weight: bold; color: #3b5998;" onclick="editLinkContents (this.id, \'textarea\', _$ (\'social_comment_post_form\').LinkTitle, \'font-family: \\\'Lucida Grande\\\', Tahoma, Verdana, Arial, sans-serif; font-size: 11px; font-weight: bold; width: 225px; padding: 0; border: 0; resize: none; outline: none;\')">' + (title ? title : '&nbsp;') + '</div>';
	HTML += '					<div id="social_link_preview_url" style="font-family: \'Lucida Grande\', Tahoma, Verdana, Arial, sans-serif; font-size: 11px; color: gray;">' + url + '</div>';
	HTML += '					<div id="social_link_preview_summaryblock" style="font-family: \'Lucida Grande\', Tahoma, Verdana, Arial, sans-serif; font-size: 11px; color: gray; margin-top: 5px; word-break: break-word; word-wrap: break-word;" onclick="editLinkContents (this.id, \'textarea\', _$ (\'social_comment_post_form\').LinkSummary, \'font-family: \\\'Lucida Grande\\\', Tahoma, Verdana, Arial, sans-serif; font-size: 11px; width: 225px; padding: 0; border: 0; resize: none; outline: none;\')">' + (summary ? summary : '&nbsp;') + '</div>';
	HTML += '				</div>';
	HTML += '			</td>';
	HTML += '		</tr>';
	HTML += '		</table>';
	HTML += '		<div id="social_link_preview_image_pickthumbnail" style="display: none; margin: 5px 0 0px 0;">';
	HTML += '			<table id="social_link_preview_numbers" border="0" cellpadding="0" cellspacing="0" style="margin-top: 4px; float: left;">';
	HTML += '			<tr valign="middle">';
	HTML += '				<td width="10"><a style="border: 1px solid #cccccc; padding: 0px 5px; text-decoration: none;" href="#" onclick="browseLinkPreviews (-1); return false">&lt;</a></td>';
	HTML += '				<td width="10" align="right"><a style="border: 1px solid #cccccc; padding: 0px 5px; text-decoration: none;" href="#" onclick="browseLinkPreviews (1); return false">&gt;</a></td>';
	HTML += '				<td width="60" style="padding-left: 5px; font-size: 9px; font-weight: bold"><span id="social_link_preview_image_page">1</span> of <span id="social_link_preview_image_count">1</span></td>';
	HTML += '			</tr>';
	HTML += '			</table>';
	HTML += '			<div style="float: left;"><label class="RadioMiddle"><input type="checkbox" name="NoThumb" value="1" onclick="if (this.checked) { _$ (\'social_link_preview_numbers\').style.display = \'none\'; _$ (\'social_link_preview_image_nothumbnail\').style.display = \'none\'; } else { _$ (\'social_link_preview_numbers\').style.display = \'\'; _$ (\'social_link_preview_image_nothumbnail\').style.display = \'\'; }" class="RadioMiddle" /><small>No thumbnail</small></label></div>';
	HTML += '			<br style="clear: both" />'
	HTML += '		</div>';
	HTML += '	</div>';
	HTML += '</div>';
	HTML += '<div id="tw_link" style="float: left;">';
	HTML += '	<div style="width: 50px; margin-right: 5px; float: left;"><img src="images/social/big-twitter.png" /></div>';
	HTML += '	<div style="width: 438px; float: left;">';
	HTML += '		<div id="social_link_tw_comment" style="font-family: \'Helvetica Neue\', Arial, sans-serif; font-size: 14px; font-weight: normal; line-height: 18px; word-break: break-word; word-wrap: break-word;">' + _$ ('social_comment_post_form').Comment.value.replace (urlMatchGlobal, '<span style="color: #2fc2ef">shortened.link</span> ').replace (/\n/g, '<br />') + '</div>';
	HTML += '	</div>';
	HTML += '</div>';
	HTML += '<br style="clear: both;" />';

	_$ ('social_link_loading').style.display = 'none';
	_$ ('social_link_not_found').style.display = 'none';
	_$ ('social_link_preview').style.display = '';
	_$ ('social_link_preview').innerHTML = HTML;
	_$ ('social_comment_post_form').LinkTitle.value = title;
	_$ ('social_comment_post_form').LinkSummary.value = summary;

	//--- Load images...
	if (images.length > 0) {
		imagePreviews.length = 0; // reset
		imageDownload.length = 0; // reset
		for (i = 0; i < images.length; i++) {
			imageFile = images[i].firstChild.nodeValue.toString ();
			imagePreviews[i] = new Image ();
			imagePreviews[i].onload = function () { if ((this.width < 50) || (this.height < 50)) { return; } if (_$ ('social_link_preview_image').innerHTML == '') { var ratio = getAspectRatio (this.width, this.height, fbPreviewImgSize, fbPreviewImgSize, 'H'); var imgAttr = ((ratio[0] < fbPreviewImgSize) && (ratio[1] >= fbPreviewImgSize) && (this.width >= fbPreviewImgSize) ? 'width: ' + fbPreviewImgSize + 'px; ' : ((ratio[1] < fbPreviewImgSize) && (ratio[0] >= fbPreviewImgSize) && (this.height >= fbPreviewImgSize) ? 'height: ' + fbPreviewImgSize + 'px; ' : 'width: ' + ratio[0] + 'px; height: ' + ratio[1] + 'px; ')); _$ ('social_link_preview_image').innerHTML = '<img src="' + this.src + '" style="' + imgAttr + 'vertical-align: middle;' + (ratio[0] > fbPreviewImgSize ? ' margin-left: -' + (Math.round (ratio[0] / 2) - (fbPreviewImgSize / 2)) + 'px;' : '') + '" />'; _$ ('social_comment_post_form').LinkPreviewImage.value = this.src; _$ ('social_link_preview_image_nothumbnail').style.display = ''; _$ ('social_link_preview_image_pickthumbnail').style.display = ''; imageDownload[imageDownload.length] = this; _$ ('linkBoxShadow').style.height = _getDynamicObjectHeight (_$ ('social_comment_link_form')) + 'px'; } else { if (_$ ('social_link_preview_image_count')) { _$ ('social_link_preview_image_count').innerHTML = parseInt (_$ ('social_link_preview_image_count').innerHTML) + 1; imageDownload[imageDownload.length] = this; } } }
			imagePreviews[i].src = imageFile;
		}
	}

	showLinkBox ();

}

function browseLinkPreviews (direction) {
//--- Views the next or previous image in the imageDownload array

	var previewsCounter = parseInt (_$ ('social_link_preview_image_count').innerHTML);
	var thisPreview = parseInt (_$ ('social_link_preview_image_page').innerHTML);
	var nextPreview = parseInt (_$ ('social_link_preview_image_page').innerHTML) + direction; // if negative, goes backward

	if ((nextPreview < 1) || (nextPreview > previewsCounter)) {
		return; // do nothing
	}

	var ratio = getAspectRatio (imageDownload[nextPreview - 1].width, imageDownload[nextPreview - 1].height, fbPreviewImgSize, fbPreviewImgSize, 'H');
	var imgAttr = ((ratio[0] < fbPreviewImgSize) && (ratio[1] >= fbPreviewImgSize) && (this.width >= fbPreviewImgSize) ? 'width: ' + fbPreviewImgSize + 'px; ' : ((ratio[1] < fbPreviewImgSize) && (ratio[0] >= fbPreviewImgSize) && (this.height >= fbPreviewImgSize) ? 'height: ' + fbPreviewImgSize + 'px; ' : 'width: ' + ratio[0] + 'px; height: ' + ratio[1] + 'px; '));

	//--- Look for the next image in the array
	_$ ('social_link_preview_image').innerHTML = '<img src="' + imageDownload[nextPreview - 1].src + '" style="' + imgAttr + 'vertical-align: middle;' + (ratio[0] > fbPreviewImgSize ? ' margin-left: -' + (Math.round (ratio[0] / 2) - (fbPreviewImgSize / 2)) + 'px;' : '') + '" />';
	_$ ('social_link_preview_image_page').innerHTML = nextPreview;
	_$ ('social_comment_post_form').LinkPreviewImage.value = imageDownload[nextPreview - 1].src;
	_$ ('linkBoxShadow').style.height = _getDynamicObjectHeight (_$ ('social_comment_link_form')) + 'px';
	showLinkBox ();

}

function editLinkContents (element, elementType, saveIntoElement, styles) {
//--- Switches the contents within a DIV tag to an editable input box or textarea

	if (_$ (element).firstChild.nodeName.toString ().toLowerCase () == elementType) {
		return;
	}

	if (element == 'social_link_preview_summaryblock') {
		_$ ('social_link_preview_summary').style.height = (fbPreviewImgSize - 18) + 'px';
		_$ ('social_link_preview_summary').style.overflow = 'visible';
		_$ ('linkBoxShadow').style.height = (_getDynamicObjectHeight (_$ ('social_comment_link_form')) - (document.all ? 10 : 0)) + 'px';
	}
	var editable = document.createElement (elementType);
	editable.onblur = function () { _$ (element).innerHTML = (this.value.replace (/^\s+|\s+$/g, '') ? this.value.replace (/</g, '&lt;').replace (/>/g, '&gt;').replace (/&nbsp;/, ' ') : '&nbsp;'); saveIntoElement.value = this.value; _$ ('social_link_preview_summary').style.height = (fbPreviewImgSize - 18) + 'px'; _$ ('social_link_preview_summary').style.overflow = 'hidden'; _$ ('linkBoxShadow').style.height = _getDynamicObjectHeight (_$ ('social_comment_link_form')) + 'px'; showLinkBox (); }
	editable.onkeydown = function (e) { var key = (e ? (e.which ? e.which : e.keyCode) : (event.which ? event.which : event.keyCode)); if (key == 13) { this.blur (); } }
	editable.style.cssText = (styles ? styles : _$ (element).style.cssText);
	editable.value = _$ (element).firstChild.nodeValue.toString ().replace (/^\s+|\s+$/g, '');

	//--- Title, do this before
	if (element == 'social_link_preview_title') {
		editable.style.width = ((!_$ ('social_comment_post_form').LinkPreviewImage.value) || (_$ ('social_comment_post_form').NoThumb.checked) ? '380px' : (379 - fbPreviewImgSize) + 'px');
		editable.style.height = _getDynamicObjectHeight (_$ ('social_link_preview_title')) + 'px';
	}

	_$ (element).replaceChild (editable, _$ (element).firstChild);

	//--- Summary, do it after
	if (element == 'social_link_preview_summaryblock') {
		editable.style.width = ((!_$ ('social_comment_post_form').LinkPreviewImage.value) || (_$ ('social_comment_post_form').NoThumb.checked) ? '380px' : (379 - fbPreviewImgSize) + 'px');
		editable.style.height = ((fbPreviewImgSize - 18) - 20 - _getDynamicObjectHeight (_$ ('social_link_preview_title'))) + 'px';
	}

	showLinkBox ();

	editable.focus (); // focus on it (just in case)

}

function _sizeColumns () {

	if (!_$ ('myViews')) {
		return;
	}

	if (_getBodyWidth () > (myViewsList.length * 440)) {
		_$ ('myViews').style.display = 'table';
		_$ ('myViews').style.width = '';
		for (i = 0; i < myViewsList.length; i++) {
			_$ (myViewsList[i] + 'Column').style.width = Math.round (100 / myViewsList.length) + '%';
		}
	} else {
		_$ ('myViews').style.display = 'block';
		_$ ('myViews').style.width = '100%';
		for (i = 0; i < myViewsList.length; i++) {
			_$ (myViewsList[i] + 'Column').style.width = '430px';
		}
	}

}

function _showProfiles (Session_ID, search) {
//--- Displays a popup view of all social profiles

	socialProfilesWindow = window.open ('VMM-social-browse.php?Session_ID=' + Session_ID + (search != '' ? '&Search=' + search : ''), 'socialProfilesPopup', 'directories=0,height=700,width=500,toolbar=0,status=0,resizable=1,location=1');
	socialProfilesWindow.focus (); 

}

function _showReplyToPost (e, defaultValue, service, layer, inReplyToPost, inReplyToServiceId) {
//--- Opens a reply box

	ieVersion = getIEVersion ();

	//--- Reset the arrow
	targetElement = (e.target ? e.target : e.srcElement);
	targetElementPos = _getDynamicObjectPosition (targetElement);

	_$ ('postReply').style.height = '125px'; // need this to calculate the height (oddly enough)
	objectH = _getDynamicObjectHeight (_$ ('postReply'));
	objectW = _getDynamicObjectWidth  (_$ ('postReply'));
	targetH = _getDynamicObjectHeight (targetElement);
	targetW = _getDynamicObjectWidth  (targetElement);

	if (window.pageXOffset) {
		scrlW = window.pageXOffset;
		pageW = window.document.body.clientWidth + scrlW;
	} else {
		scrlW = window.document.body.scrollLeft;
		pageW = window.document.body.clientWidth + scrlW;
	}
	if (window.pageYOffset) {
		scrlH = window.pageYOffset;
		pageH = window.document.body.clientHeight + scrlH;
	} else {
		scrlH = window.document.body.scrollTop;
		pageH = window.document.body.clientHeight + scrlH;
	}

	windowH = pageH;
	windowW = pageW;

	_$ ('replyBoxArrowUp').style.display = 'none';
	_$ ('replyBoxArrowDown').style.display = 'none';

	layerScrollT = parseInt (_$ (layer).scrollTop);
	layerScrollL = parseInt (_$ ('myViews').scrollLeft);
	if (layerScrollT) {
		targetElementPos[1] = parseInt (targetElementPos[1]) - layerScrollT;
	}

	if (parseInt (targetElementPos[1]) + objectH + 70 > windowH) {
		_showAtDynamicPosition (e, _$ ('postReply'), 'aboveleft', 0, 0 - 13);
		_$ ('replyBoxArrowDown').style.left = targetElementPos[0] - layerScrollL + parseInt (targetW / 2) - 5 + 'px';
		_$ ('replyBoxArrowDown').style.top  = targetElementPos[1] - layerScrollT - (document.all ? 13 : 11) + 'px';
		_$ ('replyBoxArrowDown').style.display = 'block';
	} else {
		_showAtDynamicPosition (e, _$ ('postReply'), 'belowleft', 0, 12);
		_$ ('replyBoxArrowUp').style.left = targetElementPos[0] - layerScrollL + parseInt (targetW / 2) - 5 + 'px';
		_$ ('replyBoxArrowUp').style.top  = targetElementPos[1] - layerScrollT + targetH + 'px';
		_$ ('replyBoxArrowUp').style.display = 'block';
	}

	//--- Move the box if it's over the edge
	if (layerScrollT) {
		_$ ('postReply').style.top  = (parseInt (_$ ('postReply').style.top)  - layerScrollT) + 'px';
	}
	if (layerScrollL) {
		_$ ('postReply').style.left = (parseInt (_$ ('postReply').style.left) - layerScrollL - 10) + 'px';
	}
	if (parseInt (_$ ('postReply').style.left) + objectW  > windowW) {
		_$ ('postReply').style.left = windowW - objectW - (document.all ? 40 : 20) + 'px';
	}

	//--- Show block
	_$ ('postReply').style.display = 'block';

	_$ ('replyComment').focus ();
	_$ ('replyComment').value = defaultValue;

	service = service.split (':');
	serviceType = service[0]; serviceId = service[1];

	switch (serviceType) {
		case '1':	_$ ('replyIcon').src = servicePics[serviceId];
					_$ ('replyName').innerHTML = '<img src="images/social/facebook.png" width="16" height="16" align="absmiddle" /> ' + serviceScreenNames[serviceId];
					_$ ('replyCharLimit').style.display = '';
					_$ ('replyCounter').innerHTML = countChars (_$ ('replyComment'), 2500);
					_$ ('social_comment_reply_form').MaxChars.value = '2500';
					break;
		case '2':	_$ ('replyIcon').src = servicePics[serviceId];
					_$ ('replyName').innerHTML = '<img src="images/social/twitter.png" width="16" height="16" align="absmiddle" /> ' + serviceScreenNames[serviceId];
					_$ ('replyCharLimit').style.display = '';
					_$ ('replyCounter').innerHTML = countChars (_$ ('replyComment'), 140);
					_$ ('social_comment_reply_form').MaxChars.value = '140';
					break;
		case '3':	_$ ('replyIcon').src = servicePics[serviceId];
					_$ ('replyName').innerHTML = '<img src="images/social/linkedin.png" width="16" height="16" align="absmiddle" /> ' + serviceScreenNames[serviceId];
					_$ ('replyCharLimit').style.display = '';
					_$ ('replyCounter').innerHTML = countChars (_$ ('replyComment'), 700);
					_$ ('social_comment_reply_form').MaxChars.value = '700';
					break;
		case '4':	_$ ('replyIcon').src = servicePics[serviceId];
					_$ ('replyName').innerHTML = '<img src="images/social/youtube.png" width="16" height="16" align="absmiddle" /> ' + serviceScreenNames[serviceId];
					_$ ('replyCharLimit').style.display = 'none';
					break;
	}

	if (document.all) {
		_$ ('replyBoxShadow').style.display = 'block';
		_$ ('replyBoxShadow').style.left = parseInt (_$ ('postReply').style.left) + 5 + 'px';
		_$ ('replyBoxShadow').style.top = parseInt (_$ ('postReply').style.top) + 10 + 'px';
		_$ ('replyBoxShadow').style.height = parseInt (objectH) + 'px';
		_$ ('replyBoxShadow').style.width = parseInt (objectW) + 'px';
		document.body.attachEvent ('onmouseup', _hideReplyToPost);
		window.attachEvent ('onscroll', _hideReplyToPost);
		window.attachEvent ('onresize', _hideReplyToPost);
		for (i = 0; i < myViewsList.length; i++) {
			_$ (myViewsList[i]).attachEvent ('onscroll', _hideReplyToPost);
		}
	} else {
		document.addEventListener ('mouseup', _hideReplyToPost, false);
		window.addEventListener ('scroll', _hideReplyToPost, false);
		window.addEventListener ('resize', _hideReplyToPost, false);
		for (i = 0; i < myViewsList.length; i++) {
			_$ (myViewsList[i]).addEventListener ('scroll', _hideReplyToPost, false);
		}
	}

	_$ ('social_comment_reply_form').elements['InReplyToPost_ID'].value = inReplyToPost;
	_$ ('social_comment_reply_form').elements['ServiceType'].value = serviceType;
	_$ ('social_comment_reply_form').elements['ServiceAssigned_ID'].value = serviceId;
	_$ ('social_comment_reply_form').elements['InReplyToServiceAssigned_ID'].value = inReplyToServiceId;
	_$ ('social_comment_reply_form').elements['Layer'].value = layer;

}

function _hideReplyToPost (e) {
//--- Closes a reply box

	try {
		if ((e) && (e.type == 'mouseup')) {
			mouseX = (window.event.clientX ? window.event.clientX + document.body.scrollLeft : e.pageX);
			mouseY = (window.event.clientY ? window.event.clientY + document.body.scrollTop  : e.pageY);
			objectH = _getDynamicObjectHeight (_$ ('postReply'));
			objectW = _getDynamicObjectWidth  (_$ ('postReply'));
			objectPosition = _getDynamicObjectPosition (_$ ('postReply'));
			if ((mouseX >= objectPosition[0]) && (mouseX <= objectPosition[0] + objectW) && (mouseY >= objectPosition[1]) && (mouseY <= objectPosition[1] + objectH)) {
				return false;
			}
		}
	} catch (ex) {
	}

	_$ ('postReply').style.display = 'none';
	_$ ('replyBoxArrowUp').style.display = 'none';
	_$ ('replyBoxArrowDown').style.display = 'none';
	_$ ('replyBoxShadow').style.display = 'none';

	if (document.all) {
		document.body.detachEvent ('onmouseup', _hideReplyToPost);
		window.detachEvent ('onresize', _hideReplyToPost);
		window.detachEvent ('onscroll', _hideReplyToPost);
		for (i = 0; i < myViewsList.length; i++) {
			_$ (myViewsList[i]).detachEvent ('onscroll', _hideReplyToPost);
		}
	} else {
		document.removeEventListener ('mouseup', _hideReplyToPost, false);
		window.removeEventListener ('resize', _hideReplyToPost, false);
		window.removeEventListener ('scroll', _hideReplyToPost, false);
		for (i = 0; i < myViewsList.length; i++) {
			_$ (myViewsList[i]).removeEventListener ('scroll', _hideReplyToPost, false);
		}
	}

}

function _showRetweetBox (e, postID, layer) {
//--- Opens a retweet box

	ieVersion = getIEVersion ();

	targetElement = (e.target ? e.target : e.srcElement);
	targetElementPos = _getDynamicObjectPosition (targetElement);

	_$ ('retweetPost').style.height = '135px'; // need this to calculate the height (oddly enough)
	objectH = _getDynamicObjectHeight (_$ ('retweetPost'));
	objectW = _getDynamicObjectWidth  (_$ ('retweetPost'));
	targetH = _getDynamicObjectHeight (targetElement);
	targetW = _getDynamicObjectWidth  (targetElement);

	if (window.pageXOffset) {
		scrlW = window.pageXOffset;
		pageW = window.document.body.clientWidth + scrlW;
	} else {
		scrlW = window.document.body.scrollLeft;
		pageW = window.document.body.clientWidth + scrlW;
	}
	if (window.pageYOffset) {
		scrlH = window.pageYOffset;
		pageH = window.document.body.clientHeight + scrlH;
	} else {
		scrlH = window.document.body.scrollTop;
		pageH = window.document.body.clientHeight + scrlH;
	}

	windowH = pageH;
	windowW = pageW;

	_$ ('retweetBoxArrowUp').style.display = 'none';
	_$ ('retweetBoxArrowDown').style.display = 'none';

	layerScrollT = parseInt (_$ (layer).scrollTop);
	layerScrollL = parseInt (_$ ('myViews').scrollLeft);
	if (layerScrollT) {
		targetElementPos[1] = parseInt (targetElementPos[1]) - layerScrollT;
	}

	if (parseInt (targetElementPos[1]) + objectH + 70 > windowH) {
		_showAtDynamicPosition (e, _$ ('retweetPost'), 'aboveleft', 0, 0 - 13);
		_$ ('retweetBoxArrowDown').style.left = targetElementPos[0] - layerScrollL + parseInt (targetW / 2) - 5 + 'px';
		_$ ('retweetBoxArrowDown').style.top  = targetElementPos[1] - layerScrollT - (document.all ? 13 : 11) - (ieVersion > 8 ? -2 : 0) + 'px';
		_$ ('retweetBoxArrowDown').style.display = 'block';
	} else {
		_showAtDynamicPosition (e, _$ ('retweetPost'), 'belowleft', 0, 12);
		_$ ('retweetBoxArrowUp').style.left = targetElementPos[0] - layerScrollL + parseInt (targetW / 2) - 5 + 'px';
		_$ ('retweetBoxArrowUp').style.top  = targetElementPos[1] - layerScrollT + targetH + 'px';
		_$ ('retweetBoxArrowUp').style.display = 'block';
	}

	//--- Move the box if it's over the edge
	if (layerScrollT) {
		_$ ('retweetPost').style.top  = (parseInt (_$ ('retweetPost').style.top)  - layerScrollT) + 'px';
	}
	if (layerScrollL) {
		_$ ('retweetPost').style.left = (parseInt (_$ ('retweetPost').style.left) - layerScrollL - 10) + 'px';
	}
	if (parseInt (_$ ('retweetPost').style.left) + objectW  > windowW) {
		_$ ('retweetPost').style.left = windowW - objectW - (document.all ? 40 : 20) + 'px';
	}

	//--- Show block
	_$ ('retweetPost').style.display = 'block';

	if (document.all) {
		_$ ('retweetBoxShadow').style.display = 'block';
		_$ ('retweetBoxShadow').style.left = parseInt (_$ ('retweetPost').style.left) + 5 + 'px';
		_$ ('retweetBoxShadow').style.top = parseInt (_$ ('retweetPost').style.top) + 10 + 'px';
		_$ ('retweetBoxShadow').style.height = parseInt (objectH) + 'px';
		_$ ('retweetBoxShadow').style.width = parseInt (objectW) + 'px';
		document.body.attachEvent ('onmouseup', _hideRetweetBox);
		window.attachEvent ('onscroll', _hideRetweetBox);
		window.attachEvent ('onresize', _hideRetweetBox);
		for (i = 0; i < myViewsList.length; i++) {
			_$ (myViewsList[i]).attachEvent ('onscroll', _hideRetweetBox);
		}
	} else {
		document.addEventListener ('mouseup', _hideRetweetBox, false);
		window.addEventListener ('scroll', _hideRetweetBox, false);
		window.addEventListener ('resize', _hideRetweetBox, false);
		for (i = 0; i < myViewsList.length; i++) {
			_$ (myViewsList[i]).addEventListener ('scroll', _hideRetweetBox, false);
		}
	}

	_$ ('social_comment_retweet_form').elements['Post_ID'].value = postID;
	_$ ('social_comment_retweet_form').elements['Layer'].value = layer;

}

function _hideRetweetBox (e) {
//--- Closes a retweet box

	try {
		if ((e) && (e.type == 'mouseup')) {
			mouseX = (window.event.clientX ? window.event.clientX + document.body.scrollLeft : e.pageX);
			mouseY = (window.event.clientY ? window.event.clientY + document.body.scrollTop  : e.pageY);
			objectH = _getDynamicObjectHeight (_$ ('retweetPost'));
			objectW = _getDynamicObjectWidth  (_$ ('retweetPost'));
			objectPosition = _getDynamicObjectPosition (_$ ('retweetPost'));
			if ((mouseX >= objectPosition[0]) && (mouseX <= objectPosition[0] + objectW) && (mouseY >= objectPosition[1]) && (mouseY <= objectPosition[1] + objectH)) {
				return false;
			}
		}
	} catch (ex) {
	}

	_$ ('retweetPost').style.display = 'none';
	_$ ('retweetBoxArrowUp').style.display = 'none';
	_$ ('retweetBoxArrowDown').style.display = 'none';
	_$ ('retweetBoxShadow').style.display = 'none';

	if (document.all) {
		document.body.detachEvent ('onmouseup', _hideRetweetBox);
		window.detachEvent ('onresize', _hideRetweetBox);
		window.detachEvent ('onscroll', _hideRetweetBox);
		for (i = 0; i < myViewsList.length; i++) {
			_$ (myViewsList[i]).detachEvent ('onscroll', _hideRetweetBox);
		}
	} else {
		document.removeEventListener ('mouseup', _hideRetweetBox, false);
		window.removeEventListener ('resize', _hideRetweetBox, false);
		window.removeEventListener ('scroll', _hideRetweetBox, false);
		for (i = 0; i < myViewsList.length; i++) {
			_$ (myViewsList[i]).removeEventListener ('scroll', _hideRetweetBox, false);
		}
	}

	_$ ('social_comment_retweet_form').elements['SubmitButton'].disabled = false;

}

function _retweetSelectService (whichService, id) {
//--- Selects or deselects a service

	if (whichService.className.indexOf (' selected') != -1) {
		whichService.className = whichService.className.replace (' selected', ' deselected');
		document.forms['social_comment_retweet_form'].elements['Services'].value = document.forms['social_comment_retweet_form'].elements['Services'].value.replace (';' + id, '');
	} else {
		whichService.className = whichService.className.replace (' deselected', ' selected');
		document.forms['social_comment_retweet_form'].elements['Services'].value = document.forms['social_comment_retweet_form'].elements['Services'].value + ';' + id;
	}

}

function _confirmRetweet (serviceType, postID) {
//--- Adds a tick next to the Retweet link

	span = document.createElement ("span");
	span.innerHTML = '&nbsp;<img src="images/fff_tick_small.gif" align="top" />';

	_$ ('link_hover_' + serviceType + ':' + postID).appendChild (span);

}

function _undoRetweet (e, id, Session_ID) {
//--- Attempts to undo a retweet

	targetElement = (e.target ? e.target : e.srcElement);
	targetElementText = targetElement.innerHTML;

	_ajaxRequest ('VMM-post.php?Action=unretweetPost&Post_ID=' + id + '&Session_ID=' + Session_ID + '&Rand=' + Math.random (0, 10000), 'get', function (ajaxObj, url) { targetElement.innerHTML = ''; eval (ajaxObj.responseText); }, function (ajaxObj, url) { alert ('An error occured while contacting Twitter. Please try again.'); });

}

function _likePost (e, id, Session_ID) {
//--- Likes a post

	targetElement = (e.target ? e.target : e.srcElement);
	targetElementText = targetElement.innerHTML;

	_ajaxRequest ('VMM-post.php?Action=' + (targetElementText == 'Like' ? 'likePost' : 'unlikePost') + '&Post_ID=' + id + '&Session_ID=' + Session_ID + '&Rand=' + Math.random (0, 10000), 'get', function (ajaxObj, url) { targetElement.innerHTML = (targetElementText == 'Like' ? 'Unlike' : 'Like'); eval (ajaxObj.responseText); }, function (ajaxObj, url) { alert ('An error occured while contacting the service. Please try again.'); }); 

}

function _startup (Session_ID) {
//--- Loads the latest conversations

	_sizeColumns ();

	for (i = 0; i < myViewsList.length; i++) {
		LoadLayer (myViewsList[i]); _ajaxRequest ('VMM-view-posts.php?View=' + myViewsList[i] + '&Session_ID=' + Session_ID + '&Rand=' + Math.random (0, 10000), 'get', showStream, errorHandler);
		if (document.all) {
			_$ (myViewsList[i]).attachEvent ('onscroll', function () { var el = event.srcElement; if ((_$ (el.id + 'MoreBar')) && (el.scrollTop + parseInt (el.style.height) > el.scrollHeight - 200)) { _$ (el.id + 'MoreBar').onclick (); } });
		} else {
			_$ (myViewsList[i]).addEventListener ('scroll', function () { if ((_$ (this.id + 'MoreBar')) && (this.scrollTop + parseInt (this.style.height) > this.scrollHeight - 200)) { _$ (this.id + 'MoreBar').onclick (); } }, false);			
		}
	}

	if (document.all) {
		_$ ('myViews').attachEvent ('onscroll', _hideReplyToPost);
		_$ ('myViews').attachEvent ('onscroll', _hideRetweetBox);
	} else {
		_$ ('myViews').addEventListener ('scroll', _hideReplyToPost, false);
		_$ ('myViews').addEventListener ('scroll', _hideRetweetBox, false);
	}

}

function _clearAllUpdates (whichFeed) {
//--- Removes the schedule

	switch (whichFeed) {
		case 'yourPosts':			clearInterval (lastYourPostsInterval); break;
		case 'scheduledPosts':		clearInterval (lastScheduledPostsInterval); break;
		case 'whatOthersAreSaying':	clearInterval (lastWhatOthersAreSayingInterval); break;
		case 'conversationHistory':	clearInterval (lastConversationHistoryInterval); break;
		default:					clearInterval (lastCustomInterval[whichFeed]); break;
	}

}

function _showView (whichFeed) {
//--- Show column if it's hidden

	var hasCol = false;
	for (var i = 0; i < myViewsList.length; i++) {
		if (myViewsList[i] == whichFeed) {
			hasCol = true;
		}
	}
	if (hasCol == false) {
		myViewsList.push (whichFeed); _sizeColumns (); _fadeIn (whichFeed + 'Column');
	}

}

function _scheduleUpdates (whichFeed, runNow, Session_ID) {
//--- Schedule updates

	switch (whichFeed) {
		case 'yourPosts':	var interval = lastYourPostsInterval;
							var timeSequence = 'yourPostsInitialTimeSequence_ID';
							var show = _$ ('social_comment_post_form').yourPostsShow.value;
							var find = _$ ('social_comment_post_form').yourPostsFind.value;
							break;
		case 'scheduledPosts':
							var interval = lastScheduledPostsInterval;
							var timeSequence = 'scheduledPostsInitialTimeSequence_ID';
							var show = _$ ('social_comment_post_form').scheduledPostsShow.value;
							var find = _$ ('social_comment_post_form').scheduledPostsFind.value;
							break;
		case 'whatOthersAreSaying':
							var interval = lastWhatOthersAreSayingInterval;
							var timeSequence = 'whatOthersAreSayingInitialTimeSequence_ID';
							var show = _$ ('social_comment_post_form').whatOthersAreSayingShow.value;
							var find = _$ ('social_comment_post_form').whatOthersAreSayingFind.value;
							break;
		case 'conversationHistory':
							var interval = lastConversationHistoryInterval;
							var timeSequence = 'conversationHistoryInitialTimeSequence_ID';
							var show = _$ ('social_comment_post_form').conversationHistoryShow.value;
							var find = _$ ('social_comment_post_form').conversationHistoryFind.value;
							break;
		default:			var interval = lastCustomInterval[whichFeed];
							var timeSequence = whichFeed + 'InitialTimeSequence_ID';
							var show = _$ ('social_comment_post_form').elements[whichFeed + 'Show'].value;
							var find = _$ ('social_comment_post_form').elements[whichFeed + 'Find'].value;
							break;
	}

	if (interval != null) {
		clearInterval (interval);
	}

	//--- Do it once
	if (runNow) {
		_checkForNewFeeds (whichFeed, _$ ('social_comment_post_form').elements[timeSequence].value, show, find, Session_ID);
	}

	//--- Schedule it for later
	interval = setInterval (function () {
		_checkForNewFeeds (whichFeed, _$ ('social_comment_post_form').elements[timeSequence].value, show, find, Session_ID);
	}, 30000);

	switch (whichFeed) {
		case 'yourPosts':				lastYourPostsInterval = interval; break;
		case 'scheduledPosts':			lastScheduledPostsInterval = interval; break;
		case 'whatOthersAreSaying':		lastWhatOthersAreSayingInterval = interval; break;
		case 'conversationHistory':		lastConversationHistoryInterval = interval; break;
		default:						lastCustomInterval[whichFeed] = interval; break;
	}

}

function _extendFeed (whichFeed, TimeSeq, Show, Find, Session_ID) {
//--- Loads conversations beyond a specific point

	_ajaxRequest ('VMM-view-posts.php?View=' + whichFeed + '&TimeSeq=' + TimeSeq + (Show ? '&Show=' + escape (Show) : '') + ((Find) && (Find != 'Find...') ? '&Find=' + escape (Find) : '') + '&Session_ID=' + Session_ID + '&Rand=' + Math.random (0, 10000), 'get', appendStream, errorHandler);

}

function _checkForNewFeeds (whichFeed, TimeSeq, Show, Find, Session_ID) {
//--- Loads new feeds, if any

	visibleDivTags = _$ (whichFeed).getElementsByTagName ('div');
	visiblePostIDs = '';
	for (var i = 0; i < visibleDivTags.length; i++) {
		if (visibleDivTags[i].id.substr (0, 5) == 'post_') {
			visiblePostIDs += ',' + visibleDivTags[i].id.substr (5 + whichFeed.length);
		}
	}
	if (visiblePostIDs.length > 2000) {
		visiblePostIDs = visiblePostIDs.substr (0, (visiblePostIDs.indexOf (',', 2000) != -1 ? visiblePostIDs.indexOf (',', 2000) : visiblePostIDs.length)); // no more than +/- 400 posts/20 pages supported
	}

	_ajaxRequest ('VMM-view-posts.php?View=' + whichFeed + '&NewPosts=1&RepliesFor=' + escape (visiblePostIDs) + '&TimeSeq=' + TimeSeq + (Show ? '&Show=' + escape (Show) : '') + ((Find) && (Find != 'Find...') ? '&Find=' + escape (Find) : '') + '&Session_ID=' + Session_ID + '&Rand=' + Math.random (0, 10000), 'get', prependStream, errorHandler);

}

function _updateUnread (whichFeed, delta) {
//--- Updates the appropriate unread counter

	var original = 0;
	try {
		original = _$ (whichFeed + 'Unread').innerHTML.match (unreadMatch)[0];
		original = parseInt (original);
	} catch (ex) {
	}

	var unreadCount = (original + parseInt (delta));

	if (_$ (whichFeed + 'Unread')) {
		_$ (whichFeed + 'Unread').innerHTML = (unreadCount > 0 ? '(' + (unreadCount < 1000 ? unreadCount : '*') + ')' : '');
	}

}

function _handleMouseOver (layerName) {
//--- Handles mouse over events

	if (lastHover != null) {
		try {
			_$ (lastHover).style.display = 'none';
		} catch (ex) { }
	}
	if (_$ ('link_hover_' + layerName)) {
		_$ ('link_hover_' + layerName).style.display = '';
	}

}

function _handleMouseOut (layerName) {
//--- Handles mouse out events

	lastHover = 'link_hover_' + layerName;

}

function _dragStart (el, e) {
//--- Starts the drag

	e.dataTransfer.effectAllowed = 'move';
	e.dataTransfer.setData ('Text', el.id.replace (/Col/, ''));
	if (e.dataTransfer.setDragImage) {
		e.dataTransfer.setDragImage (dragIcon, 0, 0);
	}

	dragSrc = el;
	dragCol = el.id; // set to source

	if (document.all) {
		dragSrc.style.filter = 'alpha(opacity=40)';
	} else {
		dragSrc.style.opacity = '0.4';
	}

}

function _dragOver (el, e) {
//--- Necessary

	if (e.preventDefault) {
		e.preventDefault ();
	}

	return false;

}

function _dragEnd (el, e) {
//--- Resets things

	if (e.preventDefault) {
		e.preventDefault ();
	}

	if (document.all) {
		el.style.filter = 'alpha(opacity=100)';
	} else {
		el.style.opacity = '1';
	}
	if (dragCol != null) {
		dragCol  = null;
		_$ ('overlay').style.display = 'none';
	}

}

function _dragEnter (el, e) {
//--- Hover over effect

	if (e.preventDefault) {
		e.preventDefault ();
	}

	if (document.all) {
		window.event.dropEffect = 'move';
	} else {
		e.dataTransfer.dropEffect = 'move';
	}
	if (dragCol != el.id) {
		var colPos = _getDynamicObjectPosition (el);
		if (colPos[0] - _$ ('myViews').scrollLeft < 0) {
			_$ ('overlay').style.left = '10px';
			_$ ('overlay').style.width = _getDynamicObjectWidth (el) + colPos[0] - _$ ('myViews').scrollLeft - 14 + 'px';
		} else {
			_$ ('overlay').style.left = colPos[0] - _$ ('myViews').scrollLeft + 'px';
			if (colPos[0] - _$ ('myViews').scrollLeft + _getDynamicObjectWidth (el) > _getBodyWidth ()) {
				_$ ('overlay').style.width = _getBodyWidth () - (colPos[0] - _$ ('myViews').scrollLeft) - 14 + 'px'; // FF bug but too lazy to solve
			} else {
				_$ ('overlay').style.width = _getDynamicObjectWidth (el) - 4 + 'px';
			}
		}
		_$ ('overlay').style.top = colPos[1] + 'px';
		_$ ('overlay').style.height = _getDynamicObjectHeight (el) + 'px';
		_$ ('overlay').style.display = 'block';
		dragCol = el.id;
	}

	return false;

}

function _drop (el, e) {
//--- Handles the drop

	if (e.preventDefault) {
		e.preventDefault ();
	}

	id1 = _$ (e.dataTransfer.getData ('Text') + 'Col').parentNode;
	id2 = _$ (dragCol).parentNode;

	if (id1 == id2) {
		return false;
	}

	var temp = document.createElement('div');
	id1.parentNode.insertBefore(temp, id1);
	id2.parentNode.insertBefore(id1, id2);
	temp.parentNode.insertBefore(id2, temp);
	temp.parentNode.removeChild(temp);

	//--- Save
	var cols = _$ ('myViewsColumns').getElementsByTagName ('li');
	var colIds = [];
	for (var i = 0; i < cols.length; i++) {
		try {
			colId = cols[i].getElementsByTagName ('div')[0].id.replace (/Col/, '');
			if (colId) {
				if (colId != 'clone') {
					colIds.push (colId);
				}
			}
		} catch (e) { }
	}

	_ajaxRequest ('VMM-post.php?Action=moveColumns&colIds=' + escape (colIds.toString ()) + '&Session_ID=' + _$ ('social_comment_post_form').elements['Session_ID'].value + '&Rand=' + Math.random (0, 10000), 'get', function (ajaxObj, url) { }, function (ajaxObj, url) { alert ('An error occured. We could not save your last column move. Please try again.'); });

	return false;

}

function _scrollFunction (targetPoint, scrollBy, scrollDirection) {
//--- Scrolls the body to targetPoint on the page

	var targetPos = _getDynamicObjectPosition (_$ (targetPoint));
	var testX = _$ ('myViews').scrollLeft;
	var gotoX = targetPos[0] - 20; // why 20?

	if (scrollDirection == 'left') {
		_$ ('myViews').scrollLeft -= scrollBy;
		if ((_$ ('myViews').scrollLeft <= gotoX) || (_$ ('myViews').scrollLeft == testX)) {
			clearInterval (scrollInterval);
		}
	} else {
		_$ ('myViews').scrollLeft += scrollBy;
		if ((_$ ('myViews').scrollLeft >= gotoX) || (_$ ('myViews').scrollLeft == testX)) {
			clearInterval (scrollInterval);
		}
	}

}

function _scrollTo (targetPoint) {
//--- Kicks off the scroll process

	if (!_$ (targetPoint)) {
		setTimeout ("_scrollTo ('" + targetPoint + "');", 100); return;
	}

	var targetPos = _getDynamicObjectPosition (_$ (targetPoint));
	var strtX = _$ ('myViews').scrollLeft;
	var stopX = targetPos[0] - 20; // why 20?
	var sDirection = null;

	if (strtX > stopX) {
		var sDirection = 'left';
		var stepsToEnd = (strtX - stopX) / 20;
	} else
	if (strtX < stopX) {
		var sDirection = 'right';
		var stepsToEnd = (stopX - strtX) / 20;
	}

	if (sDirection) {
		scrollInterval = setInterval ("_scrollFunction ('" + targetPoint + "', " + stepsToEnd + ", '" + sDirection + "')", 10);
	}

}

function _loadViewColumn (viewId, name) {
//--- Creates a new column and adds it to the view list with an animation

	var newColumn = _cloneChild (_$ ('cloneColumn'), 'above');
	newColumn.innerHTML = newColumn.innerHTML.replace (/clone/g, viewId);
	newColumn.id = viewId + 'Column';

	myViewsList.push (viewId);

	_sizeColumns ();

	if (document.all) {
		_$ (viewId).attachEvent ('onscroll', function () { var el = event.srcElement; if ((_$ (el.id + 'MoreBar')) && (el.scrollTop + parseInt (el.style.height) > el.scrollHeight - 200)) { _$ (el.id + 'MoreBar').onclick (); } });
	} else {
		_$ (viewId).addEventListener ('scroll', function () { if ((_$ (this.id + 'MoreBar')) && (this.scrollTop + parseInt (this.style.height) > this.scrollHeight - 200)) { _$ (this.id + 'MoreBar').onclick (); } }, false);			
	}

	_editViewColumn (viewId, name);
	_scrollTo (viewId + 'Col');

}

function _editViewColumn (viewId, name) {
//--- Edits an existing column's name and forces a reload

	_$ (viewId + 'Title').innerHTML = name;
	_$ (viewId + 'Title').title = name;

	TogglePopup (viewId + ':reload');

}

function _removeViewColumn (viewId) {
//--- Removes the column

	for (i = 0; i < myViewsList.length; i++) {
		if (myViewsList[i] == viewId) {
			myViewsList.splice (i, 1);
		}
	}

	if ((viewId == 'scheduledPosts') && (lastScheduledPostsInterval != null)) {
		clearInterval (lastScheduledPostsInterval);
	} else
	if ((lastCustomInterval[viewId] != null)) {
		clearInterval (lastCustomInterval[viewId]);
	}

	_fadeOut (viewId + 'Column');

	setTimeout (function () { _$ (viewId + 'Column').parentNode.removeChild (_$ (viewId + 'Column')); _sizeColumns (); }, 500); // must delete

}

function RenderBarChart1 () {
//--- Dynamically renders the graph

	return;

	if (_$ ('reportBar1Div').style.display == 'none') {
		return;
	}

	document.getElementById ('reportBar1Div').innerHTML = 'Your browser doesn\'t support Flash';
	var width = _getDynamicObjectWidth (_$ ('reportBar1Div')) - 20;
	var chart_report = new FusionCharts ('FusionCharts/FCF_MSArea2D.swf?rand=' + Math.random (), 'reportBar1', width, 200, '0', '0');
	chart_report.setTransparent (true);
	chart_report.setDataURL ('FusionCharts/data/test.xml?rand=' + Math.random ());
	chart_report.render ('reportBar1Div');

}

function submitForm (whichForm, defaultText) {
//--- Handles the form post

	if (whichForm.Link.value == 'http://') {
		whichForm.Link.value = '';
	}

	if ((whichForm.Link.value == '') && ((whichForm.Comment.value == defaultText) || (whichForm.Comment.value == ''))) {
		alert ('Please enter a post'); whichForm.Comment.focus (); return false;
	}
	if (whichForm.Services.value == '') {
		alert ('Please select at least one service.'); return false;
	}
	if (whichForm.Comment.value == defaultText) {
		whichForm.Comment.value = '';
	}

	for (i = 0; i < myViewsList.length; i++) {
		if (whichForm.elements[myViewsList[i] + 'Find'].value == 'Find...') {
			whichForm.elements[myViewsList[i] + 'Find'].value = '';
		}
	}

	var textLength = whichForm.Comment.value.length;

	//--- Check for Twitter problems before submit
	var promptOnFacebook = false;
	var promptOnTwitter = false;
	var promptOnLinkedIn = false;
	var promptOnYouTube = false;
	var hasTwitter = false;
	var Services = whichForm.Services.value.substr (1, whichForm.Services.value.length - 1).split (';');
	for (var i = 0; i < Services.length; i++) {
		TService = Services[i].split (':');
		switch (TService[0]) {
			case '1':	promptOnFacebook = (textLength > charsLeft ('fb') ? true : promptOnFacebook); break;
			case '2':	promptOnTwitter = (textLength > charsLeft ('tw') ? true : promptOnTwitter); hasTwitter = true; break;
			case '3':	promptOnLinkedIn = (textLength > charsLeft ('li') ? true : promptOnLinkedIn); break;
			case '4':	promptOnYouTube = false; break;
		}
	}

	if (promptOnFacebook) {
		if (!confirm ('Your post is over 2,500 characters long, and part of the message may be cut off by Facebook. Continue?')) {
			return false;
		}
	}
	if (promptOnLinkedIn) {
		if (!confirm ('Your post is over 600 characters long, and part of the message may be cut off by LinkedIn. Continue?')) {
			return false;
		}
	}
	if (promptOnTwitter) {
		if (!confirm ('Your post is over 140 characters long' + (_$ ('social_comment_post_form').Picture.value ? ' (including the linked picture)' : '') + ' and may be rejected by Twitter. Continue?')) {
			return false;
		}
	}

	if ((whichForm.Link.value) && (whichForm.Comment.value.indexOf (whichForm.Link.value) == -1)) {
		if (confirm ('Your link appears to have been removed from this post:\n\n' + whichForm.Link.value + '\n\nEnsight needs the link to appear in the body of the post or it will not display.\n\nWould you like to re-add the link to the end of your post (click OK)?')) {
			whichForm.Comment.value += (whichForm.Comment.value[whichForm.Comment.value.length - 1] != ' ' ? ' ' : '') + whichForm.Link.value;
			if (whichForm.Comment.value.length > charsLeft ('tw')) {
				if (!confirm ('Your post is over 140 characters long' + (_$ ('social_comment_post_form').Picture.value ? ' (including the linked picture)' : '') + ' and may be rejected by Twitter. Continue?')) {
					return false;
				}				
			}
		}
	}

	return true;

}

function pushCommentToTop (element, responseBody) {
//--- Adds a comment to the selected element at the top

	//--- Check for scripting
	var scriptTest = responseBody.split ('[----------SCRIPT----------]');
	if (scriptTest[1]) {
		responseLoad = scriptTest[1];
		responseBody = scriptTest[0];
		//--- Check for pre-load script
		var preloadTest = responseLoad.split ('[PRELOAD]');
		if (preloadTest[1]) {
			responseLoad = preloadTest[0];
			eval (preloadTest[1]);
		}
	} else {
		responseLoad = null;
	}

	//--- Place content
	if (responseBody != '') {
		span = document.createElement ("span");
		span.innerHTML = responseBody;
		_$ (element).insertBefore (span, _$ (element).firstChild);
	}

	if (responseLoad) {
		eval (responseLoad);
	}

}

function pushCommentToBottom (element, responseBody) {
//--- Adds a comment to the selected element at the bottom

	//--- Check for scripting
	var scriptTest = responseBody.split ('[----------SCRIPT----------]');
	if (scriptTest[1]) {
		responseLoad = scriptTest[1];
		responseBody = scriptTest[0];
		//--- Check for pre-load script
		var preloadTest = responseLoad.split ('[PRELOAD]');
		if (preloadTest[1]) {
			responseLoad = preloadTest[0];
			eval (preloadTest[1]);
		}
	} else {
		responseLoad = null;
	}

	//--- Place content
	if (responseBody != '') {
		span = document.createElement ("span");
		span.innerHTML = responseBody;
		_$ (element).appendChild (span);
	}

	if (responseLoad) {
		eval (responseLoad);
	}

}

function removePost (id, whichFeed) {
//--- Removes a post

	var isFlagged = (_$ ('flag_' + whichFeed + id) ? true : false);
	if (isFlagged) {
		_updateUnread (whichFeed, -1);
	}

	if (_$ ('post_' + whichFeed + id)) {
		_fadeOut ('post_' + whichFeed + id);
	}

	setTimeout (function () {

		if (_$ ('post_' + whichFeed + id)) {
			_$ ('post_' + whichFeed + id).parentNode.removeChild (_$ ('post_' + whichFeed + id));
		}

		//--- Check how many posts are inside this column
		if (document.getElementsByClassName) {
			postsCount = _$ (whichFeed).getElementsByClassName ('postBox').length;
		} else {
			posts = _$ (whichFeed).getElementByTagName ('div');
			postsCount = 0;
			for (var i = 0; i < posts.length; i++) {
				if (posts[i].className == 'postBox') {
					postsCount++;
				}
			}
		}
		if (postsCount == 0) {
			TogglePopup (whichFeed + ':reload');
		}

	}, 500); // must delete

}

function updateReplyCount (id, delta) {
//--- Updates the reply count

	var original = 0;
	try {
		original = _$ (id).innerHTML.match (replyCountMatch)[0];
		original = parseInt (original);
	} catch (ex) {
	}

	var replyCount = (original + parseInt (delta));

	if (_$ (id)) {
		_$ (id).innerHTML = (replyCount > 0 ? replyCount + '&nbsp;' : '0&nbsp;');
	}

}

function showStream (ajaxObj, url) {
//--- Displays the output

	var index1 = url.indexOf ('View=');
	var index2 = url.indexOf ('&', index1);
	var view = url.substr (index1 + 5, index2 - (index1 + 5));
	var layer = _$ (view);

	var responseBody = ajaxObj.responseText;

	//--- Check for scripting
	var scriptTest = responseBody.split ('[----------SCRIPT----------]');
	if (scriptTest[1]) {
		responseLoad = scriptTest[1];
		responseBody = scriptTest[0];
		//--- Check for pre-load script
		var preloadTest = responseLoad.split ('[PRELOAD]');
		if (preloadTest[1]) {
			responseLoad = preloadTest[0];
			eval (preloadTest[1]);
		}
	} else {
		responseLoad = null;
	}

	//--- Place content
	if (responseBody != '') {
		layer.innerHTML = responseBody;
	} else {
		layer.innerHTML = 'An error occured. Please refresh the page and try again.';
	}

	if (responseLoad) {
		eval (responseLoad);
	}

}

function appendStream (ajaxObj, url) {
//--- Appends the output to the current display

	var index1 = url.indexOf ('View=');
	var index2 = url.indexOf ('&', index1);
	var view = url.substr (index1 + 5, index2 - (index1 + 5));
	var layer = _$ (view);
	var moreBar = _$ (view + 'MoreBar');

	if (moreBar) {
		moreBar.parentNode.removeChild (moreBar);
	}

	var responseBody = ajaxObj.responseText;

	//--- Check for scripting
	var scriptTest = responseBody.split ('[----------SCRIPT----------]');
	if (scriptTest[1]) {
		responseLoad = scriptTest[1];
		responseBody = scriptTest[0];
		//--- Check for pre-load script
		var preloadTest = responseLoad.split ('[PRELOAD]');
		if (preloadTest[1]) {
			responseLoad = preloadTest[0];
			eval (preloadTest[1]);
		}
	} else {
		responseLoad = null;
	}

	//--- Place content
	if (responseBody != '') {
		span = document.createElement ("span");
		span.innerHTML = responseBody;
		layer.appendChild (span);
	}

	if (responseLoad) {
		eval (responseLoad);
	}

}

function prependStream (ajaxObj, url) {
//--- Prepends the output to the current display

	var index1 = url.indexOf ('View=');
	var index2 = url.indexOf ('&', index1);
	var view = url.substr (index1 + 5, index2 - (index1 + 5));
	var layer = _$ (view);
	var moreBar = _$ (view + 'MoreBar');

	var responseBody = ajaxObj.responseText;

	//--- Check for scripting
	var scriptTest = responseBody.split ('[----------SCRIPT----------]');
	if (scriptTest[1]) {
		responseLoad = scriptTest[1];
		responseBody = scriptTest[0];
		//--- Check for pre-load script
		var preloadTest = responseLoad.split ('[PRELOAD]');
		if (preloadTest[1]) {
			responseLoad = preloadTest[0];
			eval (preloadTest[1]);
		}
	} else {
		responseLoad = null;
	}

	//--- Place content
	if (responseBody != '') {
		span = document.createElement ("span");
		span.innerHTML = responseBody;
		layer.insertBefore (span, layer.firstChild);
	}

	if (responseLoad) {
		eval (responseLoad);
	}

}

function onImageError (src) {
//--- Handles image errors

	src.src = 'images/image-not-available.jpg';
	src.border = '1px solid #999999';
	src.onerror = '';
	return true;

}

//--- Popup menu functions

function ShowYourPostsMenu (e) {
//--- Displays the context-sensitive menu

	ButtonPos = _getDynamicObjectPosition (_$ ('yourPostsMenu'));
	iFramePos = _getDynamicObjectPosition (window.parent.document.getElementById ('Main'));
	pPopup.fixedX = iFramePos[0] + ButtonPos[0] - parseInt (_$ ('myViews').scrollLeft) + 1;
	pPopup.fixedY = iFramePos[1] + ButtonPos[1] - _getScrollTop () + _getDynamicObjectHeight (_$ ('yourPostsMenu')); // position around the button
	pPopup.show (e);

}

function ShowScheduledPostsMenu (e) {
//--- Displays the context-sensitive menu

	ButtonPos = _getDynamicObjectPosition (_$ ('scheduledPostsMenu'));
	iFramePos = _getDynamicObjectPosition (window.parent.document.getElementById ('Main'));
	sPopup.fixedX = iFramePos[0] + ButtonPos[0] - parseInt (_$ ('myViews').scrollLeft) + 3 - 250 + _getDynamicObjectWidth (_$ ('scheduledPostsMenu'));
	sPopup.fixedY = iFramePos[1] + ButtonPos[1] - _getScrollTop () + _getDynamicObjectHeight (_$ ('scheduledPostsMenu')); // position around the button
	sPopup.show (e);

}

function ShowWhatOthersAreSayingMenu (e) {
//--- Displays the context-sensitive menu

	ButtonPos = _getDynamicObjectPosition (_$ ('whatOthersAreSayingMenu'));
	iFramePos = _getDynamicObjectPosition (window.parent.document.getElementById ('Main'));
	oPopup.fixedX = iFramePos[0] + ButtonPos[0] - parseInt (_$ ('myViews').scrollLeft) + 3 - 250 + _getDynamicObjectWidth (_$ ('whatOthersAreSayingMenu'));
	oPopup.fixedY = iFramePos[1] + ButtonPos[1] - _getScrollTop () + _getDynamicObjectHeight (_$ ('whatOthersAreSayingMenu')); // position around the button
	oPopup.show (e);

}

function SelectService (whichService, id) {
//--- Selects or deselects a service

	if (whichService.className.indexOf (' selected') != -1) {
		whichService.className = whichService.className.replace (' selected', ' deselected');
		document.forms['social_comment_post_form'].elements['Services'].value = document.forms['social_comment_post_form'].elements['Services'].value.replace (';' + id, '');
	} else {
		whichService.className = whichService.className.replace (' deselected', ' selected');
		document.forms['social_comment_post_form'].elements['Services'].value = document.forms['social_comment_post_form'].elements['Services'].value + ';' + id;
	}

	if (_$ ('social_comment_link_form').style.display != 'none') {
		showLinkBox ();
	}
	if (_$ ('social_comment_picture_form').style.display != 'none') {
		showPictureBox ();
	}

}

function GetWindowProperty (property) {
//--- Returns a browser compatible window property

	switch (property) {
		case 'W':	return (window.innerWidth  ? window.innerWidth  + 3 : document.body.offsetWidth); break;
		case 'H':	return (window.innerHeight ? window.innerHeight + 3 : document.body.offsetHeight); break;
	}

}

function LoadLayer (id) {
//--- Creates a div layer to fit the available window space

	_$ (id).style.height = (_getBodyHeight () - parseInt (_getDynamicObjectHeight (_$ ('postBox'))) - (document.all ? 222 : (navigator.userAgent.toLowerCase ().indexOf ('firefox') != -1 ? 230 : 215))) + "px";
	_$ (id).innerHTML = "<img src=\"images/loader-circle.gif\" width=\"54\" height=\"55\" border=\"0\" style=\"margin-top: 6px;\" />";

}

function ResizeFrame (id) {
//--- Handles an onresize event

	document.getElementById (id).style.height = (_getBodyHeight () - parseInt (_getDynamicObjectHeight (_$ ('postBox'))) - (document.all ? 222 : (navigator.userAgent.toLowerCase ().indexOf ('firefox') != -1 ? 230 : 215)) - (_$ (id + 'Search').style.display != 'none' ? parseInt (_getDynamicObjectHeight (_$ (id + 'Search'))) : 0) - (_$ (id + 'Filter').style.display != 'none' ? parseInt (_getDynamicObjectHeight (_$ (id + 'Filter')) + 10) : 0)) + 'px';

}

function ResizeSocialProfileStream () {
//--- Resizes the conversation history list as tags get created or edited

	var minHeight = (document.all ? 79 : 79);

	_$ ('conversationHistory').style.height = (290 - (_getDynamicObjectHeight (_$ ('topSection')) >= minHeight ? _getDynamicObjectHeight (_$ ('topSection')) - minHeight : 0)) + 'px';

}

function showTagsEdit () {
//--- Shifts from viewing to editing mode

	_$ ('editTags').style.display = '';
	_$ ('viewTags').style.display = 'none';
	_$ ('stats').style.display = 'none';

	_$ ('Tags_tokens_input').focus ();

	ResizeSocialProfileStream ();

}

function showTagsView () {
//--- Shifts from editing to viewing mode

	_$ ('editTags').style.display = 'none';
	_$ ('viewTags').style.display = '';
	_$ ('stats').style.display = '';

	ResizeSocialProfileStream ();

}

//--- General

function errorHandler (ajaxObj, url) {
//--- Handles the error

	//--- Reset things (we need to determine what it was in response to)
	var index1 = url.indexOf ('View=');
	var index2 = url.indexOf ('&', index1);
	var view = url.substr (index1 + 5, index2 - (index1 + 5));

	if ((_$ (view + 'MoreBar')) && (_$ (view + 'MoreBar').innerHTML != 'More...')) {
		 _$ (view + 'MoreBar').innerHTML = 'More...';
	}

}

function retryRequest (ajaxObj, url) {
//--- Replacement for errorHandler that retries the original request every 30 seconds

	setTimeout ("_ajaxRequest ('" + url + "', 'get', function (response, url) { eval (response.responseText); }, retryRequest);", 30000);

}

function showToolTip (e, val) {
//--- Displays tool tip names over the avatars

	_$ ('tooltip').innerHTML = val;
	_showAtDynamicPosition (e, document.getElementById ('tooltip'), 'aboveleft', 5, 5);

}

function hideToolTip (e) {
//--- Hides tool tip

	_$ ('tooltip').style.display = 'none';

}

function assignToolTips () {
//--- Assign events

	var el = _$ ('postBox').querySelectorAll ('.smallFollowers, .newFollowers');
	for (i = 0; i < el.length; i++) {
		el[i].onmouseover = function (e) { showToolTip (e, this.getAttribute ('val')); };
		el[i].onmouseout  = function (e) { hideToolTip (e); };
	}

}
