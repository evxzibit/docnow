//--- Ensight Source Code (ESC)
//--- First Created on Friday, August 1 2003 by John Ginsberg (Happy B-Day)
//--- For Ensight 4

//--- Module Name: imenus.js
//--- Contains: None

//--- Modified by:
//--- Modified by:

//--- Description:
//--- Menu rendering and support engine

//--- Notice:
//--- This code is copyright (C) 2008, ENVENT Holdings (Pty) Ltd. All rights
//--- are reserved. Using this code in any shape or form requires permission
//--- from ENVENT Holdings (Pty) Ltd - http://www.ensight.co.uk

//----------------------------------------------------------------

//--- External functions

function createPopupOption (icon, text, command, state, hotkey) {
//--- Creates a menu option

	tButton = Array ();
	tButton['icon'] = icon;
	tButton['text'] = text;
	tButton['command'] = command;
	tButton['state'] = state;
	tButton['hotkey'] = hotkey;

	if (icon) {
		ImageCount = ImageCache.length;
		ImageCache[ImageCount] = new Image ();
		ImageCache[ImageCount].src = icon;
	}

	return tButton;

}

function setContextHandler (whichDocument, whichFunction) {
//--- Sets the context handler on the supplied document

	if (document.all) {
		whichDocument.body.attachEvent ("oncontextmenu", whichFunction); 
	} else {
		whichDocument.addEventListener ("contextmenu",   whichFunction, false);
	}

}

//------------------------------------------------------------------------------------
//--- Initialise the cross-browser popup menu object
//------------------------------------------------------------------------------------

/**
 * Object popupMenu
 * @w int		:the width (in pixels)
 * @m array		:an array containing the options, as created by CreatePopupOption
 * @f string	:the name of the frame containing the menu
 * @c fun		:a callback function to handle clicks from the menu
 */
function popupMenu (w, m, f, c) {

	function displayPopup () {
	//--- Displays the editor popup menu

		var HTML = "";

		if (document.all) {
			HTML += "<div style=\"background-color: #8a89a6; width: " + (this.W - 4) + "px; height: " + (this.H + 0) + "px; position: absolute; top: 4px; left: 4px; z-index: 1; filter: alpha(opacity=20); -moz-opacity: 0.2; opacity: 0.2\"></div>";
		}
		HTML += "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" style=\"" + MenuDefault + "\" width=\"" + (this.W - 4) + "\" height=\"" + (this.H + 0) + "\">\n";
		HTML += "<tr valign=\"top\">\n";
		HTML += "<td style=\"" + MenuLeftBar + "\" align=\"center\">&nbsp;</td>\n";
		HTML += "<td>&nbsp;</td>\n";
		HTML += "</tr>\n";
		HTML += "</table>\n";

		//--- Overlay menu on top of background
		HTML += "<div id=\"menuLayer\" style=\"position: absolute; left: 0px; top: 0px; z-index: 3\">";

		//--- Buttons
		for (i = 0; i < this.menuOptions.length; i++) {
			switch (this.menuOptions[i]['state']) {
				case 'break':		HTML += "<div style=\"border-top: 1px solid #ccc; height: 1px; width: " + (this.W - 42) + "px; margin-left: 34px;" + (document.all ? " margin-top: 7px; margin-bottom: 8px;" : " margin-top: 5px; margin-bottom: 5px;") + "\"></div>\n"; break;
				case 'disabled':	HTML += "<div class=\"menuDisabled\" style=\"padding-left: 1px; padding-top: 1px; padding-bottom: 1px; margin-left: 2px; margin-top: 2px; cursor: pointer\" onmouseover=\"\" onmouseout=\"\" onclick=\"\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"" + (this.W - 10) + "\" style=\"" + MenuBtLItem + "\"><tr><td style=\"width: 28px; padding-left: 4px\">" + (this.menuOptions[i]['icon'] ? "<img src=\"" + this.menuOptions[i]['icon'] + "\" width=\"16\" height=\"16\" vspace=\"2\" />" : "&nbsp;") + "</td><td style=\"padding-top: 5px; padding-bottom: 5px\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td><span style=\"font-family: Microsoft Sans Serif, Arial, Verdana; font-size: 11px; color: silver\">" + this.menuOptions[i]['text'] + "</span></td><td style=\"width: 20px\"><span style=\"font-family: Microsoft Sans Serif, Arial, Verdana; font-size: 11px\">" + (this.menuOptions[i]['hotkey'] ? this.menuOptions[i]['hotkey'] + '&nbsp;' : '') + "</span></td></tr></table></td></tr></table></div>\n"; break;
				case 'hidden':		break;
				default:			HTML += "<div class=\"menuStandard\" style=\"padding-left: 1px; padding-top: 1px; padding-bottom: 1px; margin-left: 2px; margin-top: 2px; cursor: pointer\" onmouseover=\"window.lastPopupMenu.togglePopupButton (this, 'over', '" + this.menuOptions[i]['command'] + "')\" onmouseout=\"window.lastPopupMenu.togglePopupButton (this, 'out', '" + this.menuOptions[i]['command'] + "')\" onclick=\"window.lastPopupMenu.togglePopupButton (this, 'down', '" + this.menuOptions[i]['command'] + "')\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"" + (this.W - 10) + "\" style=\"" + MenuBtLItem + "\"><tr><td style=\"width: 28px; padding-left: 4px\">" + (this.menuOptions[i]['icon'] ? "<img src=\"" + this.menuOptions[i]['icon'] + "\" width=\"16\" height=\"16\" vspace=\"2\" />" : "&nbsp;") + "</td><td style=\"padding-top: 5px; padding-bottom: 5px\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td><span style=\"font-family: Microsoft Sans Serif, Arial, Verdana; font-size: 11px\">" + this.menuOptions[i]['text'] + "</span></td><td style=\"width: 20px\"><span style=\"font-family: Verdana, Arial, Verdana; font-size: 11px\">" + (this.menuOptions[i]['hotkey'] ? this.menuOptions[i]['hotkey'] + '&nbsp;' : '') + "</span></td></tr></table></td></tr></table></div>\n"; break;
			}
		}

		HTML += "</div>";

		return HTML;

	}

	function getPopupHeight () {
	//--- Calculates the height for the popup menu

		Height = 0;
		HExtra = 0;

		//--- Get height of average menu item (based on font)
		tempMenu = document.createElement ("DIV");
		tempMenu.innerHTML = "<div class=\"menuStandard\" style=\"padding-left: 1px; padding-top: 1px; padding-bottom: 1px; margin-left: 2px; margin-top: 2px; cursor: pointer\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100\" style=\"" + MenuBtLItem + "\"><tr><td style=\"width: 28px; padding-left: 4px\">&nbsp;</td><td style=\"padding-top: 5px; padding-bottom: 5px\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td><span style=\"font-family: Microsoft Sans Serif, Arial, Verdana; font-size: 11px; color: silver\">TEST</span></td><td style=\"width: 40px\"><span style=\"font-family: Microsoft Sans Serif, Arial, Verdana; font-size: 11px\">&nbsp;</span></td></tr></table></td></tr></table></div>";
		document.body.appendChild (tempMenu); tempHeight = tempMenu.offsetHeight + ((navigator.userAgent.indexOf ('Chrome') != -1) || (navigator.userAgent.indexOf ('Firefox') != -1) ? 2.5 : 2.5);
		document.body.removeChild (tempMenu);

		for (i = 0; i < this.menuOptions.length; i++) {
			switch (this.menuOptions[i]['state']) {
				case 'break':		Height += (document.all ? 14 : 9); HExtra += (document.all ? 1 : 0); break;
				case 'disabled':	Height += tempHeight; break;
				case 'hidden':		break;
				default:			Height += tempHeight; break;
			}
		}

		return parseInt (Height) + HExtra + 2;

	}

	function getPopupDimensions (e) {
	//--- Get dimensions for the popup window

		//--- Allow positioning to go one level down within the primary frame
		if (this.menuFrame.parent.frameElement) {
			FrameTop = this.findFramePos (this.menuFrame.parent.frameElement);
		} else {
			FrameTop = [0, 0];
		}
		FramePos = this.findFramePos (this.menuFrame.frameElement);
		yVal = FrameTop[1] + FramePos[1] + (e.pageY ? e.pageY + 5 - (this.menuFrame.document.body.scrollTop  + (this.menuFrame.parent.frameElement ? this.menuFrame.parent.document.body.scrollTop  : 0)) : e.clientY + 5 - ((this.menuFrame.parent.frameElement ? this.menuFrame.parent.document.body.scrollTop  : 0))); // compensate for 5px padding in FF
		xVal = FrameTop[0] + FramePos[0] + (e.pageX ? e.pageX + 5 - (this.menuFrame.document.body.scrollLeft + (this.menuFrame.parent.frameElement ? this.menuFrame.parent.document.body.scrollLeft : 0)) : e.clientX + 5 - ((this.menuFrame.parent.frameElement ? this.menuFrame.parent.document.body.scrollLeft : 0))); // compensate for 5px padding in FF
		if (window.pageXOffset) {
			scrlW = window.pageXOffset;
			pageW = window.document.body.clientWidth + scrlW;
			menuW = this.W; // width only
		} else {
			scrlW = window.document.body.scrollLeft;
			pageW = window.document.body.clientWidth + scrlW;
			menuW = this.W; // width only
		}
		if (window.pageYOffset) {
			scrlH = window.pageYOffset;
			pageH = window.document.body.clientHeight + scrlH;
			menuH = this.H; // height only
		} else {
			scrlH = window.document.body.scrollTop;
			pageH = window.document.body.clientHeight + scrlH;
			menuH = this.H; // height only
		}
		if ((xVal - scrlW < 1) || (xVal > pageW - 1)) {
			return false;
		}
		if ((yVal - scrlH < 1) || (yVal > pageH - 1)) {
			return false;
		}
		if ((xVal + menuW + 4) > pageW) {
			xVal = pageW - menuW - 4 - 2;
		} else {
			xVal = xVal;
		}
		if ((yVal + menuH + 4) > pageH) {
			yVal = pageH - menuH - 4;
		} else {
			yVal = yVal;
		}

		return [xVal, yVal]; // return as array

	}

	function togglePopupButton (which, action, command) {
	//--- Flips a button

		switch (action) {
			case 'over':	if (this.lastSelected != null) {
								this.lastSelected.style.backgroundColor = MenuColorBackGrOut;
								this.lastSelected.style.border = '0px';
								this.lastSelected.style.paddingLeft = '1px';
								this.lastSelected.style.paddingTop = '1px';
								this.lastSelected.style.paddingBottom = '1px';
								this.lastSelected = null;
							}
							which.style.backgroundColor = MenuColorBackGrOver;
							which.style.border = '1px solid ' + MenuColorBorderOver;
							which.style.paddingLeft = '';
							which.style.paddingTop = '';
							which.style.paddingBottom = '';
							this.lastSelected = which;
							break;
			case 'out':		if (this.lastSelected != null) {
								this.lastSelected.style.backgroundColor = MenuColorBackGrOut;
								this.lastSelected.style.border = '0px';
								this.lastSelected.style.paddingLeft = '1px';
								this.lastSelected.style.paddingTop = '1px';
								this.lastSelected.style.paddingBottom = '1px';
								this.lastSelected = null;
							}
							which.style.backgroundColor = MenuColorBackGrOut;
							which.style.border = '0px';
							which.style.paddingLeft = '1px';
							which.style.paddingTop = '1px';
							which.style.paddingBottom = '1px';
							break;
			case 'down':	if (this.callBack != null) {
								this.hide (true); // true = in menu
								this.callBack (command);
							} else {
								tFrame = this.menuFrame; this.hide (true); // true = in menu
								tFrame.TogglePopup (command);
							}
							break;
		}

	}

	function show (e) {
	//--- Display the popup menu

		if (this.isOpen) {
			this.hide ();
		}

		if (window.lastBlurResponse != null) {
			clearTimeout (window.lastBlurResponse);
		}

		if ((this.fixedX == 0) && (this.fixedY == 0)) {
			dimensions = this.getPopupDimensions (e);
			this.X = dimensions[0];
			this.Y = dimensions[1];
		} else {
			this.X = this.fixedX;
			this.Y = this.fixedY;
		}

		top.document.getElementById ('popupx').innerHTML = this.displayPopup ();
		top.document.getElementById ('popupx').style.top = this.Y + 'px';
		top.document.getElementById ('popupx').style.left = this.X + 'px';
		top.document.getElementById ('popupx').style.height = this.H + 'px';
		top.document.getElementById ('popupx').style.width = this.W + 'px';
		top.document.getElementById ('popupx').style.visibility = 'visible';
		top.document.getElementById ('popupx').onclick = this.blurClickHandler;
		top.document.getElementById ('popupx').onerror = false;

		window.lastPopupMenu = this;

		this.isOpen = true;
		this.lastSelected = null;

		if (document.all) {
			this.menuFrame.document.body.attachEvent ('onmouseup', this.click);
			this.menuFrame.document.body.attachEvent ('onkeydown', this.keyDown);
			this.menuFrame.document.body.attachEvent ('onfocusout', this.blur); // note: onblur doesn't work well in IE
			this.menuFrame.attachEvent ('onunload', this.hide);
			this.menuFrame.attachEvent ('onscroll', this.hide);
		} else {
			this.menuFrame.document.addEventListener ('mouseup', this.click, false);
			this.menuFrame.document.addEventListener ('keydown', this.keyDown, false);
			this.menuFrame.document.addEventListener ('blur', this.blur, false);
			this.menuFrame.addEventListener ('unload', this.hide, false);
			this.menuFrame.addEventListener ('scroll', this.hide, false);
		}

		//--- Prevent windows from closing on ESCape
		top.modalWindows.disableKeyDown = true;

	}

	function hide (inMenu) {
	//--- Hides the popup menu

		which = window.lastPopupMenu;

		if (window.lastBlurResponse != null) {
			clearTimeout (window.lastBlurResponse);
		}

		top.document.getElementById ('popupx').style.visibility = 'hidden';
		top.document.getElementById ('popupx').onclick = null;
		if (document.all) {
			which.menuFrame.document.body.detachEvent ('onmouseup', which.click);
			which.menuFrame.document.body.detachEvent ('onkeydown', which.keyDown);
			which.menuFrame.document.body.detachEvent ('onfocusout', which.blur);
			which.menuFrame.detachEvent ('onunload', which.hide);
			which.menuFrame.detachEvent ('onscroll', which.hide);
		} else {
			which.menuFrame.document.removeEventListener ('mouseup', which.click, false);
			which.menuFrame.document.removeEventListener ('keydown', which.keyDown, false);
			which.menuFrame.document.removeEventListener ('blur', which.blur, false);
			which.menuFrame.removeEventListener ('unload', which.hide, false);
			which.menuFrame.removeEventListener ('scroll', which.hide, false);
		}

		if (which.callOnHide != null) {
			which.callOnHide (inMenu);
		}

		which.isOpen = false;
		which.lastSelected = null;
		which = null;

	}

	function click (e) {
	//--- Handles window clicks

		if (e.button == 2) {
			return;
		}
		which = window.lastPopupMenu;
		which.hide ();

	}

	function blur (e) {
	//--- Handles blur captures (occurs before click)

		if (window.lastBlurResponse != null) {
			clearTimeout (window.lastBlurResponse);
		}

		window.lastBlurResponse = setTimeout ("window.lastPopupMenu.hide ()", 200);

	}

	function blurClickHandler (e) {
	//--- Stops the blur event from hiding the popup if the user clicks inside it

		if (window.lastBlurResponse != null) {
			clearTimeout (window.lastBlurResponse);
		}

	}

	function keyDown (e) {
	//--- Handles key depressions

		var which = window.lastPopupMenu;
		var key = (e.which ? e.which : e.keyCode);
		var ignoreHandler = false;

		switch (key) {
			case  8:	which.hide ();
						ignoreHandler = true;
						break;
			case 13:	currMenuItem = (which.lastSelected ? which.lastSelected : null);
						if (currMenuItem) {
							currMenuItem.onclick ();
						}
						ignoreHandler = true;
						break;
			case 27:	which.hide ();
						//ignoreHandler = true;
						break;
			case 35:	//--- End
						frstMenuItem = (document.getElementById ('menuLayer').firstChild ? document.getElementById ('menuLayer').firstChild : null);
						while ((frstMenuItem) && ((frstMenuItem.nodeName != 'DIV') || (frstMenuItem.className != 'menuStandard'))) {
							frstMenuItem = (frstMenuItem.nextSibling ? frstMenuItem.nextSibling : null);
						}
						lastMenuItem = (document.getElementById ('menuLayer').lastChild ? document.getElementById ('menuLayer').lastChild : null);
						while ((lastMenuItem) && ((lastMenuItem.nodeName != 'DIV') || (lastMenuItem.className != 'menuStandard'))) {
							lastMenuItem = (lastMenuItem.previousSibling ? lastMenuItem.previousSibling : null);
						}
						currMenuItem = (which.lastSelected ? which.lastSelected : frstMenuItem);
						while ((currMenuItem) && ((currMenuItem.nodeName != 'DIV') || (currMenuItem.className != 'menuStandard'))) {
							currMenuItem = (currMenuItem.nextSibling ? currMenuItem.nextSibling : null);
						}
						currMenuItem.onmouseout ();
						if (lastMenuItem != null) {
							lastMenuItem.onmouseover (); which.lastSelected = lastMenuItem;
						}
						ignoreHandler = true;
						break;
			case 36:	//--- Home
						frstMenuItem = (document.getElementById ('menuLayer').firstChild ? document.getElementById ('menuLayer').firstChild : null);
						while ((frstMenuItem) && ((frstMenuItem.nodeName != 'DIV') || (frstMenuItem.className != 'menuStandard'))) {
							frstMenuItem = (frstMenuItem.nextSibling ? frstMenuItem.nextSibling : null);
						}
						lastMenuItem = (document.getElementById ('menuLayer').lastChild ? document.getElementById ('menuLayer').lastChild : null);
						while ((lastMenuItem) && ((lastMenuItem.nodeName != 'DIV') || (lastMenuItem.className != 'menuStandard'))) {
							lastMenuItem = (lastMenuItem.previousSibling ? lastMenuItem.previousSibling : null);
						}
						currMenuItem = (which.lastSelected ? which.lastSelected : lastMenuItem);
						while ((currMenuItem) && ((currMenuItem.nodeName != 'DIV') || (currMenuItem.className != 'menuStandard'))) {
							currMenuItem = (currMenuItem.nextSibling ? currMenuItem.nextSibling : null);
						}
						currMenuItem.onmouseout ();
						if (frstMenuItem != null) {
							frstMenuItem.onmouseover (); which.lastSelected = frstMenuItem;
						}
						ignoreHandler = true;
						break;
			case 38:	//--- Go up
						frstMenuItem = (document.getElementById ('menuLayer').firstChild ? document.getElementById ('menuLayer').firstChild : null);
						while ((frstMenuItem) && ((frstMenuItem.nodeName != 'DIV') || (frstMenuItem.className != 'menuStandard'))) {
							frstMenuItem = (frstMenuItem.nextSibling ? frstMenuItem.nextSibling : null);
						}
						lastMenuItem = (document.getElementById ('menuLayer').lastChild ? document.getElementById ('menuLayer').lastChild : null);
						while ((lastMenuItem) && ((lastMenuItem.nodeName != 'DIV') || (lastMenuItem.className != 'menuStandard'))) {
							lastMenuItem = (lastMenuItem.previousSibling ? lastMenuItem.previousSibling : null);
						}
						currMenuItem = (which.lastSelected ? which.lastSelected : frstMenuItem);
						while ((currMenuItem) && ((currMenuItem.nodeName != 'DIV') || (currMenuItem.className != 'menuStandard'))) {
							currMenuItem = (currMenuItem.nextSibling ? currMenuItem.nextSibling : null);
						}
						prevMenuItem = (currMenuItem.previousSibling ? currMenuItem.previousSibling : null);
						while ((prevMenuItem) && ((prevMenuItem.nodeName != 'DIV') || (prevMenuItem.className != 'menuStandard'))) {
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
						frstMenuItem = (document.getElementById ('menuLayer').firstChild ? document.getElementById ('menuLayer').firstChild : null);
						while ((frstMenuItem) && ((frstMenuItem.nodeName != 'DIV') || (frstMenuItem.className != 'menuStandard'))) {
							frstMenuItem = (frstMenuItem.nextSibling ? frstMenuItem.nextSibling : null);
						}
						lastMenuItem = (document.getElementById ('menuLayer').lastChild ? document.getElementById ('menuLayer').lastChild : null);
						while ((lastMenuItem) && ((lastMenuItem.nodeName != 'DIV') || (lastMenuItem.className != 'menuStandard'))) {
							lastMenuItem = (lastMenuItem.previousSibling ? lastMenuItem.previousSibling : null);
						}
						currMenuItem = (which.lastSelected ? which.lastSelected : lastMenuItem);
						while ((currMenuItem) && ((currMenuItem.nodeName != 'DIV') || (currMenuItem.className != 'menuStandard'))) {
							currMenuItem = (currMenuItem.previousSibling ? currMenuItem.previousSibling : null);
						}
						nextMenuItem = (currMenuItem.nextSibling ? currMenuItem.nextSibling : null);
						while ((nextMenuItem) && ((nextMenuItem.nodeName != 'DIV') || (nextMenuItem.className != 'menuStandard'))) {
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
			default:	ignoreHandler = true;
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

	function findFramePos (frame) {
	//--- Find the current position of an object on the page

		var curL = 0;
		var curT = 0;

		if (frame.offsetParent) {
			curL = frame.offsetLeft;
			curT = frame.offsetTop;
			while (frame = frame.offsetParent) {
				curL += frame.offsetLeft;
				curT += frame.offsetTop;
			}
		}

		return [curL, curT]; // return as array

	}

	//--- Methods
	this.displayPopup = displayPopup;
	this.getPopupHeight = getPopupHeight;
	this.getPopupDimensions = getPopupDimensions;
	this.togglePopupButton = togglePopupButton;
	this.show = show;
	this.hide = hide;
	this.click = click;
	this.blur = blur;
	this.blurClickHandler = blurClickHandler;
	this.keyDown = keyDown;
	this.findFramePos = findFramePos;

	//--- Properties
	this.menuOptions = m;
	this.menuFrame = (f ? f : window.parent.frames['Main']);
	this.W = w;
	this.H = this.getPopupHeight ();
	this.X = 0;
	this.Y = 0;
	this.fixedX = 0;
	this.fixedY = 0;
	this.callBack = (c ? c : null);
	this.callOnHide = null;
	this.isOpen = false;
	this.lastSelected = null;

}

var MenuDefault = 'position: absolute; top: 0px; left: 0px; background-color: #f9f8f7; border: 1px solid #666666; z-index: 2; -webkit-box-shadow: rgba(0, 0, 0, 0.4) 3px 6px 25px; -moz-box-shadow: rgba(0, 0, 0, 0.3) 3px 6px 9px; box-shadow: rgba(0, 0, 0, 0.3) 3px 6px 9px;';
var MenuLeftBar = 'width: 25px; background-color: #e0dfe3';
var MenuBtBreak = 'font-size:  7px';
var MenuBtLItem = 'font-size: 11px';
var MenuTxBreak = 'font-family: Microsoft Sans Serif, Arial, Verdana; font-size:  7px';
var MenuTxLItem = 'font-family: Microsoft Sans Serif, Arial, Verdana; font-size: 11px' + (document.all ? '; width: 99%' : '');
var MenuColorBorderOver = '#4b4b6f';
var MenuColorBackGrOver = '#ffcd81';
var MenuColorBorderOut  = '';
var MenuColorBackGrOut  = '';
var ImageCache = new Array ();

//--- Deprecated functions
function CreatePopupOption (icon, text, command, state, hotkey) { return createPopupOption (icon, text, command, state, hotkey); }