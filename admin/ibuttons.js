//--- Ensight Source Code (ESC)
//--- First Created on Wednesday, March 26 2003 by John Ginsberg
//--- For Ensight

//--- Module Name: ibuttons.js
//--- Contains: None

//--- Modified by:
//--- Modified by:

//--- Description:
//--- Button rendering and support engine

//--- Notice:
//--- This code is copyright (C) 2003, ENVENT Holdings (Pty) Ltd. All rights
//--- are reserved. Using this code in any shape or form requires permission
//--- from ENVENT Holdings (Pty) Ltd - http://www.envent.co.za

//----------------------------------------------------------------

//--- Definitions
buttonImages = Array ();
CurrentTab = 0; //--- Set this to whichever tab needs to show first

//--- Custom tabs
HideCustomTab = Array ();	//--- Configure this to hide a tab if necessary
ShowCustomTab = Array ();	//--- Configure this to show a tab if necessary

function CreateButton (standardIcon, selectedIcon, alt, width, height, callBack) {
//--- Creates a button array

	tButton = Array ();
	tButton['standard'] = standardIcon;
	tButton['selected'] = selectedIcon;
	tButton['alt'] = alt;
	tButton['width'] = width;
	tButton['height'] = height;
	tButton['callBack'] = (callBack ? callBack : null);

	return tButton;

}

function RetrieveButton (tab) {
//--- Creates a button using JavaScript

	if (!buttonImages[tab]) {
		return;
	}
	return "<img src=\"" + buttonImages[tab]['standard'] + "\" width=\"" + buttonImages[tab]['width'] + "\" height=\"" + buttonImages[tab]['height'] + "\" border=\"0\" title=\"" + buttonImages[tab]['alt'] + "\" hspace=\"1\" class=\"menu\" onmouseover=\"ToggleButton (this, 'over', " + tab + ", null)\" onmouseout=\"ToggleButton (this, 'out', " + tab + ", null)\" onmousedown=\"ToggleButton (this, 'down', " + tab + ", " + (buttonImages[tab]['callBack'] ? buttonImages[tab]['callBack'] : "null") + ")\" id=\"button" + tab + "\" style=\"cursor: pointer\" />\n";

}

function PrintButton (tab) {
//--- Creates a button using JavaScript

	document.write (RetrieveButton (tab));

}

function RetrieveMenuBar () {
//--- Returns the complete menu bar for display anywhere

	HTML = '';

	for (i = 0; i < buttonImages.length; i++) {
		HTML += RetrieveButton (i);
	}

	return HTML;

}

function PrintMenuBar () {
//--- Displays the complete menu bar on the left

	document.write (RetrieveMenuBar ());

}

function RetrieveHorizontalMenuBar (TableClass) {
//--- Returns a horizontal version of the menu bar for display

	HTML  = '<table border="0" cellpadding="0" cellspacing="0" class="' + TableClass + '">';
	HTML += '<tr valign="middle">';

	for (i = 0; i < buttonImages.length; i++) {
		HTML += '<td align="center">' + RetrieveButton (i) + '</td>';
	}

	HTML += '</tr>';
	HTML += '</table>';

	return HTML;

}

function PrintHorizontalMenuBar (TableClass) {
//--- Displays a horizontal version of the menu bar for display

	document.write (RetrieveHorizontalMenuBar (TableClass));

}

function ToggleButton (which, action, show, callBack) {
//--- Flips a button

	switch (action) {
		case 'over':	which.style.borderColor = '#0A246A'; which.style.backgroundColor = '#B6BDD2'; break;
		case 'out':		which.style.borderColor = '#DBD8D1'; which.style.backgroundColor = '#DBD8D1'; break;
		case 'down':	if (!document.getElementById ('tab' + show)) {
							alert ('Tab #' + show + ' does not exist'); return;
						}
						if (document.getElementById ('tab' + CurrentTab)) {
							document.getElementById ('tab' + CurrentTab).style.visibility = 'hidden';
						}
						//--- Reset current button
						document.getElementById ('button' + CurrentTab).src = buttonImages[CurrentTab]['standard'];
						document.getElementById ('button' + CurrentTab).style.borderStyle = 'solid';
						document.getElementById ('button' + CurrentTab).style.borderColor = '#dbd8d1';
						//--- Select new button and display associated tab
						CurrentTab = show;
						document.getElementById ('tab' + show).style.visibility = 'visible';
						which.src = buttonImages[show]['selected'];
						which.style.borderStyle = 'inset';
						which.style.borderColor = 'white';
						//--- Hide custom
						if (HideCustomTab[CurrentTab]) {
							document.getElementById (HideCustomTab[CurrentTab]).style.visibility = 'hidden';
						}
						//--- Show custom
						if (ShowCustomTab[CurrentTab]) {
							document.getElementById (ShowCustomTab[CurrentTab]).style.visibility = 'visible';
						}
						if (callBack != null) {
							try { eval (callBack)(); } catch (e) { }
						}
						break;
	}

}

function ToggleNextButton () {
//--- Toggles the next button in the sequence

	NextTab = ((CurrentTab + 1) > (buttonImages.length - 1) ? 0 : (CurrentTab + 1));

	if (!document.getElementById ('button' + NextTab)) {
		return;
	}
	document.getElementById ('button' + NextTab).onmousedown ();

}

function TogglePrevButton () {
//--- Toggles the previous button in the sequence

	NextTab = ((CurrentTab - 1) < 0 ? (buttonImages.length - 1) : (CurrentTab - 1));

	if (!document.getElementById ('button' + NextTab)) {
		return;
	}
	document.getElementById ('button' + NextTab).onmousedown ();

}

function LoadTab () {
//--- Loads the page and selects the default tab

	if (CurrentTab > buttonImages.length - 1) {
		alert ('Cannot load tab #' + CurrentTab); CurrentTab = 0; return;
	}

	ToggleButton (document.getElementById ('button' + CurrentTab), 'down', CurrentTab, buttonImages[CurrentTab]['callBack']);
	if (document.getElementById ('tab' + CurrentTab)) {
		document.getElementById ('tab' + CurrentTab).style.visibility = 'visible';
	}

}

function ButtonKeyDown (e) {
//--- Handles key presses

	var key = (e.which ? e.which : e.keyCode);
	if (key == 27) {
		if (e.preventDefault) { e.preventDefault (); } else { window.event.returnValue = false; } window.parent.CloseWindow (); return;
	}

	if ((e.ctrlKey)) {
		switch (key) {
			case 9:		//--- Ctrl + (Shift) + Tab
						if (e.shiftKey) {
							TogglePrevButton ();
						} else {
							ToggleNextButton ();
						}
						break;
		}
	}

}