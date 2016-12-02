<?
global $UserStatus, $Filename;
global $Session_ID, $Error_NUM, $From;

if ($UserStatus > PROFILE_GUEST) {
?>
<!-- Update login details component -->
<form action="<? echo ($IncludePath ? ThisURL.ROOT_URL."/" : ROOT_URL."/"); ?>PPM.php" method="post">
<?
if (($Error_NUM) && ($From == 'update-login')) {
	include_once ("modules/db-functions.php"); echo "<p><font color=\"red\">".RetrieveError ($Error_NUM, $Session_ID)."</font></p>";
}
?>
<table border="0" cellspacing="0" cellpadding="2" width="100%">
<tr>
	<td width="155" align="right">New e-mail address: </td>
	<td><input type="text" size="30" name="Eml" value="<? echo $Eml; ?>" /></td>
</tr>
</table><br />

<table border="0" cellspacing="0" cellpadding="2" width="100%">
<tr>
	<td width="155" align="right">Your current password: </td>
	<td><input type="password" size="20" name="Pwd" /></td>
</tr>
<tr>
	<td width="155" align="right">New password: </td>
	<td><input type="password" size="20" name="Pwd2" /></td>
</tr>
</table><br />

<table border="0" cellspacing="0" cellpadding="2" width="100%">
<tr>
	<td width="155"></td>
	<td><input type="submit" value="Update your account login" /></td>
</tr>
</table>

<?
if ($IncludePath) {
	echo RetrieveHiddenField ("Src_URL", "");
	echo RetrieveHiddenField ("Dest_URL", $Dest_URL);
} else {
	echo RetrieveHiddenField ("Src_URL", ($Filename ? ROOT_URL."/".$Filename : PHP)."?From=update-login");
	echo RetrieveHiddenField ("Dest_URL", $Dest_URL);
}
PrintHiddenField ("Action", "L");
PrintHiddenField ("Session_ID", $Session_ID);
?>
</form>
<?
} else {
?>
You are not signed in. Click <a href="<? echo ($IncludePath ? ThisURL : ROOT_URL."/").RLM; ?>?Session_ID=<? echo $Session_ID; ?>">here</a> to sign in.
<?
} // end if
?>
