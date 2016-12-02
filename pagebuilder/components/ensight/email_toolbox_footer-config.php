<?
global $Address;
global $Session_ID;

//--- Setup
$TIncludeFile = "ensight/email_toolbox_footer-config.php";
$Container_ID = $Container['Container_ID'];

if ($Address) {
	UpdateContainerParameters ($Container_ID, $Address); die ("<html><body><script>window.close ()</script></body></html>");
} else {

?>
<form action="pagebuilder-container-settings.php" method="post">
Company Address:<br />
<input type="text" name="Address" value="<? echo $Parameters; ?>" size="29" /><br /><br />
<p align="right"><input type="submit" value="Save" />&nbsp;&nbsp;<input type="button" value="Close Window" onclick="window.close ()" /></p>
<?
echo RetrieveHiddenField ("IncludeFile", $TIncludeFile);
echo RetrieveHiddenField ("Container_ID", $Container_ID);
echo RetrieveHiddenField ("Session_ID", $Session_ID);
?>
</form>
<?
} // end if
?>
