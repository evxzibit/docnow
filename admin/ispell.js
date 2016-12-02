//--- Ensight Source Code (ESC)
//--- First Created on Friday, January 12 2005 by John Ginsberg
//--- For Ensight 4

//--- Module Name: ispell.js
//--- Contains: None

//--- Modified by:
//--- Modified by:

//--- Description:
//--- Spell checking using the IESpell library - http://www.iespell.com

//--- Notice:
//--- This code is copyright (C) 2009, ENVENT Holdings (Pty) Ltd. All rights
//--- are reserved. Using this code in any shape or form requires permission
//--- from ENVENT Holdings (Pty) Ltd - http://www.ensight.co.uk

//----------------------------------------------------------------

function CheckSpelling () {
//--- Calls the spelling component

/*
	try {
	    var tempIESpell = new ActiveXObject("ieSpell.ieSpellExtension");
		if (tempIESpell.CheckAllLinkedDocuments2 (document, true)) {
			alert ('The spelling check is complete.');
		}
	} catch (exception) {
		alert ('In order to spell check this document, you will need to download and install IESpell. Click OK to open the IESpell website.'); window.open ("http://www.iespell.com/download.php", "Download");
	}
*/
	doSpell ({ctrl:'iEditor', lang:'en'});

}

