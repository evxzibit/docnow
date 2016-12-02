<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>You have been <? echo ($_REQUEST['Action'] == 'unsubscribe' ? "unsubscribed" : "re-subscribed"); ?></title>
	<style>
	td { font-family: Verdana; font-size: 12px; color: black; background: white }
	</style>
</head>

<body topmargin="2" leftmargin="2" rightmargin="2" bottommargin="2" marginwidth="2" marginheight="2">

<form>

<table border="0" cellpadding="0" cellspacing="4" width="550">
<tr>
	<td><div align="justify"><? echo ($_REQUEST['Action'] == 'unsubscribe' ? "We have removed you from our mailing list. If you unsubscribed in error, <a href=\"/live/subscribe.php?action=subscribe&m=".$_REQUEST['Mail_ID']."&r=".$_REQUEST['Reference_ID']."&next=".urlencode ($_SERVER['PHP_SELF'])."&Session_ID=".$Session_ID."\">click here to re-subscribe</a>." : "<b>Success!</b> We have re-added you to our mailing list. You can unsubscribe at any time by clicking on a link in any email, or by <a href=\"/live/pagebuilder/components/ensight/unsubscribe.php?Mail_ID=".$_REQUEST['Mail_ID']."&Reference_ID=".$_REQUEST['Reference_ID']."&Session_ID=".$Session_ID."\">following this link</a>."); ?></div><br /></td>
</tr>
<tr>
	<td><center><input type="button" value="Close Window" onclick="window.close ()" /></center></td>
</tr>
</table>

</form>

</body>
</html>
