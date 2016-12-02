<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Update your subscription preferences</title>
	<style>
	body, td { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: black; background: white }
	</style>
</head>

<body topmargin="2" leftmargin="2" rightmargin="2" bottommargin="2" marginwidth="2" marginheight="2">

<form action="/live/subscribe.php" method="post" target="subscribeFrame">

<div style="border: 1px solid #666; width: 600px; margin: 20px auto;">

<? if ($Action == 'unsubscribe') { ?>

<table border="0" cellpadding="4" cellspacing="4" width="100%" id="subscription">
<tr>
	<td>
	<div align="justify"><b>You have been unsubscribed.</b><br />We're sorry to see you go. Would you give us some feedback on why you decided to leave?<br /><br />Made a mistake? You can <a href="/live/subscribe.php?action=subscribe&m=<? echo $Mail_ID; ?>&r=<? echo $Reference_ID; ?>&Session_ID=<? echo $Session_ID; ?>">re-subscribe in one click</a>.<hr size="1" /></div>
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
	<textarea name="comment" cols="32" rows="5" style="margin-bottom: 10px;"></textarea><br />
	</td>
</tr>
<tr>
	<td><input type="submit" value="Submit" />&nbsp;&nbsp;<input type="button" value="Close Window" onclick="window.close ()" /></td>
</tr>
</table>

<div id="thanks" style="display: none; padding: 8px;">Thank you for your feedback.</div>

<?
} else {
?>

<div style="padding: 8px;">
	You have been re-subscribed.<br /><br />Made a mistake? You can <a href="/live/subscribe.php?	action=unsubscribe&m=<? echo $Mail_ID; ?>&r=<? echo $Reference_ID; ?>&Session_ID=<? echo $Session_ID; ?>">unsubscribe in one click</a>.
</div>

<?
} // end if
?>

</div>

<?
echo RetrieveHiddenField ("action", "unsubscribe");
echo RetrieveHiddenField ("m", $Mail_ID);
echo RetrieveHiddenField ("r", $Reference_ID);
echo RetrieveHiddenField ("s", $Session_ID);
echo RetrieveHiddenField ("format", "iframe");
echo RetrieveHiddenField ("callback", "confirmSubscribe");
?>

</form>

<script>
function confirmSubscribe (response) {
//--- Confirms the action

	document.getElementById ('subscription').style.display = 'none';
	document.getElementById ('thanks').style.display = '';


}
</script>

<iframe style="display: none" name="subscribeFrame" id="subscribeFrame" src="about:blank"></iframe>

</body>
</html>