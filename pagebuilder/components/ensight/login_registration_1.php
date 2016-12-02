<?
global $UserStatus, $Filename;
global $Session_ID, $Error_NUM, $From, $Dest_URL;

if ($UserStatus < PROFILE_REGISTERED) {
?>
<!-- Login/Register Page 1 component -->
<form action="<? echo ($IncludePath ? ThisURL.ROOT_URL."/" : ROOT_URL."/"); ?>LSM.php" method="post">
<?
if (($Error_NUM) && ($From == 'login-registration1')) {
	include_once ("modules/db-functions.php"); echo "<p><font color=\"red\">".RetrieveError ($Error_NUM, $Session_ID)."</font></p>";
}
?>
<table border="0" cellspacing="0" cellpadding="2" width="100%">
<tr>
	<td width="130" align="right">E-mail address: </td>
	<td><input type="text" size="30" name="Eml"></td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="2" width="100%">
<tr>
	<td width="20" align="right"><input type="radio" name="option" onclick="this.form.action = '<? echo CPCM; ?>'" />&nbsp;</td><td>I want to sign up for a new account</td>
</tr>
<tr>
	<td width="20" align="right"><input type="radio" name="option" onclick="this.form.action = 'LSM.php'" checked="checked" />&nbsp;</td><td>I already have an account, and want to sign in:</td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="2" width="100%">
<tr>
	<td width="130" align="right">Password: </td>
	<td><input type="password" size="20" name="Pwd" /></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><a href="<? echo PRP; ?>?Session_ID=<? echo $Session_ID; ?>">I've forgotten my password</a></td>
</tr>
</table><br />

<table border="0" cellspacing="0" cellpadding="2" width="100%">
<tr>
	<td width="20" align="right">&nbsp;</td><td><input type="submit" value="Sign in and continue" />&nbsp;&nbsp;<input type="button" value="Sign in and edit your profile" onclick="this.form.Dest_URL.value = '<? echo PEM; ?>'; this.form.submit ()" /></td>
</tr>
</table>

<?
if ($IncludePath) {
	echo RetrieveHiddenField ("Src_URL", "");
	echo RetrieveHiddenField ("Dest_URL", ($Dest_URL ? $Dest_URL : PHP));
} else {
	echo RetrieveHiddenField ("Src_URL",  ($Filename ? ROOT_URL."/".$Filename : HOME)."?From=login-registration1");
	echo RetrieveHiddenField ("Dest_URL", ($Dest_URL ? $Dest_URL : PHP));
}
PrintHiddenField ("Session_ID", $Session_ID);
?>
</form>
<?
} else {
?>
You are signed in. Click <a href="<? echo ($IncludePath ? ThisURL : "").PEM; ?>?Session_ID=<? echo $Session_ID; ?>">here</a> to edit your profile.

<form action="<? echo ($IncludePath ? ThisURL.ROOT_URL."/" : ROOT_URL."/"); ?>logout.php" method="post">
<input type="submit" value="Sign out" /><br />
<?
if ($IncludePath) {
	echo RetrieveHiddenField ("Src_URL", "");
	echo RetrieveHiddenField ("Dest_URL", ($Dest_URL ? $Dest_URL : HOME));
} else {
	echo RetrieveHiddenField ("Src_URL",  ($Filename ? ROOT_URL."/".$Filename : PHP)."?From=login-registration1");
	echo RetrieveHiddenField ("Dest_URL", ($Dest_URL ? $Dest_URL : HOME));
}
PrintHiddenField ("Session_ID", $Session_ID);
?>
</form>
<?
} // end if
?>