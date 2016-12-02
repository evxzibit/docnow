<?
include_once ("modules/connect.php");
include_once ("modules/utils.php");

global $UploadFile, $UploadFile_name, $UploadFile_current, $PathToFile, $Width, $Height, $AutoPlay, $AutoBuffer, $BufferTime;

//--- Setup
$TIncludeFile = "media/video-config.php";
$Container_ID = $Container['Container_ID'];

if (isset ($UploadFile)) {

	if ($PathToFile) {
		$UploadFile_name = ($PathToFile);
	} else {
		CopyUniqueFilename ($UploadFile, $UploadFile_name, CONTENT_FILES, $UploadFile_current);
	}

	$AutoPlay = ($AutoPlay ? "true" : "false");
	$AutoBuffer = ($AutoBuffer ? "true" : "false");

	//--- Save to database or into form field
	UpdateContainerParameters ($Container_ID, "$UploadFile_name|$Width|$Height|$AutoPlay|$AutoBuffer|$BufferTime"); die ("<html><body><script>window.close ()</script></body></html>");

} else {
	if (($Parameters) && (!isset ($UploadFile))) {
		list ($UploadFile, $Width, $Height, $AutoPlay, $AutoBuffer, $BufferTime) = explode ('|', $Parameters);
	}
	if (strpos ($UploadFile, '/') !== false) {
		$PathToFile = $UploadFile;
		$UploadFile = '';
	}
?>
<form enctype="multipart/form-data" action="pagebuilder-container-settings.php" method="post">

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td colspan="5">
	Select File (.flv):<br />
	<?
	echo RetrieveUploadBox ("UploadFile", $UploadFile, 30, CONTENT_URL, "document.forms[0]");
	?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>-- or --</b> <br />
	Enter Filename in <? echo CONTENT_URL; ?>:<br />
	<input type="text" name="PathToFile" value="<? echo $PathToFile; ?>" size="30" />
	</td>
</tr>
<tr>
	<td colspan="5"><hr size="1" /></td>
</tr>
<tr>
	<td>Width: </td>
	<td><input type="text" name="Width" size="3" value="<? echo $Width; ?>" /> </td>
	<td colspan="3" rowspan="2">
	<input type="checkbox" name="AutoPlay" value="1"<? echo ($AutoPlay == 'true' ? " checked=\"checked\"" : ""); ?> /> Automatic Play<br />
	<input type="checkbox" name="AutoBuffer" value="1"<? echo ($AutoBuffer == 'true' ? " checked=\"checked\"" : ""); ?> /> Automatic Buffer
	</td>
</tr>
<tr>
	<td>Height: </td>
	<td><input type="text" name="Height" size="3" value="<? echo $Height; ?>" /> </td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<td colspan="3" align="right">

	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>for</td>
		<td>&nbsp;<input type="text" name="BufferTime" size="3" value="<? echo ($BufferTime == '' ? 15 : $BufferTime); ?>" />&nbsp;</td>
		<td>secs&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	</tr>
	</table>

	</td>
</tr>
<tr>
	<td colspan="5"><hr size="1" /></td>
</tr>
</table>
<p align="right"><input type="submit" value="Save" />&nbsp;&nbsp;<input type="button" value="Close Window" onclick="window.close ()" /></p>
<?
echo RetrieveHiddenField ("IncludeFile", $TIncludeFile);
echo RetrieveHiddenField ("Container_ID", $Container_ID);
echo RetrieveHiddenField ("Session_ID", $Session_ID);
?>
<input type="hidden" name="MAX_FILE_SIZE" value="20971520" />
</form>
<?
} // end if
?>
