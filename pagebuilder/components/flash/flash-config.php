<?
include_once ("modules/connect.php");
include_once ("modules/utils.php");

global $UploadFile, $UploadFile_name, $UploadFile_current, $Width, $Height, $Quality, $BGColor;

//--- Setup
$TIncludeFile = "flash/flash-config.php";
$Container_ID = $Container['Container_ID'];

if (isset ($UploadFile)) {

	CopyUniqueFilename ($UploadFile, $UploadFile_name, CONTENT_FILES, $UploadFile_current);

	//--- Save to database or into form field
	if ($Container_ID != 0) {
		UpdateContainerParameters ($Container_ID, "$UploadFile_name|$Width|$Height|$Quality|$BGColor"); die ("<html><body><script>window.close ()</script></body></html>");
	} else {
		die ("<html><body><script>if (opener.document.forms[0].Parameters) { opener.document.forms[0].Parameters.value = '$UploadFile_name|$Width|$Height|$Quality|$BGColor'; } window.close ()</script></body></html>");
	}

} else {
	if (($Parameters) && (!isset ($UploadFile))) {
		list ($UploadFile, $Width, $Height, $Quality, $BGColor) = explode ('|', $Parameters);
	}
?>
<FORM ENCTYPE="multipart/form-data" ACTION="pagebuilder-container-settings.php" METHOD="post">

<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH="100%">
<TR>
<TD colspan="4">
Select File:<br>
<?
echo RetrieveUploadBox ("UploadFile", $UploadFile, 26, CONTENT_URL, "document.forms[0]");
?><P>
</TD>
</TR>
<TR>
<TD>Width: </TD><TD><INPUT type="text" name="Width" size="3" value="<? echo $Width; ?>"> </TD>
<TD>Quality: </TD><TD>
<SELECT name="Quality">
<?
echo RetrieveDropDownOption ("high", $Quality, "high");
echo RetrieveDropDownOption ("medium", $Quality, "medium");
echo RetrieveDropDownOption ("low", $Quality, "low");
?>
</SELECT>
</TD>
</TR>
<TR>
<TD>Height: </TD><TD><INPUT type="text" name="Height" size="3" value="<? echo $Height; ?>"> </TD>
<TD>Background: </TD><TD><INPUT type="text" name="BGColor" size="5" value="<? echo $BGColor; ?>"></TD>
</TR>
</TABLE>
<br>
<P ALIGN="right"><INPUT TYPE="SUBMIT" VALUE="Save">&nbsp;&nbsp;<INPUT TYPE="BUTTON" VALUE="Close Window" onClick="window.close ()"></P>
<?
echo RetrieveHiddenField ("IncludeFile", $TIncludeFile);
echo RetrieveHiddenField ("Container_ID", $Container_ID);
echo RetrieveHiddenField ("Session_ID", $Session_ID);
?>
<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="1024000">
</FORM>
<?
} // end if
?>
