<?
include_once ("modules/db-functions.php");

global $URL;
global $Session_ID;

//--- Setup
$TIncludeFile = "ensight/rss-config.php";
$Container_ID = $Container['Container_ID'];

if ($URL) {

	//--- Add to cache?
	if (!IsURLCached ($URL)) {
		chdir (ADMIN_FILES); exec (PHP_STANDALONE_PREFIX." ".ADMIN_FILES."/cache.php --download \"$URL\" ".PHP_STANDALONE_SUFFIX, $Result, $Status);
	}

	UpdateContainerParameters ($Container_ID, $URL); die ("<html><body><script>window.close ()</script></body></html>");

} else {

?>
<form action="pagebuilder-container-settings.php" method="post">
Enter URL:<br />
<input type="text" name="URL" value="<? echo $Parameters; ?>" size="29" /><br /><br />
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
