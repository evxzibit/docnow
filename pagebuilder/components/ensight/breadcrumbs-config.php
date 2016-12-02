<?
include_once ("modules/utils.php");
include_once ("modules/profile.php");
include_once ("modules/session.php");
include_once ("modules/catalog.php");

global $BaseCategory;
global $Session_ID;

//--- Setup
$TIncludeFile = "ensight/breadcrumbs-config.php";
$Container_ID = $Container['Container_ID'];

if ($Session_ID) {
	$Profile_ID = LocateSession ($Session_ID);
}

if (isset ($BaseCategory)) {
	UpdateContainerParameters ($Container_ID, $BaseCategory); die ("<html><body><script>window.close ()</script></body></html>");
} else {
?>
<form action="pagebuilder-container-settings.php" method="post">

<table border="0" cellpadding="0" cellspacing="0">
<tr valign="top">
	<td><input type="radio" name="Source"<? if ($Parameters != -1) { echo " checked=\"checked\""; } ?> /></td>
	<td style="padding-top: 2px">
	Select Folder:<br />
	<?
	$Path = RetrieveCatalogCategoriesTruePath ($Profile_ID, None, $Parameters, CATALOG_ROOT, ALLOW_ITEM_MGT, ACCESS_ON);
	?>
	<input type="text" name="CategoryDescription" value="<? echo RetrieveCatalogCategoriesTextPath (None, $Profile_ID, $Parameters, $Session_ID, $Path); ?>" size="18" readonly="readonly" />&nbsp;<input type="button" Value="Browse" onclick="JavaScript:window.open ('../admin/CMM-threaded-small.php?Category_ID=' + document.forms[0].BaseCategory.value + '&Session_ID=<? echo $Session_ID; ?>', 'popup<? echo GetRandom (1000); ?>', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,alwaysRaised=yes,titlebar=no,width=325,height=330');" accesskey="F" /><input type="hidden" name="BaseCategory" value="<? echo $Parameters; ?>" /><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>or</b><br />
	</td>
</tr>
<tr valign="top">
	<td><input type="radio" name="Source"<? if ($Parameters == -1) { echo " checked=\"checked\""; } ?> onclick="this.form.BaseCategory.value = '-1'; this.form.CategoryDescription.value = ''" /></td>
	<td style="padding-top: 2px">
	Folder Selected by User
	</td>
</tr>
</table>
<br />
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