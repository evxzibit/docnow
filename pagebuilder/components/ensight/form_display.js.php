<?
include_once ("../../../modules/connect.php");
?>
function __ShowThanks (Response) {

	var ResponseSplit = Response.split (/&/);
	var ResponseAssoc = new Object ();
	for (i = 0; i < ResponseSplit.length; i++) {
		ResponseVar = ResponseSplit[i].split (/=/);
		ResponseAssoc[ResponseVar[0]] = ResponseVar[1];
	}

	if ((ResponseAssoc['Error_NUM'] == 12)) {
		alert ('Please complete all mandatory fields.');
		document.getElementById ('dynamicForm' + ResponseAssoc['Item_ID']).elements['SubmitButton'].disabled = false;
		return false;
	} else
	if ((ResponseAssoc['Error_NUM'] == 27)) {
		alert ('Please try the CAPTCHA test again.');
		document.getElementById ('dynamicForm' + ResponseAssoc['Item_ID']).elements['SubmitButton'].disabled = false;
		return false;
	} else
	if ((ResponseAssoc['Error_NUM'] == 0) || (ResponseAssoc['Error_NUM'] == null)) {
		//--- Okay, let's continue
		document.getElementById ('dynamicForm' + ResponseAssoc['Item_ID'] + 'Struct').style.display = 'none';
		document.getElementById ('dynamicForm' + ResponseAssoc['Item_ID'] + 'Thanks').style.display = '';
	} else {
		alert ('Something went wrong, please try again.');
	}

}

function __ValidateField (e, value, validationType) {
//--- Provides general field validation for Ensight forms

	var allLetters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	var allNumbers = "1234567890";

	var isIgnore = false;
	var isNumber = false;
	var isLetter = false;
	var isDecimalNumber = false;

	var key = (e.keyCode ? e.keyCode : e.which);

	isNumber = !((key > 31) && ((key < 48) || (key > 57)));
	isLetter = !((key > 31) && ((key < 65) || (key > 90)) && ((key < 97) || (key > 122))) || (key == 32) || (key == 222);
	isDecimalNumber = !((key > 31) && ((key < 48) || (key > 57))) || (key == 46);

	switch (validationType) {
		case '1':	//--- Numbers only
					if (isNumber) {
						return true;
					} else {
						if (e.preventDefault) { e.preventDefault (); } else { e.returnValue = false; } return false;
					}
					break;

		case '2':	//--- Decimal numbers only
					if (isDecimalNumber) {
						return true;
					} else {
						if (e.preventDefault) { e.preventDefault (); } else { e.returnValue = false; } return false;
					}
					break;

		case '3':	//--- Letters only
					if (isLetter) {
						return true;
					} else {
						if (e.preventDefault) { e.preventDefault (); } else { e.returnValue = false; } return false;
					}
					break;

		case '4':	//--- Numbers and letters only
					if ((isNumber) || (isLetter)) {
						return true;
					} else {
						if (e.preventDefault) { e.preventDefault (); } else { e.returnValue = false; } return false;
					}
					break;
	}

}

function __ValidateForm (whichForm) {
//--- Validates the form itself, ensures all mandatory fields are submitted and that email and mobile entries are valid

	var eExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
	var mExp = /^[+0-9]{10,14}$/;

	for (i = 0; i < whichForm.length; i++) {

		if ((whichForm.elements[i].type == 'hidden') && (whichForm.elements[i].name.substr (0, 8) == 'Validate')) {

			ElementToLocate = <? echo ((defined ("EnableProfiling2_0")) || ($_SERVER['QUERY_STRING']) ? '' : "'Form' + "); ?>whichForm.elements[i].name.substr (8, whichForm.elements[i].name.length - 8);
			ValidateMessage = whichForm.elements[i].value.split ('|');

			isValid = false;

			//--- Special cases
			if ((!whichForm.elements[ElementToLocate]) && (whichForm.elements[ElementToLocate + '_isset'])) {
				//--- Checkbox groups
				if ((whichForm.elements[ElementToLocate + '[]']) && (whichForm.elements[ElementToLocate + '[]'].length)) {
					for (j = 0; j < whichForm.elements[ElementToLocate + '[]'].length; j++) {
						if (whichForm.elements[ElementToLocate + '[]'][j].checked) {
							isValid = true;
						}
					}
				} else
				//--- Date entry fields
				if ((whichForm.elements[ElementToLocate + '_Day']) && (whichForm.elements[ElementToLocate + '_Month']) && (whichForm.elements[ElementToLocate + '_Year'])) {
					isValid = (whichForm.elements[ElementToLocate + '_Day'].value != '') && (whichForm.elements[ElementToLocate + '_Month'].value != '') && (whichForm.elements[ElementToLocate + '_Year'].value != '');
				}
				if ((!isValid) && (ValidateMessage[1])) {
					alert (ValidateMessage[1]); return false;
				}
			}

			if ((!whichForm.elements[ElementToLocate]) || (whichForm.elements[ElementToLocate].disabled == true)) {
				continue;
			}

			switch (whichForm.elements[ElementToLocate].type) {
				case 'text':		
				case 'email':		
				case 'tel':			isValid = whichForm.elements[ElementToLocate].value != '';
									ValidateSize = ValidateMessage[2].split (',');
									if ((ValidateSize[1]) && (parseInt (ValidateSize[1]) > 0) && (whichForm.elements[ElementToLocate].value) && (whichForm.elements[ElementToLocate].value.length > parseInt (ValidateSize[1]))) { alert ('Please enter a value with maximum ' + parseInt (ValidateSize[1]) + ' character(s)'); whichForm.elements[ElementToLocate].focus (); return false; }
									if ((ValidateSize[2]) && (parseInt (ValidateSize[2]) > 0) && (whichForm.elements[ElementToLocate].value) && (whichForm.elements[ElementToLocate].value.length < parseInt (ValidateSize[2]))) { alert ('Please enter a value with minimum ' + parseInt (ValidateSize[2]) + ' character(s)'); whichForm.elements[ElementToLocate].focus (); return false; }
									break;
				case 'password':	isValid = whichForm.elements[ElementToLocate].value != '';
									ValidateSize = ValidateMessage[2].split (',');
									if ((ValidateSize[1]) && (parseInt (ValidateSize[1]) > 0) && (whichForm.elements[ElementToLocate].value) && (whichForm.elements[ElementToLocate].value.length > parseInt (ValidateSize[1]))) { alert ('Please enter a value with maximum ' + parseInt (ValidateSize[1]) + ' character(s)'); whichForm.elements[ElementToLocate].focus (); return false; }
									if ((ValidateSize[2]) && (parseInt (ValidateSize[2]) > 0) && (whichForm.elements[ElementToLocate].value) && (whichForm.elements[ElementToLocate].value.length < parseInt (ValidateSize[2]))) { alert ('Please enter a value with minimum ' + parseInt (ValidateSize[2]) + ' character(s)'); whichForm.elements[ElementToLocate].focus (); return false; }
									break;
				case 'textarea':	isValid = whichForm.elements[ElementToLocate].value != ''; break;
				case 'hidden':		isValid = whichForm.elements[ElementToLocate].value != ''; break; // test for date fields
				case 'file':		isValid = whichForm.elements[ElementToLocate].value != ''; break;
				case 'checkbox':	isValid = whichForm.elements[ElementToLocate].checked == true; break;
				case 'select-one':	isValid = whichForm.elements[ElementToLocate].options[whichForm.elements[ElementToLocate].selectedIndex].value != ''; break;
				case 'select-multiple':	
									if (whichForm.elements[ElementToLocate].length) {
										for (j = 0; j < whichForm.elements[ElementToLocate].length; j++) {
											if (whichForm.elements[ElementToLocate][j].selected) {
												isValid = true;
											}
										}
									}
									break;
				case 'radio':		
				default:			if (whichForm.elements[ElementToLocate].length) {
										for (j = 0; j < whichForm.elements[ElementToLocate].length; j++) {
											if (whichForm.elements[ElementToLocate][j].checked) {
												isValid = true;
											}
										}
									}
									break;
			}
			if ((!isValid) && (ValidateMessage[1])) {
				alert (ValidateMessage[1]); if (whichForm.elements[ElementToLocate].type != 'hidden') { try { whichForm.elements[ElementToLocate].focus (); } catch (e) { } } return false;
			}

			//--- If we make it past the first test, look for additional checks
			switch (ValidateMessage[0]) {
				case '5':		//--- Email address check
								if ((whichForm.elements[ElementToLocate].value) && (!whichForm.elements[ElementToLocate].value.match (eExp))) {
									alert ('The email address format is incorrect.');
									whichForm.elements[ElementToLocate].focus ();
									return false;
								}
								break;
				case '6':		//--- Mobile telephone check
								if ((whichForm.elements[ElementToLocate].value) && (!whichForm.elements[ElementToLocate].value.match (mExp))) {
									alert ('The mobile number should be numeric, and at least 10 digits long.');
									whichForm.elements[ElementToLocate].focus ();
									return false;
								}
								break;
			}

		}

	}

	if ((whichForm.elements['CAPTCHA']) && (whichForm.elements['CAPTCHA'].value == '')) {
		alert ('Please enter the 6-letter code displayed on the page.');
		whichForm.elements['CAPTCHA'].focus ();
		return false;
	}

	return true;

}

function __InsertMsgReferer (id) {
//--- Adds a parameter

	if ((document.location.hash) && (!isNaN (parseInt (document.location.hash.toString ().substr (1))))) {
		var h = document.createElement ('INPUT');
		h.type = 'hidden';
		h.name = 'm';
		h.value = parseInt (document.location.hash.toString ().substr (1));
		document.getElementById (id).appendChild (h);
	}

}