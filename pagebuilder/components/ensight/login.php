<?
global $UserStatus, $Filename;
global $Session_ID, $Error_NUM, $From, $Dest_URL;

if ($UserStatus == PROFILE_GUEST) {
?>
<!-- Login component -->
<form action="<? echo ($IncludePath ? ThisURL.ROOT_URL."/" : ROOT_URL."/"); ?>LSM.php" method="post">
<?
if (($Error_NUM) && ($From == 'login')) {
	include_once ("modules/db-functions.php"); echo "<p><font color=\"red\">".RetrieveError ($Error_NUM, $Session_ID)."</font></p>";
}
?>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>Email:&nbsp;</td>
	<td><input type="text" name="Eml" size="7" /></td>
</tr>
<tr>
	<td>Password:&nbsp;</td>
	<td><input type="password" name="Pwd" size="7" /></td>
</tr>
<tr>
	<td colspan="2"><input type="checkbox" name="RememberMe" value="1" /> Remember me</td>
</tr>
</table><br />
<input type="submit" value="Sign in &gt;&gt;" /><br />
<?
if ($IncludePath) {
	echo RetrieveHiddenField ("Src_URL", "");
	echo RetrieveHiddenField ("Dest_URL", ($Dest_URL ? $Dest_URL : PHP));
} else {
	echo RetrieveHiddenField ("Src_URL",  ($Filename ? ROOT_URL."/".$Filename : HOME)."?From=login");
	echo RetrieveHiddenField ("Dest_URL", ($Dest_URL ? $Dest_URL : PHP));
}
PrintHiddenField ("Session_ID", $Session_ID);
?>
</form>
<?
} else {
?>
You are signed in. Click <a href="<? echo ($IncludePath ? ThisURL : ROOT_URL."/").PEM; ?>?Session_ID=<? echo $Session_ID; ?>">here</a> to edit your profile.

<form action="<? echo ($IncludePath ? ThisURL.ROOT_URL."/" : ""); ?>logout.php" method="post">
<input type="submit" value="Sign out" /><br />
<?
if ($IncludePath) {
	echo RetrieveHiddenField ("Src_URL", "");
	echo RetrieveHiddenField ("Dest_URL", ($Dest_URL ? $Dest_URL : HOME));
} else {
	echo RetrieveHiddenField ("Src_URL",  ($Filename ? ROOT_URL."/".$Filename : PHP)."?From=login");
	echo RetrieveHiddenField ("Dest_URL", ($Dest_URL ? $Dest_URL : HOME));
}
PrintHiddenField ("Session_ID", $Session_ID);
?>
</form>
<?
} // end if
?>