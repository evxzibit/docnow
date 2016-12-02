//--- Ensight Source Code (ESC)
//--- First Created on Friday, January 9 2009 by John Ginsberg (on a train)
//--- For Ensight 4

//--- Module Name: isuggest.js
//--- Contains: None

//--- Modified by:
//--- Modified by:

//--- Description:
//--- Text suggestions engine

//--- Notice:
//--- This code is copyright (C) 2008, ENVENT Holdings (Pty) Ltd. All rights
//--- are reserved. Using this code in any shape or form requires permission
//--- from ENVENT Holdings (Pty) Ltd - http://www.ensight.co.uk

//----------------------------------------------------------------

//------------------------------------------------------------------------------------
//--- Initialise the cross-browser suggestion object
//------------------------------------------------------------------------------------

/**
 * Object suggestionList
 * @f obj		:the field object
 * @u string	:the callback url that supplies XML results
 * @l int		:the lookup type (0 = word, 1 = phrase)
 */
function suggestionList (f, u, l) {

	function findFieldPos (fieldObj) {
	//--- Find the current position of an object on the page

		var curL = 0;
		var curT = 0;

		if (fieldObj.offsetParent) {
			curL = fieldObj.offsetLeft
			curT = fieldObj.offsetTop
			while (fieldObj = fieldObj.offsetParent) {
				curL += fieldObj.offsetLeft
				curT += fieldObj.offsetTop
			}
		}

		return [curL, curT]; // return as array

	}

	function requestSuggestions (url, handler) {
	//--- Send an HTTP request

		if (window.XMLHttpRequest) {

			this.suggestReq = new XMLHttpRequest ();
			this.suggestReq.onreadystatechange = handler;
			this.suggestReq.open ("GET", url, true);
			this.suggestReq.send (null);

	    } else if (window.ActiveXObject) {

			this.suggestReq = new ActiveXObject ("Microsoft.XMLHTTP");
			if (this.suggestReq) {
				this.suggestReq.onreadystatechange = handler;
				this.suggestReq.open ("GET", url, true);
				this.suggestReq.send ();
	        }

	    }

	}

	function replaceWord (whichWord) {
	//--- Connects the selected word to the partial word

		which = top.lastSuggestionList;
		whichField = which.formField;
		whichField.focus ();

		//--- Get the caret position
		if (document.selection) {
			range = document.selection.createRange ();
			range.moveStart ('character', -whichField.value.length);
			CaretPos = range.text.length;
		} else
		if ((whichField.selectionStart) || (whichField.selectionStart == '0')) {
			CaretPos = whichField.selectionStart;
		}

		lastComma = whichField.value.substr (0, CaretPos).lastIndexOf (',');
		if (lastComma == -1) {
			lastComma = 0;
		}

		inclSpaces = '';
		findSpaces = lastComma;
		while ((findSpaces <= whichField.value.length) && ((whichField.value.substr (findSpaces, 1) == ',') || (whichField.value.substr (findSpaces, 1) == ' '))) {
			inclSpaces += whichField.value.substr (findSpaces, 1);
			findSpaces++;
		}
		whichField.value = whichField.value.substr (0, lastComma) + inclSpaces + whichWord + whichField.value.substr (CaretPos, whichField.value.length - CaretPos);

		//--- Reset caret
		if (whichField.setSelectionRange) {
			whichField.focus ();
			whichField.setSelectionRange (CaretPos + whichWord.length - (CaretPos - lastComma) + inclSpaces.length, CaretPos + whichWord.length - (CaretPos - lastComma) + inclSpaces.length);
		} else
		if (whichField.createTextRange) {
			range = whichField.createTextRange ();
			range.collapse (true);
			range.moveEnd ('character', CaretPos + whichWord.length - (CaretPos - lastComma) + inclSpaces.length);
			range.moveStart ('character', CaretPos + whichWord.length - (CaretPos - lastComma) + inclSpaces.length);
			range.select ();
		}

		which.hideSuggestions ();

	}

	function replacePhrase (whichPhrase) {
	//--- Connects the selected word to the partial word

		which = top.lastSuggestionList;
		whichField = which.formField;
		whichField.focus ();
		whichField.value = whichPhrase;

		which.hideSuggestions ();

	}

	function getSuggestionsHeight (lines) {
	//--- Calculates the height for the popup menu

		Height = 0;
		HExtra = 0;

		for (i = 0; i < lines; i++) {
			Height += (document.all ? 23 : 24); HExtra += (document.all ? 0 : 2);
		}

		return parseInt (Height) + HExtra;

	}

	function sanitizeSuggestion (inp) {
	//--- Adds slashes to variable and returns it

		outp = inp;
		outp = outp.replace (/\\/g, '/');
		outp = outp.replace (/\'/g, '\\\'');
		outp = outp.replace (/\"/g, '&quot;');
		return outp;

	}

	function showSuggestions () {
	//--- Loads the most recent suggestions returned from the server

		which = top.lastSuggestionList;

		if (which.suggestReq.readyState == 4) {

			switch (which.suggestReq.status) {
				case 200:	//--- Display response text
							var tagLinks = '';
							var hasError = which.suggestReq.responseXML.getElementsByTagName ("error");
							if (hasError.length) {
								which.suggestionLayer.innerHTML = "Could not download suggestion list."; break;
							} else {
								var tagList    = which.suggestReq.responseXML.getElementsByTagName ("tags")[0].getElementsByTagName ("tag");
								var whichField = which.suggestReq.responseXML.getElementsByTagName ("tags")[0].getAttribute ("for");
								var whichWord  = which.suggestReq.responseXML.getElementsByTagName ("tags")[0].getAttribute ("word");
							}
							if (tagList.length) {
								fieldWidth = document.getElementById (whichField).offsetWidth - 2;
								tagLinks  = "<div id=\"tagShade\" style=\"position: absolute; left: 6px; top: 6px; width: " + fieldWidth.toString () + "px; height: " + which.getSuggestionsHeight (tagList.length + 1) + "px; background-color: #8a89a6; z-index: 2; filter: alpha(opacity=20); -moz-opacity: 0.2; opacity: 0.2\"></div>";
								tagLinks += "<div id=\"suggestLayer\" style=\"position: absolute; left: 0px; top: 0px; width: " + fieldWidth.toString () + "px; height: " + which.getSuggestionsHeight (tagList.length + 1) + "px; background-color: #ffffff; z-index: 3; border: 1px solid #000000\">";
								tagLinks += "<div style=\"background-color: #cecece; padding: 5px; height: 16px; width: " + (document.all ? fieldWidth : (fieldWidth - 10)) + "px\"><b>Try the following...</b></div>";
								for (i = 0; i < tagList.length; i++) {
									tagLinks += "<div class=\"suggestStandard\" style=\"padding: 5px; height: 16px; cursor: pointer\" valign=\"top\" onmouseover=\"if (top.lastSuggestionList.lastSelected != null) { top.lastSuggestionList.lastSelected.style.backgroundColor = '#ffffff'; top.lastSuggestionList.lastSelected = null; } top.lastSuggestionList.lastSelected = this; this.style.backgroundColor = '#ccffcc';\" onmouseout=\"if (top.lastSuggestionList.lastSelected != null) { top.lastSuggestionList.lastSelected.style.backgroundColor = '#ffffff'; top.lastSuggestionList.lastSelected = null; } this.style.backgroundColor = '#ffffff'\" onclick=\"if (top.lastSuggestionList.lookupType == 0) { top.lastSuggestionList.replaceWord ('" + sanitizeSuggestion (tagList[i].firstChild.nodeValue.toString ()) + "'); } else { top.lastSuggestionList.replacePhrase ('" + sanitizeSuggestion (tagList[i].firstChild.nodeValue.toString ()) + "'); } return false\"><div style=\"width: " + (document.all ? (fieldWidth - 10) : (fieldWidth - 20)) + "px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis\" title=\"" + sanitizeSuggestion (tagList[i].firstChild.nodeValue.toString ()) + "\">" + sanitizeSuggestion (tagList[i].firstChild.nodeValue.toString ()).replace ("\\\'", "'").replace (new RegExp ("(" + whichWord + ")", "ig"), "<b>$1</b>") + "</div></div>";
								}
								tagLinks += "</div>";
							}
							if (tagLinks) {
								textAreaObjPos = which.findFieldPos (document.getElementById (whichField));
								which.suggestionLayer.innerHTML = tagLinks;
								which.suggestionLayer.style.top = (textAreaObjPos[1] + document.getElementById (whichField).offsetHeight + 0) + 'px';
								which.suggestionLayer.style.left = (textAreaObjPos[0] + 0) + 'px';
								which.suggestionLayer.style.visibility = 'visible';
								which.isOpen = true;
								//--- Preselect first option
								frstMenuItem = (document.getElementById ('suggestLayer').firstChild ? document.getElementById ('suggestLayer').firstChild : null);
								while ((frstMenuItem) && ((frstMenuItem.nodeName != 'DIV') || (frstMenuItem.className != 'suggestStandard'))) {
									frstMenuItem = (frstMenuItem.nextSibling ? frstMenuItem.nextSibling : null);
								}
								frstMenuItem.onmouseover (); which.lastSelected = frstMenuItem;
								//--- Stop windows from closing on ESCape
								top.modalWindows.disableKeyDown = true;
							} else {
								which.hideSuggestions ();
							}
							break;
				default:	which.suggestionLayer.innerHTML = "Could not download tag list."; break;
							break;
			}

		}

	}

	function hideSuggestions () {
	//--- Hides the suggestions popup

		this.suggestionLayer.style.visibility = 'hidden';
		this.lastSelected = null;
		this.isOpen = false;

	}

	function keyDown (e) {
	//--- Handles key depressions

		var which = top.lastSuggestionList;
		var key = (e.which ? e.which : e.keyCode);
		var ignoreHandler = false;

		if (which.suggestionLayer.style.visibility == 'hidden') {
			return;
		}
		if ((e.shiftKey) || (e.altKey) || (e.ctrlKey)) {
			return;
		}

		switch (key) {
			case  8:	//which.hideSuggestions ();
						//ignoreHandler = true;
						break;
			case 13:	currMenuItem = (which.lastSelected ? which.lastSelected : null);
						if (currMenuItem) {
							currMenuItem.onclick ();
						}
						top.modalWindows.disableKeyDown = false;
						ignoreHandler = true;
						break;
			case 27:	which.hideSuggestions ();
						which.lastWord = '';
						//ignoreHandler = true;
						break;
			case 38:	//--- Go up
						frstMenuItem = (document.getElementById ('suggestLayer').firstChild ? document.getElementById ('suggestLayer').firstChild : null);
						while ((frstMenuItem) && ((frstMenuItem.nodeName != 'DIV') || (frstMenuItem.className != 'suggestStandard'))) {
							frstMenuItem = (frstMenuItem.nextSibling ? frstMenuItem.nextSibling : null);
						}
						lastMenuItem = (document.getElementById ('suggestLayer').lastChild ? document.getElementById ('suggestLayer').lastChild : null);
						while ((lastMenuItem) && ((lastMenuItem.nodeName != 'DIV') || (lastMenuItem.className != 'suggestStandard'))) {
							lastMenuItem = (lastMenuItem.previousSibling ? lastMenuItem.previousSibling : null);
						}
						currMenuItem = (which.lastSelected ? which.lastSelected : frstMenuItem);
						while ((currMenuItem) && ((currMenuItem.nodeName != 'DIV') || (currMenuItem.className != 'suggestStandard'))) {
							currMenuItem = (currMenuItem.nextSibling ? currMenuItem.nextSibling : null);
						}
						prevMenuItem = (currMenuItem.previousSibling ? currMenuItem.previousSibling : null);
						while ((prevMenuItem) && ((prevMenuItem.nodeName != 'DIV') || (prevMenuItem.className != 'suggestStandard'))) {
							prevMenuItem = (prevMenuItem.previousSibling ? prevMenuItem.previousSibling : null);
						}
						if (currMenuItem == prevMenuItem) {
							ignoreHandler = true; return;
						}
						currMenuItem.onmouseout ();
						if (prevMenuItem != null) {
							prevMenuItem.onmouseover (); which.lastSelected = prevMenuItem;
						} else {
							lastMenuItem.onmouseover (); which.lastSelected = lastMenuItem;
						}
						ignoreHandler = true;
						break;
			case 40:	//--- Go down
						frstMenuItem = (document.getElementById ('suggestLayer').firstChild ? document.getElementById ('suggestLayer').firstChild : null);
						while ((frstMenuItem) && ((frstMenuItem.nodeName != 'DIV') || (frstMenuItem.className != 'suggestStandard'))) {
							frstMenuItem = (frstMenuItem.nextSibling ? frstMenuItem.nextSibling : null);
						}
						lastMenuItem = (document.getElementById ('suggestLayer').lastChild ? document.getElementById ('suggestLayer').lastChild : null);
						while ((lastMenuItem) && ((lastMenuItem.nodeName != 'DIV') || (lastMenuItem.className != 'suggestStandard'))) {
							lastMenuItem = (lastMenuItem.previousSibling ? lastMenuItem.previousSibling : null);
						}
						currMenuItem = (which.lastSelected ? which.lastSelected : lastMenuItem);
						while ((currMenuItem) && ((currMenuItem.nodeName != 'DIV') || (currMenuItem.className != 'suggestStandard'))) {
							currMenuItem = (currMenuItem.previousSibling ? currMenuItem.previousSibling : null);
						}
						nextMenuItem = (currMenuItem.nextSibling ? currMenuItem.nextSibling : null);
						while ((nextMenuItem) && ((nextMenuItem.nodeName != 'DIV') || (nextMenuItem.className != 'suggestStandard'))) {
							nextMenuItem = (nextMenuItem.nextSibling ? nextMenuItem.nextSibling : null);
						}
						if (currMenuItem == nextMenuItem) {
							ignoreHandler = true; return;
						}
						currMenuItem.onmouseout ();
						if (nextMenuItem != null) {
							nextMenuItem.onmouseover (); which.lastSelected = nextMenuItem;
						} else {
							frstMenuItem.onmouseover (); which.lastSelected = frstMenuItem;
						}
						ignoreHandler = true;
						break;
			default:	which.lastSelected = null;
						break;
		}

		if (ignoreHandler) {
			if (document.all) {
				e.cancelBubble = true;
				e.returnValue = false;
			} else {
				e.preventDefault ();
			}
		}

	}

	function lookupWord (e) {
	//--- Function to lookup words

		var whichField = this.formField;
		var key = (e.which ? e.which : e.keyCode);

		if (key == 27) {
			this.hideSuggestions ();
			return false; //--- Escape
		}

		//--- Ignore special characters
		if ((this.suggestionLayer.style.visibility == 'visible') && ((key == 38) || (key == 40))) { // (key == 35) || (key == 36) || 
			return false;
		}	

		//--- Ignore commas and semi-colons
		if ((key == 13) || (key == 186) || (key == 188)) {
			return false;
		}

		if (document.selection) {
			range = document.selection.createRange ();
			range.moveStart ('character', -whichField.value.length);
			CaretPos = range.text.length;
		} else
		if ((whichField.selectionStart) || (whichField.selectionStart == '0')) {
			CaretPos = whichField.selectionStart;
		}

		lastComma = whichField.value.substr (0, CaretPos).lastIndexOf (',');
		if (lastComma == -1) {
			lastComma = 0;
		}

		newWord = whichField.value.substr ((lastComma ? lastComma + 1 : 0), CaretPos - (lastComma ? lastComma + 1 : 0)).replace (/^\s+/, '');
		if ((newWord != '') && (newWord.length > 2) && (newWord != this.lastWord)) {
			this.requestSuggestions (this.callBack.replace (/{field}/g, escape (whichField.id)).replace (/{word}/g, escape (newWord)), this.showSuggestions);
			this.lastWord = newWord;
		} else {
			return false;
		}

	}

	function lookupPhrase (e) {
	//--- Function to lookup words

		var whichField = this.formField;
		var key = (e.which ? e.which : e.keyCode);

		if (key == 27) {
			this.hideSuggestions ();
			return false; //--- Escape
		}

		//--- Ignore special characters
		if ((this.suggestionLayer.visibility == 'visible') && ((key == 38) || (key == 40))) { // (key == 35) || (key == 36) || 
			return false;
		}	

		//--- Ignore commas and semi-colons
		if ((key == 13) || (key == 186) || (key == 188)) {
			return false;
		}

		newWord = whichField.value;
		if ((newWord != '') && (newWord.length > 2) && (newWord != this.lastWord)) {
			this.requestSuggestions (this.callBack.replace (/{field}/g, escape (whichField.id)).replace (/{word}/g, escape (newWord)), this.showSuggestions);
			this.lastWord = newWord;
		} else {
			return false;
		}

	}

	function setActive () {
	//--- Sets the selected object to active

		top.lastSuggestionList = this;

	}

	//--- Safety
	if (!document.getElementById ('tagSuggestions')) {
		var tLayer = document.createElement ("DIV");
		tLayer.id = 'tagSuggestions';
		tLayer.style.visibility = 'hidden';
		tLayer.style.position = 'absolute';
		tLayer.style.zIndex = '1';
		document.body.appendChild (tLayer);
	}

	//--- Methods
	this.findFieldPos = findFieldPos;
	this.requestSuggestions = requestSuggestions;
	this.replaceWord = replaceWord;
	this.replacePhrase = replacePhrase;
	this.getSuggestionsHeight = getSuggestionsHeight;
	this.sanitizeSuggestion = sanitizeSuggestion;
	this.showSuggestions = showSuggestions;
	this.hideSuggestions = hideSuggestions;
	this.keyDown = keyDown;
	this.lookupWord = lookupWord;
	this.lookupPhrase = lookupPhrase;
	this.setActive = setActive;

	//--- Properties
	this.formField = f;
	this.callBack = u;
	this.lookupType = l;
	this.lastSelected = null;
	this.lastWord = '';
	this.suggestionLayer = document.getElementById ('tagSuggestions');
	this.isOpen = false;

	//--- Globals
	top.lastSuggestionList = this;

}
