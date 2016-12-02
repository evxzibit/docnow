<?
$CWD = getcwd ();

if (strpos ($CWD, 'pagebuilder/components') !== false) {
	include_once ("../../../modules/DB.php");
	include_once ("../../../modules/connect.php");
	include_once ("../../../modules/utils.php");
}

global $Mail_ID, $Reference_ID, $Session_ID;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Unsubscribe</title>
	<style>
	td { font-family: Verdana; font-size: 12px; color: black; background: white }
	</style>
</head>

<body topmargin="2" leftmargin="2" rightmargin="2" bottommargin="2" marginwidth="2" marginheight="2" onload="document.forms[0].Recipient.focus ()">

<form action="<? echo ROOT_URL; ?>/subscribe.php" method="post">

<table border="0" cellpadding="4" cellspacing="4" width="550">
<tr>
	<td>
	<div align="justify"><b>We're sorry to see you go. Please complete the form below to confirm your request to unsubscribe, and we'll remove you from our list immediately.</b></div>
	</td>
</tr>
<tr>
	<td>
	Please select a reason:<br />
	<select name="reason">
	<option>-----</option>
	<option>You send me too many emails</option>
	<option>I'm not interested anymore</option>
	<option>Your emails are too commercial in nature</option>
	<option>I never subscribed in the first place</option>
	</select>
	</td>
</tr>
<tr valign="top">
	<td>
	Message (optional)<br />
	<textarea name="comment" cols="32" rows="5" tabindex="4" style="margin-bottom: 10px;"></textarea><br />
	</td>
</tr>
<tr>
	<td><input type="submit" value="Submit" />&nbsp;&nbsp;<input type="button" value="Close Window" onclick="window.close ()" /></td>
</tr>
</table>

<?
echo RetrieveHiddenField ("action", "unsubscribe");
echo RetrieveHiddenField ("m", $Mail_ID);
echo RetrieveHiddenField ("r", $Reference_ID);
echo RetrieveHiddenField ("s", $Session_ID);
echo RetrieveHiddenField ("next", ROOT_URL."/pagebuilder/components/ensight/unsubscribe_complete.php");
?>

</form>

</body>
</html>