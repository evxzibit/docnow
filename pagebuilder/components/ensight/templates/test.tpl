<script>
function CPPMResponse (Response) {
//--- Handles the response from CPPM.php and continues with the registration

	var ResponseSplit = Response.split (/&/);
	var ResponseAssoc = new Object ();
	for (i = 0; i < ResponseSplit.length; i++) {
		ResponseVar = ResponseSplit[i].split (/=/);
		ResponseAssoc[ResponseVar[0]] = ResponseVar[1];
	}

	whichForm = document.forms[0];

	if (ResponseAssoc['Error_NUM'] == 14) {
		alert ('Your password must be a minimum of 4 characters, and a maximum of 8 characters'); return false;
	} else
	if (ResponseAssoc['Error_NUM'] == 3) {
		alert ('The login you selected is unavailable. Please try again.'); return false;
	} else
	if (ResponseAssoc['Error_NUM'] == 0) {
		//--- Okay, let's continue
		whichForm.action = '/live/FPM.php';
		whichForm.target = '_self';
		whichForm.elements['_JS'].value = '';
		whichForm.elements['Session_ID'].value = ResponseAssoc['Session_ID'];
		whichForm.submit ();
	} else {
		alert ('We could not register you. Please try again.');
	}

}

function ReplaceSubmit () {
//--- Replaces the standard onsubmit form

	whichForm = document.forms[0];

	var isOkay = __ValidateForm (whichForm);
	if (isOkay == false) {
		return false;
	}

	//--- Check registration data
	if (whichForm.Eml.value == '') {
		alert ('Please select a login');
		whichForm.Eml.focus ();
		return false;
	}
	if (whichForm.Pwd.value == '') {
		alert ('Please select a password');
		whichForm.Pwd.focus ();
		return false;
	}
	if (whichForm.Pwd2.value == '')	{
		alert ('Please confirm your password choice');
		whichForm.Pwd2.focus ();
		return false;
	}
	if (whichForm.Pwd.value != whichForm.Pwd2.value) {
		alert ('Your password and confirmation password do not match.');
		whichForm.Pwd.focus ();
		return false;
	}

	//--- All okay, proceed to registration
	whichForm.action = '/live/CPPM.php';
	whichForm.target = 'RegFrame';
	whichForm.elements['_JS'].value = 'CPPMResponse'; // callback

}
</script>

<table border="0" cellpadding="5" cellspacing="0">
<tr>
	<td>Title</td>
	<td>{Title}</td>
</tr>
<tr>
	<td>First Name</td>
	<td>{First Name}</td>
</tr>
<tr>
	<td>Surname</td>
	<td>{Surname}</td>
</tr>
<tr>
	<td colspan="2"><hr size="1" /></td>
</tr>
<tr>
	<td>Login</td>
	<td><input type="text" name="Eml" /></td>
</tr>
<tr>
	<td>Password</td>
	<td><input type="password" name="Pwd" /></td>
</tr>
<tr>
	<td>Confirm Password</td>
	<td><input type="password" name="Pwd2" /></td>
</tr>
<tr>
	<td colspan="2"><hr size="1" /></td>
</tr>
</table>

<input type="hidden" name="_JS" value="" />
<input type="submit" value="Submit" name="SubmitButton" />

<iframe src="about:blank" style="display: none" name="RegFrame" id="RegFrame"></iframe>

<script>
document.forms[0].onsubmit = ReplaceSubmit;
</script>