function ValidateName (Name) {
//--- Validates an item code or category name within Ensight

	if (Name == '') {
		alert ('Please enter a name for the folder'); return false;
	}

	if ((Name.indexOf ('\\') != -1) ||
		(Name.indexOf ('/') != -1) || 
		(Name.indexOf (':') != -1) || 
		(Name.indexOf ('*') != -1) || 
		(Name.indexOf ('?') != -1) || 
		(Name.indexOf ('"') != -1) || 
		(Name.indexOf ('<') != -1) || 
		(Name.indexOf ('>') != -1) || (Name.indexOf ('|') != -1)) {
		alert ('A folder name cannot include any of the following characters:\n\t\\ / : * ? " < > |'); return false;
	}

	return true;

}

function Validate (which) {

	if ((which.BaseCategory.value == '') || (which.BaseCategory.value == '-99')) {
		alert ('Please select a folder'); which.CategoryDescription.focus (); return false;
	}

	if (which.Description.value == '') {
		alert ('Please enter a name for this folder'); which.Description.focus (); return false;
	}

	if (!ValidateName (which.Description.value)) {
		which.Description.focus (); return false;
	}

	return true;

}

function MoveSelected (From, To, KillOriginal) {
//--- Moves the selected options from the From select to the To select.

	//--- Variables	
	var i, j, error = 0;

	for (i = 0; i < From.length; i++) {

		error = 0;
		if (From[i].selected) {
			//--- Check that it doesn't exist in To
			for (j = 0; j < To.length; j++) {
				if (From[i].value == To[j].value) {
					error = 1;
				} 
			}
			if (!error) {
				NewOption = new Option (From[i].text, From[i].value, false, true);
				To.options[To.length] = NewOption;
				if (KillOriginal) {
					From[i] = null;
					i--;
				}
			} 				
		} // end if

	} // end for

}

function KillSelected (From) {
//--- Deletes the selected options from the From select.

	//--- Variables	
	var i;

	for (i = 0; i < From.length; i++) {
		if (From[i].selected) { From[i] = null; i--; }
	} // end for

}

function SaveSelected (From, To) {
//--- Saves the values from a select element (From) into a hidden field (To)

	TValue = '';

	for (i = 0; i < From.length; i++) {
		if (i < From.length-1) {
			TValue += From.options[i].value + ',';
		} else {
			TValue += From.options[i].value;
		}
	}

	To.value = TValue;

}

function NewWindow (URL, Title, Width, Height) {
//--- Opens a new window with the specifics in the center of the screen

	window.parent.modalWindows.openWindow (URL, Title, (parseInt (Width) + 5), (parseInt (Height) + 40), window);

}