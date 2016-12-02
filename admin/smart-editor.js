/*
onload=function(){
if (document.getElementsByClassName == undefined) {
	document.getElementsByClassName = function(className)
	{
		var hasClassName = new RegExp("(?:^|\\s)" + className + "(?:$|\\s)");
		var allElements = document.getElementsByTagName("*");
		var results = [];

		var element;
		for (var i = 0; (element = allElements[i]) != null; i++) {
			var elementClass = element.className;
			if (elementClass && elementClass.indexOf(className) != -1 && hasClassName.test(elementClass))
				results.push(element);
		}

		return results;
	}
}
}
*/

function NewWindow (URL, Title, Width, Height, Params) {
//--- Opens a new window with the specifics in the center of the screen

	top.modalWindows.openWindow (URL, Title, (parseInt (Width) + 5), (parseInt (Height) + 40), Params);

}

function SelectFileFromLibrary (Filename, ImagePath, W, H) {
//--- Handler function that gets called when an image is selected from the library

	if (lastSelected == null) {
		return;
	}

	if (lastSelected.nodeName == 'IMG') {
		lastSelected.src = ImagePath + '/' + Filename;
		lastSelected.width = W;
		lastSelected.height = H;
	} else {
		newImg = new Image ();
		newImg.src = ImagePath + '/' + Filename;
		newImg.width = W;
		newImg.height = H;
		lastSelected.innerHTML = '';
		lastSelected.appendChild (newImg);
	}

}

function Prompt (Question, Default, Title, ResponseType) {
//--- Replaces the prompt function that doesn't work anymore

	top.modalWindows.openWindow ("prompt-dialog.php?Question=" + escape (Question) + "&Default=" + escape (Default), Title, 460, 130, Array (ResponseType, null), PromptResponse);

}

function PromptResponse (Response) {
//--- Handles the response to a prompt

	lastSelected.innerHTML = Response[1].toString ();

}

function GetObjPos (whichObject) {
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

function GetObjSize (whichObject) {
//--- Tries to determine the dynamic width and height of an object

	H = (whichObject.style.height ? parseInt (whichObject.style.height) : 
		(whichObject.style.pixelHeight ? parseInt (whichObject.style.pixelHeight) : 
		(whichObject.offsetHeight ? whichObject.offsetHeight : 
		(document.defaultView && document.defaultView.getComputedStyle ? document.defaultView.getComputedStyle (whichObject, '').getPropertyValue ('height') : 0)))); 
 	W = (whichObject.style.width ? parseInt (whichObject.style.width) : 
		(whichObject.style.pixelWidth ? parseInt (whichObject.style.pixelWidth) : 
		(whichObject.offsetWidth ? whichObject.offsetWidth : 
		(document.defaultView && document.defaultView.getComputedStyle ? document.defaultView.getComputedStyle (whichObject, '').getPropertyValue ('width') : 0))));

	return [W, H];

}

function CloneChild (whichObject, relativePos) {
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

function DuplicateRow (whichLink) {
//--- Duplicates the selected row

	newRepeater = CloneChild (lastRepeater, 'below');
	newRepeater.className = newRepeater.className.toString ().replace (/ solid/g, '');
	AssignRepeater (newRepeater);
	divs = newRepeater.getElementsByTagName ('*');
	AssignHandlers (divs);

}

function RemoveRow (whichLink) {
//--- Removes the selected row

	if (confirm ('Are you sure you want to delete this section?')) {
		lastRepeater.parentNode.removeChild (lastRepeater);
		document.getElementById ('buttonBar').style.display = 'none';
		if (document.all) {
			document.getElementById ('buttonBarBG').style.display = 'none';
		}
	}

}

function MoveRowUp (whichLink) {
//--- Moves the row one level up in its parent

	if ((lastRepeater == null) || (lastRepeater.parentNode.childNodes == undefined) || (lastRepeater.parentNode.childNodes.length == undefined)) {
		return; // can't move, no children or already at the top
	}

	var repeaterParent = lastRepeater.parentNode;
	var sibling = undefined;
	var i = 0;

	while ((repeaterParent.childNodes[i]) && (repeaterParent.childNodes[i] != lastRepeater)) {
		if (repeaterParent.childNodes[i].nodeType == 1) {
			sibling = repeaterParent.childNodes[i];
		}
		i++;
	}

	if (sibling != undefined) {
		if ((sibling.className) && (sibling.className.indexOf ('repeater') != -1)) {
			var removed = repeaterParent.removeChild (lastRepeater); repeaterParent.insertBefore (removed, sibling);
		}
	}

}

function MoveRowDown (whichLink) {
//--- Moves the row one level down in its parent

	if ((lastRepeater == null) || (lastRepeater.parentNode.childNodes == undefined) || (lastRepeater.parentNode.childNodes.length == undefined)) {
		return; // can't move, no children or already at the bottom
	}

	var repeaterParent = lastRepeater.parentNode;
	var sibling = undefined;
	var i = (repeaterParent.childNodes.length - 1);

	while ((repeaterParent.childNodes[i]) && (repeaterParent.childNodes[i] != lastRepeater)) {
		if (repeaterParent.childNodes[i].nodeType == 1) {
			sibling = repeaterParent.childNodes[i];
		}
		i--;
	}

	if (sibling != undefined) {
		if ((sibling.className) && (sibling.className.indexOf ('repeater') != -1)) {
			var removed = repeaterParent.removeChild (lastRepeater); repeaterParent.insertBefore (removed, sibling.nextSibling);
		}
	}

}

function RepeaterMouseOff () {
//--- Handles mouse off events

	if (lastRepeaterTimeout != null) {
		clearTimeout (lastRepeaterTimeout);
	}

	lastRepeater.className = lastRepeater.className.toString ().replace (/ solid/g, '');
	lastRepeater = null;

	document.getElementById ('buttonBar').style.display = 'none';
	if (document.all) {
		document.getElementById ('buttonBarBG').style.display = 'none';
	}

}

function AssignEditor (div) {
//--- Assign an editor to the DIV

	div.onmouseover = function () { this.className += ' dashed'; };
	div.onmouseout = function () { this.className = this.className.replace (/ dashed/g, ''); };
	switch (div.className) {
		case 'editorTitle':		div.onclick = function () { lastSelected = this; Prompt ('Enter a new title value', unescape (escape (this.innerHTML).replace (/%u([0-9]+)/g, '&#x$1;')), 'Edit Text', '1'); return false; }; break;
		case 'editorBody':		div.onclick = function () { lastSelected = this; NewWindow ('smart-editor-mini.php', 'Edit Content', 900, 500, [this.innerHTML, this]); return false; }; break;
		case 'editorImage':		div.onclick = function () { lastSelected = this; NewWindow ('smart-editor-insert-image.php?', 'Edit Image', 600, (document.all ? 390 : 400), [this, window]); return false; }; break;
	}
	
}

function AssignRepeater (div) {
//--- Assign a repeater function to the DIV

	div.onmouseover = function () {
		if (lastRepeater != null) {
			RepeaterMouseOff ();
		}
		lastRepeater = this;
		lastRepeater.className += ' solid';
		tPos = GetObjPos (lastRepeater);
		tDimensions = GetObjSize (lastRepeater);
		document.getElementById ('buttonBar').style.left = tPos[0] + 'px';
		document.getElementById ('buttonBar').style.top = tPos[1] + tDimensions[1] + 'px';
		document.getElementById ('buttonBar').style.width = tDimensions[0] + 'px';
		if (document.all) {
			document.getElementById ('buttonBarBG').style.left = document.getElementById ('buttonBar').style.left;
			document.getElementById ('buttonBarBG').style.top = document.getElementById ('buttonBar').style.top;
			document.getElementById ('buttonBarBG').style.width = document.getElementById ('buttonBar').style.width;
		}
		repeatersCount = 0;
		repeaters = lastRepeater.parentNode.getElementsByTagName ('*');
		for (l = 0; l < repeaters.length; l++) {
			if (repeaters[l].className.indexOf ('repeater') != -1) {
				repeatersCount++;
			}
		}
		if (repeatersCount == 1) {
			document.getElementById ('buttonBar').getElementsByTagName ('img')[1].className += ' filterlite';
		} else {
			document.getElementById ('buttonBar').getElementsByTagName ('img')[1].className = document.getElementById ('buttonBar').getElementsByTagName ('img')[1].className.toString ().replace (/ filterlite/g, '');
		}
		document.getElementById ('buttonBar').style.display = 'block';
		if (document.all) {
			document.getElementById ('buttonBarBG').style.display = 'block';
		}
	};
	div.onmouseout = function () {
		lastRepeaterTimeout = setTimeout ("RepeaterMouseOff ()", 50);
	};

}

function AssignHandlers (divs) {
//--- Assign mouse and click handlers

	for (i = 0; i < divs.length; i++) {
		if (divs[i].className.indexOf ('editor') != -1) {
			AssignEditor (divs[i]);
		}
		if (divs[i].className.indexOf ('repeater') != -1) {
			AssignRepeater (divs[i]);
		}
	}

}

var lastSelected = null;
var lastRepeater = null;
var lastRepeaterTimeout = null;