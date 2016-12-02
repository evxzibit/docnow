//--- Ensight Source Code (ESC)
//--- First Created on Friday, January 8 2010 by John Ginsberg (snowed in)
//--- For Ensight 4

//--- Module Name: ilayout.js
//--- Contains: v1.0

//--- Modified by:
//--- Modified by:

//--- Description:
//--- Helpful javascript functionality
//--- Make sure XHTML mode is enabled, especially in Internet Explorer!

//--- Notice:
//--- This code is copyright (C) 2010, Ensight Ltd. All rights
//--- are reserved. Using this code in any shape or form requires permission
//--- from Ensight Ltd - http://www.getensight.com

//----------------------------------------------------------------

function _getScreenWidth  () {
//--- Returns the dynamic screen width [XHTML mode]

	return 	(window.innerWidth ? window.innerWidth : 
			(document.documentElement ? document.documentElement.clientWidth : 
			(document.body ? document.body.clientWidth : 0)));

}

function _getScreenHeight () {
//--- Returns the dynamic screen height [XHTML mode]

	return 	(window.innerHeight ? window.innerHeight : 
			(document.documentElement ? document.documentElement.clientHeight : 
			(document.body ? document.body.clientHeight : 0)));

}

function _getBodyWidth  () {
//--- Returns the actual body width [XHTML mode]

	return 	(window.outerWidth ? window.outerWidth : 
			(document.body ? document.body.clientWidth : 0));

}

function _getBodyHeight () {
//--- Returns the actual body height

	return 	(window.outerHeight ? window.outerHeight : 
			(document.body ? document.body.clientHeight : 0));

}

function _getDynamicObjectWidth  (whichObject) {
//--- Tries to determine the dynamic width of an object

	return 	(whichObject.style.width ? parseInt (whichObject.style.width) : 
			(whichObject.style.pixelWidth ? parseInt (whichObject.style.pixelWidth) : 
			(whichObject.offsetWidth ? whichObject.offsetWidth : 
			(document.defaultView && document.defaultView.getComputedStyle ? document.defaultView.getComputedStyle (whichObject, '').getPropertyValue ('width') : 0)))); 

}

function _getDynamicObjectHeight (whichObject) {
//--- Tries to determine the dynamic height of an object

	return 	(whichObject.style.height ? parseInt (whichObject.style.height) : 
			(whichObject.style.pixelHeight ? parseInt (whichObject.style.pixelHeight) : 
			(whichObject.offsetHeight ? whichObject.offsetHeight : 
			(document.defaultView && document.defaultView.getComputedStyle ? document.defaultView.getComputedStyle (whichObject, '').getPropertyValue ('height') : 0)))); 

}

function _getScrollTop () {
//--- Looks up the correct scrollTop setting

	if (window.pageYOffset) {
		return window.pageYOffset;
	} else
	if (document.documentElement) {
		return document.documentElement.scrollTop;
	} else
	if (document.body) {
		return document.body.scrollTop;
	} else {
		return 0;
	}

}

function _getScrollLeft () {
//--- Looks up the correct scrollWidth setting

	if (window.pageXOffset) {
		return window.pageXOffset;
	} else
	if (document.documentElement) {
		return document.documentElement.scrollLeft;
	} else
	if (document.body) {
		return document.body.scrollLeft;
	} else {
		return 0;
	}

}

function _getScrollWidth () {
//--- Looks up the correct scrollWidth setting

	if (document.documentElement) {
		return document.documentElement.scrollWidth;
	} else
	if (document.body) {
		return document.body.scrollWidth;
	}

}

function _getScrollHeight () {
//--- Looks up the correct scrollWidth setting

	if (document.documentElement) {
		return document.documentElement.scrollHeight;
	} else
	if (document.body) {
		return document.body.scrollHeight;
	}

}

function _getDynamicObjectPosition (whichObject) {
//--- Find the dynamic position of an object on the page and returns an array (xPos, yPos, isFixed)

	var curL = 0;
	var curT = 0;

	if (whichObject.offsetParent) {
		curL = whichObject.offsetLeft;
		curT = whichObject.offsetTop;
		if (whichObject.style.position == 'fixed') {
			return [curL, curT, true];
		}
		while (whichObject = whichObject.offsetParent) {
			curL += whichObject.offsetLeft;
			curT += whichObject.offsetTop;
			if (whichObject.style.position == 'fixed') {
				return [curL, curT, true];
			}
		}
	}

	return [curL, curT, false]; // return as array

}

function _setDynamicObjectPosition (whichObject) {
//--- Moves the object to the root of the DOM tree so that its dynamic position can be calculated
//--- Can be called via a function that _onBodyReady triggers

	document.body.appendChild (whichObject);

}

function _showElement (whichObject) {
//--- Ensures that any element is visible on the page

	whichObject.style.visibility = 'visible';
	whichObject.style.display = 'block';

}

function _hideElement (whichObject) {
//--- Ensures that any element is hidden from the page

	whichObject.style.visibility = 'hidden';
	whichObject.style.display = 'none';

}

//----------------------------------------------------------------

function _showAsLightbox (whichObject, opacity, w, h) {
//--- Loads the selected object as a lightbox

	if (_$ ('_lightBoxShadow')) {
		_$ ('_lightBoxShadow').style.visibility = 'visible';
	} else {
		var opacity = (opacity == null ? 60 : opacity);
		var shadowLayer = document.createElement ("DIV");
		shadowLayer.id = '_lightBoxShadow';
		shadowLayer.style.visibility = 'visible';
		shadowLayer.style.position = 'fixed';
		shadowLayer.style.backgroundColor = '#000000';
		shadowLayer.style.left = '0px';
		shadowLayer.style.top = '0px';
		shadowLayer.style.height = '100%';
		shadowLayer.style.width = '100%';
		shadowLayer.style.zIndex = 1001;
		shadowLayer.style.filter = 'alpha(opacity=' + opacity + ')';
		shadowLayer.style.MozOpacity = opacity / 100;
		shadowLayer.style.opacity = opacity / 100;
		//--- Pin to the document body
		_setDynamicObjectPosition (shadowLayer);
	}

	whichObject.style.position = 'fixed';
	whichObject.style.visibility = 'hidden'; // hide
	whichObject.style.display = 'block';
	whichObject.style.zIndex = 1050;

	//--- Get dynamic width and height
	h = (h ? h : _getDynamicObjectHeight (whichObject));
	w = (w ? w : _getDynamicObjectWidth  (whichObject));

	whichObject.style.width  = w + 'px';
	whichObject.style.height = h + 'px'; 
	whichObject.style.top    = ((_getScreenHeight () - h) / 2) + 'px';
	whichObject.style.left   = ((_getScreenWidth  () - w) / 2) + 'px';
	whichObject.style.visibility = 'visible'; // show

}

function _hideLightbox (whichObject) {
//--- Hides the lightbox

	whichObject.style.visibility = 'hidden';
	whichObject.style.display = 'none';

	if (_$ ('_lightBoxShadow')) {
		_$ ('_lightBoxShadow').style.visibility = 'hidden';
	}

}

//----------------------------------------------------------------

function _showAtDynamicPosition (e, whichObject, relativePos, xDiff, yDiff) {
//--- Loads a div relative to the position of another element

	targetElement = (e.target ? e.target : e.srcElement);
	targetElementPos = _getDynamicObjectPosition (targetElement);

	whichObject.style.position = 'absolute';
	whichObject.style.visibility = 'hidden'; // hide
	whichObject.style.display = 'block';

	objectH = _getDynamicObjectHeight (whichObject);
	objectW = _getDynamicObjectWidth  (whichObject);
	targetH = _getDynamicObjectHeight (targetElement);
	targetW = _getDynamicObjectWidth  (targetElement);

	switch (relativePos) {
		case 'aboveleft':
		case 'above':
		case 'abovemiddle':
		case 'aboveright':
						y = targetElementPos[1] - (objectH) + (targetElementPos[2] ? _getScrollTop () : 0) + yDiff;
						x = targetElementPos[0] + (relativePos == 'aboveright' ? targetW - objectW : 0) + (relativePos == 'abovemiddle' ? 0 - parseInt (objectW / 2) + parseInt (targetW / 2) : 0) + (targetElementPos[2] ? _getScrollLeft () : 0) + xDiff;
						break;
		case 'belowleft':
		case 'below':
		case 'belowmiddle':
		case 'belowright':
						y = targetElementPos[1] + (targetH) + (targetElementPos[2] ? _getScrollTop () : 0) + yDiff;
						x = targetElementPos[0] + (relativePos == 'belowright' ? targetW - objectW : 0) + (relativePos == 'belowmiddle' ? 0 - parseInt (objectW / 2) + parseInt (targetW / 2) : 0) + (targetElementPos[2] ? _getScrollLeft () : 0)  + xDiff;
						break;
		case 'lefttop':
		case 'left':
		case 'leftmiddle':
		case 'leftbottom':
						x = targetElementPos[0] - (objectW) + (targetElementPos[2] ? _getScrollLeft () : 0) + xDiff;
						y = targetElementPos[1] + (relativePos == 'leftbottom' ? targetH - objectH : 0) + (relativePos == 'leftmiddle' ? 0 - parseInt (objectH / 2) + parseInt (targetH / 2) : 0) + (targetElementPos[2] ? _getScrollTop () : 0) + yDiff;
						break;
		case 'righttop':
		case 'right':
		case 'rightmiddle':
		case 'rightbottom':
						x = targetElementPos[0] + (targetW) + (targetElementPos[2] ? _getScrollLeft () : 0) + xDiff;
						y = targetElementPos[1] + (relativePos == 'rightbottom' ? targetH - objectH : 0) + (relativePos == 'rightmiddle' ? 0 - parseInt (objectH / 2) + parseInt (targetH / 2) : 0) + (targetElementPos[2] ? _getScrollTop () : 0) + yDiff;
						break;
		case 'follow':
		default:		y = (e.pageY ? e.pageY : e.clientY + _getScrollTop  ()) + yDiff;
						x = (e.pageX ? e.pageX : e.clientX + _getScrollLeft ()) + xDiff;
						break;
	}

	if (x < 0) {
		x = 0;
	}
	if (y < 0) {
		y = 0;
	}

	whichObject.style.top  = y + 'px';
	whichObject.style.left = x + 'px';
	whichObject.style.zIndex = 1200; // above the lightbox (if appropriate)

	whichObject.style.visibility = 'visible'; // show

}

//----------------------------------------------------------------

function _findPrevSibling (whichObject) {
//--- Ensures that only previous object siblings are found

	nearest = whichObject.previousSibling;
	while ((nearest) && (nearest.nodeType != 1)) {
		nearest = nearest.previousSibling;
	}

	return nearest;

}

function _findNextSibling (whichObject) {
//--- Ensures that only next object siblings are found

	nearest = whichObject.nextSibling;
	while ((nearest) && (nearest.nodeType != 1)) {
		nearest = nearest.nextSibling;
	}

	return nearest;

}

function _cloneChild (whichObject, relativePos) {
//--- Clones a child element and inserts it somewhere else within its direct parent

	cloneObject = whichObject.cloneNode (true); // copy the full tree
	cloneObject.id = '';

	switch (relativePos) {
		case 'top':		whichObject.parentNode.insertBefore (cloneObject, whichObject.parentNode.firstChild); break;
		case 'bottom':	whichObject.parentNode.insertBefore (cloneObject, null); break;
		case 'above':	whichObject.parentNode.insertBefore (cloneObject, whichObject); break;
		case 'below':	whichObject.parentNode.insertBefore (cloneObject, whichObject.nextSibling); break;
	}

	cloneObject.style.display = '';

	return cloneObject; // in case further manipulation is required

}
