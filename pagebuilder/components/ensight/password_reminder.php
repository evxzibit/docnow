<?
global $UserStatus, $Filename;
global $Session_ID, $Error_NUM, $From, $Dest_URL;
?>
<!-- Password reminder component -->
<form action="<? echo ($IncludePath ? ThisURL.ROOT_URL."/" : ROOT_URL."/"); ?>reminder.php" method="post">
<?
if (($Error_NUM) && ($From == 'reminder')) {
	include_once ("modules/db-functions.php"); echo "<p><font color=\"red\">".RetrieveError ($Error_NUM, $Session_ID)."</font></p>";
}
?>
<table border="0" cellspacing="0" cellpadding="2" width="100%">
<tr>
	<td width="100" align="right">E-mail address: </td>
	<td><input type="text" size="30" name="Eml" /></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="Send my password" /></td>
</tr>
</table>

<?
if ($IncludePath) {
	echo RetrieveHiddenField ("Src_URL", "");
	echo RetrieveHiddenField ("Dest_URL", ($Dest_URL ? $Dest_URL : PHP));
} else {
	echo RetrieveHiddenField ("Src_URL",  ($Filename ? ROOT_URL."/".$Filename : RLM)."?From=reminder");
	echo RetrieveHiddenField ("Dest_URL", ($Dest_URL ? $Dest_URL : PHP));
}
PrintHiddenField ("Session_ID", $Session_ID);
?>
</form>