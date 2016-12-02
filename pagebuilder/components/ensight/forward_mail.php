<?
include_once ("../../../modules/DB.php");
include_once ("../../../modules/connect.php");
include_once ("../../../modules/utils.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Forward this mail to...</title>
	<style>
	td { font-family: Verdana; font-size: 12px; color: black; background: white }
	</style>
</head>

<body topmargin="2" leftmargin="2" rightmargin="2" bottommargin="2" marginwidth="2" marginheight="2" onload="document.forms[0].Recipient.focus ()">

<form action="../../../sendmail.php" method="post">

<table border="0" cellpadding="4" cellspacing="4" width="550">
<tr>
	<td colspan="2">
	<div align="justify"><b>Forward this mail to a friend. Enter their details below and we'll send them a copy with a personal message from you!</b></div>
	</td>
</tr>
<tr valign="top">
	<td>
	E-mail this mail to:<br />
	<input type="text" name="Recipient" size="40" tabindex="1" /><br />
	<small><div align="justify">To send to more than one person, separate addresses with a comma.</div></small>
	</td>
	<td rowspan="4">
	Message (optional)<br />
	<textarea name="Comment" cols="32" rows="5" tabindex="4" style="margin-bottom: 10px;"></textarea><br />
	<?
	if (EnableCAPTCHA == '1') {
		echo "Please enter the 6-letter code displayed in this image:<br /><img id=\"CAPTCHA_Image\" src=\"".ROOT_URL."/pagebuilder/components/ensight/load-captcha.php?Key=".$Item_ID."&Session_ID=".$Session_ID."&Rand=".GetRandom (999999)."\" width=\"150\" height=\"70\" border=\"1\" alt=\"\" /><br /><a href=\"#\" onclick=\"document.getElementById ('CAPTCHA_Image').src = '".ROOT_URL."/pagebuilder/components/ensight/load-captcha.php?Key=".$Item_ID."&Session_ID=".$Session_ID."&Rand=' + (Math.random () * 999999); return false\"><small>Generate another random image</small></a><br /><input type=\"text\" name=\"CAPTCHA\" style=\"width: 150px\" />\n";
	}
	?>
	</td>
</tr>
<tr>
	<td>
	Your name:<br />
	<input type="text" name="MyName" size="40" tabindex="2" />
	</td>
</tr>
<tr>
	<td>
	Your e-mail address:<br />
	<input type="text" name="MyEml" size="40" tabindex="3" />
	</td>
</tr>
<tr>
	<td>
	<small><div align="justify">We respect your privacy. This form is only used to e-mail a copy of the mail to your designated recipients. The e-mail addresses entered will not be saved, or used in any way other than for the specified purpose.</div></small>
	</td>
</tr>
<tr>
	<td colspan="2"><input type="submit" value="Submit" />&nbsp;&nbsp;<input type="button" value="Close Window" onclick="window.close ()" /></td>
</tr>
</table>

<?
echo RetrieveHiddenField ("Mail_ID", htmlspecialchars ($Mail_ID));
echo RetrieveHiddenField ("Session_ID", htmlspecialchars ($Session_ID));
echo RetrieveHiddenField ("Dest_URL", ROOT_URL."/pagebuilder/components/ensight/forward_mail_complete.php");
echo RetrieveHiddenField ("Format", "HTML");
?>

</form>

</body>
</html>
