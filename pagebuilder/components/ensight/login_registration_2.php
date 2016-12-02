<?
global $UserStatus, $Filename;
global $Session_ID, $Error_NUM, $From, $Dest_URL, $Eml, $Pwd;

if ($UserStatus == PROFILE_GUEST) {
?>
<!-- Login/Register Page 2 component -->
<form action="<? echo ($IncludePath ? ThisURL.ROOT_URL."/" : ROOT_URL."/"); ?>CPPM.php" method="post">
<?
if (($Error_NUM) && ($From == 'login-registration2')) {
	include_once ("modules/db-functions.php"); echo "<p><font color=\"red\">".RetrieveError ($Error_NUM, $Session_ID)."</font></p>";
}
?>
<table border="0" cellspacing="0" cellpadding="2" width="100%">
<tr>
	<td width="155" align="right">Your e-mail address: </td>
	<td><input type="text" size="30" name="Eml" value="<? echo $Eml; ?>" /></td>
</tr>
</table><br />

<table border="0" cellspacing="0" cellpadding="2" width="100%">
<tr>
	<td width="155" align="right">Type in a secret password: </td>
	<td><input type="password" size="20" name="Pwd" /></td>
</tr>
<tr>
	<td width="155" align="right">Type it in again: </td>
	<td><input type="password" size="20" name="Pwd2" /></td>
</tr>
<tr>
	<td width="155" align="right">&nbsp;</td>
	<td><input type="checkbox" name="RememberMe" value="1" /> Remember me on this computer</td>
</tr>
</table><br />

<table border="0" cellspacing="0" cellpadding="2" width="100%">
<tr>
<td width="155">&nbsp;</td>
<td><input type="submit" value="Sign up and create your profile" /></td>
</tr>
</table>

<?
if ($IncludePath) {
	echo RetrieveHiddenField ("Src_URL", "");
	echo RetrieveHiddenField ("Dest_URL", ($Dest_URL ? $Dest_URL : PEM));
} else {
	echo RetrieveHiddenField ("Src_URL",  ($Filename ? ROOT_URL."/".$Filename : HOME)."?From=login-registration2");
	echo RetrieveHiddenField ("Dest_URL", ($Dest_URL ? $Dest_URL : PEM));
}
PrintHiddenField ("Session_ID", $Session_ID);
?>
</form>
<?
} else {
?>
<form action="<? echo ($IncludePath ? ThisURL.ROOT_URL."/" : ROOT_URL."/"); ?>logout.php" method="post">
<input type="submit" value="Sign out" /><br />
<?
if ($IncludePath) {
	echo RetrieveHiddenField ("Src_URL", "");
	echo RetrieveHiddenField ("Dest_URL", ($Dest_URL ? $Dest_URL : HOME));
} else {
	echo RetrieveHiddenField ("Src_URL",  ($Filename ? ROOT_URL."/".$Filename : PHP)."?From=login-registration2");
	echo RetrieveHiddenField ("Dest_URL", ($Dest_URL ? $Dest_URL : HOME));
}
PrintHiddenField ("Session_ID", $Session_ID);
?>
</form>
<?
} // end if
?>