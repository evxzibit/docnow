//--- Ensight Source Code (ESC)
//--- First Created on Friday, August 1 2003 by John Ginsberg (Happy B-Day)
//--- For Ensight 4

//--- Module Name: iwindows.js
//--- Contains: None

//--- Modified by:
//--- Modified by:

//--- Description:
//--- Window rendering and support engine

//--- Notice:
//--- This code is copyright (C) 2008, ENVENT Holdings (Pty) Ltd. All rights
//--- are reserved. Using this code in any shape or form requires permission
//--- from ENVENT Holdings (Pty) Ltd - http://www.ensight.co.uk

//----------------------------------------------------------------

/**
 * Object modalWindow
 */
function modalWindow () {

	function dragStart (e) {
	//--- Kick-starts the drag process

		var X, Y;

		if (document.all) {
			X = window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
			Y = window.event.clientY + document.documentElement.scrollTop  + document.body.scrollTop;
		} else {
			X = e.clientX + window.scrollX;
			Y = e.clientY + window.scrollY;
		}

		this.windowDrag.startX = X;
		this.windowDrag.startY = Y;
		this.windowDrag.windowLeft = parseInt (this.windows[this.lastWindow].style.left, 10);
		this.windowDrag.windowTop  = parseInt (this.windows[this.lastWindow].style.top,  10);

		if (isNaN (this.windowDrag.windowLeft)) { this.windowDrag.windowLeft = 0; }
		if (isNaN (this.windowDrag.windowTop))  { this.windowDrag.windowTop = 0; }

		if ((e.target || e.srcElement).className == 'closeWindow') {
			return false;
		}

		//--- Add something to prevent the user from dragging over the iframe
		document.getElementById ('overlayFrame_' + this.lastWindow).style.display = '';

		if (document.all) {
			document.attachEvent ("onmousemove", this.dragInMotion);
			document.attachEvent ("onmouseup", this.dragStop);
			window.event.cancelBubble = true;
			window.event.returnValue = false;
		} else {
			document.addEventListener ("mousemove", this.dragInMotion, false);
			document.addEventListener ("mouseup", this.dragStop, false);
			e.preventDefault ();
		}

	}

	function dragInMotion (e) {
	//--- Handles the drag while in motion

		var X, Y;
		var which = window.lastModalWindow;

		if ((document.all) && (e.button != 1)) {
			which.dragStop (e); return;
		}

		if (document.all) {
			X = window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
			Y = window.event.clientY + document.documentElement.scrollTop  + document.body.scrollTop;
		} else {
			X = e.clientX + window.scrollX;
			Y = e.clientY + window.scrollY;
		}

		which.screenW = (window.innerWidth  ? window.innerWidth  : document.body.clientWidth);
		which.screenH = (window.innerHeight ? window.innerHeight : document.body.clientHeight);

		DestX = (which.windowDrag.windowLeft + X - which.windowDrag.startX);
		DestY = (which.windowDrag.windowTop  + Y - which.windowDrag.startY);
		if (DestX < 10) {
			DestX = 10;
		}
		if (DestX + parseInt (which.windows[which.lastWindow].style.width) > (which.screenW - 10)) {
			DestX = (which.screenW - parseInt (which.windows[which.lastWindow].style.width) - 10);
		}
		if (DestY < 10) {
			DestY = 10;
		}
		if (DestY + parseInt (which.windows[which.lastWindow].style.height) > (which.screenH - 10)) {
			DestY = (which.screenH - parseInt (which.windows[which.lastWindow].style.height) - 10);
		}
		which.windows[which.lastWindow].style.left = DestX + 'px';
		which.windows[which.lastWindow].style.top  = DestY + 'px';

		if (document.all) {
			window.event.cancelBubble = true;
			window.event.returnValue = false;
		} else {
			e.preventDefault ();
		}

	}

	function dragStop (e) {
	//--- Stops the drag process

		var which = window.lastModalWindow;

		document.getElementById ('overlayFrame_' + which.lastWindow).style.display = 'none';

		if (document.all) {
			document.detachEvent ("onmousemove", which.dragInMotion); 
			document.detachEvent ("onmouseup", which.dragStop);
			window.event.cancelBubble = true;
			window.event.returnValue = false;
		} else {
			document.removeEventListener ("mousemove", which.dragInMotion, false);
			document.removeEventListener ("mouseup", which.dragStop, false);
			e.preventDefault ();
		}

	}

	function keyDown (e) {
	//--- Handles key presses

		var which = window.lastModalWindow;
		var key = (e.which ? e.which : e.keyCode);
		var ignoreHandler = false;
		var ignoreTrigger = false;
		var srcElement = (e.srcElement ? e.srcElement.tagName : e.target.nodeName);

		which.screenW = (window.innerWidth  ? window.innerWidth  : document.body.clientWidth);
		which.screenH = (window.innerHeight ? window.innerHeight : document.body.clientHeight);

		if ((srcElement == 'INPUT') || (srcElement == 'SELECT') || (srcElement == 'BUTTON') || (srcElement == 'TEXTAREA')) {
			ignoreTrigger = true;
		}

		switch (key) {
			case  8:	which.disableKeyDown = false; break;
			case 13:	which.disableKeyDown = false; break;
			case 27:	if ((ignoreHandler) || (which.disableKeyDown)) { which.disableKeyDown = false; break; } which.closeWindow (); ignoreHandler = true; break; // close the last opened window
			case 37:	if ((ignoreHandler) || (ignoreTrigger) || (which.disableKeyDown)) { break; } which.windows[which.lastWindow].style.left = (parseInt (which.windows[which.lastWindow].style.left) > 10 ? ((parseInt (which.windows[which.lastWindow].style.left) - 5) < 10 ? 10 : (parseInt (which.windows[which.lastWindow].style.left) - 5)) : 10) + 'px'; ignoreHandler = true; break;
			case 38:	if ((ignoreHandler) || (ignoreTrigger) || (which.disableKeyDown)) { break; } which.windows[which.lastWindow].style.top  = (parseInt (which.windows[which.lastWindow].style.top)  > 10 ? ((parseInt (which.windows[which.lastWindow].style.top)  - 5) < 10 ? 10 : (parseInt (which.windows[which.lastWindow].style.top)  - 5)) : 10) + 'px'; ignoreHandler = true; break;
			case 39:	if ((ignoreHandler) || (ignoreTrigger) || (which.disableKeyDown)) { break; } which.windows[which.lastWindow].style.left = (parseInt (which.windows[which.lastWindow].style.left) < which.screenW - parseInt (which.windows[which.lastWindow].style.width)  - 10 ? ((parseInt (which.windows[which.lastWindow].style.left) + 5) > (which.screenW - parseInt (which.windows[which.lastWindow].style.width)  - 10) ? (which.screenW - parseInt (which.windows[which.lastWindow].style.width)  - 10) : (parseInt (which.windows[which.lastWindow].style.left) + 5)) : which.screenW - parseInt (which.windows[which.lastWindow].style.width)  - 10) + 'px'; ignoreHandler = true; break;
			case 40:	if ((ignoreHandler) || (ignoreTrigger) || (which.disableKeyDown)) { break; } which.windows[which.lastWindow].style.top  = (parseInt (which.windows[which.lastWindow].style.top)  < which.screenH - parseInt (which.windows[which.lastWindow].style.height) - 10 ? ((parseInt (which.windows[which.lastWindow].style.top)  + 5) > (which.screenH - parseInt (which.windows[which.lastWindow].style.height) - 10) ? (which.screenH - parseInt (which.windows[which.lastWindow].style.height) - 10) : (parseInt (which.windows[which.lastWindow].style.top)  + 5)) : which.screenH - parseInt (which.windows[which.lastWindow].style.height) - 10) + 'px'; ignoreHandler = true; break;
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

	function resizeWindows (e) {
	//--- Resizes the shadows when the window resizes

		var which = window.lastModalWindow;

		oldScreenW = which.screenW;
		oldScreenH = which.screenH;
		which.screenW = (window.innerWidth  ? window.innerWidth  : document.body.clientWidth);
		which.screenH = (window.innerHeight ? window.innerHeight : document.body.clientHeight);

		for (i = 0; i <= which.lastWindow; i++) {
			which.shadows[i].style.width  = which.screenW + 'px';
			which.shadows[i].style.height = which.screenH + 'px';
			//--- Move windows up and left to ensure they don't scroll off the page
			if ((which.screenW < oldScreenW) && ((parseInt (which.windows[i].style.left) + parseInt (which.windows[i].style.width) + 10) > which.screenW)) {
				which.windows[i].style.left = (parseInt (which.windows[i].style.left) - (oldScreenW - which.screenW) > 10 ? parseInt (which.windows[i].style.left) - (oldScreenW - which.screenW) : 10) + 'px';
			}
			if ((which.screenH < oldScreenH) && ((parseInt (which.windows[i].style.top) + parseInt (which.windows[i].style.height) + 10) > which.screenH)) {
				which.windows[i].style.top  = (parseInt (which.windows[i].style.top)  - (oldScreenH - which.screenH) > 10 ? parseInt (which.windows[i].style.top)  - (oldScreenH - which.screenH) : 10) + 'px';
			}
		}

	}

	function openWindow (URL, Title, Width, Height, wParameters, callOnClose) {
	//--- Opens a new window with the specifics in the center of the screen

		//--- Work out exact screen center and position frame
		this.screenW = (window.innerWidth  ? window.innerWidth  : document.body.clientWidth);
		this.screenH = (window.innerHeight ? window.innerHeight : document.body.clientHeight);

		//--- Change mode of current window
		if (this.lastWindow >= 0) {
			if (document.getElementById ('windowHeader_' + this.lastWindow)) {
				document.getElementById ('windowHeader_' + this.lastWindow).className = 'boxHeaderPassive';
			}
		}

		this.lastWindow++;

		Width  = (document.all ? Width - 2 : Width - 2);
		Height = (document.all ? Height - 2 : Height - 2);

		//--- Create the modal window
		var tWindow = document.createElement ("DIV");
		tWindow.style.visibility = 'visible';
		tWindow.style.position = 'absolute';
		tWindow.style.zIndex = 500 + (this.lastWindow * 10) + 1;
		tWindow.style.left = parseInt ((this.screenW - Width) / 2) + 'px';
		tWindow.style.top = parseInt ((this.screenH - Height) / 2) + 'px';
		tWindow.style.width = parseInt (Width) + 'px';
		tWindow.style.height = parseInt (Height) + 'px';
		tWindow.style.border = '0px';
		tRandom = (Math.random () * 4123929040).toString ();
		layerHTML  = "";
		layerHTML += "<div id=\"outerWindow_" + this.lastWindow + "\" style=\"" + (document.all ? "-webkit-box-shadow: rgba(0, 0, 0, 0.4) 3px 6px 25px; -moz-box-shadow: rgba(0, 0, 0, 0.3) 3px 6px 9px; box-shadow: rgba(0, 0, 0, 0.3) 3px 6px 9px; " : "-webkit-box-shadow: rgba(0, 0, 0, 0.4) 3px 6px 25px; -moz-box-shadow: rgba(0, 0, 0, 0.3) 3px 6px 9px; box-shadow: rgba(0, 0, 0, 0.3) 3px 6px 9px;") + " border: 1px solid #8a89a6; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; width: " + (parseInt (Width)) + "px; height: " + (parseInt (Height)) + "px; position: absolute; top: 0px; z-index: 200\" onmousedown=\"window.lastModalWindow.dragStart (event)\">\n";
		layerHTML += "	<div id=\"overlayFrame_" + this.lastWindow + "\" class=\"overlayWindow\" style=\"display: none; width: " + (parseInt (Width) - (document.all ? 0 : 0)) + "px; height: " + (parseInt (Height) - (document.all ? 0 : 0)) + "px;\"></div>\n";
		layerHTML += "	<div id=\"windowHeader_" + this.lastWindow + "\" class=\"boxHeaderActive\">\n";
		layerHTML += "		<div class=\"windowTitle\"><b>" + Title + "</b></div>\n";
		layerHTML += "		<div class=\"closeWindow\" onclick=\"window.lastModalWindow.closeWindow (); return false\"></div>\n";
		layerHTML += "	</div>";
		layerHTML += "	<div id=\"frameContent_" + this.lastWindow + "\" class=\"frameBackground\">\n";
		layerHTML += "		<iframe id=\"iwinframe_" + tRandom + "\" src=\"" + (URL + (URL.indexOf ('?') != -1 ? '&' : '?') + 'rand=' + tRandom) + "\" frameborder=\"0\" scrolling=\"no\" style=\"display: block; width: " + (parseInt (Width) - (document.all ? 0 : 0)) + "px; height: " + (parseInt (Height) - (document.all ? 25 : 25)) + "px; z-index: 200; -webkit-border-bottom-left-radius: 3px; -webkit-border-bottom-right-radius: 3px; -moz-border-radius-bottomleft: 3px; -moz-border-radius-bottomright: 3px; border-bottom-left-radius: 3px; border-bottom-right-radius: 3px;\"></iframe>\n";
		layerHTML += "	</div>";
		layerHTML += "</div>";
		tWindow.innerHTML = layerHTML;

		//--- Create the shadow
		opacity = (this.lastWindow == 0 ? 60 : 0);
		var tShadow = document.createElement ("DIV");
		tShadow.style.visibility = 'visible';
		tShadow.style.position = 'absolute';
		tShadow.style.zIndex = 500 + (this.lastWindow * 10);
		tShadow.style.left = '0px';
		tShadow.style.top = '0px';
		tShadow.style.width = this.screenW + 'px';
		tShadow.style.height = this.screenH + 'px';
		tShadow.style.backgroundColor = '#000000';
		tShadow.style.backgroundImage = '-moz-radial-gradient(50% 50%, ellipse closest-side, #666, #000000 100%)';
		tShadow.style.backgroundImage = '-webkit-radial-gradient(50% 50%, ellipse closest-side, #666, #000000 100%)';
		tShadow.style.backgroundImage = '-o-radial-gradient(50% 50%, ellipse closest-side, #666, #000000 100%)';
		tShadow.style.backgroundImage = '-ms-radial-gradient(50% 50%, ellipse closest-side, #666, #000000 100%)';
		tShadow.style.backgroundImage = 'radial-gradient(50% 50%, ellipse closest-side, #666, #000000 100%)';
		tShadow.style.filter = 'alpha(opacity=' + opacity + ')';
		tShadow.style.MozOpacity = opacity / 100;
		tShadow.style.opacity = opacity / 100;

		//--- Assign event handlers
		if (this.lastWindow == 0) {
			if (document.all) {
				window.attachEvent ("onresize", this.resizeWindows); document.attachEvent ("onkeydown", this.keyDown);
			} else {
				window.addEventListener ("resize", this.resizeWindows, false); document.addEventListener ("keydown", this.keyDown, false);
			}
		}

		//--- Assign parameters
		if (wParameters != null) {
			this.windowArguments = wParameters;
		}

		this.disableKeyDown = false;

		this.onClose[this.lastWindow] = (callOnClose ? callOnClose : null);
		this.lastArg[this.lastWindow] = (wParameters ? wParameters : null);
		this.shadows[this.lastWindow] = document.body.appendChild (tShadow);
		this.windows[this.lastWindow] = document.body.appendChild (tWindow);
		this.iFrames[this.lastWindow] = document.getElementById ('iwinframe_' + tRandom); // easy access to the iFrame itself
		this.windows[this.lastWindow].focus ();

		return true;

	}

	function closeWindow () {
	//--- Closes the popup window (call from the popup's iframe only)

		if (this.lastWindow < 0) {
			return; // just in case
		}

		var callOnBeforeClose = this.onBeforeClose[this.lastWindow]; this.onBeforeClose[this.lastWindow] = null;

		if (callOnBeforeClose != null) {
			callOnBeforeClose (); // no parameters, this is just a request to clean up
		}

		var callOnClose = this.onClose[this.lastWindow]; this.onClose[this.lastWindow] = null;
		var lastWinArgs = this.lastArg[this.lastWindow]; this.lastArg[this.lastWindow] = null;
		var lastWinBody = this.onClose[this.lastWindow]; this.iFrames[this.lastWindow] = null; // var not used (for now)
		document.body.removeChild (this.shadows[this.lastWindow]); this.shadows[this.lastWindow] = null;
		document.body.removeChild (this.windows[this.lastWindow]); this.windows[this.lastWindow] = null;

		this.lastWindow--;

		if (this.lastWindow >= 0) {
			//--- Change mode of current window
			if (document.getElementById ('windowHeader_' + this.lastWindow)) {
				document.getElementById ('windowHeader_' + this.lastWindow).className = 'boxHeaderActive';
			}
			this.windows[this.lastWindow].focus ();
		}

		//--- Remove event handlers
		if (this.lastWindow  < 0) {
			if (document.all) {
				window.detachEvent ("onresize", this.resizeWindows); document.detachEvent ("onkeydown", this.keyDown);
			} else {
				window.removeEventListener ("resize", this.resizeWindows, false); document.removeEventListener ("keydown", this.keyDown, false);
			}
		}

		if (callOnClose != null) {
			callOnClose (lastWinArgs);
		}

		this.windowArguments = this.lastArg[this.lastWindow];

	}

	function hideWindows () {
	//--- Temporarily hides the windows and shows the background

		for (i = 0; i < this.windows.length; i++) {
			this.windows[i].style.display = 'none';
		}
		for (i = 0; i < this.shadows.length; i++) {
			this.shadows[i].style.display = 'none';
		}

	}

	function showWindows () {
	//--- Temporarily hides the windows and shows the background

		for (i = 0; i < this.windows.length; i++) {
			this.windows[i].style.display = '';
		}
		for (i = 0; i < this.shadows.length; i++) {
			this.shadows[i].style.display = '';
		}

	}

	function expandWindow (addWidth, addHeight, Centered) {
	//--- Changes the dimensions of the active window, use a negative value to shrink the window back

		//--- Work out exact screen center and position frame
		this.screenW = (window.innerWidth  ? window.innerWidth  : document.body.clientWidth);
		this.screenH = (window.innerHeight ? window.innerHeight : document.body.clientHeight);

		Width  = addWidth;
		Height = addHeight;

		this.windows[this.lastWindow].style.left   = (Centered ? parseInt ((this.screenW - (parseInt (this.windows[this.lastWindow].style.width)  + parseInt (Width)))  / 2) + 'px' : this.windows[this.lastWindow].style.left);
		this.windows[this.lastWindow].style.top    = (Centered ? parseInt ((this.screenH - (parseInt (this.windows[this.lastWindow].style.height) + parseInt (Height))) / 2) + 'px' : this.windows[this.lastWindow].style.top);
		this.windows[this.lastWindow].style.width  = parseInt (this.windows[this.lastWindow].style.width)  + parseInt (Width)  + 'px';
		this.windows[this.lastWindow].style.height = parseInt (this.windows[this.lastWindow].style.height) + parseInt (Height) + 'px';
		this.iFrames[this.lastWindow].style.width  = parseInt (this.iFrames[this.lastWindow].style.width)  + parseInt (Width)  + 'px';
		this.iFrames[this.lastWindow].style.height = parseInt (this.iFrames[this.lastWindow].style.height) + parseInt (Height) + 'px';
		subDivs = this.windows[this.lastWindow].getElementsByTagName ('DIV');
		for (i = 0; i < subDivs.length; i++) {
			switch (subDivs[i].id) {
				case 'outerWindow_'  + this.lastWindow:
				case 'overlayFrame_' + this.lastWindow:
				case 'frameContent_' + this.lastWindow:
					try {
						subDivs[i].style.width  = parseInt (subDivs[i].style.width)  + parseInt (Width)  + 'px';
						subDivs[i].style.height = parseInt (subDivs[i].style.height) + parseInt (Height) + 'px';
					} catch (e) {
						// IE error catcher
					}
					break;
			}			
		}

	}

	function attachBeforeCloseHandler (callOnBeforeClose) {
	//--- Indicates a function that must be called before the window closes (call from the window itself)

		this.onBeforeClose[this.lastWindow] = (callOnBeforeClose ? callOnBeforeClose : null);

	}

	function detachBeforeCloseHandler () {
	//--- Removes the close handler function

		this.onBeforeClose[this.lastWindow] = null;

	}

	function attachKeyHandler (whichDocument) {
	//--- Sets a browser-agnostic ESCape key close handler to the document

		if (document.all) {
			if (whichDocument.body) { whichDocument.body.attachEvent ("onkeydown", this.keyDown); }
		} else {
			if (whichDocument) { whichDocument.addEventListener ("keydown", this.keyDown, false); }
		}

	}

	function switchFocus (whichWindow) {
	//--- Forces focus on the window object itself

		whichWindow.focus ();

	}

	//--- Methods
	this.dragStart = dragStart;
	this.dragInMotion = dragInMotion;
	this.dragStop = dragStop;
	this.keyDown = keyDown;
	this.resizeWindows = resizeWindows;
	this.openWindow = openWindow;
	this.closeWindow = closeWindow;
	this.hideWindows = hideWindows;
	this.showWindows = showWindows;
	this.expandWindow = expandWindow;
	this.attachBeforeCloseHandler = attachBeforeCloseHandler;
	this.detachBeforeCloseHandler = detachBeforeCloseHandler;
	this.attachKeyHandler = attachKeyHandler;
	this.switchFocus = switchFocus;

	//--- Properties
	this.screenW = null;
	this.screenH = null;
	this.shadows = new Array ();
	this.windows = new Array ();
	this.iFrames = new Array ();
	this.onBeforeClose = new Array ();
	this.onClose = new Array ();
	this.lastArg = new Array ();
	this.windowArguments = null;
	this.windowDrag = new Object ();
	this.lastWindow = -1;
	this.disableKeyDown = false;

	//--- Globals
	window.lastModalWindow = this;

}

function PopupModalDialog (URL, Arguments, Width, Height, Title) {
//--- Displays a modal window

	return top.modalWindows.openWindow (URL, Title, Width, Height, Arguments);

}

function PopupModelessDialog (URL, Arguments, Width, Height, Title) {
//--- Displays a modeless window

	return top.modalWindows.openWindow (URL, Title, Width, Height, Arguments);

}

//--- Cache images
popup_bg = new Image ();
popup_bg.src = 'images/modal-bg.gif';
popup__x = new Image ();
popup__x.src = 'images/modal-close.gif';

var modalWindows = new modalWindow ();
